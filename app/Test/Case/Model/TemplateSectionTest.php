<?php
App::uses('Cobrand', 'Model');
App::uses('Template', 'Model');
App::uses('TemplatePage', 'Model');
App::uses('TemplateSection', 'Model');

/**
 * TemplateSection Test Case
 *
 */
class TemplateSectionTest extends CakeTestCase {

	public $dropTables = false;

	public $fixtures = array(
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplatePage',
		'app.onlineappTemplateSection',
		'app.onlineappTemplateField',
	);

	public $autoFixtures = false;

	public function setUp() {
		parent::setUp();
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
		$this->TemplatePage = ClassRegistry::init('TemplatePage');
		$this->TemplateSection = ClassRegistry::init('TemplateSection');
		$this->TemplateField = ClassRegistry::init('TemplateField');

		// load data
		$this->loadFixtures('OnlineappCobrand');
		$this->loadFixtures('OnlineappTemplate');
		$this->loadFixtures('OnlineappTemplatePage');
		$this->loadFixtures('OnlineappTemplateSection');
		$this->loadFixtures('OnlineappTemplateField');
	}

	public function tearDown() {
		$this->TemplateField->deleteAll(true, false);
		$this->TemplateSection->deleteAll(true, false);
		$this->TemplatePage->deleteAll(true, false);
		$this->Template->deleteAll(true, false);
		$this->Cobrand->deleteAll(true, false);

		unset($this->TemplateField);
		unset($this->TemplateSection);
		unset($this->TemplatePage);
		unset($this->Template);
		unset($this->Cobrand);

		parent::tearDown();
	}

	public function testGetCobrand() {
		// each page with id=2|3 should have teh same cobrand
		$pageId = 1;
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
		$returnedCobrand = $this->TemplateSection->getCobrand($pageId);
		$this->assertEquals($expectedCobrand, $returnedCobrand);

		$pageId = 2;
		$returnedCobrand = $this->TemplateSection->getCobrand($pageId);
		$this->assertEquals($expectedCobrand, $returnedCobrand);

		$pageId = 3;
		$returnedCobrand = $this->TemplateSection->getCobrand($pageId);
		$this->assertEquals($expectedCobrand, $returnedCobrand);
	}

	public function testGetTemplate() {
		$pageId = 1;
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

		$returnedTemplate = $this->TemplateSection->getTemplate($pageId);
		$this->assertEquals($expectedTemplate, $returnedTemplate);
	}

	public function testGetTemplatePage() {
		$pageId = 1;
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

		$returnedTemplatePage = $this->TemplateSection->getTemplatePage($pageId);
		$this->assertEquals($expectedTemplatePage, $returnedTemplatePage);
	}

	public function testValidation() {
		$expectedValidationErrors = array(
			'name' => array('Template section name cannot be empty'),
			'width' => array('Invalid width value used, please select a number between 1 and 12'),
			'page_id' => array('Invalid page_id value used'),
			'order' => array('Invalid order value used'),
		);

		$this->TemplateSection->create(array('name' => '', 'width' => '', 'page_id' => '', 'order' => ''));
		$this->assertFalse($this->TemplateSection->validates());
		$this->assertEquals($expectedValidationErrors, $this->TemplateSection->validationErrors);

		// go right
		$expectedValidationErrors = array();
		$this->TemplateSection->create(array('name' => 'section name', 'width' => 6, 'page_id' => '1'));
		$this->assertTrue($this->TemplateSection->validates());
		$this->assertEquals($expectedValidationErrors, $this->TemplateSection->validationErrors);
	}

	public function testSaveNew() {
		// save new will get the existing count of Template Sections and use that for the
		//  order property initially there should be no templatePages for any template
		$expectedOrderValue = 3;
		// create a new template page for template_id = 1
		$data = array(
			'TemplateSection' => array(
				'name' => 'testSaveNew_order',
				'width' => 12,
				'description' => '',
				'page_id' => '1',
			)
		);
		$this->TemplateSection->save($data, array('validate' => false));
		$this->assertEquals($expectedOrderValue, $this->TemplateSection->field('order'));
	}

