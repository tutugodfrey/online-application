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

    // drop all data in the db
    // NOTE: there should be a better way to do this
    $this->Template->query("SELECT truncate_tables('axia');");

    // load data
    $this->loadFixtures('OnlineappCobrand');
    $this->loadFixtures('OnlineappTemplate');
  }

  public function tearDown() {
    $this->Template->deleteAll(true, true);
    $this->Cobrand->deleteAll(true, true);

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
      ),
    );
    $returned_cobrand = $this->Template->getCobrand(3);
    $this->assertEquals($expected_cobrand, $returned_cobrand);
  }

  public function testValidation() {
    $expected_validationErrors = array(
      'name' => array('Template name cannot be empty'),
    );

    $this->Template->create(array('name' => ''));
    $this->assertFalse($this->Template->validates());
    $this->assertEquals($expected_validationErrors, $this->Template->validationErrors);

    // test non-numeric cobrand_id
    $expected_validationErrors = array(
      'cobrand_id' => array('Invalid cobrand_id value used'),
    );
    $this->Template->create(array('name' => 'template name', 'cobrand_id' => 'abcd'));
    $this->assertFalse($this->Template->validates());
    $this->assertEquals($expected_validationErrors, $this->Template->validationErrors);

    // test the go right path
    $expected_validationErrors = array();
    $this->Template->create(array('name' => 'template name', 'cobrand_id' => 1));
    $this->assertTrue($this->Template->validates());
    $this->assertEquals($expected_validationErrors, $this->Template->validationErrors);
  }
}
