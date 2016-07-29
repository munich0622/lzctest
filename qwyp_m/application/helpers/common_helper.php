<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/* -----通用的常用函数----- */

//实例化数据库
function init_db($dbname = 'master') {
    static $db = array();
    if (isset($db[$dbname])) {
        return $db[$dbname];
    }
    $CI = &get_instance();
    return $db[$dbname] = $CI->load->database($dbname, true);
}

//实例化缓存
function init_cache($adapter = 'redis') {
    //ini_set('default_socket_timeout', -1); //不超时
    static $cache = array();
    if (!isset($cache[$adapter])) {
        $CI = &get_instance();
        $CI->load->driver('cache', array('adapter' => $adapter));
        $cache[$adapter] = $CI->cache;
    }
}

//css，js版本号
function static_version() {
    static $version = null;
    if (!empty($version)) {
        return $version;
    }
    init_cache('redis');
    $CI = &get_instance();
    $cache_key = 'pc::web::static_version';
    $result = $CI->cache->get($cache_key);
    if (!$result) {
        $result = date('YmdHis');
        $CI->cache->save($cache_key, $result, 86400);
    }
    return $result;
}

//图片版本号
function img_version() {
    static $version = null;
    if (!empty($version)) {
        return $version;
    }
    init_cache('redis');
    $CI = &get_instance();
    $cache_key = 'pc::web::img_version';
    $result = $CI->cache->get($cache_key);
    if (!$result) {
        $result = date('YmdHis');
        $CI->cache->save($cache_key, $result, 86400);
    }
    return $result;
}

//获取参数
function I($key = NULL, $xss_or_default = false) {
    $CI = &get_instance();
    if (empty($key)) {
        $post = $CI->input->post(null, $xss_or_default);
        $get  = $CI->input->get(null, $xss_or_default);
        return !$post ? $get : (!$get ? false : array_merge($post, $get));
    }
    if (is_array($key)) {
        foreach ($key as $value) {
            I($value);
        }
    }
    $key = explode(".", $key);
    if (count($key) <= 1) {
        switch ($key[0]) {
            case 'post':
                return $CI->input->post(null, $xss_or_default);
            case 'get':
                return $CI->input->get(null, $xss_or_default);
            default:
                $key   = explode("/", $key[0]);
                $value = $CI->input->get_post($key[0], stripos($key[1], 'x') !== false);
        }
    } else {
        $key[1] = explode("/", $key[1]);
        $key2   = $key[0];
        $value  = $CI->input->$key2($key[1][0], stripos($key[1][1], 'x') !== false);
        $key[1] = $key[1][1];
    }
    $filter = array('i' => 'intval', 't' => 'trim', 'h' => 'htmlspecialchars', 'u' => 'urlencode');
    for ($i = 0, $c = strlen($key[1]); $i < $c; $i++) {
        if (empty($filter[$key[1][$i]]))
            continue;
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                if (empty($v) && $xss_or_default !== false) {
                    continue;
                }
                $value[$k] = $filter[$key[1][$i]]($v);
            }
        } else {
            if (empty($value) && $xss_or_default !== false) {
                continue;
            }
            $value = $filter[$key[1][$i]]($value);
        }
    }
    return $value === false ? $xss_or_default : $value;
}

function L($obj_name, $name = '') {
    $CI         = &get_instance();
    $data       = explode(".", $obj_name);
    $load_array = array('m' => 'model', 'l' => 'library', 'h' => 'helper');
    $model      = $load_array[$data[0]];
    isset($load_array[$data[0]]) ? $CI->load->$model($data[1], $name) : false;
}

//获取返回地址
function back_url($default_url=''){
	if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
	    return 'javascript:window.history.back(-1);';
	}
	if($default_url != '') {
		return $default_url;
	}
	return '/';
}

//有小数点则保留，否则取整
function fmt($money) {
    if (stripos($money, '.') !== false) {
        $p = explode('.', $money);
        return intval($p[1]) > 0 ? fmt_2f($money) : intval($money);
    } else {
        return intval($money);
    }
}

//保留两位数字格式
function fmt_2f($num) {
    return sprintf("%.2f", round($num, 2));
}

//数字字符串
function num_str($str) {
    $str     = explode(',', $str);
    $str_arr = array();
    foreach ($str as $v) {
        $str_arr[] = (int) $v;
    }
    return join(',', $str_arr);
}

