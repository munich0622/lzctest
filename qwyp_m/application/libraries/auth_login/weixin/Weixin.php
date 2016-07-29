<?php

/*
 * To change this li s cense header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Weixin {

    private $code_url  = "https://open.weixin.qq.com/connect/qrconnect?";   //获取code url
    private $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?";
    private $config    = [];

    public function __construct() {
        if (empty($this->config)) {
            $this->config = require("weixin_config.php");
        }
        $this->config['code']['redirect_uri'] = urlencode($this->config['code']['redirect_uri']);
    }

    //获取code的请求地址
    public function get_code_url() {
        return $this->code_url . http_build_query($this->config['code']) . "#wechat_redirect";
    }
    
    /**
     * 根据 code 获取 token 
     * @param type $code   code 
     * @return string  token
     */
    public  function  get_token($code="") {
        if(empty($code)) {
            return "";
        }
        $param = $this->config['token'];
        $param['code']  = $code;
        
        $url = $this->token_url.http_build_query($param);
        $result = curl_post($url, $param);
        var_dump($result);
    }
    
    

}
