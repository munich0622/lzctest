<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 微信支付
 */
class Payment_wxpay extends Payment_Driver {
    
    public function init(){
        require_once("wxpay/lib/WxPay.Api.php");
        
    }
    
    //生成支付二维码
    static function qrcode($url,$order_sn,$rs_code) {
    	$errorCorrectionLevel = "L";
    	$matrixPointSize = "8";//生成的大小
    	
    	//$outfile=TEMP_UPLOAD_DIR."qr_".$order_sn.".png";
    	$outfile=$_SERVER['DOCUMENT_ROOT']."/upload/tmp/qr_".$order_sn.".png";
    	
    	if ($rs_code == 'SUCCESS'){//生成新二维码
    		QRcode::png($url, $outfile, $errorCorrectionLevel, $matrixPointSize);
    		//$qrcodeimgurl="/qrcode/qr_".$order_sn.".png";
    		$qrcodeimgurl="/upload/tmp/qr_".$order_sn.".png";
    	}else{//在有效期内使用已生成的二维码
    		//$qrcodeimgurl="/qrcode/qr_".$order_sn.".png";
    		$qrcodeimgurl="/upload/tmp/qr_".$order_sn.".png";
    	}
    	
    	return $qrcodeimgurl;
    }
    
    //格式化参数格式化成url参数
    static function ToUrlParams($data)
    {
    	$buff = "";
    	foreach ($data as $k => $v)
    	{
    		if($k != "sign" && $v != "" && !is_array($v)){
    			$buff .= $k . "=" . $v . "&";
    		}
    	}
    
    	$buff = trim($buff, "&");
    	return $buff;
    }
    
    //本地生成签名
    static function checksign($receivedata){
    	ksort($receivedata);//stringA中的字段需按首字符在ACSII码表中的顺序从小到大排列
    	$stringA = self::ToUrlParams($receivedata);//格式化参数格式化成url参数
    	$stringSignTemp = $stringA."&key=".WxPayConfig::KEY;
    	return $lcsign = strtoupper(md5($stringSignTemp));//微信签名算法（请勿修改）
    }
}