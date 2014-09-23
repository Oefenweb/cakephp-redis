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
		'server' => '127.0.0.1',
		'port' => 6379,
		'password' => '',
		'database' => 0,
		'timeout' => 0,
		'persistent' => true,
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
 * Constructor.
 *
 *   Opens the connection to the server (if needed).
 *
 * @param array $config Array of configuration information for the Datasource
 * @param bool $autoConnect Whether or not the datasource should automatically connect
 * @return bool If autoconnect is true, true if the database could be connected, else false, otherwise, (always) true
 * @throws MissingConnectionException when a connection cannot be made
 */
	public function __construct($config = null, $autoConnect = true) {
		parent::__construct($config);

		if (!$this->enabled()) {
			throw new MissingConnectionException(array(
				'class' => get_class($this),
				'message' => __d('cake_dev', 'Selected driver is not enabled'),
				'enabled' => false
			));
		}

		if ($autoConnect) {
			return $this->connect();
		}

		return true;
	}

/**
 * Destructor.
 *
 *   Closes the connection to the server (if needed).
 *
 * @return void
 */
	public function __destruct() {
		if (!$this->config['persistent']) {
			$this->close();
		}
	}

/**
 * Check that the redis extension is installed/loaded.
 *
 * @return bool Whether or not the extension is loaded
 */
	public function enabled() {
		return extension_loaded('redis');
	}

/**
 * Connects to the database using options in the given configuration array.
 *
 * @return bool True if the database could be connected, else false
 * @throws MissingConnectionException
 */
	public function connect() {
		$this->connected = false;

		try {
			$this->_connection = new Redis();
			$this->connected = true;

		} catch (Exception $e) {
			throw new MissingConnectionException(array('class' => get_class($this), 'message' => $e->getMessage()));
		}

		return $this->connected;
	}

/**
 * Closes a connection.
 *
 * @return bool Always true
 */
	public function close() {
		if ($this->isConnected()) {
			$this->_connection->close();
			unset($this->_connection);
		}

		$this->connected = false;

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
