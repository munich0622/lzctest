<?php

class User extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
		$this->load->helper('pagination');
	}
	
	
	
	
	/**
	 * 重置密码
	 */
	public function update_pas(){
		
	    $data['menu_name'] = '重置密码';
	    
	    $this->load->view('user/reset_pas',$data);
	}
	
	/**
	 * 重置密码提交
	 */
	public function reset_pas_sub(){
	    $phone = $this->input->post('phone',true);
	    
	    $res = $this->user_model->reset_pas($phone);
	    
	    if($res === true){
	        go('重置成功','/user/update_pas');
	    }elseif($res === '-1'){
	        go('用户不存在','/user/update_pas');
	    }else{
	        go('重置失败','/user/update_pas');
	    }
	}
	
	
	
}
?>
