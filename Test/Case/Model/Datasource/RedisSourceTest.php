<?php
App::uses('RedisSource', 'Redis.Model/Datasource');

/**
 * Test Redis Source class
 *
 */
class TestRedisSource extends RedisSource {

/**
 * Test double of `parent::_connection`.
 *
 * @var Redis
 */
	// @codingStandardsIgnoreStart
	public $_connection = null;
	// @codingStandardsIgnoreEnd

/**
 * Test double of `parent::_connect`.
 *
 * @return bool
 */
	// @codingStandardsIgnoreStart
	public function _connect() {
	// @codingStandardsIgnoreEnd
		return parent::_connect();
	}

/**
 * Test double of `parent::_authenticate`.
 *
 * @return bool
 */
	// @codingStandardsIgnoreStart
	public function _authenticate() {
	// @codingStandardsIgnoreEnd
		return parent::_authenticate();
	}

/**
 * Test double of `parent::_select`.
 *
 * @return bool
 */
	// @codingStandardsIgnoreStart
	public function _select() {
	// @codingStandardsIgnoreEnd
		return parent::_select();
	}

/**
 * Test double of `parent::_setPrefix`.
 *
 * @return bool
 */
	// @codingStandardsIgnoreStart
	public function _setPrefix() {
	// @codingStandardsIgnoreEnd
		return parent::_setPrefix();
	}

}

/**
 * Redis Source Test class
 *
 */
class RedisSourceTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
	}

/**
 * testConstructExtensionNotLoaded method
 *
 *  Tests that `connect` will never be called when redis extension is not loaded.
 *
 * @expectedException RedisSourceException
 * @expectedExceptionMessage Extension is not loaded.
 * @return void
 */
	public function testConstructExtensionNotLoaded() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		// Set expectations for constructor calls
		$Source->expects($this->once())->method('enabled')->will($this->returnValue(false));
		$Source->expects($this->never())->method('connect');

		// Now call the constructor
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$constructor = $reflectedClass->getConstructor();
		$constructor->invoke($Source);
	}

/**
 * testConstructExtensionLoaded method
 *
 *  Tests that `connect` will be called when redis extension is loaded.
 *
 * @return void
 */
	public function testConstructExtensionLoaded() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		// Set expectations for constructor calls
		$Source->expects($this->once())->method('enabled')->will($this->returnValue(true));
		$Source->expects($this->once())->method('connect');

		// Now call the constructor
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$constructor = $reflectedClass->getConstructor();
		$constructor->invoke($Source);

		$expected = 'Redis';
		$result = $Source->_connection;

		$this->assertInstanceOf($expected, $result);
	}

/**
 * testConnectFailedConnect method
 *
 *  Tests that an exception in thrown when `_connect` fails.
 *
 * @expectedException RedisSourceException
 * @expectedExceptionMessage Could not connect.
 * @return void
 */
	public function testConnectFailedConnect() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		// Set expectations for connect calls
		$Source->expects($this->once())->method('_connect')->will($this->returnValue(false));

		// Now call connect
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('connect');
		$connect->invoke($Source);
	}

/**
 * testConnectFailedAuthenticate method
 *
 *  Tests that an exception in thrown when `_authenticate` fails.
 *
 * @expectedException RedisSourceException
 * @expectedExceptionMessage Could not authenticate.
 * @return void
 */
	public function testConnectFailedAuthenticate() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		// Set expectations for connect calls
		$Source->expects($this->once())->method('_connect')->will($this->returnValue(true));
		$Source->expects($this->once())->method('_authenticate')->will($this->returnValue(false));

		// Now call connect
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('connect');
		$connect->invoke($Source);
	}

/**
 * testConnectFailedSelect method
 *
 *  Tests that an exception in thrown when `_select` fails.
 *
 * @expectedException RedisSourceException
 * @expectedExceptionMessage Could not select.
 * @return void
 */
	public function testConnectFailedSelect() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		// Set expectations for connect calls
		$Source->expects($this->once())->method('_connect')->will($this->returnValue(true));
		$Source->expects($this->once())->method('_authenticate')->will($this->returnValue(true));
		$Source->expects($this->once())->method('_select')->will($this->returnValue(false));

		// Now call connect
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('connect');
		$connect->invoke($Source);
	}

/**
 * testConnectFailedSetPrefix method
 *
 *  Tests that an exception in thrown when `_setPrefix` fails.
 *
 * @expectedException RedisSourceException
 * @expectedExceptionMessage Could not set prefix.
 * @return void
 */
	public function testConnectFailedSetPrefix() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		// Set expectations for connect calls
		$Source->expects($this->once())->method('_connect')->will($this->returnValue(true));
		$Source->expects($this->once())->method('_authenticate')->will($this->returnValue(true));
		$Source->expects($this->once())->method('_select')->will($this->returnValue(true));
		$Source->expects($this->once())->method('_setPrefix')->will($this->returnValue(false));

		// Now call connect
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('connect');
		$connect->invoke($Source);
	}

