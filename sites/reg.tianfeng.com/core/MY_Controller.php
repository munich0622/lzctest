<?php
require COMPATH."core/Do_Controller.php";
/**
 * 注册站点类
 * @author 刘志超
 * @date 2015-04-16
 */
class MY_Controller extends Do_Controller {
	function __construct(){
		parent::__construct();
		header("Content-Type:text/html;charset=utf-8");
	}
}