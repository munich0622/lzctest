<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* CodeIgniter Mongo Caching Class
*
* @package	CodeIgniter
* @subpackage Libraries
* @category Core
* @author	Tiger<289233809@qq.com>
*/
class Mongo extends CI_Driver {
	/**
	* Default config
	* @static
	* @var	array
	*/
	protected static $_default_config = array(
		'socket_type' => 'tcp',
		'host' => '127.0.0.1',
		'password' => NULL,
		'port' => 27017,
		'timeout' => 0
	);
	/**
	* Mongo connection
	* @var	Mongo
	*/
	protected $_mongo;
	
	/**
	* An internal cache for storing keys of serialized values.
	* @var	array
	*/
	protected $_serialized = array();
	
	/**
	* Get cache
	* @param	string Cache ID
	* @return	mixed
	*/
	public function get($key)
	{
		$value = $this->_mongo->get($key);
		if ($value !== FALSE && isset($this->_serialized[$key]))
		{
			return unserialize($value);
		}
		return $value;
	}

	/**
	* Save cache
	*
	* @param	string $id Cache ID
	* @param	mixed $data Data to save
	* @param	int $ttl Time to live in seconds
	* @param	bool $raw Whether to store the raw value (unused)
	* @return	bool TRUE on success, FALSE on failure
	*/
	public function save($id, $data, $ttl = 60, $raw = FALSE)
	{
		if (is_array($data) OR is_object($data))
		{
			if ( ! $this->_redis->sAdd('_ci_redis_serialized', $id))
			{
				return FALSE;
			}
			isset($this->_serialized[$id]) OR $this->_serialized[$id] = TRUE;
			$data = serialize($data);
		}elseif (isset($this->_serialized[$id])){
			$this->_serialized[$id] = NULL;
			$this->_redis->sRemove('_ci_redis_serialized', $id);
		}
		return ($ttl)? $this->_redis->setex($id, $ttl, $data) : $this->_redis->set($id, $data);
	}

	/**
	* Delete from cache
	*
	* @param	string Cache key
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

	/**
	* Increment a raw value
	*
	* @param	string $id Cache ID
	* @param	int $offset Step/value to add
	* @return	mixed New value on success or FALSE on failure
	*/
	public function increment($id, $offset = 1)
	{
		return $this->_redis->incr($id, $offset);
	}
	
	/**
	* Decrement a raw value
	*
	* @param	string $id Cache ID
	* @param	int $offset Step/value to reduce by
	* @return	mixed New value on success or FALSE on failure
	*/
	public function decrement($id, $offset = 1)
	{
		return $this->_redis->decr($id, $offset);
	}
	
	/**
	* Clean cache
	*
	* @return	bool
	* @see	Mongo::flushDB()
	*/
	public function clean()
	{
		return $this->_redis->flushDB();
	}
	
	/**
	* Get cache driver info
	*
	* @param	string Not supported in Mongo.
	* Only included in order to offer a
	* consistent cache API.
	* @return	array
	* @see	Mongo::info()
	*/
	public function cache_info($type = NULL)
	{
		return $this->_redis->info();
	}
	
	/**
	* Get cache metadata
	*
	* @param	string Cache key
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
	
	/**
	* Check if Mongo driver is supported
	*
	* @return	bool
	*/
	public function is_supported()
	{
		if (extension_loaded('redis'))
		{
			return $this->_setup_redis();
		}else{
			log_message('debug', 'The Mongo extension must be loaded to use Mongo cache.');
			return FALSE;
		}
	}
	
	/**
	* Setup Mongo config and connection
	*
	* Loads Mongo config file if present. Will halt execution
	* if a Mongo connection can't be established.
	*
	* @return	bool
	* @see	Mongo::connect()
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
			$this->_redis = new Mongo();
		try{
			if ($config['socket_type'] === 'unix'){
				$success = $this->_redis->connect($config['socket']);
			}else {
				$success = $this->_redis->connect($config['host'], $config['port'], $config['timeout']);
			}
			
			if ( ! $success){
				log_message('debug', 'Cache: Mongo connection refused. Check the config.');
				return FALSE;
			}
		}catch (MongoException $e){
			log_message('debug', 'Cache: Mongo connection refused ('.$e->getMessage().')');
			return FALSE;
		}
		
		if (isset($config['password'])){
			$this->_redis->auth($config['password']);
		}
		// Initialize the index of serialized values.
		$serialized = $this->_redis->sMembers('_ci_redis_serialized');
		if ( ! empty($serialized)){
			$this->_serialized = array_flip($serialized);
		}
		return TRUE;
	}
	
	/**
	* Class destructor
	*
	* Closes the connection to Mongo if present.
	*
	* @return	void
	*/
	public function __destruct()
	{
		if ($this->_redis){
			$this->_redis->close();
		}
	}
}
/* End of file Cache_redis.php */
/* Location: ./system/libraries/Cache/drivers/Cache_redis.php */