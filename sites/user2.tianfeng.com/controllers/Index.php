<?php
class Index extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('user_model');
	}
	
	/**
	 * 登录页面
	 */
	public function index(){
	    if(is_login()) {
	        redirect('user/index');
	    }
		$this->load->view('index');
	}
	
	/**
	 * 登录提交
	 */
	public function login_sub(){
	    if(is_login()) {
	        ajax_response(true,'','已经登录！');
	    }
	    
	    $phone    = $this->input->post('phone',true);
	    $password = $this->input->post('password',true);
	    
	    //获取用户信息
	    $user_info = $this->user_model->get_user('phone',$phone);
	    if(empty($user_info)){
	        ajax_response(false,'','该用户不存在！');
	    }
	    //用户输入的密码加密
	    $input_password = en_pass($password, $user_info['salt']);
	    if($input_password != $user_info['password']){
	        ajax_response(false,'','密码不正确！');
	    }
	    
	    $_SESSION['user'] = $user_info;
	    
	    ajax_response(true,'','登录成功！');
	}
	
	public function loginout(){
	    unset($_SESSION['user']);
	    redirect('login');
	}
	
}