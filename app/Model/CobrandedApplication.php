<?php
App::uses('AppModel', 'Model');
/**
 * CobrandedApplication Model
 *
 * @property CobrandedApplicationValues $CobrandedApplicationValues
 */
class CobrandedApplication extends AppModel {

	public $useTable = 'onlineapp_cobranded_applications';

	public $actsAs = array(
		'Search.Searchable',
		'Containable',
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		/*
		'id' => array(),
		*/
		'uuid' => array(
			'rule' => array('uuid'),
			'message' => 'Invalid UUID',
			'allowEmpty' => false,
			//'required' => false,
			//'last' => false, // Stop validation after this rule
			//'on' => 'create', // Limit validation to 'create' or 'update' operations
		),
		/*
		'created' => array(),
		'modified' => array(),
		*/
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
/**
 * belongsTo association
 * 
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
		),
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'CobrandedApplicationValues' => array(
			'className' => 'CobrandedApplicationValue',
			'foreignKey' => 'cobranded_application_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);

}
