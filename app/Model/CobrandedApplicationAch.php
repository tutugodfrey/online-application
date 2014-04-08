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
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'cobranded_application_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Numeric value expected',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'auth_type' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'auth_type is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'routing_number' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'routing_number is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'account_number' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'account_number is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'bank_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'bank_name is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

// !!!!!  ADD CRYPTABLE BEHAVIOR
}