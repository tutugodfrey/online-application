<?php
App::uses('Apip', 'Model');

/**
 * Apip Test Case
 *
 */
class ApipTest extends CakeTestCase {
  public $dropTables = true;
  public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.onlineappApip'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Apip = ClassRegistry::init('Apip');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Apip);

		parent::tearDown();
	}

	public function testValidation() {}
}
