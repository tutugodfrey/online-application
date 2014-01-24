<?php
App::uses('CobrandedApplication', 'Model');

/**
 * CobrandedApplication Test Case
 *
 */
class CobrandedApplicationTest extends CakeTestCase {

	public $autoFixtures = false;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.onlineappCobrandedApplication',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');

		// load data
		$this->loadFixtures('OnlineappCobrandedApplication');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		$this->CobrandedApplication->deleteAll(true, false);

		unset($this->CobrandedApplication);

		parent::tearDown();
	}

	public function testValidation() {
		// create a new appliction
		// only validation currently in place is for the uuid
		$expectedValidationErrors = array('uuid' => array('Invalid UUID'));
		$this->CobrandedApplication->create(array('uuid' => ''));
		$this->assertFalse($this->CobrandedApplication->validates());
		$this->assertEquals(
			$expectedValidationErrors,
			$this->CobrandedApplication->validationErrors,
			'testing expected validation errors for empty uuid'
		);

		// testing go right path
		$expectedValidationErrors = array();
		$this->CobrandedApplication->create(array('uuid' => String::uuid()));
		$this->assertTrue($this->CobrandedApplication->validates());
		$this->assertEquals(
			$expectedValidationErrors,
			$this->CobrandedApplication->validationErrors,
			'testing no validation errors for valid uuid'
		);
	}

}
