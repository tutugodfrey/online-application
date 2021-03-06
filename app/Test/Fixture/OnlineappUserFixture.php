<?php
/**
 * OnlineappUserFixture
 *
 */
class OnlineappUserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'email' => array('type' => 'string', 'null' => false),
		'password' => array('type' => 'string', 'null' => false, 'length' => 40),
		'group_id' => array('type' => 'integer', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'token' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 40),
		'token_used' => array('type' => 'datetime', 'null' => true),
		'token_uses' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'firstname' => array('type' => 'string', 'null' => true, 'length' => 40),
		'lastname' => array('type' => 'string', 'null' => true, 'length' => 40),
		'extension' => array('type' => 'integer', 'null' => true),
		'active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'api_password' => array('type' => 'string', 'null' => true, 'length' => 200),
		'api_enabled' => array('type' => 'boolean', 'null' => true),
		'template_id' => array('type' => 'integer', 'null' => true),
		'pw_expiry_date' => array('type' => 'datetime', 'null' => false),
		'pw_reset_hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32),
		'wrong_log_in_count' => array('type' => 'integer', 'default' => 0),
		'is_blocked' => array('type' => 'boolean', 'default' => false),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'onlineapp_users_email_key' => array('unique' => true, 'column' => 'email'),
			'token' => array('unique' => true, 'column' => 'token')
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
			'email' => 'testing@axiapayments.com',
			'password' => '0e41ea572d9a80c784935f2fc898ac34649079a9',
			'group_id' => 1,
			'created' => '2014-01-24 11:02:22',
			'modified' => '2014-01-24 11:02:22',
			'token' => 'Lorem ipsum dolor sit amet',
			'token_used' => '2014-01-24 11:02:22',
			'token_uses' => 1,
			'firstname' => 'Lorem ipsum dolor sit amet',
			'lastname' => 'Lorem ipsum dolor sit amet',
			'extension' => 1,
			'active' => 1,
			'api_password' => 'Lorem ipsum dolor sit amet',
			'api_enabled' => 1,
			'template_id' => 1,
			'pw_expiry_date' => '2019-10-01',
			'pw_reset_hash' => null,
			'wrong_log_in_count' => 0,
			'is_blocked' => false
		),
		array(
			'id' => 11,
			'email' => 'testing11@axiapayments.com',
			'password' => '0e41ea572d9a80c784935f2fc898ac34649079a9',
			'group_id' => 1,
			'created' => '2014-01-24 11:02:22',
			'modified' => '2014-01-24 11:02:22',
			'token' => 'tokenForUserId11',
			'token_used' => '2014-01-24 11:02:22',
			'token_uses' => 99,
			'firstname' => 'John',
			'lastname' => 'Doe',
			'extension' => 1,
			'active' => 1,
			'api_password' => 'Lorem ipsum dolor sit amet',
			'api_enabled' => 1,
			'template_id' => 1,
			'pw_expiry_date' => '2019-10-01',
			'pw_reset_hash' => null
		),
		array(
			'id' => 12,
			'email' => 'testing12@axiapayments.com',
			'password' => '0e41ea572d9a80c784935f2fc898ac34649079a9',
			'group_id' => 4,
			'created' => '2014-01-24 11:02:22',
			'modified' => '2014-01-24 11:02:22',
			'token' => 'tokenForUserId12',
			'token_used' => '2014-01-24 11:02:22',
			'token_uses' => 1,
			'firstname' => 'Chew',
			'lastname' => 'Bacca',
			'extension' => 1,
			'active' => 1,
			'api_password' => 'Lorem ipsum dolor sit amet',
			'api_enabled' => 1,
			'template_id' => 1,
			'pw_expiry_date' => '2019-10-01',
			'pw_reset_hash' => null
		),
	);

}
