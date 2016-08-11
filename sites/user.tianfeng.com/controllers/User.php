<?php

class User extends Admin_Controller{
    
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('pay_model');
		
		if(empty($_SESSION['user']['openid'])){
		    $this->get_openid();
		}
		
		$this->user = $this->user_model->get_user(array('uid'=>$this->user['uid']));
		if($this->user['status'] == 0 && !strpos($_SERVER['REQUEST_URI'],'user/pay_register')){
		    go('请先支付注册费用','/user/pay_register/');
		}elseif($this->user['status'] == 2 || $this->user['status'] == 3 && !strpos($_SERVER['REQUEST_URI'],'user/create_qrcode')){
		    redirect('user/create_qrcode');
		}
		
	}
	
	
	
	/**
	 * 个人资料管理
	 */
	public function index(){
	    $data['user']      = $this->user_model->get_user_and_invited_user($this->user['uid']);
	    $data['bank_list'] = $this->user_model->bank_list();
	    
	    $this->load->view('user/user_info',$data);
	}
	
	public function test(){
	    $this->pay_model->pay_response();
	}
	
    /**
     * 保存个人资料
     * 
     */	
	public function save_user_info(){
	    $uname = $this->input->post('uname',TRUE);
	    $weixin_name = $this->input->post('weixin_name',TRUE);
	    $id_card = $this->input->post('id_card',TRUE);
	    $bank_num = $this->input->post('bank_num',TRUE);
	    $bank = $this->input->post('bank',TRUE);
	    
	    if(empty($uname)){
	        goback('用户名不能为空');
	    }
	    
	    $data['uname']       = $uname;
	    $data['weixin_name'] = $weixin_name;
	    $data['id_card']     = $id_card;
	    $data['bank_num']    = $bank_num;
	    $data['bank']        = $bank;
	    
	    $res = $this->user_model->update_user_info($this->user['uid'],$data);
	    if($res){
	        go('保存成功','/index/index');
	    }
	    
	    goback('保存失败');
	}
	
	
	
	/**
	 * 修改密码
	 */
	public function update_pass(){
	    
	    $this->load->view('user/update_pass');
	}
	
	/**
	 * 修改密码提交
	 */
	public function update_pass_sub(){
	    $password   = $this->input->post('password',TRUE);
	    $pwd        = $this->input->post('pwd',TRUE);
	    $pwd_repeat = $this->input->post('pwd_repeat',TRUE);
	    
	    if( strlen($password) < 6 || strlen($pwd) < 6 || strlen($pwd_repeat) < 6){
	        goback('密码不能小于6位');
	    }
	    
	    if($pwd != $pwd_repeat){
	        goback('两次输入的新密码不相等');
	    }
	    
	    $uid  = $this->user['uid'];
	    $user = $this->user_model->get_user(array('uid'=>$uid));
	    //判断老密码是否正确
	    if(en_pass($password, $user['salt']) != $user['password']){
	        goback('密码不正确！');
	    }
	    
	    $new_pass = en_pass($pwd,$user['salt']);
	    
	    $data['password'] = $new_pass;
	    $res = $this->user_model->update_user_info($uid,$data);
	    
	    if($res){
	        go('修改成功','/index/index');
	    }
	    
	    goback('修改失败');
	}
	
	/**
	 * 升级用户等级展示
	 * 
	 */
	public function display_user_level(){
	    $level = $this->user['level'];
	    $uid   = $this->user['uid'];
	    $space = $this->user['space'];
	    $level++;
	    //如果当前等级为1就是要升2级
	    $data['is_upgrade'] = $this->user_model->upgrade_require($uid,$level,$space);
	    $data['level'] = $level;
	    $this->load->view('user/upgrade',$data);
	}
	
	/**
	 * 升级提交
	 */
	public function upgrade(){
	    //如果当前等级为1就是要升2级
	    $level = $this->user['level'];
	    $uid   = $this->user['uid'];
	    $space = $this->user['space'];
	    $level++;
	    $is_upgrade = $this->user_model->upgrade_require($uid,$level,$space);
	    
	    if(empty($is_upgrade)){
	       go('不符合升级条件','index/index');
	    }
	    
	    
	    //判断是否有插入过支付表
	    $pay_info = $this->pay_model->pay_info($uid,PAY_TYPE_DOWN_UP);
	    if(empty($pay_info)){
	        $receive_uid = $this->user_model->get_parents($uid,$level);
	        //插入支付表
	        $pay_info['myself_trade_no'] = create_order_sn(2);
	        $pay_info['pay_uid']         = $uid;
	        $pay_info['receive_uid']     = $receive_uid;
	        $pay_info['type']            = PAY_TYPE_DOWN_UP;
	        $pay_info['price']           = $this->user_model->constants_pay_money[$space][$level];
	        $pay_info['content']         = '升'.$level.'费用';
	        $pay_info['time']            = time();
	        $pay_info['status']          = 0;
	        $pay_info['space']           = $space;
	        
	        $this->load->model('register_model');
	        $pay_info['id'] = $pay_id = $this->register_model->insert_table_data($pay_info,'pay');
	        if($pay_id == 0){
	            goback('升级失败');
	        }
	        
	    }
	    
	    
	    include '../libraries/Payment/drivers/wxpay/example/WxPay.JsApiPay.php';
	    include "../libraries/Payment/drivers/wxpay/lib/WxPay.Api.php";
	    
	    $tools = new JsApiPay();
	    
	    $input = new WxPayUnifiedOrder();
	     
	    $input->SetBody("升级支付号:".$pay_info['myself_trade_no']);
	    $input->SetOut_trade_no($pay_info['myself_trade_no']);
	    
	    //订单金额 微信单位是分
	    $fee = $pay_info['price'] * 100;
	    $input->SetTotal_fee($fee);
	    $input->SetTime_start(date("YmdHis"));
	    $input->SetTime_expire(date("YmdHis", time() + 600));
	    
	    $notify = 'http://'.$_SERVER['HTTP_HOST'].'/api/wxpay';
	    $input->SetNotify_url($notify);
	    $input->SetTrade_type("JSAPI");
	    $input->SetOpenid($_SESSION['user']['openid']);
	    
	    $result = WxPayApi::unifiedOrder($input);
	    
	    if(isset($result['err_code_des']) && $result['err_code_des'] == '该订单已支付'){
	        $res = $this->pay_model->pay_response($pay_info['myself_trade_no'],$result['nonce_str']);
	        if($res){
	            go('升级成功!','index/index');
	        }else{
	            go('升级失败!','index/index');
	        }
	        exit();
	    }elseif(isset($result['result_code']) &&  $result['result_code'] == 'FAIL'){
	        go($result['err_code_des'],'index/index');
	        exit();
	    }
	    
	    $data['jsApiParameters'] = $tools->GetJsApiParameters($result);
	    
	    $data['pay_id']    = $pay_info['id'];
	    $data['pay_money'] = $pay_info['price'];
	    $this->load->view('pay/wx_pay',$data);
	    
	}
	
	public function pay_result(){
	    $result = $this->input->get('res',true);
	    $pay_id = $this->input->get('pay_id',true);
	    $data['pay_info']  = $this->pay_model->pay_info_to_id($pay_id,$this->user['uid']);
	    $data['user_info'] = $this->user; 
	    if($result == 'success'){
	        
	        $this->load->view('pay/pay_success',$data);
	    }else{
	        
	        $this->load->view('pay/pay_fail',$data);
	    }
	}
	
	
	
	/**
	 * 查看或者生成二维码
	 * 
	 */
	public function create_qrcode(){
	    
	    $uid = $this->user['uid'];
	    $user_info = $this->user_model->get_user(array('uid'=>$uid));
	    if(empty($user_info['qr_code_url']) || !file_exists(TEMP_UPLOAD_DIR.$user_info['qr_code_url'])){
	        include '../libraries/Qrcode_lib.php';
	        $url = SITE_URL.'register?tj_uid='.$this->user['uid']; //二维码内容
	        $errorCorrectionLevel = 'L';//容错级别
	        $matrixPointSize      = 4;//生成图片大小
	        //生成二维码图片
	        QRcode::png($url, 'temp_qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
	        $logo = './public/img/logo.gif';//准备好的logo图片
	        $QR   = 'temp_qrcode.png';//已经生成的原始二维码图
	         
	        if ($logo !== FALSE) {
	            $QR             = imagecreatefromstring(file_get_contents($QR));
	            $logo           = imagecreatefromstring(file_get_contents($logo));
	            $QR_width       = imagesx($QR);//二维码图片宽度
	            $QR_height      = imagesy($QR);//二维码图片高度
	            $logo_width     = imagesx($logo);//logo图片宽度
	            $logo_height    = imagesy($logo);//logo图片高度
	            $logo_qr_width  = $QR_width / 5;
	            $scale          = $logo_width/$logo_qr_width;
	            $logo_qr_height = $logo_height/$scale;
	            $from_width     = ($QR_width - $logo_qr_width) / 2;
	            //重新组合图片并调整大小
	            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);
	        }
	         
	        //输出图片
	        $temp_dir = TEMP_UPLOAD_DIR.date("Ymd").'/';
	        is_dir ( $temp_dir ) or mkdir ( $temp_dir, 0775, TRUE );
	        $path = $temp_dir.create_file_name().'.jpg';
	        imagepng($QR,$path);
	        unlink('temp_qrcode.png');
	        
	        $data['qr_code_url'] = str_replace(TEMP_UPLOAD_DIR,'/',$path);
	        
	        $this->user_model->update_user_info($uid,$data);
	        
	        $data['qr_code_img_url'] = IMG_URL.'upload/qr_code'.$data['qr_code_url'];
	    }else{
	        $data['qr_code_img_url'] = IMG_URL.'upload/qr_code'.$user_info['qr_code_url'];
	    }
	    
	    
	    $this->load->view('user/display_qr_code',$data);
	    
	}
	
	
	//获取OPENID
	public function get_openid(){
	    $uid = (int)$_SESSION['user']['uid'];
	    $openid_key = $uid.'_'.WEIXIN_OPENID_KEY;
	    $this->load->model('weixin_model');
	    
	    $_SESSION['user']['openid'] = $this->weixin_model->get_openid_to_mysql($openid_key);
	    
	    if(empty($_SESSION['user']['openid'])){
	        $_SESSION['user']['openid'] = $this->weixin_model->get_openid($openid_key);
	    }
	    
	}
	
	
	/**
	 * 支付注册金额
	 *
	 */
	public function pay_register(){
	    $uid = (int)$_SESSION['user']['uid'];
	    if(empty($uid)) {
	        go('请先登录','login/index');
	    }
	
	
	    //判断是否是被人邀请过来的 并且已经支付注册金额
	    $invited_info = $this->user_model->tf_invited($uid);
	    if(empty($invited_info)) {
	        go('非法注册',GW_URL);
	    }
	    //判断是否支付过
	    if($invited_info['is_pay'] == 1){
	        go('已经支付过了','index/index');
	    }
	    $pay_info = $this->pay_model->pay_info($uid,PAY_TYPE_REG);
	    if($pay_info['status'] != 0){
	        go('已经支付过了','index/index');
	    }
	     
	    include '../libraries/Payment/drivers/wxpay/example/WxPay.JsApiPay.php';
	    include "../libraries/Payment/drivers/wxpay/lib/WxPay.Api.php";
	
	    $tools = new JsApiPay();
	
	    $input = new WxPayUnifiedOrder();
	    
	    $input->SetBody("注册支付号:".$pay_info['myself_trade_no']);
	    $input->SetOut_trade_no($pay_info['myself_trade_no']);
	
	    //订单金额 微信单位是分
	    $fee = $pay_info['price'] * 100;
	    $input->SetTotal_fee($fee);
	    $input->SetTime_start(date("YmdHis"));
	    $input->SetTime_expire(date("YmdHis", time() + 600));
	
	    $notify = 'http://'.$_SERVER['HTTP_HOST'].'/api/wxpay';
	    $input->SetNotify_url($notify);
	    $input->SetTrade_type("JSAPI");
	    $input->SetOpenid($_SESSION['user']['openid']);
	
	    $result = WxPayApi::unifiedOrder($input);
	    if(isset($result['err_code_des']) && $result['err_code_des'] == '该订单已支付'){
	        $res = $this->pay_model->pay_response($pay_info['myself_trade_no'],$result['nonce_str']);
	        if($res){
	            go('注册成功!','index/index');
	        }else{
	            go('注册失败!','index/index');
	        }
	        exit();
	    }elseif(isset($result['result_code']) &&  $result['result_code'] == 'FAIL'){
	        go($result['err_code_des'],'index/index');
	        exit();
	    }
	
	    $data['jsApiParameters'] = $tools->GetJsApiParameters($result);
	    $data['pay_id']    = $pay_info['id'];
	    $data['pay_money'] = $pay_info['price'];
	
	    $this->load->view('pay/wx_pay',$data);
	}
	
	/*
	 * 查看组织框架
	 */
	public function frame(){
	    $uid = $this->user['uid'];
	    
	    $up_info = array();
	    $son_info = array();
	    $son_son_info = array();
	    $son_son_son_info = array();
	    
	    //获取上一级
	    $up_info = $this->user_model->get_parent_info($uid);
	    
	    //获取下一级
	    $son_info = $this->user_model->get_son_info($uid);
	    if(!empty($son_info)){
	        $son_son_uid = array_column($son_info, 'uid');
	        //获取下下一级
	        $son_son_info = $this->user_model->get_son_info($son_son_uid);
	        if(!empty($son_son_info)){
	            $son_son_son_uid = array_column($son_son_info, 'uid');
	            $son_son_son_info = $this->user_model->get_son_info($son_son_son_uid);
	        }
	    }
	    
	    $data['up_info']          = $up_info;
	    $data['son_info']         = $son_info;
	    $data['son_son_info']     = $son_son_info;
	    $data['son_son_son_info'] = $son_son_son_info;
	    
	    $this->load->view('user/frame',$data);
	}
	
	/**
	 * 资金管理
	 */
	public function zijin_manage(){
	    
	    $this->load->view('user/zijin');
	}
	
	/**
	 * 专属链接
	 */
	public function my_link(){
	    $uid = $this->user['uid'];
	    
	    $data['link'] = 'http://www.yctfgw.com/register?tj_uid='.$uid;
	    $this->load->view('user/my_link',$data);
	}
}
?>
