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
}
