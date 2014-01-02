<?php
App::uses('Application', 'Model');

/**
 * Application Test Case
 *
 */
class ApplicationTest extends CakeTestCase {
	public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
		$this->Application = ClassRegistry::init('Application');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Application);

		parent::tearDown();
	}

/**
 * testExpiration method
 *
 * @return void
 */
	public function testExpiration() {
	}

/**
 * testCreateOAuth method
 *
 * @return void
 */
	public function testCreateOAuth() {
	}

/**
 * testBillingDeliveryPolicy method
 *
 * @return void
 */
	public function testBillingDeliveryPolicy() {
	}

/**
 * testCheckDepositRoutingNumber method
 *
 * @return void
 */
	public function testCheckDepositRoutingNumber() {
	}

/**
 * testCheckFeesRoutingNumber method
 *
 * @return void
 */
	public function testCheckFeesRoutingNumber() {
	}

/**
 * testMultipassCsv method
 *
 * @return void
 */
	public function testMultipassCsv() {
	}

/**
 * testPaginationRules method
 *
 * @return void
 */
	public function testPaginationRules() {
	}

}
