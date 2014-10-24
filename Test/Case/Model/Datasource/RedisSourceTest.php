<?php
App::uses('RedisSource', 'Redis.Model/Datasource');

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
 * testListSources method
 *
 * @return void
 */
	public function testListSources() {
		$Source = new RedisSource();

		$result = $Source->listSources();

		$this->assertNull($result);
	}

/**
 * testListSources method
 *
 * @return void
 */
	public function testDescribe() {
		$Source = new RedisSource();
		$Model = $this->getMockForModel('Model');

		$result = $Source->describe($Model);

		$this->assertNull($result);
	}

/**
 * testListSources method
 *
 * @return void
 */
	public function testCalculate() {
		$Source = new RedisSource();
		$Model = $this->getMockForModel('Model');
		$func = 'foo';
		$params = array('b', 'a', 'r');

		$result = $Source->calculate($Model, $func, $params);
		$expected = array('count' => true);

		$this->assertIdentical($expected, $result);
	}

}
