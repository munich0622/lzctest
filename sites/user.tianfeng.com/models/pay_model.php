<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
/**
 * 支付模型
 *
 * @author 刘志超
 * @date 2015-04-21
 */
class Pay_model extends CI_Model{
    
    //支付状态
    public  $payed    = 1;
    public  $no_payed = 0;
	public function __construct(){
		parent::__construct();
		$this->load->database();
	
	}
	
	
	/**
	 * 获取支付信息
	 */
	public function pay_info($uid,$type){
	    $uid = (int)$uid;
	    $type = (int)$type;
	    if($uid <= 0 || $type <= 0){
	        return false;
	    }
	    $where = array(
	        'pay_uid' => $uid,
	        'type'    => $type,
	        'status'  => 0
 	    );
	    
	    return $this->db->get_where('pay',$where)->row_array();
	}
	
	/**
	 * 获取支付信息
	 */
	public function pay_info_to_id($pay_id,$uid){
	    $uid = (int)$uid;
	    $pay_id = (int)$pay_id;
	    if($uid <= 0 || $pay_id <= 0){
	        return false;
	    }
	    $where = array(
	        'id'      => $pay_id,
	        'pay_uid' => $uid,
	    );
	     
	    return $this->db->get_where('pay',$where)->row_array();
	}
	
	
	/**
	 * 微信支付结果回调
	 */
	public function pay_response($out_trade_no,$transaction_id){
	    $where = array('myself_trade_no'=>$out_trade_no);
	    //获取支付类型
	    $pay_info = $this->db->get_where('pay',$where)->row_array();
	    if($pay_info['status'] == 1){
	        return TRUE;
	    }
	    //判断状态是否需要推荐两个人才激活
	    //获取推荐人所在的盘子（盘子不同 金额基数不同）
	    $tj_uid = $pay_info['receive_uid'];
	    $sql = " SELECT * FROM tf_user WHERE uid = {$tj_uid}";
	    $tj_info = $this->db->query($sql)->row_array();
	    $space  = $tj_info['space'];
	    $status = $tj_info['status'];
	    $level  = $tj_info['level'];
	    
	    $sql = " SELECT * FROM tf_user WHERE uid = {$pay_info['pay_uid']}";
	    $been_info = $this->db->query($sql)->row_array();
	    
	    
	    //开启事务
	    $this->db->trans_begin();
	    //支付类型为1的话是注册用户支付金额
	    if($pay_info['type'] == 1){
	        //如果是第一个盘子的，要把用户分配到推荐人下面，如果推荐人下面满了，则寻找缺少一个下级的，否则寻找没有下级的
	        if($space == 1){
	            $real_uid = $this->_deal_relate_user($pay_info,$space);
	            if(!$real_uid){
	                $this->db->trans_rollback();
	                return false;
	            }
	        }else{
	            //第二，三个盘子的话把他的状态改成2
	            $res = $this->_update_user_status($pay_info,$space);
	            if(!$res){
	                $this->db->trans_rollback();
	                return false;
	            }
	        }
	        
	       //判断推荐人的状态 如果状态不为1 则是
	       if($status == 2){
	            $this->db->where('uid',$tj_uid)->update('user',array('status'=>3));
	            if($this->db->affected_rows() != 1){
	                $this->db->trans_rollback();
	                return false;
	            }
	       }elseif($status == 3){
	           $this->db->where('uid',$tj_uid)->update('user',array('status'=>1,'frozen_time'=>0));
	           if($this->db->affected_rows() != 1){
	               $this->db->trans_rollback();
	               return false;
	           }
	           
	           $real_uid = $this->_get_tj_uid($space);
	           $data = array(
	               'puid'  => $real_uid ,
	               'uid'   => $tj_uid ,
	               'space' => $space,
	               'time'  => time()
	           );
	           $this->db->insert('relate',$data);
	           if($this->db->affected_rows() != 1){
	               $this->db->trans_rollback();
	               return false;
	           }
	       }
	       
           //查看是否属于最后一个红包如果是的话则开启冻结时间
           if($real_uid > 0){
               $res = $this->_is_last_redbag($real_uid,$been_info);
               if(!$res){
                   $this->db->trans_rollback();
                   return false;
               }
           }
	    }elseif($pay_info['type'] == 2){
	        //升级
	        if($been_info['level'] < 3){
	            $sql = " UPDATE tf_user SET level = level + 1,frozen_time = 0 WHERE uid = {$been_info['uid']}";
	            $this->db->query($sql);
	            if($this->db->affected_rows() != 1){
	                $this->db->trans_rollback();
	                return false;
	            }
	            $cur_level = ++$been_info['level'];
	            if($tj_uid > 0 && $cur_level == 2){
	                $res = $this->_is_last_redbag_upgrade($been_info,$cur_level,$tj_uid);
	            }
	        }
	        
	    }
	    
	    $data = array(
	        'other_trade_no' => $transaction_id,
	        'status' => $this->payed,
	    );
	    $this->db->where('myself_trade_no', $out_trade_no);
	    $this->db->update('pay', $data);
	    
	    if($this->db->affected_rows() != 1){
	        $this->db->trans_rollback();
	        return false;
	    }
	    
	    $this->db->trans_commit();
	    return true;
	}
	
