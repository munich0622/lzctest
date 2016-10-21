<?php

class User extends Admin_Controller{
    
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
	}
	
	
	
	/**
	 * 个人资料管理
	 */
	public function index(){
	    
	    
	    
	    $this->load->view('user/user_info');
	}
	
	
}
?>
