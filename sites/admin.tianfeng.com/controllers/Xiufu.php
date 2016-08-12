<?php

class Xiufu extends Admin_Controller{
    
	function __construct(){
		parent::__construct();
		$this->load->model('xiufu_model');
	}
	
	
	/**
	 * 修复支付订单
	 */
	public function xiufu_pay(){
	    $data['menu_name'] = '修复订单';
	    
	    $this->load->view('xiufu/xiufu_pay',$data);
	}
	
	/**
	 * 修复支付提交
	 */
	public function xiufu_pay_sub(){
	     $order_sn = $this->input->post('order_sn',TRUE);
	     
	     $pay_info = $this->xiufu_model->get_pay_info($order_sn);
	     
	     if(empty($pay_info)){
	         go('查找不到该订单号','/xiufu/xiufu_pay');
	     }
	     
	     if($pay_info['status'] == 1){
	         go('该订单已经被修复','/xiufu/xiufu_pay');
	     }
	     
	     $res = $this->xiufu_model->pay_response($order_sn);
	     if($res){
	         go('修复成功','/xiufu/xiufu_pay');
	     }else{
	         go('修复失败','/xiufu/xiufu_pay');
	     }
	}
	
	/**
	 * 读取csv
	 */
	public function opencsv(){
	    
	    $data['menu_name'] = '读取csv';
	     
	    $this->load->view('xiufu/open_csv',$data);
	    
	    
	}
	
	/**
	 * 读取csv
	 */
	public function opencsv_sub(){
	    
	    if(!isset($_FILES['csv']['name']) || empty($_FILES['csv']['name'])){
	        go('找不到上传文件','/xiufu/opencsv');
	    }
	    
	    $type = pathinfo($_FILES['csv']['name'])['extension'];
	    if($type != 'csv'){
	        go('文件类型错误','/xiufu/opencsv');
	    }
	    $row  = 1;
	    $file = fopen($_FILES['csv']['tmp_name'],"r");
        while ($data = fgetcsv($file, 1000, ",")) { 
            if($row > 1){
                $order[] = substr($data[2], 1);
            }
            $row++;
        } 
	    fclose($file);
	    
	    foreach ($order as $key=>$val){
	        $res = $this->xiufu_model->update_pay($val);
	        var_dump($res);
	    }
	}
}
?>
