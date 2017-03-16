<?php
App::uses('Multipass', 'Model');

/**
 * Multipass Test Case
 *
 */
class MultipassTest extends CakeTestCase {
  public $autoFixtures = true;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Multipass = ClassRegistry::init('Multipass');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Multipass);

		parent::tearDown();
	}

/**
 * testCallbackFailure method
 *
 * @return void
 */
	public function testCallbackFailure() {
	}

/**
 * testInitializeMultipass method
 *
 * @return void
 */
	public function testInitializeMultipass() {
	}

/**
 * testEditMultipass method
 *
 * @return void
 */
	public function testEditMultipass() {
	}

/**
 * testEmailLowMultipass method
 *
 * @return void
 */
	public function testEmailLowMultipass() {
	}

/**
 * testEmailSaveFailure method
 *
 * @return void
 */
	public function testEmailSaveFailure() {
	}

/**
 * testEmailNoneAvailable method
 *
 * @return void
 */
	public function testEmailNoneAvailable() {
	}

/**
 * testGetMultipass method
 *
 * @return void
 */
	public function testGetMultipass() {
	}

/**
 * testInUseMultipass method
 *
 * @return void
 */
	public function testInUseMultipass() {
	}

/**
 * testAvailableMultipass method
 *
 * @return void
 */
	public function testAvailableMultipass() {
	}

}
