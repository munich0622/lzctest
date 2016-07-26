<?php

/**
 * 注册类
 * @author 刘志超
 * @date 2015-04-16
 */

class Reg_lib{
	private $CI = NULL;
	/**
	 * 注册错误代码
	 * 301:您注册的用户名已经被注册，请重新更换新的用户名
	 * 302:两次输入的密码不相同
	 * 303:密码不能纯数字
	 * 304:密码不能有特殊字符
	 * 305:手机号格式不正确
	 * 306:系统错误，注册失败！
	 * 307:激活失败
	 * 308:保存用户信息失败
	 * 309:验证码错误
	 * 310:注册来源不能为空
	 * @author 刘志超
	 * @date 2015-04-17
	 */
	private $error = NULL;
	public $create_uid = NULL;
	function __construct(){
		$this->CI = & get_instance ();
		$this->CI->load->model ( 'reg_model', '', true );
	}
	
	/**
	 * 获取错误信息
	 * @author 刘志超
	 * @date 2015-04-16
	 */
	public function error(){
		return $this->error;
	}
	
	/**
	 * 重新定义错误信息
	 * @param string $error_code 错误代码
	 * @param string $error_message 错误信息
	 * @author 刘志超
	 * @date 2015-04-16
	 */
	public function redefind_error($error_code,$error_message){
		$this->error = array('error_code'=>$error_code,'error_message'=>$error_message);
	}
	 
	/**
	 * 检测用户名手机号和密码
	 * @author 刘志超
	 * @param string $phone 手机号
	 * @param string $password 密码
	 * @param string $confirm_pass 确认密码
	 * @param string $source 注册来源
	 * @return boolean
	 */
	public function check_user( $phone ,$password,$confirm_pass,$source){
		//效验密码
		if(!$this->_check_password($password,$confirm_pass)) return FALSE;
		if(empty($source)){
			$this->error = array('error_code'=>'310','error_message'=>'注册来源不能为空！');
			return FALSE;
		}
		//检查手机号的合法性
		if( !$this->_check_correct_phone( $phone) ) return FALSE;
		// 查询是否存在手机号
		if ( $this->_check_exist_phone( $phone) ) {
			$this->error = array('error_code'=>'301','error_message'=>'您注册的手机号已经被注册，请重新更换新的手机号');
			return FALSE;
		}
		return TRUE;
	}
	
	
	/**
	 * 保存注册信息
	 * @param string $phone 用户名
	 * @param string $password 密码
	 * @param string $confirm_pass 确认密码
	 * @param boolean $is_invited 是否是被邀请的
	 * @param varchar $source 注册来源
	 * @author 刘志超
	 * @date 2015-04-17
	 */
	public function save($phone,$password,$confirm_pass,$is_invited = FALSE,$source,$imei,$invitation_code){
		$code = $this->get_invitation_code();
		
		//获取注册来源id
		$source_id = $this->CI->reg_model->get_source_id($source);
		if(!$source_id){
			$this->error = array('error_code'=>'310','error_message'=>'注册来源不能为空！');
			return FALSE;
		}
		//注册的时候只能是类型1的普通用户 要想改成管理员 必须经过数据库修改
		$res = $this->CI->reg_model->save_user_info($phone,$password,1,$this->_rank_string(),ip('int'),$is_invited,$code,$source_id,$imei,$invitation_code);
		if($res === TRUE) {
			$this->create_uid =  $this->CI->reg_model->create_uid;
			return TRUE;
		}
		$this->error = array('error_code'=>'306','error_message'=>'系统错误，注册失败！');
		return FALSE;
	}
	/**
	 * 递归获取唯一邀请码
	 * @author 刘志超
	 * @date 2015-05-05
	 */
	public function get_invitation_code(){
		static $i = 4;
		$res = FALSE;
		$code = $this->_create_invitation_code($i);
		//查询是否存在这个code
		$res = $this->CI->reg_model->is_unique_code($code);
		if($res !== TRUE){
			$i++;
			return $this->get_invitation_code();
		}else{
			return $code;
		}
	}
	
	
	/**
	 * 生成邀请码
	 * @author 刘志超
	 * @param int $length 产生随机字符长度
	 * @date 2015-04-17
	 * @return $code
	 */
	private function _create_invitation_code($length = 4 ){
		// 密码字符集，可任意添加你需要的字符
		$code = '';
		for($i = 0;$i<1;$i++){
			$code .= rand(1,9);
		}
		for($i = 0;$i<$length-1;$i++){
			$code .= rand(0,9);
		}
		return $code;
	}
	
	
	/**
	 * 获取当天开始时间
	 * @author 刘志超
	 * @date 2015-04-22
	 */
	public function get_today_start_time(){
		return mktime(0,0,0,date('m'),date('d'),date('Y'));
	}
	
	/**
	 * 获取当天结束时间
	 * @author 刘志超
	 * @date 2015-04-22
	 */
	public function get_today_end_time(){
		return mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
	}
	
	
	
	/**
	 * 产生随机字符
	 * @author 刘志超
	 * @param int $length 产生随机字符长度
	 * @date 2015-04-17
	 * @return string
	 */
	private function _rank_string($length = 6 ){
		// 密码字符集，可任意添加你需要的字符
		$chars = '123456789abcdefghijklmnopqrstuvwxyz';
		$string = '';
		for($i = 0; $i < $length; $i ++) {
			$string .= $chars [mt_rand ( 0, strlen ( $chars ) - 1 )];
		}
		return $string;
	}
	
	/**
	 * 判断手机号是否已经存在
	 * @author 刘志超
	 * @param string $phone 用户名
	 * @date 2015-04-16
	 */
	private function _check_exist_phone($phone){
		return $this->CI->reg_model->check_exist_phone($phone);
	}
	
	/**
	 * 效验密码
	 * @param string $password 密码
	 * @param string $confirm_pass 确认密码
	 * @author 刘志超
	 * @date 2015-04-17
	 * 
	 */
	private function _check_password($password,$confirm_pass){
		if($password !== $confirm_pass){
			$this->error = array('error_code'=>'302','error_message'=>'两次输入的密码不相同');
			return FALSE;
		}
		
		if( preg_match ( '/^([0-9]+)$/', $password ) ){
			$this->error = array('error_code'=>'303','error_message'=>'密码不能纯数字');
			return FALSE;
		}
		
		// 密码不能包含特殊字符
		if( preg_match ( '/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/', $password ) ){
			$this->error = array('error_code'=>'304','error_message'=>'密码不能有特殊字符');
			return FALSE;
		}
		
		return TRUE;
	}
	/**
	 * 检查用户名的合法性，规则和试客联盟的一样
	 * @author 刘志超
	 * @param  string $phone 手机号
	 * @return boolean
	 */
	private function _check_correct_phone($phone){
		//验证手机号码
		if(!preg_match("/1[34578]{1}\d{9}$/",$phone)){
			$this->error = array('error_code'=>'305','error_message'=>'手机号格式不正确');
			return FALSE;
		}
		return TRUE;
	}
}
?>
