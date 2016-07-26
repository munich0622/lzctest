<?php
$application_folder = dirname(dirname(__FILE__));
include dirname(dirname($application_folder)).'/sites/common/config/interface_switch.php';
include dirname(dirname($application_folder)).'/sites/common/helpers/interface_encrypt_helper.php';
//判断开关打开则判断加密的东西
if(INTERFACE_SWITCH){
	if(!isset($_POST['data']) || empty($_POST['data'])){
		die('非法传输!');
	}
	//包含接口加密辅助函数
	include dirname(dirname($application_folder)).'/sites/common/helpers/interface_encrypt_helper.php';
	//解密
	$data = last_decrypt($_POST['data']);
	if(empty($data)){
		die('非法传输!!!');
	}
	foreach($data as $key => $val){
		$_POST[$key] = $val;
	}
}
include dirname(dirname($application_folder)).'/sites/common/common_index.php';
?>