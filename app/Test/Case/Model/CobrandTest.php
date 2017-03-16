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
		$expected[4] = 'Corral';

		$result = $this->Cobrand->getList();

		$this->assertEquals($expected, $result);
	}

	public function testSetLogoUrl() {
		$cobrand = $this->Cobrand->find('first',
			array(
				'conditions' => array(
					'id' => 1
				)
			)
		);

		$expected = array(
			'id' => 1,
			'partner_name' => 'Partner Name 1',
			'partner_name_short' => 'PN1',
			'cobrand_logo_url' => 'PN1 logo_url',
			'description' => 'Cobrand "Partner Name 1" description goes here.',
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'response_url_type' => null,
			'brand_logo_url' => 'PN1 logo_url'
		);

		$result = $this->Cobrand->setLogoUrl($cobrand);
		$this->assertEquals($expected, $result['Cobrand']);

		$expected = array(
			'id' => 1,
			'partner_name' => 'Partner Name 1',
			'partner_name_short' => 'PN1',
			'cobrand_logo_url' => '/img/cobrand_logo_test',
			'description' => 'Cobrand "Partner Name 1" description goes here.',
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'response_url_type' => null,
			'brand_logo_url' => '/img/brand_logo_test',
			'cobrand_logo' => array(
				'name' => 'cobrand_logo_test'
			),
			'brand_logo' => array(
				'name' => 'brand_logo_test'
			)
		);

		$cobrand['Cobrand']['cobrand_logo']['name'] = 'cobrand_logo_test';
		$cobrand['Cobrand']['brand_logo']['name'] = 'brand_logo_test';

		$result = $this->Cobrand->setLogoUrl($cobrand);
		$this->assertEquals($expected, $result['Cobrand']);
	}

	public function testGetTemplateIds() {
		$expected = array(
			'1' => '1',
			'2' => '2'
		);

		$result = $this->Cobrand->getTemplateIds(1);
		$this->assertEquals($expected, $result);
	}

	public function testGetExistingLogos() {
		$result = $this->Cobrand->getExistingLogos();
		$this->assertTrue(!empty($result));
	}

	public function testValidation() {
		$expected_missing_both = array(
			'partner_name' => array('Partner name cannot be empty'),
			'partner_name_short' => array('Short partner name cannot be empty'),
		);

		// try to create a new cobrand with out a partner_name or partner_name_short
		$this->Cobrand->create(array(
			'partner_name' => '', 'partner_name_short' => '', 
			'cobrand_logo_url',
			'brand_logo_url',
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
