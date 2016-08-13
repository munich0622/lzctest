<?php
class Index extends Admin_Controller{
	function __construct()
	{
		parent::__construct();
		if(!$this->user) redirect('login/index');
		$this->load->database();
	}
	public function index(){
		
	   
		$this->load->view('index');
	}
	
	public function test(){
	    $this->load->model('user_model');
	    $sql = " SELECT * FROM tf_pay WHERE receive_uid = 0 AND dakuan_id = 0 AND type = 2";
	    $list = $this->db->query($sql)->result_array();
	    foreach($list as $key=>$val){
	        if($val['content'] == '升2费用'){
	            $receive_uid = $this->user_model->get_parents($val['pay_uid'],2);
	        }else{
	            $receive_uid = $this->user_model->get_parents($val['pay_uid'],3);
	        }
	        $this->db->where('id',$val['id'])->update('pay',array('receive_uid'=>$receive_uid));
	    }
	}
}