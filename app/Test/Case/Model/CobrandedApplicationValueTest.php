<?php
App::uses('CobrandedApplicationValue', 'Model');

/**
 * CobrandedApplicationValue Test Case
 *
 */
class CobrandedApplicationValueTest extends CakeTestCase {

	public $dropTables = false;

	public $autoFixtures = false;

	/**
	 * Fixtures
	 *
	 * @var array
	 */
		public $fixtures = array(
//		'app.onlineappUser',
			'app.group',
			'app.onlineappCobrand',
			'app.onlineappTemplate',
			'app.onlineappTemplatePage',
			'app.onlineappTemplateSection',
			'app.onlineappTemplateField',
			'app.onlineappCobrandedApplication',
			'app.onlineappCobrandedApplicationValue',
			'app.onlineappCobrandedApplicationAch',
			'app.onlineappCoversheet',
			'app.onlineappEmailTimelineSubject',
			'app.onlineappEmailTimeline'
		);

		private $__template;

		private $__user;

	/**
	 * setUp method
	 *
	 * @return void
	 */
		public function setUp() {
			parent::setUp();
			$this->User = ClassRegistry::init('OnlineappUser');
			$this->Group = ClassRegistry::init('Group');
			$this->Coversheet = ClassRegistry::init('Coversheet');
			$this->Cobrand = ClassRegistry::init('Cobrand');
			$this->Template = ClassRegistry::init('Template');
			$this->TemplatePage = ClassRegistry::init('TemplatePage');
			$this->TemplateSection = ClassRegistry::init('TemplateSection');
			$this->TemplateField = ClassRegistry::init('TemplateField');
			$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
			$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');
			$this->CobrandedApplicationAch = ClassRegistry::init('CobrandedApplicationAch');
			$this->OnlineappEmailTimelineSubject = ClassRegistry::init('OnlineappEmailTimelineSubject');
			$this->OnlineappEmailTimeline = ClassRegistry::init('OnlineappEmailTimeline');

			// load data
//			$this->loadFixtures('OnlineappUser');
			$this->loadFixtures('Group');
			$this->loadFixtures('OnlineappCobrand');
			$this->loadFixtures('OnlineappTemplate');
			$this->loadFixtures('OnlineappTemplatePage');
			$this->loadFixtures('OnlineappTemplateSection');
			$this->loadFixtures('OnlineappTemplateField');
			$this->loadFixtures('OnlineappCobrandedApplication');
			$this->loadFixtures('OnlineappCobrandedApplicationValue');
			$this->loadFixtures('OnlineappCobrandedApplicationAch');
			$this->loadFixtures('OnlineappEmailTimelineSubject');
			$this->loadFixtures('OnlineappEmailTimeline');

			$this->__template = $this->Template->find(
				'first',
				array(
					'conditions' => array(
						'name' => 'Template used to test afterSave of app values',
					)
				)
			);

			//$this->User->create(
//			$this->User->deleteAll(true, false);
			$user =	array(
				'id' => 1,
					'email' => 'testing@axiapayments.com',
					'password' => '0e41ea572d9a80c784935f2fc898ac34649079a9',
					'group_id' => 1,
					'created' => '2014-01-24 11:02:22',
					'modified' => '2014-01-24 11:02:22',
					'token' => 'sometokenvalue',
					'token_used' => '2014-01-24 11:02:22',
					'token_uses' => 1,
					'firstname' => 'testuser1firstname',
					'lastname' => 'testuser1lastname',
					'extension' => 1,
					'active' => 1,
					'api_password' => 'notset',
					'api_enabled' => 1,
					'template_id' => $this->__template['Template']['id'],
			);

			$this->__user = $this->User->save($user);

			$this->loadFixtures('OnlineappCoversheet');
		}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
		public function tearDown() {
			$this->Coversheet->deleteAll(true, false);
			$this->OnlineappEmailTimeline->deleteAll(true, false);
			$this->OnlineappEmailTimelineSubject->deleteAll(true, false);
			$this->CobrandedApplicationAch->deleteAll(true, false);
			$this->CobrandedApplicationValue->deleteAll(true, false);
			$this->CobrandedApplication->deleteAll(true, false);
			$this->User->delete($this->__user['OnlineappUser']['id']);
			$this->Group->deleteAll(true, false);
			$this->TemplateField->deleteAll(true, false);
			$this->TemplateSection->deleteAll(true, false);
			$this->TemplatePage->deleteAll(true, false);
			$this->Template->deleteAll(true, false);
			$this->Cobrand->deleteAll(true, false);
			unset($this->Coversheet);
			unset($this->CobrandedApplicationAch);
			unset($this->CobrandedApplicationValue);
			unset($this->CobrandedApplication);
			unset($this->TemplateField);
			unset($this->TemplateSection);
			unset($this->TemplatePage);
			unset($this->Template);
			unset($this->Cobrand);
			unset($this->User);
			unset($this->Group);
			unset($this->EmailTimeline);
			unset($this->EmailTimelineSubject);

			parent::tearDown();
		}

