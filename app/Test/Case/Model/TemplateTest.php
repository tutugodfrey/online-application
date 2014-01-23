<?php
App::uses('Cobrand', 'Model');
App::uses('Template', 'Model');

class TemplateTest extends CakeTestCase {

	public $fixtures = array(
		'app.onlineappCobrand',
		'app.onlineappTemplate'
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

		unset($this->Template);
		unset($this->Cobrand);
		parent::tearDown();
	}

	public function testGetList() {
		$expected = array();
		$expected[1] = 'Template 1 for PN1';
		$expected[2] = 'Template 2 for PN1';
		$expected[3] = 'Template 1 for PN2';

		$result = $this->Template->getList();

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
}
