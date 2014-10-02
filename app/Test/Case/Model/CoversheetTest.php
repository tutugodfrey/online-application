<?php
App::uses('Coversheet', 'Model');

/**
 * Coversheet Test Case
 *
 */
class CoversheetTest extends CakeTestCase {
	public $autoFixtures = false;

	public $dropTables = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.onlineappCoversheet',
		'app.onlineappApplication',
		'app.onlineappUser',
//		'app.onlineappGroup',
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplatePage',
		'app.onlineappTemplateSection',
		'app.onlineappTemplateField',
		'app.onlineappCobrandedApplication',
		'app.onlineappCobrandedApplicationValue',
//		'app.onlineappApip',
//		'app.onlineappEpayment',
//		'app.onlineappApi_log',
//		'app.onlineappUsers_manager',
//		'app.onlineappMultipass',
//		'app.merchant',
//		'app.equipment_programming',
//		'app.timeline_entry',
//		'app.timeline_item',
//		'app.onlineappEmail_timeline',
//		'app.onlineappEmail_timeline_subject'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Coversheet = ClassRegistry::init('Coversheet');
		$this->Application = ClassRegistry::init('Application');
		$this->User = ClassRegistry::init('OnlineappUser');
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
		$this->TemplatePage = ClassRegistry::init('TemplatePage');
		$this->TemplateSection = ClassRegistry::init('TemplateSection');
		$this->TemplateField = ClassRegistry::init('TemplateField');
		$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
		$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');

		// load data
                $this->loadFixtures('OnlineappCobrand');
                $this->loadFixtures('OnlineappTemplate');
                $this->loadFixtures('OnlineappTemplatePage');
                $this->loadFixtures('OnlineappTemplateSection');
		$this->loadFixtures('OnlineappTemplateField');
		$this->loadFixtures('OnlineappUser');
		$this->loadFixtures('OnlineappCobrandedApplication');
		$this->loadFixtures('OnlineappCobrandedApplicationValue');
		$this->loadFixtures('OnlineappApplication');
		$this->loadFixtures('OnlineappCoversheet');


	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		$this->Coversheet->delete(1);
		$this->Application->deleteAll(true, false);
		$this->CobrandedApplicationValue->deleteAll(true, false);
		$this->CobrandedApplication->deleteAll(true, false);
		$this->User->delete(1);;
		$this->TemplateField->deleteAll(true, false);
		$this->TemplateSection->deleteAll(true, false);
		$this->TemplatePage->deleteAll(true, false);
		$this->Template->deleteAll(true, false);
		$query = 'ALTER TABLE onlineapp_users
		DROP CONSTRAINT onlineapp_users_cobrand_fk;
		UPDATE onlineapp_users SET cobrand_id = null;';
		$this->Cobrand->query($query);
		$this->Cobrand->deleteAll(true, false);
		$query = 'ALTER TABLE onlineapp_users
		ADD CONSTRAINT onlineapp_users_cobrand_fk FOREIGN KEY (cobrand_id) REFERENCES onlineapp_cobrands (id);';
		$this->Cobrand->query($query);
		unset($this->CobrandedApplicationValue);
		unset($this->CobrandedApplication);
		unset($this->TemplateField);
		unset($this->TemplateSection);
		unset($this->TemplatePage);
		unset($this->Template);
		unset($this->Cobrand);
		unset($this->Coversheet);
		unset($this->Application);
		unset($this->User);
		
		parent::tearDown();
	}

/**
 * testEquipment method
 *
 * @return void
 */
//	public function testEquipment() {
//	}

/**
 * testTier3 method
 *
 * @return void
 */
//	public function testTier3() {
//	}

/**
 * testTier4 method
 *
 * @return void
 */
//	public function testTier4() {
//	}

/**
 * testSetupTier5Financials method
 *
 * @return void
 */
//	public function testSetupTier5Financials() {
//	}

/**
 * testSetupTier5ProcessingStatements method
 *
 * @return void
 */
//	public function testSetupTier5ProcessingStatements() {
//	}

/**
 * testSetupTier5BankStatements method
 *
 * @return void
 */
//	public function testSetupTier5BankStatements() {
//	}

/**
 * testSetupStarterkit method
 *
 * @return void
 */
//	public function testSetupStarterkit() {
//	}

/**
 * testEquipmentPayment method
 *
 * @return void
 */
//	public function testEquipmentPayment() {
//	}

/**
 * testReferrer method
 *
 * @return void
 */
//	public function testReferrer() {
//	}

/**
 * testReseller method
 *
 * @return void
 */
//	public function testReseller() {
//	}

/**
 * testDebit method
 *
 * @return void
 */
//	public function testDebit() {
//	}

/**
 * testCheckGuarantee method
 *
 * @return void
 */
//	public function testCheckGuarantee() {
//	}

/**
 * testPos method
 *
 * @return void
 */
//	public function testPos() {
//	}

/**
 * testMicros method
 *
 * @return void
 */
//	public function testMicros() {
//	}

/**
 * testMoto method
 *
 * @return void
 */
//	public function testMoto() {
//	}

/**
 * testGatewayPackage method
 *
 * @return void
 */
//	public function testGatewayPackage() {
//	}

/**
 * testGatewayGoldSubpackage method
 *
 * @return void
 */
//	public function testGatewayGoldSubpackage() {
//	}

/**
 * testGatewayEpay method
 *
 * @return void
 */
//	public function testGatewayEpay() {
//	}

/**
 * testGatewayBilling method
 *
 * @return void
 */
//	public function testGatewayBilling() {
//	}

/**
 * testSendCoversheet method
 *
 * @return void
 */
//	public function testSendCoversheet() {
//	}
/**
 * testFindIndex method
 *
 * @return void
 */
	public function testFindIndex() {
		$expectedResponse = array(
				0 => array(
					'Coversheet' => array(
						'id' => 1,
						'status' => 'Lorem ip',
					),
					'Dba' => array(
						'value' => 'Doing Business As',
					),
					'User' => array(
						'id' => 1,
						'firstname' => 'Lorem ipsum dolor sit amet',
						'lastname' => 'Lorem ipsum dolor sit amet',
					),
					'CobrandedApplication' => array(
						'id' => 1,
						'uuid' => 'b118ac22d3cd4ab49148b05d5254ed59',
						'status' => null,
					),
				),
		);
		$response = $this->Coversheet->find('index', array('limit' => 1));
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}
		
}
