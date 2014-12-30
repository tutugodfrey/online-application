<?php
App::uses('Cobrand', 'Model');

/**
 * Cobrand Test Case
 *
 */
class CobrandTest extends CakeTestCase {

	public $fixtures = array(
		'app.onlineappTemplate',
		'app.onlineappCobrand'
	);

	public $autoFixtures = false;

	public $dropTables = false;

	public function setUp() {
		parent::setUp();
		$this->Template = ClassRegistry::init('Template');
		$this->Cobrand = ClassRegistry::init('Cobrand');
		
		// mock filesystem
		// load data
		$this->loadFixtures('OnlineappCobrand');
		$this->loadFixtures('OnlineappTemplate');
	}

	public function tearDown() {
		$this->Template->deleteAll(true, false);
		$this->Cobrand->deleteAll(true, false);
		unset($this->Cobrand);
		parent::tearDown();
	}

	public function testGetList() {
		$expected = array();
		$expected[1] = 'Partner Name 1';
		$expected[2] = 'Partner Name 2';
		$expected[3] = 'Partner Name 3';

		$result = $this->Cobrand->getList();

		$this->assertEquals($expected, $result);
	}

	public function testValidation() {
		$expected_missing_both = array(
			'partner_name' => array('Partner name cannot be empty'),
			'partner_name_short' => array('Short partner name cannot be empty'),
		);

		// try to create a new cobrand with out a partner_name or partner_name_short
		$this->Cobrand->create(array(
			'partner_name' => '', 'partner_name_short' => '', 
			'logo_url',
		));
		$this->assertFalse($this->Cobrand->validates());
		$this->assertEquals($expected_missing_both, $this->Cobrand->validationErrors);

		// go right
		$expected_validation_errors = array();
		$this->Cobrand->create(array(
			'partner_name' => 'some_partner',
			'partner_name_short' => 'some_partner_name_short',
		));
		$this->assertTrue($this->Cobrand->validates());
		$this->assertEquals($expected_validation_errors, $this->Cobrand->validationErrors);
	}

	// TODO: add test for associated templates???
}