	public function testValidation() {
		// we need the application in order to add a new application value
		$application = $this->CobrandedApplication->findById(1);

		$expectedValidationErrors = array(
			'cobranded_application_id' => array('Numeric value expected'),
			'template_field_id' => array('Numeric value expected'),
			'name' => array('Name is required'),
		);
		$this->CobrandedApplicationValue->create(
			array(
				'cobranded_application_id' => '',
				'template_field_id' => '',
				'name' => '',
			)
		);

		$this->assertFalse($this->CobrandedApplicationValue->validates());
		$this->assertEquals(
			$expectedValidationErrors,
			$this->CobrandedApplicationValue->validationErrors,
			'verify create with empty strings'
		);

		// test go right path
		$this->CobrandedApplicationValue->create(
			array(
				'cobranded_application_id' => $application['CobrandedApplication']['id'],
				'template_field_id' => 1,
				'name' => 'Field 1',
				'value' => 'Value 1',
			)
		);
		$expectedValidationErrors = array();
		$this->assertTrue($this->CobrandedApplicationValue->validates());
		$this->assertEquals(
			$expectedValidationErrors,
			$this->CobrandedApplicationValue->validationErrors,
			'verify create produces empty validationErrors array'
		);
	}

	public function testBeforeSaveValidation() {
		// create a new application from template with id 4
		// or find the template with a name = 'Template used to test afterSave of app values'
		$applicationData = array(
			'user_id' => $this->__user['OnlineappUser']['id'],
			'template_id' => $this->__template['Template']['id'],
			'uuid' => String::uuid(),
		);
		$this->CobrandedApplication->create($applicationData);
		$cobrandedApplication = $this->CobrandedApplication->save();
		$applicationAndValues = $this->CobrandedApplicationValue->find(
			'all',
			array(
				'conditions' => array('cobranded_application_id' => $this->CobrandedApplication->id)
			)
		);

		// try to set the value for each application-value
		foreach ($applicationAndValues as $key => $appValue) {
			switch ($appValue['TemplateField']['type']) {
				case 0:  // text        - no validation
				case 3:  // checkbox    - no validation
				case 4:  // radio       - no validation
				case 6:  // label       - no validation
				case 7:  // fees        - (group of money?)
				case 8:  // hr          - no validation
				case 20: // select      - no validation
				case 21: // textArea    - no validation
				case 22: // multirecord	- no validation here, will happen in the multirecord Model
					// always valid so don't test
					break;

				case 1:  //  1 - yyyy[/-.]mm[/-.]dd (dateISO)
					$this->__testInvalidAndValidAppValues('date', $appValue, 'leters are not a valid date', '2014-01-01');
					break;

				case 2:  // time      - hh:mm:ss [a|p]m
					$this->__testInvalidAndValidAppValues('time', $appValue, 'leters are not a valid time', '08:00 am');
					break;

				case 5:  // percents  - between [0-100]
				case 11: // percent   - (0-100)%
					$this->__testInvalidAndValidAppValues('percent', $appValue, 'leters are not a valid percent', '88');
					break;

				case 9:  // phoneUS   - (###) ###-####
					$this->__testInvalidAndValidAppValues('phoneUS', $appValue, 'leters are not a valid phoneus', '888-555-1234');
					break;

				case 10: // money     - $(#(1-3),)?(#(1-3)).## << needs work
					$this->__testInvalidAndValidAppValues('money', $appValue, 'leters are not a valid money', '$123000.00');
					break;
				case 12: // ssn       - ###-##-####
					$this->__testInvalidAndValidAppValues('ssn', $appValue, 'leters are not a valid ssn', '123-45-6789');
					break;

				case 13: // zipcodeUS - #####[-####]
					$this->__testInvalidAndValidAppValues('zipcodeUS', $appValue, 'leters are not a valid zipcodeUS', '99999-9999');
					$this->__testInvalidAndValidAppValues('zipcodeUS', $appValue, '99999-99', '99999');
					break;

				case 14: // email     - name@domian.com
					$this->__testInvalidAndValidAppValues('email', $appValue, 'leters are not a valid email', 'name@domain.com');
					break;

				//case 15: // lengthoftime - [#+] [year|month|day]s
				//case 16: // creditcard -

				case 17: // url       - http(s)?://domain.com
					$this->__testInvalidAndValidAppValues('url', $appValue, 'leters are not a valid url', 'http://www.somewhere.com');
					break;

				case 18: // number    - (#)+.(#)+
					$this->__testInvalidAndValidAppValues('number', $appValue, 'leters are not a valid number', '2342.09808');
					break;

				case 19: // digits    - (#)+
					$this->__testInvalidAndValidAppValues('url', $appValue, 'leters are not a valid digits', '234232');
					break;

				default:
					# code...
					break;
			}
		}

		// for each app value, test the beforeSave validation step
		$this->CobrandedApplication->delete($this->CobrandedApplication->id);
		//$this->User->delete($this->User->id);
		$this->Template->delete($this->Template->id);
	}

