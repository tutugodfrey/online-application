<?php
App::uses('CobrandedApplication', 'Model');

/**
 * CobrandedApplication Test Case
 *
 */
class CobrandedApplicationTest extends CakeTestCase {

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
		'app.onlineappCobrandedApplication',
		'app.onlineappCobrandedApplicationValue',
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
				'email' => 'compact(varname)',
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

	public function testValidation() {
		// create a new appliction
		// only validation currently in place is for the uuid
		$expectedValidationErrors = array(
			'uuid' => array('Invalid UUID'),
			'user_id' => array('This field cannot be left blank'),
			'template_id' => array('This field cannot be left blank'),
		);
		$this->CobrandedApplication->create(array('uuid' => ''));
		$this->assertFalse($this->CobrandedApplication->validates());
		$this->assertEquals(
			$expectedValidationErrors,
			$this->CobrandedApplication->validationErrors,
			'testing expected validation errors for empty uuid'
		);

		// testing go right path
		$expectedValidationErrors = array();
		$this->CobrandedApplication->create(
			array(
				'uuid' => String::uuid(),
				'user_id' => 1,
				'template_id' => 1,
			)
		);

		$this->assertTrue($this->CobrandedApplication->validates());
		$this->assertEquals(
			$expectedValidationErrors,
			$this->CobrandedApplication->validationErrors,
			'testing no validation errors for valid uuid'
		);
	}

