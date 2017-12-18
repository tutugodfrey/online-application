<?php

class EmailTimelineSubject extends AppModel {

	public $validate = array(
		'subject' => array(
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
		'EmailTimeline' => array(
			'className' => 'EmailTimeline',
			'foreignKey' => 'email_timeline_subject_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