	public function testSaveAppValueWithUnknownFieldType() {
		// create a new application from template with id 4
		// find the template with a name = 'Template used to test afterSave of app values'
		// find the template field with an id of 35 "Unknown Type for testing"
		$templateFieldId = 35;
		$applicationData = array(
			'user_id' => $this->__user['OnlineappUser']['id'],
			'template_id' => $this->__template['Template']['id'],
			'uuid' => String::uuid(),
		);

		$this->CobrandedApplication->create($applicationData);
		$cobrandedApplication = $this->CobrandedApplication->save();

		$applicationAndValues = $this->CobrandedApplicationValue->find(
			'first',
			array(
				'conditions' => array('cobranded_application_id' => $this->CobrandedApplication->id,
					'template_field_id' => '35')
			)
		);

		// lastly, test trying to save 'Unknown Type for testing'

		$applicationAndValues['CobrandedApplicationValue']['value'] = 'a new value';
		$this->setExpectedException('Exception', 'Unknown field type, cannot validate it.', 1);
		$this->CobrandedApplicationValue->save($applicationAndValues['CobrandedApplicationValue']);


	}

	public function testApplicationValueEncryption() {
		$applicationValue = $this->CobrandedApplicationValue->find(
			'first',
			array(
				'conditions' => array('CobrandedApplicationValue.name' => 'Encrypt1')
			)
		);
		$this->assertEquals(null, $applicationValue['CobrandedApplicationValue']['value'], 'verify Encrypt1 application value is initially null');

		$testValue = 'encryption testing';

		// application value should be encrypted in database after this
		$applicationValue['CobrandedApplicationValue']['value'] = $testValue;
		$this->CobrandedApplicationValue->save($applicationValue);

		// encrypt our test value for comparison
		$encryptedTestValue = base64_encode(mcrypt_encrypt(Configure::read('Cryptable.cipher'), Configure::read('Cryptable.key'),
			$testValue, 'cbc', Configure::read('Cryptable.iv')));

		$result = $this->db->query("SELECT * from onlineapp_cobranded_application_values where cobranded_application_id = 1 and name = 'Encrypt1'");
		$encryptedDbValue = $result[0][0]['value'];
		$this->assertEquals($encryptedTestValue, $encryptedDbValue, 'verify value is encrypted as expected in database');
	}

	public function testBeforeSaveEncryption() {
		$applicationValue = $this->CobrandedApplicationValue->find(
			'first',
			array(
				'conditions' => array('CobrandedApplicationValue.name' => 'Encrypt1')
			)
		);
		$this->assertEquals(null, $applicationValue['CobrandedApplicationValue']['value'],
			'verify Encrypt1 application value is initially null');

		$testValue = 'encryption testing';

		// application value should be encrypted in database after this
		$applicationValue['CobrandedApplicationValue']['value'] = $testValue;
		$result = $this->CobrandedApplicationValue->save($applicationValue);

		$this->assertEquals('XD+C8LSmk/u58hI1tyN88qlZtIRVvBa+', $result['CobrandedApplicationValue']['value'],
			'verify value is encrypted as expected in database');
	}

