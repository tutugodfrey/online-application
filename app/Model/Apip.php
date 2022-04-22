<?php
class Apip extends AppModel {

	public $validate = array(
		'ip_address' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);

	public $belongsTo = array(
		'User' => array(
		'className' => 'User',
		'foreignKey' => 'user_id',
		'conditions' => '',
		'fields' => '',
		'order' => '',
		'group' => '',
	));

}
