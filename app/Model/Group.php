<?php
class Group extends AppModel {

	public $recursive = -1;

	public $validate = array(
	'name' => array(
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

	public $hasMany = array(
	'User' => array(
		'className' => 'User',
		'foreignKey' => 'group_id',
		'dependent' => false,
		'conditions' => '',
		'fields' => '',
		'order' => '',
		'limit' => '',
		'offset' => '',
		'exclusive' => '',
		'finderQuery' => '',
		'counterQuery' => ''
	));

}
