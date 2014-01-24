<?php
App::uses('AppModel', 'Model');
/**
 * CobrandedApplicationValue Model
 *
 * @property CobrandedApplication $CobrandedApplication
 */
class CobrandedApplicationValue extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	public $useTable = 'onlineapp_cobranded_application_values';

	public $actsAs = array(
		'Search.Searchable',
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
				'message' => 'Numeric value expected',
				//'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'template_field_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Numeric value expected',
				//'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Name is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'CobrandedApplication' => array(
			'className' => 'CobrandedApplication',
			'foreignKey' => 'cobranded_application_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'TemplateField' => array(
			'className' => 'TemplateField',
			'foreignKey' => 'template_field_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
