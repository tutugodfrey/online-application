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
