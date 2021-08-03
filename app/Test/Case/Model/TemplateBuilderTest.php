<?php
// App::uses('Cobrand', 'Model');
// App::uses('Template', 'Model');

class TemplateBuilderTest extends CakeTestCase {

	public $autoFixtures = false;

	public $fixtures = array(
		'app.onlineappApiConfiguration',
		'app.onlineappUser',
		'app.onlineappUsersTemplate',
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplatePage',
		'app.onlineappTemplateSection',
		'app.onlineappTemplateField',
		'app.onlineappUsersTemplate',
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
		$this->TemplateBuilder = ClassRegistry::init('TemplateBuilder');
		$this->UsersTemplate = ClassRegistry::init('UsersTemplate');
		$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
		$this->TemplatePage = ClassRegistry::init('TemplatePage');
		$this->TemplateSection = ClassRegistry::init('TemplateSection');
		$this->TemplateField = ClassRegistry::init('TemplateField');

		// load data
		$this->loadFixtures('OnlineappApiConfiguration');
		$this->loadFixtures('OnlineappUser');
		$this->loadFixtures('OnlineappUsersTemplate');
		$this->loadFixtures('OnlineappCobrand');
		$this->loadFixtures('OnlineappTemplate');
		$this->loadFixtures('OnlineappTemplatePage');
		$this->loadFixtures('OnlineappTemplateSection');
		$this->loadFixtures('OnlineappTemplateField');
		$this->loadFixtures('OnlineappCobrandedApplication');
		$this->loadFixtures('OnlineappCobrandedApplicationValue');
		$this->loadFixtures('OnlineappUsersTemplate');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CobrandedApplication);
		unset($this->TemplateField);
		unset($this->TemplateSection);
		unset($this->TemplatePage);
		unset($this->Template);
		unset($this->Cobrand);
		unset($this->TemplateBuilder);
		parent::tearDown();
	}

/**
 * testSetBuilderViewData
 *
 * @param array $expected Expected result
 * @return void
 * @covers TemplateBuilder::setBuilderViewData()
 * @dataProvider providerTestSetBuilderViewData
 */
	public function testSetBuilderViewData($expected) {
		$actual = $this->TemplateBuilder->setBuilderViewData(5);
		unset($actual['templateList']);
		unset($actual['installTemplateList']);
		$this->assertArrayHasKey('cobrands', $actual);
		$this->assertArrayHasKey('logoPositionTypes', $actual);
		$this->assertArrayHasKey('template', $actual);

		$fields = $expected['template']['TemplatePages'][0]['TemplateSections'][0]['TemplateFields'];
		foreach($fields as $field) {
			$this->assertContains($field, $actual['template']['TemplatePages'][0]['TemplateSections'][0]['TemplateFields']);
		}

		$this->assertEquals($expected['template']['Template'], $actual['template']['Template']);
		$this->assertEquals($expected['template']['Cobrand'], $actual['template']['Cobrand']);
	}

