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
		'app.onlineappGroup',
		'app.onlineappCoversheet',
		'app.onlineappUser',
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplatePage',
		'app.onlineappTemplateSection',
		'app.onlineappTemplateField',
		'app.onlineappCobrandedApplication',
		'app.onlineappCobrandedApplicationValue',
		'app.onlineappEmailTimelineSubject',
		'app.onlineappEmailTimeline',
		'app.onlineappUsersManager',
		'app.onlineappUsersTemplate',
		'app.onlineappUsersCobrand',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Group = ClassRegistry::init('Group');
		$this->Coversheet = ClassRegistry::init('Coversheet');
		$this->User = ClassRegistry::init('User');
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
		$this->TemplatePage = ClassRegistry::init('TemplatePage');
		$this->TemplateSection = ClassRegistry::init('TemplateSection');
		$this->TemplateField = ClassRegistry::init('TemplateField');
		$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
		$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');
		$this->OnlineappEmailTimelineSubject = ClassRegistry::init('EmailTimelineSubject');
		$this->OnlineappEmailTimeline = ClassRegistry::init('EmailTimeline');

		$this->Coversheet->validator()->remove('cp_encrypted_sn');
		$this->Coversheet->validator()->remove('cp_pinpad_ra_attached');
		$this->Coversheet->validator()->remove('moto_online_chd');

		// load data
		$this->loadFixtures('OnlineappGroup');
		$this->loadFixtures('OnlineappCobrand');
		$this->loadFixtures('OnlineappTemplate');
		$this->loadFixtures('OnlineappTemplatePage');
		$this->loadFixtures('OnlineappTemplateSection');
		$this->loadFixtures('OnlineappTemplateField');
		$this->loadFixtures('OnlineappUser');
		$this->loadFixtures('OnlineappCobrandedApplication');
		$this->loadFixtures('OnlineappCobrandedApplicationValue');
		$this->loadFixtures('OnlineappCoversheet');
		$this->loadFixtures('OnlineappEmailTimelineSubject');
		$this->loadFixtures('OnlineappEmailTimeline');
		$this->loadFixtures('OnlineappUsersManager');
		$this->loadFixtures('OnlineappUsersTemplate');
		$this->loadFixtures('OnlineappUsersCobrand');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		$this->OnlineappEmailTimeline->deleteAll(true, false);
		$this->OnlineappEmailTimelineSubject->deleteAll(true, false);
		$this->Coversheet->deleteAll(true, false);
		$this->CobrandedApplicationValue->deleteAll(true, false);
		$this->CobrandedApplication->deleteAll(true, false);
		$this->User->delete(1);
		$this->Group->deleteAll(true, false);
		$this->TemplateField->deleteAll(true, false);
		$this->TemplateSection->deleteAll(true, false);
		$this->TemplatePage->deleteAll(true, false);
		$this->Template->deleteAll(true, false);
		$this->Cobrand->deleteAll(true, false);
		unset($this->OnlineappEmailTimeline);
		unset($this->OnlineappEmailTimelineSubject);
		unset($this->CobrandedApplicationValue);
		unset($this->CobrandedApplication);
		unset($this->TemplateField);
		unset($this->TemplateSection);
		unset($this->TemplatePage);
		unset($this->Template);
		unset($this->Cobrand);
		unset($this->Coversheet);
		unset($this->User);
		unset($this->Group);
		parent::tearDown();
	}

