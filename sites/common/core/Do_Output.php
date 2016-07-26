<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 输出类
 * 
 * 重写CI_Output的_display_cache方法,使其可以使用?fc跳过缓存重新生成
 * 
 * @author 刘志超
 * @date 2015-04-17
 *
 */
class Do_Output extends CI_Output
{
	/**
	 * Update/serve a cached file
	 *
	 * @access	public
	 * @param 	object	config class
	 * @param 	object	uri class
	 * @return	void
	 */
	function _display_cache(&$CFG, &$URI)
	{
		if (isset($_GET['fc']))
		{
			return FALSE;;
		}

		return parent::_display_cache($CFG, $URI);
	}
}