	/**
	 * 判断是否是最后一个红包(升级)
	 * $been_info 升级用户的信息
	 * $cur_level 升级后的等级
	 * $receive_uid 收钱的uid
	 */
	private function _is_last_redbag_upgrade($been_info,$cur_level,$receive_uid){
	    if(empty($been_info) || !is_array($been_info)){
	        return false;
	    }
	    
	    $sql = " SELECT uid FROM tf_relate WHERE puid = {$receive_uid} AND space = {$been_info['space']}";
	    
	    $temp = $this->db->query($sql)->result_array();
	    if(count($temp) != 2){
	        return true;
	    }
	    
	    $uids_arr = array_column($temp, 'uid');
	    $sql  = " SELECT tu.uid,tu.company_id,tu.level FROM tf_relate AS tr LEFT JOIN tf_user AS tu 
	              ON tr.uid = tu.uid 
	              WHERE tr.puid in (".implode(',',$uids_arr).") AND tr.space = {$been_info['space']} AND (tu.level = {$cur_level} || tu.company_id > 0 ) ";
	    $temp2 = $this->db->query($sql)->result_array();
	    
	    if(count($temp2) != 4){
	        return true;
	    }
	    
	    $this->db->where(array('uid'=>$receive_uid))->update('user',array('frozen_time'=>time()));
	    
	    if($this->db->affected_rows() != 1){
	        return false;
	    }
	    
	    return true;
	    
	}
	/**
	 * 判断是否是最后一个红包(注册)
	 * @param int $puid 推荐人的用户id
	 * @param array $been_info 升级用户的信息
	 */
	private function _is_last_redbag($puid,$been_info){
	    $puid = intval($puid);
	    $sql = " SELECT tu.company_id FROM tf_relate AS tr
        	     LEFT JOIN tf_user AS tu ON tr.puid  = tu.uid
        	     WHERE tr.puid = {$puid} AND tu.status = 1 AND tr.space = {$been_info['space']} ";
	    $res = $this->db->query($sql)->result_array();
	    
	    if(count($res) == 2){
	        //修改冻结时间为2天后
	        $time = time()+86400*2;
	        $sql = " UPDATE tf_user SET frozen_time = {$time} WHERE uid = {$puid}";
	        $this->db->query($sql);
	        if($this->db->affected_rows() != 1){
	            return false;
	        }
	    }
	    
	    return true;
	    
	}
	
