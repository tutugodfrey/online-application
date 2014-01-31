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
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappCobrandedApplication',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
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
		$this->Template->deleteAll(true, false);
		$this->Cobrand->deleteAll(true, false);
		unset($this->CobrandedApplication);
		unset($this->Template);
		unset($this->Cobrand);

		parent::tearDown();
	}

	public function testValidation() {
		// create a new appliction
		// only validation currently in place is for the uuid
		$expectedValidationErrors = array(
			'uuid' => array('Invalid UUID'),
			'user_id' => array('This field cannot be left blank'),
			'template_id' => array('This field cannot be left blank'),
		);
		$this->CobrandedApplication->create(array('uuid' => ''));
		$this->assertFalse($this->CobrandedApplication->validates());
		$this->assertEquals(
			$expectedValidationErrors,
			$this->CobrandedApplication->validationErrors,
			'testing expected validation errors for empty uuid'
		);

		// testing go right path
		$expectedValidationErrors = array();
		$this->CobrandedApplication->create(
			array(
				'uuid' => String::uuid(),
				'user_id' => 1,
				'template_id' => 1,
			)
		);

		$this->assertTrue($this->CobrandedApplication->validates());
		$this->assertEquals(
			$expectedValidationErrors,
			$this->CobrandedApplication->validationErrors,
			'testing no validation errors for valid uuid'
		);
	}

}
