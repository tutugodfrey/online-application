<?php
App::uses('UsersManager', 'Model');

/**
 * UsersManager Test Case
 *
 */
class UsersManagerTest extends CakeTestCase {
  public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.onlineapp_users_manager'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UsersManager = ClassRegistry::init('UsersManager');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UsersManager);

		parent::tearDown();
	}

	public function testValidation() {}

}
