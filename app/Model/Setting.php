<?php
class Setting extends AppModel {

	public $primaryKey = 'key';

	public $displayField = 'key';

	public $validate = array(
		'key' => array(
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
		'value' => array(
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
		'description' => array(
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
}
?>
