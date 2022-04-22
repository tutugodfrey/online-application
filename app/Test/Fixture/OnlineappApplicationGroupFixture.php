<?php
/**
 * OnlineappApplicationGroupFixture
 *
 */
class OnlineappApplicationGroupFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'onlineapp_application_groups';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'access_token' => array('type' => 'string', 'null' => true, 'length' => 256),
		'token_renew_count' => array('type' => 'integer', 'null' => false, 'default'=> 0),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'token_expiration' => array('type' => 'datetime', 'null' => true),
	 	'client_access_token' => array('type' => 'string', 'length' => 40),
        'client_password' => array('type' => 'string', 'length' => 200),
        'client_pw_expiration' => array('type' => 'date'),
        'client_fail_login_count' => array('type' => 'integer', 'default' => 0),
        'client_account_locked' => array('type' => 'boolean', 'default' => false),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);

/**
 * Records
 *
 * @var array
 */
	//public $records = array();
}
