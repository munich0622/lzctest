<?php

/**
 * 
 * 微信公众平台接口
 * 
 * @author 陆学锦
 * @date 2015-05-27
 *
 */
class  Dl_weixin_api{
	function __construct(){
		$this->CI = & get_instance ();
		$this->CI->load->model('Common_User_model', '', true);
		$this->CI->load->database();
	}
	
	/**
	 * 验证函数
	 */
	public function valid()
	{
		$echoStr = $_GET["echostr"];
	
		//valid signature , option
		if($this->checkSignature()){
			echo $echoStr;
			exit;
		}
	}
	
	/**
	 * 响应信息
	 */
	public function responseMsg()
	{
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
	
		//extract post data
		if (!empty($postStr)){
			/* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
			 the best way is to check the validity of xml by yourself */
			libxml_disable_entity_loader(true);
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj->FromUserName;
			$toUsername = $postObj->ToUserName;
			$keyword = trim($postObj->Content);
			$time = time();
			$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
			if(!empty( $keyword ))
			{
				$msgType = "text";
				$arr = explode("+", $keyword);
	
				$uinfo = $this->CI->Common_User_model->check_phone_invicode(trim($arr[0]),trim($arr[1]));
				
				if($uinfo && !empty($uinfo['openid'])){
					$contentStr = "已经绑定了公众号，不能再绑定";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					echo $resultStr;
				}elseif($uinfo && empty($uinfo['openid']) )
				{
					$ret = $this->CI->Common_User_model->exist_openid($fromUsername);
					if($ret){
						$contentStr = "该微信号已经绑定了公众号，不能再绑定";
					}else{
						//更新用户openid
						$res = $this->CI->Common_User_model->update_user_openid($arr[0],$fromUsername);
						if($res !==TRUE){
							return FALSE;
						}
						$re = $this->db->where(array('uid'=>$uinfo['uid'],'type'=>11))->get('user_account_log_stat')->row_array();
						if($re){
							return FALSE;
						}
						
						//更新用户统计表
						$res =$this->CI->Common_User_model->update_user_stat($uinfo['uid'],'',ATTENT_WEIXIN,false,true);
						if($res !== TRUE){
							return FALSE;
						}
							
						$account = array(
								'uid'       => $uinfo['uid'],
								'phone'     => $uinfo['phone'],
								'type'      => 11,
								'money'     => ATTENT_WEIXIN,
								'relate_id' => 0,
								'is_get_money' => 1,
								'is_effect' => 1,
								'dateline'  => time(),
								'finish_time'  => time()
						);
						$res = $this->CI->Common_User_model->user_account_log($account);
						if($res !==TRUE){
							return FALSE;
						}
						$contentStr = "关注成功，返回优锁屏完成关注任务";
						
						//$ret = $this->_weixin_api($uinfo);
// 						if($ret ==TRUE){
// 							$contentStr = "关注成功，返回优锁屏完成关注任务";
// 						}else{
// 							$contentStr = "已经获取过关注奖励";
// 						}
	
					}
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					echo $resultStr;
			
				}else{
					$contentStr = "欢迎关注优锁屏公众号";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					echo $resultStr;
				}
				
				
			}else{
				echo "Input something...";
			}
	
		}else {
			echo "";
			exit;
		}
	}
	
	private function _weixin_api($uinfo){
	
		if(empty($uinfo)){
			return FALSE;
		}

		$re = $this->db->where(array('uid'=>$uinfo['uid'],'type'=>11))->get('user_account_log_stat')->row_array();
		if($re){
			return FALSE;
		}

		//更新用户统计表
		$res =$this->Common_User_model->update_user_stat($uinfo['uid'],'',ATTENT_WEIXIN,false,true);
		if($res !== TRUE){
			return FALSE;
		}
			
		$account = array(
				'uid'       => $uinfo['uid'],
				'phone'     => $uinfo['phone'],
				'type'      => 11,
				'money'     => ATTENT_WEIXIN,
				'relate_id' => 0,
				'is_get_money' => 1,
				'is_effect' => 1,
				'dateline'  => time(),
				'finish_time'  => time()
		);
		$res = $this->Common_User_model->user_account_log($account);
		if($res !==TRUE){
			return FALSE;
		}
		
		return TRUE;
	}
	/**
	 * 校检签名
	 * @throws Exception
	 * @return boolean
	 */
	private function checkSignature(){

		// you must define TOKEN by yourself
		if (!defined("TOKEN")) {
			throw new Exception('TOKEN is not defined!');
		}
	
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
	
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		// use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
	
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}