/**
 * testConnected method
 *
 * @return void
 */
	public function testIsConnected() {
		$Source = new TestRedisSource();

		$Source->connected = true;
		$result = $Source->isConnected();

		$this->assertTrue($result);

		$Source->connected = false;
		$result = $Source->isConnected();

		$this->assertFalse($result);
	}

/**
 * testConnectUnixSocket method
 *
 * @return void
 */
	public function testConnectUnixSocket() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		$unixSocket = '/foo/bar';

		$Source->config = array('unix_socket' => $unixSocket);
		$Source->_connection = $this->getMock('Redis', array('connect'));

		// Set expectations for connect calls
		$Source->_connection->expects($this->once())->method('connect')
			->with($this->equalTo($unixSocket))->will($this->returnValue(true));

		// Now call _connect
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('_connect');
		$result = $connect->invoke($Source);

		$this->assertTrue($result);
	}

/**
 * testConnectTcp method
 *
 * @return void
 */
	public function testConnectTcp() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		$unixSocket = '';
		$persistent = false;
		$host = 'foo';
		$port = 'bar';
		$timeout = 0;

		$Source->config = array(
			'unix_socket' => $unixSocket,
			'persistent' => $persistent,
			'host' => $host,
			'port' => $port,
			'timeout' => $timeout,
		);
		$Source->_connection = $this->getMock('Redis', array('connect'));

		// Set expectations for connect calls
		$Source->_connection->expects($this->once())->method('connect')
			->with($this->equalTo($host), $this->equalTo($port), $this->equalTo($timeout))
			->will($this->returnValue(true));

		// Now call _connect
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('_connect');
		$result = $connect->invoke($Source);

		$this->assertTrue($result);
	}

/**
 * testConnectTcpPersistent method
 *
 * @return void
 */
	public function testConnectTcpPersistent() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		$unixSocket = '';
		$persistent = true;
		$host = 'foo';
		$port = 'bar';
		$timeout = 0;

		$Source->config = array(
			'unix_socket' => $unixSocket,
			'persistent' => $persistent,
			'host' => $host,
			'port' => $port,
			'timeout' => $timeout,
		);
		$Source->_connection = $this->getMock('Redis', array('pconnect'));

		// Set expectations for pconnect calls
		$Source->_connection->expects($this->once())->method('pconnect')
			->with($this->equalTo($host), $this->equalTo($port), $this->equalTo($timeout), $this->anything())
			->will($this->returnValue(true));

		// Now call _connect
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('_connect');
		$result = $connect->invoke($Source);

		$this->assertTrue($result);
	}

/**
 * testCallExisting method
 *
 *  Tests calling of an existing (Redis) method on a connected / configured instance.
 *
 * @return void
 */
	public function testCallExisting() {
		$Source = new TestRedisSource();

		$result = $Source->ping();
		$expected = '+PONG';

		$this->assertIdentical($result, $expected);
	}

/**
 * testCallNonExisting method
 *
 *  Tests calling of an non-existing (Redis) method on a connected / configured instance.
 *
 * @return void
 * @expectedException RedisSourceException
 * @expectedExceptionMessage Method (pang) does not exist.
 */
	public function testCallNonExisting() {
		$Source = new TestRedisSource();

		$Source->pang();
	}

/**
 * testCallExistingFailure method
 *
 *  Tests calling of an existing (Redis) method on a disconnected / misconfigured instance.
 *
 * @return void
 * @expectedException RedisSourceException
 * @expectedExceptionMessage Method (ping) failed: Redis server went away.
 */
	public function testCallExistingFailure() {
		// Get mock, without the constructor being called
		$Source = new TestRedisSource();
		$Source->_connection = new Redis();
		$Source->connected = false;

		// Now call ping
		$Source->ping();
	}

/**
 * testNoAuthenticate method
 *
 * @return void
 */
	public function testNoAuthenticate() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		$password = '';

		$Source->config = array('password' => $password);
		$Source->_connection = $this->getMock('Redis', array('auth'));

		// Set expectations for constructor calls
		$Source->_connection->expects($this->never())->method('auth');

		// Now call _authenticate
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('_authenticate');
		$result = $connect->invoke($Source);

		$this->assertTrue($result);
	}

/**
 * testSuccessfulAuthenticate method
 *
 * @return void
 */
	public function testSuccessfulAuthenticate() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		$password = 'foo';

		$Source->config = array('password' => $password);
		$Source->_connection = $this->getMock('Redis', array('auth'));

		// Set expectations for auth calls
		$Source->_connection->expects($this->once())->method('auth')
			->with($this->equalTo($password))->will($this->returnValue(true));

		// Now call _authenticate
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('_authenticate');
		$result = $connect->invoke($Source);

		$this->assertTrue($result);
	}

/**
 * testFailingAuthenticate method
 *
 * @return void
 */
	public function testFailingAuthenticate() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		$password = 'foo';

		$Source->config = array('password' => $password);
		$Source->_connection = $this->getMock('Redis', array('auth'));

		// Set expectations for auth calls
		$Source->_connection->expects($this->once())->method('auth')
			->with($this->equalTo($password))->will($this->returnValue(false));

		// Now call _authenticate
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('_authenticate');
		$result = $connect->invoke($Source);

		$this->assertFalse($result);
	}

