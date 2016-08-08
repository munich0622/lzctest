<?php
/**
 * 后台站点 - 控制器基类
 * 
 * @author 刘志超
 * @date 2015-04-16
 */

class MY_Controller extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}
}


/**
 * 后台站点 - 控制器基类
 *
 * @author 刘志超
 * @date 2015-04-16
 */

class Admin_Controller extends MY_Controller {
    public $user;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['user']) || (int)$_SESSION['user']['id'] <= 0){
            redirect('login/index');
        }
        
        $this->user = $_SESSION['user'];
    }
}
?>