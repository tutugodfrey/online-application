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

	public $fixtures = array(
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplatePage',
		'app.onlineappTemplateSection',
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
	}

	public function tearDown() {
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

		unset($this->TemplateSection);
		unset($this->TemplatePage);
		unset($this->Template);
		unset($this->Cobrand);

		parent::tearDown();
	}

	public function testGetCobrand() {
		// each page with id=2|3 should have teh same cobrand
		$page_id = 1;
		$expected_cobrand = array(
			'id' => 1,
			'partner_name' => 'Partner Name 1',
			'partner_name_short' => 'PN1',
			'description' => 'Cobrand "Partner Name 1" description goes here.',
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'logo_url' => 'PN1 logo_url',
		);
		$returned_cobrand = $this->TemplateSection->getCobrand($page_id);
		$this->assertEquals($expected_cobrand, $returned_cobrand);

		$page_id = 2;
		$returned_cobrand = $this->TemplateSection->getCobrand($page_id);
		$this->assertEquals($expected_cobrand, $returned_cobrand);

		$page_id = 3;
		$returned_cobrand = $this->TemplateSection->getCobrand($page_id);
		$this->assertEquals($expected_cobrand, $returned_cobrand);    
	}

	public function testGetTemplate() {
		$page_id = 1;
		$expected_template = array(
			'id' => 1,
			'name' => 'Template 1 for PN1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'cobrand_id' => 1,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'logo_position' => 0,
			'include_axia_logo' => true,
		);

		$returned_template = $this->TemplateSection->getTemplate($page_id);
		$this->assertEquals($expected_template, $returned_template);
	}

	public function testGetTemplatePage() {
		$page_id = 1;
		$expected_template_page = array(
			'id' => 1,
			'name' => 'Page 1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'template_id' => 1,
			'order' => 0,
			'created' => '2013-12-18 09:26:45',
			'modified' => '2013-12-18 09:26:45',
			'rep_only' => false,
		);

		$returned_template_page = $this->TemplateSection->getTemplatePage($page_id);
		$this->assertEquals($expected_template_page, $returned_template_page);
	}

	public function testValidation() {
		$expected_validationErrors = array(
			'name' => array('Template section name cannot be empty'),
			'width' => array('Invalid width value used, please select a number between 1 and 12'),
			'page_id' => array('Invalid page_id value used'),
			'order' => array('Invalid order value used'),
		);

		$this->TemplateSection->create(array('name' => '', 'width' => '', 'page_id' => '', 'order' => ''));
		$this->assertFalse($this->TemplateSection->validates());
		$this->assertEquals($expected_validationErrors, $this->TemplateSection->validationErrors);

		// go right
		$expected_validationErrors = array();
		$this->TemplateSection->create(array('name' => 'section name', 'width' => 6, 'page_id' => '1'));
		$this->assertTrue($this->TemplateSection->validates());
		$this->assertEquals($expected_validationErrors, $this->TemplateSection->validationErrors);    
	}

	public function testSaveNew() {
		// save new will get the existing count of Template Sections and use that for the
		//  order property initially there should be no templatePages for any template
		$expected_order_value = 3;
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
		$this->assertEquals($expected_order_value, $this->TemplateSection->field('order'));
	}

	public function testReordering_LastToFirst() {
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

	public function testReordering_FirstToLast() {
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

	public function testReordering_MiddleToFirst() {
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

	public function testReordering_MiddleToLast() {
		
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
