<?php
App::uses('Setting', 'Model');

/**
 * Setting Test Case
 *
 */
class SettingTest extends CakeTestCase {
  public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.onlineappSetting'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Setting = ClassRegistry::init('Setting');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Setting);

		parent::tearDown();
	}

	public function testValidation() {}

}
