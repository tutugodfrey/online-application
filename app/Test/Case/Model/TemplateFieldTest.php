<?php
App::uses('Cobrand', 'Model');
App::uses('Template', 'Model');
App::uses('TemplatePage', 'Model');
App::uses('TemplateSection', 'Model');
App::uses('TemplateField', 'Model');

/**
 * TemplateField Test Case
 *
 */
class TemplateFieldTest extends CakeTestCase {

	

	public $fixtures = array(
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplatePage',
		'app.onlineappTemplateSection',
		'app.onlineappTemplateField',
		'app.onlineappCobrandedApplication',
		'app.onlineappCobrandedApplicationValue',
	);

	public $autoFixtures = false;

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

	public function tearDown() {
		$this->CobrandedApplicationValue->deleteAll(true, false);
		$this->CobrandedApplication->deleteAll(true, false);
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

		parent::tearDown();
	}

	public function testGetCobrand() {
		$sectionId = 1;
		$expectedCobrand = array(
			'id' => 1,
			'partner_name' => 'Partner Name 1',
			'partner_name_short' => 'PN1',
			'description' => 'Cobrand "Partner Name 1" description goes here.',
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'cobrand_logo_url' => 'PN1 logo_url',
			'response_url_type' => null,
			'brand_logo_url' => 'PN1 logo_url',
		);
		$returnedCobrand = $this->TemplateField->getCobrand($sectionId);
		$this->assertEquals($expectedCobrand, $returnedCobrand);
	}

	public function testGetTemplate() {
		$sectionId = 1;
		$expectedTemplate = array(
			'id' => 1,
			'name' => 'Template 1 for PN1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'cobrand_id' => 1,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'logo_position' => 0,
			'include_brand_logo' => true,
			'rightsignature_template_guid' => null,
			'rightsignature_install_template_guid' => null,
			'owner_equity_threshold' => 50,
			'requires_coversheet' => false
		);

		$returnedTemplate = $this->TemplateField->getTemplate($sectionId);
		$this->assertEquals($expectedTemplate, $returnedTemplate);
	}

	public function testGetTemplatePage() {
		$sectionId = 1;
		$expectedTemplatePage = array(
			'id' => 1,
			'name' => 'Page 1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'template_id' => 1,
			'order' => 0,
			'created' => '2013-12-18 09:26:45',
			'modified' => '2013-12-18 09:26:45',
			'rep_only' => false,
		);

		$returnedTemplatePage = $this->TemplateField->getTemplatePage($sectionId);
		$this->assertEquals($expectedTemplatePage, $returnedTemplatePage);
	}

	public function testGetTemplateSection() {
		$sectionId = 1;
		$expectedTemplateSection = array(
			'id' => 1,
			'name' => 'Page Section 1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'page_id' => 1,
			'order' => 0,
			'created' => '2013-12-18 13:36:11',
			'modified' => '2013-12-18 13:36:11',
			'rep_only' => false,
			'width' => 12,
		);

		$returnedTemplateSection = $this->TemplateField->getTemplateSection($sectionId);
		$this->assertEquals($expectedTemplateSection, $returnedTemplateSection);
	}

	public function testValidation() {
		$expectedValidationErrors = array(
			'name' => array('Template field name cannot be empty'),
			'width' => array('Invalid width value used, please select a number between 1 and 12'),
			'type' => array('Template field type cannot be empty'),
			'source' => array('Template field source cannot be empty'),
			'merge_field_name' => array('Template field merge_field_name cannot be empty'),
			'order' => array('Invalid order value used'),
			'section_id' => array('Invalid section_id value used'),
		);

		$newFieldData = array(
			'name' => '',
			'width' => '',
			'type' => '',
			'required' => '',
			'source' => '',
			'merge_field_name' => '',
			'order' => '',
			'section_id' => '',
		);

		$this->TemplateField->create($newFieldData);
		$this->assertFalse($this->TemplateField->validates());
		$this->assertEquals($expectedValidationErrors, $this->TemplateField->validationErrors);

		// go right
		$newFieldData = array(
			'name' => 'required text field from user without default',
			'width' => 6,
			'type' => 0, // (text|)
			'required' => 1,
			'source' => 1,
			'merge_field_name' => 'required_text_from_user_with_default',
			'order' => 0,
			'section_id' => 1,
		);
		$expectedValidationErrors = array();
		$this->TemplateField->create($newFieldData);
		$this->asserttrue($this->TemplateField->validates());
		$this->assertEquals($expectedValidationErrors, $this->TemplateField->validationErrors);

		// merge_field_name can be empty for fields with type 4, 5, 7 or 20
		$newFieldData = array(
			'name' => 'required text field from user without default',
			'width' => 6,
			'type' => 4, // (radio)
			'required' => 1,
			'source' => 1,
			'merge_field_name' => '',
			'order' => 0,
			'section_id' => 1,
		);
		$expectedValidationErrors = array();
		$this->TemplateField->create($newFieldData);
		$this->asserttrue($this->TemplateField->validates());
		$this->assertEquals($expectedValidationErrors, $this->TemplateField->validationErrors);

		// merge_field_name can be empty for fields with type 4, 5, 7 or 20
		$newFieldData = array(
			'name' => 'required text field from user without default',
			'width' => 6,
			'type' => 5, // (radio)
			'required' => 1,
			'source' => 1,
			'merge_field_name' => '',
			'order' => 0,
			'section_id' => 1,
		);
		$expectedValidationErrors = array();
		$this->TemplateField->create($newFieldData);
		$this->asserttrue($this->TemplateField->validates());
		$this->assertEquals($expectedValidationErrors, $this->TemplateField->validationErrors);

		// merge_field_name can be empty for fields with type 4, 5, 7 or 20
		$newFieldData = array(
			'name' => 'required text field from user without default',
			'width' => 6,
			'type' => 7, // (radio)
			'required' => 1,
			'source' => 1,
			'merge_field_name' => '',
			'order' => 0,
			'section_id' => 1,
		);
		$expectedValidationErrors = array();
		$this->TemplateField->create($newFieldData);
		$this->asserttrue($this->TemplateField->validates());
		$this->assertEquals($expectedValidationErrors, $this->TemplateField->validationErrors);

		// merge_field_name can be empty for fields with type 4, 5, 7 or 20
		$newFieldData = array(
			'name' => 'required text field from user without default',
			'width' => 6,
			'type' => 20, // (radio)
			'required' => 1,
			'source' => 1,
			'merge_field_name' => '',
			'order' => 0,
			'section_id' => 1,
		);
		$expectedValidationErrors = array();
		$this->TemplateField->create($newFieldData);
		$this->asserttrue($this->TemplateField->validates());
		$this->assertEquals($expectedValidationErrors, $this->TemplateField->validationErrors);
	}

