<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 首页控制器
 * 
 * @author 刘志超
 * @date 2015-05-08
 */
class Mobile extends MY_Controller
{
	public function __construct() {
		parent::__construct ();

	}
	/**
	 * 首页
 	 * @author 刘志超
 	 * @date 2015-05-05
	 * @return void
	 */
	public function index(){
		$data['code'] = $this->input->get('code',TRUE);
		$this->load->view('mobile/index',$data);
	}
	
	/**
	 * 联系我们
	 * @author 刘志超
	 * @date 2015-05-05
	 * @return void
	 */
	public function contact(){
		$this->load->view('mobile/contact');
	}
	

}
