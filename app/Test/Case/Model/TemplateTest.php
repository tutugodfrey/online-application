<?php
App::uses('Cobrand', 'Model');
App::uses('Template', 'Model');

class TemplateTest extends CakeTestCase {

	public $autoFixtures = false;

	public $fixtures = array(
		'app.onlineappUser',
		'app.onlineappUsersCobrand',
		'app.onlineappUsersTemplate',
		'app.onlineappUsersManager',
		'app.onlineappGroup',
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

	public function setUp() {
		parent::setUp();
		$this->Group = ClassRegistry::init('Group');
		$this->User = ClassRegistry::init('User');
		$this->UsersManager = ClassRegistry::init('UsersManager');
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
		$this->TemplatePage = ClassRegistry::init('TemplatePage');
		$this->TemplateSection = ClassRegistry::init('TemplateSection');
		$this->TemplateField = ClassRegistry::init('TemplateField');
		$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
		$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');

		// load data
		$this->loadFixtures('OnlineappUser');
		$this->loadFixtures('OnlineappUsersCobrand');
		$this->loadFixtures('OnlineappUsersTemplate');
		$this->loadFixtures('OnlineappUsersManager');
		$this->loadFixtures('OnlineappGroup');
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
					'name' => 'Template used to test getFields',
				)
			)
		);

		$this->User->create(
			array(
				'email' => 'testing@axiatech.com',
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
				'pw_expiry_date' => '2019-10-01'
			)
		);
		$this->__user = $this->User->save();
	}

	public function tearDown() {
		$this->CobrandedApplicationValue->deleteAll(true, false);
		$this->CobrandedApplication->deleteAll(true, false);
		$this->User->delete($this->__user['User']['id']);
		$this->Group->deleteAll(true, false);
		$this->TemplateField->deleteAll(true, false);
		$this->TemplateSection->deleteAll(true, false);
		$this->TemplatePage->deleteAll(true, false);
		$this->Template->deleteAll(true, false);
		$this->Cobrand->deleteAll(true, false);
		unset($this->CobrandedApplicationValue);
		unset($this->CobrandedApplication);
		unset($this->TemplateField);
		unset($this->TemplateSection);
		unset($this->TemplatePage);
		unset($this->Template);
		unset($this->Cobrand);
		unset($this->User);
		unset($this->Group);
		parent::tearDown();
	}

	public function testGetList() {
		$expected = array();
		$expected[1] = 'Partner Name 1 - Template 1 for PN1';
		$expected[2] = 'Partner Name 1 - Template 2 for PN1';

		$result = $this->Template->getList(1);

		$this->assertEquals($expected, $result);
	}

	public function testGetCobrand() {
		$expectedCobrand = array(
			'Cobrand' => array (
				'id' => 1,
				'partner_name' => 'Partner Name 1',
				'partner_name_short' => 'PN1',
				'description' => 'Cobrand "Partner Name 1" description goes here.',
				'created' => '2007-03-18 10:41:31',
				'modified' => '2007-03-18 10:41:31',
				'cobrand_logo_url' => 'PN1 logo_url',
				'response_url_type' => null,
				'brand_logo_url' => 'PN1 logo_url',
				'brand_name' => null
			),
		);
		$returnedCobrand = $this->Template->getCobrand(1);
		$this->assertEquals($expectedCobrand, $returnedCobrand);

		$expectedCobrand = array(
			'Cobrand' => array (
				'id' => 2,
				'partner_name' => 'Partner Name 2',
				'partner_name_short' => 'PN2',
				'description' => 'Cobrand "Partner Name 2" description goes here.',
				'created' => '2007-03-18 10:41:31',
				'modified' => '2007-03-18 10:41:31',
				'cobrand_logo_url' => 'PN2 logo_url',
				'response_url_type' => null,
				'brand_logo_url' => 'PN2 logo_url',
				'brand_name' => null
			),
		);
		$returnedCobrand = $this->Template->getCobrand(2);
		$this->assertEquals($expectedCobrand, $returnedCobrand);

		$expectedCobrand = array(
			'Cobrand' => array (
				'id' => 3,
				'partner_name' => 'Partner Name 3',
				'partner_name_short' => 'PN3',
				'description' => 'Cobrand "Partner Name 3" description goes here.',
				'created' => '2007-03-18 10:41:31',
				'modified' => '2007-03-18 10:41:31',
				'cobrand_logo_url' => 'PN3 logo_url',
				'response_url_type' => null,
				'brand_logo_url' => 'PN3 logo_url',
				'brand_name' => null
			),
		);
		$returnedCobrand = $this->Template->getCobrand(3);
		$this->assertEquals($expectedCobrand, $returnedCobrand);
	}

	public function testValidation() {
		$expectedValidationErrors = array(
			'name' => array('Template name cannot be empty'),
			'logo_position' => array('Logo position value not selected'),
		);

		$this->Template->create(array('name' => '', 'logo_position' => ''));
		$this->assertFalse($this->Template->validates());
		$this->assertEquals($expectedValidationErrors, $this->Template->validationErrors);

		// test non-numeric cobrand_id
		$expectedValidationErrors = array(
			'cobrand_id' => array('Invalid cobrand_id value used'),
			'logo_position' => array('Logo position value not selected'),
		);
		$this->Template->create(array('name' => 'template name', 'cobrand_id' => 'abcd', 'logo_position' => ''));
		$this->assertFalse($this->Template->validates());
		$this->assertEquals($expectedValidationErrors, $this->Template->validationErrors);

		// test the go right path
		$expectedValidationErrors = array();
		$newTemplateData = array('name' => 'template name', 'cobrand_id' => 1, 'logo_position' => 0);
		$this->Template->create($newTemplateData);
		$this->Template->save($newTemplateData);
		$this->assertTrue($this->Template->validates());
		$this->assertEquals($expectedValidationErrors, $this->Template->validationErrors);
	}

	public function testGetTemplateApiFields() {
		$templates = $this->Template->find('all', array('order' => 'Template.id'));
		$expectedFields = array(
			array(), // all fields are expected to be from the user
			array(), // all fields are expected to be from the user
			array(), // all fields are expected to be from the user
			array(), // all fields are expected to be from the user
			array(
				'required_text_from_api_without_default' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Text field'
				),
				'required_text_from_api_without_default_source_2' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Text field'
				),
				'DBA' => array(
					'type' => 'text',
					'required' => true,
					'name' => 'DBA',
					'description' => 'DBA'
				),
				'EMail' => array(
						'type' => 'email',
						'required' => true,
						'name' => 'EMail',
						'description' => 'EMail'
				),
				'multirecord_from_api_with_default' => array(
					array(
						'description' => array(
							'type' => 'text',
							'required' => false,
							'description' => 'Description',
						),
						'auth_type' => array(
							'type' => 'text',
							'required' => true,
							'description' => 'Authorization Type',
						),
						'routing_number' => array(
							'type' => 'text',
							'required' => true,
							'description' => 'Routing #',
						),
						'account_number' => Array (
							'type' => 'text',
							'required' => true,
							'description' => 'Account #',
						),
						'bank_name' => array(
							'type' => 'text',
							'required' => true,
							'description' => 'Bank Name',
						),
					),
				),
				'OwnerType-Corp' => array(
					'type' => 'radio',
					'required' => false,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-SoleProp' => array(
					'type' => 'radio',
					'required' => false,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-LLC' => array(
					'type' => 'radio',
					'required' => false,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-Partnership' => array(
					'type' => 'radio',
					'required' => false,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-NonProfit' => array(
					'type' => 'radio',
					'required' => false,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-Other' => array(
					'type' => 'radio',
					'required' => false,
					'description' => '',
					'name' => 'Owner Type - '
				),
			), // one field from the user
			array() // all fields are expected to be from the user
		);
		$index = 0;
		foreach ($templates as $key => $template) {
			$actualFields = $this->Template->getTemplateApiFields($template['Template']['id']);
			$this->assertEquals(
				$expectedFields[$index],
				$actualFields,
				'Template with id [' . $index . '] did not match expected API fields');
			$index = $index + 1;
		}

		$expectedFields = array(
			array(
				'required_text_from_user_without_default' => array(
					'type' => 'text',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
					'name' => 'field 3'
				),
				'required_radio_from_user_without_defaultvalue1' => Array (
					'type' => 'radio',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
					'name' => 'field 4'
				),
				'required_radio_from_user_without_defaultvalue2' => Array (
					'type' => 'radio',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
					'name' => 'field 4'
				),
				'required_radio_from_user_without_defaultvalue3' => Array (
					'type' => 'radio',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
					'name' => 'field 4'
				),
				'rep_only_true_field_for_testing_rep_only_view_logic' => Array (
					'type' => 'text',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
					'name' => 'Text field 1'
				),
			),
			array(), // all fields are expected to be from the user
			array(), // all fields are expected to be from the user
			array(
				'required_text_from_user_without_default' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'field type text',
				),
				'required_date_from_user_without_default' => array(
					'type' => 'date',
					'required' => true,
					'description' => '',
					'name' => 'field type date',
				),
				'required_time_from_user_without_default' => array(
					'type' => 'time',
					'required' => true,
					'description' => '',
					'name' => 'field type time',
				),
				'required_checkbox_from_user_without_default' => array(
					'type' => 'checkbox',
					'required' => true,
					'description' => '',
					'name' => 'field type checkbox',
				),
				'required_radio_from_user_without_defaultvalue1' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'field type radio',
				),
				'required_radio_from_user_without_defaultvalue2' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'field type radio',
				),
				'required_radio_from_user_without_defaultvalue3' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'field type radio',
				),
				'required_percents_from_user_without_defaultvalue1' => array(
					'type' => 'percents',
					'required' => true,
					'description' => '',
					'name' => 'field type percents',
				),
				'required_percents_from_user_without_defaultvalue2' => array(
					'type' => 'percents',
					'required' => true,
					'description' => '',
					'name' => 'field type percents',
				),
				'required_percents_from_user_without_defaultvalue3' => array(
					'type' => 'percents',
					'required' => true,
					'description' => '',
					'name' => 'field type percents',
				),
				'required_fees_from_user_without_defaultvalue1' => array(
					'type' => 'fees',
					'required' => true,
					'description' => '',
					'name' => 'field type fees',
				),
				'required_fees_from_user_without_defaultvalue2' => array(
					'type' => 'fees',
					'required' => true,
					'description' => '',
					'name' => 'field type fees',
				),
				'required_fees_from_user_without_defaultvalue3' => array(
					'type' => 'fees',
					'required' => true,
					'description' => '',
					'name' => 'field type fees',
				),
				'hr' => array(
					'type' => 'hr',
					'required' => true,
					'description' => '',
					'name' => 'field type hr',
				),
				'required_phoneUS_from_user_without_default' => array(
					'type' => 'phoneUS',
					'required' => true,
					'description' => '',
					'name' => 'field type phoneUS',
				),
				'required_money_from_user_without_default' => array(
					'type' => 'money',
					'required' => true,
					'description' => '',
					'name' => 'field type money',
				),
				'required_percent_from_user_without_default' => array(
					'type' => 'percent',
					'required' => true,
					'description' => '',
					'name' => 'field type percent',
				),
				'required_ssn_from_user_without_default' => array(
					'type' => 'ssn',
					'required' => true,
					'description' => '',
					'name' => 'field type ssn',
				),
				'required_zipcodeUS_from_user_without_default' => array(
					'type' => 'zipcodeUS',
					'required' => true,
					'description' => '',
					'name' => 'field type zip',
				),
				'required_email_from_user_without_default' => array(
					'type' => 'email',
					'required' => true,
					'description' => '',
					'name' => 'field type email',
				),
				'required_url_from_user_without_default' => array(
					'type' => 'url',
					'required' => true,
					'description' => '',
					'name' => 'field type url',
				),
				'required_number_from_user_without_default' => array(
					'type' => 'number',
					'required' => true,
					'description' => '',
					'name' => 'field type number',
				),
				'required_digits_from_user_without_default' => array(
					'type' => 'digits',
					'required' => true,
					'description' => '',
					'name' => 'field type digits',
				),
				'required_select_from_user_without_default' => array(
					'type' => 'select',
					'required' => true,
					'description' => '',
					'name' => 'field type select',
				),
				'required_textArea_from_user_without_default' => array(
					'type' => 'textArea',
					'required' => true,
					'description' => '',
					'name' => 'field type textArea',
				),
				'Referral1Business' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral1Business',
				),
				'Referral1Owner/Officer' => Array (
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral1Owner/Officer',
				),
				'Referral1Phone' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral1Phone',
				),
				'Referral2Business' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral2Business',
				),
				'Referral2Owner/Officer' => Array (
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral2Owner/Officer',
				),
				'Referral2Phone' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral2Phone',
				),
				'Referral3Business' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral3Business',
				),
				'Referral3Owner/Officer' => Array (
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral3Owner/Officer',
				),
				'Referral3Phone' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral3Phone',
				),
				'OwnerType-Corp' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-SoleProp' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-LLC' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-Partnership' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-NonProfit' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-Other' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'Unknown Type for testing' => Array (
					'type' => 'unknown',
					'required' => true,
					'description' => '',
					'name' => 'Unknown Type for testing'
				),
			),
			array(
				 'DBA' => array(
					'type' => 'text',
					'required' => true,
					'name' => 'DBA',
					'description' => 'DBA'
				),
				'EMail' => array(
					'type' => 'email',
					'required' => true,
					'name' => 'EMail',
					'description' => 'EMail'
				),
				'required_text_from_api_without_default' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Text field'
				),
				'required_text_from_api_without_default_source_2' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Text field'
				),
				'required_text_from_user_without_default_repOnly' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Text field'
				),
				'required_text_from_user_without_default_textfield' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Text field'
				),
				'required_text_from_user_without_default_textfield1' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Text field 1'
				),
			), // one field from the user
			array() // all fields are expected to be from the user
		);

		$index = 0;
		foreach ($templates as $key => $template) {
			$actualFields = $this->Template->getTemplateFields($template['Template']['id'], null, null, true);
			$this->assertEquals(
				$expectedFields[$index],
				$actualFields,
				'Template with id [' . $index . '] did not match expected API fields');
			$index = $index + 1;
		}

		$expectedFields = array(
			array(
				'required_text_from_user_without_default' => array(
					'type' => 'text',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
					'name' => 'field 3'
				),
				'required_radio_from_user_without_defaultvalue1' => Array (
					'type' => 'radio',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
					'name' => 'field 4'
				),
				'required_radio_from_user_without_defaultvalue2' => Array (
					'type' => 'radio',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
					'name' => 'field 4'
				),
				'required_radio_from_user_without_defaultvalue3' => Array (
					'type' => 'radio',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
					'name' => 'field 4'
				),
				'rep_only_true_field_for_testing_rep_only_view_logic' => Array (
					'type' => 'text',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
					'name' => 'Text field 1'
				),
			),
			array(), // all fields are expected to be from the user
			array(), // all fields are expected to be from the user
			array(
				'required_text_from_user_without_default' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'field type text'
				),
				'required_date_from_user_without_default' => array(
					'type' => 'date',
					'required' => true,
					'description' => '',
					'name' => 'field type date'
				),
				'required_time_from_user_without_default' => array(
					'type' => 'time',
					'required' => true,
					'description' => '',
					'name' => 'field type time'
				),
				'required_checkbox_from_user_without_default' => array(
					'type' => 'checkbox',
					'required' => true,
					'description' => '',
					'name' => 'field type checkbox'
				),
				'required_radio_from_user_without_defaultvalue1' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'field type radio'
				),
				'required_radio_from_user_without_defaultvalue2' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'field type radio'
				),
				'required_radio_from_user_without_defaultvalue3' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'field type radio'
				),
				'required_percents_from_user_without_defaultvalue1' => array(
					'type' => 'percents',
					'required' => true,
					'description' => '',
					'name' => 'field type percents'
				),
				'required_percents_from_user_without_defaultvalue2' => array(
					'type' => 'percents',
					'required' => true,
					'description' => '',
					'name' => 'field type percents'
				),
				'required_percents_from_user_without_defaultvalue3' => array(
					'type' => 'percents',
					'required' => true,
					'description' => '',
					'name' => 'field type percents'
				),
				'required_fees_from_user_without_defaultvalue1' => array(
					'type' => 'fees',
					'required' => true,
					'description' => '',
					'name' => 'field type fees'
				),
				'required_fees_from_user_without_defaultvalue2' => array(
					'type' => 'fees',
					'required' => true,
					'description' => '',
					'name' => 'field type fees'
				),
				'required_fees_from_user_without_defaultvalue3' => array(
					'type' => 'fees',
					'required' => true,
					'description' => '',
					'name' => 'field type fees'
				),
				'hr' => array(
					'type' => 'hr',
					'required' => true,
					'description' => '',
					'name' => 'field type hr'
				),
				'required_phoneUS_from_user_without_default' => array(
					'type' => 'phoneUS',
					'required' => true,
					'description' => '',
					'name' => 'field type phoneUS'
				),
				'required_money_from_user_without_default' => array(
					'type' => 'money',
					'required' => true,
					'description' => '',
					'name' => 'field type money'
				),
				'required_percent_from_user_without_default' => array(
					'type' => 'percent',
					'required' => true,
					'description' => '',
					'name' => 'field type percent'
				),
				'required_ssn_from_user_without_default' => array(
					'type' => 'ssn',
					'required' => true,
					'description' => '',
					'name' => 'field type ssn'
				),
				'required_zipcodeUS_from_user_without_default' => array(
					'type' => 'zipcodeUS',
					'required' => true,
					'description' => '',
					'name' => 'field type zip'
				),
				'required_email_from_user_without_default' => array(
					'type' => 'email',
					'required' => true,
					'description' => '',
					'name' => 'field type email'
				),
				'required_url_from_user_without_default' => array(
					'type' => 'url',
					'required' => true,
					'description' => '',
					'name' => 'field type url'
				),
				'required_number_from_user_without_default' => array(
					'type' => 'number',
					'required' => true,
					'description' => '',
					'name' => 'field type number'
				),
				'required_digits_from_user_without_default' => array(
					'type' => 'digits',
					'required' => true,
					'description' => '',
					'name' => 'field type digits'
				),
				'required_select_from_user_without_default' => array(
					'type' => 'select',
					'required' => true,
					'description' => '',
					'name' => 'field type select'
				),
				'required_textArea_from_user_without_default' => array(
					'type' => 'textArea',
					'required' => true,
					'description' => '',
					'name' => 'field type textArea'
				),
				'Referral1Business' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral1Business'
				),
				'Referral1Owner/Officer' => Array (
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral1Owner/Officer'
				),
				'Referral1Phone' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral1Phone'
				),
				'Referral2Business' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral2Business'
				),
				'Referral2Owner/Officer' => Array (
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral2Owner/Officer'
				),
				'Referral2Phone' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral2Phone'
				),
				'Referral3Business' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral3Business'
				),
				'Referral3Owner/Officer' => Array (
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral3Owner/Officer'
				),
				'Referral3Phone' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Referral3Phone'
				),
				'OwnerType-Corp' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-SoleProp' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-LLC' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-Partnership' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-NonProfit' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'OwnerType-Other' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
					'name' => 'Owner Type - '
				),
				'Unknown Type for testing' => Array (
					'type' => 'unknown',
					'required' => true,
					'description' => '',
					'name' => 'Unknown Type for testing'
				),
			),
			array(
				'required_text_from_user_without_default_repOnly' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Text field'
				),
				'required_text_from_user_without_default_textfield' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Text field'
				),
				'required_text_from_user_without_default_textfield1' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
					'name' => 'Text field 1'
				),
			), // one field from the user
			array() // all fields are expected to be from the user
		);

		// test source 1 - user only
		$index = 0;
		foreach ($templates as $key => $template) {
			$actualFields = $this->Template->getTemplateFields($template['Template']['id'], 1, null, true);
			$this->assertEquals(
				$expectedFields[$index],
				$actualFields,
				'Template with id [' . $index . '] did not match expected API fields');
			$index = $index + 1;
		}
	}

	public function testBeforeDelete() {
		$expectedTemplate = array(
			'id' => (int)5,
			'name' => 'Template used to test getFields',
			'logo_position' => (int)0,
			'include_brand_logo' => true,
			'description' => '',
			'cobrand_id' => (int)2,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'rightsignature_template_guid' => null,
			'rightsignature_install_template_guid' => null,
			'owner_equity_threshold' => 50,
			'requires_coversheet' => null,
			'email_app_pdf' => null
		);
		$actualTemplate = $this->Template->find(
			'first',
			array(
				'conditions' => array('Template.id' => $expectedTemplate['id']),
				'order' => 'Template.id',
			)
		);
		$this->assertEquals($expectedTemplate, $actualTemplate['Template'], 'Expected to find a template with id of 5');

		// make sure that no users are still referencing this template
		$users = $this->User->find(
			'all',
			array(
				'conditions' => array(
					'template_id' => $expectedTemplate['id']
				),
				'recursive' => -1
			)
		);
		foreach ($users as $key => $user) {
			$user['OnlineappUser']['template_id'] = null;
			$this->User->save($user);
		}

		$this->Template->delete($expectedTemplate['id'], true);

		// look it up again and verify it is missing
		$actualTemplate = $this->Template->find(
			'first',
			array(
				'conditions' => array('Template.id' => $expectedTemplate['id']),
				'order' => 'Template.id',
			)
		);
		$this->assertEquals(array(), $actualTemplate, 'Template with id of 5 should not be in the database');
	}

/**
 * testGetTemplatesAndCobrands
 *
 * @return void
 */
	public function testGetTemplatesAndCobrands() {
		$conditions['conditions'] = array('Template.id' => 1);
		$expected = array(
			array(
				'Template' => array(
						'id' => 1,
						'name' => 'Template 1 for PN1'
				),
				'Cobrand' => array(
						'partner_name' => 'Partner Name 1',
						'id' => 1
				)
			)
		);
		$actual = $this->Template->getTemplatesAndCobrands($conditions);
		$this->assertSame($expected, $actual);
	}

/**
 * testSetCobrandsTemplatesList
 *
 * @return void
 */
	public function testSetCobrandsTemplatesList() {
		$tstData = array(
				array(
					'Template' => array(
						'id' => 1,
						'name' => 'Template Name'
					),
					'Cobrand' => array(
						'partner_name' => 'Partner Name'
					)
				)
			);
		$expected = array(1 => 'Partner Name - Template Name');
		$actual = $this->Template->setCobrandsTemplatesList($tstData);
		$this->assertEquals($expected, $actual);
	}
}
