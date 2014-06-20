<?php
App::uses('Multipass', 'Model');

/**
 * Multipass Test Case
 *
 */
class MultipassTest extends CakeTestCase {
  public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.onlineappApip',
		'app.onlineappApplication',
		'app.user',
		'app.group',
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
		$this->Multipass = ClassRegistry::init('Multipass');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Multipass);

		parent::tearDown();
	}

/**
 * testCallbackFailure method
 *
 * @return void
 */
	public function testCallbackFailure() {
	}

/**
 * testInitializeMultipass method
 *
 * @return void
 */
	public function testInitializeMultipass() {
	}

/**
 * testEditMultipass method
 *
 * @return void
 */
	public function testEditMultipass() {
	}

/**
 * testEmailLowMultipass method
 *
 * @return void
 */
	public function testEmailLowMultipass() {
	}

/**
 * testEmailSaveFailure method
 *
 * @return void
 */
	public function testEmailSaveFailure() {
	}

/**
 * testEmailNoneAvailable method
 *
 * @return void
 */
	public function testEmailNoneAvailable() {
	}

/**
 * testGetMultipass method
 *
 * @return void
 */
	public function testGetMultipass() {
	}

/**
 * testInUseMultipass method
 *
 * @return void
 */
	public function testInUseMultipass() {
	}

/**
 * testAvailableMultipass method
 *
 * @return void
 */
	public function testAvailableMultipass() {
	}

}
