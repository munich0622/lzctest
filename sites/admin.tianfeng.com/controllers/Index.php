<?php

class Index extends Admin_Controller{
    
	function __construct(){
		parent::__construct();
		
	}
	
	
	
	/**
	 * 后台首页
	 */
	public function index(){
	    
	    $this->load->view('index');
	}
	
	
}
?>