//建立路径
function my_mkdir($path) {
    if (is_dir($path))
        return TRUE;

    $paths = array();
    while (!is_dir($path)) {
        array_push($paths, basename($path));
        $path = dirname($path);
    }
    @chmod($path, 0777);
    while ($basename = array_pop($paths)) {
        $path .= DIRECTORY_SEPARATOR . $basename;
        @mkdir($path, 0777) or die('make dir access deny');
    }
    return TRUE;
}

//js提示信息
function jsalert($msg) {
    header("content-type: text/html;charset=utf-8");
    echo '<script>alert("' . str_replace("'", "\'", $msg) . '");</script>';
    exit;
}

//js提示信息并跳转
function go2($msg, $url) {
    header("content-type: text/html;charset=utf-8");
    echo '<script>alert("' . str_replace("'", "\'", $msg) . '");parent.window.location.href="' . $url . '"</script>';
    exit;
}

//js返回，若是表单并返回上一步将刷新表单数据
function history_back($msg = '', $deep = '-1') {
    header("content-type: text/html;charset=utf-8");
    if ($msg == '') {
        echo '<script type="text/javascript">window.history.back(' . $deep . ');"</script>';
        exit;
    } else {
        echo '<script type="text/javascript">alert("' . str_replace("'", "\'", $msg) . '");window.history.back(' . $deep . ');</script>';
        exit;
    }
}

//js返回，若是表单并返回的是上一步将保留表单数据
function history_go($msg = '', $deep = '-1') {
    header("content-type: text/html;charset=utf-8");
    if ($msg == '') {
        echo '<script type="text/javascript">window.history.go(' . $deep . ');</script>';
        exit;
    } else {
        echo '<script type="text/javascript">alert("' . str_replace("'", "\'", $msg) . '");window.history.go(' . $deep . ');</script>';
        exit;
    }
}

//返回json结果
function r($msg, $status = 0, $data = array()) {
    $ret = json_encode(array('m' => $msg, 's' => $status, 'd' => $data));

    /* $callback = $_REQUEST['callback'];
      if ($callback) {
      echo $callback.'('.$ret.')'; exit;
      }else{ */
    echo $ret;
    exit;
    //}
}

/**
 * @param int $len 字符长度
 * @param int $type 0数字字母混合,1纯字母,2纯数字
 */
function rand_str($len, $type = 0) {
    $chars_str = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z"
    );
    $chars_num = array(
        "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"
    );
    $chars     = array();
    if ($type == 0) {
        $chars = array_merge($chars_str, $chars_num);
    } elseif ($type == 1) {
        $chars = $chars_str;
    } elseif ($type == 2) {
        $chars = $chars_num;
    }
    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱

    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

//json_encode不转义中文
function my_json_encode($input) {
    // 从 PHP 5.4.0 起, 增加了这个选项.
    if (defined('JSON_UNESCAPED_UNICODE')) {
        return json_encode($input, JSON_UNESCAPED_UNICODE);
    }
    if (is_string($input)) {
        $text = $input;
        $text = str_replace('\\', '\\\\', $text);
        $text = str_replace(
                array("\r", "\n", "\t", "\""), array('\r', '\n', '\t', '\\"'), $text);
        return '"' . $text . '"';
    } else if (is_array($input) || is_object($input)) {
        $arr    = array();
        $is_obj = is_object($input) || (array_keys($input) !== range(0, count($input) - 1));
        foreach ($input as $k => $v) {
            if ($is_obj) {
                $arr[] = my_json_encode($k) . ':' . my_json_encode($v);
            } else {
                $arr[] = my_json_encode($v);
            }
        }
        if ($is_obj) {
            return '{' . join(',', $arr) . '}';
        } else {
            return '[' . join(',', $arr) . ']';
        }
    } else {
        return $input . '';
    }
}

//md5+密钥 加密
function md5_encrypt($encrypt_str) {
    return md5(md5($encrypt_str . SECRET_KEY) . SECRET_KEY);
}

//防问token，防刷
function access_token($token_name){
	$token = md5(md5(date("YmdHis")+rand(100000,999999)).get_client_ip().$token_name);
	return $_SESSION[$token_name] = substr($token, -10);
}

