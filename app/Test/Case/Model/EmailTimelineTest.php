<?php
App::uses('EmailTimeline', 'Model');

/**
 * EmailTimeline Test Case
 *
 */
class EmailTimelineTest extends CakeTestCase {
  public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.onlineappApip',
		'app.onlineappApplication',
		'app.onlineappUser',
		'app.onlineappGroup',
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplate_page',
		'app.onlineappTemplate_section',
		'app.onlineappTemplate_field',
		'app.onlineappEpayment',
		'app.onlineappApi_log',
		'app.onlineappUsers_manager',
		'app.onlineappCoversheet',
		'app.onlineappMultipass',
		'app.merchant',
		'app.equipment_programming',
		'app.timeline_entry',
		'app.timeline_item',
		'app.onlineappEmail_timeline',
		'app.onlineappEmail_timeline_subject'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->EmailTimeline = ClassRegistry::init('EmailTimeline');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EmailTimeline);

		parent::tearDown();
	}

	public function testValidation() {}
}
