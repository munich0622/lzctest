<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 漫道短信通道，可适用于验证码类和营销类短信
 */
class Sms
{
    public $argv = array();
    public $errno = 1;
    public $errstr = 'can not open sockect';
    public $sockect = '';

    private $sn = 'SDK-BBX-010-24321';
    private $pwd = '[-9651-[';
    private $mobile = '';
    private $content = '';
    private $ext = '';
    private $rrid = '';
    private $stime = '';

    //md5加密账号
    private function md5pwd() {
        return strtoupper(md5($this->sn . $this->pwd));
    }

    //转为gb2312
    private function iconvcontent() {
        return iconv("UTF-8", "gb2312//IGNORE", $this->content);
    }

    //发送短信参数
    private function send_param() {
        $this->argv = array(
            'sn' => $this->sn,
            'pwd' => $this->md5pwd(),
            'mobile' => $this->mobile,
            'content' => $this->iconvcontent(),
            'ext' => $this->ext,
            'rrid' => $this->rrid,
            'stime' => $this->stime,
            );
        return $this->argv;
    }
   	
    //短信账号余额参数
    private function balance_param()
    {
        $this->argv = array(
            'sn' => $this->sn,
            'pwd' => $this->pwd,
            );
        return $this->argv;
    }
	
	//扫描主域是否可以连接
	private function sockect_link() {
		for($j=0;$j<3;$j++) {
			$fp = @fsockopen("sdk2.zucp.net", 8060, $errno, $errstr, 30);
			$timeb=time();
			if(!$fp) {
				$timee=time();
				$r=$timee-$timeb;
				if($r<10) sleep($r);
			}else{
			  return 1;
			}
		}
		return 0;
	}
	
    /**
     * 发送短信
     * @param string $mobile 多个手机用,分隔
     */
    public function send($mobile, $content, $stime='', $ext='', $rrid='') {
        $this->content = $content;
        $this->mobile = $mobile;
        $this->stime = $stime;
        $this->ext = $ext;
        $this->rrid = $rrid;
        //print_r($this->send_param());
        
        $params = array();
        foreach ($this->send_param() as $key => $value) {
        	$params[] = $key."=".urlencode($value);
        }
        $params = join('&', $params);
        if (empty($params)) {
            return false;
        }
		
		if($this->sockect_link()) {
			$this->sockect = "sdk2.zucp.net";  //优选主域
		}else{
			$this->sockect = "sdk2.entinfo.cn";  //备选域
		}
		
        $fp = @fsockopen($this->sockect, 8060, $this->errno, $this->errstr, 30);
        if(! $fp) {
        	log_msg('error', __METHOD__.$this->errstr."->".$this->errno.$this->sockect, 'sms');
        	return false;
        }

        $header = "POST /webservice.asmx/mt HTTP/1.1\r\n";
        $header .= "Host:" . $this->sockect . "\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: ".strlen($params)."\r\n";
        $header .= "Connection: Close\r\n\r\n";
        //添加post的字符串
        $header .= $params."\r\n";
        //发送post的数据
        fputs($fp, $header);
        $inheader = 1;
        while (!feof($fp)) {
            $line = fgets($fp, 1024); //去除请求包的头只显示页面的返回数据
            if ($inheader && ($line == "\n" || $line == "\r\n"))
            {
                $inheader = 0; 
            }
        }
        preg_match('/<string xmlns=\"http:\/\/tempuri.org\/\">(.*)<\/string>/', $line, $str);
        $result = explode("-", $str[1]);
		return count($result) > 1 ? false : true;

    }

    //获取短信余额
    public function balance() {
    	$params = array();
    	foreach ($this->balance_param() as $key => $value) {
    		$params[] = $key."=".urlencode($value);
    	}
    	$params = join('&', $params);
    	if (empty($params)) {
    		return false;
    	}
        $length = strlen($params); 
        //创建socket连接 
        $fp = @fsockopen("sdk2.entinfo.cn",8060,$errno,$errstr,10); 
        //构造post请求的头 
        $header = "POST /webservice.asmx/GetBalance HTTP/1.1\r\n"; 
        $header .= "Host:sdk2.entinfo.cn\r\n"; 
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
        $header .= "Content-Length: ".$length."\r\n";
        $header .= "Connection: Close\r\n\r\n"; 
        //添加post的字符串 
        $header .= $params."\r\n"; 
        //发送post的数据 
        fputs($fp,$header); 
        $inheader = 1; 
        while (!feof($fp)) { 
        	$line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据
        	if ($inheader && ($line == "\n" || $line == "\r\n")) {
        		$inheader = 0;
        	}
       	} 
       	$line=str_replace("<string xmlns=\"http://tempuri.org/\">","",$line);
       	$line=str_replace("</string>","",$line);
       	$result=explode("-",$line);
       	if(count($result)>1) {
       		log_msg('error', __METHOD__.$line, 'sms');
       		return false;
       	}
       	return $line;
    }



}