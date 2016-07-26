<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
/**
 * 用户任务模型
 *
 * @author 刘志超
 * @date 2015-04-21
 */
class User_model extends CI_Model{
	public function __construct(){
		parent::__construct();
		$this->load->database();
	
	}
	
	//用户状态 0 未激活  1激活 2需要邀请一个人 3需要邀请两个人
	public $u_status_not_active  = 0;
	public $u_status_active      = 1;
	public $u_status_invited_one = 2;
	public $u_status_invited_two = 3;
	
	public $constants_pay_money = array(
	    '1' => array( 
	        '1' => PLATE_ONE_GRADE_ONE,
	        '2' => PLATE_ONE_GRADE_TWO,
	        '3' => PLATE_ONE_GRADE_THREE,
	    ),
	    '2' => array(
	        '1' => PLATE_TWO_GRADE_ONE,
	        '2' => PLATE_TWO_GRADE_TWO,
	        '3' => PLATE_TWO_GRADE_THREE,
	    ),
	    '3' => array(
	        '1' => PLATE_THREE_GRADE_ONE,
	        '2' => PLATE_THREE_GRADE_TWO,
	        '3' => PLATE_THREE_GRADE_THREE,
	    )
	);
	

	/**
	 * 获取用户信息
	 */
	public function get_user($where){
	    return $this->db->get_where('user',$where)->row_array();
	}
	
	/**
	 * 获取用户任务信息
	 * @author 刘志超
	 * @date 2015-06-15
	 */
	public function bank_list(){
		return $this->db->get_where('bank')->result_array();
	}
	
	/**
	 * 修改用户信息
	 */
	public function update_user_info($uid,$data){
	    $uid = intval($uid);
	    if($uid<= 0 || empty($data) || !is_array($data)){
	        return false;
	    }
	    
        return $this->db->where('uid', $uid)->update('user',$data);
	}
	
	/**
	 * 获取bank
	 */
	public function get_bank($bank_id){
	    $bank_id = (int)$bank_id;
	    if($bank_id <= 0){
	        return false;
	    }
	    
	    return $this->db->get_where('bank',array('id'=>$bank_id))->row_array();
	}

	/**
	 * 获取邀请信息
	 */
	public function tf_invited($uid){
	    $uid = (int)$uid;
	    if($uid <= 0){
	        return false;
	    }
	    
	    return $this->db->get_where('invited',array('be_invited_uid'=>$uid))->row_array();
	}
	
