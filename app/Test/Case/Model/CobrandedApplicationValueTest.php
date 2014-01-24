<?php
App::uses('CobrandedApplicationValue', 'Model');

/**
 * CobrandedApplicationValue Test Case
 *
 */
class CobrandedApplicationValueTest extends CakeTestCase {

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

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
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
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		$this->CobrandedApplicationValue->deleteAll(true, false);
		$this->CobrandedApplication->deleteAll(true, false);
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

		unset($this->TemplateField);
		unset($this->TemplateSection);
		unset($this->TemplatePage);
		unset($this->Template);
		unset($this->Cobrand);
		unset($this->CobrandedApplication);
		unset($this->CobrandedApplicationValue);

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

}
