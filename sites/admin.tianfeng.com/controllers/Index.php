<?php

class Index extends Admin_Controller{
    
	function __construct(){
		parent::__construct();
		
	}
	
	/**
	 * 删除状态为0的会员
	 * delete from tf_user where status = 0;
	 * 删除会员支付记录
	 * DELETE p from tf_pay as p LEFT JOIN tf_user as u ON p.pay_uid = u.uid WHERE u.uid is NULL
	 */
	
	/**
	 * 后台首页
	 */
	public function index(){
	    
	    $this->load->view('index');
	}
	
	
}
?>
