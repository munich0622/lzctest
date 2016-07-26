<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 登录控制器
 * 处理登录相关调用
 * @author 刘志超
 * @date 2015-04-20
 */
class Login extends MY_Controller
{
	public function __construct() {
		parent::__construct ();
		$this->load->model('Login_model');
		$this->load->library('session');
	}
	/**
	 * 登录页面
	 * @author 刘志超
	 * @date 2015-04-20
	 * @return void
	 */
	public function login(){
		$phone    = trim($this->input->get_post('phone', TRUE));
		$password = trim($this->input->get_post('password', TRUE));
		
		//添加进登录日记表的信息
		$version = trim($this->input->get_post('version', TRUE));
		$channel = trim($this->input->get_post('channel', TRUE));
		$brand = trim($this->input->get_post('brand', TRUE));
		$device_type = trim($this->input->get_post('device_type', TRUE));
		$os = trim($this->input->get_post('os', TRUE));
		$resolution = trim($this->input->get_post('resolution', TRUE));
		$network_method = trim($this->input->get_post('network_method', TRUE));
		$imei = trim($this->input->get_post('imei', TRUE));
		//版本号
		$version_code = trim($this->input->get_post('version_code', TRUE));

		$is_block = FALSE;
		//定义开关数组 是否显示相关内容code
		$switch_arr = array(//渠道信息 版本号 
				array('channel_info'=>'zs360','version_num'=>22),
				array('channel_info'=>'baidu','version_num'=>0),
				array('channel_info'=>'huawei','version_num'=>23)
		);

		
		//添加进登录日志表的信息
		
		if(empty($phone)){
			$code = 301;
			failure($code,$this->Login_model->error_code[$code]);
			return FALSE;
		}
		if(empty($password)){
			$code = 302;
			failure($code,$this->Login_model->error_code[$code]);
			return FALSE;
		}
		
		$user = $this->Login_model->get_user($phone);
		if(!$user) {
			$code = 303;
			failure($code,$this->Login_model->error_code[$code]);
			return FALSE;
		}
		//判断是否被封号 封号后清空login_sign 签名密钥
		if($user['is_blocked'] ==1){
			$re = $this->db->where(array('uid'=>$user['uid']))->update('user',array('login_sign'=>''));
			if($re !==true){
				$code = 307;
				failure($code,$this->Login_model->error_code[$code]);
				return FALSE;
			}			
			$code = 307;
			failure($code,$this->Login_model->error_code[$code]);
			return FALSE;
		}
		//验证密码是否正确
		$check_res = $this->_check_password($user['password'],$user['salt'],$password);
		if(!$check_res) {
			$code = 304;
			failure($code,$this->Login_model->error_code[$code]);
			return FALSE;
		}
			
		//判断渠道信息和版本号
		foreach ($switch_arr as $k=>$v){
			if($v['channel_info']==$channel && $v['version_num']==$version_code){
				$is_block = TRUE;
			}
		
		}
		
		//把上次登陆时间和ip保存到session
		$last_info = array(
			'last_login_time' => $user['last_time'],
			'last_ip'         => $user['last_ip']
		);
		
		//开启事务
		$this->db->trans_begin();
		$this->session->set_userdata($last_info);		
		//修改用户信息包括last_time,last_ip
		$update_userinfo_res = $this->Login_model->update_userinfo($user['uid'],array('last_time'=>time(),'last_ip'=>ip('int')));
		if(!$update_userinfo_res){
			$this->db->trans_rollback();
			$code = 305;
			failure($code,$this->Login_model->error_code[$code]);
			return FALSE;
			
		}
		
		//保存登陆信息到cookie
		$this->load->model('common_user_model');
		$this->common_user_model->save_cookie($user['uid'],$user['phone'],$user['uname'],1,$user['salt'],TRUE);
		
		//添加登录日志
		$login_data  =array(
				'uid'=>$user['uid'],
				'phone'=>$user['phone'],
				'dateline'=>time(),
				'version'=>$version,
				'channel'=>$channel,
				'brand'=>$brand,
				'device_type'=>$device_type,
				'os'=>$os,
				'resolution'=>$resolution,
				'network_method'=>$network_method,
				'imei'=>$imei,
				'login_ip'=>ip('int')
				
		);
		
		$ret = $this->Login_model->insert_login_log($login_data);
		if($ret !==TRUE){
			$this->db->trans_rollback();
			$code = 306;
			failure($code,$this->Login_model->error_code[$code]);
			return FALSE;
		}
		//生成login_sign
		$sign = $this->_create_login_sign($user['uid']);
		//获取用户所做过安装任务的应用id
		$appids = $this->Login_model->get_user_task_appids($user['uid']);
		$this->db->trans_commit();
		success(200,'登陆成功',array('uid'=>$user['uid'],'sign'=>$sign,'phone'=>$user['phone'],'invitation_code'=>$user['invitation_code'],'time'=>time(),'appids'=>$this->_arr_unique($appids),'is_block'=>$is_block));
	}
	
	/**
	 * 数组去重
	 * @author 刘志超
	 * @date 2015-05-09
	 */
	private function _arr_unique($arr){
		if(empty($arr) || !is_array($arr)){
			return array();
		}
		$temp = array();
		foreach($arr as $key=>$val){
			$temp[] = $val['app_id'];
		}
		return array_values(array_unique($temp));
	}
	
	/**
	 * 生成login_sign
	 * @param int $uid 用户id
	 * @author 刘志超
	 * @date 2015-04-20
	 */
	private function _create_login_sign($uid){
		$sign = md5(md5(rand()).rand());
		$this->Login_model->set_login_sign($uid,$sign);
		return $sign;
	}
	
	/**
	 * 检测密码是否正确
	 * @param string $password 加密过的密码
	 * @param string $salt 6位随机加密串 
	 * @param string $pass 明密码
	 * @author 刘志超
	 * @date 2015-04-20
	 */
	private function _check_password($password,$salt,$pass){
		$compare_pass = strtolower ( md5 ( strtolower ( md5 ( $pass ) . $salt ) ) );
		if($password != $compare_pass) return FALSE;
		return TRUE;
	}
	
}
