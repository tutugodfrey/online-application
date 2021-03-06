<?php
App::uses('CobrandedApplicationAch', 'Model');

/**
 * CobrandedApplicationAch Test Case
 *
 */
class CobrandedApplicationAchTest extends CakeTestCase {

	
	public $autoFixtures = false;

	/**
 	* Fixtures
 	*
 	* @var array
 	*/
	public $fixtures = array(
		'app.onlineappCobrandedApplicationAch',
	);

	/**
	 * setUp method
	 *
	 * @return void
 	*/
	public function setUp() {
		parent::setUp();
		$this->CobrandedApplicationAch = ClassRegistry::init('CobrandedApplicationAch');
		// load data
		$this->loadFixtures('OnlineappCobrandedApplicationAch');
	}

	/**
 	* tearDown method
 	*
 	* @return void
 	*/
	public function tearDown() {
		$this->CobrandedApplicationAch->deleteAll(true, false);
		unset($this->CobrandedApplicationAch);
		parent::tearDown();
	}

	public function testGetAches() {
		$expectedAches = array(
			array(
				'CobrandedApplicationAch' => array(
					'id' => 1,
					'cobranded_application_id' => 1,
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '321174851',
            		'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
					'created' => '2014-01-23 17:28:15',
					'modified' => '2014-01-23 17:28:15'
				)
			),
			array(
				'CobrandedApplicationAch' => array(
					'id' => 2,
					'cobranded_application_id' => 1,
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '321174851',
            		'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
					'created' => '2014-01-23 17:28:15',
					'modified' => '2014-01-23 17:28:15'
				)
			),
			array(
				'CobrandedApplicationAch' => array(
					'id' => 3,
					'cobranded_application_id' => 1,
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '321174851',
            		'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
					'created' => '2014-01-23 17:28:15',
					'modified' => '2014-01-23 17:28:15'
				)
			),
			array(
				'CobrandedApplicationAch' => array(
					'id' => 4,
					'cobranded_application_id' => 1,
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '321174851',
            		'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
					'created' => '2014-01-23 17:28:15',
					'modified' => '2014-01-23 17:28:15'
				)
			),
			array(
				'CobrandedApplicationAch' => array(
					'id' => 5,
					'cobranded_application_id' => 1,
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '321174851',
            		'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
					'created' => '2014-01-23 17:28:15',
					'modified' => '2014-01-23 17:28:15'
				)
			),
		);

		$aches = $this->CobrandedApplicationAch->find(
			'all',
			array(
				'conditions' => array('cobranded_application_id' => 1)
			)
		);

		$this->assertEquals($expectedAches, $aches, 'cobranded application ACH records are not as expected');
	}