	public function testGetTemplateAndAssociatedValues() {
		// expected was built via fixtures
		$expected = array(
			'CobrandedApplication' => array(
				'id' => (int) 1,
				'user_id' => (int) 1,
				'template_id' => (int) 1,
				'uuid' => 'b118ac22d3cd4ab49148b05d5254ed59',
				'created' => '2014-01-24 09:07:08',
				'modified' => '2014-01-24 09:07:08',
				'rightsignature_document_guid' => null,
				'status' => null,
				'rightsignature_install_document_guid' => null,
				'rightsignature_install_status' => null,
			),
			'Template' => array(
				'id' => (int) 1,
				'name' => 'Template 1 for PN1',
				'logo_position' => (int) 0,
				'include_axia_logo' => true,
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'cobrand_id' => (int) 1,
				'created' => '2007-03-18 10:41:31',
				'modified' => '2007-03-18 10:41:31',
				'rightsignature_template_guid' => null,
				'rightsignature_install_template_guid' => null,
				'Cobrand' => array(
					'id' => (int) 1,
					'partner_name' => 'Partner Name 1',
					'partner_name_short' => 'PN1',
					'logo_url' => 'PN1 logo_url',
					'description' => 'Cobrand "Partner Name 1" description goes here.',
					'created' => '2007-03-18 10:41:31',
					'modified' => '2007-03-18 10:41:31',
					'response_url_type' => null
				),
				'TemplatePages' => array(
					(int) 0 => array(
						'id' => (int) 1,
						'name' => 'Page 1',
						'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
						'rep_only' => false,
						'template_id' => (int) 1,
						'order' => (int) 0,
						'created' => '2013-12-18 09:26:45',
						'modified' => '2013-12-18 09:26:45',
						'TemplateSections' => array(
							(int) 0 => array(
								'id' => (int) 1,
								'name' => 'Page Section 1',
								'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
								'rep_only' => false,
								'width' => (int) 12,
								'page_id' => (int) 1,
								'order' => (int) 0,
								'created' => '2013-12-18 13:36:11',
								'modified' => '2013-12-18 13:36:11',
								'TemplateFields' => array(
									(int) 0 => array(
										'id' => (int) 1,
										'name' => 'field 1',
										'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
										'rep_only' => false,
										'width' => (int) 12,
										'type' => (int) 1,
										'required' => true,
										'source' => (int) 1,
										'default_value' => '',
										'merge_field_name' => 'required_text_from_user_without_default',
										'order' => (int) 0,
										'section_id' => (int) 1,
										'encrypt' => false,
										'created' => '2013-12-18 14:10:17',
										'modified' => '2013-12-18 14:10:17',
										'CobrandedApplicationValues' => array(
											(int) 0 => array(
												'id' => (int) 1,
												'cobranded_application_id' => (int) 1,
												'template_field_id' => (int) 1,
												'name' => 'Field 1',
												'value' => null,
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15'
											)
										)
									),
									(int) 1 => array(
										'id' => (int) 2,
										'name' => 'field 2',
										'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
										'rep_only' => false,
										'width' => (int) 12,
										'type' => (int) 0,
										'required' => true,
										'source' => (int) 1,
										'default_value' => '',
										'merge_field_name' => 'required_text_from_user_without_default',
										'order' => (int) 1,
										'section_id' => (int) 1,
										'encrypt' => true,
										'created' => '2013-12-18 14:10:17',
										'modified' => '2013-12-18 14:10:17',
										'CobrandedApplicationValues' => array(
											(int) 0 => array(
												'id' => (int) 5,
												'cobranded_application_id' => (int) 1,
												'template_field_id' => (int) 2,
												'name' => 'Encrypt1',
												'value' => null,
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15'
											)
										)
									),
									(int) 2 => array(
										'id' => (int) 3,
										'name' => 'field 3',
										'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
										'rep_only' => false,
										'width' => (int) 12,
										'type' => (int) 0,
										'required' => true,
										'source' => (int) 1,
										'default_value' => '',
										'merge_field_name' => 'required_text_from_user_without_default',
										'order' => (int) 2,
										'section_id' => (int) 1,
										'encrypt' => false,
										'created' => '2013-12-18 14:10:17',
										'modified' => '2013-12-18 14:10:17',
										'CobrandedApplicationValues' => array()
									),
									(int) 3 => array(
										'id' => 4,
										'name' => 'field 4',
										'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
										'rep_only' => false,
										'width' => 12,
										'type' => 4,
										'required' => true,
										'source' => 1,
										'default_value' => 'name1::value1,name2::value2,name3::value3',
										'merge_field_name' => 'required_radio_from_user_without_default',
										'order' => 3,
										'section_id' => 1,
										'encrypt' => false,
										'created' => '2013-12-18 14:10:17',
										'modified' => '2013-12-18 14:10:17',
										'CobrandedApplicationValues' => array(
											array(
												'id' => 2,
												'cobranded_application_id' => 1,
												'template_field_id' => 4,
												'name' => 'name1',
												'value' => null,
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15',
											),
											array(
												'id' => 3,
												'cobranded_application_id' => 1,
												'template_field_id' => 4,
												'name' => 'name2',
												'value' => null,
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15',
											),
											array(
												'id' => 4,
												'cobranded_application_id' => 1,
												'template_field_id' => 4,
												'name' => 'name3',
												'value' => null,
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15',
											),
											array(
												'id' => 8,
												'cobranded_application_id' => 1,
												'template_field_id' => 4,
												'name' => 'Owner1Name',
												'value' => 'Owner1NameTest',
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15'
											),
										),
									),
								)
							),
							(int) 1 => array(
								'id' => (int) 2,
								'name' => 'Page Section 2',
								'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
								'rep_only' => false,
								'width' => (int) 12,
								'page_id' => (int) 1,
								'order' => (int) 1,
								'created' => '2013-12-18 13:36:11',
								'modified' => '2013-12-18 13:36:11',
								'TemplateFields' => array()
							),
							(int) 2 => array(
								'id' => (int) 3,
								'name' => 'Page Section 2',
								'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
								'rep_only' => false,
								'width' => (int) 12,
								'page_id' => (int) 1,
								'order' => (int) 2,
								'created' => '2013-12-18 13:36:11',
								'modified' => '2013-12-18 13:36:11',
								'TemplateFields' => array()
							)
						)
					),
					(int) 1 => array(
						'id' => (int) 2,
						'name' => 'Page 2',
						'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
						'rep_only' => false,
						'template_id' => (int) 1,
						'order' => (int) 1,
						'created' => '2013-12-18 09:26:45',
						'modified' => '2013-12-18 09:26:45',
						'TemplateSections' => array()
					),
					(int) 2 => array(
						'id' => (int) 3,
						'name' => 'Page 3',
						'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
						'rep_only' => false,
						'template_id' => (int) 1,
						'order' => (int) 2,
						'created' => '2013-12-18 09:26:45',
						'modified' => '2013-12-18 09:26:45',
						'TemplateSections' => array()
					)
				),
			)
		);

		// We need an application
		$actual = $this->CobrandedApplication->getTemplateAndAssociatedValues(1);
		$this->assertEquals($expected, $actual, 'getTemplateAndAssociatedValues test failed');

		// we should get rep_only pages, sections, and fields, if we're logged in
		$expected['Template']['TemplatePages'][3] = array(
			'id' => (int) 6,
			'name' => 'Page 4',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'rep_only' => true,
			'template_id' => (int) 1,
			'order' => (int) 3,
			'created' => '2013-12-18 09:26:45',
			'modified' => '2013-12-18 09:26:45',
			'TemplateSections' => array(
				(int) 0 => array(
					'id' => (int) 6,
					'name' => 'Page Section 1',
					'description' => '',
					'rep_only' => true,
					'width' => (int) 12,
					'page_id' => (int) 6,
					'order' => (int) 0,
					'created' => '2013-12-18 13:36:11',
					'modified' => '2013-12-18 13:36:11',
					'TemplateFields' => array(
						(int) 0 => array(
							'id' => (int) 43,
							'name' => 'Text field 1',
							'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
							'rep_only' => true,
							'width' => (int) 12,
							'type' => (int) 0,
							'required' => true,
							'source' => (int) 1,
							'default_value' => '',
							'merge_field_name' => 'rep_only_true_field_for_testing_rep_only_view_logic',
							'order' => (int) 0,
							'section_id' => (int) 6,
							'encrypt' => false,
							'created' => '2013-12-18 14:10:17',
							'modified' => '2013-12-18 14:10:17',
							'CobrandedApplicationValues' => array()
						)
					)
				)
			)
		);

		// We need an application... also pass in user id - mimicks logged in user
		$actual = $this->CobrandedApplication->getTemplateAndAssociatedValues(1, 1);
		$this->assertEquals($expected, $actual, 'getTemplateAndAssociatedValues test failed when using logged in user');
	}

