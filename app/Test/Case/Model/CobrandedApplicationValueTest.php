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
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplatePage',
		'app.onlineappTemplateSection',
		'app.onlineappTemplateField',
		'app.onlineappCobrandedApplicationValue',
		'app.onlineappCobrandedApplication',
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
		$this->loadFixtures('OnlineappCobrandedApplication');
		$this->loadFixtures('OnlineappCobrandedApplicationValue');

		$this->__template = $this->Template->find(
			'first',
			array(
				'conditions' => array(
					'name' => 'Template used to test afterSave of app values',
				)
			)
		);

		$this->User->create(
			array(
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
				'api' => 1,
				'cobrand_id' => 2,
				'template_id' => $this->__template['Template']['id'],
			)
		);
		$this->__user = $this->User->save();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		$this->CobrandedApplicationValue->deleteAll(true, false);
		$this->CobrandedApplication->deleteAll(true, false);
		$this->User->delete($this->__user['OnlineappUser']['id']);
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

		unset($this->CobrandedApplication);
		unset($this->CobrandedApplicationValue);
		unset($this->TemplateField);
		unset($this->TemplateSection);
		unset($this->TemplatePage);
		unset($this->Template);
		unset($this->Cobrand);
		unset($this->User);

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
		$applictionData = array(
			'user_id' => $this->__user['OnlineappUser']['id'],
			'template_id' => $this->__template['Template']['id'],
			'uuid' => String::uuid(),
		);

		$this->CobrandedApplication->create($applictionData);
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
				case 0:  // text      - no validation
				case 3:  // checkbox  - no validation
				case 4:  // radio     - no validation
				case 6:  // label     - no validation
				case 7:  // fees      - (group of money?)
				case 8:  // hr        - no validation
				case 20: // select    - no validation
				case 21: // textArea  - no validation
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
					$this->__testInvalidAndValidAppValues('money', $appValue, 'leters are not a valid money', '$123,000.00');
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
		$this->User->delete($this->User->id);
		$this->Template->delete($this->Template->id);
	}

	public function testSaveAppValueWithUnknownFieldType() {
		// create a new application from template with id 4
		// or find the template with a name = 'Template used to test afterSave of app values'
		$applictionData = array(
			'user_id' => $this->__user['OnlineappUser']['id'],
			'template_id' => $this->__template['Template']['id'],
			'uuid' => String::uuid(),
		);

		$this->CobrandedApplication->create($applictionData);
		$cobrandedApplication = $this->CobrandedApplication->save();
		$applicationAndValues = $this->CobrandedApplicationValue->find(
			'all',
			array(
				'conditions' => array('cobranded_application_id' => $this->CobrandedApplication->id)
			)
		);

		// lastly, test trying to save 'Unknown Type for testing'
		$unknownFieldType = $applicationAndValues[count($applicationAndValues)-1];
		$unknownFieldType['CobrandedApplicationValue']['value'] = 'a new value';

		$this->setExpectedException('Exception', 'Unknown field type, cannot validate it.', 1);
		$this->CobrandedApplicationValue->save($unknownFieldType['CobrandedApplicationValue']);
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
		$applicationValue = $this->CobrandedApplicationValue->find(
			'first',
			array(
				'conditions' => array('CobrandedApplicationValue.name' => 'Encrypt1')
			)
		);

		$this->assertEquals('encryption testing', $applicationValue['CobrandedApplicationValue']['value'],
			'verify value is decrypted as expected');
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
