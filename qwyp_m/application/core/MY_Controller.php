<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');header("Content-type: text/html; charset=utf-8");
/**
 * 控制器继承此类
 */
class MY_Controller extends CI_Controller {

	public function __construct (){
        parent::__construct();
    }
    
    protected function view($view, $vars=array(), $return=FALSE) {
    	//图片和文件版本号
    	$vars['static_version'] = static_version();
    	$vars['img_version'] = img_version();
    	return $this->load->view($view, $vars, $return);
    }
    
    
    
}


