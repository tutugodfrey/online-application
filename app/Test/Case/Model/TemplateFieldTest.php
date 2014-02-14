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
		$query = 'ALTER TABLE onlineapp_users
			DROP CONSTRAINT onlineapp_users_cobrand_fk;
			UPDATE onlineapp_users SET cobrand_id = null;';
		$this->Cobrand->query($query);
		$this->Cobrand->deleteAll(true, false);
		$query = 'ALTER TABLE onlineapp_users
				ADD CONSTRAINT onlineapp_users_cobrand_fk FOREIGN KEY (cobrand_id) REFERENCES onlineapp_cobrands (id);';
		$this->Cobrand->query($query);

		unset($this->TemplateField);
		unset($this->TemplateSection);
		unset($this->TemplatePage);
		unset($this->Template);
		unset($this->Cobrand);

		parent::tearDown();
	}

	public function testGetCobrand() {
		$section_id = 1;
		$expected_cobrand = array(
			'id' => 1,
			'partner_name' => 'Partner Name 1',
			'partner_name_short' => 'PN1',
			'description' => 'Cobrand "Partner Name 1" description goes here.',
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'logo_url' => 'PN1 logo_url',
		);
		$returned_cobrand = $this->TemplateField->getCobrand($section_id);
		$this->assertEquals($expected_cobrand, $returned_cobrand);
	}

	public function testGetTemplate() {
		$section_id = 1;
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

		$returned_template = $this->TemplateField->getTemplate($section_id);
		$this->assertEquals($expected_template, $returned_template);
	}

	public function testGetTemplatePage() {
		$section_id = 1;
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

		$returned_template_page = $this->TemplateField->getTemplatePage($section_id);
		$this->assertEquals($expected_template_page, $returned_template_page);
	}

	public function testGetTemplateSection() {
		$section_id = 1;
		$expected_template_section = array(
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

		$returned_template_section = $this->TemplateField->getTemplateSection($section_id);
		$this->assertEquals($expected_template_section, $returned_template_section);
	}

	public function testValidation() {
		$expected_validationErrors = array(
			'name' => array('Template field name cannot be empty'),
			'width' => array('Invalid width value used, please select a number between 1 and 12'),
			'type' => array('Template field type cannot be empty'),
			'source' => array('Template field source cannot be empty'),
			'merge_field_name' => array('Template field merge_field_name cannot be empty'),
			'order' => array('Invalid order value used'),
			'section_id' => array('Invalid section_id value used'),
		);

		$new_field_data = array(
			'name' => '',
			'width' => '',
			'type' => '',
			'required' => '',
			'source' => '',
			'merge_field_name' => '',
			'order' => '',
			'section_id' => '',
		);

		$this->TemplateField->create($new_field_data);
		$this->assertFalse($this->TemplateField->validates());
		$this->assertEquals($expected_validationErrors, $this->TemplateField->validationErrors);

		// go right
		$new_field_data = array(
			'name' => 'required text field from user w/o default',
			'width' => 6,
			'type' => 0, // (text|)
			'required' => 1,
			'source' => 1,
			'merge_field_name' => 'required_text_from_user_with_default',
			'order' => 0,
			'section_id' => 1,
		);
		$expected_validationErrors = array();
		$this->TemplateField->create($new_field_data);
		$this->asserttrue($this->TemplateField->validates());
		$this->assertEquals($expected_validationErrors, $this->TemplateField->validationErrors);

		// merge_field_name can be empty for fields with type 4, 5, 7 or 20
		$new_field_data = array(
			'name' => 'required text field from user w/o default',
			'width' => 6,
			'type' => 4, // (radio)
			'required' => 1,
			'source' => 1,
			'merge_field_name' => '',
			'order' => 0,
			'section_id' => 1,
		);
		$expected_validationErrors = array();
		$this->TemplateField->create($new_field_data);
		$this->asserttrue($this->TemplateField->validates());
		$this->assertEquals($expected_validationErrors, $this->TemplateField->validationErrors);

		// merge_field_name can be empty for fields with type 4, 5, 7 or 20
		$new_field_data = array(
			'name' => 'required text field from user w/o default',
			'width' => 6,
			'type' => 5, // (radio)
			'required' => 1,
			'source' => 1,
			'merge_field_name' => '',
			'order' => 0,
			'section_id' => 1,
		);
		$expected_validationErrors = array();
		$this->TemplateField->create($new_field_data);
		$this->asserttrue($this->TemplateField->validates());
		$this->assertEquals($expected_validationErrors, $this->TemplateField->validationErrors);

		// merge_field_name can be empty for fields with type 4, 5, 7 or 20
		$new_field_data = array(
			'name' => 'required text field from user w/o default',
			'width' => 6,
			'type' => 7, // (radio)
			'required' => 1,
			'source' => 1,
			'merge_field_name' => '',
			'order' => 0,
			'section_id' => 1,
		);
		$expected_validationErrors = array();
		$this->TemplateField->create($new_field_data);
		$this->asserttrue($this->TemplateField->validates());
		$this->assertEquals($expected_validationErrors, $this->TemplateField->validationErrors);

		// merge_field_name can be empty for fields with type 4, 5, 7 or 20
		$new_field_data = array(
			'name' => 'required text field from user w/o default',
			'width' => 6,
			'type' => 20, // (radio)
			'required' => 1,
			'source' => 1,
			'merge_field_name' => '',
			'order' => 0,
			'section_id' => 1,
		);
		$expected_validationErrors = array();
		$this->TemplateField->create($new_field_data);
		$this->asserttrue($this->TemplateField->validates());
		$this->assertEquals($expected_validationErrors, $this->TemplateField->validationErrors);
	}

	public function testSaveNew() {
		$template_field_data = array(
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
		$this->TemplateField->save($template_field_data, array('validate' => false));
		$expected_order_value = 3; // there are three fields already
		$this->assertEquals($expected_order_value, $this->TemplateField->field('order'));

		// add another field
		$template_field_data['TemplateField']['name'] = 'another field';
		$this->TemplateField->create();
		$this->TemplateField->save($template_field_data, array('validate' => false));
		$expected_order_value = $expected_order_value + 1;
		$this->assertEquals($expected_order_value, $this->TemplateField->field('order'));
	}

	
	public function testReordering_LastToFirst() {
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

	public function testReordering_FirstToLast() {
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

	public function testReordering_MiddleToFirst() {
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

	public function testReordering_MiddleToLast() {
		
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
