<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 注册model
 *
 * @author 刘志超
 * @date 2015-04-16
 */
class Reg_model extends CI_Model{
	public $create_uid = NULL;
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
	 */
	public $error_code = array(
			'NAME_EXIST_ALREADY' => '301',
			'PASSWORD_NOT_EQUAL' => '302',
			'PASSWORD_ALL_NUM' 	 => '303',
			'PASSWORD_CONTAIN_SPECIAL' 	=> '304',
			'PHONE_ERROR' => '305',
			'ERROR_SUBMIT' => '306',
			'SYS_RUM_WRONG' => '307',
			'ACTIVE_FAILURE'=> '308',
			'ACTIVE_FAILURE'=> '308',
	);
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
	}
	
	
	/**
	 * 判断是否存在此手机号
	 * @author 刘志超
	 * @param string $phone 手机号
	 * @date 2015-04-16
	 */
	public function check_exist_phone ($phone){
	  return $this->db->select('phone')->where('phone',$phone)->get('user')->row_array();
	}
	
	/**
	 * 保存用户信息
	 * @param int $phone 手机号
	 * @param string $password 密码
	 * @param tinyint $type 用户类型
	 * @param string $salt 6位加密随机数
	 * @param int $ip ip地址
	 * @param boolean $is_invited 是否是被邀请的
	 * @param int $code 生成邀请码
	 * @param varchar $source 注册来源
	 * @author 刘志超
	 * @date 2015-04-17
	 */
	public function save_user_info($phone,$password,$type = 1,$salt,$ip,$is_invited = FALSE,$code,$source,$imei='',$invitation_code){
		//密码加密
		$password_encode = strtolower ( md5 ( strtolower ( md5 ( $password ) . $salt ) ) );
		$time = time();
		/*
		$data = array(
			'utype' => $type,
			'reg_time' => $time,
			'phone' => $phone,
			'password' => $password_encode,
			'salt'=> $salt,
			'last_time'=>$time,
			'last_ip'=>$ip,
			'status' => 1,
			'invitation_code' => intval($code),
			'source' => $source,
			'imei' => $imei
		);*/
		
		$data['utype'] = $type;
		$data['reg_time'] = $time;
		$data['phone'] = $phone;
		$data['password'] = $password_encode;
		$data['salt'] = $salt;
		$data['last_time'] = $time;
		$data['last_ip'] = $ip;
		$data['status'] = 1;
		$data['invitation_code'] = intval($code);
		$data['source'] = $source;
		$data['imei'] = $imei;
		//判断是否填写邀请码
		if($is_invited === TRUE){
			//通过邀请码查找上一级邀请人
			$inivt_info = $this->db->select('uid,invite_uid,invite_time')->where('invitation_code',$invitation_code)->get('user')->row_array();
			
			if($inivt_info ){
				$data['invite_uid'] = $inivt_info['uid'];
				$data['invite_time'] =time();
			}
			if($inivt_info && $inivt_info['invite_uid']){
				$data['invite_invite_uid'] = $inivt_info['invite_uid'];
				$data['invite_invite_time'] =$inivt_info['invite_time'];
			}
		}
		$this->db->insert('user',$data);
		if(!$this->db->insert_id()) return FALSE;
			
		$uid = $this->create_uid = $this->db->insert_id ();
		
		$user_statistics_data = array(
				'uid'             => $uid,
				'phone'           => $phone,
				'today_income'    => INVITED_AWARD,
				'total_income'    => INVITED_AWARD,
				'remain_income'   => INVITED_AWARD,
				'last_income_time'=> $time,
		);
		
		if($is_invited === TRUE){
			$user_statistics_data['today_income']  = INVITED_AWARD + BEI_INVIT_AWARD ;
			$user_statistics_data['total_income']  = INVITED_AWARD + BEI_INVIT_AWARD ;
			$user_statistics_data['remain_income'] = INVITED_AWARD + BEI_INVIT_AWARD ;
		}
		
		//新增用户到统计表
		$this->db->insert('user_statistics',$user_statistics_data);
		if($this->db->affected_rows() != 1) return FALSE;

		return TRUE;
	}
	
	
	/**
	 * 获取用户信息
	 * @param int $uid 用户id
	 * @author 刘志超
	 * @date 2015-04-17
	 * 
	 */
	public function get_reg_user($uid){
		$uid = intval($uid);
		return $this->db->get_where('user',array('uid'=>$uid))->row_array();
	}
	
	
	
	/**
	 * 获取用户信息包括统计信息
	 * @param int $uid 用户id
	 * @param string $fields 默认全字段
	 * @author 刘志超
	 * @date 2015-04-20
	 */
	public function get_uinfo($invitation_code){
		$invitation_code = intval($invitation_code);
		if(!$invitation_code) return FALSE;
		$this->db->select('uid,phone,phone,invite_uid,invite_invite_uid,invite_time,invite_invite_time,login_sign');
		$this->db->from('user');
		return $this->db->where('invitation_code',$invitation_code)->get()->row_array();
	}
	
	
	/**
	 * 添加邀请日志
	 * @param array $invit_data 插入日志表的数组
	 * @author 刘志超
	 * @date 2015-04-22
	 */
	public function add_user_invitation_log($invit_data){
		$this->db->insert('user_invitation_log',$invit_data);
		if($this->db->affected_rows() != 1){
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * 增加邀请人收入
	 * @param int $uid 邀请人uid
	 * @param boolean $is_today 是否是今天收入
	 * @author 刘志超
	 * @date 2015-04-22
	 */
	public function add_income($uid,$is_today = FALSE){
		$uid = intval($uid);
		$this->db->where('uid',$uid);
		$this->db->set('today_income','today_income + '.INVIT_AWARD,FALSE);
		$this->db->set('total_income','total_income + '.INVIT_AWARD,FALSE);
		$this->db->set('remain_income','remain_income + '.INVIT_AWARD,FALSE);
		$this->db->update('user_statistics',array('last_income_time'=>time()));
		if($this->db->affected_rows() != 1){
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * 插入用户获取金额的日志表   ----------修改 插入user_account_log_stat表----------
	 * @param array $data 插入数据
	 * @param int $affect_rows 影响行数
	 * @author 刘志超
	 * @date 2015-04-27
	 */
	public function user_account_log($data,$affect_rows = 0){
		if(!is_array($data) || empty($data)) return FALSE;
		$this->db->insert_batch('user_account_log_stat',$data);
		if($this->db->affected_rows() != $affect_rows) return FALSE;
		return TRUE;
	}
	
	/**
	 * 查询user表是否存在验证码
	 * @author 刘志超
	 * @date 2015-05-05
	 */
	public function is_unique_code($code){
		$code = intval($code);
		$uinfo = $this->db->select('uid')->get_where('user',array('invitation_code'=>$code))->row_array();
		if($uinfo) return FALSE;
		return TRUE;
	}
	
	/***
	 * 查询来源的id如果没有则插入一条
	 * @param string $source 注册来源
	 * @author 刘志超
	 * @date 2015-05-05
	 */
	public function get_source_id($source){
		$source= trim($source);
		$result = $this->db->select('id')->get_where('user_source_type',array('source'=>$source))->row_array();
		if($result){
			return $result['id'];
		}else{
			$this->db->insert('user_source_type',array('source'=>$source));
			return $this->db->insert_id() ? $this->db->insert_id() : FALSE;
		}
	}
}