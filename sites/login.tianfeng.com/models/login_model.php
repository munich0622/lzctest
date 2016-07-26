<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 登陆model
 *
 * @author 刘志超
 * @date 2015-04-16
 */
class Login_model extends CI_Model{
	/**
	 * 允许修改的字段
	 * 
	 */
	private $allow_fields = array('last_time','last_ip','login_sign');
	
	/**
	 * 注册错误代码
	 * NAME_EXIST_ALREADY:您注册的用户名已经被注册，请重新更换新的用户名
	 * PASSWORD_NOT_EQUAL:两次输入的密码不相同
	 * PASSWORD_ALL_NUM:密码不能纯数字
	 * PASSWORD_CONTAIN_SPECIAL:密码不能有特殊字符
	 * PHONE_ERROR:手机号格式不正确
	 * SYS_RUM_WRONG:系统错误，注册失败！
	 * ACTIVE_FAILURE:激活失败
	 * @author 刘志超
	 * @date 2015-04-17
	 * 
	 * 		添加登录日志 （暂时不加限制条件）
	 * 		'306' => '用户编号不能为空',
	 *		'307' => '版本号不能为空',
	 *		'308' => '渠道不能为空',
	 *		'309' => '品牌不能为空',
	 *		'310' => '设备型号不能为空',
	 *		'311' => '操作系统不能为空',
	 *		'312' => '分辨率不能为空',
	 *		'313' => '联网方式不能为空',
	 *		'314' => '分辨率不能为空',
	 */
	public $error_code = array(
			'301' => '请输入手机号',
			'302' => '请输入密码',
			'303' => '找不到该用户',
			'304' => '用户名密码不匹配',
			'305' => '修改用户信息失败',
			'306' => '用户登录日志添加失败',
			'307' => '该账号已被封号'
			
	);
	
	public function __construct() {
		parent::__construct ();
		$this->load->database();
	}
	
	
	
	/**
	 * 根据用户手机号获取用户信息
	 * @param string $phone 手机号
	 * @author 刘志超
	 * @date 2015-04-20
	 */
	public function get_user($phone){
		return $this->db->get_where('user',array('phone'=>$phone))->row_array();
	}
	
	/**
	 * 修改用户信息
	 * @param array $array 修改的数组，格式为字段对应值值
	 * @author 刘志超
	 * @date 2015-04-20
	 */
	public function update_userinfo($uid,$array){
		$uid = intval($uid);
		if(empty($uid) || empty($array) || !is_array($array)){
			return FALSE;
		}
		
		foreach ($array as $key => $val){
			if(!in_array($key,$this->allow_fields)){
				return FALSE;
			}
		}
		
		return $this->db->where('uid',$uid)->update('user',$array);
		
	}
	
	/**
	 * 设置login_sign
	 * @param int $uid 用户id
	 * @param string $login_sign 登陆签名
	 * @author 刘志超
	 * @date 2015-04-20
	 */
	
	public function set_login_sign($uid,$login_sign){
		$this->db->where('uid',$uid)->update('user',array('login_sign'=>$login_sign));
	}
	
	/**
	 * 获取用户所做过安装任务的应用id
	 * @author 刘志超
	 * @date 2015-05-09
	 */
	public function get_user_task_appids($uid){
		$uid = intval($uid);
		$where = array(
			'uid'   => $uid,
			'type'  => 1, //安装类型
			'task_state' => 1,
		);
		return $this->db->select('app_id')->get_where('user_account_log_stat',$where)->result_array();
	}
	
	/**
	 * 添加登录日志
	 * 
	 * @param array $data  登录日志表信息
	 * @return boolean
	 * 
	 * @author 陆学锦
	 * @date 2015-05-26
	 */
	public function insert_login_log($data){
		
		$this->db->insert('user_login_log',$data);
		if($this->db->affected_rows() != 1){
			return FALSE;
		}
	
		return TRUE;
	}
	
}