<?php
App::uses('Merchant', 'Model');

/**
 * Merchant Test Case
 *
 */
class MerchantTest extends CakeTestCase {
  public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.merchant',
		'app.equipment_programming',
		'app.timeline_entry',
		'app.timeline_item'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Merchant = ClassRegistry::init('Merchant');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Merchant);

		parent::tearDown();
	}

/**
 * testImport method
 *
 * @return void
 */
	public function testImport() {
	}

/**
 * testSpImportMerchant method
 *
 * @return void
 */
	public function testSpImportMerchant() {
	}

/**
 * testSpUserGetIdByName method
 *
 * @return void
 */
	public function testSpUserGetIdByName() {
	}

}
