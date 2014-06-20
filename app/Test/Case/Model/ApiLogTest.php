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
		'app.onlineappApi_log',
		'app.onlineappApplication',
		'app.user',
		'app.group',
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplate_page',
		'app.onlineappTemplate_section',
		'app.onlineappTemplate_field',
		'app.onlineappApip',
		'app.onlineappEpayment',
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
