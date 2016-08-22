<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
/**
 * 支付模型
 *
 * @author 刘志超
 * @date 2015-04-21
 */
class Pay_model extends CI_Model{
	public function __construct(){
		parent::__construct();
		$this->load->database();
	
	}
	
	
	public function pay_list($where,$limit = 20,$page = 1){
	    $page   = $page > 0 ? $page : 1;
	    $offset = ($page - 1) * $limit;
	    $sql = " SELECT count(1) as count FROM tf_pay AS tp
	    LEFT JOIN tf_dakuan_log AS tdl ON tp.dakuan_id = tdl.id {$where} ";
	    
	    $num = $this->db->query($sql)->row_array();
	    
	    $sql = " SELECT tp.id,tp.pay_uid,tp.receive_uid,tp.type,tp.price,tp.content,tp.time,tp.status,tp.dakuan_id,tdl.dk_money,tdl.service_money,tdl.time as dk_time 
	             FROM tf_pay AS tp 
	             LEFT JOIN tf_dakuan_log AS tdl ON tp.dakuan_id = tdl.id 
	             {$where} ORDER BY tp.dakuan_id ASC,tp.id DESC LIMIT {$offset}, {$limit}";
	    
	    echo $sql;exit;
	    $list = $this->db->query($sql)->result_array();
	    
	    $data['total'] = $num['count'];
	    $data['list']  = $list;
	    
	    return $data;
	}
	
	/*
	 * 获取支付信息
	 */
	public function pay_info($id){
	    $id = intval($id);
	    
	    $sql = " SELECT * from tf_pay WHERE id = {$id} ";
	    
	    return $this->db->query($sql)->row_array();
	}
	
	/**
	 * 记录打款日志
	 */
	public function update_dakuan_log($pay_info,$money){
	    if(empty($pay_info) || !is_array($pay_info) || $money <= 0){
	        return false;
	    }
	    
	    $data = array(
	        'pay_id'        => $pay_info['id'],
	        'receive_uid'   => $pay_info['receive_uid'],
	        'dk_money'      => $money,
	        'service_money' => bcsub($pay_info['price'], $money),
	        'time'          => time()
	    );
	    
	   $this->db->insert('dakuan_log',$data);
	   if(!$this->db->insert_id()){
	       return false;
	   }
	   
	   return $this->db->insert_id();
	}
	
	/**
	 * 修改打款状态
	 */
	public function update_dakuan_status($pay_id,$dakuan_id){
	    $dakuan_id = intval($dakuan_id);
	    $pay_id = intval($pay_id);
	    if(empty($dakuan_id) || empty($pay_id)){
	        return false;
	    }
	    
	    $this->db->where('id',$pay_id)->update('pay',array('dakuan_id'=>$dakuan_id));
	    
	    if($this->db->affected_rows() != 1){
	        return false;
	    }
	    
	    return true;
	}
}

?>
