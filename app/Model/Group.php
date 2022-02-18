<?php
class Group extends AppModel {

	public $recursive = -1;

	public $validate = array(
	'name' => array(
		'notBlank' => array(
			'rule' => array('notBlank'),
		),
		'input_has_only_valid_chars' => array(
            'rule' => array('inputHasOnlyValidChars'),
            'message' => 'Special characters (i.e "<>`()[]"... etc) are not permitted!',
            'required' => false,
            'allowEmpty' => true,
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
