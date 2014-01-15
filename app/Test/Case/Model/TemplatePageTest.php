<?php
App::uses('Cobrand', 'Model');
App::uses('Template', 'Model');
App::uses('TemplatePage', 'Model');

/**
 * TemplatePage Test Case
 *
 */
class TemplatePageTest extends CakeTestCase {

  public $fixtures = array(
    'app.onlineappCobrand',
    'app.onlineappTemplate',
    'app.onlineappTemplatePage'
  );
  public $autoFixtures = false;

  public static function setUpBeforeClass() {
    parent::setUpBeforeClass();
  }

  public static function tearDownAfterClass() {
    parent::tearDownAfterClass();
  }

  public function setUp() {
    parent::setUp();
    $this->Cobrand = ClassRegistry::init('Cobrand');
    $this->Template = ClassRegistry::init('Template');
    $this->TemplatePage = ClassRegistry::init('TemplatePage');

    // load data
    $this->loadFixtures('OnlineappCobrand');
    $this->loadFixtures('OnlineappTemplate');
    $this->loadFixtures('OnlineappTemplatePage');
  }

  public function tearDown() {
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

    unset($this->TemplatePage);
    unset($this->Template);
    unset($this->Cobrand);

    parent::tearDown();
  }

  public function testGetCobrand() {
    $template_id = 1;
    $expected_cobrand = array(
      'id' => 1,
      'partner_name' => 'Partner Name 1',
      'partner_name_short' => 'PN1',
      'description' => 'Cobrand "Partner Name 1" description goes here.',
      'created' => '2007-03-18 10:41:31',
      'modified' => '2007-03-18 10:41:31',
      'logo_url' => 'PN1 logo_url'
    );
    $returned_cobrand = $this->TemplatePage->getCobrand($template_id);
    $this->assertEquals($expected_cobrand, $returned_cobrand);

    $template_id = 3;
    $expected_cobrand = array(
      'id' => 2,
      'partner_name' => 'Partner Name 2',
      'partner_name_short' => 'PN2',
      'description' => 'Cobrand "Partner Name 2" description goes here.',
      'created' => '2007-03-18 10:41:31',
      'modified' => '2007-03-18 10:41:31',
      'logo_url' => 'PN2 logo_url'
    );
    $returned_cobrand = $this->TemplatePage->getCobrand($template_id);
    $this->assertEquals($expected_cobrand, $returned_cobrand);
  }

  public function testGetCobrand_afterRead() {
    // now test the case when we set the id
    $expected_cobrand = array (
      'id' => 1,
      'partner_name' => 'Partner Name 1',
      'partner_name_short' => 'PN1',
      'logo_url' => 'PN1 logo_url',
      'description' => 'Cobrand "Partner Name 1" description goes here.',
      'created' => '2007-03-18 10:41:31',
      'modified' => '2007-03-18 10:41:31',
    );

    $this->TemplatePage->id = 2;
    $templatePage = $this->TemplatePage->read();
    $this->assertEquals($expected_cobrand, $this->TemplatePage->getCobrand());
  }

  public function testGetTemplate() {
    $template_id = 1;
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
    $returned_template = $this->TemplatePage->getTemplate($template_id);
    $this->assertEquals($expected_template, $returned_template);

    $template_id = 2;
    $expected_template = array(
      'id' => 2,
      'name' => 'Template 2 for PN1',
      'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
      'cobrand_id' => 1,
      'created' => '2007-03-18 10:41:31',
      'modified' => '2007-03-18 10:41:31',
      'logo_position' => 0,
      'include_axia_logo' => true,
    );
    $returned_template = $this->TemplatePage->getTemplate($template_id);
    $this->assertEquals($expected_template, $returned_template);

    $template_id = 3;
    $expected_template = array(
      'id' => 3,
      'name' => 'Template 1 for PN2',
      'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
      'cobrand_id' => 2,
      'created' => '2007-03-18 10:41:31',
      'modified' => '2007-03-18 10:41:31',
      'logo_position' => 0,
      'include_axia_logo' => true,
    );
    $returned_template = $this->TemplatePage->getTemplate($template_id);
    $this->assertEquals($expected_template, $returned_template);
  }

  public function testValidation() {
    $expected_validationErrors = array(
      'name' => array('Template page name cannot be empty'),
      'template_id' => array('Invalid cobrand_id value used'),
      'order' => array('Invalid order value used'),
    );

    $this->TemplatePage->create(array('name' => '', 'template_id' => '', 'order' => ''));
    $this->assertFalse($this->TemplatePage->validates());
    $this->assertEquals($expected_validationErrors, $this->TemplatePage->validationErrors);

    // go right path
    $expected_validationErrors = array();

    $this->TemplatePage->create(array('name' => 'name', 'template_id' => 1, 'order' => 0));
    $this->assertTrue($this->TemplatePage->validates());
    $this->assertEquals($expected_validationErrors, $this->TemplatePage->validationErrors);
  }

