<?php
/**
 * OnlineappApiLogFixture
 *
 */
class OnlineappApiLogFixture extends CakeTestFixture {

	/**
 * Table name
 *
 * @var string
 */
	public $table = 'onlineapp_api_logs';
/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer'),
		'user_token' => array('type' => 'string', 'key' => 'primary'),
		'ip_address' => array('type' => 'string', 'key' => 'primary'),
		'request_string' => array('type' => 'string', 'key' => 'primary'),
		'request_url' => array('type' => 'string', 'key' => 'primary'),
		'request_type' => array('type' => 'string', 'key' => 'primary'),
		'created' => array('type' => 'datetime'),
		'auth_status' => array('type' => 'string', 'key' => 'primary'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);

}
