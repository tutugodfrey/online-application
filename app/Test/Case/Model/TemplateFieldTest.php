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

    // drop all data in the db
    // NOTE: there should be a better way to do this
    $this->Cobrand->query("SELECT truncate_tables('axia');");

    // load data
    $this->loadFixtures('OnlineappCobrand');
    $this->loadFixtures('OnlineappTemplate');
    $this->loadFixtures('OnlineappTemplatePage');
    $this->loadFixtures('OnlineappTemplateSection');
    $this->loadFixtures('OnlineappTemplateField');
  }

  public function tearDown() {
    $this->TemplateField->deleteAll(true, true);
    $this->TemplateSection->deleteAll(true, true);
    $this->TemplatePage->deleteAll(true, true);
    $this->Template->deleteAll(true, true);
    $this->Cobrand->deleteAll(true, true);

    unset($this->TemplateField);

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
    );

    $returned_template = $this->TemplateField->getTemplate($section_id);
    $this->assertEquals($expected_template, $returned_template);
  }

  public function testGetTemplatePage() {
    $section_id = 1;
    $expected_template_page = array(
      'id' => 1,
      'name' => 'Lorem ipsum dolor sit amet',
      'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
      'template_id' => 1,
      'order' => 0,
      'created' => '2013-12-18 09:26:45',
      'modified' => '2013-12-18 09:26:45'
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
      'modified' => '2013-12-18 13:36:11'
    );

    $returned_template_section = $this->TemplateField->getTemplateSection($section_id);
    $this->assertEquals($expected_template_section, $returned_template_section);
  }

  public function testValidation() {
    $expected_validationErrors = array(
    	'name' => array('Template field name cannot be empty'),
    	'type' => array('Template field type cannot be empty'),
    	'required' => array('Invalid required value used'),
    	'source' => array('Template field source cannot be empty'),
    	'merge_field_name' => array('Template field merge_field_name cannot be empty'),
    	'order' => array('Invalid order value used'),
    	'section_id' => array('Invalid section_id value used'),
  	);

  	$new_field_data = array(
  		'name' => '',
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
  }

  public function testSaveNew() {
    $template_section_data = array(
      'TemplateSection' => array(
        'name' => 'testSaveNew_order',
        'description' => '',
        'page_id' => '1'
      )
    );
    $this->TemplateSection->save($template_section_data);

    $section_id = $this->TemplateSection->id;
    $template_field_data = array(
      'TemplateField' => array(
        'name' => 'testSaveNew',
        'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
        'type' => 0, // (text|)
        'required' => 1,
        'source' => 1,
        'default_value' => '',
        'merge_field_name' => 'required_text_from_user_with_default',
        'section_id' => $section_id,
      )
    );
    $this->TemplateField->save($template_field_data);
    $expected_order_value = 0;
    $this->assertEquals($expected_order_value, $this->TemplateField->field('order'));

    // add another field
    $template_field_data['TemplateField']['name'] = 'another field';
    $this->TemplateField->create();
    $this->TemplateField->save($template_field_data);
    $expected_order_value = 1;
    $this->assertEquals($expected_order_value, $this->TemplateField->field('order'));

    
  }
}
