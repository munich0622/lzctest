<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 3.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Redis Caching Class
 *
 * @package	   CodeIgniter
 * @subpackage Libraries
 * @category   Core
 * @author	   Anton Lindqvist <anton@qvister.se>
 * @link
 */
class CI_Cache_redis extends CI_Driver
{
	/**
	 * Default config
	 *
	 * @static
	 * @var	array
	 */
	protected static $_default_config = array(
		'socket_type' => 'tcp',
		'host' => '127.0.0.1',
		'password' => NULL,
		'port' => 6379,
		'timeout' => 0
	);

	/**
	 * Redis connection
	 *
	 * @var	Redis
	 */
	protected $_redis;

	/**
	 * An internal cache for storing keys of serialized values.
	 *
	 * @var	array
	 */
	protected $_serialized = array();

	// ------------------------------------------------------------------------

	/**
	 * Get cache
	 *
	 * @param	string	Cache ID
	 * @return	mixed
	 */
	public function get($key)
	{
		$value = $this->_redis->get($key);

		if ($value !== FALSE && isset($this->_serialized[$key]))
		{
			return unserialize($value);
		}

		return $value;
	}
	

	// ------------------------------------------------------------------------

	/**
	 * Save cache
	 *
	 * @param	string	$id	Cache ID
	 * @param	mixed	$data	Data to save
	 * @param	int	$ttl	Time to live in seconds
	 * @param	bool	$raw	Whether to store the raw value (unused)
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function save($id, $data, $ttl = 60, $raw = FALSE)
	{
		if (is_array($data) OR is_object($data))
		{
			if ( ! $this->_redis->sIsMember('_ci_redis_serialized', $id) && ! $this->_redis->sAdd('_ci_redis_serialized', $id))
			{
				return FALSE;
			}

			isset($this->_serialized[$id]) OR $this->_serialized[$id] = TRUE;
			$data = serialize($data);
		}
		elseif (isset($this->_serialized[$id]))
		{
			$this->_serialized[$id] = NULL;
			$this->_redis->sRemove('_ci_redis_serialized', $id);
		}

		return ($ttl)
			? $this->_redis->setex($id, $ttl, $data)
			: $this->_redis->set($id, $data);
	}

	// ------------------------------------------------------------------------

	
	/**
	 * Delete from cache
	 *
	 * @param	string	Cache key
	 * @return	bool
	 */
	public function delete($key)
	{
		if ($this->_redis->delete($key) !== 1)
		{
			return FALSE;
		}

		if (isset($this->_serialized[$key]))
		{
			$this->_serialized[$key] = NULL;
			$this->_redis->sRemove('_ci_redis_serialized', $key);
		}

		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Increment a raw value
	 *
	 * @param	string	$id	Cache ID
	 * @param	int	$offset	Step/value to add
	 * @return	mixed	New value on success or FALSE on failure
	 */
	public function increment($id, $offset = 1)
	{
		return $this->_redis->incr($id, $offset);
	}

	// ------------------------------------------------------------------------

	/**
	 * Decrement a raw value
	 *
	 * @param	string	$id	Cache ID
	 * @param	int	$offset	Step/value to reduce by
	 * @return	mixed	New value on success or FALSE on failure
	 */
	public function decrement($id, $offset = 1)
	{
		return $this->_redis->decr($id, $offset);
	}

	// ------------------------------------------------------------------------

	/**
	 * Clean cache
	 *
	 * @return	bool
	 * @see		Redis::flushDB()
	 */
	public function clean()
	{
		return $this->_redis->flushDB();
	}

	// ------------------------------------------------------------------------

	/**
	 * Get cache driver info
	 *
	 * @param	string	Not supported in Redis.
	 *			Only included in order to offer a
	 *			consistent cache API.
	 * @return	array
	 * @see		Redis::info()
	 */
	public function cache_info($type = NULL)
	{
		return $this->_redis->info();
	}

	// ------------------------------------------------------------------------

	/**
	 * Get cache metadata
	 *
	 * @param	string	Cache key
	 * @return	array
	 */
	public function get_metadata($key)
	{
		$value = $this->get($key);

		if ($value)
		{
			return array(
				'expire' => time() + $this->_redis->ttl($key),
				'data' => $value
			);
		}

		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Check if Redis driver is supported
	 *
	 * @return	bool
	 */
	public function is_supported()
	{
		if ( ! extension_loaded('redis'))
		{
			log_message('debug', 'The Redis extension must be loaded to use Redis cache.');
			return FALSE;
		}

		return $this->_setup_redis();
	}

	// ------------------------------------------------------------------------

	/**
	 * Setup Redis config and connection
	 *
	 * Loads Redis config file if present. Will halt execution
	 * if a Redis connection can't be established.
	 *
	 * @return	bool
	 * @see		Redis::connect()
	 */
	protected function _setup_redis()
	{

		$config = array();
		$CI =& get_instance();

		if ($CI->config->load('redis', TRUE, TRUE))
		{
			$config += $CI->config->item('redis');
		}

		$config = array_merge(self::$_default_config, $config);

		$this->_redis = new Redis();

		try
		{
			if ($config['socket_type'] === 'unix')
			{
				$success = $this->_redis->connect($config['socket']);
			}
			else // tcp socket
			{
				$success = $this->_redis->connect($config['host'], $config['port'], $config['timeout']);
			}

			if ( ! $success)
			{
				log_message('debug', 'Cache: Redis connection refused. Check the config.');
				return FALSE;
			}
		}
		catch (RedisException $e)
		{
			log_message('debug', 'Cache: Redis connection refused ('.$e->getMessage().')');
			return FALSE;
		}

		if (isset($config['password']))
		{
			$this->_redis->auth($config['password']);
		}

		// Initialize the index of serialized values.
		$serialized = $this->_redis->sMembers('_ci_redis_serialized');
		if ( ! empty($serialized))
		{
			$this->_serialized = array_flip($serialized);
		}

		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Class destructor
	 *
	 * Closes the connection to Redis if present.
	 *
	 * @return	void
	 */
	public function __destruct()
	{
		if ($this->_redis)
		{
			$this->_redis->close();
		}
	}
	
	//-------------------CI自己封装的redis不够用 以下是额外增加的----------------------------
	
	/**
	 * redis无序集合添加元素
	 * @author 刘志超
	 * @date 2015-05-20
	 */
	public function set_setval($key,$value){
		$key = trim($key);
		$value = trim($value);
		if(empty($key) || empty($value)){
			return FALSE;
		}
		return $this->_redis->sAdd($key,$value);
	}
	
	/**
	 * 获取redis无序集合所有元素
	 * @author 刘志超
	 * @date 2015-05-20
	 */
	public function get_setval($key){
		$key = trim($key);
		if(empty($key)) return FALSE;
			
		return $this->_redis->sMembers($key);
	}
	
	/**
	 * 
	 * @param unknown $key      键
	 * @param unknown $value	值
	 * @return boolean
	 */
	public function is_sismember($key,$value){
		$key = trim($key);

		if(empty($key) || empty($value)){
			return FALSE;
		}
		return $this->_redis->sismember($key,$value);
	}
	
	/**
	 * 获取剩余时间戳
	 * @author 刘志超
	 * @param unknown $id
	 */
	
	public function ttl($key){
		return $this->_redis->ttl($key);
	}
	
	/**
	 * 哈希结构-存储
	 * @author 刘志超
	 * @param $table 哈希表
	 * @param $key
	 */
	public function hset($table,$key,$value,$ttl){
		if($ttl){
			$this->_redis->expire ($table, $ttl);
		}
		return $this->_redis->hset($table,$key,$value);
	}

	/**
	 * 设置字符串键值
	 * @param string $key  键
	 * @param mix $value   值
	 * @param int $ttl     剩余时间
	 * @author 陆学锦
	 * @ate    2015-07-20
	 */
	public function set($key,$value,$ttl=0){
		if($ttl){
			$this->_redis->expire ($key, $ttl);
		}
		return $this->_redis->set($key,$value,$ttl);
	}
	
	/**
	 * 哈希结构-获取
	 * @author 刘志超
	 * @param  $table 哈希表
	 * @param  $key   键值
	 */
	function hget($table,$key){
		return $this->_redis->hget($table,$key);
	}
	
	function keys($key){
		return $this->_redis->keys($key);
	}
	
	function prefix_keys($prefix){
		$all_keys = $this->_redis->keys($prefix.'*');
		$keys = array();
		$len = strlen($prefix);
		foreach($all_keys as $key){
			$keys[] = substr($key,$len);
		}
		return $keys;
	}
}
