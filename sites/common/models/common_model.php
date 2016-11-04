<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 公共用户model
 *
 * @author 刘志超
 * @date 2015-04-16
 */
class Common_model extends CI_Model{
	function __construct() {
		parent::__construct ();
		$this->load->database ();
	}
	
	
	
    /**
	 * 插入数据
	 * @param unknown $tabel
	 * @param unknown $data
	 */
	public function add_data($tabel,$data){
        $this->db->insert($tabel, $data);
        
        return $this->db->insert_id();
    }
	
    /**
     * 修改数据
     * @param unknown $id
     * @param unknown $tabel
     * @param unknown $data
     */
    public function update_data($where,$tabel,$data){
        if(empty($where)){
            return false;
        }
        return $this->db->where($where)->update($tabel, $data);
    }

}