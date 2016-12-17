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

	private $__cobrandedApplication;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Group = ClassRegistry::init('Group');
		$this->User = ClassRegistry::init('OnlineappUser');
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
		$cobrandedApplication = $this->CobrandedApplication->find('first', array('recursive' => -1));
		$this->CobrandedApplication->id = $cobrandedApplication['CobrandedApplication']['id'];
		$this->__cobrandedApplication = $this->CobrandedApplication->saveField('user_id', $this->__user['OnlineappUser']['id']);

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
				'id' => (int)1,
				'user_id' => (int)1,
				'template_id' => (int)1,
				'uuid' => 'b118ac22d3cd4ab49148b05d5254ed59',
				'created' => '2014-01-24 09:07:08',
				'modified' => $this->__cobrandedApplication['CobrandedApplication']['modified'],
				'rightsignature_document_guid' => null,
				'status' => null,
				'rightsignature_install_document_guid' => null,
				'rightsignature_install_status' => null,
			),
			'Template' => array(
				'id' => (int)1,
				'name' => 'Template 1 for PN1',
				'logo_position' => (int)0,
				'include_brand_logo' => true,
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'cobrand_id' => (int)1,
				'created' => '2007-03-18 10:41:31',
				'modified' => '2007-03-18 10:41:31',
				'rightsignature_template_guid' => null,
				'rightsignature_install_template_guid' => null,
				'owner_equity_threshold' => 50,
				'Cobrand' => array(
					'id' => (int)1,
					'partner_name' => 'Partner Name 1',
					'partner_name_short' => 'PN1',
					'cobrand_logo_url' => 'PN1 logo_url',
					'description' => 'Cobrand "Partner Name 1" description goes here.',
					'created' => '2007-03-18 10:41:31',
					'modified' => '2007-03-18 10:41:31',
					'response_url_type' => null,
					'brand_logo_url' => 'PN1 logo_url',
				),
				'TemplatePages' => array(
					(int)0 => array(
						'id' => (int)1,
						'name' => 'Page 1',
						'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
						'rep_only' => false,
						'template_id' => (int)1,
						'order' => (int)0,
						'created' => '2013-12-18 09:26:45',
						'modified' => '2013-12-18 09:26:45',
						'TemplateSections' => array(
							(int)0 => array(
								'id' => (int)1,
								'name' => 'Page Section 1',
								'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
								'rep_only' => false,
								'width' => (int)12,
								'page_id' => (int)1,
								'order' => (int)0,
								'created' => '2013-12-18 13:36:11',
								'modified' => '2013-12-18 13:36:11',
								'TemplateFields' => array(
									(int)0 => array(
										'id' => (int)1,
										'name' => 'field 1',
										'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
										'rep_only' => false,
										'width' => (int)12,
										'type' => (int)1,
										'required' => true,
										'source' => (int)1,
										'default_value' => '',
										'merge_field_name' => 'required_text_from_user_without_default',
										'order' => (int)0,
										'section_id' => (int)1,
										'encrypt' => false,
										'created' => '2013-12-18 14:10:17',
										'modified' => '2013-12-18 14:10:17',
										'CobrandedApplicationValues' => array(
											(int)0 => array(
												'id' => (int)1,
												'cobranded_application_id' => (int)1,
												'template_field_id' => (int)1,
												'name' => 'Field 1',
												'value' => null,
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15'
											)
										)
									),
									(int)1 => array(
										'id' => (int)2,
										'name' => 'field 2',
										'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
										'rep_only' => false,
										'width' => (int)12,
										'type' => (int)0,
										'required' => true,
										'source' => (int)1,
										'default_value' => '',
										'merge_field_name' => 'required_text_from_user_without_default',
										'order' => (int)1,
										'section_id' => (int)1,
										'encrypt' => true,
										'created' => '2013-12-18 14:10:17',
										'modified' => '2013-12-18 14:10:17',
										'CobrandedApplicationValues' => array(
											(int)0 => array(
												'id' => (int)5,
												'cobranded_application_id' => (int)1,
												'template_field_id' => (int)2,
												'name' => 'Encrypt1',
												'value' => null,
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15'
											)
										)
									),
									(int)2 => array(
										'id' => (int)3,
										'name' => 'field 3',
										'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
										'rep_only' => false,
										'width' => (int)12,
										'type' => (int)0,
										'required' => true,
										'source' => (int)1,
										'default_value' => '',
										'merge_field_name' => 'required_text_from_user_without_default',
										'order' => (int)2,
										'section_id' => (int)1,
										'encrypt' => false,
										'created' => '2013-12-18 14:10:17',
										'modified' => '2013-12-18 14:10:17',
										'CobrandedApplicationValues' => array()
									),
									(int)3 => array(
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
							(int)1 => array(
								'id' => (int)2,
								'name' => 'Page Section 2',
								'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
								'rep_only' => false,
								'width' => (int)12,
								'page_id' => (int)1,
								'order' => (int)1,
								'created' => '2013-12-18 13:36:11',
								'modified' => '2013-12-18 13:36:11',
								'TemplateFields' => array()
							),
							(int)2 => array(
								'id' => (int)3,
								'name' => 'Page Section 2',
								'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
								'rep_only' => false,
								'width' => (int)12,
								'page_id' => (int)1,
								'order' => (int)2,
								'created' => '2013-12-18 13:36:11',
								'modified' => '2013-12-18 13:36:11',
								'TemplateFields' => array()
							)
						)
					),
					(int)1 => array(
						'id' => (int)2,
						'name' => 'Page 2',
						'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
						'rep_only' => false,
						'template_id' => (int)1,
						'order' => (int)1,
						'created' => '2013-12-18 09:26:45',
						'modified' => '2013-12-18 09:26:45',
						'TemplateSections' => array()
					),
					(int)2 => array(
						'id' => (int)3,
						'name' => 'Page 3',
						'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
						'rep_only' => false,
						'template_id' => (int)1,
						'order' => (int)2,
						'created' => '2013-12-18 09:26:45',
						'modified' => '2013-12-18 09:26:45',
						'TemplateSections' => array()
					)
				),
			)
		);

		// We need an application
		$actual = $this->CobrandedApplication->getTemplateAndAssociatedValues(1);
		$expected['CobrandedApplication']['modified'] = $actual['CobrandedApplication']['modified'];
		$this->assertEquals($expected, $actual, 'getTemplateAndAssociatedValues test failed');

		// we should get rep_only pages, sections, and fields, if we're logged in
		$expected['Template']['TemplatePages'][3] = array(
			'id' => (int)6,
			'name' => 'Page 4',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'rep_only' => true,
			'template_id' => (int)1,
			'order' => (int)3,
			'created' => '2013-12-18 09:26:45',
			'modified' => '2013-12-18 09:26:45',
			'TemplateSections' => array(
				(int)0 => array(
					'id' => (int)6,
					'name' => 'Page Section 1',
					'description' => '',
					'rep_only' => true,
					'width' => (int)12,
					'page_id' => (int)6,
					'order' => (int)0,
					'created' => '2013-12-18 13:36:11',
					'modified' => '2013-12-18 13:36:11',
					'TemplateFields' => array(
						(int)0 => array(
							'id' => (int)43,
							'name' => 'Text field 1',
							'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
							'rep_only' => true,
							'width' => (int)12,
							'type' => (int)0,
							'required' => true,
							'source' => (int)1,
							'default_value' => '',
							'merge_field_name' => 'rep_only_true_field_for_testing_rep_only_view_logic',
							'order' => (int)0,
							'section_id' => (int)6,
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
				'modified' => $this->__cobrandedApplication['CobrandedApplication']['modified'],
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
		$expected['CobrandedApplication']['modified'] = $actual['CobrandedApplication']['modified'];
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
				'modified' => $this->__cobrandedApplication['CobrandedApplication']['modified'],
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
		$expected['CobrandedApplication']['modified'] = $actual['CobrandedApplication']['modified'];
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

		$coversheet = array(
			'user_id' => $this->__user['OnlineappUser']['id'],
			'status' => 'saved',
			'created' => '2016-09-16 14:56:40',
			'modified' => '2016-09-16 14:56:40',
			'cobranded_application_id' => $this->CobrandedApplication->id
		);

		$this->Coversheet->create($coversheet);
		$newCoversheet = $this->Coversheet->save();

		// export an empty application
		$expectedKeys =
			'"MID",' .
			'"required_text_from_user_without_default",' .
			'"required_date_from_user_without_default",' .
			'"required_time_from_user_without_default",' .
			'"required_checkbox_from_user_without_default",' .
			'"required_radio_from_user_without_defaultvalue1",' .
			'"required_radio_from_user_without_defaultvalue2",' .
			'"required_radio_from_user_without_defaultvalue3",' .
			'"required_percents_from_user_without_defaultvalue1",' .
			'"required_percents_from_user_without_defaultvalue2",' .
			'"required_percents_from_user_without_defaultvalue3",' .
			'"required_fees_from_user_without_defaultvalue1",' .
			'"required_fees_from_user_without_defaultvalue2",' .
			'"required_fees_from_user_without_defaultvalue3",' .
			'"required_phoneUS_from_user_without_default",' .
			'"required_money_from_user_without_default",' .
			'"required_percent_from_user_without_default",' .
			'"required_ssn_from_user_without_default",' .
			'"required_zipcodeUS_from_user_without_default",' .
			'"required_email_from_user_without_default",' .
			'"required_url_from_user_without_default",' .
			'"required_number_from_user_without_default",' .
			'"required_digits_from_user_without_default",' .
			'"required_select_from_user_without_default",' .
			'"required_textArea_from_user_without_default",' .
			'"Referral1",' .
			'"Referral2",' .
			'"Referral3",' .
			'"OwnerType-Corp",' .
			'"OwnerType-SoleProp",' .
			'"OwnerType-LLC",' .
			'"OwnerType-Partnership",' .
			'"OwnerType-NonProfit",' .
			'"OwnerType-Other",' .
			'"Unknown Type for testing",' .
			'"oaID",' .
			'"api",' .
			'"aggregated",' .
			'"onlineapp_application_id",' .
			'"user_id",' .
			'"status",' .
			'"setup_existing_merchant",' .
			'"setup_banking",' .
			'"setup_statements",' .
			'"setup_drivers_license",' .
			'"setup_new_merchant",' .
			'"setup_business_license",' .
			'"setup_other",' .
			'"setup_field_other",' .
			'"setup_tier_select",' .
			'"setup_tier3",' .
			'"setup_tier4",' .
			'"setup_tier5_financials",' .
			'"setup_tier5_processing_statements",' .
			'"setup_tier5_bank_statements",' .
			'"setup_equipment_terminal",' .
			'"setup_equipment_gateway",' .
			'"setup_install",' .
			'"setup_starterkit",' .
			'"setup_equipment_payment",' .
			'"setup_lease_price",' .
			'"setup_lease_months",' .
			'"setup_debit_volume",' .
			'"setup_item_count",' .
			'"setup_referrer",' .
			'"setup_referrer_type",' .
			'"setup_referrer_pct",' .
			'"setup_reseller",' .
			'"setup_reseller_type",' .
			'"setup_reseller_pct",' .
			'"setup_notes",' .
			'"cp_encrypted_sn",' .
			'"cp_pinpad_ra_attached",' .
			'"cp_giftcards",' .
			'"cp_check_guarantee",' .
			'"cp_check_guarantee_info",' .
			'"cp_pos",' .
			'"cp_pos_contact",' .
			'"micros",' .
			'"micros_billing",' .
			'"gateway_option",' .
			'"gateway_package",' .
			'"gateway_gold_subpackage",' .
			'"gateway_epay",' .
			'"gateway_billing",' .
			'"moto_online_chd",' .
			'"moto_developer",' .
			'"moto_company",' .
			'"moto_gateway",' .
			'"moto_contact",' .
			'"moto_phone",' .
			'"moto_email",' .
			'"created",' .
			'"modified",' .
			'"setup_referrer_pct_profit",' .
			'"setup_referrer_pct_volume",' .
			'"setup_referrer_pct_gross",' .
			'"setup_reseller_pct_profit",' .
			'"setup_reseller_pct_volume",' .
			'"setup_reseller_pct_gross",' .
			'"setup_partner",' .
			'"setup_partner_pct_profit",' .
			'"setup_partner_pct_volume",' .
			'"setup_partner_pct_gross",' .
			'"gateway_retail_swipe",' .
			'"gateway_epay_charge_licenses"';

		$expectedValues =
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"Off",' .
			'"Off",' .
			'"Off",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"Referral3",' .
			'"Off",' .
			'"Off",' .
			'"Off",' .
			'"Off",' .
			'"Off",' .
			'"Off",' .
			'"",' .
			'"4",' .
			'"",' .
			'"",' .
			'"",' .
			'"1",' .
			'"saved",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"2016-09-16 14:56:40",' .
			'"2016-09-16 14:56:40",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'""';

		$actualKeys = '';
		$actualValues = '';
		$this->CobrandedApplication->buildExportData($this->CobrandedApplication->id, $actualKeys, $actualValues);

		$this->assertEquals($expectedKeys, $actualKeys, 'Empty application keys were not what we expected');
		$this->assertEquals($expectedValues, $actualValues, 'Empty application values were not what we expected');

		// now insert values into the fields
		$app = $this->CobrandedApplication->read();

		$this->__setSomeValuesBasedOnType($app);

		// update the coversheet with some values
		$coversheetData = array(
			'Coversheet' => array(
				'setup_existing_merchant' => '',
				'setup_banking' => true,
				'setup_statements' => true,
				'setup_drivers_license' => true,
				'setup_new_merchant' => true,
				'setup_business_license' => true,
				'setup_other' => true,
				'setup_field_other' => '',
				'setup_tier_select' => true,
				'setup_tier3' => false,
				'setup_tier4' => false,
				'setup_tier5_financials' => false,
				'setup_tier5_processing_statements' => false,
				'setup_tier5_bank_statements' => false,
				'setup_equipment_terminal' => false,
				'setup_equipment_gateway' => true,
				'setup_install' => 'pos',
				'setup_starterkit' => '',
				'setup_equipment_payment' => '',
				'setup_lease_price' => '',
				'setup_lease_months' => '',
				'setup_debit_volume' => '',
				'setup_item_count' => '',
				'setup_referrer' => 'Xsilva',
				'setup_referrer_type' => 'gp',
				'setup_referrer_pct' => '20',
				'setup_reseller' => 'BFA Technologies',
				'setup_reseller_type' => 'gp',
				'setup_reseller_pct' => '30',
				'setup_notes' => '',
				'cp_encrypted_sn' => '',
				'cp_pinpad_ra_attached' => false,
				'cp_giftcards' => 'no',
				'cp_check_guarantee' => 'no',
				'cp_check_guarantee_info' => '',
				'cp_pos' => 'yes',
				'cp_pos_contact' => 'LightSpeed',
				'micros' => '',
				'micros_billing' => '',
				'gateway_option' => 'option2',
				'gateway_package' => 'silver',
				'gateway_gold_subpackage' => '',
				'gateway_epay' => true,
				'gateway_billing' => 'rep',
				'moto_online_chd' => '',
				'moto_developer' => '',
				'moto_company' => '',
				'moto_gateway' => '',
				'moto_contact' => '',
				'moto_phone' => '',
				'moto_email' => '',
				'modified' => '2016-09-16 14:56:40'
			)
		);

		$this->Coversheet->id = $newCoversheet['Coversheet']['id'];
		$this->Coversheet->save($coversheetData, false);

		$expectedValues =
			'"",' .
			'"text",' .
			'"2000-01-01",' .
			'"08:00 pm",' .
			'"true",' .
			'"On",' .
			'"On",' .
			'"On",' .
			'"10",' .
			'"10",' .
			'"10",' .
			'"10.00",' .
			'"10.00",' .
			'"10.00",' .
			'"8005551234",' .
			'"10.00",' .
			'"50",' .
			'"123-45-6789",' .
			'"12345-1234",' .
			'"name@domain.com",' .
			'"http://www.domain.com",' .
			'"12.82234",' .
			'"1234567890",' .
			'"",' .
			'"a whole lot of text can go into this field...",' .
			'"text text text",' .
			'"text text text",' .
			'"text text text",' .
			'"Yes",' .
			'"Yes",' .
			'"Yes",' .
			'"Yes",' .
			'"Yes",' .
			'"Yes",' .
			'"",' .
			'"4",' .
			'"",' .
			'"",' .
			'"",' .
			'"1",' .
			'"saved",' .
			'"",' .
			'"1",' .
			'"1",' .
			'"1",' .
			'"1",' .
			'"1",' .
			'"1",' .
			'"",' .
			'"1",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"1",' .
			'"pos",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"Xsilva",' .
			'"gp",' .
			'"20",' .
			'"BFA Technologies",' .
			'"gp",' .
			'"30",' .
			'"",' .
			'"",' .
			'"",' .
			'"no",' .
			'"no",' .
			'"",' .
			'"yes",' .
			'"LightSpeed",' .
			'"",' .
			'"",' .
			'"option2",' .
			'"silver",' .
			'"",' .
			'"1",' .
			'"rep",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"2016-09-16 14:56:40",' .
			'"2016-09-16 14:56:40",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'""';

		$actualKeys = '';
		$actualValues = '';
		$this->CobrandedApplication->buildExportData($this->CobrandedApplication->id, $actualKeys, $actualValues);

		// the keys should not have changed
		$this->assertEquals($expectedKeys, $actualKeys, 'Filled out application keys were not what we expected');
		$this->assertEquals($expectedValues, $actualValues, 'Filled out application values were not what we expected');

		$actualKeys = '';
		$actualValues = '';
		$this->CobrandedApplication->buildExportData(1, $actualKeys, $actualValues);

		// assertion
		$this->assertNotEmpty($actualValues, 'Expected values to not be empty');
	}

	public function testSetFields() {
		// knowns:
		//     - user
		//     - fieldsData
		// pre-test:
		//     - should be one cobrandedApplication for this user
		$applications = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array('CobrandedApplication.user_id' => $this->__user['OnlineappUser']['id']),
			)
		);
		$this->assertEquals(1, count($applications), 'Expected to find 1 application for user with id [' . $this->__user['OnlineappUser']['id'] . ']');

		// set expected results
		$expectedValidationErrors = array(
			'required_text_from_api_without_default' => 'required',
			'required_text_from_api_without_default_source_2' => 'required',
			'required_text_from_user_without_default_repOnly' => 'required',
			'required_text_from_user_without_default_textfield' => 'required',
			'required_text_from_user_without_default_textfield1' => 'required'
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

		$this->assertEquals(1, count($applications), 'Expected to find 1 application for user with id [' . $this->__user['OnlineappUser']['id'] . ']');

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

		$this->assertEquals(2, count($applications), 'Expect to find two applications for user with id [' . $this->__user['OnlineappUser']['id'] . ']');

		$templateData = $this->CobrandedApplication->getTemplateAndAssociatedValues($applications[0]['CobrandedApplication']['id']);
		$templateField = $templateData['Template']['TemplatePages'][0]['TemplateSections'][0]['TemplateFields'][0];

		for ($index = 1; $index < 23; $index++) {
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
		'text type is not validated',										//  0 - free form
		'not a date',														//  1 - yyyy/mm/dd
		'not a time',														//  2 - hh:mm:ss
		'radio type is not validated',										//  3 -
		'label type is not validated',										//  4 -
		'100000',															//  5 - (group of percent)
		'label type is not validated',										//  6 - no validation
		'fees type is not validated',										//  7 - (group of money?)
		'hr type is not validated',											//  8 - no validation
		'phoneUS',															//  9 - (###) ###-####
		'money same as fees',												// 10 - $(#(1-3),)?(#(1-3)).## << needs work
		'percent should be > 0 < 100',										// 11 - (0-100)%
		'ssn value shoudl be ###-##-####',									// 12 - ###-##-####
		'zipcodeUS could include zip and the optional plus four value',		// 13 - #####[-####]
		'email value shoudld be name@domainname.com',						// 14 -
		'lengthoftime is not used yet',										// 15 - [#+] [year|month|day]s
		'creditcard is not used yet',										// 16 -
		'url value should look like http://domain.com',						// 17 -
		'number with a decimal',											// 18 - (#)+.(#)+
		'digits only, nothing else',										// 19 - (#)+
		'select... must be one of the default values',						// 20 - *** need to implement this ***
		'',																	// 21 - free form textarea
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
		$expectedResponse['cobrandedApplication']['id'] = $actualResponse['cobrandedApplication']['id'] + 1;
		$uuid = String::uuid();
		$expectedResponse['cobrandedApplication']['uuid'] = $uuid;
		$actualResponse = $this->CobrandedApplication->createOnlineappForUser($this->__user['OnlineappUser'], $uuid);
		$this->assertEquals($expectedResponse, $actualResponse, 'createOnlineappForUser response did not match the expected');
	}

	public function testCopyApplication() {
		// knowns:
		//     applicationId of the app we want to copy
		// pre-test:
		//     - should have no cobrandedApplications for this template
		$apps = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array(
					'CobrandedApplication.template_id' => $this->__user['OnlineappUser']['template_id']
				),
			)
		);
		$this->assertEquals(0, count($apps), 'Expected to find no apps for user with id [' . $this->__user['OnlineappUser']['id'] . ']');

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
					'CobrandedApplication.template_id' => $this->__user['OnlineappUser']['template_id']
				//	'CobrandedApplication.user_id' => $this->__user['OnlineappUser']['id']
				),
			)
		);

		// should now have 1 app
		$this->assertEquals(1, count($apps), 'Expected to find one app for user with id [' . $this->__user['OnlineappUser']['id'] . ']');

		// update the values
		$expectedApp = $apps[0];
		$this->__setSomeValuesBasedOnType($expectedApp);

		// next, copy this app
		$this->CobrandedApplication->copyApplication($expectedApp['CobrandedApplication']['id'], $this->__user['OnlineappUser']['id']);

		$apps = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array(
					'CobrandedApplication.template_id' => $this->__user['OnlineappUser']['template_id']
				//	'CobrandedApplication.user_id' => $this->__user['OnlineappUser']['id']
				),
			)
		);

		// should now have 2 apps
		$this->assertEquals(2, count($apps), 'Expected to find two apps for user with id [' . $this->__user['OnlineappUser']['id'] . ']');

		// and they should have the same user_id and template_id
		$this->assertEquals(
			$expectedApp['CobrandedApplication']['user_id'],
			$apps[0]['CobrandedApplication']['user_id'],
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

		// make sure copy works when passing template_id
		$this->CobrandedApplication->copyApplication(
			$expectedApp['CobrandedApplication']['id'],
			$this->__user['OnlineappUser']['id'],
			$this->__user['OnlineappUser']['template_id']
		);

		$apps = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array(
					'CobrandedApplication.template_id' => $this->__user['OnlineappUser']['template_id']
				),
			)
		);

		// should now have 3 apps
		$this->assertEquals(3, count($apps), 'Expected to find three apps for user with id [' . $this->__user['OnlineappUser']['id'] . ']');

		$response = $this->CobrandedApplication->copyApplication(null, null, 999999);
		$this->assertFalse($response, 'copyApplication with bad template_id should fail.');
	}

	public function testSendFieldCompletionEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => '',
			'dba' => 'Doing Business As',
			'email' => 'testing@axiapayments.com',
			'fullname' => 'Corporate Contact'
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

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => '',
			'dba' => 'Doing Business As',
			'email' => 'testing@axiapayments.com',
			'fullname' => 'Corporate Contact'
		);

		$emailAddress = 'testing@axiapayments.com';
		$response = $this->CobrandedApplication->sendFieldCompletionEmail($emailAddress, 1);

		// assertions
		$this->assertTrue($response['success'], 'sendFieldCompletionEmail with good email address should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => '',
			'dba' => 'Doing Business As',
			'email' => 'nogood@assertfail.com',
			'fullname' => 'Corporate Contact'
		);

		$emailAddress = 'nogood@assertfail.com';
		$response = $this->CobrandedApplication->sendFieldCompletionEmail($emailAddress, 1);

		// assertions
		$this->assertTrue($response['success'], 'sendFieldCompletionEmail with good id should succeed');
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
			'success' => true,
			'msg' => ''
		);

		$args = array(
			'from' => 'test@axiapayments.com',
			'cobrand' => 'test',
			'link' => 'test',
			'attachments' => array(
				'test' => array(
					'data' => 'test'
				)
			)
		);
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

	public function testSendEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'from argument is missing.'
		);

		$args = array('to' => 'test@axiapayments.com');
		$response = $this->CobrandedApplication->sendEmail($args);

		// assertions
		$this->assertFalse($response['success'], 'sendEmail with missing from argument should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'to argument is missing.'
		);

		$args = array('from' => 'test@axiapayments.com');
		$response = $this->CobrandedApplication->sendEmail($args);

		// assertions
		$this->assertFalse($response['success'], 'sendEmail with missing to argument should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => ''
		);

		$args = array(
			'from' => 'test@axiapayments.com',
			'to' => 'test@axiapayments.com',
			'attachments' => array(
				'test' => array(
					'data' => 'test'
				)
			)
		);
		$response = $this->CobrandedApplication->sendEmail($args);

		// assertions
		$this->assertTrue($response['success'], 'sendEmail using default values should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$this->CobrandedApplication->CakeEmail = null;

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => ''
		);

		$args = array(
			'from' => 'test@axiapayments.com',
			'to' => 'test@axiapayments.com'
		);
		$response = $this->CobrandedApplication->sendEmail($args);

		// assertions
		$this->assertTrue($response['success'], 'sendEmail using default values should succeed');
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
			'dba' => 'Doing Business As',
			'email' => 'testing@axiapayments.com',
			'fullname' => 'Corporate Contact'
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

		$optionalTemplate = 'rep_notify_signed';

		$response = $this->CobrandedApplication->repNotifySignedEmail($app['CobrandedApplication']['id'], $optionalTemplate);

		$expectedResponse = array(
			'success' => true,
			'msg' => '',
		);

		// assertions
		$this->assertTrue($response['success'], 'repNotifySignedEmail with valid application should not fail');
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

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'cobranded_application_id argument is missing.'
		);

		// use bad data
		$args = array(
			'email_timeline_subject_id' => '',
			'recipient' => ''
		);
		$response = $this->CobrandedApplication->createEmailTimelineEntry($args);

		// assertions
		$this->assertFalse($response['success'], 'createEmailTimelineEntry with missing cobranded_application_id should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'email_timeline_subject_id argument is missing.'
		);

		// use bad data
		$args = array(
			'cobranded_application_id' => '1',
			'recipient' => ''
		);
		$response = $this->CobrandedApplication->createEmailTimelineEntry($args);

		// assertions
		$this->assertFalse($response['success'], 'createEmailTimelineEntry with missing email_timeline_subject_id should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'recipient argument is missing.'
		);

		// use bad data
		$args = array(
			'cobranded_application_id' => '1',
			'email_timeline_subject_id' => ''
		);
		$response = $this->CobrandedApplication->createEmailTimelineEntry($args);

		// assertions
		$this->assertFalse($response['success'], 'createEmailTimelineEntry with missing recipient should fail');
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
			'Owner2Email' => 'testing@axiapayments.com',
			'Owner2Name' => 'Owner2NameTest',
			'TextField1' => 'text field 1',
			'TextField2' => 'text field 2',
			'TextField3' => 'text field 3',
			'TextField4' => 'text field 4',
			'DBA' => 'Doing Business As',
			'CorpName' => 'Corporate Name',
			'CorpContact' => 'Corporate Contact'
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
				'Field 1' => 'required',
				'Encrypt1' => 'required'
			),
			'validationErrorsArray' => array(
				0 => array(
					'fieldName' => 'field 1',
					'mergeFieldName' => 'Field 1',
					'msg' => 'Required field is empty: field 1',
					'page' => 1,
					'rep_only' => false
				),
				1 => array(
					'fieldName' => 'field 2',
					'mergeFieldName' => 'Encrypt1',
					'msg' => 'Required field is empty: field 2',
					'page' => 1,
					'rep_only' => false
				)
			)
		);

		// assertions
		$this->assertFalse($response['success'], 'validateCobrandedApplication with empty required field should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testIndexInfo() {
		$responseTmp = $this->CobrandedApplication->find('index');

		$index = 0;
		$response = array();

		foreach ($responseTmp as $r) {
			if ($r['CobrandedApplication']['uuid'] == 'b118ac22d3cd4ab49148b05d5254ed59') {
				$response[$index] = $r;
				break;
			}
			$index++;
		}

		$expectedResponse = array(
			$index => array(
					'CobrandedApplication' => array(
						'id' => 1,
						'user_id' => 1,
						'template_id' => 1,
						'uuid' => 'b118ac22d3cd4ab49148b05d5254ed59',
						'modified' => $this->__cobrandedApplication['CobrandedApplication']['modified'],
						'rightsignature_document_guid' => null,
						'status' => null,
						'rightsignature_install_document_guid' => null,
						'rightsignature_install_status' => null,
					),
					'Cobrand' => array(
						'id' => 1,
						'partner_name' => 'Partner Name 1',
					),
					'Template' => array(
						'id' => 1,
						'name' => 'Template 1 for PN1',
					),
					'User' => array(
						'id' => 1,
						'firstname' => 'testuser1firstname',
						'lastname' => 'testuser1lastname',
						'email' => 'testing@axiapayments.com',
					),
					'Coversheet' => array(
						'id' => 1,
					),
					'Dba' => array(
						'value' => 'Doing Business As',
					),
					'CorpName' => array(
						'value' => 'Corporate Name',
					),
					'CorpContact' => array(
						'value' => 'Corporate Contact',
					),
					'Owner1Email' => array(
						'value' => 'testing@axiapayments.com',
					),
					'Owner2Email' => array(
						'value' => 'testing@axiapayments.com',
					),
					'EMail' => array(
						'value' => null,
					),
					'LocEMail' => array(
						'value' => null,
					),
					'Merchant' => array(
						'merchant_id' => null,
					)
			),
		);

		$expectedResponse[$index]['CobrandedApplication']['modified'] = $response[$index]['CobrandedApplication']['modified'];
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$responseTmp = $this->CobrandedApplication->find('index',
			array(
				'operation' => 'count',
				'sort' => 'test'
			)
		);

		// assertions
		$this->assertNotEmpty($responseTmp, 'Expected to find at least one app');
	}

	public function testBeforeFind() {
		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
				'sort' => 'CobrandedApplication.user_id',
				'direction' => 'ASC'
			)
		);

		// assertions
		$this->assertNotEmpty($app['CobrandedApplication'], 'Expected to find one app for user with id 1');
	}

	public function testIsExpired() {
		$response = $this->CobrandedApplication->isExpired('b118ac22d3cd4ab49148b05d5254ed59');
		// assertions
		$this->assertFalse($response, 'Expected isExpired to return false for non-signed application');

		$response = $this->CobrandedApplication->isExpired('c118ac22d3cd4ab49148b05d5254ed59');
		// assertions
		$this->assertTrue($response, 'Expected isExpired to return true for signed application');
	}

	public function testOrConditions() {
		$expectedResponse = array(
			'Dba.value ILIKE' => '%test%',
			'CorpName.value ILIKE' => '%test%',
			'CorpContact.value ILIKE' => '%test%',
			'Owner1Email.value ILIKE' => '%test%',
			'EMail.value ILIKE' => '%test%',
			'LocEMail.value ILIKE' => '%test%',
			'CAST(CobrandedApplication.id AS TEXT) ILIKE' => '% test%'
		);

		$response = $this->CobrandedApplication->orConditions(array('search' => 'test'));

		// assertions
		$this->assertEquals($expectedResponse, $response['OR'], 'Expected response did not match response');
	}

	public function testCreateRightSignatureClient() {
		$response = $this->CobrandedApplication->createRightSignatureClient();

		// assertions
		$this->assertInstanceOf('RightSignature', $response, 'Expected response to be instance of RightSignature Object');
	}

	public function testSubmitForReviewEmail() {
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
		$response = $this->CobrandedApplication->submitForReviewEmail($app['CobrandedApplication']['id']);

		$expectedResponse = array(
			'success' => true,
			'msg' => '',
		);

		// assertions
		$this->assertTrue($response['success'], 'submitForReviewEmail with valid application should not fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$response = $this->CobrandedApplication->submitForReviewEmail('0');

		$expectedResponse = array(
			'success' => false,
			'msg' => 'Invalid application.'
		);

		// assertions
		$this->assertFalse($response['success'], 'submitForReviewEmail with invalid application should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testCreateRightSignatureApplicationXml() {
		$now = date('m/d/Y');
		$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : gethostname();

		$expectedResponse = "<?xml version='1.0' encoding='UTF-8'?>
	<template>
		<guid>1234</guid>
		<subject>Doing Business As Axia Merchant Application</subject>
		<description>Sent for signature by test@axiapayments.com</description>
		<action>send</action>
		<expires_in>10 days</expires_in>
		<roles>
			<role role_name='Owner/Officer 1 PG'>
				<name>Owner1NameTest</name>
				<email>noemail@rightsignature.com</email>
				<locked>true</locked>
			</role>
			<role role_name='Owner/Officer 1'>
				<name>Owner1NameTest</name>
				<email>noemail@rightsignature.com</email>
				<locked>true</locked>
			</role>
			<role role_name='Owner/Officer 2 PG'>
				<name>Owner2NameTest</name>
				<email>noemail@rightsignature.com</email>
				<locked>true</locked>
			</role>
			<role role_name='Owner/Officer 2'>
				<name>Owner2NameTest</name>
				<email>noemail@rightsignature.com</email>
				<locked>true</locked>
			</role>
		</roles>
		<merge_fields>
			<merge_field merge_field_name='SystemType'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='MID'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Customer Service Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Product Shipment Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Handling of Returns Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Application Date'>
				<value>" . htmlspecialchars($now) . "</value>
				<locked>true</locked>
			</merge_field>
		</merge_fields>
		<callback_location>http://" . $hostname . "/cobranded_applications/document_callback</callback_location>
	</template>
";

		$applicationId = 1;
		$sender = 'test@axiapayments.com';
		$subject = null;
		$terminalType = null;

		$rightSignatureTemplate = array();
		$rightSignatureTemplate['guid'] = '1234';
		$rightSignatureTemplate['merge_fields'] = array(
			array(
				'name' => 'SystemType'
			),
			array(
				'name' => 'MID'
			),
			array(
				'name' => 'Customer Service'
			),
			array(
				'name' => 'Product Shipment'
			),
			array(
				'name' => 'Handling of Returns'
			),
		);

		$response = $this->CobrandedApplication->createRightSignatureApplicationXml(
			$applicationId, $sender, $rightSignatureTemplate, $subject, $terminalType
		);

		// assertions
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$expectedResponse = "<?xml version='1.0' encoding='UTF-8'?>
	<template>
		<guid>1234</guid>
		<subject>Doing Business As Axia Install Sheet - VAR</subject>
		<description>Sent for signature by test@axiapayments.com</description>
		<action>send</action>
		<expires_in>10 days</expires_in>
		<roles>
			<role role_name='Signer'>
				<name>Owner1NameTest</name>
				<email>noemail@rightsignature.com</email>
				<locked>true</locked>
			</role>
		</roles>
		<merge_fields>
			<merge_field merge_field_name='SystemType'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='MID'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Customer Service Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Product Shipment Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Handling of Returns Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Phone#'>
				<value>877.875.6114 x 1</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='RepFax#'>
				<value>877.875.5135</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Application Date'>
				<value>" . htmlspecialchars($now) . "</value>
				<locked>true</locked>
			</merge_field>
		</merge_fields>
		<callback_location>http://" . $hostname . "/cobranded_applications/document_callback</callback_location>
	</template>
";

		$applicationId = 1;
		$sender = 'test@axiapayments.com';
		$subject = 'Axia Install Sheet - VAR';
		$terminalType = null;

		$rightSignatureTemplate = array();
		$rightSignatureTemplate['guid'] = '1234';
		$rightSignatureTemplate['merge_fields'] = array(
			array(
				'name' => 'SystemType'
			),
			array(
				'name' => 'MID'
			),
			array(
				'name' => 'Customer Service'
			),
			array(
				'name' => 'Product Shipment'
			),
			array(
				'name' => 'Handling of Returns'
			),
		);

		$response = $this->CobrandedApplication->createRightSignatureApplicationXml(
			$applicationId, $sender, $rightSignatureTemplate, $subject, $terminalType
		);

		// assertions
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$expectedResponse = "<?xml version='1.0' encoding='UTF-8'?>
	<template>
		<guid>1234</guid>
		<subject> Axia Merchant Application</subject>
		<description>Sent for signature by test@axiapayments.com</description>
		<action>send</action>
		<expires_in>10 days</expires_in>
		<roles>
		</roles>
		<merge_fields>
			<merge_field merge_field_name='SystemType'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='MID'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Customer Service Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Product Shipment Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Handling of Returns Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Terminal2-'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Application Date'>
				<value>" . htmlspecialchars($now) . "</value>
				<locked>true</locked>
			</merge_field>
		</merge_fields>
		<callback_location>http://" . $hostname . "/cobranded_applications/document_callback</callback_location>
	</template>
";

		$applicationId = 3;
		$sender = 'test@axiapayments.com';
		$subject = null;
		$terminalType = null;

		$rightSignatureTemplate = array();
		$rightSignatureTemplate['guid'] = '1234';
		$rightSignatureTemplate['merge_fields'] = array(
			array(
				'name' => 'SystemType'
			),
			array(
				'name' => 'MID'
			),
			array(
				'name' => 'Customer Service'
			),
			array(
				'name' => 'Product Shipment'
			),
			array(
				'name' => 'Handling of Returns'
			),
			array(
				'name' => 'Terminal2-'
			),
		);

		$response = $this->CobrandedApplication->createRightSignatureApplicationXml(
			$applicationId, $sender, $rightSignatureTemplate, $subject, $terminalType
		);

		// assertions
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
