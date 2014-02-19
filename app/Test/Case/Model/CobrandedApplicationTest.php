<?php
App::uses('CobrandedApplication', 'Model');

/**
 * CobrandedApplication Test Case
 *
 */
class CobrandedApplicationTest extends CakeTestCase {

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
				'modified' => '2014-01-24 09:07:08'
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
				'Cobrand' => array(
					'id' => (int) 1,
					'partner_name' => 'Partner Name 1',
					'partner_name_short' => 'PN1',
					'logo_url' => 'PN1 logo_url',
					'description' => 'Cobrand "Partner Name 1" description goes here.',
					'created' => '2007-03-18 10:41:31',
					'modified' => '2007-03-18 10:41:31'
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
										'type' => (int) 0,
										'required' => true,
										'source' => (int) 1,
										'default_value' => '',
										'merge_field_name' => 'required_text_from_user_without_default',
										'order' => (int) 0,
										'section_id' => (int) 1,
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
										'created' => '2013-12-18 14:10:17',
										'modified' => '2013-12-18 14:10:17',
										'CobrandedApplicationValues' => array()
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
				)
			)
		);

		// We need an application
		$actual = $this->CobrandedApplication->getTemplateAndAssociatedValues(1);
		$this->assertEquals($expected, $actual, 'getTemplateAndAssociatedValues test failed');
	}

	public function testSaveApplicationValue() {
		// CUT (class under test) expects
		$data = array(
			'id' => 1,
			'value' => 'new value'
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
			),
			'TemplateField' => array(
				'id' => 1,
				'name' => 'field 1',
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'rep_only' => false,
				'width' => 12,
				'type' => 0,
				'required' => true,
				'source' => 1,
				'default_value' => '',
				'merge_field_name' => 'required_text_from_user_without_default',
				'order' => 0,
				'section_id' => 1,
				'created' => '2013-12-18 14:10:17',
				'modified' => '2013-12-18 14:10:17',
			),
		);
		$actual = $this->CobrandedApplication->getApplicationValue(1);
		$this->assertEquals($expected, $actual, 'expected to find application value with id of 1...');

		// change the value
		$expectedNewValue = 'newValue';
		$inputData = array('id' => 1, 'value' => 'newValue');
		$response = $this->CobrandedApplication->saveApplicationValue($inputData);

		$this->assertTrue($response['success'], 'Expected save operation to return true');
		$actual = $this->CobrandedApplication->getApplicationValue(1);
		$this->assertEquals($expectedNewValue, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');

		// save again witht the same data
		$response = $this->CobrandedApplication->saveApplicationValue($inputData);

		$this->assertFalse($response['success'], 'Expected save operation to return true');
		// also make sure the actual value is still newValue
		$actual = $this->CobrandedApplication->getApplicationValue(1);
		$this->assertEquals($expectedNewValue, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');

		// next save a app value with tmeplate type of 4 (radio)
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
		// build a new template with each type of field on one page
		$this->Template->create(
			array(
				'name' => 'Template For exporting',
				'description' => 'This template will be used in phpunit to test the export feature',
				'cobrand_id' => 2,
				'logo_position' => 0,
				'created' => '2007-03-18 10:41:31',
				'modified' => '2007-03-18 10:41:31',
			)
		);
		$template = $this->Template->save();
		// delete the page that is created by default
		$this->TemplatePage->delete($template['TemplatePages']['0']['id']);

		$this->TemplatePage->create(
			array(
				'template_id' => $this->Template->id,
				'name' => 'Page 1',
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'template_id' => $this->Template->id,
				'rep_only' => false,
				'created' => '2013-12-18 09:26:45',
				'modified' => '2013-12-18 09:26:45'
			)
		);
		$this->TemplatePage->save();

		$this->TemplateSection->create(
			array(
				'name' => 'Page Section 1',
				'width' => 12,
				'rep_only' => false,
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'page_id' => $this->TemplatePage->id,
			)
		);
		$this->TemplateSection->save();

		// loop through the field types and create one of each type
		for ($i=0; $i < count($this->TemplateField->fieldTypes); $i++) { 
			$this->TemplateField->create(
				array(
					'name' => 'field '.$i.' ('.$this->TemplateField->fieldTypes[$i].')',
					'width' => 12,
					'type' => $i,
					'required' => 1,
					'source' => 1,
					'default_value' => 'name1::value1,name2::value2,name3::value3',
					'merge_field_name' => 'required_'.$this->TemplateField->fieldTypes[$i].'_from_user_without_default',
					'section_id' => $this->TemplateSection->id,
					'rep_only' => false,
				)
			);
			$this->TemplateField->save();
		}

		$templateFields = $this->TemplateField->find(
			'list',
			array(
				'conditions' => array('section_id' => $this->TemplateSection->id)
			)
		);

		// next, create an application from this template
		// create a user
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
				'template_id' => $this->Template->id,
			)
		);
		$this->User->save();

		// create the application
		$appCreateData = array(
			'user_id' => $this->User->id,
			'template_id' => $this->Template->id,
			'uuid' => String::uuid(),
		);
		$this->CobrandedApplication->create($appCreateData);
		$this->CobrandedApplication->save();

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
			'"required_lengthoftime_from_user_without_default",'.
			'"required_creditcard_from_user_without_default",'.
			'"required_url_from_user_without_default",'.
			'"required_number_from_user_without_default",'.
			'"required_digits_from_user_without_default",'.
			'"required_select_from_user_without_default",'.
			'"required_text_from_user_without_default",'.
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

		try {
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
					case 15:
						$newValue = '10 months';
						break;
					case 16:
						$newValue = '4111-1111-1111-1111';
						break;
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

					default:
						throw new Exception("Unknown type encountered [".$templateField['TemplateField']['type']."]", 1);
						break;
				}

				$value['value'] = $newValue;
				$this->CobrandedApplicationValue->save($value);
			}
		} catch(Exception $e) {
			// eat this error
			debug($e);
		}

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
			'"10 months",'.
			'"4111-1111-1111-1111",'.
			'"http://www.domain.com",'.
			'"12.82234",'.
			'"1234567890",'.
			'"true",'.
			'"a whole lot of text can go into this field...",'.
			'"2",'.
			'"",'.
			'""';

		$actualKeys = '';
		$actualValues = '';
		$this->CobrandedApplication->buildExportData($this->CobrandedApplication->id, $actualKeys, $actualValues);

		// the keys should not have changed
		$this->assertEquals($expectedKeys, $actualKeys, 'Filled out application keys were not what we expected');
		$this->assertEquals($expectedValues, $actualValues, 'Filled out application values were not what we expected');

		// clean up
		$this->CobrandedApplication->delete($this->CobrandedApplication->id);
		$this->User->delete($this->User->id);
		$this->Template->delete($this->Template->id);
	}
}
