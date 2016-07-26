<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 支付驱动
 */
class Payment extends CI_Driver_Library {
	protected $valid_drivers 	= array(
		'alipay',
		//'alipay_bank',
		//'ccb',
		'chinabank',
		'wxpay'
	);
}

class Payment_Driver extends CI_Driver{

	public $_config;
	public $is_log = false;
	public $bank = '';
	public function setConfig($config, $bank=''){

		if(!empty($config)) {
			$this->_config = $config;
		}
		$this->bank = $bank;
		$this->init();
		return $this;
	}

	public function getCode($order) {
		
	}

	public function getOptions($data) {
		return $data;
	}

	public function response($callback) {
		$callback($response);
	}

	public function __destruct() {
		if($this->is_log) {
			$CI = &get_instance();
			L('m.pay_model', 'pay');
			$CI->pay->pay_log();
		}
	}
}