	public function testSaveApplicationValue() {
		// TODO: handle application value not found

		// CUT (class under test) expects
		$data = array(
			'id' => 1,
			'value' => null
		);

		// pre-check - verify the value exists and is what we expect
		$expected = array(
			'CobrandedApplicationValue' => array(
				'id' => 1,
				'cobranded_application_id' => 1,
				'template_field_id' => 1,
				'name' => 'Field 1',
				'value' => null,
				'created' => '2014-01-23 17:28:15',
				'modified' => '2014-01-23 17:28:15',
			),
			'CobrandedApplication' => array(
				'id' => 1,
				'user_id' => 1,
				'template_id' => 1,
				'uuid' => 'b118ac22d3cd4ab49148b05d5254ed59',
				'created' => '2014-01-24 09:07:08',
				'modified' => '2014-01-24 09:07:08',
				'rightsignature_document_guid' => null,
				'status' => null,
				'rightsignature_install_document_guid' => null,
				'rightsignature_install_status' => null,
			),
			'TemplateField' => array(
				'id' => 1,
				'name' => 'field 1',
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'rep_only' => false,
				'width' => 12,
				'type' => 1,
				'required' => true,
				'source' => 1,
				'default_value' => '',
				'merge_field_name' => 'required_text_from_user_without_default',
				'order' => 0,
				'section_id' => 1,
				'encrypt' => false,
				'created' => '2013-12-18 14:10:17',
				'modified' => '2013-12-18 14:10:17',
			),
		);
		$actual = $this->CobrandedApplication->getApplicationValue(1);
		$this->assertEquals($expected, $actual, 'expected to find application value with id of 1...');

		// change the value
		$expectedNewValue = '2014-01-01';
		$inputData = array('id' => 1, 'value' => '2014-01-01');
		$response = $this->CobrandedApplication->saveApplicationValue($inputData);

		// tests for the other types are performed by testBuildExportData
		// we should also test the other invalid types...
		$this->assertTrue($response['success'], 'Expected save operation to return true');
		$actual = $this->CobrandedApplication->getApplicationValue(1);
		$this->assertEquals($expectedNewValue, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');

		// save again witht the same data
		$response = $this->CobrandedApplication->saveApplicationValue($inputData);
		$this->assertFalse($response['success'], 'Expected save operation to return true');

		// also make sure the actual value is still newValue
		$actual = $this->CobrandedApplication->getApplicationValue(1);
		$this->assertEquals($expectedNewValue, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');

		// next save a app value with template type of 4 (radio)
		$expected = array(
			'CobrandedApplicationValue' => array(
				'id' => 2,
				'cobranded_application_id' => 1,
				'template_field_id' => 4,
				'name' => 'name1',
				'value' => null,
				'created' => '2014-01-23 17:28:15',
				'modified' => '2014-01-23 17:28:15',
			),
			'CobrandedApplication' => array(
				'id' => 1,
				'user_id' => 1,
				'template_id' => 1,
				'uuid' => 'b118ac22d3cd4ab49148b05d5254ed59',
				'created' => '2014-01-24 09:07:08',
				'modified' => '2014-01-24 09:07:08',
				'rightsignature_document_guid' => null,
				'status' => null,
				'rightsignature_install_document_guid' => null,
				'rightsignature_install_status' => null,
			),
			'TemplateField' => array(
				'id' => 4,
				'name' => 'field 4',
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'rep_only' => false,
				'width' => 12,
				'type' => 4,
				'required' => true,
				'source' => 1,
				'default_value' => 'name1::value1,name2::value2,name3::value3',
				'merge_field_name' => 'required_radio_from_user_without_default',
				'order' => 3,
				'section_id' => 1,
				'encrypt' => false,
				'created' => '2013-12-18 14:10:17',
				'modified' => '2013-12-18 14:10:17',
			),
		);
		$actual = $this->CobrandedApplication->getApplicationValue(2);
		$this->assertEquals($expected, $actual, 'expected to find application value with id of 4...');

		$expectedNewValue = true;
		$inputData = array('id' => 2, 'value' => true);
		$response = $this->CobrandedApplication->saveApplicationValue($inputData);

		$this->assertTrue($response['success'], 'Expected save operation to return true');
		$actual = $this->CobrandedApplication->getApplicationValue(2);
		$this->assertEquals($expectedNewValue, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');

		// now update id 3
		$inputData = array('id' => 3, 'value' => true);
		$response = $this->CobrandedApplication->saveApplicationValue($inputData);

		$this->assertTrue($response['success'], 'Expected save operation to return true');
		$actual = $this->CobrandedApplication->getApplicationValue(3);
		$this->assertEquals($expectedNewValue, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');

		// and id 2 should have a value of  null
		$actual = $this->CobrandedApplication->getApplicationValue(2);
		$this->assertEquals(false, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');
		// and id 4 should have a value of  null
		$actual = $this->CobrandedApplication->getApplicationValue(4);
		$this->assertEquals(false, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');
	}

	public function testBuildExportData() {
		// create a new application from template with id 4
		// or find the template with a name = 'Template used to test afterSave of app values'
		$applictionData = array(
			'user_id' => $this->__user['OnlineappUser']['id'],
			'template_id' => $this->__template['Template']['id'],
			'uuid' => String::uuid(),
		);
		$this->CobrandedApplication->create($applictionData);
		$cobrandedApplication = $this->CobrandedApplication->save();

		// export a empty application
		$expectedKeys = 
			'"MID",'.
			'"required_text_from_user_without_default",'.
			'"required_date_from_user_without_default",'.
			'"required_time_from_user_without_default",'.
			'"required_checkbox_from_user_without_default",'.
			'"required_radio_from_user_without_defaultvalue1",'.
			'"required_radio_from_user_without_defaultvalue2",'.
			'"required_radio_from_user_without_defaultvalue3",'.
			'"required_percents_from_user_without_defaultvalue1",'.
			'"required_percents_from_user_without_defaultvalue2",'.
			'"required_percents_from_user_without_defaultvalue3",'.
			'"required_fees_from_user_without_defaultvalue1",'.
			'"required_fees_from_user_without_defaultvalue2",'.
			'"required_fees_from_user_without_defaultvalue3",'.
			'"required_phoneUS_from_user_without_default",'.
			'"required_money_from_user_without_default",'.
			'"required_percent_from_user_without_default",'.
			'"required_ssn_from_user_without_default",'.
			'"required_zipcodeUS_from_user_without_default",'.
			'"required_email_from_user_without_default",'.
			'"required_url_from_user_without_default",'.
			'"required_number_from_user_without_default",'.
			'"required_digits_from_user_without_default",'.
			'"required_select_from_user_without_default",'.
			'"required_textArea_from_user_without_default",'.
			'"Referral1",'.
			'"Referral2",'.
			'"Referral3",'.
			'"OwnerType-Corp",'.
			'"OwnerType-SoleProp",'.
			'"OwnerType-LLC",'.
			'"OwnerType-Partnership",'.
			'"OwnerType-NonProfit",'.
			'"OwnerType-Other",'.
			'"Unknown Type for testing",'.
			'"oaID",'.
			'"api",'.
			'"aggregated"';
		$expectedValues = 
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"Off",'.
			'"Off",'.
			'"Off",'.
			'"Off",'.
			'"Off",'.
			'"Off",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"",'.
			'"Referral3",'.
			'"Off",'.
			'"Off",'.
			'"Off",'.
			'"Off",'.
			'"Off",'.
			'"Off",'.
			'"",'.
			'"2",'.
			'"",'.
			'""';

		$actualKeys = '';
		$actualValues = '';
		$this->CobrandedApplication->buildExportData($this->CobrandedApplication->id, $actualKeys, $actualValues);
		$this->assertEquals($expectedKeys, $actualKeys, 'Empty application keys were not what we expected');
		$this->assertEquals($expectedValues, $actualValues, 'Empty application values were not what we expected');

		// now insert values into the fields
		$app = $this->CobrandedApplication->read();

		$this->__setSomeValuesBasedOnType($app);

		$expectedValues = 
			'"",'.
			'"text",'.
			'"2000-01-01",'.
			'"08:00 pm",'.
			'"true",'.
			'"On",'.
			'"On",'.
			'"On",'.
			'"Off",'.
			'"Off",'.
			'"Off",'.
			'"10.00",'.
			'"10.00",'.
			'"10.00",'.
			'"8005551234",'.
			'"10.00",'.
			'"50",'.
			'"123-45-6789",'.
			'"12345-1234",'.
			'"name@domain.com",'.
			//'"10 months",'.
			//'"4111-1111-1111-1111",'.
			'"http://www.domain.com",'.
			'"12.82234",'.
			'"1234567890",'.
			'"true",'.
			'"a whole lot of text can go into this field...",'.
			'"text text text",'.
			'"text text text",'.
			'"text text text",'.
			'"Yes",'.
			'"Yes",'.
			'"Yes",'.
			'"Yes",'.
			'"Yes",'.
			'"Yes",'.
			'"",'.
			'"2",'.
			'"",'.
			'""';

		$actualKeys = '';
		$actualValues = '';
		$this->CobrandedApplication->buildExportData($this->CobrandedApplication->id, $actualKeys, $actualValues);

		// the keys should not have changed
		$this->assertEquals($expectedKeys, $actualKeys, 'Filled out application keys were not what we expected');
		$this->assertEquals($expectedValues, $actualValues, 'Filled out application values were not what we expected');
	}

	public function testSetFields() {
		// knowns:
		//     - user
		//     - fieldsData
		// pre-test:
		//     - should not be cobrandedApplications for this user
		$applications = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array('CobrandedApplication.user_id' => $this->__user['OnlineappUser']['id']),
			)
		);
		$this->assertEquals(0, count($applications), 'Did not expect to find any applications for user with id ['.$this->__user['OnlineappUser']['id'].']');

		// set expected results
		$expectedValidationErrors = array(
			'required_text_from_api_without_default' => 'required',
			'Text field' => 'required',
			'Text field 1' => 'required'
		);

		// set knowns
		$user = $this->__user['OnlineappUser'];
		// update the template_id to be 5
		$user['template_id'] = 5;

		// first pass, use invalid fieldsData
		$fieldsData = array(
			'required_text_from_api_without_default' => '' // this guy is required
		);

		// execute the method under test
		$actualResponse = $this->CobrandedApplication->saveFields($user, $fieldsData);

		// assertions
		$this->assertFalse($actualResponse['success'], 'saveFields with empty value for required field should fail');
		$this->assertEquals($expectedValidationErrors, $actualResponse['validationErrors'], 'Expected validation errors did not match');
		$applications = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array('CobrandedApplication.user_id' => $this->__user['OnlineappUser']['id']),
			)
		);
		$this->assertEquals(0, count($applications), 'Expect to find no applications for user with id ['.$this->__user['OnlineappUser']['id'].']');

		// this time use good data
		$fieldsData['required_text_from_api_without_default'] = 'any text will do';
		$fieldsData['required_text_from_api_without_default_source_2'] = 'any text will do';
		$fieldsData['required_text_from_user_without_default_repOnly'] = 'any text will do';
		$fieldsData['required_text_from_user_without_default_textfield'] = 'any text will do';
		$fieldsData['required_text_from_user_without_default_textfield1'] = 'any text will do';

		$fieldsData['multirecord_from_api_with_default'] = array(
			array(
				'description' => 'Lorem ipsum dolor sit amet',
				'auth_type' => 'Lorem ipsum dolor sit amet',
				'routing_number' => '321174851',
            	'account_number' => '9900000003',
				'bank_name' => 'Lorem ipsum dolor sit amet'
			),
		);

		// execute the method under test
		$actualResponse = $this->CobrandedApplication->saveFields($user, $fieldsData);

		$application = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array('CobrandedApplication.id' => $actualResponse['application_id']),
			)
		);

		// assertions
		$this->assertTrue($actualResponse['success'], 'saveFields with valid data should succeed');
		$this->assertEquals(array(), $actualResponse['validationErrors'], 'Expected no validation errors for valid $fieldsData');

		// test with bad routing number
		$fieldsData['multirecord_from_api_with_default'] = array(
			array(
				'description' => 'Lorem ipsum dolor sit amet',
				'auth_type' => 'Lorem ipsum dolor sit amet',
				'routing_number' => '3211',
            	'account_number' => '9900000003',
				'bank_name' => 'Lorem ipsum dolor sit amet'
			),
		);

		// set expected results
		$expectedValidationErrors = array(
			array(
				'invalid record' => array(
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '3211',
					'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
				),
				'routing_number' => array(
					'routing number is invalid'
				)
			)
		);

		// execute the method under test
		$actualResponse = $this->CobrandedApplication->saveFields($user, $fieldsData);

		// assertions
		$this->assertFalse($actualResponse['success'], 'saveFields with invalid value for required field should fail');
		$this->assertEquals($expectedValidationErrors, $actualResponse['validationErrors'], 'Expected validation errors did not match');

		unset($fieldsData);
		unset($expectedValidationErrors);

		$applications = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array('CobrandedApplication.user_id' => $this->__user['OnlineappUser']['id']),
			)
		);
		$this->assertEquals(1, count($applications), 'Expect to find one application for user with id ['.$this->__user['OnlineappUser']['id'].']');

		$templateData = $this->CobrandedApplication->getTemplateAndAssociatedValues($applications[0]['CobrandedApplication']['id']);
		$templateField = $templateData['Template']['TemplatePages'][0]['TemplateSections'][0]['TemplateFields'][0];

		for ($index=1; $index < 23; $index++) {
			// 15 and 16 are not implemented yet
			if ($index == 15 || $index == 16) {
				// ignore for now
			} else {
				if ($index == 0 ||
					$index == 3 ||
					$index == 4 ||
					$index == 5 ||
					$index == 6 ||
					$index == 7 ||
					$index == 8 ||
					$index == 20 ||
					$index == 21 ||
					$index == 22) {
					// no validation
				} else {
					// set the $expectedValidationErrors
					$expectedValidationErrors['required_text_from_api_without_default'] = 'date';
					$expectedValidationErrors['Text field'] = 'required';
					$expectedValidationErrors['Text field 1'] = 'required';

					// update templateField's type
					$templateField['type'] = $index;
					$this->TemplateField->save($templateField);

					// set the fieldsData
					$fieldsData['required_text_from_api_without_default'] = $this->__invalidApiTestValue[$index];

					// execute the method under test
					$actualResponse = $this->CobrandedApplication->saveFields($user, $fieldsData);
					$this->assertFalse($actualResponse['success'], 'saveFields with empty value for required field should fail. $index ['.$index.']');
// !! NEED TO REVISIT THE FOLLOWING TEST
					//$this->assertEquals($expectedValidationErrors, $actualResponse['validationErrors'], 'Expected validation errors did not match ['.$index.']');
				}
			}
		}
	}

	private $__invalidApiTestValue = array(
		'text type is not validated',                                    //  0 - free form
		'not a date',                                                    //  1 - yyyy/mm/dd
		'not a time',                                                    //  2 - hh:mm:ss
		'radio type is not validated',                                   //  3 - 
		'label type is not validated',                                   //  4 - 
		'100000',                                                        //  5 - (group of percent)
		'label type is not validated',                                   //  6 - no validation
		'fees type is not validated',                                    //  7 - (group of money?)
		'hr type is not validated',                                      //  8 - no validation
		'phoneUS',                                                       //  9 - (###) ###-####
		'money same as fees',                                            // 10 - $(#(1-3),)?(#(1-3)).## << needs work
		'percent should be > 0 < 100',                                   // 11 - (0-100)%
		'ssn value shoudl be ###-##-####',                               // 12 - ###-##-####
		'zipcodeUS could include zip and the optional plus four value',  // 13 - #####[-####]
		'email value shoudld be name@domainname.com',                    // 14 - 
		'lengthoftime is not used yet',                                  // 15 - [#+] [year|month|day]s
		'creditcard is not used yet',                                    // 16 - 
		'url value should look like http://domain.com',                  // 17 - 
		'number with a decimal',                                         // 18 - (#)+.(#)+
		'digits only, nothing else',                                     // 19 - (#)+
		'select... must be one of the default values',                   // 20 - *** need to implement this ***
		'',                                                              // 21 - free form textarea
	);

	public function testCreateOnlineappForUser() {
		$expectedResponse = array(
			'success' => true,
			'cobrandedApplication' => array(
				'id' => 1, // testing for not null is the best we can do
				'uuid' => 'some uuid', // no way of knowing this value...
			),
		);

		$actualResponse = $this->CobrandedApplication->createOnlineappForUser($this->__user['OnlineappUser']);
		$this->assertTrue($actualResponse['success'], 'createOnlineappForUser did not create an application');
		$this->assertNotNull($actualResponse['cobrandedApplication']['id'], 'createOnlineappForUser should return a cobranded application id that is not null');

		// next pass a uuid and guess at the id (id++)
		$expectedResponse['cobrandedApplication']['id'] = $actualResponse['cobrandedApplication']['id']+1;
		$uuid = String::uuid();
		$expectedResponse['cobrandedApplication']['uuid'] = $uuid;
		$actualResponse = $this->CobrandedApplication->createOnlineappForUser($this->__user['OnlineappUser'], $uuid);
		$this->assertEquals($expectedResponse, $actualResponse, 'createOnlineappForUser response did not match the expected');
	}

	public function testCopyApplication() {
		// knowns:
		//     applicationId of the app we want to copy
		// pre-test:
		//     - should not any cobrandedApplications for this user
		$apps = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => $this->__user['OnlineappUser']['id']
				),
			)
		);
		$this->assertEquals(0, count($apps), 'Expected to find no apps for user with id ['.$this->__user['OnlineappUser']['id'].']');

		// create an app
		$this->CobrandedApplication->create(
			array(
				'uuid' => String::uuid(),
				'user_id' => $this->__user['OnlineappUser']['id'],
				'template_id' => $this->__user['OnlineappUser']['template_id'],
			)
		);
		$this->CobrandedApplication->save();
		$apps = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => $this->__user['OnlineappUser']['id']
				),
			)
		);

		// should now have 1 app
		$this->assertEquals(1, count($apps), 'Expected to find one app for user with id ['.$this->__user['OnlineappUser']['id'].']');

		// update the values
		$expectedApp = $apps[0];
		$this->__setSomeValuesBasedOnType($expectedApp);

		// next, copy this app
		$this->CobrandedApplication->copyApplication($expectedApp['CobrandedApplication']['id'], $this->__user['OnlineappUser']['id']);

		$apps = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => $this->__user['OnlineappUser']['id']
				),
			)
		);

		// should now have 2 apps
		$this->assertEquals(2, count($apps), 'Expected to find two apps for user with id ['.$this->__user['OnlineappUser']['id'].']');

		// and they should have the same user_id and template_id
		$this->assertEquals(
			$expectedApp['CobrandedApplication']['user_id'],
			$apps[1]['CobrandedApplication']['user_id'],
			'Copied application should have the same userId'
		);
		$this->assertEquals(
			$expectedApp['CobrandedApplication']['template_id'],
			$apps[1]['CobrandedApplication']['template_id'],
			'Copied application should have the same templateId'
		);

		// and CobrandedApplicationValues
		foreach ($expectedApp['CobrandedApplicationValues'] as $key => $value) {
			$expected = $apps[0]['CobrandedApplicationValues'][$key]['value'];
			$actual = $apps[1]['CobrandedApplicationValues'][$key]['value'];
			$this->assertEquals($expected, $actual,
				"Copied applications value [$expected] did not match original [$actual]"
			);
		}
	}

	public function testSendFieldCompletionEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => '',
			'dba' => '',
			'email' => 'testing@axiapayments.com',
			'fullname' => ''
		);

		$emailAddress = 'testing@axiapayments.com';
		$response = $this->CobrandedApplication->sendFieldCompletionEmail($emailAddress);

		// assertions
		$this->assertTrue($response['success'], 'sendFieldCompletionEmail with good email address should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'Could not find any applications with the specified email address.'
		);

		$emailAddress = 'nogood@assertfail.com';
		$response = $this->CobrandedApplication->sendFieldCompletionEmail($emailAddress);

		// assertions
		$this->assertFalse($response['success'], 'sendFieldCompletionEmail with bad email address should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testSendNewApiApplicationEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => ''
		);

		$args = array();
		$response = $this->CobrandedApplication->sendNewApiApplicationEmail($args);

		// assertions
		$this->assertTrue($response['success'], 'sendNewApiApplicationEmail using default values should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'invalid email address submitted.'
		);

		$args = array('to' => '');
		$response = $this->CobrandedApplication->sendNewApiApplicationEmail($args);

		// assertions
		$this->assertFalse($response['success'], 'sendNewApiApplicationEmail with invalid email address should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testSendApplicationForSigningEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);

		$response = $this->CobrandedApplication->sendApplicationForSigningEmail($app['CobrandedApplication']['id']);

		$expectedResponse = array(
			'success' => true,
			'msg' => ''
		);

		// assertions
		$this->assertTrue($response['success'], 'sendApplicationForSigningEmail with good email address should not fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$response = $this->CobrandedApplication->sendApplicationForSigningEmail('0');

		$expectedResponse = array(
			'success' => false,
			'msg' => 'Invalid application.'
		);
		
		// assertions
		$this->assertFalse($response['success'], 'sendApplicationForSigningEmail with invalid application should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testSendForCompletion() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);

		$response = $this->CobrandedApplication->sendForCompletion($app['CobrandedApplication']['id']);

		$expectedResponse = array(
			'success' => true,
			'msg' => '',
			'dba' => '',
			'email' => 'testing@axiapayments.com',
			'fullname' => ''
		);

		// assertions
		$this->assertTrue($response['success'], 'sendForCompletion with good email address should not fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$response = $this->CobrandedApplication->sendForCompletion('0');

		$expectedResponse = array(
			'success' => false,
			'msg' => 'Invalid application.'
		);
		
		// assertions
		$this->assertFalse($response['success'], 'sendForCompletion with invalid application should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testRepNotifySignedEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);

		$response = $this->CobrandedApplication->repNotifySignedEmail($app['CobrandedApplication']['id']);

		$expectedResponse = array(
			'success' => true,
			'msg' => '',
		);

		// assertions
		$this->assertTrue($response['success'], 'repNotifySignedEmail with valid application should not fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$response = $this->CobrandedApplication->repNotifySignedEmail('0');

		$expectedResponse = array(
			'success' => false,
			'msg' => 'Invalid application.'
		);
		
		// assertions
		$this->assertFalse($response['success'], 'repNotifySignedEmail with invalid application should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testSendRightsignatureInstallSheetEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);

		$response = $this->CobrandedApplication->sendRightsignatureInstallSheetEmail($app['CobrandedApplication']['id'], 'testing@axiapayments.com');

		$expectedResponse = array(
			'success' => true,
			'msg' => '',
		);

		// assertions
		$this->assertTrue($response['success'], 'sendRightsignatureInstallSheetEmail with valid application should not fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$response = $this->CobrandedApplication->sendRightsignatureInstallSheetEmail($app['CobrandedApplication']['id'], 'testing@nogood');

		$expectedResponse = array(
			'success' => false,
			'msg' => 'invalid email address submitted.',
		);

		// assertions
		$this->assertFalse($response['success'], 'sendRightsignatureInstallSheetEmail with invalid email address should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$response = $this->CobrandedApplication->sendRightsignatureInstallSheetEmail('0', 'testing@axiapayments.com');

		$expectedResponse = array(
			'success' => false,
			'msg' => 'Invalid application.'
		);
		
		// assertions
		$this->assertFalse($response['success'], 'sendRightsignatureInstallSheetEmail with invalid application should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testCreateNewApiApplicationEmailTimelineEntry() {
		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'Failed to create email timeline entry.'
		);

		// use bad data
		$args = array('cobranded_application_id' => '');
		$response = $this->CobrandedApplication->createNewApiApplicationEmailTimelineEntry($args);

		// assertions
		$this->assertFalse($response['success'], 'createNewApiApplicationEmailTimelineEntry with empty value for required field should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// use good data now - set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => ''
		);

		$args = array('cobranded_application_id' => '1');
		$response = $this->CobrandedApplication->createNewApiApplicationEmailTimelineEntry($args);

		// assertions
		$this->assertTrue($response['success'], 'createNewApiApplicationEmailTimelineEntry with good value for required field should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testBuildCobrandedApplicationValuesMap() {
		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);

		$response = $this->CobrandedApplication->buildCobrandedApplicationValuesMap($app['CobrandedApplicationValues']);

		$expectedResponse = array(
			'Field 1' => null,
			'name1' => null,
			'name2' => null,
			'name3' => null,
			'Encrypt1' => null,
			'email' => 'testing@axiapayments.com',
			'Owner1Email' => 'testing@axiapayments.com',
			'Owner1Name' => 'Owner1NameTest',
			'TextField1' => 'text field 1',
			'TextField2' => 'text field 2',
			'TextField3' => 'text field 3',
			'TextField4' => 'text field 4'
		);

		// assertions
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testValidateCobrandedApplication() {
		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);

		$response = $this->CobrandedApplication->validateCobrandedApplication($app);

		$expectedResponse = array(
			'success' => false,
			'validationErrors' => array(
				'field 1' => 'required',
				'field 2' => 'required',
				'field 3' => 'required',
				'Text field 1' => 'required'
     		),
     		'validationErrorsArray' => array(
				0 => array(
            		'fieldName' => 'field 1',
            		'mergeFieldName' => 'required_text_from_user_without_default',
            		'msg' => 'Required field is empty: field 1',
            		'page' => 1
         		),
         		1 => array(
            		'fieldName' => 'field 2',
            		'mergeFieldName' => 'required_text_from_user_without_default',
            		'msg' => 'Required field is empty: field 2',
            		'page' => 1
         		),
         		2 => array(
            		'fieldName' => 'field 3',
            		'mergeFieldName' => 'required_text_from_user_without_default',
            		'msg' => 'Required field is empty: field 3',
            		'page' => 1
         		),
         		3 => array(
            		'fieldName' => 'Text field 1',
            		'mergeFieldName' => 'rep_only_true_field_for_testing_rep_only_view_logic',
            		'msg' => 'Required field is empty: Text field 1',
            		'page' => 4
         		)
     		)
		);

		// assertions
		$this->assertFalse($response['success'], 'validateCobrandedApplication with empty required field should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testGetIndexInfo() {
		$expectedResponse = array(
			'fields' => array(
				'DISTINCT CobrandedApplication.id',
				'CobrandedApplication.user_id',
				'CobrandedApplication.template_id',
				'CobrandedApplication.uuid',
				'CobrandedApplication.modified',
				'CobrandedApplication.rightsignature_document_guid',
				'CobrandedApplication.status',
				'CobrandedApplication.rightsignature_install_document_guid',
				'CobrandedApplication.rightsignature_install_status',
				'Template.id',
				'Template.name',
				'User.id',
				'User.firstname',
				'User.lastname',
				'User.email',
				'Coversheet.id',
				'Dba.value',
				'CorpName.value',
				'CorpContact.value'
			),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'onlineapp_cobranded_application_values',
					'alias' => 'Dba',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = Dba.cobranded_application_id and Dba.name =\'DBA\''
					)
				),
				array(
					'table' => 'onlineapp_cobranded_application_values',
					'alias' => 'CorpName',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = CorpName.cobranded_application_id and CorpName.name =\'CorpName\''
					)
				),
				array(
					'table' => 'onlineapp_cobranded_application_values',
					'alias' => 'CorpContact',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = CorpContact.cobranded_application_id and CorpContact.name =\'CorpContact\''
					)
				),
				array(
					'table' => 'onlineapp_cobranded_application_values',
					'alias' => 'CobrandedApplicationValue',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = CobrandedApplicationValue.cobranded_application_id'
					)
				),
				array(
					'table' => 'onlineapp_templates',
					'alias' => 'Template',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.template_id = Template.id'
					)
				),
				array(
					'table' => 'onlineapp_users',
					'alias' => 'User',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.user_id = User.id'
					)
				),
				array(
					'table' => 'onlineapp_coversheets',
					'alias' => 'Coversheet',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = Coversheet.cobranded_application_id'
					)
				)
			)
		);

		$response = $this->CobrandedApplication->getIndexInfo();
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	private function __setSomeValuesBasedOnType(&$app) {
		foreach ($app['CobrandedApplicationValues'] as $key => $value) {
			$templateField = $this->TemplateField->find(
				'first',
				array(
					"conditions" => array(
						"TemplateField.id" => $value['template_field_id']
					)
				)
			);
			$newValue = '';
			switch ($templateField['TemplateField']['type']) {
				case 0:
					$newValue = 'text';
					break;
				case 1:
					$newValue = '2000-01-01';
					break;
				case 2:
					$newValue = '08:00 pm';
					break;
				case 3:
					$newValue = 'true';
					break;
				case 4:
					$newValue = 'true';
					break;
				case 5:
					$newValue = '10';
					break;
				case 6: // label
					$newValue = 'label';
					break;
				case 7:
					$newValue = '10.00';
					break;
				case 8: // hr
					$newValue = 'hr';
					break;
				case 9:
					$newValue = '8005551234';
					break;
				case 10:
					$newValue = '10.00';
					break;
				case 11:
					$newValue = '50';
					break;
				case 12:
					$newValue = '123-45-6789';
					break;
				case 13:
					$newValue = '12345-1234';
					break;
				case 14:
					$newValue = 'name@domain.com';
					break;
				//case 15:
				//	$newValue = '10 months';
				//	break;
				//case 16:
				//	$newValue = '4111-1111-1111-1111';
				//	break;
				case 17:
					$newValue = 'http://www.domain.com';
					break;
				case 18:
					$newValue = '12.82234';
					break;
				case 19:
					$newValue = '1234567890';
					break;
				case 20:
					$newValue = 'true';
					break;
				case 21:
					$newValue = 'a whole lot of text can go into this field...';
					break;
				case 22:
					$newValue = null;
					break;

				default:
					// ignore this
					break;
			}

			$value['value'] = $newValue;
			if ($templateField['TemplateField']['type'] != 9999) {
				$this->CobrandedApplicationValue->save($value);
			}
		}
	}

}
