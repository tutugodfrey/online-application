<?php
App::uses('AppModel', 'Model');
App::uses('Validation', 'Utility');

/**
 * CobrandedApplicationAch Model
 *
 */
class CobrandedApplicationAch extends AppModel {

	public $useTable = 'onlineapp_cobranded_application_aches';

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
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'auth type is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'routing_number' => array(
       		'rule' => array('cc', 'fast', true, null), // third arg set to true - will use Luhn algorithm
        	'message' => 'routing number is invalid'
    	),
    	'account_number' => array(
        	'rule' => array('cc', 'fast', true, null), // third arg set to true - will use Luhn algorithm
        	'message' => 'account number is invalid'
    	),
		'bank_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
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
}









