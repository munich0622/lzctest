<?php
class Index extends MY_Controller{
	function __construct()
	{
		parent::__construct();
		if(!$this->user) redirect('login/index');
		
	}
	public function index(){
		
	   
		$this->load->view('index');
	}
	
}