<?php
class Merchant extends AppModel {

	public $useTable = 'merchants';

	public $displayField = 'merchant_dba';

	public $hasMany = array(
		'EquipmentProgramming' => array(
			'className' => 'EquipmentProgramming',
			'foreignKey' => 'merchant_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'TimelineEntry' => array(
			'className' => 'TimelineEntry',
			'foreignKey' => 'merchant_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	/*
	public $validate = array(
		'key' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'value' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'description' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
 */

}
// End of File
