<?php
App::uses('ApiLog', 'Model');

/**
 * ApiLog Test Case
 *
 */
class ApiLogTest extends CakeTestCase {
  public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.onlineappApi_log'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ApiLog = ClassRegistry::init('ApiLog');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ApiLog);

		parent::tearDown();
	}

	public function testValidation() {
	}

}
