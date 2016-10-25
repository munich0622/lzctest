<?php

class User extends Admin_Controller{
    
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
		$this->load->helper('pagination');
	}
	
	
	
	/**
	 * 个人主页
	 */
	public function index(){
	    
	    $data['method'] = __METHOD__;   
	    $data['user'] = $this->user_model->get_user('uid',$this->user['uid']);
	    $data['user']['link'] = SITE_URL_TWO.'register?tj_id='.$data['user']['uid'];
	    $data['user']['head_img_url'] = IMG_URL_TWO.'upload'.$data['user']['head_img_url'];
	    //获取上级推荐人的姓名
	    $data['user']['tj_info'] = $this->user_model->get_user('uid',$data['user']['tj_1']);
	    //获取推荐的总人数
	    $data['user']['tj_count'] = $this->user_model->invited_count($this->user['uid']);
	    
	    $this->load->view('user/user_info',$data);
	}
	
	/**
	 * 获取用户直推列表
	 */
	public function tj_list($page = 1){
	    $data['method'] = __METHOD__;
	    $data['user'] = $this->user_model->get_user('uid',$this->user['uid']);
	    $data['user']['link'] = SITE_URL_TWO.'register?tj_id='.$data['user']['uid'];
	    $where = " where ti.invited_uid = {$this->user['uid']}";
	    
	    $result = $this->user_model->tj_list($where,$page);
	    $total  =  $result['total'];
	    $data['list'] = $result['list'];
	    if(!empty($data['list'])){
	        foreach ($data['list'] as $key=>$val){
	            $data['list'][$key]['head_img_url'] = IMG_URL_TWO.'upload'.$data['list'][$key]['head_img_url'];
	        }
	    }
	    $url = '/user/tj_list/%d?'. urldecode ( $_SERVER ['QUERY_STRING'] );
	    
	    $data['page_html'] = pagination ($page, ceil($total/$this->user_model->limit), $url, 5, TRUE, TRUE, $total );
	    
	    $this->load->view('user/user_tj_list',$data);
	}
	
}
?>
