<?php
/**
 * 手机短信验证码
 * @author 刘志超
 * @date 2015-05-06
 */
class Dl_mobile_verification{
	private $api = 'https://api.sms.mob.com/sms/verify';
	private $appkey = '6c0f234d1388';
	
	private $error = NULL;
	
	/**
	 * 获取错误信息
	 * @author 刘志超
	 * @date 2015-04-16
	 */
	public function error(){
		return $this->error;
	}
	/**
	 * 发起一个post请求到指定接口
	 *
	 * @param string $api 请求的接口
	 * @param array $params post参数
	 * @param int $timeout 超时时间
	 * @return string 请求结果
	 */
	private function postRequest( $api, array $params = array(), $timeout = 30 ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $api );
		// 以返回的形式接收信息
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		// 设置为POST方式
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
		// 不验证https证书
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
		'Accept: application/json',
		) );
		// 发送数据
		$response = curl_exec( $ch );
		// 不要忘记释放资源
		curl_close( $ch );
		return $response;
	}
	
	public function check_active_code($phone,$active_code){
		$res = $this->postRequest( $this->api, array(
				'appkey' => $this->appkey,
				'phone' => $phone,
				'zone' => '86',
				'code' => $active_code,
		) );
		$res_obj = (array)json_decode($res,false);
		if ($res_obj['status'] == 200) return TRUE;
		$this->error = array('error_code'=>'309','error_message'=>'短信验证码错误');
		return FALSE;
	}
}