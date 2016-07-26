<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 公共用户model
 *
 * @author 刘志超
 * @date 2015-04-16
 */
class Common_user_model extends CI_Model{
	function __construct() {
		parent::__construct ();
		$this->load->database ();
	}
	
	/**
	 * 保存用户cookie信息
	 * @author 刘志超
	 * @param int $uid 用户编号
	 * @param int $phone 手机号
	 * @param string $uname 用户名
	 * @param int $utype 用户类型
	 * @param string $usalt 加密字符串
	 * @param bool $is_remember 保存登录账号
	 */
	public function save_cookie($uid, $phone, $uname = '', $utype = 1, $usalt, $is_remember) {
		// 保存cookie
		$cookie_crypt_key = KEY_COOKIE_CRYPT;
		$cookie_crypt_iv = KEY_COOKIE_CRYPT_IV;
		$this->load->library ( 'Dl_crypt', array (
				'key' => $cookie_crypt_key,
				'iv' => $cookie_crypt_iv
		) );
			
		$uinfo = array ();
		$uinfo['Phone'] = $phone;
		$uinfo['userType'] = $utype;
		$uinfo['auth'] = $this->dl_crypt->encode ( $uid . '|' . $utype . '|' . $usalt );
		$uinfo['userName'] = $uname;
		$cookie = implode ( '|', $uinfo );
		// 账号
		setcookie ( $this->config->item('cookie_account') , $phone, $is_remember ? (time () + 86400 * 30) : time () - 360, '/', $this->config->item('cookie_domain'), NULL, FALSE );
		// 登录的加密信息
		setcookie ( $this->config->item('cookie_name'), $cookie, 0, '/', $this->config->item('cookie_domain'), NULL, FALSE );
	}
	
	/**
	 * 修改用户统计表
	 * @param int $uid 用户id
	 * @param string $uname 用户名称(无用)
	 * @param array $price 钱
	 * @param boolean $is_today 是否今天
	 * @param boolean $is_complete 是否完成任务
	 * @author 刘志超
	 * @date 2015-04-27
	 * 
	 */
	public function update_user_stat($uid,$uname = '',$price,$is_today = FALSE,$is_complete = FALSE){
		$uid = intval($uid);
		$this->db->where('uid',$uid);
		$this->db->set('today_income','today_income + '.$price,FALSE);
		$this->db->set('total_income','total_income + '.$price , FALSE);
		$this->db->set('remain_income','remain_income + '.$price , FALSE);
		if($is_complete) $this->db->set('total_task_num','total_task_num + 1',FALSE);
		$this->db->update('user_statistics');
		if($this->db->affected_rows() != 1) return FALSE;
		return TRUE;
	}
	
	/**
	 * 修改用户统计表 --------单独写这个方法主要用于更新二级分成更新统计表---------
	 * @param int $uid 用户id
	 * @param string $uname 用户名称(无用)
	 * @param array $price 钱
	 * @param boolean $is_today 是否今天
	 * @param boolean $is_complete 是否完成任务
	 * @author 陆学锦
	 * @date 2015-06-01
	 *
	 */
	public function update_user_stat_into($uid,$uname = '',$price,$is_today = FALSE,$is_complete = FALSE,$invite_money = FALSE,$invite_invite_money=FALSE){
		$uid = intval($uid);
		$this->db->where('uid',$uid);
		$this->db->set('today_income','today_income + '.$price,FALSE);
		$this->db->set('total_income','total_income + '.$price , FALSE);
		$this->db->set('remain_income','remain_income + '.$price , FALSE);
		if($invite_money ==TRUE && $invite_invite_money==TRUE){
			$this->db->set('invite_invite_money','invite_invite_money + '.$price , FALSE);
			$this->db->set('today_invite_money','today_invite_money + '.$price , FALSE);
		}
		elseif ($invite_money ==TRUE){
			$this->db->set('invite_money','invite_money + '.$price , FALSE);
			$this->db->set('today_invite_money','today_invite_money + '.$price , FALSE);
		}
		
		if($is_complete) $this->db->set('total_task_num','total_task_num + 1',FALSE);
		$this->db->update('user_statistics',array('last_income_time'=>time()));

		if($this->db->affected_rows() != 1) return FALSE;
		return TRUE;
	}
	
	/**
	 * 修改用户密码
	 * @param $uid 用户id
	 * @param $password 新的用户密码
	 * @author 刘志超
	 * @date 2015-05-04
	 */
	public function update_user_password($uid,$password){
		$uid = intval($uid);
		$this->db->where('uid',$uid)->update('user',array('password'=>$password));
		if($this->db->affected_rows() != 1){
			return FALSE;
		}
		return TRUE;
	}
	
	
	/**
	 * 检查填写手机号码和邀请码是否正确
	 * @param int $phone	手机号码
	 * @param int $code		邀请码
	 * 
	 * @author 陆学锦
	 * @date 2015-05-27
	 */
	public function check_phone_invicode($phone,$code){
		
		$phone =  trim(strval($phone));
		$code = intval($code);
		$this->db->select('phone,invitation_code,uid,openid');
		$this->db->from('user');	
		return $this->db->where(array('phone'=>$phone,'invitation_code'=>$code))->get()->row_array();
	}
	
	public function exist_openid($openid){
		$phone =  trim(strval($phone));
		$this->db->select('phone,invitation_code,uid,openid');
		$this->db->from('user');
		$ret =  $this->db->where(array('openid'=>$openid))->get()->row_array();
		if($ret){
			return TRUE;
		}
		return FALSE;
	}
	
