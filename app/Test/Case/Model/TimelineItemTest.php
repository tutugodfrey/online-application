<?php
App::uses('TimelineItem', 'Model');

/**
 * TimelineItem Test Case
 *
 */
class TimelineItemTest extends CakeTestCase {
  public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.timeline_item',
		'app.timeline_entry',
		'app.merchant',
		'app.equipment_programming'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TimelineItem = ClassRegistry::init('TimelineItem');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TimelineItem);

		parent::tearDown();
	}

	public function testValidation() {}

}