	public function testSaveNew() {
		$templateFieldData = array(
			'TemplateField' => array(
				'name' => 'testSaveNew',
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'type' => 0, // (text|)
				'required' => 1,
				'source' => 1,
				'default_value' => '',
				'merge_field_name' => 'required_text_from_user_with_default',
				'section_id' => 1,
			)
		);
		$this->TemplateField->save($templateFieldData, array('validate' => false));
		$expectedOrderValue = 4; // there are three fields already
		$this->assertEquals($expectedOrderValue, $this->TemplateField->field('order'));

		// add another field
		$templateFieldData['TemplateField']['name'] = 'another field';
		$this->TemplateField->create();
		$this->TemplateField->save($templateFieldData, array('validate' => false));
		$expectedOrderValue = $expectedOrderValue + 1;
		$this->assertEquals($expectedOrderValue, $this->TemplateField->field('order'));
	}

	public function testReorderingLastToFirst() {
		// make sure the order values are what we expect
		$first = $this->TemplateField->findById(1);
		$this->assertEquals(0, $first['TemplateField']['order']);
		$second = $this->TemplateField->findById(2);
		$this->assertEquals(1, $second['TemplateField']['order']);
		$third = $this->TemplateField->findById(3);
		$this->assertEquals(2, $third['TemplateField']['order']);

		// move the third field to the front of the list
		$third['TemplateField']['order'] = 0;
		// make sure we save
		$this->assertTrue($this->TemplateField->save($third));

		// check the order values
		$third = $this->TemplateField->findById(3);
		$this->assertEquals(0, $third['TemplateField']['order']);
		$first = $this->TemplateField->findById(1);
		$this->assertEquals(1, $first['TemplateField']['order']);
		$second = $this->TemplateField->findById(2);
		$this->assertEquals(2, $second['TemplateField']['order']);
	}

	public function testReorderingFirstToLast() {
		// make sure the order values are what we expect
		$first = $this->TemplateField->findById(1);
		$this->assertEquals(0, $first['TemplateField']['order']);
		$second = $this->TemplateField->findById(2);
		$this->assertEquals(1, $second['TemplateField']['order']);
		$third = $this->TemplateField->findById(3);
		$this->assertEquals(2, $third['TemplateField']['order']);

		// move the third back to the end now
		$first = $this->TemplateField->findById(1);
		$first['TemplateField']['order'] = 2;
		$this->TemplateField->save($first);

		// make sure the order values are what we expect
		$first = $this->TemplateField->findById(1);
		$this->assertEquals(2, $first['TemplateField']['order']);
		$second = $this->TemplateField->findById(2);
		$this->assertEquals(0, $second['TemplateField']['order']);
		$third = $this->TemplateField->findById(3);
		$this->assertEquals(1, $third['TemplateField']['order']);
	}

	public function testReorderingMiddleToFirst() {
		// make sure the order values are what we expect
		$first = $this->TemplateField->findById(1);
		$this->assertEquals(0, $first['TemplateField']['order']);
		$second = $this->TemplateField->findById(2);
		$this->assertEquals(1, $second['TemplateField']['order']);
		$third = $this->TemplateField->findById(3);
		$this->assertEquals(2, $third['TemplateField']['order']);

		// move the third back to the end now
		$second = $this->TemplateField->findById(2);
		$second['TemplateField']['order'] = 0;
		$this->TemplateField->save($second);

		// make sure the order values are what we expect
		$first = $this->TemplateField->findById(1);
		$this->assertEquals(1, $first['TemplateField']['order']);
		$second = $this->TemplateField->findById(2);
		$this->assertEquals(0, $second['TemplateField']['order']);
		$third = $this->TemplateField->findById(3);
		$this->assertEquals(2, $third['TemplateField']['order']);
	}

	public function testReorderingMiddleToLast() {
		// make sure the order values are what we expect
		$first = $this->TemplateField->findById(1);
		$this->assertEquals(0, $first['TemplateField']['order']);
		$second = $this->TemplateField->findById(2);
		$this->assertEquals(1, $second['TemplateField']['order']);
		$third = $this->TemplateField->findById(3);
		$this->assertEquals(2, $third['TemplateField']['order']);

		// move the third back to the end now
		$second = $this->TemplateField->findById(2);
		$second['TemplateField']['order'] = 2;
		$this->TemplateField->save($second);

		// make sure the order values are what we expect
		$first = $this->TemplateField->findById(1);
		$this->assertEquals(0, $first['TemplateField']['order']);
		$second = $this->TemplateField->findById(2);
		$this->assertEquals(2, $second['TemplateField']['order']);
		$third = $this->TemplateField->findById(3);
		$this->assertEquals(1, $third['TemplateField']['order']);
	}
}
