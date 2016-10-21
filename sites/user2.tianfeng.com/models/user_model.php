<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
/**
 * 用户任务模型
 *
 * @author 刘志超
 * @date 2015-04-21
 */
class User_model extends CI_Model{
	public function __construct(){
		parent::__construct();
		$this->load->database();
	
	}
	
	/**
	 * 获取用户信息
	 * $field string 字段名称
	 * $value string 字段对应的值
	 */
	public function get_user($field,$value){
	    if(!in_array($field,array('uid','phone','name')) || empty($value)){
	        return false;
	    }
	    $where = array(
	        $field => $value
	    );
	    return $this->db->get_where('ooo_user',$where)->row_array();
	}
	
	
}

?>
