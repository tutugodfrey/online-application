<?php
App::uses('EmailTimelineSubject', 'Model');

/**
 * EmailTimelineSubject Test Case
 *
 */
class EmailTimelineSubjectTest extends CakeTestCase {
  public $autoFixtures = true;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->EmailTimelineSubject = ClassRegistry::init('EmailTimelineSubject');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EmailTimelineSubject);

		parent::tearDown();
	}

	public function testValidation() {
	}
}