	public function testAfterFind() {
		$applicationValue = $this->CobrandedApplicationValue->find(
			'first',
			array(
				'conditions' => array('CobrandedApplicationValue.name' => 'Encrypt1')
			)
		);
		$this->assertEquals(null, $applicationValue['CobrandedApplicationValue']['value'],
			'verify Encrypt1 application value is initially null');

		$testValue = 'encryption testing';

		// application value should be encrypted in database after this
		$applicationValue['CobrandedApplicationValue']['value'] = $testValue;
		$this->CobrandedApplicationValue->save($applicationValue);

		// retrieve application value
		// value should get decrypted in afterFind()
		// it should also get masked, displaying only last 4 chars
		$applicationValue = $this->CobrandedApplicationValue->find(
			'first',
			array(
				'conditions' => array('CobrandedApplicationValue.name' => 'Encrypt1')
			)
		);

		$this->assertEquals('XXXXXXXXXXXXXXting', $applicationValue['CobrandedApplicationValue']['value'],
			'verify value is decrypted and masked as expected');
	}

	public function testBeforeSaveMaskCheck() {
		$applicationValue = $this->CobrandedApplicationValue->find(
			'first',
			array(
				'conditions' => array('CobrandedApplicationValue.name' => 'Encrypt1')
			)
		);
		$this->assertEquals(null, $applicationValue['CobrandedApplicationValue']['value'],
			'verify Encrypt1 application value is initially null');

		$testValue = 'encryption testing';

		// application value should be encrypted in database after this
		$applicationValue['CobrandedApplicationValue']['value'] = $testValue;
		$this->CobrandedApplicationValue->save($applicationValue);

		// retrieve application value
		// value should get decrypted in afterFind()
		// it should also get masked, displaying only last 4 chars
		$applicationValue = $this->CobrandedApplicationValue->find(
			'first',
			array(
				'conditions' => array('CobrandedApplicationValue.name' => 'Encrypt1')
			)
		);

		$this->assertEquals('XXXXXXXXXXXXXXting', $applicationValue['CobrandedApplicationValue']['value'],
			'verify value is decrypted and masked as expected');

		// try to modify and save the value - this should not work
		// otherwise values will get stored with masking
		$applicationValue['CobrandedApplicationValue']['value'] .= '123';

		// try to update
		$result = $this->CobrandedApplicationValue->save($applicationValue);

		// check that value did not change
		$applicationValue = $this->CobrandedApplicationValue->find(
			'first',
			array(
				'conditions' => array('CobrandedApplicationValue.name' => 'Encrypt1')
			)
		);

		$this->assertEquals('XXXXXXXXXXXXXXting', $applicationValue['CobrandedApplicationValue']['value'],
			'verify value is decrypted and masked as expected');
	}

	public function testCheckRoutingNumber() {
		$response = $this->CobrandedApplicationValue->checkRoutingNumber();
		$this->assertFalse($response, 'check routing number without passing a number should fail.');

		$response = $this->CobrandedApplicationValue->checkRoutingNumber('321-174-851');
		$this->assertTrue($response, 'check routing number containing non-numerical characters should still succeed');

		$response = $this->CobrandedApplicationValue->checkRoutingNumber('321174851');
		$this->assertTrue($response, 'check routing number with good number should succeed.');

		$response = $this->CobrandedApplicationValue->checkRoutingNumber('000000001');
		$this->assertFalse($response, 'check routing number with bad number should fail.');

		$response = $this->CobrandedApplicationValue->checkRoutingNumber('001');
		$this->assertFalse($response, 'check routing number with number that is not 9 digits long should fail.');
	}

	private function __testInvalidAndValidAppValues($typeString, $appValue, $invalid, $valid) {
		// try to update to an invalid value
		$appValue['CobrandedApplicationValue']['value'] = $invalid;
		$this->assertFalse($this->CobrandedApplicationValue->save($appValue), 'Saving an invalid '.$typeString.' value should fail.');

		// now test the go right path
		$appValue['CobrandedApplicationValue']['value'] = $valid;
		// verify that ['CobrandedApplicationValue']['value'] == $valid
		$actual = $this->CobrandedApplicationValue->save($appValue);
		$this->assertEquals($valid, $actual['CobrandedApplicationValue']['value'], 'Saving a valid '.$typeString.' value should succeed.');
	}

}
