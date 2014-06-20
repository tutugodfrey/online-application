<?php
App::uses('EquipmentProgramming', 'Model');

/**
 * EquipmentProgramming Test Case
 *
 */
class EquipmentProgrammingTest extends CakeTestCase {
  public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.merchant',
		'app.equipment_programming',
		'app.timeline_entry',
		'app.timeline_item',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->EquipmentProgramming = ClassRegistry::init('EquipmentProgramming');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EquipmentProgramming);

		parent::tearDown();
	}

	public function testValidation() {}
}
