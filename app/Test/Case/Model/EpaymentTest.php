<?php
App::uses('Epayment', 'Model');

/**
 * Epayment Test Case
 *
 */
class EpaymentTest extends CakeTestCase {
  public $autoFixtures = false;
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.onlineappApip',
		'app.onlineappApplication',
		'app.onlineappUser',
		'app.onlineappGroup',
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplate_page',
		'app.onlineappTemplate_section',
		'app.onlineappTemplate_field',
		'app.onlineappEpayment',
		'app.onlineappApi_log',
		'app.onlineappUsers_manager',
		'app.onlineappCoversheet',
		'app.onlineappMultipass',
		'app.merchant',
		'app.equipment_programming',
		'app.timeline_entry',
		'app.timeline_item',
		'app.onlineappEmail_timeline',
		'app.onlineappEmail_timeline_subject'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Epayment = ClassRegistry::init('Epayment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Epayment);

		parent::tearDown();
	}

	public function testValidation() {}
}
