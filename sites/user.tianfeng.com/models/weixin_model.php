<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Weixin_model extends MY_Model {

	const APPID = 'wx1fed98ad6d053213';
	const MCHID = '1357839302';
	const KEY = 'Hdxhmh22070804Hdxhmh22070804Hdxh';
	const APPSECRET = 'ab7eca13351aa935acbcb67ec14531f0';
	
	
    public function __construct() { 
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * 通过数据库openid
     * 
     */
    public function get_openid_to_mysql($openid_key){
       if(empty($openid_key)){
            return false;
       }
        
       $res = $this->db->get_where('wx_cache',array('key'=>$openid_key))->row_array();
       if(empty($res)){
           return false;
       }
       
       
       $final_time = $res['time'] + 3600;
       if($final_time < time()){
           return false;
       }
       
       return $res['value'];
    }
    
    /**
     * 获取openid 并且保存
     * 
     */
    public function get_openid($openid_key,$goback_url){
        $code = isset($_GET['code']) ? $_GET['code'] : '';
        if(empty($code)){
            $callback_url = urlencode('http://'.$_SERVER['HTTP_HOST'].$goback_url.'?'.$_SERVER['QUERY_STRING']);
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.self::APPID.'&redirect_uri='.$callback_url.'&response_type=code&scope=snsapi_base&state=1#wechat_redirect';
            header("Location:".$url);
        }else{
            $appid         = self::APPID;
            $secret        = self::APPSECRET;
            $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
            $res           = $this->https_request($get_token_url);
            
            $json_obj      = json_decode($res,true);
            $openid        = $json_obj['openid'];
            
            
            $cache_info = $this->db->get_where('wx_cache',array('key'=>$openid_key))->row_array();
            if(empty($cache_info)){
                //把openid存入数据库
                $wx_data = array();
                $wx_data['key']   = $openid_key;
                $wx_data['value'] = $openid;
                $wx_data['time']  = time();
                $this->db->insert('wx_cache',$wx_data);
            }else{
                $this->db->where('key',$openid_key)->update('wx_cache',array('time'=>time(),'value'=>$openid));
            }
            
            $url = 'http://'.$_SERVER['HTTP_HOST'].$goback_url.'?'.$_SERVER['QUERY_STRING'];
            header("Location:".$url);
        }
        
    }
    
    
    
    
    /**
     * 获取用户openid
     * 
     * @return unknown
     */
    public function GetOpenid(){
        //通过code获得openid
        
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].'/user/upgrade'.'?'.$_SERVER['QUERY_STRING']);
            $url = $this->__CreateOauthUrlForCode($baseUrl);
            header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $openid = $this->getOpenidFromMp($code);
            return $openid;
        }
    }
    
    /**
     * curl请求微信获取用户信息
     *
     * @param string $url 请求的链接
     * @return mixed
     */
    public function https_request($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
  
    
    /**
     *
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     *
     * @return 返回构造好的url
     */
    private function __CreateOauthUrlForCode($redirectUrl)
    {
        $urlObj["appid"] = self::APPID;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        
        $bizString = $this->_ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }
    
    /**
     *
     * 拼接签名字符串
     * @param array $urlObj
     *
     * @return 返回已经拼接好的字符串
     */
    private function _ToUrlParams($urlObj){
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }
    
        $buff = trim($buff, "&");
        return $buff;
    }
    
    /**
     *
     * 通过code从工作平台获取openid机器access_token
     * @param string $code 微信跳转回来带上的code
     *
     * @return openid
     */
    public function GetOpenidFromMp($code){
        $url = $this->__CreateOauthUrlForOpenid($code);
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        //取出openid
        $data = json_decode($res,true);
        $this->data = $data;
        $openid = $data['openid'];
        return $openid;
    }
    
    /**
     *
     * 构造获取open和access_toke的url地址
     * @param string $code，微信跳转带回的code
     *
     * @return 请求的url
     */
    private function __CreateOauthUrlForOpenid($code){
        $urlObj["appid"]      = self::APPID;
        $urlObj["secret"]     = self::APPSECRET;
        $urlObj["code"]       = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->_ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }
}
