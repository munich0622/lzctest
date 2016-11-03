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
	    $data['user']['head_img_url'] = IMG_URL_TWO.'upload/head'.$data['user']['head_img_url'];
	    //获取上级推荐人的姓名
	    $data['user']['tj_info'] = $this->user_model->get_user('uid',$data['user']['tj_1']);
	    //获取推荐的总人数
	    $data['user']['tj_count'] = $this->user_model->invited_count($this->user['uid']);
	    
	    $this->load->view('user/index',$data);
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
	
	/*
	 * 个人资料
	 */
	public function info(){
	    $data['method'] = __METHOD__;
	    $data['user'] = $this->user_model->get_user('uid',$this->user['uid']);
	    
	    $data['user']['head_img_url'] = empty($data['user']['head_img_url']) ? '' : IMG_URL_TWO.'upload/head'.$data['user']['head_img_url'];
	    
	    $this->load->view('user/user_info',$data);
	}
	
	/**
	 * 修改个人资料
	 */
	public function info_sub(){
	   $uname    = $this->input->post('uname',true);
	   $bank     = $this->input->post('bank',true);
	   $bank_num = $this->input->post('bank_num',true);
	   $id_card  = $this->input->post('id_card',true);
	    
	    
	   $data['head_img_url'] = '';
	   if(!empty($_FILES['head_img_url']['name'])){
	       $res = $this->_upload_img();
	       if($res == 1){
	           goback('上传的文件类型错误!');
	       }elseif($res == 2){
	           goback('上传的文件大于1M!');
	       }elseif($res == 3){
	           goback('上传失败！');
	       }else{
	           $data['head_img_url'] = $res;
	       }
	   }
	   
	   $data['uname']    = $uname;
	   $data['bank']     = $bank;
	   $data['bank_num'] = $bank_num;
	   $data['id_card']  = $id_card;
	   if(empty($data['head_img_url'])){
	       unset($data['head_img_url']);
	   }
	   
	   $result = $this->user_model->update_user_info($this->user['uid'],$data);
	   
	   if($result){
	       go('修改成功','/user/index');
	   }else{
	       goback('修改失败');
	   }
	}
	
	//上传图片
	private function _upload_img(){
	    $arr_type = array('image/jpg','image/gif','image/png','image/bmp','image/jpeg');
	    if(!in_array($_FILES["head_img_url"]["type"],$arr_type)){
	        return 1;
	    }
	    //上传图片不能大于1M
	    if($_FILES["head_img_url"]["size"] > 1024*1024){
	        return 2;
	    }
	    
	    //获取扩展名
	    $file_info       = pathinfo($_FILES["head_img_url"]['name']);
	    $file_ext        = strtolower($file_info["extension"]);
	    //初始化存放图片路径
	    $temp_dir = HEAD_UPLOAD_DIR.date("Ymd").'/';
	    is_dir ( $temp_dir ) or mkdir ( $temp_dir, 0775, TRUE );
	    $path = $temp_dir.create_file_name().'.'.$file_ext;
	     
	    $head_img_url = str_replace(HEAD_UPLOAD_DIR,'/',$path);
	    
	    if(move_uploaded_file($_FILES["head_img_url"]['tmp_name'],$path)){
	        return $head_img_url;
	    }else{
	        return 3;
	    }
	}
	
	/**
	 * 密码设置
	 */
	public function set_password(){
	    $data['method'] = __METHOD__;
	    
	    $this->load->view('user/set_password',$data);
	}
	
	/**
	 * 密码设置提交
	 */
	public function set_pass_sub(){
	    $old_pass     = $this->input->post('old_pass',true);
	    $new_pass     = $this->input->post('new_pass',true);
	    $re_new_pass  = $this->input->post('re_new_pass',true);
	    if($new_pass != $re_new_pass){
	        goback('两次输入的密码不一致！');
	    }
	    
	    if(strlen($new_pass) < 6){
	        goback('密码长度必须大于等于6位数！');
	    }
	    
	    $data = $this->user_model->get_user('uid',$this->user['uid']);
	    
	    if(en_pass($old_pass, $data['salt']) != $data['password']){
	        goback('旧密码不正确！');
	    }
	    
	    $update_data = array(
	        'password' => en_pass($new_pass, $data['salt'])
	    );
	    
	    $res = $this->user_model->update_user_info($this->user['uid'],$update_data);
	    if($res){
	        go('修改成功','/user/index');
	    }else{
	        goback('修改失败');
	    }
	}
}
?>
