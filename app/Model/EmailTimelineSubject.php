<?php

class EmailTimelineSubject extends AppModel {

	public $validate = array(
		'subject' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
			'input_has_only_valid_chars' => array(
	            'rule' => array('inputHasOnlyValidChars'),
	            'message' => 'Special characters (i.e "<>`()[]"... etc) are not permitted!',
	            'required' => true,
	            'allowEmpty' => false,
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
