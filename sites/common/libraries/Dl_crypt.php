<?php
/**
 * crypt加/解密方法，包括：
 * encode/decode (双重加/解密）
 * @author 刘志超
 * @version 2015.4.16
 */
class Dl_crypt{
    protected $key;
    protected $iv;
    
    /**
     * $config['key'] string 密钥
     * $config['iv] string 密钥向量数组，用逗号分割，例如：'0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF'
     */
    function __construct($config){
        $this->key = isset($config['key'])?$config['key']:die('缺少参数：key');
        $iv = isset($config['iv'])?$config['iv']:die('缺少参数：iv');
        $iv = explode(',', $iv);

        $this -> iv = '';
        foreach ($iv as $byte){
            $byte = hexdec(trim($byte));
            $this -> iv .= chr($byte);
        }
    }

    
    protected function _mcrypt_cbc($cc, $key, $iv, $encode=TRUE){
        $cipher = mcrypt_module_open(MCRYPT_BLOWFISH,'','cbc','');
        mcrypt_generic_init($cipher, $key, $iv);
        $ret = $encode?mcrypt_generic($cipher,$cc):mdecrypt_generic($cipher, $cc);
        mcrypt_generic_deinit($cipher);
        return $ret;
    }

    protected function _pkcs5Pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    protected function _pkcs5Unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, -1 * $pad);
    }

    protected function _encrypt($str, $key) {
        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
        $str = $this -> _pkcs5Pad($str, $size);
        return base64_encode(($this->_mcrypt_cbc($str, $key, $this ->iv)));
    }

    protected function _decrypt($str, $key) {
        $strBin = base64_decode($str);
        $str = $this->_mcrypt_cbc($strBin, $key, $this -> iv, FALSE);
        $str = $this -> _pkcs5Unpad($str);
        return $str;
    }

    public function encode($str) {
        $en = $this -> _encrypt($str, $this -> key);
        $key2 = substr(md5($en), 0, 8);
        $en2 = $this -> _encrypt($en, $key2);
        return substr_replace($en2, $key2, 5, 0);
    }

    public function decode($str) {
        $key2 = substr($str, 5, 8);
        $en2 = substr_replace($str, '', 5, 8);
        $str = $this -> _decrypt($en2, $key2);
        return $this -> _decrypt($str, $this -> key);
    }
}