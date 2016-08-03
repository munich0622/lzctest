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
	
	
	public function pay_list($where,$limit = 20){
	    $page   = (int)$this->input->get('page',TRUE);
	    $page   = $page > 0 ? $page : 1;
	    $offset = ($page - 1) * $limit;
	    $sql = " SELECT count(1) as count FROM tf_pay AS tp
	    LEFT JOIN tf_dakuan_log AS tdl ON tp.dakuan_id = tdl.id {$where} ";
	    
	    $num = $this->db->query($sql)->row_array();
	    
	    $sql = " SELECT tp.id,tp.pay_uid,tp.receive_uid,tp.type,tp.price,tp.content,tp.time,tp.status,tdl.money,tdl.service_money,tdl.time as dk_time 
	             FROM tf_pay AS tp 
	             LEFT JOIN tf_dakuan_log AS tdl ON tp.dakuan_id = tdl.id 
	             {$where} ORDER BY tp.id DESC LIMIT {$offset}, {$limit}";
	    
	    
	    $list = $this->db->query($sql)->result_array();
	    
	    $data['total'] = $num['count'];
	    $data['list']  = $list;
	    
	    return $data;
	}
	
}

?>
