<?php
class ApiLog extends AppModel {

	public $belongsTo = 'User';

	public $actsAs = array('Cryptable' => array(
		'fields' => array('request_string')
	));
}