/**
 * testCreate method
 *
 * @return void
 */
	public function testCreate() {
		$coversheet = $this->Coversheet->find('first',
			array(
				'conditions' => array(
					'Coversheet.id' => 1
				)
			)
		);

		$this->Coversheet->create($coversheet['Coversheet']);
		$response = $this->Coversheet->validates();
		$this->assertTrue($response);

		$response = $this->Coversheet->referrer();
		$this->assertFalse($response);

		$response = $this->Coversheet->reseller();
		$this->assertFalse($response);

		$coversheet = $this->Coversheet->find('first',
			array(
				'conditions' => array(
					'Coversheet.id' => 2
				)
			)
		);

		$this->Coversheet->create($coversheet['Coversheet']);
		$response = $this->Coversheet->validates();
		$this->assertFalse($response);

		$coversheet = $this->Coversheet->find('first',
			array(
				'conditions' => array(
					'Coversheet.id' => 1
				)
			)
		);

		$coversheet['Coversheet']['setup_tier_select'] = '3';
		$coversheet['Coversheet']['setup_tier3'] = '2';

		$this->Coversheet->create($coversheet['Coversheet']);
		$response = $this->Coversheet->validates();
		$this->assertFalse($response);

		$coversheet = $this->Coversheet->find('first',
			array(
				'conditions' => array(
					'Coversheet.id' => 1
				)
			)
		);

		$coversheet['Coversheet']['setup_tier_select'] = '3';
		$coversheet['Coversheet']['setup_tier3'] = '1';

		$this->Coversheet->create($coversheet['Coversheet']);
		$response = $this->Coversheet->validates();
		$this->assertTrue($response);

		$coversheet = $this->Coversheet->find('first',
			array(
				'conditions' => array(
					'Coversheet.id' => 1
				)
			)
		);

		$coversheet['Coversheet']['setup_tier_select'] = '4';
		$coversheet['Coversheet']['setup_tier4'] = '2';

		$this->Coversheet->create($coversheet['Coversheet']);
		$response = $this->Coversheet->validates();
		$this->assertFalse($response);

		$coversheet = $this->Coversheet->find('first',
			array(
				'conditions' => array(
					'Coversheet.id' => 1
				)
			)
		);

		$coversheet['Coversheet']['setup_tier_select'] = '4';
		$coversheet['Coversheet']['setup_tier4'] = '1';

		$this->Coversheet->create($coversheet['Coversheet']);
		$response = $this->Coversheet->validates();
		$this->assertTrue($response);

		$coversheet = $this->Coversheet->find('first',
			array(
				'conditions' => array(
					'Coversheet.id' => 2
				)
			)
		);

		$coversheet['Coversheet']['setup_tier5_financials'] = '1';
		$coversheet['Coversheet']['setup_tier5_processing_statements'] = '1';
		$coversheet['Coversheet']['setup_tier5_bank_statements'] = '1';
		$coversheet['Coversheet']['setup_equipment_terminal'] = '1';
		$coversheet['Coversheet']['setup_starterkit'] = '';
		$coversheet['Coversheet']['setup_lease_price'] = '';
		$coversheet['Coversheet']['gateway_package'] = '';

		$this->Coversheet->create($coversheet['Coversheet']);
		$response = $this->Coversheet->validates();
		$this->assertFalse($response);
	}

/**
 * testPdfGen method
 *
 * @covers Coversheet::pdfGen()
 * @return void
 */
	public function testPdfGen() {
		$response = $this->Coversheet->pdfGen('1', 'test');
		$this->assertTrue($response);

		$response = $this->Coversheet->pdfGen();
		$this->assertFalse($response);
	}

/**
 * testSendCoversheet method
 *
 * @covers Coversheet::sendCoversheet()
 * @return void
 */
	public function testSendCoversheet() {
		$file = WWW_ROOT . DS . 'files' . DS . 'axia_1_coversheet.pdf';
		$fh = (fopen($file, 'w'));
		fwrite($fh, "");
		fclose($fh);

		$response = $this->Coversheet->sendCoversheet(1, array('to' => 'test@axiamed.com'));
		$this->assertTrue($response);

		$response = $this->Coversheet->sendCoversheet();
		$this->assertFalse($response);
	}

/**
 * testUnlinkCoversheet method
 *
 * @covers Coversheet::unlinkCoversheet()
 * @return void
 */
	public function testUnlinkCoversheet() {
		$file = WWW_ROOT . DS . 'files' . DS . 'axia_1_coversheet.pdf';
		$fh = (fopen($file, 'w'));
		fwrite($fh, "");
		fclose($fh);

		$response = $this->Coversheet->unlinkCoversheet(1);
		$this->assertTrue($response);

		$response = $this->Coversheet->unlinkCoversheet();
		$this->assertFalse($response);
	}

