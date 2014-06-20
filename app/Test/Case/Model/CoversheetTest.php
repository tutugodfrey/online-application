<?php
App::uses('Coversheet', 'Model');

/**
 * Coversheet Test Case
 *
 */
class CoversheetTest extends CakeTestCase {
  public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.onlineappCoversheet',
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
		$this->Coversheet = ClassRegistry::init('Coversheet');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Coversheet);

		parent::tearDown();
	}

/**
 * testEquipment method
 *
 * @return void
 */
	public function testEquipment() {
	}

/**
 * testTier3 method
 *
 * @return void
 */
	public function testTier3() {
	}

/**
 * testTier4 method
 *
 * @return void
 */
	public function testTier4() {
	}

/**
 * testSetupTier5Financials method
 *
 * @return void
 */
	public function testSetupTier5Financials() {
	}

/**
 * testSetupTier5ProcessingStatements method
 *
 * @return void
 */
	public function testSetupTier5ProcessingStatements() {
	}

/**
 * testSetupTier5BankStatements method
 *
 * @return void
 */
	public function testSetupTier5BankStatements() {
	}

/**
 * testSetupStarterkit method
 *
 * @return void
 */
	public function testSetupStarterkit() {
	}

/**
 * testEquipmentPayment method
 *
 * @return void
 */
	public function testEquipmentPayment() {
	}

/**
 * testReferrer method
 *
 * @return void
 */
	public function testReferrer() {
	}

/**
 * testReseller method
 *
 * @return void
 */
	public function testReseller() {
	}

/**
 * testDebit method
 *
 * @return void
 */
	public function testDebit() {
	}

/**
 * testCheckGuarantee method
 *
 * @return void
 */
	public function testCheckGuarantee() {
	}

/**
 * testPos method
 *
 * @return void
 */
	public function testPos() {
	}

/**
 * testMicros method
 *
 * @return void
 */
	public function testMicros() {
	}

/**
 * testMoto method
 *
 * @return void
 */
	public function testMoto() {
	}

/**
 * testGatewayPackage method
 *
 * @return void
 */
	public function testGatewayPackage() {
	}

/**
 * testGatewayGoldSubpackage method
 *
 * @return void
 */
	public function testGatewayGoldSubpackage() {
	}

/**
 * testGatewayEpay method
 *
 * @return void
 */
	public function testGatewayEpay() {
	}

/**
 * testGatewayBilling method
 *
 * @return void
 */
	public function testGatewayBilling() {
	}

/**
 * testSendCoversheet method
 *
 * @return void
 */
	public function testSendCoversheet() {
	}

}
