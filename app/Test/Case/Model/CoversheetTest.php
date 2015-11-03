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
		'app.group',
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
		$this->User = ClassRegistry::init('OnlineappUser');
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
		$this->TemplatePage = ClassRegistry::init('TemplatePage');
		$this->TemplateSection = ClassRegistry::init('TemplateSection');
		$this->TemplateField = ClassRegistry::init('TemplateField');
		$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
		$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');
		$this->OnlineappEmailTimelineSubject = ClassRegistry::init('OnlineappEmailTimelineSubject');
		$this->OnlineappEmailTimeline = ClassRegistry::init('OnlineappEmailTimeline');

		$this->Coversheet->validator()->remove('cp_encrypted_sn');
		$this->Coversheet->validator()->remove('cp_pinpad_ra_attached');
		$this->Coversheet->validator()->remove('moto_online_chd');

		// load data
		$this->loadFixtures('Group');
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
 * @return void
 */
	public function testSendCoversheet() {
		$file = WWW_ROOT . DS . 'files' . DS . 'axia_1_coversheet.pdf';
		$fh = (fopen($file, 'w'));
		fwrite($fh, "");
		fclose($fh);

		$response = $this->Coversheet->sendCoversheet(1, array('to' => 'test@axiapayments.com'));
		$this->assertTrue($response);

		$response = $this->Coversheet->sendCoversheet();
		$this->assertFalse($response);
	}

/**
 * testUnlinkCoversheet method
 *
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
}