  public function testSaveNew() {
    // save new will get the existing count of Template pages and use that for the order property
    // initially there should be no templatePages for any template
    $expected_order_value = 3;
    // create a new template page for template_id = 1
    $data = array(
      'TemplatePage' => array(
        'name' => 'testSaveNew_order',
        'description' => '',
        'template_id' => '1',
      )
    );
    // NOTE: since we are explicitly passing an order value of null
    //       turn off validation
    $this->TemplatePage->save($data, array('validate' => false));
    $this->assertEquals($expected_order_value, $this->TemplatePage->field('order'));
  }

  public function testReordering_LastToFirst() {
    // make sure the order values are what we expect
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(0, $first_page['TemplatePage']['order']);
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(1, $second_page['TemplatePage']['order']);
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(2, $third_page['TemplatePage']['order']);

    // move the third field to the front of the list
    $third_page['TemplatePage']['order'] = 0;
    $this->TemplatePage->save($third_page);

    // check the order values
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(0, $third_page['TemplatePage']['order']);
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(1, $first_page['TemplatePage']['order']);
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(2, $second_page['TemplatePage']['order']);
  }

  public function testReordering_FirstToLast() {
    // make sure the order values are what we expect
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(0, $first_page['TemplatePage']['order']);
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(1, $second_page['TemplatePage']['order']);
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(2, $third_page['TemplatePage']['order']);

    // move the third back to the end now
    $first_page = $this->TemplatePage->findById(1);
    $first_page['TemplatePage']['order'] = 2;
    $this->TemplatePage->save($first_page);

    // make sure the order values are what we expect
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(2, $first_page['TemplatePage']['order']);
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(0, $second_page['TemplatePage']['order']);
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(1, $third_page['TemplatePage']['order']);
  }

  public function testReordering_MiddleToFirst() {
    // make sure the order values are what we expect
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(0, $first_page['TemplatePage']['order']);
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(1, $second_page['TemplatePage']['order']);
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(2, $third_page['TemplatePage']['order']);

    // move the third back to the end now
    $second_page = $this->TemplatePage->findById(2);
    $second_page['TemplatePage']['order'] = 0;
    $this->TemplatePage->save($second_page);
    
    // make sure the order values are what we expect
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(1, $first_page['TemplatePage']['order']);
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(0, $second_page['TemplatePage']['order']);
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(2, $third_page['TemplatePage']['order']);
  }

  public function testReordering_MiddleToLast() {
    // make sure the order values are what we expect
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(0, $first_page['TemplatePage']['order']);
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(1, $second_page['TemplatePage']['order']);
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(2, $third_page['TemplatePage']['order']);

    // move the third back to the end now
    $second_page = $this->TemplatePage->findById(2);
    $second_page['TemplatePage']['order'] = 2;
    $this->TemplatePage->save($second_page);
    
    // make sure the order values are what we expect
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(0, $first_page['TemplatePage']['order']);
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(2, $second_page['TemplatePage']['order']);
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(1, $third_page['TemplatePage']['order']);
  }

  public function testDelete_FirstPage() {
    // make sure the order values are what we expect
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(0, $first_page['TemplatePage']['order']);
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(1, $second_page['TemplatePage']['order']);
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(2, $third_page['TemplatePage']['order']);

    // delete the first one
    $this->TemplatePage->delete(1);
    // make sure 1 is gone
    $this->assertEquals(array(), $this->TemplatePage->findById(1), 'Page with id == [1] has not been deleted.');

    // re-check the order
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(0, $second_page['TemplatePage']['order']);
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(1, $third_page['TemplatePage']['order']);
    // make sure 1 is gone
  }

  public function testDelete_MiddlePage() {
    // make sure the order values are what we expect
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(0, $first_page['TemplatePage']['order']);
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(1, $second_page['TemplatePage']['order']);
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(2, $third_page['TemplatePage']['order']);

    // delete one in the middle; id = 2
    $this->TemplatePage->delete(2);
    // make sure 2 is gone
    $this->assertEquals(array(), $this->TemplatePage->findById(2), 'Page with id == [2] has not been deleted.');

    // recheck the order
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(0, $first_page['TemplatePage']['order']);
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(1, $third_page['TemplatePage']['order']);
  }

  public function testDelete_LastPage() {
    // make sure the order values are what we expect
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(0, $first_page['TemplatePage']['order']);
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(1, $second_page['TemplatePage']['order']);
    $third_page = $this->TemplatePage->findById(3);
    $this->assertEquals(2, $third_page['TemplatePage']['order']);

    // delete the last page
    $this->TemplatePage->delete(3);
    // make sure 3 is gone
    $this->assertEquals(array(), $this->TemplatePage->findById(3), 'Page with id == [3] has not been deleted.');

    // recheck the order
    $first_page = $this->TemplatePage->findById(1);
    $this->assertEquals(0, $first_page['TemplatePage']['order']);
    $second_page = $this->TemplatePage->findById(2);
    $this->assertEquals(1, $second_page['TemplatePage']['order']);
  }
}
