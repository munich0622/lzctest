<?php  

/**
 * 用户密码加密
 */
function password($username, $password, $salt){
    return md5(md5($password). $username.$salt);
}


/**
 * 缓存用户登录信息
 */
function set_login_info($user) {
    $data = array(
        'id'       => 0,
        'username' => '',
        'mobile'   => '',
        'email'    => '',
        'address_id'    => '0'
    );
    foreach ($data as $key => $value) {
        if(isset($user[$key]))
            $data[$key] = $user[$key];
    }
    empty($user['nickname']) || $data['username'] = $user['nickname'];
    $_SESSION['user'] = $data;
    
    return TRUE;
}


/**
 * 获取用户信息
 * @return array
 */
function get_user_info(){
    return isset( $_SESSION['user'] ) && is_array( $_SESSION['user'] ) ? $_SESSION['user'] : array() ;
}


/**
 * 获取校验码
 * @return str
 */
function get_salt() {
	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$str = '';
	for ($i = 0; $i < 4; $i++) {
		$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
	}
	return $str;
}


/**
 * 生成随机token
 */
function create_token(){
    return md5(date("YmdHis") + get_client_ip().rand(100000,999999));
}


/**
 * 退出登录
 */
function logout(){
    unset($_SESSION['user']);
    session_destroy();
}
?>