/**
 * testFindIndex method
 *
 * @covers Coversheet::_findIndex()
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

/**
 * testOrConditions
 *
 * @covers Coversheet::orConditions()
 * @return void
 */
	public function testOrConditions() {
		$expectedResponse = array(
			'OR' => array(
				'Dba.value ILIKE' => '%test%',
				'CorpName.value ILIKE' => '%test%',
				'CorpCity.value ILIKE' => '%test%',
				'CorpContact.value ILIKE' => '%test%',
				'Owner1Name.value ILIKE' => '%test%',
				'Owner2Name.value ILIKE' => '%test%',
				'User.email ILIKE' => '%test%'
			)
		);

		$data['search'] = 'test';
		$response = $this->Coversheet->orConditions($data);

		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

/**
 * testCheckOrgRegionSubRegion
 *
 * @covers Coversheet::checkOrgRegionSubRegion()
 * @return void
 */
	public function testCheckOrgRegionSubRegion() {
		$tstData = [
			'Coversheet' => [
				'org_name' => null,
				'region_name' => 'tst region',
				'subregion_name' => 'tst subregion',
			]
		];
		$actual = $this->Coversheet->set($tstData);
		$actual = $this->Coversheet->checkOrgRegionSubRegion(['org_name' => null]);
		$this->assertSame("A parent Organization is required if Region or Subregion are entered", $actual);
		

		$tstData['Coversheet']['region_name'] = null;
		$actual = $this->Coversheet->clear();
		$actual = $this->Coversheet->set($tstData);
		$actual = $this->Coversheet->checkOrgRegionSubRegion(['region_name' => null]);
		$this->assertSame("Region is required if adding a Subregion", $actual);

		$tstData['Coversheet']['org_name'] = 'tst org';
		$tstData['Coversheet']['region_name'] = 'tst region';
		$actual = $this->Coversheet->clear();
		$actual = $this->Coversheet->set($tstData);
		$actual = $this->Coversheet->checkOrgRegionSubRegion(['org_name' => null]);
		$this->assertTrue($actual);
		$actual = $this->Coversheet->checkOrgRegionSubRegion(['region_name' => null]);
		$this->assertTrue($actual);
	}

/**
 * testCreateNew
 *
 * @covers Coversheet::createNew()
 * @return void
 */
	public function testCreateNew() {
		$appId = 999;
		$uid = 321;

		$this->assertNotEmpty($this->Coversheet->createNew($appId, $uid));
	}

/**
 * testDateIsNotInThePast
 *
 * @covers Coversheet::dateIsNotInThePast()
 * @return void
 */
	public function testDateIsNotInThePast() {
		for($x = 1; $x<10; $x ++) {
			$check = ['some_date' => (date('Y') + $x) . date('-m-d')];
			$this->assertTrue($this->Coversheet->dateIsNotInThePast($check));
			$check = ['some_date' => (date('Y') - $x) . date('-m-d')];
			$this->assertSame('Some Date cannot be in the past!', $this->Coversheet->dateIsNotInThePast($check));
		}
		$this->assertTrue($this->Coversheet->dateIsNotInThePast([]));
	}


/**
 * testReseller
 *
 * @covers Coversheet::reseller()
 * @return void
 */
	public function testReseller() {
		$data = [
			'Coversheet' => [
				'setup_reseller' => 'Some ref',
				'setup_reseller_type' => '',
				'setup_reseller_pct' => ''
			]
		];
		$this->Coversheet->set($data);
		$this->assertFalse($this->Coversheet->reseller());

		$data = [
			'Coversheet' => [
				'setup_reseller' => 'Some res',
				'setup_reseller_type' => 'Some type',
				'setup_reseller_pct' => '100'
			]
		];
		$this->Coversheet->set($data);
		$this->assertTrue($this->Coversheet->reseller());
	}

/**
 * testReferrer
 *
 * @covers Coversheet::referrer()
 * @return void
 */
	public function testReferrer() {
		$data = [
			'Coversheet' => [
				'setup_referrer' => 'Some ref',
				'setup_referrer_type' => '',
				'setup_referrer_pct' => ''
			]
		];
		$this->Coversheet->set($data);
		$this->assertFalse($this->Coversheet->referrer());

		$data = [
			'Coversheet' => [
				'setup_referrer' => 'Some ref',
				'setup_referrer_type' => 'Some type',
				'setup_referrer_pct' => '100'
			]
		];
		$this->Coversheet->set($data);
		$this->assertTrue($this->Coversheet->referrer());
	}

/**
 * testDebit
 *
 * @covers Coversheet::debit()
 * @return void
 */
	public function testDebit() {
		$data = [
			'Coversheet' => [
				'debit' => 'yes',
				'cp_encrypted_sn' => '',
				'cp_pinpad_ra_attached' => '0',
			]
		];
		$this->Coversheet->set($data);
		$this->assertFalse($this->Coversheet->debit());

		$data = [
			'Coversheet' => [
				'debit' => 'yes',
				'cp_encrypted_sn' => 'dummy val',
				'cp_pinpad_ra_attached' => '1',
			]
		];
		$this->Coversheet->set($data);
		$this->assertTrue($this->Coversheet->debit());
	}

/**
 * testCheck_guarantee
 *
 * @covers Coversheet::check_guarantee()
 * @return void
 */
	public function testCheck_guarantee() {
		$data = [
			'Coversheet' => [
				'cp_check_guarantee' => 'yes',
				'cp_check_guarantee_info' => '',
			]
		];
		$this->Coversheet->set($data);
		$this->assertFalse($this->Coversheet->check_guarantee());
		
		$data['Coversheet']['cp_check_guarantee_info'] = 'non empty';
		$this->Coversheet->set($data);
		$this->assertTrue($this->Coversheet->check_guarantee());
	}

/**
 * testPos
 *
 * @covers Coversheet::pos()
 * @return void
 */
	public function testPos() {
		$data = [
			'Coversheet' => [
				'cp_pos' => 'yes',
				'cp_pos_contact' => '',
			]
		];
		$this->Coversheet->set($data);
		$this->assertFalse($this->Coversheet->pos());
		
		$data['Coversheet']['cp_pos_contact'] = 'non empty';
		$this->Coversheet->set($data);
		$this->assertTrue($this->Coversheet->pos());
	}

/**
 * testMicros
 *
 * @covers Coversheet::micros()
 * @return void
 */
	public function testMicros() {
		$data = [
			'Coversheet' => [
				'micros' => 'yes',
				'micros_billing' => '',
			]
		];
		$this->Coversheet->set($data);
		$this->assertFalse($this->Coversheet->micros());
		
		$data['Coversheet']['micros_billing'] = 'non empty';
		$this->Coversheet->set($data);
		$this->assertTrue($this->Coversheet->micros());
	}

/**
 * testMoto
 *
 * @covers Coversheet::moto()
 * @return void
 */
	public function testMoto() {
		$data = [
			'Coversheet' => [
				'moto' => 'internet',
				'moto_online_chd' => '',
			]
		];
		$this->Coversheet->set($data);
		$this->assertFalse($this->Coversheet->moto());
		
		$data['Coversheet']['moto_online_chd'] = 'non empty';
		$this->Coversheet->set($data);
		$this->assertTrue($this->Coversheet->moto());
	}

/**
 * testGateway_package
 *
 * @covers Coversheet::gateway_package()
 * @return void
 */
	public function testGateway_package() {
		$data = [
			'Coversheet' => [
				'gateway_option' => 'Gateway',
				'gateway_package' => '',
			]
		];
		$this->Coversheet->set($data);
		$this->assertFalse($this->Coversheet->gateway_package());
		
		$data['Coversheet']['gateway_package'] = 'non empty';
		$this->Coversheet->set($data);
		$this->assertTrue($this->Coversheet->gateway_package());
	}

/**
 * testGateway_gold_subpackage
 *
 * @covers Coversheet::gateway_gold_subpackage()
 * @return void
 */
	public function testGateway_gold_subpackage() {
		$data = [
			'Coversheet' => [
				'gateway_package' => 'gold',
				'gateway_gold_subpackage' => '',
			]
		];
		$this->Coversheet->set($data);
		$this->assertFalse($this->Coversheet->gateway_gold_subpackage());
		
		$data['Coversheet']['gateway_gold_subpackage'] = 'non empty';
		$this->Coversheet->set($data);
		$this->assertTrue($this->Coversheet->gateway_gold_subpackage());
	}

/**
 * testGateway_epay
 *
 * @covers Coversheet::gateway_epay()
 * @return void
 */
	public function testGateway_epay() {
		$data = [
			'Coversheet' => [
				'gateway_option' => 'Gateway',
				'gateway_epay' => '',
			]
		];
		$this->Coversheet->set($data);
		$this->assertFalse($this->Coversheet->gateway_epay());
		
		$data['Coversheet']['gateway_epay'] = 'non empty';
		$this->Coversheet->set($data);
		$this->assertTrue($this->Coversheet->gateway_epay());
	}

/**
 * testGateway_billing
 *
 * @covers Coversheet::gateway_billing()
 * @return void
 */
	public function testGateway_billing() {
		$data = [
			'Coversheet' => [
				'gateway_option' => 'Gateway',
				'gateway_billing' => '',
			]
		];
		$this->Coversheet->set($data);
		$this->assertFalse($this->Coversheet->gateway_billing());
		
		$data['Coversheet']['gateway_billing'] = 'non empty';
		$this->Coversheet->set($data);
		$this->assertTrue($this->Coversheet->gateway_billing());
	}
}