//记录日志
function log_msg($type, $content, $son_folder = '') {
    if (!in_array($type, array('error', 'warn', 'info', 'debug'))) {
        return false;
    }

    $dir = APPPATH . "logs/{$type}/";
    if (!empty($son_folder)) {
        $dir = $dir . trim($son_folder, "/") . "/";
    }
    my_mkdir($dir);
    $file = $dir . date('Ymd') . ".log";

    file_put_contents($file, date('Y-m-d H:i:s') . "：" . $content . "\n\r", FILE_APPEND);
}

/**
 * 对称加密算法之加密
 * @param String $string 需要加密的字串
 * @param String $skey 加密EKY
 * @return String
 */
function _encrypt($string='', $skey='key') {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}

/**
 * 对称加密算法之解密
 * @param String $string 需要解密的字串
 * @param String $skey 解密KEY
 * @return String
 */
function _decrypt($string='', $skey='key') {
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}

//获取访客ip
function get_client_ip() {
    if (!empty($_SERVER["HTTP_CLIENT_IP"]))
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    elseif (!empty($_SERVER["REMOTE_ADDR"]))
        $cip = $_SERVER["REMOTE_ADDR"];
    else
        $cip = "";
    return $cip;
}

//邮箱是否合法
function is_email($user_email) {
    $pattern = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false) {
        if (preg_match($pattern, $user_email)) {
            return true;
        }
    }
    return false;
}

//手机是否合法
function is_mobile($mobile) {
    return preg_match("/^(147|13[0-9]|15[0-9]|18[0-9]|17[0-9])\d{8}$/i", $mobile);
}

/**
 * 时间转为x分，x天
 */
function time_tip($time, $is_differ = false) {
    if (strlen(intval($time)) < 10 AND ! $is_differ) {
        $time = strtotime($time);
    }
    $differ = $is_differ ? $time : $time - time();
    $tip    = null;
    if ($differ <= 0) {
        $tip = '0秒';
    } elseif ($differ < 60) {   //按分钟
        $tip = $differ . '秒';
    } elseif ($differ < 3600) {   //按分钟
        $tip = ceil($differ / 60) . '分钟';
    } elseif ($differ < 86400) { //按小时
        $tip = floor($differ / 3600) . '小时';
    } elseif ($differ < 86400 * 30) { //按天数
        $tip = floor($differ / 86400) . '天';
    } elseif ($differ < 86400 * 30 * 12) { //按月数
        $tip = floor($differ / (86400 * 30)) . '月';
    } else {  //按年数
        $tip = floor($differ / (86400 * 30 * 12)) . '年';
    }
    return $tip;
}

//新截取字符串
function cut_str($str='',$length){
    $newstr = mb_substr($str,0,$length);
    if(mb_strlen($str) > $length){
        return $newstr.'...';
    }else{
        return $newstr;
    }
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

/**
 * 将xml转为array
 * @param string $xml
 * @throws WxPayException
 */
 function XmlToArray($xml)
{	
	if(!$xml){
		throw new WxPayException("xml数据异常！");
	}
    //将XML转为array
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
	
}

/**
 * 判断是否是微信浏览器
 */
function is_weixin(){
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        return TRUE;
    }
    return FALSE;
}

/**
 * 获取订单状态标题
 * define('OS_UNPAID',            	0);  //等待买家付款
define('OS_PAID',            	1);  //买家已付款
define('OS_PREPARING',          2);  //卖家配货中
define('OS_SHIPPING',           3);  //卖家已发货
define('OS_RECEIVED',           4);  //买家已收货
define('OS_INSTALLED',          5);  //已安装
define('OS_FINISH',           	6);  //订单完成
define('OS_RETURN_SUCCEED',     10);  //退款退货成功
define('OS_PAID_PART',     		11);  //买家已付订金
define('OS_CANCEL',           	21);  //订单取消
 */
function order_state_title($state){
    $state_array = array(
                        '0'  => '待付款',
                        '1'  => '待发货',
                        '2'  => '待收货',
                        '3'  => '待收货',
                        '4'  => '待评价',
                        '5'  => '待评价',
                        '6'  => '交易成功',
                        '10' => '退款退货成功',
                        '11' => '买家已付订金',
                        '21' => '订单取消',
                    );
    
    return $state_array[$state] ? $state_array[$state] : '未知状态' ;
}



