<?php

class Api extends MY_Controller{
    
	function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * 微信回调支付成功
	 * 
	 */
	public function wxpay(){
	    $this->load->model('pay_model');
	    
	    //接收微信请求 file_put_contents($log_name,"【接收到的native通知】:\n".$xml."\n");
	    $xml = file_get_contents("php://input");;
	    
	    //xml转数组
	    $this->load->driver('payment');
	    
	    
	    $receivedata = XmlToArray($xml);
	    
	    //公众账号ID string(32)
	    $appid			= $receivedata['appid'];
	    
	    $bank_type		= $receivedata['bank_type'];//支付方式CFT 微信钱包零钱
	    $cash_fee		= $receivedata['cash_fee'];//支付金额
	    $fee_type		= $receivedata['fee_type'];//支付货币类型 CNY
	    
	    //是否关注公众账号 string(1) Y OR N
	    $is_subscribe	= $receivedata['is_subscribe'];
	    
	    //商户号 string(32)
	    $mch_id			= $receivedata['mch_id'];
	    
	    //随机字符串 string(32)
	    $nonce_str		= $receivedata['nonce_str'];
	    
	    //用户标识 string(128) 微信用户在商户appid下的唯一标识
	    $openid			= $receivedata['openid'];
	    
	    $out_trade_no	= $receivedata['out_trade_no'];//商户系统内部交易号
	    
	    //业务结果 		result_code 	String(16) 	是 	SUCCESS 	SUCCESS/FAIL
	    $result_code	= $receivedata['result_code'];
	    
	    //返回状态码 	return_code 	String(16) 	是 	SUCCESS 	SUCCESS/FAIL,此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断
	    $return_code	= $receivedata['return_code'];
	    
	    //签名 string(32) 本地需要验证，以防被伪造的数据访问回调接口
	    $sign			= $receivedata['sign'];
	    
	    $time_end		= $receivedata['time_end'];//交易时间
	    $total_fee		= $receivedata['total_fee'];//总交易金额
	    $trade_type		= $receivedata['trade_type'];//微信支付类型 NATIVE
	    $transaction_id	= $receivedata['transaction_id'];//微信支付交易单号
	    
	    //返回信息 		return_msg 		String(128) 否 	签名失败 	返回信息，如非空，为错误原因;签名失败;具体某个参数格式校验错误.
	    $return_msg		= isset($receivedata['return_msg']) ? $receivedata['return_msg'] : '';
	    
	    //错误代码 		err_code_des 	String(32) 否 	当result_code为FAIL时，商户展示给用户的错误代码 SYSTEMERROR
	    $err_code		= isset($receivedata['err_code_des']) ? $receivedata['err_code_des'] : '';
	    
	   //错误描述 		err_code_des 	String(128) 否 	当result_code为FAIL时，商户展示给用户的错误描述 系统错误
	   $err_code_des	= isset($receivedata['err_code_des']) ? $receivedata['err_code_des'] : '';
	    
	   $lcsign = Payment_wxpay::checksign($receivedata);
	    
	   if($lcsign == $sign){
	       
			if ($return_code == "SUCCESS"){
				//支付成功，进行逻辑处理！
				if($result_code == "SUCCESS"){
					$ret = $this->pay_model->pay_response($out_trade_no,$transaction_id);
					if($ret){
						echo arrayToXml(array("return_code"=>"SUCCESS"));
					}else{
						echo arrayToXml(array("return_code"=>"FAIL"));
					}
				}else{
					echo $err_code_des;//错误代码描述
				}
			}else{
				echo $return_msg;
			}
		}else{
			echo "<br>校验失败,数据可疑";
		}
	}
	
	
	
	/**
	 * 产生随机字符
	 * @author 刘志超
	 * @param int $length 产生随机字符长度
	 * @date 2015-04-17
	 * @return string
	 */
	private function _rank_string($length = 6 ){
	    // 密码字符集，可任意添加你需要的字符+
	    $chars = '123456789abcdefghijklmnopqrstuvwxyz';
	    $string = '';
	    for($i = 0; $i < $length; $i ++) {
	        $string .= $chars [mt_rand ( 0, strlen ( $chars ) - 1 )];
	    }
	    return $string;
	}
}
?>
