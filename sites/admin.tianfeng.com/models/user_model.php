<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
/**
 * 用户模型
 *
 * @author 刘志超
 * @date 2015-04-21
 */
class User_model extends CI_Model{
	public function __construct(){
		parent::__construct();
		$this->load->database();
	
	}
	
	
	public function get_user_arr_info($user_arr){
	    if(empty($user_arr) || !is_array($user_arr)){
	        return false;
	    }
	    
	    $new_user_arr = array_unique($user_arr);
	    $sql = " SELECT tu.uid,tu.uname,tu.phone,tu.weixin_name,tu.bank_num,tb.bank_name FROM tf_user AS tu LEFT JOIN tf_bank AS tb 
	             ON tu.bank = tb.id WHERE uid IN ( ".implode(',', $new_user_arr)." )";
	    $temp = $this->db->query($sql)->result_array();
	    
	    if(empty($temp)){
	        return false;
	    }
	    
	    foreach ($temp as $key=>$val){
	        $new_arr[$val['uid']]['uname']       = $val['uname'];
	        $new_arr[$val['uid']]['phone']       = $val['phone'];
	        $new_arr[$val['uid']]['weixin_name'] = $val['weixin_name'];
	        $new_arr[$val['uid']]['bank_num']    = $val['bank_num'];
	        $new_arr[$val['uid']]['bank_name']   = $val['bank_name'];
	    }
	    
	    return $new_arr;
	}
	
}

?>