/**
 * testNoSelect method
 *
 * @return void
 */
	public function testNoSelect() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		$database = '';

		$Source->config = array('database' => $database);
		$Source->_connection = $this->getMock('Redis', array('select'));

		// Set expectations for select calls
		$Source->_connection->expects($this->never())->method('select');

		// Now call _Select
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('_select');
		$result = $connect->invoke($Source);

		$this->assertTrue($result);
	}

/**
 * testSuccessfulSelect method
 *
 * @return void
 */
	public function testSuccessfulSelect() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		$database = 'foo';

		$Source->config = array('database' => $database);
		$Source->_connection = $this->getMock('Redis', array('select'));

		// Set expectations for select calls
		$Source->_connection->expects($this->once())->method('select')
			->with($this->equalTo($database))->will($this->returnValue(true));

		// Now call _Select
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('_select');
		$result = $connect->invoke($Source);

		$this->assertTrue($result);
	}

/**
 * testFailingSelect method
 *
 * @return void
 */
	public function testFailingSelect() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		$database = 'foo';

		$Source->config = array('database' => $database);
		$Source->_connection = $this->getMock('Redis', array('select'));

		// Set expectations for select calls
		$Source->_connection->expects($this->once())->method('select')
			->with($this->equalTo($database))->will($this->returnValue(false));

		// Now call _Select
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('_select');
		$result = $connect->invoke($Source);

		$this->assertFalse($result);
	}

/**
 * testNoSelect method
 *
 * @return void
 */
	public function testNoSetPrefix() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		$prefix = '';

		$Source->config = array('prefix' => $prefix);
		$Source->_connection = $this->getMock('Redis', array('setOption'));

		// Set expectations for setOption calls
		$Source->_connection->expects($this->never())->method('setOption');

		// Now call _Select
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('_setPrefix');
		$result = $connect->invoke($Source);

		$this->assertTrue($result);
	}

/**
 * testSuccessfulSelect method
 *
 * @return void
 */
	public function testSuccessfulSetPrefix() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		$prefix = 'foo';

		$Source->config = array('prefix' => $prefix);
		$Source->_connection = $this->getMock('Redis', array('setOption'));

		// Set expectations for setOption calls
		$Source->_connection->expects($this->once())->method('setOption')
			->with($this->equalTo(Redis::OPT_PREFIX), $this->equalTo($prefix))->will($this->returnValue(true));

		// Now call _Select
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('_setPrefix');
		$result = $connect->invoke($Source);

		$this->assertTrue($result);
	}

/**
 * testFailingSelect method
 *
 * @return void
 */
	public function testFailingSetPrefix() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestRedisSource')->disableOriginalConstructor()->getMock();

		$prefix = 'foo';

		$Source->config = array('prefix' => $prefix);
		$Source->_connection = $this->getMock('Redis', array('setOption'));

		// Set expectations for setOption calls
		$Source->_connection->expects($this->once())->method('setOption')
			->with($this->equalTo(Redis::OPT_PREFIX), $this->equalTo($prefix))->will($this->returnValue(false));

		// Now call _Select
		$reflectedClass = new ReflectionClass('TestRedisSource');
		$connect = $reflectedClass->getMethod('_setPrefix');
		$result = $connect->invoke($Source);

		$this->assertFalse($result);
	}

	public function testCloseNotConnected() {
		$Source = new TestRedisSource();

		$Source->connected = false;
		$Source->_connection = $this->getMock('Redis', array('close'));

		// Set expectations for close calls
		$Source->_connection->expects($this->never())->method('close');

		$result = $Source->close();

		$this->assertFalse($Source->connected);
		$this->assertNull($Source->_connection);
		$this->assertTrue($result);
	}

	public function testCloseConnected() {
		$Source = new TestRedisSource();

		$Source->connected = true;
		$Source->_connection = $this->getMock('Redis', array('close'));

		// Set expectations for close calls
		$Source->_connection->expects($this->once())->method('close');

		$result = $Source->close();

		$this->assertFalse($Source->connected);
		$this->assertNull($Source->_connection);
		$this->assertTrue($result);
	}

/**
 * testListSources method
 *
 * @return void
 */
	public function testListSources() {
		$Source = new TestRedisSource();

		$result = $Source->listSources();

		$this->assertNull($result);
	}

/**
 * testDescribe method
 *
 * @return void
 */
	public function testDescribe() {
		$Source = new TestRedisSource();
		$Model = $this->getMockForModel('Model');

		$result = $Source->describe($Model);

		$this->assertNull($result);
	}

/**
 * testCalculate method
 *
 * @return void
 */
	public function testCalculate() {
		$Source = new TestRedisSource();
		$Model = $this->getMockForModel('Model');
		$func = 'foo';
		$params = array('b', 'a', 'r');

		$result = $Source->calculate($Model, $func, $params);
		$expected = array('count' => true);

		$this->assertIdentical($expected, $result);
	}

}
