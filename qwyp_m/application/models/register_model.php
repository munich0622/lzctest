<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
/**
 * 用户任务模型
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
	 * 注册用户信息
	 */
	public function insert_table_data($data,$table){
	    if(!in_array($table,array('user','pay','invited'))){
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
	
	
	
	
}

?>
