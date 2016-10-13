<?php

class User extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
		$this->load->helper('pagination');
	}
	
	
	
	
	/**
	 * 重置密码
	 */
	public function update_pas(){
		
	    $data['menu_name'] = '重置密码';
	    
	    $this->load->view('user/reset_pas',$data);
	}
	
	/**
	 * 重置密码提交
	 */
	public function reset_pas_sub(){
	    $phone = $this->input->post('phone',true);
	    
	    $res = $this->user_model->reset_pas($phone);
	    
	    if($res === true){
	        go('重置成功','/user/update_pas');
	    }elseif($res === '-1'){
	        go('用户不存在','/user/update_pas');
	    }else{
	        go('重置失败','/user/update_pas');
	    }
	}
	
	
	/**
	 * 点位对换
	 */
	public function swap(){
	    
	    $this->load->view('user/swap');
	}
	
	/**
	 * 点位对换
	 */
	public function swap_sub(){
	    $phone1 = $this->input->post('phone1',true);
	    $phone2 = $this->input->post('phone2',true);
	     
	    if(trim($phone1) == '' || trim($phone2) == ''){
	        ajax_response(false,'交换的两个的手机号不能为空');
	    }
	    
	    //禁止查询手机
	    if(in_array($phone1,$this->config->item("forbidden"))){
	        ajax_response(false,'找不到第一个手机号码的用户信息！');
	    }
 
	    if(in_array($phone2,$this->config->item("forbidden"))){
	        ajax_response(false,'找不到第二个手机号码的用户信息！');
	    }
	    
	    $uinfo1 = $this->user_model->get_user($phone1);
	    if(empty($uinfo1)){
	        ajax_response(false,'找不到第一个手机号码的用户信息！');
	    }
	     
	    $uinfo2 = $this->user_model->get_user($phone2);
	    if(empty($uinfo2)){
	        ajax_response(false,'找不到第二个手机号码的用户信息！');
	    }
	     
	    if($uinfo2['level'] != $uinfo1['level']){
	        ajax_response(false,'两个用户的等级不一样！');
	    }
	    
	    $res = $this->user_model->swap($uinfo1,$uinfo2);
	    if($res === true){
	        ajax_response(true,'交换成功！');
	    }
	    
	    ajax_response(false,'交换失败！');
	}
	
	/**
	 * 查询用户信息
	 */
	public function check_user_info(){
	    $phone1 = $this->input->post('phone1',true);
	    $phone2 = $this->input->post('phone2',true);
	    
	    if(trim($phone1) == '' || trim($phone2) == ''){
	        ajax_response(false,'交换的两个的手机号不能为空');
	    }
	    
	    //禁止查询手机 
	    if(in_array($phone1,$this->config->item("forbidden"))){
	        ajax_response(false,'找不到第一个手机号码的用户信息！');
	    }
	    
	    if(in_array($phone2,$this->config->item("forbidden"))){
	        ajax_response(false,'找不到第二个手机号码的用户信息！');
	    }
	    
	    $uinfo1 = $this->user_model->get_user($phone1);
	    if(empty($uinfo1)){
	        ajax_response(false,'找不到第一个手机号码的用户信息！');
	    }
	    
	    $uinfo2 = $this->user_model->get_user($phone2);
	    if(empty($uinfo2)){
	        ajax_response(false,'找不到第二个手机号码的用户信息！');
	    }
	    
	    if($uinfo2['level'] != $uinfo1['level']){
	        ajax_response(false,'两个用户的等级不一样！');
	    }
	    
	    $str  = '<tbody><tr><th>会员信息1：</th><td>用户名称:'.$uinfo1['uname'].'</td><th>会员信息2：</th><td>用户名称:'.$uinfo2['uname'].'</td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>当前点位:'.$uinfo1['uid'].'</td><td>&nbsp;</td><td>当前点位:'.$uinfo2['uid'].'</td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>手机号码:'.$uinfo1['phone'].'</td><td>&nbsp;</td><td>手机号码:'.$uinfo2['phone'].'</td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>微信名称:'.$uinfo1['weixin_name'].'</td><td>&nbsp;</td><td>微信名称:'.$uinfo2['weixin_name'].'</td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>身份证:'.$uinfo1['id_card'].'</td><td>&nbsp;</td><td>身份证:'.$uinfo2['id_card'].'</td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>所属银行:'.$uinfo1['id_card'].'</td><td>&nbsp;</td><td>所属银行:'.$uinfo2['id_card'].'</td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>银行卡号:'.$uinfo1['bank_num'].'</td><td>&nbsp;</td><td>银行卡号:'.$uinfo2['bank_num'].'</td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>用户等级:'.$uinfo1['level'].'</td><td>&nbsp;</td><td>用户等级:'.$uinfo2['level'].'</td></tr>';
	    $str .= '</tbody>';
	    
	    
	    ajax_response(true,$str);
	    
	}
	
	/**
	 * 查询会员等级以及组织框架
	 */
	public function user_frame(){
	    
	    $this->load->view('user/user_frame');
	}
	
	/**
	 * 查询会员等级以及组织框架提交
	 */
	public function user_frame_sub(){
	    $uname = $this->input->post('uname',true);
	    if(empty($uname)){
	        ajax_response(false,'找不到此人的信息');
	    }
	    

	    $uinfo = $this->user_model->get_user_to_name($uname);
	    if(empty($uinfo)){
	        ajax_response(false,'找不到此人的信息');
	    }
	    
	    $son_info = array();
	    $son_son_info = array();
	    $son_son_son_info = array();
	    
	    //获取下级
	    $son_info = $this->user_model->get_son_info($uinfo['uid']);
	    
	    if(!empty($son_info)){
	        $son_son_uid = array_column($son_info, 'uid');
	        //获取下下一级
	        $temp_son_son_info = $this->user_model->get_son_info($son_son_uid);
	            
	        if(!empty($temp_son_son_info)){
	            foreach ($temp_son_son_info as $key=>$val){
	                if($son_info[0]['uid'] == $val['puid']){
	                    $son_son_info['left'][] = $val;
	                }
	                if(isset($son_info[1]['uid']) && $son_info[1]['uid'] == $val['puid']){
	                    $son_son_info['right'][] = $val;
	                }
	            }
	            
	            $temp_son_son_son_uid = array_column($temp_son_son_info, 'uid');
	            $temp_son_son_son_info = $this->user_model->get_son_info($temp_son_son_son_uid);
	            if(!empty($temp_son_son_son_info)){
	                foreach ($temp_son_son_son_info as $key=>$val){
	                    if(isset($son_son_info['left'][0]['uid']) && $son_son_info['left'][0]['uid'] == $val['puid']){
	                        $son_son_son_info['left_left'][] = $val;
	                    }
	                    if(isset($son_son_info['left'][1]['uid']) && $son_son_info['left'][1]['uid'] == $val['puid']){
	                        $son_son_son_info['left_zhong'][] = $val;
	                    }
	                    if(isset($son_son_info['right'][0]['uid']) && $son_son_info['right'][0]['uid'] == $val['puid']){
	                        $son_son_son_info['right_zhong'][] = $val;
	                    }
	                    if(isset($son_son_info['right'][1]['uid']) && $son_son_info['right'][1]['uid'] == $val['puid']){
	                        $son_son_son_info['right_right'][] = $val;
	                    }
	                }
	            }
	        }
	    }
	    
	    
	    
	    
	    $str  = '<tbody><tr><td colspan="8" style="text-align:center">'.$uinfo['uname'].'('.$uinfo['level'].')</td></tr>';
	    $str .= '<tr>';
	    $str .= '<td colspan="4" style="text-align:center">'.(isset($son_info[0]['uname']) ? $son_info[0]['uname'].'('.$son_info[0]['level'].')' : '暂无').'</td>';
	    $str .= '<td colspan="4" style="text-align:center">'.(isset($son_info[1]['uname']) ? $son_info[1]['uname'].'('.$son_info[1]['level'].')' : '暂无').'</td>';
	    $str .= '</tr>';
	    $str .= '<tr>';
	    $str .= '<td colspan="2" style="text-align:center">'.(isset($son_son_info['left'][0]['uname']) ? $son_son_info['left'][0]['uname'].'('.$son_son_info['left'][0]['level'].')' : '暂无').'</td>';
	    $str .= '<td colspan="2" style="text-align:center">'.(isset($son_son_info['left'][1]['uname']) ? $son_son_info['left'][1]['uname'].'('.$son_son_info['left'][1]['level'].')' : '暂无').'</td>';
	    $str .= '<td colspan="2" style="text-align:center">'.(isset($son_son_info['right'][0]['uname']) ? $son_son_info['right'][0]['uname'].'('.$son_son_info['right'][0]['level'].')' : '暂无').'</td>';
	    $str .= '<td colspan="2" style="text-align:center">'.(isset($son_son_info['right'][1]['uname']) ? $son_son_info['right'][1]['uname'].'('.$son_son_info['right'][1]['level'].')' : '暂无').'</td>';
	    $str .= '</tr>';
	    $str .= '<tr>';
	    $str .= '<td style="text-align:center">'.(isset($son_son_son_info['left_left'][0]['uname']) ? $son_son_son_info['left_left'][0]['uname'].'('.$son_son_son_info['left_left'][0]['level'].')' : '暂无').'</td>';
	    $str .= '<td style="text-align:center">'.(isset($son_son_son_info['left_left'][1]['uname']) ? $son_son_son_info['left_left'][1]['uname'].'('.$son_son_son_info['left_left'][1]['level'].')' : '暂无').'</td>';
	    $str .= '<td style="text-align:center">'.(isset($son_son_son_info['left_zhong'][0]['uname']) ? $son_son_son_info['left_zhong'][0]['uname'].'('.$son_son_son_info['left_zhong'][0]['level'].')' : '暂无').'</td>';
	    $str .= '<td style="text-align:center">'.(isset($son_son_son_info['left_zhong'][1]['uname']) ? $son_son_son_info['left_zhong'][1]['uname'].'('.$son_son_son_info['left_zhong'][1]['level'].')' : '暂无').'</td>';
	    $str .= '<td style="text-align:center">'.(isset($son_son_son_info['right_zhong'][0]['uname']) ? $son_son_son_info['right_zhong'][0]['uname'].'('.$son_son_son_info['right_zhong'][0]['level'].')' : '暂无').'</td>';
	    $str .= '<td style="text-align:center">'.(isset($son_son_son_info['right_zhong'][1]['uname']) ? $son_son_son_info['right_zhong'][1]['uname'].'('.$son_son_son_info['right_zhong'][1]['level'].')' : '暂无').'</td>';
	    $str .= '<td style="text-align:center">'.(isset($son_son_son_info['right_right'][0]['uname']) ? $son_son_son_info['right_right'][0]['uname'].'('.$son_son_son_info['right_right'][0]['level'].')' : '暂无').'</td>';
	    $str .= '<td style="text-align:center">'.(isset($son_son_son_info['right_right'][1]['uname']) ? $son_son_son_info['right_right'][1]['uname'].'('.$son_son_son_info['right_right'][1]['level'].')' : '暂无').'</td>';
	    $str .= '</tr>';
	    $str .= '</tbody>';
	    
	    
	    
	    ajax_response(true,$str);
	}
	
	/**
	 * 查询会员信息
	 */
	public function check_user(){
	     
	    $this->load->view('user/check_user');
	}
	
	/**
	 * 查询会员信息提交
	 */
	public function check_user_sub(){
	    $uname = $this->input->post('uname',true);
	    if(empty($uname)){
	        ajax_response(false,'找不到此人的信息');
	    }
	
	    $uinfo = $this->user_model->get_user_to_name($uname);
	    if(empty($uinfo)){
	        ajax_response(false,'找不到此人的信息');
	    }
	    
	    //获取银行信息
	    $bank_list = $this->user_model->bank_list();
	    $bank_html = '<select name="bank"><option value ="0">请选择</option>';
	    foreach ($bank_list as $key=>$val){
	        if($val['id'] == $uinfo['bank']){
	            $bank_html .= '<option value="'.$val['id'].'" selected="selected">'.$val['bank_name'].'</option>';
	        }else{
	            $bank_html .= '<option value="'.$val['id'].'">'.$val['bank_name'].'</option>';
	        }
	        
	    }
	    $bank_html .= '</select>';
	    $str  = '<tbody><tr><th>会员信息：</th><td>用户名称:'.$uinfo['uname'].'</td><td><input type="text" value="'.$uinfo['uname'].'" name="uname"></td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>手机号码:'.$uinfo['phone'].'</td><td><input type="text" value="'.$uinfo['phone'].'" name="phone"></td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>微信名称:'.$uinfo['weixin_name'].'</td><td><input type="text" value="'.$uinfo['weixin_name'].'" name="weixin_name"></td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>身份证:'.$uinfo['id_card'].'</td><td><input type="text" value="'.$uinfo['id_card'].'" name="id_card"></td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>所属银行:'.$uinfo['bank_name'].'</td><td>'.$bank_html.'</td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>银行卡号:'.$uinfo['bank_num'].'</td><td><input type="text" value="'.$uinfo['bank_num'].'" name="bank_num"></td></tr>';
	    $str .= '<tr><td>&nbsp;</td><td>用户等级:'.$uinfo['level'].'</td><td><input type="hidden" value="'.$uinfo['uid'].'" name="uid"><input type="submit"  value="提交修改"></td></tr>';
	    $str .= '</tbody>';
	
	
	    ajax_response(true,$str);
	}
	
	

	/**
	 * 用户列表
	 */
	public function one_user_list($page = 1){
	    $page_size = 50;
	    $where  = " where 1 ";
	    $result = $this->user_model->one_user_list($where,$page_size,$page);
	    $total  =  $result['total'];
	    $data['list'] = $result['list'];
	    
	     
	    $temp_bank_list = $this->user_model->bank_list(); 
	    foreach($temp_bank_list as $key=>$val){
	        $data['bank_list'][$val['id']] = $val['bank_name'];
	    }
	    $url = '/user/one_user_list/%d?'. urldecode ( $_SERVER ['QUERY_STRING'] );
	    $data['page_html'] = pagination ($page, ceil($total/$page_size), $url, 5, TRUE, TRUE, $total );
	     
	    $data['menu_name'] = '一级用户列表';
	    $this->load->view('user/one_user_list',$data);
	}
	
	/**
	 * 修改会员信息
	 */
	public function update_user_info(){
	    $uid = (int)$this->input->post('uid',true);
	    $data['uname']       = $this->input->post("uname",true);
	    $data['phone']       = $this->input->post("phone",true);
	    $data['weixin_name'] = $this->input->post("weixin_name",true);
	    $data['id_card']     = $this->input->post("id_card",true);
	    $data['bank']        = (int)$this->input->post("bank",true);
	    $data['bank_num']    = $this->input->post("bank_num",true);
	    
	    $url = '/user/check_user';
	    //查询是否已经有这个手机号码
	    $is_phone = $this->user_model->get_user_to_phone($data['phone']);
	    if(!empty($is_phone) && $is_phone['c'] > 0){
	        goback('要修改的手机号码已经被人注册了');
	    }
	    
	    $res = $this->user_model->update_info($uid,$data);
	    if($res){
	        go('修改成功',$url);
	    }else{
	        go('修改失败',$url);
	    }
	}
}
?>
