<?php

class Pay extends Admin_Controller{
    
	function __construct(){
		parent::__construct();
		$this->load->model('pay_model');
		$this->load->helper('pagination');
	}
	
	
	
	/**
	 * 支付列表
	 */
	public function index(){
	    $type      = (int)$this->input->get('type',true);
	    $status    = (int)$this->input->get('status',true);
	    $is_dakuan = (int)$this->input->get('is_dakuan',true);
	    $page      = (int)$this->input->get('page',true);
	    $page_size = 1;
	    
	    
	    $where = " where 1 ";
	    if(!empty($type)){
	        $where .= " AND tp.type = {$type} ";
	    }
	    if(in_array($status,array(1,2))){
	        $status = $status == 1 ? 1 : 0;
	        
	        $where .= " AND tp.status = {$status} ";
	    }
	    
	    if(in_array($is_dakuan,array(1,2))){
	        $is_dakuan = $is_dakuan == 1 ? 1 : 0;
	        if($is_dakuan == 1){
	            $where .= " AND tp.dakuan_id > 0 ";
	        }else{
	            $where .= " AND tp.dakuan_id = 0 ";
	        }
	        
	    }
	    
	    
	    
	    $result = $this->pay_model->pay_list($where,$page_size);
	    $total  =  $result['total'];
	    $data['list'] = $result['list'];
	    //获取列表的用户信息
	    $uid_arr = array();
	    if($total > 0){
	        foreach ($data['list'] as $key=>$val){
	             $uid_arr[] = $val['pay_uid'];
	             if(!empty($val['receive_uid'])){
	                 $uid_arr[] = $val['receive_uid'];
	             }
	             
	        }
	    }
	    
	    $this->load->model('user_model');
	    $data['user_arr_info'] = $this->user_model->get_user_arr_info($uid_arr);
	    
	    $url = '/pay/pay_list/%d?'. urldecode ( $_SERVER ['QUERY_STRING'] );
	    $data['page_html'] = pagination ($page, ceil($total/$page_size), $url, 5, TRUE, TRUE, $total );
	    
	    $this->load->view('pay/pay_list',$data);
	}
	
   
}
?>
