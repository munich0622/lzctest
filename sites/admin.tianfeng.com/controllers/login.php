<?php

class Login extends MY_Controller{
	function __construct()
	{
		parent::__construct();
		$this ->load->database();
		$this->load->helper(array('url','get_user'));
	
	}
	
	/**
	 * 登录首页
	 */
	public function index(){
	    if(isset($_SESSION['user']) && $_SESSION['user']['id'] > 0){
	        go('已经登录','/index/index');
	    }
	    
		$this->load->view('login');
	}
	
	
	/**
	 * 登录提交
	 */
	public function signin(){
		$username = trim(strval($this->input->post('username',TRUE))) ;
		$password = trim(strval($this->input->post('password',TRUE))) ;
		
		$salt = $this->db->select('salt')->where(array('username'=>$username))->get('admin')->row_array();
		if($salt){
		    $password = en_pass($password, $salt['salt']);
			$user = $this->db->where(array('username'=>$username,'password'=>$password))->get('admin')->row_array();
		}

		
		if ($user) {
			#成功，将用户信息保存至session
			$_SESSION['user'] = $user;
			redirect('/');
		} else {
			# error
		    go('登录失败','/login/index');
		}
	}
	
	public function loginout(){
		unset($_SESSION['user']);
		redirect('login');
	}
	
	
}
?>
