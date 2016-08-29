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
	
	/**
	 * 重置用户密码
	 */
	public function reset_pas($phone){
	    $password = '123456';
	    $uinfo = $this->db->select('uid,salt')->where(array('phone'=>$phone))->get('user')->row_array();
		if(isset($uinfo) && !empty($uinfo)){
		    $password = en_pass($password, $uinfo['salt']);
			$res = $this->db->where('uid',$uinfo['uid'])->update('user',array('password'=>$password));
			if($res){
			    return true;
			}else{
			    return false;
			}
		}else{
		   return '-1';
		}
	}
	
	/**
	 * 获取用户信息
	 */
	public function get_user($phone){
	    
	    $res = $this->db->get_where('user',array('phone'=>$phone))->row_array();
	    
	    return $res;
	}
	
	/**
	 * 获取用户信息
	 */
	public function get_user_to_name($uname){
	     
	    $res = $this->db->get_where('user',array('uname'=>$uname))->row_array();
	     
	    return $res;
	}
	
	/**
	 * 交换用户信息
	 */
	public function swap($uinfo1,$uinfo2){
	    $data1 = array(
	        'uname'       => $uinfo2['uname'],
	        'phone'       => $uinfo2['phone'].'yctfgw',
	        'weixin_name' => $uinfo2['weixin_name'],
	        'id_card'     => $uinfo2['id_card'],
	        'bank_num'    => $uinfo2['bank_num'],
	        'bank'        => $uinfo2['bank'],
	        'salt'        => '8888',
	        'password'    => 'd46696a210e323c17dcdc759a23c7ea1',
	    );
	    
	    $data2 = array(
	        'uname'       => $uinfo1['uname'],
	        'phone'       => $uinfo1['phone'],
	        'weixin_name' => $uinfo1['weixin_name'],
	        'id_card'     => $uinfo1['id_card'],
	        'bank_num'    => $uinfo1['bank_num'],
	        'bank'        => $uinfo1['bank'],
	        'salt'        => '8888',
	        'password'    => 'd46696a210e323c17dcdc759a23c7ea1',
	    );
	    $last_phone1 = $uinfo2['phone'];
	    
	    
        $this->db->trans_begin();
	    
	    $this->db->where('uid',$uinfo1['uid'])->update('user',$data1);
	    
	    if($this->db->affected_rows() != 1){
	        $this->db->trans_rollback();
	        return false;
	    }
	    
	    $this->db->where('uid',$uinfo2['uid'])->update('user',$data2);
	    
	    if($this->db->affected_rows() != 1){
	        $this->db->trans_rollback();
	        return false;
	    }
	    
	    $this->db->where('uid',$uinfo1['uid'])->update('user',array('phone'=>$last_phone1));
	    if($this->db->affected_rows() != 1){
	        $this->db->trans_rollback();
	        return false;
	    }
	    
        $this->db->trans_commit();
        
	    return true;
	}
}

?>
