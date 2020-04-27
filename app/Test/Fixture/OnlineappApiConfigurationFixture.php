<?php
/**
 * OnlineappApiConfigurationFixture
 *
 */
class OnlineappApiConfigurationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'key' => 'primary'),
		'configuration_name' => array('type' => 'string', 'length' => 150, 'null' => false),
		'auth_type' => array('type' => 'string', 'length' => 150),
		'instance_url' => array('type' => 'string', 'length' => 250),
		'authorization_url' => array('type' => 'string', 'length' => 250),
		'access_token_url' => array('type' => 'string', 'length' => 250),
		'redirect_url' => array('type' => 'string', 'length' => 250),
		'client_id' => array('type' => 'string', 'length' => 250),
		'client_secret' => array('type' => 'string', 'length' => 250),
		'access_token' => array('type' => 'string', 'length' => 250),
		'access_token_lifetime_seconds' => array('type' => 'integer'),
		'refresh_token' => array('type' => 'string', 'length' => 250),
		'issued_at' => array('type' => 'string', 'length' => 100),
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
	public $records = array(
		array(
			'id' => 1,
			'configuration_name' => 'rightsignature test',
			'auth_type' => 'Oauth 2',
			'instance_url' => 'https://axia_rs_test.fakesignature.com',
			'authorization_url' => 'https://axia_rs_test.fakesignature.com/authorize',
			'access_token_url' => 'https://axia_rs_test.fakesignature.com/token',
			'redirect_url' => 'https://axia_rs_test.redirecturl.com/',
			'client_id' => 'tstclientid123',
			'client_secret' => 'tstsecret',
			'access_token' => 'accesstesttoken123abc',
			'access_token_lifetime_seconds' => '9999',
			'refresh_token' => 'refreshtesttoken123abc',
			'issued_at' => '1999999999',
		)
	);

}
