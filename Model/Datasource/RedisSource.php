<?php
App::uses('DataSource', 'Model/Datasource');

/**
 * Redis Source class
 *
 */
class RedisSource extends DataSource {

/**
 * Datasource description.
 *
 * @var string
 */
	public $description = 'Redis Data Source';

/**
 * Base configuration.
 *
 * @var array
 */
	protected $_baseConfig = array(
		'host' => '127.0.0.1',
		'port' => 6379,
		'password' => '',
		'database' => 0,
		'timeout' => 0,
		'persistent' => false,
		'unix_socket' => '',
		'prefix' => '',
	);

/**
 * Configuration.
 *
 * @var array
 */
	public $config = array();

/**
 * A reference to the physical connection of this DataSource.
 *
 * @var Redis
 */
	protected $_connection = null;

/**
 * Whether or not we are connected to the DataSource.
 *
 * @var bool
 */
	public $connected = false;

/**
	* Constructor.
	*
	* @param array $config Array of configuration information for the Datasource
	* @return bool True if connecting to the DataSource succeeds, else false
	*/
	public function __construct($config = array()) {
		parent::__construct($config);

		if (!$this->enabled()) {
			return false;
		}

		return $this->connect();
	}

/**
 * Destructor.
 *
 *  Closes the connection to the host (if needed).
 *
 * @return void
 */
	public function __destruct() {
		if (!$this->config['persistent']) {
			$this->close();
		}
	}

/**
 * Passes (non-existing) method calls to `Redis`.
 *
 * @param string $name The name of the method being called
 * @param array $arguments An enumerated array containing the parameters passed to the method
 * @return mixed Method return value
 */
	public function __call($name, $arguments) {
		return call_user_func_array(array($this->_connection, $name), $arguments);
	}

/**
 * Check that the redis extension is loaded.
 *
 * @return bool Whether or not the extension is loaded
 */
	public function enabled() {
		return extension_loaded('redis');
	}

/**
 * Connects to the database using options in the given configuration array.
 *
 *  "Connects mean:
 *  - connect
 *  - authenticate
 *  - select
 *  - setPrefix
 *
 * @return bool
 */
	public function connect() {
		$this->connected = $this->_connect();
		$this->connected = $this->connected && $this->_authenticate();
		$this->connected = $this->connected && $this->_select();
		$this->connected = $this->connected && !$this->_setPrefix();

		return $this->connected;
	}

/**
 * Connects to the database using options in the given configuration array.
 *
 * @return bool True if connecting to the DataSource succeeds, else false
 */
	protected function _connect() {
		try {
			$this->_connection = new Redis();

			if ($this->config['unix_socket']) {
				return $this->_connection->connect($this->config['unix_socket']);
			} elseif (!$this->config['persistent']) {
				return $this->_connection->connect(
					$this->config['host'], $this->config['port'], $this->config['timeout']
				);
			} else {
				$persistentId = crc32(serialize($this->config));

				return $this->_connection->pconnect(
					$this->config['host'], $this->config['port'], $this->config['timeout'], $persistentId
				);
			}
		} catch (RedisException $e) {
			return false;
		}
	}

/**
 * Authenticates to the database (if needed) using options in the given configuration array.
 *
 * @return bool True if the authentication succeeded or no password was specified, else false
 */
	protected function _authenticate() {
		if ($this->config['password']) {
			return $this->_connection->auth($this->config['password']);
		}

		return true;
	}

/**
 * Selects a database (if needed) using options in the given configuration array.
 *
 * @return bool True if the select succeeded or no database was specified, else false
 */
	protected function _select() {
		if ($this->config['database']) {
			return $this->_connection->select($this->config['database']);
		}

		return true;
	}

/**
 * Sets a prefix for all keys (if needed) using options in the given configuration array.
 *
 * @return bool True if setting the prefix succeeded or no prefix was specified, else false
 */
	protected function _setPrefix() {
		if ($this->config['prefix']) {
			return $this->_connection->setOption(Redis::OPT_PREFIX, $this->config['prefix']);
		}

		return true;
	}

/**
 * Closes a connection.
 *
 * @return bool Always true
 */
	public function close() {
		if ($this->isConnected()) {
			$this->_connection->close();
		}

		$this->connected = false;
		$this->_connection = null;

		return true;
	}

/**
 * Checks if the source is connected to the database.
 *
 * @return bool True if the database is connected, else false
 */
	public function isConnected() {
		return $this->connected;
	}

/**
 * Caches/returns cached results for child instances.
 *
 * @param mixed $data List of tables
 * @return array Array of sources available in this datasource
 */
	public function listSources($data = null) {
		return null;
	}

/**
 * Returns a Model description (metadata) or null if none found.
 *
 * @param Model|string $model Name of database table to inspect or model instance
 * @return array Array of Metadata for the $model
 */
	public function describe($model) {
		return null;
	}

/**
 * Returns an SQL calculation, i.e. COUNT() or MAX()
 *
 * @param Model $Model The model to get a calculated field for
 * @param string $func Lowercase name of SQL function, i.e. 'count' or 'max'
 * @param array $params Function parameters (any values must be quoted manually)
 * @return string An SQL calculation function
 */
	public function calculate(Model $Model, $func, $params = array()) {
		return array('count' => true);
	}

}