	public function testSaveAches() {
		$newAches = array(
			array(
				'cobranded_application_id' => 1,
				'description' => 'Lorem ipsum dolor sit amet',
				'auth_type' => 'Lorem ipsum dolor sit amet',
				'routing_number' => '321174851',
            	'account_number' => '9900000003',
				'bank_name' => 'Lorem ipsum dolor sit amet',
				'created' => '2014-01-23 17:28:15',
				'modified' => '2014-01-23 17:28:15'
			),
			array(
				'cobranded_application_id' => 1,
				'description' => 'Lorem ipsum dolor sit amet',
				'auth_type' => 'Lorem ipsum dolor sit amet',
				'routing_number' => '321174851',
            	'account_number' => '9900000003',
				'bank_name' => 'Lorem ipsum dolor sit amet',
				'created' => '2014-01-23 17:28:15',
				'modified' => '2014-01-23 17:28:15'
			),
		);

		foreach ($newAches as $newAch) {
			$this->CobrandedApplicationAch->create($newAch);
			$this->CobrandedApplicationAch->save();
		}

		$expectedAches = array(
			array(
				'CobrandedApplicationAch' => array(
					'id' => 1,
					'cobranded_application_id' => 1,
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '321174851',
            		'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
					'created' => '2014-01-23 17:28:15',
					'modified' => '2014-01-23 17:28:15'
				)
			),
			array(
				'CobrandedApplicationAch' => array(
					'id' => 2,
					'cobranded_application_id' => 1,
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '321174851',
            		'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
					'created' => '2014-01-23 17:28:15',
					'modified' => '2014-01-23 17:28:15'
				)
			),
			array(
				'CobrandedApplicationAch' => array(
					'id' => 3,
					'cobranded_application_id' => 1,
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '321174851',
            		'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
					'created' => '2014-01-23 17:28:15',
					'modified' => '2014-01-23 17:28:15'
				)
			),
			array(
				'CobrandedApplicationAch' => array(
					'id' => 4,
					'cobranded_application_id' => 1,
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '321174851',
            		'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
					'created' => '2014-01-23 17:28:15',
					'modified' => '2014-01-23 17:28:15'
				)
			),
			array(
				'CobrandedApplicationAch' => array(
					'id' => 5,
					'cobranded_application_id' => 1,
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '321174851',
            		'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
					'created' => '2014-01-23 17:28:15',
					'modified' => '2014-01-23 17:28:15'
				)
			),
			array(
				'CobrandedApplicationAch' => array(
					'id' => 6,
					'cobranded_application_id' => 1,
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '321174851',
            		'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
					'created' => '2014-01-23 17:28:15',
					'modified' => '2014-01-23 17:28:15'
				)
			),
			array(
				'CobrandedApplicationAch' => array(
					'id' => 7,
					'cobranded_application_id' => 1,
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '321174851',
            		'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
					'created' => '2014-01-23 17:28:15',
					'modified' => '2014-01-23 17:28:15'
				)
			),
		);

		$achesAfterSave = $this->CobrandedApplicationAch->find(
			'all',
			array(
				'conditions' => array('cobranded_application_id' => 1)
			)
		);

		$this->assertEquals($expectedAches, $achesAfterSave, 'cobranded application ACH records are not as expected');
	}

	public function testValidation() {
		$expectedValidationErrors = array(
			'cobranded_application_id' => array('numeric value expected'),
			'auth_type' => array('auth type is required'),
			'routing_number' => array('routing number is invalid'),
			'account_number' => array('account number is invalid'),
			'bank_name' => array('bank name is required')
		);

		$this->CobrandedApplicationAch->create(
			array(
				'cobranded_application_id' => '',
				'description' => '',
				'auth_type' => '',
				'routing_number' => '',
				'account_number' => '',
				'bank_name' => ''
			)
		);
	
		$this->assertFalse($this->CobrandedApplicationAch->validates());
		$this->assertEquals($expectedValidationErrors, $this->CobrandedApplicationAch->validationErrors, 'verify create with empty strings');

		$expectedValidationErrors = array();

		$this->CobrandedApplicationAch->create(
			array(
				'cobranded_application_id' => 1,
				'description' => 'Lorem ipsum dolor sit amet',
				'auth_type' => 'Lorem ipsum dolor sit amet',
				'routing_number' => '321174851',
            	'account_number' => '9900000003',
				'bank_name' => 'Lorem ipsum dolor sit amet'
			)
		);

		$this->assertTrue($this->CobrandedApplicationAch->validates());
		$this->assertEquals($expectedValidationErrors, $this->CobrandedApplicationAch->validationErrors, 'verify create produces empty validationErrors array');

		// test bad routing number
		$expectedValidationErrors = array(
			'routing_number' => array('routing number is invalid')
		);

		$this->CobrandedApplicationAch->create(
			array(
				'cobranded_application_id' => 1,
				'description' => 'Lorem ipsum dolor sit amet',
				'auth_type' => 'Lorem ipsum dolor sit amet',
				'routing_number' => '000000000',
            	'account_number' => '9900000003',
				'bank_name' => 'Lorem ipsum dolor sit amet'
			)
		);
	
		$this->assertFalse($this->CobrandedApplicationAch->validates());
		$this->assertEquals($expectedValidationErrors, $this->CobrandedApplicationAch->validationErrors, 'verify create with invalid routing number');
	}
}














