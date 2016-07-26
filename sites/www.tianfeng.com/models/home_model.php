<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 首页model
 *
 * @author 陆学锦
 * @date 2015-05-05
 */
class Home_model extends CI_Model{
	
	
	public function __construct() {
		parent::__construct ();
		$this->load->database();
	}
	
	
	
}