	public function find_openid($openid){
		$phone =  trim(strval($phone));
		$this->db->select('phone,invitation_code,uid,openid');
		$this->db->from('user');
		$ret =  $this->db->where(array('openid'=>$openid))->get()->row_array();
		return $ret;
	}
	
	/**
	 * 更新openid
	 * 
	 * @param int $phone		手机号码
	 * @param unknown $openid   openid
	 * @return boolean
	 */
	public function update_user_openid($phone,$openid){
		$phone =  trim(strval($phone));
		$this->db->where('phone',$phone)->update('user',array('openid'=>$openid));
		if($this->db->affected_rows() != 1){
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * 插入用户获取金额的日志表
	 * @param array $data 插入数据
	 * @param int $affect_rows 影响行数
	 * @author 陆学锦
	 * @date 2015-05-28
	 */
	public function user_account_log($data){
		if(!is_array($data) || empty($data)) return FALSE;
		$this->db->insert('user_account_log_stat',$data);
		if($this->db->affected_rows() != 1) return FALSE;
		return TRUE;
	}
	
	public function user_account_log1(){
		
		return TRUE;
	}
	
	/**
	 * 修改用户统计表 --------单独写这个方法主要用于更新二级分成更新统计表---------
	 * @param int $uid 用户id
	 * @param array $price 钱
	 * @param boolean $is_complete 是否完成任务
	 * @param boolean $is_invite_money 是否有一级分成
	 * @param boolean $is_invite_invite_money 是否有二级分成
	 * @param boolean $is_task_money 是否属于任务的金钱
	 * @param boolean $is_exchange_money 是否属于兑换成功的钱
	 * @author 刘志超
	 * @date 2015-06-10
	 *
	 */
	public function new_update_user_stat($uinfo,$price,$is_complete = FALSE,$is_invite_money = FALSE,$is_invite_invite_money = FALSE,$is_task_money = FALSE,$is_exchange_money = FALSE){
		if(!is_array($uinfo) || empty($uinfo)) return  FALSE;
		//初始化分成今日收入
		$today_invite_money = 0;
		$all_price = $price;
		
		$this->db->where('uid',$uinfo['uid']);
		$this->db->set('today_income','today_income + '.$all_price,FALSE);
		$this->db->set('total_income','total_income + '.$all_price , FALSE);
		$this->db->set('remain_income','remain_income + '.$all_price , FALSE);
		//二级机制加钱处理
		// 		if ($is_invite_money ==TRUE){
		// 			$this->db->set('invite_money','invite_money + '.$invite_money , FALSE);
		// 			if($is_invite_invite_money === TRUE){
		// 				$this->db->set('invite_invite_money','invite_invite_money + '.$invite_invite_money , FALSE);
		// 			}
		// 			$this->db->set('today_invite_money','today_invite_money + '.$today_invite_money , FALSE);
		// 		}
		
		if($is_complete) $this->db->set('total_task_num','total_task_num + 1',FALSE);
		if($is_task_money) $this->db->set('task_money','task_money + '.$price,FALSE);
		if($is_exchange_money) $this->db->set('exchange_money','exchange_money + '.$price,FALSE);
		$this->db->update('user_statistics',array('last_income_time'=>time()));
		
		if($this->db->affected_rows() != 1) return FALSE;
		
		//判断是否有分成收入
		if($is_invite_money) {
			$all_price = $price * ONE_SCALE;
			$invite_money = $price * ONE_SCALE;
			$today_invite_money = $invite_money;
						
			//二级机制加钱处理(一级处理)
			if ($is_invite_money ==TRUE){
				$this->db->where('uid',$uinfo['invite_uid']);
				$this->db->set('today_income','today_income + '.$all_price,FALSE);
				$this->db->set('total_income','total_income + '.$all_price , FALSE);
				$this->db->set('remain_income','remain_income + '.$all_price , FALSE);
				$this->db->set('invite_money','invite_money + '.$invite_money , FALSE);
				$this->db->set('today_invite_money','today_invite_money + '.$today_invite_money , FALSE);
				
				if($is_complete) $this->db->set('total_task_num','total_task_num + 1',FALSE);
				//if($is_task_money) $this->db->set('task_money','task_money + '.$all_price,FALSE);
				//if($is_exchange_money) $this->db->set('exchange_money','exchange_money + '.$all_price,FALSE);
				$this->db->update('user_statistics',array('last_income_time'=>time()));					
				if($this->db->affected_rows() != 1) return FALSE;
			}

			if($is_invite_invite_money){
				$all_price = $price * TWO_SCALE;
				$invite_invite_money = $price * TWO_SCALE;
				$today_invite_money = $invite_invite_money;
								
				//二级机制加钱处理(二级处理)
				if ($is_invite_money ==TRUE && $is_invite_invite_money === TRUE){					
					
						$this->db->where('uid',$uinfo['invite_invite_uid']);
						$this->db->set('today_income','today_income + '.$all_price,FALSE);
						$this->db->set('total_income','total_income + '.$all_price , FALSE);
						$this->db->set('remain_income','remain_income + '.$all_price , FALSE);
						$this->db->set('invite_invite_money','invite_invite_money + '.$invite_invite_money , FALSE);
						$this->db->set('today_invite_money','today_invite_money + '.$today_invite_money , FALSE);
						
						if($is_complete) $this->db->set('total_task_num','total_task_num + 1',FALSE);
						//if($is_task_money) $this->db->set('task_money','task_money + '.$all_price,FALSE);
						//if($is_exchange_money) $this->db->set('exchange_money','exchange_money + '.$all_price,FALSE);
						$this->db->update('user_statistics',array('last_income_time'=>time()));
						
						if($this->db->affected_rows() != 1) return FALSE;
					
					
				}				
				
			} 
		}

		return TRUE;
	}

}