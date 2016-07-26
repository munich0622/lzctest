<?php 
if(!defined('RADIX32')){
	define('RADIX32', '0123456789abcdefghijklmnopqrstuv');
}
if(!defined('INTERFACE_KEY')){
	define('INTERFACE_KEY', '12345678');
}
if(!defined('INTERFACE_IV')){
	define('INTERFACE_IV', 'abcdefgh');
}
    // 加密函数
if(!function_exists('str_encrypt')) {
    function str_encrypt($str) {
        $tmp_key = md5(rand(0, 0xffff));
        $key_len = strlen($tmp_key);
        $str_len = strlen($str);

        $ret = "";
        for ($i = 0; $i < $str_len; $i++) {
            $j = $i % $key_len;
            $p1 = get_radix32_idx(substr($str, $i, 1));
            $p2 = get_radix32_idx(substr($tmp_key, $j, 1));
            $p = $p1 ^ $p2;
            $ret .= substr(RADIX32, $p2, 1);
            $ret .= substr(RADIX32, $p, 1);
        }
        return $ret;
    }
}
if(!function_exists('str_decrypt')) {
    //解密函数
    function str_decrypt($str) {
        $ret = "";
        $str_len = strlen($str);
        for ($i = 0; $i < $str_len; $i++) {
            $md = substr($str, $i++, 1);
            $ed = substr($str, $i, 1);
            $p1 = get_radix32_idx($md);
            $p2 = get_radix32_idx($ed);
            $p = $p1 ^ $p2;
            $ret .= substr(RADIX32, $p, 1);
        }
        return $ret;
    }
}

if(!function_exists('get_radix32_idx')) {    
	// 获取一个字符在 radix32 中的序号
    function get_radix32_idx($char) {
        $idx = strpos(RADIX32, $char);
        if (!is_int($idx)) $idx = -1;
        return $idx;
    }
}
    
    
    //------------------------------以下des加密解密算法-------------------------------------//
if(!function_exists('inter_encrypt')) {    
    //加密
    function inter_encrypt($str,$key)
    {
    	$size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
    	$str = inter_pkcs5Pad ( $str, $size );
    
    	$data=inter_mcrypt_cbc($str, $key,INTERFACE_IV);
    	return base64_encode($data);
    }
}
    
if(!function_exists('inter_decrypt')) {    
    //解密
    function inter_decrypt($str,$key){
    	$str = base64_decode ($str);
    	$str = inter_mcrypt_cbc($str, $key, INTERFACE_IV, FALSE );
    	$str = inter_pkcs5Unpad( $str );
    	return $str;
    }
}

if(!function_exists('inter_pkcs5Pad')) {   
    function inter_pkcs5Pad($text, $blocksize){
    	$pad = $blocksize - (strlen ( $text ) % $blocksize);
    	return $text . str_repeat ( chr ( $pad ), $pad );
    }
}
if(!function_exists('inter_pkcs5Unpad')) {   
    function inter_pkcs5Unpad($text){
    	$pad = ord ( $text {strlen ( $text ) - 1} );
    	if ($pad > strlen ( $text ))
    		return false;
    	if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
    		return false;
    	return substr ( $text, 0, - 1 * $pad );
    }
}
if(!function_exists('inter_mcrypt_cbc')) {    
    function inter_mcrypt_cbc($cc, $key, $iv, $encode=TRUE){
    	$cipher = mcrypt_module_open(MCRYPT_DES,'','cbc','');
    	mcrypt_generic_init($cipher, $key, $iv);
    	$ret = $encode?mcrypt_generic($cipher,$cc):mdecrypt_generic($cipher, $cc);
    	mcrypt_generic_deinit($cipher);
    	return $ret;
    }
}


//最终加密
if(!function_exists('last_encrypt')) {
	function last_encrypt($str){
		$key  = str_encrypt(time());
		$pass = substr(md5($key.INTERFACE_KEY),0,8);
		$data =  inter_encrypt($str,$pass);
		return $key.$data;
	}
}

//最终解密
if(!function_exists('last_decrypt')) {
	function last_decrypt($data){
		$key = substr($data, 0,20);
		$mykey = substr(md5($key.INTERFACE_KEY),0,8);
		$json = inter_decrypt(substr($data, 20),$mykey);
		return json_decode($json,TRUE);
	}
}
?>