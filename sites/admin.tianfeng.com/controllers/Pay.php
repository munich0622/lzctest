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
	public function index($page = 1){
	    $type      = (int)$this->input->get('type',true);
	    $status    = (int)$this->input->get('status',true);
	    $is_dakuan = (int)$this->input->get('is_dakuan',true);
	    $page_size = 20;
	    
	    
	    $where = " where 1 ";
	    if(!empty($type)){
	        $where .= " AND tp.type = {$type} ";
	    }
	    if(in_array($status,array(1,2))){
	        $status = $status == 1 ? 1 : 0;
	        
	        $where .= " AND tp.status = 1 ";
	    }
	    
	    if(in_array($is_dakuan,array(1,2))){
	        $is_dakuan = $is_dakuan == 1 ? 1 : 0;
	        if($is_dakuan == 1){
	            $where .= " AND tp.dakuan_id > 0 ";
	        }else{
	            $where .= " AND tp.dakuan_id = 0 ";
	        }
	        
	    }
	    
	    
	    
	    $result = $this->pay_model->pay_list($where,$page_size,$page);
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
	    
	    $url = '/pay/index/%d?'. urldecode ( $_SERVER ['QUERY_STRING'] );
	    $data['page_html'] = pagination ($page, ceil($total/$page_size), $url, 5, TRUE, TRUE, $total );
	    
	    $this->load->view('pay/pay_list',$data);
	}
	
   /**  
    * 打款记录日志
    */
	public function dakuan(){
	    $money  = (int)$this->input->post('money',true);
	    $pay_id = (int)$this->input->post('pay_id',true);
	    if($money <= 0){
	        ajax_response(FALSE,'','传入的金额有误!');
	    }
	    
	    $info = $this->pay_model->pay_info($pay_id); 
	    
	    if(empty($info)){
	        ajax_response(FALSE,'','找不到支付信息!');
	    }
	    
	    if($info['dakuan_id'] > 0 ){
	        ajax_response(FALSE,'','已经打过款了!');
	    }
	    
	    if($info['price'] < $money){
	        ajax_response(FALSE,'','打款金额超过支付金额！');
	    }
	    
	    //开启事务
	    $this->db->trans_begin();
	    
	    $id = $this->pay_model->update_dakuan_log($info,$money);
	    
	    if(empty($id)){
	        $this->db->trans_rollback();
	        ajax_response(FALSE,'','记录日志失败!');
	    }
	    
	    $ret = $this->pay_model->update_dakuan_status($pay_id,$id);
	    
	    if(empty($ret)){
	        $this->db->trans_rollback();
	        ajax_response(FALSE,'','记录日志失败!');
	    }
	    
	    $this->db->trans_commit();
	    
	    ajax_response(true,'','打款日志成功!');
	}
}
?>
