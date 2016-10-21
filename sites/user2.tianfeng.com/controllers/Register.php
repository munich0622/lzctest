<?php

class Register extends MY_Controller{
    
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('register_model');
	}
	
	/**
	 * 个人资料管理
	 */
	public function index(){
	    if(is_login()) {
	        redirect('user/index');
	    }

	    
	    $this->load->view('user/register');
	}
	
	/**
	 * 注册提交
	 */
	public function register_sub(){
	    $phone           = $this->input->post('phone',TRUE);
	    $pwd             = $this->input->post('pwd',TRUE);
	    $pwd2            = $this->input->post('pwd2',TRUE);
	    $invitation_code = $this->input->post('invitation_code',TRUE);
	    if(empty($pwd) || strlen($pwd)<6 || strlen($pwd) > 15) {
	        ajax_response(false,'','密码必须是6-15位!');
	    }
	    
	    if($pwd != $pwd2) {
	        ajax_response(false,'','两次输入的密码不一致!');
	    }
	    
	    $res = $this->user_model->get_user('phone',$phone);
	    if($res){
	        ajax_response(false,'','该手机号已经被人注册了!');
	    }
	    //判断邀请码是否用过
	    $res = $this->register_model->get_invited_code($invitation_code);
	    if(empty($res)){
	        ajax_response(false,'','邀请码不存在!');
	    }
	    
	    if($res['status'] > 0 || $res['uid'] > 0){
	        ajax_response(false,'','邀请码已经被使用!');
	    }
	    
	    
	    
	    
	    $data['phone']       = $phone;
	    $data['salt']        = $this->_rank_string();
	    $data['password']    = en_pass($pwd, $data['salt']);
	    $data['reg_time']    = time();
	    $data['status']      = 1;
	    
	    $this->db->trans_begin();
	    //插入注册信息
	    $uid = (int)$this->register_model->insert_table_data($data,'ooo_user');
	    if($uid == 0){
	        $this->db->trans_rollback();
	        ajax_response(false,'','注册失败!');
	    }
	    
	    //更新邀请码表的状态
	    $res = $this->register_model->update_invited_code($invitation_code,$uid);
	    if(empty($res)){
	        $this->db->trans_rollback();
	        ajax_response(false,'','注册失败!');
	    }
	    
        $this->db->trans_commit();
        
        $user = $this->user_model->get_user('uid',$uid);
        
        $_SESSION['user'] = $user;
        ajax_response(true,'','注册成功!');
	}
	
	
	
	/**
	 * 产生随机字符
	 * @author 刘志超
	 * @param int $length 产生随机字符长度
	 * @date 2015-04-17
	 * @return string
	 */
	private function _rank_string($length = 4 ){
	    // 密码字符集，可任意添加你需要的字符+
	    $chars = '123456789abcdefghijklmnopqrstuvwxyz';
	    $string = '';
	    for($i = 0; $i < $length; $i ++) {
	        $string .= $chars [mt_rand ( 0, strlen ( $chars ) - 1 )];
	    }
	    return $string;
	}
}
?>