/**
 * Provider for testSetBuilderViewData
 *
 * @dataProvider
 * @return void
 */
	public function providerTestSetBuilderViewData() {
		return array(
			array(
				array(
				'cobrands' => array(
					1 => 'Partner Name 1',
					2 => 'Partner Name 2',
					3 => 'Partner Name 3',
					4 => 'Corral'
				),
				'logoPositionTypes' => array(
					'left',
					'center',
					'right',
					'hide'
				),
				'template' => array(
					'Template' => array(
						'id' => 5,
						'name' => 'Template used to test getFields',
						'logo_position' => 0,
						'include_brand_logo' => true,
						'description' => '',
						'cobrand_id' => 2,
						'created' => '2007-03-18 10:41:31',
						'modified' => '2007-03-18 10:41:31',
						'rightsignature_template_guid' => null,
						'rightsignature_install_template_guid' => null,
						'owner_equity_threshold' => 50,
						'requires_coversheet' => null,
						'email_app_pdf' => null
					),
					'Cobrand' => array(
						'id' => 2,
						'partner_name' => 'Partner Name 2',
						'partner_name_short' => 'PN2',
						'cobrand_logo_url' => 'PN2 logo_url',
						'description' => 'Cobrand "Partner Name 2" description goes here.',
						'created' => '2007-03-18 10:41:31',
						'modified' => '2007-03-18 10:41:31',
						'response_url_type' => null,
						'brand_logo_url' => 'PN2 logo_url',
						'brand_name' => null
					),
					'TemplatePages' => array(
						array(
							'id' => 5,
							'name' => 'Page 1',
							'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
							'rep_only' => false,
							'template_id' => 5,
							'order' => 0,
							'created' => '2013-12-18 09:26:45',
							'modified' => '2013-12-18 09:26:45',
							'TemplateSections' => array(
								array(
										'id' => 5,
										'name' => 'Page Section 1',
										'description' => '',
										'rep_only' => false,
										'width' => 12,
										'page_id' => 5,
										'order' => 0,
										'created' => '2013-12-18 13:36:11',
										'modified' => '2013-12-18 13:36:11',
										'TemplateFields' => array(
												array(
														'id' => 36,
														'name' => 'DBA',
														'description' => 'DBA',
														'rep_only' => false,
														'width' => 12,
														'type' => 0,
														'required' => true,
														'source' => 2,
														'default_value' => '',
														'merge_field_name' => 'DBA',
														'order' => 0,
														'section_id' => 5,
														'encrypt' => false,
														'created' => '2013-12-18 14:10:17',
														'modified' => '2013-12-18 14:10:17'
												),
												array(
														'id' => 37,
														'name' => 'EMail',
														'description' => 'EMail',
														'rep_only' => false,
														'width' => 12,
														'type' => 14,
														'required' => true,
														'source' => 2,
														'default_value' => '',
														'merge_field_name' => 'EMail',
														'order' => 0,
														'section_id' => 5,
														'encrypt' => false,
														'created' => '2013-12-18 14:10:17',
														'modified' => '2013-12-18 14:10:17'
												),
												array(
														'id' => 38,
														'name' => 'Text field',
														'description' => '',
														'rep_only' => false,
														'width' => 12,
														'type' => 0,
														'required' => true,
														'source' => 2,
														'default_value' => '',
														'merge_field_name' => 'required_text_from_api_without_default',
														'order' => 0,
														'section_id' => 5,
														'encrypt' => false,
														'created' => '2013-12-18 14:10:17',
														'modified' => '2013-12-18 14:10:17'
												),
												array(
														'id' => 39,
														'name' => 'Text field',
														'description' => '',
														'rep_only' => false,
														'width' => 12,
														'type' => 0,
														'required' => true,
														'source' => 2,
														'default_value' => '',
														'merge_field_name' => 'required_text_from_api_without_default_source_2',
														'order' => 1,
														'section_id' => 5,
														'encrypt' => false,
														'created' => '2013-12-18 14:10:17',
														'modified' => '2013-12-18 14:10:17'
												),
												array(
														'id' => 40,
														'name' => 'Text field',
														'description' => '',
														'rep_only' => false,
														'width' => 12,
														'type' => 0,
														'required' => true,
														'source' => 1,
														'default_value' => '',
														'merge_field_name' => 'required_text_from_user_without_default_repOnly',
														'order' => 2,
														'section_id' => 5,
														'encrypt' => false,
														'created' => '2013-12-18 14:10:17',
														'modified' => '2013-12-18 14:10:17'
												),
												array(
														'id' => 41,
														'name' => 'Text field',
														'description' => '',
														'rep_only' => false,
														'width' => 12,
														'type' => 0,
														'required' => true,
														'source' => 1,
														'default_value' => '',
														'merge_field_name' => 'required_text_from_user_without_default_textfield',
														'order' => 3,
														'section_id' => 5,
														'encrypt' => false,
														'created' => '2013-12-18 14:10:17',
														'modified' => '2013-12-18 14:10:17'
												),
												array(
														'id' => 42,
														'name' => 'Text field 1',
														'description' => '',
														'rep_only' => false,
														'width' => 12,
														'type' => 0,
														'required' => true,
														'source' => 1,
														'default_value' => '',
														'merge_field_name' => 'required_text_from_user_without_default_textfield1',
														'order' => 4,
														'section_id' => 5,
														'encrypt' => false,
														'created' => '2013-12-18 14:10:17',
														'modified' => '2013-12-18 14:10:17'
												),
												array(
														'id' => 43,
														'name' => 'Multirecord field',
														'description' => '',
														'rep_only' => false,
														'width' => 12,
														'type' => 22,
														'required' => false,
														'source' => 0,
														'default_value' => 'CobrandedApplicationAch',
														'merge_field_name' => 'multirecord_from_api_with_default',
														'order' => 5,
														'section_id' => 5,
														'encrypt' => false,
														'created' => '2013-12-18 14:10:17',
														'modified' => '2013-12-18 14:10:17'
												),
												array(
														'id' => 44,
														'name' => 'Owner Type - ',
														'description' => '',
														'rep_only' => false,
														'width' => 12,
														'type' => 4,
														'required' => false,
														'source' => 0,
														'default_value' => 'Corporation::Corp,Sole Prop::SoleProp,LLC::LLC,Partnership::Partnership,Non Profit/Tax Exempt (fed form 501C)::NonProfit,Other::Other',
														'merge_field_name' => 'OwnerType-',
														'order' => 6,
														'section_id' => 5,
														'encrypt' => false,
														'created' => '2013-12-18 14:10:17',
														'modified' => '2013-12-18 14:10:17'
												)
										)
								)
							)
						)
					)
				)
				)
			)
		);
	}

