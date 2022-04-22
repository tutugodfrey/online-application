<?php
App::uses('AppModel', 'Model');
App::uses('Validation', 'Utility');

/**
 * CobrandedApplicationAch Model
 *
 */
class CobrandedApplicationAch extends AppModel {

	/**
	 * Attach Model Behavoirs
	 * @var array
	 */
	public $actsAs = array(
		'Cryptable' => array(
			'fields' => array(
				'auth_type', 'routing_number', 'account_number'
			)
		)
	);

	/**
	* Validation rules
	*
	* @var array
	*/
	public $validate = array(
		'cobranded_application_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'numeric value expected',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'auth_type' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'auth type is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'routing_number' => array(
			'rule' => 'checkRoutingNumber',
			'required' => true,
			'message' => 'routing number is invalid'
		),
		'account_number' => array(
			'rule' => 'numeric',
			'required' => true,
			'message' => 'account number is invalid'
		),
		'bank_name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'bank name is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

    public $fields = array(
		'description' => array(
			'type' => 'text',
			'required' => false,
			'description' => 'Description'
		),
		'auth_type' => array(
			'type' => 'text',
			'required' => true,
			'description' => 'Authorization Type'
		),
		'routing_number' => array(
			'type' => 'text',
			'required' => true,
			'description' => 'Routing #'
		),
		'account_number' => array(
			'type' => 'text',
			'required' => true,
			'description' => 'Account #'
		),
		'bank_name' => array(
			'type' => 'text',
			'required' => true,
			'description' => 'Bank Name'
		)
    );

    /**
	 * Custom Validation Rule
	 * Checks to see if the routing number entered passes Mod 10 (LUHN)
	 * @param numeric $routingNumber
	 * @return boolean
	 */
	public function checkRoutingNumber($routingNumber = 0) {

		$routingNumber = preg_replace('[\D]', '', $routingNumber['routing_number']); //only digits
		if (strlen($routingNumber) != 9) {
			return false;
		}

		$checkSum = 0;
		for ($i = 0, $j = strlen($routingNumber); $i < $j; $i+= 3) {
			//loop through routingNumber character by character
			$checkSum += ($routingNumber[$i] * 3);
			$checkSum += ($routingNumber[$i + 1] * 7);
			$checkSum += ($routingNumber[$i + 2]);
		}

		if ($checkSum != 0 and ($checkSum % 10) == 0) {
			return true;
		} else {
			return false;
		}
	}
}