	/**
	 * 获取用户资料和邀请人资料
	 * 
	 */
	public function get_user_and_invited_user($uid){
	    $uid = (int)$uid;
	    if($uid <= 0){
	        return false;
	    } 
	    
	   $sql = " SELECT u.*,uu.uname AS uu_name,uu.phone AS uu_phone,uu.weixin_name AS uu_weixin_name FROM tf_user as u 
	            LEFT JOIN tf_user as uu ON u.tj_uid = uu.uid WHERE u.uid = {$uid}";
	   
	   return $this->db->query($sql)->row_array();
	}
	
	
	/**
	 * 判断是否符合升级条件
	 * @param int $puid 当前升级用户的uid
	 * @param int $level 要升的等级
	 * @param int $space 当前升级用户所在空间
	 */
	public function upgrade_require($uid,$level,$space){
	     
	     $level = (int)$level;
	     $uid  = (int)$uid;
	     $space  = (int)$space;
	     if($level != 2 || $level != 3 || empty($uid) || empty($space)){
	         return false;
	     }
	     
// 	     //获取当前uid的puid
// 	     $sql = " SELECT * FROM tf_relate WHERE uid = {$uid} AND space = {$space}";
// 	     $relate = $this->db->query($sql)->row_array();
// 	     if(!$relate){
// 	         return false;
// 	     }
// 	     $puid = $relate['puid'];
	     
	     
	     if($level == 2){
// 	         $sql = " SELECT tu.company_id,tu.uid FROM tf_relate AS tr LEFT JOIN tf_user AS tu  ON tr.uid = tu.uid
// 	         WHERE tr.puid = {$puid} AND tr.space = {$space} AND tu.status = ".$this->u_status_active;
	         $sql = " SELECT * FROM tf_relate AS tr LEFT JOIN tf_user AS tu ON tr.uid = tu.uid 
	                  WHERE tr.puid = {$uid} AND tr.space = {$space} AND tu.status = ".$this->u_status_active;
	         
	         $arr = $this->db->query($sql)->result_array();
	         if(count($arr) < 2){
	             return false;
	         }
	         
// 	         foreach ($arr as $key=>$val){
// 	             if($val['company_id'] > 0){
// 	                 unset($arr[$key]);
// 	             }
// 	         }
	         
// 	         $new_arr = array_column($arr, 'uid');
	         
// 	         //判断支付类型 如果升2级 则要求“注册”类型，如果升3级则要求“下级支付上级费用”类型
// 	         $sql = ' SELECT count(1) as count FROM tf_pay WHERE status = 1 AND type= 1 AND pay_uid IN ('.implode(',', $new_arr).')';
// 	         $res = $this->db->query($sql)->row_array();
// 	         if($res['count'] != count($new_arr)){
// 	             return false;
// 	         }
	         
// 	         return true;
	     }else{
	         $sql = " SELECT * FROM tf_relate AS tr LEFT JOIN tf_user AS tu ON tr.uid = tu.uid 
	                  WHERE tr.puid = {$uid} AND tr.space = {$space} AND tu.status = ".$this->u_status_active;
	         $arr = $this->db->query()->result_array($sql);
	         
	         if(count($arr) < 2){
	             return false;
	         }
	         
	         $new_arr = array_column($arr, 'uid');
	         
	         $sql = " SELECT tu.company_id,tu.uid,tr.level FROM tf_relate AS tr LEFT JOIN tf_user AS tu  ON tr.uid = tu.uid
	         WHERE tr.puid in ( ".implode(',', $new_arr).") AND tr.space = {$space} AND tu.status = ".$this->u_status_active;
	         
	         $arr2 = $this->db->query($sql)->result_array();
	         
	         $i = 0;
	         foreach ($arr2 as $key=>$val){
	             if($val['level'] == 2 || $val['company_id'] > 0){
	                 $i++;
	             }
	         }
	         
	         if($i < 4){
	             return false;
	         }
	         
	         
// 	         $require = 4;
// 	         foreach ($arr2 as $key=>$val){
// 	             if($val['company_id'] > 0){
// 	                 $require -- ;
// 	                 unset($arr2[$key]);
// 	             }
// 	         }
	         
// 	         $new_arr2 = array_column($arr2, 'uid');
	         
// 	         $sql = ' SELECT count(1) as count FROM tf_pay WHERE receive_uid = '.$puid.' status = 1 AND type = 2 AND pay_uid IN ('.implode(',', $new_arr2).')';
// 	         $res = $this->db->query($sql)->row_array();
// 	         if($res['count'] != $require){
// 	             return false;
// 	         }
	     }
	     
	     return true;
	}
	
	
	/**
	 * 获取升级父亲节点
	 */
	public function get_parents($uid,$level,$space){
	    $level = intval($level);
	    $uid   = intval($uid);
	    $space = intval($space);
	    if($level != 2 || $level != 3 || empty($uid) || empty($space)){
	        return false;
	    }
	    
	    $cur_uid = $uid;
	    for ($i = 0 ;$i < $level;$i++){
	        if(!empty($cur_uid)){
	            $cur_uid = $this->_get_parent($cur_uid,$space);
	        }
	    }
	   if(empty($cur_uid)){
	       return 0;
	   }
	   return $cur_uid;
	}
	
	/*
	 * 递归寻找父uid
	 */
	private function _get_parent($uid,$space){
	    $uid   = intval($uid);
	    $space = intval($space);
	    $sql = " SELECT puid FROM tf_relate WHERE uid = {$uid} AND space = {$space}"; 
	    
	    $res = $this->db->query($sql)->row_array();
	    
	    if(empty($res)){
	        return 0;
	    }
	    
	    return $res['puid'];
	}
	
}

?>