	/**
	 * 处理用户上下级关系（要把用户分配到推荐人下面如果推荐人下面满了2个人 则推荐到该分支下面，如果该分支买）
	 */
	private function _deal_relate_user($pay_info,$space = 1){
	    //被推荐人的uid
	    $been_uid   = (int)$pay_info['pay_uid'];
	    //推荐人uid
	    $tj_uid     = (int)$pay_info['receive_uid'];
	    
	    //查找推荐人下面是否已经满人了
	    $relate_uid = $this->_digui_get_relate_uid($tj_uid,$space);
	    
	    $data = array(
	        'puid'  => $relate_uid ,
	        'uid'   => $been_uid ,
	        'space' => $space,
	        'time'  => time()
	    );
	    
	    $this->db->insert('relate', $data);
	    if($this->db->affected_rows() != 1){
	        return false;
	    }
	    return $relate_uid;
	}
	
	/**
	 * 获取上级人的uid(根据规则获取)
	 * @param int $tj_uid 推荐人uid
	 * @param int $space 所在盘子
	 */
	private function _digui_get_relate_uid($tj_uid,$space = 1){
	    //判断推荐人下面是否有两个下级
	    $sql = " SELECT tu.uid FROM tf_relate AS tr LEFT JOIN tf_user AS tu
	             ON tr.uid = tu.uid WHERE tr.puid in ({$tj_uid}) AND tu.status = 1 AND tu.space = {$space} ";
	    $result = $this->db->query($sql)->result_array();
	    if(count($result) < 2){
	        return $tj_uid;
	    }
	    
	    //如果已经有两个下级了 则寻找一个下级的
	    $sql = " SELECT tr.puid FROM tf_relate AS tr 
	             LEFT JOIN tf_user AS tu ON tr.uid = tu.uid
	             WHERE tu.`status` = 1 AND tu.space = {$space} 
	             GROUP BY tr.puid HAVING count(1) = 1 LIMIT 1 ";
	    $result = $this->db->query($sql)->row_array();
	    if(empty($result)){
	        //如果已经有两个下级了 则寻找一个下级的
	        $sql = " SELECT tr.puid FROM tf_relate AS tr
        	        LEFT JOIN tf_user AS tu ON tr.uid = tu.uid
        	        WHERE tu.`status` = 1 AND tu.space = {$space}
        	        GROUP BY tr.puid HAVING count(1) < 2 LIMIT 1 ";
	        $result = $this->db->query($sql)->row_array();
	    }
	    return $result['puid'];
	    
	}
	
	/**
	 * 获取数组下的某个符合条件的uid作为上下级关系
	 */
	private function _get_tj_uid($space = 1){
	    
	    //如果已经有两个下级了 则寻找一个下级的
	    $sql = " SELECT tr.puid FROM tf_relate AS tr 
	             LEFT JOIN tf_user AS tu ON tr.uid = tu.uid
	             WHERE tu.`status` = 1 AND tu.space = {$space} 
	             GROUP BY tr.puid HAVING count(1) = 1 LIMIT 1 ";
	    $result = $this->db->query($sql)->row_array();
	    if(empty($result)){
	        //如果已经有两个下级了 则寻找一个下级的
	        $sql = " SELECT tr.puid FROM tf_relate AS tr
        	        LEFT JOIN tf_user AS tu ON tr.uid = tu.uid
        	        WHERE tu.`status` = 1 AND tu.space = {$space}
        	        GROUP BY tr.puid HAVING count(1) < 2 LIMIT 1 ";
	        $result = $this->db->query($sql)->row_array();
	    }
	    return $result['puid'];
	}
	
	/**
	 * 支付成功之后修改用户状态
	 * 
	 */
	private function _update_user_status($pay_info,$space){
	    $been_uid   = (int)$pay_info['pay_uid'];
	    $status = $space == 1 ? 1 : 2;
	    $sql = ' UPDATE tf_user SET status = '.$status.' WHERE uid = '.$been_uid;
	    $this->db->query($sql);
	    if($this->db->affected_rows() != 1){
	        return false;
	    }
	    
	    return true;
	}
	
	public function test($content,$sss){
	    $sql = " INSERT INTO `tttt`(text,xxx) VALUES ('{$content}','{$sss}')";
	    $this->db->query($sql);
	}
	
}

?>
