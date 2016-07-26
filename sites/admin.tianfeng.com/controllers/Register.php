<?php

class Register extends MY_Controller{
    
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('register_model');
	
	}
	
	/**
	 * 个人资料管理
	 */
	public function index(){
	    //推荐人的uid
	    $data['uid']  = (int)$this->input->get('tj_uid',TRUE);
	    $data['bank'] = $this->user_model->bank_list();
	    
	    $this->load->view('user/register',$data);
	}
	
	/**
	 * 注册提交
	 */
	public function register_sub(){
	    $phone            = $this->input->post('phone',TRUE);
	    $uname            = $this->input->post('uname',TRUE);
	    $weixin_name      = $this->input->post('weixin_name',TRUE);
	    $id_card          = $this->input->post('id_card',TRUE);
	    $bank_num         = $this->input->post('bank_num',TRUE);
	    $bank             = (int)$this->input->post('bank',TRUE);
	    $password         = $this->input->post('password',TRUE);
	    $password_confirm = $this->input->post('password_confirm',TRUE);
	    $tj_uid           = (int)$this->input->post('tj_uid',TRUE);
	    if(empty($password) || strlen($password)<6 || strlen($password) > 20) {
	        goback('密码要在6-20位');
	    }
	    
	    if($password_confirm != $password) {
	        goback('两次输入的密码不一致');
	    }
	    
	    $res = $this->user_model->get_bank($bank);
	    if(!$res){
	        goback('找不到该银行');
	    }
	    $res = $this->user_model->get_user(array('uname'=>$uname));
	    if($res){
	        goback('用户名重复');
	    }
	    $res = $this->user_model->get_user(array('phone'=>$phone));
	    if($res){
	        goback('手机号重复');
	    }
	    $res = $this->user_model->get_user(array('weixin_name'=>$weixin_name));
	    if($res){
	        goback('微信名重复');
	    }
	    $res = $this->user_model->get_user(array('id_card'=>$id_card));
	    if($res){
	        goback('身份证号码重复');
	    }
	    
	    $tj_uid = 1;
	    $tj_uinfo = $this->user_model->get_user(array('uid'=>$tj_uid));
	    if(!$tj_uinfo){
	        goback('找不到该推荐人');
	    }
	    
	    $data['phone']       = $phone;
	    $data['uname']       = $uname;
	    $data['weixin_name'] = $weixin_name;
	    $data['id_card']     = $id_card;
	    $data['bank_num']    = $bank_num;
	    $data['bank']        = $bank;
	    $data['salt']        = $this->_rank_string();
	    $data['password']    = en_pass($password, $data['salt']);
	    $data['tj_uid']      = $tj_uid;
	    $data['reg_time']    = time();
	    $data['status']      = $tj_uinfo['space'] > 1 ? 2 : 0;
	    $data['level']       = 1;
	    $data['space']       = $tj_uinfo['space'];
	    $data['is_need_invited'] = $tj_uinfo['space'] == 1 ? 0 : 1;
	    
	    
	    $this->db->trans_begin();
	    //插入注册信息
	    $uid = (int)$this->register_model->insert_table_data($data,'user');
	    if($uid == 0){
	        $this->db->trans_rollback();
	        goback('注册失败');
	    }
	    
	    $invited_data['invited_uid']    = $tj_uid;
	    $invited_data['be_invited_uid'] = $uid;
	    $invited_data['invited_time']   = time();
	    $invited_data['space']          = $tj_uinfo['space'];
	    $invited_id = $this->register_model->insert_table_data($invited_data,'invited');
	    if($invited_id == 0){
	        $this->db->trans_rollback();
	        goback('注册失败');
	    }
	    //插入支付表
	    if($tj_uinfo['space'] == 1){
	        $register_money = PLATE_ONE_GRADE_ONE;
	    }elseif($tj_uinfo['space'] == 2){
	        $register_money = PLATE_TWO_GRADE_ONE;
	    }else{
	        $register_money = PLATE_THREE_GRADE_ONE;
	    }
	    $pay_data['myself_trade_no'] = create_order_sn(1);
	    $pay_data['pay_uid']         = $uid;
	    $pay_data['receive_uid']     = $tj_uid;
	    $pay_data['type']            = PAY_TYPE_REG;
	    $pay_data['price']           = $register_money;
	    $pay_data['content']         = '新用户注册费用';
	    $pay_data['time']            = time();
	    $pay_data['status']          = 0;
	    $pay_data['space']           = $tj_uinfo['space'];
	    $pay_id = $this->register_model->insert_table_data($pay_data,'pay');
	    if($pay_id == 0){
	        $this->db->trans_rollback();
	        goback('注册失败');
	    }
        $this->db->trans_commit();
        
        $user = $this->user_model->get_user(array('uid'=>$uid));
        
        $_SESSION['user'] = $user;
        go('注册成功','/login/');
	}
	
	
	
	
	/**
	 * 产生随机字符
	 * @author 刘志超
	 * @param int $length 产生随机字符长度
	 * @date 2015-04-17
	 * @return string
	 */
	private function _rank_string($length = 6 ){
	    // 密码字符集，可任意添加你需要的字符+
	    $chars = '123456789abcdefghijklmnopqrstuvwxyz';
	    $string = '';
	    for($i = 0; $i < $length; $i ++) {
	        $string .= $chars [mt_rand ( 0, strlen ( $chars ) - 1 )];
	    }
	    return $string;
	}
}
?>
