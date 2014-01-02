<?php
App::uses('TimelineEntry', 'Model');

/**
 * TimelineEntry Test Case
 *
 */
class TimelineEntryTest extends CakeTestCase {
  public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.timeline_entry',
		'app.merchant',
		'app.equipment_programming',
		'app.timeline_item'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TimelineEntry = ClassRegistry::init('TimelineEntry');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TimelineEntry);

		parent::tearDown();
	}

	public function testValidation() {}

}
