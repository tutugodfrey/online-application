<?php
// App::uses('Cobrand', 'Model');
// App::uses('Template', 'Model');

class TemplateBuilderTest extends CakeTestCase {

	

	public $autoFixtures = false;

	public $fixtures = array(
		'app.onlineappUser',
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplatePage',
		'app.onlineappTemplateSection',
		'app.onlineappTemplateField',
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
		$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
		$this->TemplatePage = ClassRegistry::init('TemplatePage');
		$this->TemplateSection = ClassRegistry::init('TemplateSection');
		$this->TemplateField = ClassRegistry::init('TemplateField');

		// load data
		$this->loadFixtures('OnlineappUser');
		$this->loadFixtures('OnlineappCobrand');
		$this->loadFixtures('OnlineappTemplate');
		$this->loadFixtures('OnlineappTemplatePage');
		$this->loadFixtures('OnlineappTemplateSection');
		$this->loadFixtures('OnlineappTemplateField');
		$this->loadFixtures('OnlineappCobrandedApplication');
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
		$this->assertArrayHasKey('cobrands', $actual);
		$this->assertArrayHasKey('logoPositionTypes', $actual);
		$this->assertArrayHasKey('template', $actual);
		$this->assertArrayHasKey('templateList', $actual);
		$this->assertArrayHasKey('installTemplateList', $actual);
		$this->assertSame(count($expected['templateList']), count($actual['templateList']));
		$this->assertEquals($expected, $actual);
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
						'requires_coversheet' => null
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
						'brand_logo_url' => 'PN2 logo_url'
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
														'id' => 37,
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
														'id' => 38,
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
														'id' => 39,
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
														'id' => 40,
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
														'id' => 41,
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
														'id' => 42,
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
				),
				'templateList' => array(
					'a_12701676_30b90f5acb3e4c298f7812db54275da7' => 'ACH_Authorization.pdf',
					'a_20408697_6cba965939d6441e8ce0b0ce49cdd8a5' => 'AxiaMed_Agreement_with_Terms_and_Conditions_for_RightSignature__revised_12.7.16__Editable.pdf',
					'a_22024756_ad8ea678bbec41ca9416109c2cdb021d' => 'AxiaMed_Agreement_with_Terms_and_Conditions_for_RightSignature__revised_12.7.16__Editable_-_Copy.pdf',
					'a_20941894_fe1a3fae1f4b40a38b8a7044da864ccb' => 'AxiaMed_Flat_Rate_Agreement_with_Terms_and_Conditions_for_RightSignature__revised_12.6.16__Editable.pdf',
					'a_22078031_6a53077ce3b84b8ca98d800b96f4ca9b' => 'AxiaMed_Payment_Fusion_Combined_Flat_Rate_Agreement_with_Terms_and_Conditions_for_RightSignature__revised_12.6.16__Editable.pdf',
					'a_17763983_ad33f7dbf44f47cc9793af93554019ef' => 'AxiaTech_Agreement_with_Terms_and_Conditions_for_RightSignature__revised_12.6.16__Editable.pdf',
					'a_20941762_3d26dee147e84366a061cf49d8cf2956' => 'AxiaTech_Agreement_with_Terms_and_Conditions_for_RightSignature__revised_12.7.16__Flat_Rate.pdf',
					'a_10422450_37b37aa07c084fb384d1fe74275e80ed' => 'Axia_Merrick_Merchant_Agreement_with_Terms_and_Conditions__revised_4.22.15__Crowdster.pdf',
					'a_12928505_274ee92971c64852bfe5e862a659b803' => 'Axia_Merrick_Merchant_Agreement_with_Terms_and_Conditions__revised_6.10.15__Breadcrumbs.pdf',
					'a_10422496_0265c80643bb4978a29d765da11109c9' => 'Axia_Merrick_Non-Profit_Merchant_Agreement_with_Terms_and_Conditions__revised_6.10.15_.pdf',
					'a_19945988_a0a6fb37d6ef4340bcdca6d6bf5a6e93' => 'Axia_Tech_Employee_NDA_Non-Solicit_Non-Disparagement_5-16.pdf',
					'a_19945424_a94f48a9dfba41afa717fcaa32dc9b70' => 'Background_Investigation_Form.pdf',
					'a_20498855_4d1b269add9c433ab81ad80cc65f091e' => 'Corral_Agreement_for_RightSignature_Complete_Provider_Resource__revised_12.6.16__Editable.pdf',
					'a_20499152_37c0bef9d50a4d059bc056b4b8913f4d' => 'Corral_Agreement_for_RightSignature_Rehab_Net_Arkansas__revised_12.6.16__Editable.pdf',
					'a_10509298_9a7acbbfeea4489c97c30717cac2407b' => 'Corral_Agreement_for_RightSignature__revised_12.6.16__Editable.pdf',
					'a_10508841_8c16eeeefe42498e9eaf13bc5ca13ba7' => 'Corral_Agreement_with_ACH_RightSignature__revised_12.7.16__Editable.pdf',
					'a_20552750_56c79090c00b401d9f17a44189ebe33f' => 'Corral_Sales_Agreement_I3Axia_rev_11.22.16.pdf',
					'a_20575209_75128c7ae617494eb6808a1cbbf6295f' => 'Corral_Sales_Agreement_rev_11.22.16.pdf',
					'a_21933214_7e74fcd0e3b2475dbe4dbc9c0d85e38c' => 'I3_Axia_Payments_Agreement_with_Terms_and_Conditions__revised_7.22.16__Editable.pdf',
					'a_10382161_c7e20695693845f1b1f9fd3ddc79a3fa' => 'I3_Axia_Payments_Agreement_with_Terms_and_Conditions_for_RightSignature__revised_7.22.16_.pdf',
					'a_12819196_9fc4bb1d1f72498f86574153233d0d35' => 'Loaner_Agreement.pdf',
					'a_13392825_7b9cebb9f8db4e7abcdde06f5c980536' => 'Payment_Fusion_Sales_Agreement_-_Billed_to_Merchant_via_ACH_by_Payment_Fusion__Acquiring__rev_1.19.16.pdf',
					'a_20767716_2ac3106997d44d97a3bf05ca9418ca2c' => 'Payment_Fusion_Sales_Agreement_-_Billed_to_Merchant_via_ACH_by_Payment_Fusion__Acquiring__rev_1.3.17.pdf'
				),
				'installTemplateList' => array(
						'a_1370816_63b4c6fa71634ac5b2d35a9a69a7e64d' => 'Install Sheet - VAR - 7.16.12.pdf'
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
