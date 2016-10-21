<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
/**
 * 用户注册模型
 *
 * @author 刘志超
 * @date 2015-04-21
 */
class Register_model extends CI_Model{
	public function __construct(){
		parent::__construct();
		$this->load->database();
	
	}
	
	/**
	 * 获取邀请码是否存在
	 */
	public function get_invited_code($invited_code){
	    if(empty($invited_code)){
	        return false;
	    }
	    
	    return $this->db->get_where('ooo_invited_code',array('code'=>$invited_code))->row_array();
	}
	
	/**
	 * 注册用户信息
	 */
	public function insert_table_data($data,$table){
	    if(!in_array($table,array('ooo_user'))){
	        return false;
	    }
	    if(empty($data) || !is_array($data)){
	        return false;
	    }
	     
	    $this->db->insert($table,$data);
	    if($this->db->affected_rows() != 1){
	        return false;
	    }
	
	    return $this->db->insert_id();
	}
	
	/**
	 * 更新邀请码表
	 */
	public function update_invited_code($invited_code,$uid){
	    $uid = intval($uid);
	    if(empty($invited_code) || empty($uid)){
	        return false;
	    }
	    
	    $this->db->where('code',$invited_code)->update('ooo_invited_code',array('status'=>1,'uid'=>$uid));
	    if($this->db->affected_rows() != 1){
	        return false;
	    }
	    return true;
	}
	
}

?>