/**
 * testSaveNewTemplate
 *
 * @param array $submittedData emulates data submitted from client side template buider form
 * @return void
 * @covers TemplateBuilder::saveNewTemplate()
 * @dataProvider providerTestSaveNewTemplate
 */
	public function testSaveNewTemplate($submittedData) {
		$actual = $this->TemplateBuilder->saveNewTemplate($submittedData);
		$this->assertEmpty($actual);

		$submittedData['template_page_id_1'] = '0';
		$actual = $this->TemplateBuilder->saveNewTemplate($submittedData);
		$expected = array(
			'errors' => array(
					"-- section: Page Section 1 was selected, but not it's parent page: Page 1",
					"-- section: Page Section 2 was selected, but not it's parent page: Page 1"
			)
		);
		$this->assertNotEmpty($actual);
		$this->assertSame($expected, $actual);
	}

/**
 * Provider for testSaveNewTemplate
 *
 * @dataProvider
 * @return void
 */
	public function providerTestSaveNewTemplate() {
		return array(
			array(
				array(
					'mainBuilderForm' => '1',
					'selected_template_id' => '130',
					'new_template_cobrand_id' => '16',
					'name' => 'New Test Template',
					'logo_position' => '0',
					'include_brand_logo' => '0',
					'requires_coversheet' => '1',
					'description' => 'Lorem Ipsum Dolor sit amet',
					'rightsignature_template_guid' => 'a_12701676_30b90f5acb3e4c298f7812db54275da7',
					'rightsignature_install_template_guid' => 'a_1370816_63b4c6fa71634ac5b2d35a9a69a7e64d',
					'owner_equity_threshold' => '',
					'check_all' => '1',
					'template_page_id_1' => '1',
					'rep_only_template_page_id_1' => 'false',
					'template_page_id_1_section_id_1' => '1',
					'rep_only_template_page_id_1_section_id_1' => 'false',
					'template_page_id_1_section_id_1_field_id_1' => '1',
					'rep_only_template_page_id_1_section_id_1_field_id_1' => 'false',
					'required_template_page_id_1_section_id_1_field_id_1' => 'true',
					'default_template_page_id_1_section_id_1_field_id_1' => 'Corporation::Corp,Sole Prop::SoleProp,LLC::LLC,Partnership::Partnership,Non Profit/Tax Exempt (fed form 501C)::NonProfit,Other::Other',
					'template_page_id_1_section_id_2' => '1',
					'rep_only_template_page_id_1_section_id_2' => 'false',
					'template_page_id_1_section_id_2_field_id_2' => '1',
					'rep_only_template_page_id_1_section_id_2_field_id_2' => 'false',
					'required_template_page_id_1_section_id_2_field_id_2' => 'true',
					'default_template_page_id_1_section_id_2_field_id_2' => '',
					'template_page_id_1_section_id_2_field_id_3' => '1',
					'rep_only_template_page_id_1_section_id_2_field_id_3' => 'false',
					'required_template_page_id_1_section_id_2_field_id_3' => 'true',
					'default_template_page_id_1_section_id_2_field_id_3' => '',
					'template_page_id_1_section_id_2_field_id_4' => '1',
					'rep_only_template_page_id_1_section_id_2_field_id_4' => 'false',
					'required_template_page_id_1_section_id_2_field_id_4' => 'true',
					'default_template_page_id_1_section_id_2_field_id_4' => '',
					'template_page_id_1_section_id_2_field_id_5' => '1',
					'rep_only_template_page_id_1_section_id_2_field_id_5' => 'false',
					'required_template_page_id_1_section_id_2_field_id_5' => 'true',
					'default_template_page_id_1_section_id_2_field_id_5' => 'Alabama::AL,Alaska::AK,Arizona::AZ,Arkansas::AR,California::CA,Colorado::CO,Connecticut::CT,Delaware::DE,District Of Columbia::DC,Florida::FL,Georgia::GA,Hawaii::HI,Idaho::ID,Illinois::IL,Indiana::IN,Iowa::IA,Kansas::KS,Kentucky::KY,Louisiana::LA,Maine::ME,Maryland::MD,Massachusetts::MA,Michigan::MI,Minnesota::MN,Mississippi::MS,Missouri::MO,Montana::MT,Nebraska::NE,Nevada::NV,New Hampshire::NH,New Jersey::NJ,New Mexico::NM,New York::NY,North Carolina::NC,North Dakota::ND,Ohio::OH,Oklahoma::OK,Oregon::OR,Pennsylvania::PA,Rhode Island::RI,South Carolina::SC,South Dakota::SD,Tennessee::TN,Texas::TX,Utah::UT,Vermont::VT,Virginia::VA,Washington::WA,West Virginia::WV,Wisconsin::WI,Wyoming::WY',
					'template_page_id_1_section_id_2_field_id_6' => '1',
					'rep_only_template_page_id_1_section_id_2_field_id_6' => 'false',
					'required_template_page_id_1_section_id_2_field_id_6' => 'true',
					'default_template_page_id_1_section_id_2_field_id_6' => '',
					'template_page_id_1_section_id_2_field_id_7' => '1',
					'rep_only_template_page_id_1_section_id_2_field_id_7' => 'false',
					'required_template_page_id_1_section_id_2_field_id_7' => 'true',
					'default_template_page_id_1_section_id_2_field_id_7' => '',
					'template_page_id_1_section_id_2_field_id_8' => '1',
					'rep_only_template_page_id_1_section_id_2_field_id_8' => 'false',
					'required_template_page_id_1_section_id_2_field_id_8' => 'false',
					'default_template_page_id_1_section_id_2_field_id_8' => '',
					'template_page_id_1_section_id_2_field_id_9' => '1',
					'rep_only_template_page_id_1_section_id_2_field_id_9' => 'false',
					'required_template_page_id_1_section_id_2_field_id_9' => 'true',
					'default_template_page_id_1_section_id_2_field_id_9' => '',
					'template_page_id_1_section_id_2_field_id_10' => '1',
					'rep_only_template_page_id_1_section_id_2_field_id_10' => 'false',
					'required_template_page_id_1_section_id_2_field_id_10' => 'true',
					'default_template_page_id_1_section_id_2_field_id_10' => '',
					'template_page_id_1_section_id_2_field_id_11' => '1',
					'rep_only_template_page_id_1_section_id_2_field_id_11' => 'false',
					'required_template_page_id_1_section_id_2_field_id_11' => 'true',
					'default_template_page_id_1_section_id_2_field_id_11' => '',
				)
			)
		);
	}
}
