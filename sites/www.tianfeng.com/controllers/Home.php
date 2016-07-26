<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 首页控制器
 * 
 * @author 陆学锦
 * @date 2015-05-05
 */
class Home extends MY_Controller
{
	public function __construct() {
		parent::__construct ();
		$this->load->database();

	}
	/**
	 * 首页
 	 * @author 刘志超
 	 * @date 2015-05-05
	 * @return void
	 */
	public function index(){
		$path = $this->isMobile() ? 'mobile/index' : 'index';
		$this->load->view($path);
	}
	
	/**
	 * 联系我们
	 * @author 刘志超
	 * @date 2015-05-05
	 * @return void
	 */
	public function contact(){
		$path = $this->isMobile() ? 'mobile/contact' : 'contact';
		$this->load->view($path);
	}
	
	function isMobile(){  
		$useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';  
		$useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';  	  
		$mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
		$mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');  
		$found_mobile = $this->CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||  $this->CheckSubstrs($mobile_token_list,$useragent);  
			  
		if ($found_mobile){  
			return true;  
		}else{  
			return false;  
		}  
	}
	
	function CheckSubstrs($substrs,$text){
		foreach($substrs as $substr)
		if(false!==strpos($text,$substr)){
			return true;
		}
		return false;
	}
	
	/**
	 * 
	 * 
	
	public function deal_with(){
		$sql = ' SELECT sum(money) as money,uid FROM dolocker_user_account_log_stat 
				where type in(1,3,4,30) AND (task_state = 1 OR task_state = 4) GROUP BY uid ';
		$result = $this->db->query($sql)->result_array();

		$sql = 'UPDATE dolocker_user_statistics SET task_money = CASE uid ';
		foreach($result as $key=>$val){
			$sql .= ' WHEN '.$val['uid'].' THEN '.$val['money'];
		}
		$sql .= ' END ';
		$this->db->query($sql);
		
	}

	public function deal_with_exchange(){
		$sql = ' SELECT sum(price) as money,user_id FROM dolocker_user_exchange_log
				where state = 2 GROUP BY user_id limit 10';
		$result = $this->db->query($sql)->result_array();
	
		$sql = 'UPDATE dolocker_user_statistics SET exchange_money = CASE uid ';
		foreach($result as $key=>$val){
			$sql .= ' WHEN '.$val['user_id'].' THEN '.$val['money'];
		}
		$sql .= ' END ';
		$this->db->query($sql);
	}
	 */
}