	public function testReorderingLastToFirst() {
		// make sure the order values are what we expect
		$first = $this->TemplateSection->findById(1);
		$this->assertEquals(0, $first['TemplateSection']['order']);
		$second = $this->TemplateSection->findById(2);
		$this->assertEquals(1, $second['TemplateSection']['order']);
		$third = $this->TemplateSection->findById(3);
		$this->assertEquals(2, $third['TemplateSection']['order']);

		// move the third field to the front of the list
		$third['TemplateSection']['order'] = 0;
		$this->TemplateSection->save($third);

		// check the order values
		$third = $this->TemplateSection->findById(3);
		$this->assertEquals(0, $third['TemplateSection']['order']);
		$first = $this->TemplateSection->findById(1);
		$this->assertEquals(1, $first['TemplateSection']['order']);
		$second = $this->TemplateSection->findById(2);
		$this->assertEquals(2, $second['TemplateSection']['order']);
	}

	public function testReorderingFirstToLast() {
		// make sure the order values are what we expect
		$first = $this->TemplateSection->findById(1);
		$this->assertEquals(0, $first['TemplateSection']['order']);
		$second = $this->TemplateSection->findById(2);
		$this->assertEquals(1, $second['TemplateSection']['order']);
		$third = $this->TemplateSection->findById(3);
		$this->assertEquals(2, $third['TemplateSection']['order']);

		// move the third back to the end now
		$first = $this->TemplateSection->findById(1);
		$first['TemplateSection']['order'] = 2;
		$this->TemplateSection->save($first);

		// make sure the order values are what we expect
		$first = $this->TemplateSection->findById(1);
		$this->assertEquals(2, $first['TemplateSection']['order']);
		$second = $this->TemplateSection->findById(2);
		$this->assertEquals(0, $second['TemplateSection']['order']);
		$third = $this->TemplateSection->findById(3);
		$this->assertEquals(1, $third['TemplateSection']['order']);
	}

	public function testReorderingMiddleToFirst() {
		// make sure the order values are what we expect
		$first = $this->TemplateSection->findById(1);
		$this->assertEquals(0, $first['TemplateSection']['order']);
		$second = $this->TemplateSection->findById(2);
		$this->assertEquals(1, $second['TemplateSection']['order']);
		$third = $this->TemplateSection->findById(3);
		$this->assertEquals(2, $third['TemplateSection']['order']);

		// move the third back to the end now
		$second = $this->TemplateSection->findById(2);
		$second['TemplateSection']['order'] = 0;
		$this->TemplateSection->save($second);

		// make sure the order values are what we expect
		$first = $this->TemplateSection->findById(1);
		$this->assertEquals(1, $first['TemplateSection']['order']);
		$second = $this->TemplateSection->findById(2);
		$this->assertEquals(0, $second['TemplateSection']['order']);
		$third = $this->TemplateSection->findById(3);
		$this->assertEquals(2, $third['TemplateSection']['order']);
	}

	public function testReorderingMiddleToLast() {
		// make sure the order values are what we expect
		$first = $this->TemplateSection->findById(1);
		$this->assertEquals(0, $first['TemplateSection']['order']);
		$second = $this->TemplateSection->findById(2);
		$this->assertEquals(1, $second['TemplateSection']['order']);
		$third = $this->TemplateSection->findById(3);
		$this->assertEquals(2, $third['TemplateSection']['order']);

		// move the third back to the end now
		$second = $this->TemplateSection->findById(2);
		$second['TemplateSection']['order'] = 2;
		$this->TemplateSection->save($second);

		// make sure the order values are what we expect
		$first = $this->TemplateSection->findById(1);
		$this->assertEquals(0, $first['TemplateSection']['order']);
		$second = $this->TemplateSection->findById(2);
		$this->assertEquals(2, $second['TemplateSection']['order']);
		$third = $this->TemplateSection->findById(3);
		$this->assertEquals(1, $third['TemplateSection']['order']);
	}
}
