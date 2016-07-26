<?php


/**
 * 获取登录或指定用户信息
 * @author 刘志超
 * 依赖类库：crypt
 * 依赖配置项：cookie_name/cookie_crypt_key/cookie_crypt_iv
 * 依赖常量：KEY_COOKIE_CRYPT/KEY_COOKIE_CRYPT_IV
 * @param uid 想要获取的用户编号，留空则为获取当前登录用户
 */
function get_user($uid = NULL, $use_cache = TRUE) {
	$CI = &get_instance();
	if ($uid === NULL) {
		$cookie_name = $CI->config->item('cookie_name');
		$cookie = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : '';
		
		if (!$cookie)
			return FALSE;
		
		$cookie = explode('|', $cookie);

		$CI->load->library('dl_crypt', array('key' => KEY_COOKIE_CRYPT, 'iv' => KEY_COOKIE_CRYPT_IV));
		
		if (count($cookie) >= 3 && $auth = $CI->dl_crypt->decode($cookie[2])) {
			$auths = explode('|', $auth);
			$uid = $auths[0];
		} else {
			return FALSE;
		}
	}
	if($uid){
		$db = $CI->load->database ( 'default', TRUE );
		$row = $db->select('*')->get_where('user', array('uid' => $uid), 1)->row_array();
		if(!$row) return FALSE;
		return $row;
	}
	return FALSE;
}


/**
 * ajax返回 (输出并终止执行)
 * @author 刘志超
 * @param bool $success
 * @param string $code 错误代码
 * @param mixed $data 附加数据
 * @return void
 * @date 2015-04-16
 */
function ajax_response($success, $code = NULL, $data = NULL) {
	$success = ( bool ) $success;
	if ($data === NULL && is_array ( $code )) {
		isset ( $code ['data'] ) and $data = $code ['data'];
		isset ( $code ['code'] ) and $code = $code ['code'];
	}
	$code === NULL and $code = '';
	die ( json_encode ( compact ( 'success', 'code', 'data' ) ) );
}




/**
 * 获取IP地址
 * @author 刘志超
 * @param $format 返回IP格式
 *        	string（默认）表示传统的127.0.0.1，int或其它表示转化为整型，便于存放到数据库字段
 * @param $side IP来源
 *        	client（默认）表示客户端，server或其它表示服务端
 * @return string or int
 * @date 2015-04-16
 *  
 */
function ip($format = 'string', $side = 'client') {
	if ($side === 'client') {
		static $_client_ip = NULL;
		if ($_client_ip === NULL) {
			// 获取客户端IP地址
			$ci = &get_instance ();
			$_client_ip = $ci->input->ip_address ();
		}
		$ip = $_client_ip;
	} else {
		static $_server_ip = NULL;
		if ($_server_ip === NULL) {
			// 获取服务器IP地址
			if (isset ( $_SERVER )) {
				if ($_SERVER ['SERVER_ADDR']) {
					$_server_ip = $_SERVER ['SERVER_ADDR'];
				} else {
					$_server_ip = $_SERVER ['LOCAL_ADDR'];
				}
			} else {
				$_server_ip = getenv ( 'SERVER_ADDR' );
			}
		}
		$ip = $_server_ip;
	}
	
	return $format === 'string' ? $ip : bindec ( decbin ( ip2long ( $ip ) ) );
}



/**
 * 处理成功返回json数据
 * @param int $code:错误编码
 * @param string $msg:描述
 * @param string $data:数据
 * @author 刘志超
 * @version 2014-6-19
 */
function success($code , $msg = '', $data = NULL, $output = TRUE ,$is_encrypt = TRUE) {
	$code = intval($code);
	$ret = array (
			'code' => $code,
			'msg'  => $msg
	);
	$data !== NULL && ($ret ['data'] = $data);
	$json_str = json_encode ( $ret);

	if ($output) {
		if(INTERFACE_SWITCH && $is_encrypt){
			$key = str_encrypt(time());
			$pass = substr(md5($key.INTERFACE_KEY),0,8);
			$data = inter_encrypt($json_str,$pass);
			die ( $key.$data );
		}else{
			// 输出后停止程序
			die ( $json_str );
		}
		
	}else{
		// 返回json字符串
		return $json_str;
	}
}

/**
 * 处理失败返回json数据
 * @param int $code:错误编码
 * @param string $msg:描述
 * @param string $data:数据
 * @author 刘志超
 * @version 2014-6-19
 */
function failure($code , $msg = '', $data = NULL, $output = TRUE ,$is_encrypt = TRUE) {
	$code = intval($code);
	$ret = array (
			'code' => $code,
			'msg'  => $msg
	);
	$data !== NULL && ($ret ['data'] = $data);
	$json_str = json_encode ( $ret );

	if ($output) {
		if(INTERFACE_SWITCH && $is_encrypt){
			die ( last_encrypt($json_str) );
		}else{
			// 输出后停止程序
			die ( $json_str );
		}
	}else{
		// 返回json字符串
		return $json_str;
	}
}



//手机是否合法
function is_mobile($mobile) {
    return preg_match("/^(147|13[0-9]|15[0-9]|18[0-9]|17[0-9])\d{8}$/i", $mobile);
}

/**
 * 跳转
 * @param unknown $msg
 * @param unknown $url
 */
function go($msg, $url) {
    header("content-type: text/html;charset=utf-8");
    echo '<script>alert("'.str_replace("'", "\'", $msg).'");parent.window.location.href="'.$url.'"</script>';
    exit;
}

function goback($msg) {
    header("content-type: text/html;charset=utf-8");
    if($msg=='没有权限') {
        echo '<script>alert("'.str_replace("'", "\'", $msg).'");window.location.href="/index.php/admin/login"</script>';exit;
    }else {
        echo '<script>alert("'.str_replace("'", "\'", $msg).'");window.history.back()</script>';exit;
    }
}

function en_pass($password,$salt){
   return  strtolower ( md5 ( strtolower ( md5 ( $password ) . $salt ) ) );
}

/**
 * 生成文件名称
 *
 * @date 2015-09-18
 */
function create_file_name(){
    $file = microtime();
    $rand = rand(0,9);
    $file = str_replace(' ',$rand,$file);
    return str_replace('.',$rand,$file).rand(0,99999);
}
/**
 * 生成订单号 
 * @param $type 1注册
 */
function create_order_sn($type){
    $prefix = ENVIRONMENT == 'development' ? 't_' : 'f_';
    $mark = $prefix.$type.'_';
    return $mark.date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

/**
 * 	作用：array转xml
 */
function arrayToXml($arr) {
    $xml = "<xml>";
    foreach ($arr as $key => $val) {
        if (is_numeric($val)) {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
        } else
            $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
    }
    $xml .= "</xml>";
    return $xml;
}
?>
