<?php

class User extends Admin_Controller{
    
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
	}
	
	
	
	/**
	 * 个人主页
	 */
	public function index(){
	    
	    $data['method'] = __METHOD__;   
	    $data['user'] = $this->user_model->get_user('uid',$this->user['uid']);
	    $data['user']['link'] = SITE_URL_TWO.'register?tj_id='.$data['user']['uid'];
	    //获取上级推荐人的姓名
	    $data['user']['tj_info'] = $this->user_model->get_user('uid',$data['user']['tj_1']);
	    
	    
	    $this->load->view('user/user_info',$data);
	}
	
	
	
}
?>
