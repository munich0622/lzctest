<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg extends MY_Controller {
	//请求参数
	private $uid; // 用户uid
	private $phone; // 输入的用户名
	private $source; // 注册来源
	private $active_code; // 激活码
	private $password; // 明文密码
	private $confirm_password; //明文确认密码
	private $invitation_code; //邀请码
	private $imei; //IMEI码
	//是否填写邀请码 正确与否 0没填，1，填写并正确 2填写但是不正确
	private $invit_code_is_right = 0;
	public  $session_key = 'session_reg_key';
	public function __construct() {
		parent::__construct ();
		$this->load->library('session');
		$this->load->library('reg_lib');
		$this->load->model('Reg_model');
		$this->load->database ();
		//初始化post变量
		$this->_set_post_params();
	}
	
	/**
	 * 注册dolocker用户
	 * @author 刘志超
	 * @date 2015-04-16
	 */
	public function register(){
			//检查手机号是否唯一
			$check_phone_res = $this->reg_lib->check_user($this->phone,$this->password,$this->confirm_password,$this->source);
			if($check_phone_res !== TRUE){
				$error = $this->reg_lib->error();
				failure($error['error_code'],$error['error_message']);
				return FALSE;
			}
			
			//检查验证码是否正确
			
			$this->load->library('Dl_mobile_verification');
			$check_active_code_res = $this->dl_mobile_verification->check_active_code($this->phone,$this->active_code);
			if($check_active_code_res !== TRUE){
			      $error = $this->dl_mobile_verification->error();
			      failure($error['error_code'],$error['error_message']);
				  return FALSE;
			}
			
			//保存用户信息
			$save_res = $this->_save();
			if($save_res !== TRUE){
					$this->reg_lib->redefind_error('308','保存用户信息失败');
					$error = $this->reg_lib->error();
					failure($error['error_code'],$error['error_message']);
			}

			//激活账户
			$active_res = $this->_active_user();
			if($active_res !== TRUE){
					$error = $this->reg_lib->error();
					failure($error['error_code'],$error['error_message']);
			}
			success(200,'注册成功',array('invit_code_is_right'=>$this->invit_code_is_right));
	}

	
	
	/**
	 * 保存用户信息
	 * @author 刘志超
	 * @date 2015-04-17
	 */
	private function _save(){
		
		//开启事务
		$invit = array();
		$invit = $this->Reg_model->get_uinfo($this->invitation_code);
		$this->db->trans_begin();
		$is_invited = $invit ? TRUE : FALSE;
		//判断用户是否填对了邀请码
		if($is_invited === TRUE) $this->invit_code_is_right = 1;
		if(!empty($this->invitation_code) && $is_invited === FALSE) $this->invit_code_is_right = 2;
		$res = $this->reg_lib->save($this->phone,$this->password,$this->confirm_password,$is_invited,$this->source,$this->imei,$this->invitation_code);

		if($res !== TRUE){
			$this->db->trans_rollback();
			return FALSE;
		}
		
		
		$this->session->set_userdata($this->session_key.'_uid', $this->reg_lib->create_uid);
		
		//初始化注册获得金额为5元  ---------现在改为2元------
		$money = INVITED_AWARD;
		$account_data = array();
		$account_data[0]['uid'] = $this->reg_lib->create_uid;
		$account_data[0]['phone'] = $this->phone;
		$account_data[0]['type'] = 10;
		$account_data[0]['is_get_money'] = 1;
		$account_data[0]['money'] = $money;
		$account_data[0]['dateline'] = time();
		$account_data[0]['task_state'] = 1;
		//邀请注册
		if(!empty($this->invitation_code) && !empty($invit)){
			//获取邀请人信息
			$invit_data = array(
					'uid' => $invit['uid'],
					'phone' => $invit['phone'],
					'invited_uid' => $this->reg_lib->create_uid,
					'invited_phone' => $this->phone,
					'dateline' => time(),
			);
			$res = $this->Reg_model->add_user_invitation_log($invit_data);
			if($res !== TRUE){
				$this->db->trans_rollback();
				return FALSE;
			}
			
			//填写了正确的邀请码增加3元
			//$money  += INVIT_AWARD;
			
			$account_data[1]['uid'] = $this->reg_lib->create_uid;
			$account_data[1]['phone'] = $this->phone;
			$account_data[1]['type'] = 7;
			$account_data[1]['is_get_money'] = 1;
			$account_data[1]['money'] = BEI_INVIT_AWARD;
			$account_data[1]['dateline'] = time();
			$account_data[1]['task_state'] = 1;
			
		}
		
		$this->db->insert_batch('user_account_log_stat',$account_data);
		if($this->db->affected_rows() != count($account_data)){
			$this->db->trans_rollback();
			return FALSE;
		}
		$this->db->trans_commit();
		return TRUE;
	}
	
	
	/**
	 * 激活用户信息
	 * @return boolean
	 * @author 刘志超
	 * @version 2014-8-2
	 */
	private function _active_user(){
		$this->load->model('common_user_model');
		$this->uid = intval( $this->session->userdata($this->session_key.'_uid') ); // 用户uid
		$user = $this->Reg_model->get_reg_user($this->uid);
		if($user) {
			$this->common_user_model->save_cookie($this->uid,$user['phone'],$user['uname'],$user['utype'],$user['salt'],TRUE);
			return TRUE;
		}
		$this->reg_lib->redefind_error('307','激活失败');
		return FALSE;
	}
	
	/**
	 * 初始化post变量
	 * @author 刘志超
	 * @date 2015-04-16
	 */
	private function _set_post_params(){
		$this->phone            = trim( $this->input->post( 'phone' , TRUE ) ); // 输入的手机号
		$this->password         = trim( $this->input->post( 'password' , TRUE ) ); // 明文密码
		$this->confirm_password = trim( $this->input->post( 'confirmpassword' , TRUE ) ); //明文确认密码
		$this->invitation_code  = trim( $this->input->post( 'invitation_code' , TRUE ) ); //邀请码
		$this->active_code 		= trim( $this->input->post( 'active_code' , TRUE ) ); //短信验证码
		$this->source 		    = trim( $this->input->post( 'source' , TRUE ) ); //短信验证码
		$this->imei 		    = trim( $this->input->post( 'imei' , TRUE ) ); //短信验证码
	}
	
	
}
