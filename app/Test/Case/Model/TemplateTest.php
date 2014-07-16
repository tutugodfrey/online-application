<?php
App::uses('Cobrand', 'Model');
App::uses('Template', 'Model');

class TemplateTest extends CakeTestCase {

	public $dropTables = false;

	public $autoFixtures = false;

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
					'name' => 'Template used to test getFields',
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

		unset($this->CobrandedApplicationValue);
		unset($this->CobrandedApplication);
		unset($this->TemplateField);
		unset($this->TemplateSection);
		unset($this->TemplatePage);
		unset($this->Template);
		unset($this->Cobrand);
		unset($this->User);
		parent::tearDown();
	}

	public function testGetList() {
		$expected = array();
		$expected[1] = 'Template 1 for PN1';
		$expected[2] = 'Template 2 for PN1';

		$result = $this->Template->getList(1);

		$this->assertEquals($expected, $result);
	}

	public function testGetCobrand() {
		$expected_cobrand = array(
			'Cobrand' => array (
				'id' => 1,
				'partner_name' => 'Partner Name 1',
				'partner_name_short' => 'PN1',
				'description' => 'Cobrand "Partner Name 1" description goes here.',
				'created' => '2007-03-18 10:41:31',
				'modified' => '2007-03-18 10:41:31',
				'logo_url' => 'PN1 logo_url',
			),
		);
		$returned_cobrand = $this->Template->getCobrand(1);
		$this->assertEquals($expected_cobrand, $returned_cobrand);

		$expected_cobrand = array(
			'Cobrand' => array (
				'id' => 2,
				'partner_name' => 'Partner Name 2',
				'partner_name_short' => 'PN2',
				'description' => 'Cobrand "Partner Name 2" description goes here.',
				'created' => '2007-03-18 10:41:31',
				'modified' => '2007-03-18 10:41:31',
				'logo_url' => 'PN2 logo_url',
			),
		);
		$returned_cobrand = $this->Template->getCobrand(2);
		$this->assertEquals($expected_cobrand, $returned_cobrand);

		$expected_cobrand = array(
			'Cobrand' => array (
				'id' => 3,
				'partner_name' => 'Partner Name 3',
				'partner_name_short' => 'PN3',
				'description' => 'Cobrand "Partner Name 3" description goes here.',
				'created' => '2007-03-18 10:41:31',
				'modified' => '2007-03-18 10:41:31',
				'logo_url' => 'PN3 logo_url',
			),
		);
		$returned_cobrand = $this->Template->getCobrand(3);
		$this->assertEquals($expected_cobrand, $returned_cobrand);
	}

	public function testValidation() {
		$expected_validationErrors = array(
			'name' => array('Template name cannot be empty'),
			'logo_position' => array('Logo position value not selected'),
		);

		$this->Template->create(array('name' => '', 'logo_position' => ''));
		$this->assertFalse($this->Template->validates());
		$this->assertEquals($expected_validationErrors, $this->Template->validationErrors);

		// test non-numeric cobrand_id
		$expected_validationErrors = array(
			'cobrand_id' => array('Invalid cobrand_id value used'),
			'logo_position' => array('Logo position value not selected'),
		);
		$this->Template->create(array('name' => 'template name', 'cobrand_id' => 'abcd', 'logo_position' => ''));
		$this->assertFalse($this->Template->validates());
		$this->assertEquals($expected_validationErrors, $this->Template->validationErrors);

		// test the go right path
		$expected_validationErrors = array();
		$newTemplateData = array('name' => 'template name', 'cobrand_id' => 1, 'logo_position' => 0);
		$this->Template->create($newTemplateData);
		$this->Template->save($newTemplateData);
		$this->assertTrue($this->Template->validates());
		$this->assertEquals($expected_validationErrors, $this->Template->validationErrors);

		$createdTemplate = $this->Template->read();
		$this->assertEquals(1, count($createdTemplate['TemplatePages']), "we should have a new template with a 'Validate Application' page");
		$createdTemplatePage = $createdTemplate['TemplatePages'][0];
		$this->TemplatePage->id = $createdTemplatePage['id'];
		$createdTemplatePage = $this->TemplatePage->read();
		$this->assertEquals(4, count($createdTemplatePage['TemplateSections']), "The new page should have some sections");

		// deleting the template will delete the associated children (sections and fields)
		$this->TemplatePage->delete($createdTemplatePage['TemplatePage']['id']);
		$createdTemplatePage = $this->TemplatePage->read();
		$this->assertEquals(0, count($createdTemplatePage['TemplateSections']), "The new page should have no sections");
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
				),
				'required_text_from_api_without_default_source_2' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
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
				),
				'OwnerType-SoleProp' => array(
					'type' => 'radio',
					'required' => false,
					'description' => '',
				),
				'OwnerType-LLC' => array(
					'type' => 'radio',
					'required' => false,
					'description' => '',
				),
				'OwnerType-Partnership' => array(
					'type' => 'radio',
					'required' => false,
					'description' => '',
				),
				'OwnerType-NonProfit' => array(
					'type' => 'radio',
					'required' => false,
					'description' => '',
				),
				'OwnerType-Other' => array(
					'type' => 'radio',
					'required' => false,
					'description' => '',
				),
			), // one field from the user
		);
		$index = 0;
		foreach ($templates as $key => $template) {
			$actualFields = $this->Template->getTemplateApiFields($template['Template']['id']);
			$this->assertEquals(
				$expectedFields[$index],
				$actualFields,
				'Template with id ['.$index.'] did not match expected API fields');
			$index = $index + 1;
		}

		$expectedFields = array(
			array(
				'required_text_from_user_without_default' => array(
					'type' => 'text',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
				),
				'required_radio_from_user_without_defaultvalue1' => Array (
					'type' => 'radio',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
				),
				'required_radio_from_user_without_defaultvalue2' => Array (
					'type' => 'radio',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
				),
				'required_radio_from_user_without_defaultvalue3' => Array (
					'type' => 'radio',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
				),
				'rep_only_true_field_for_testing_rep_only_view_logic' => Array (
					'type' => 'text',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
				),
			),
			array(), // all fields are expected to be from the user
			array(), // all fields are expected to be from the user
			array(
				'required_text_from_user_without_default' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'required_date_from_user_without_default' => array(
					'type' => 'date',
					'required' => true,
					'description' => '',
				),
				'required_time_from_user_without_default' => array(
					'type' => 'time',
					'required' => true,
					'description' => '',
				),
				'required_checkbox_from_user_without_default' => array(
					'type' => 'checkbox',
					'required' => true,
					'description' => '',
				),
				'required_radio_from_user_without_defaultvalue1' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'required_radio_from_user_without_defaultvalue2' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'required_radio_from_user_without_defaultvalue3' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'required_percents_from_user_without_defaultvalue1' => array(
					'type' => 'percents',
					'required' => true,
					'description' => '',
				),
				'required_percents_from_user_without_defaultvalue2' => array(
					'type' => 'percents',
					'required' => true,
					'description' => '',
				),
				'required_percents_from_user_without_defaultvalue3' => array(
					'type' => 'percents',
					'required' => true,
					'description' => '',
				),
				'label' => array(
					'type' => 'label',
					'required' => true,
					'description' => '',
				),
				'required_fees_from_user_without_defaultvalue1' => array(
					'type' => 'fees',
					'required' => true,
					'description' => '',
				),
				'required_fees_from_user_without_defaultvalue2' => array(
					'type' => 'fees',
					'required' => true,
					'description' => '',
				),
				'required_fees_from_user_without_defaultvalue3' => array(
					'type' => 'fees',
					'required' => true,
					'description' => '',
				),
				'hr' => array(
					'type' => 'hr',
					'required' => true,
					'description' => '',
				),
				'required_phoneUS_from_user_without_default' => array(
					'type' => 'phoneUS',
					'required' => true,
					'description' => '',
				),
				'required_money_from_user_without_default' => array(
					'type' => 'money',
					'required' => true,
					'description' => '',
				),
				'required_percent_from_user_without_default' => array(
					'type' => 'percent',
					'required' => true,
					'description' => '',
				),
				'required_ssn_from_user_without_default' => array(
					'type' => 'ssn',
					'required' => true,
					'description' => '',
				),
				'required_zipcodeUS_from_user_without_default' => array(
					'type' => 'zipcodeUS',
					'required' => true,
					'description' => '',
				),
				'required_email_from_user_without_default' => array(
					'type' => 'email',
					'required' => true,
					'description' => '',
				),
				'required_url_from_user_without_default' => array(
					'type' => 'url',
					'required' => true,
					'description' => '',
				),
				'required_number_from_user_without_default' => array(
					'type' => 'number',
					'required' => true,
					'description' => '',
				),
				'required_digits_from_user_without_default' => array(
					'type' => 'digits',
					'required' => true,
					'description' => '',
				),
				'required_select_from_user_without_default' => array(
					'type' => 'select',
					'required' => true,
					'description' => '',
				),
				'required_textArea_from_user_without_default' => array(
					'type' => 'textArea',
					'required' => true,
					'description' => '',
				),
				'Referral1Business' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral1Owner/Officer' => Array (
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral1Phone' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral2Business' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral2Owner/Officer' => Array (
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral2Phone' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral3Business' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral3Owner/Officer' => Array (
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral3Phone' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'OwnerType-Corp' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'OwnerType-SoleProp' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'OwnerType-LLC' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'OwnerType-Partnership' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'OwnerType-NonProfit' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'OwnerType-Other' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'Unknown Type for testing' => Array (
					'type' => 'unknown',
					'required' => true,
					'description' => '',
				),
			),
			array(
				'required_text_from_api_without_default' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'required_text_from_api_without_default_source_2' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'required_text_from_user_without_default_repOnly' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'required_text_from_user_without_default_textfield' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'required_text_from_user_without_default_textfield1' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
			), // one field from the user
		);

		$index = 0;
		foreach ($templates as $key => $template) {
			$actualFields = $this->Template->getTemplateFields($template['Template']['id'], null, null, true);
			$this->assertEquals(
				$expectedFields[$index],
				$actualFields,
				'Template with id ['.$index.'] did not match expected API fields');
			$index = $index + 1;
		}

		$expectedFields = array(
			array(
				'required_text_from_user_without_default' => array(
					'type' => 'text',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
				),
				'required_radio_from_user_without_defaultvalue1' => Array (
					'type' => 'radio',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
				),
				'required_radio_from_user_without_defaultvalue2' => Array (
					'type' => 'radio',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
				),
				'required_radio_from_user_without_defaultvalue3' => Array (
					'type' => 'radio',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
				),
				'rep_only_true_field_for_testing_rep_only_view_logic' => Array (
					'type' => 'text',
					'required' => true,
					'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
				),
			),
			array(), // all fields are expected to be from the user
			array(), // all fields are expected to be from the user
			array(
				'required_text_from_user_without_default' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'required_date_from_user_without_default' => array(
					'type' => 'date',
					'required' => true,
					'description' => '',
				),
				'required_time_from_user_without_default' => array(
					'type' => 'time',
					'required' => true,
					'description' => '',
				),
				'required_checkbox_from_user_without_default' => array(
					'type' => 'checkbox',
					'required' => true,
					'description' => '',
				),
				'required_radio_from_user_without_defaultvalue1' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'required_radio_from_user_without_defaultvalue2' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'required_radio_from_user_without_defaultvalue3' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'required_percents_from_user_without_defaultvalue1' => array(
					'type' => 'percents',
					'required' => true,
					'description' => '',
				),
				'required_percents_from_user_without_defaultvalue2' => array(
					'type' => 'percents',
					'required' => true,
					'description' => '',
				),
				'required_percents_from_user_without_defaultvalue3' => array(
					'type' => 'percents',
					'required' => true,
					'description' => '',
				),
				'label' => array(
					'type' => 'label',
					'required' => true,
					'description' => '',
				),
				'required_fees_from_user_without_defaultvalue1' => array(
					'type' => 'fees',
					'required' => true,
					'description' => '',
				),
				'required_fees_from_user_without_defaultvalue2' => array(
					'type' => 'fees',
					'required' => true,
					'description' => '',
				),
				'required_fees_from_user_without_defaultvalue3' => array(
					'type' => 'fees',
					'required' => true,
					'description' => '',
				),
				'hr' => array(
					'type' => 'hr',
					'required' => true,
					'description' => '',
				),
				'required_phoneUS_from_user_without_default' => array(
					'type' => 'phoneUS',
					'required' => true,
					'description' => '',
				),
				'required_money_from_user_without_default' => array(
					'type' => 'money',
					'required' => true,
					'description' => '',
				),
				'required_percent_from_user_without_default' => array(
					'type' => 'percent',
					'required' => true,
					'description' => '',
				),
				'required_ssn_from_user_without_default' => array(
					'type' => 'ssn',
					'required' => true,
					'description' => '',
				),
				'required_zipcodeUS_from_user_without_default' => array(
					'type' => 'zipcodeUS',
					'required' => true,
					'description' => '',
				),
				'required_email_from_user_without_default' => array(
					'type' => 'email',
					'required' => true,
					'description' => '',
				),
				'required_url_from_user_without_default' => array(
					'type' => 'url',
					'required' => true,
					'description' => '',
				),
				'required_number_from_user_without_default' => array(
					'type' => 'number',
					'required' => true,
					'description' => '',
				),
				'required_digits_from_user_without_default' => array(
					'type' => 'digits',
					'required' => true,
					'description' => '',
				),
				'required_select_from_user_without_default' => array(
					'type' => 'select',
					'required' => true,
					'description' => '',
				),
				'required_textArea_from_user_without_default' => array(
					'type' => 'textArea',
					'required' => true,
					'description' => '',
				),
				'Referral1Business' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral1Owner/Officer' => Array (
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral1Phone' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral2Business' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral2Owner/Officer' => Array (
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral2Phone' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral3Business' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral3Owner/Officer' => Array (
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'Referral3Phone' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'OwnerType-Corp' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'OwnerType-SoleProp' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'OwnerType-LLC' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'OwnerType-Partnership' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'OwnerType-NonProfit' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'OwnerType-Other' => array(
					'type' => 'radio',
					'required' => true,
					'description' => '',
				),
				'Unknown Type for testing' => Array (
					'type' => 'unknown',
					'required' => true,
					'description' => '',
				),
			),
			array(
				'required_text_from_user_without_default_repOnly' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'required_text_from_user_without_default_textfield' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
				'required_text_from_user_without_default_textfield1' => array(
					'type' => 'text',
					'required' => true,
					'description' => '',
				),
			), // one field from the user
		);

		// test source 1 - user only
		$index = 0;
		foreach ($templates as $key => $template) {
			$actualFields = $this->Template->getTemplateFields($template['Template']['id'], 1, null, true);
			$this->assertEquals(
				$expectedFields[$index],
				$actualFields,
				'Template with id ['.$index.'] did not match expected API fields');
			$index = $index + 1;
		}
	}

	public function testBeforeDelete() {
		$expectedTemplate = array(
			'id' => (int) 5,
			'name' => 'Template used to test getFields',
			'logo_position' => (int) 0,
			'include_axia_logo' => true,
			'description' => '',
			'cobrand_id' => (int) 2,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'rightsignature_template_guid' => '',
			'rightsignature_install_template_guid' => '',
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

}
