<?php
class ApiLogs extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'merchant' => array(
					'aggregated' => array('type' => 'boolean', 'null' => true, 'after' => 'partner_exclude_volume'),
				),
				'onlineapp_applications' => array(
//					'callback_url' => array('type' => 'string', 'null' => true, 'after' => 'tickler_id'),
					'guid' => array('type' => 'string', 'null' => true, 'length' => 40, 'after' => 'callback_url'),
					'redirect_url' => array('type' => 'string', 'null' => true, 'after' => 'guid'),
				),
			),
			'create_table' => array(
				'onlineapp_api_logs' => array(
					'id' => array('type' => 'string', 'null' => true, 'length' => 36, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => true, 'after' => 'id'),
					'user_token' => array('type' => 'string', 'null' => true, 'length' => 40, 'after' => 'user_id'),
					'ip_address' => array('type' => 'inet', 'null' => true, 'after' => 'user_token'),
					'request_string' => array('type' => 'text', 'null' => true, 'length' => 1073741824, 'after' => 'ip_address'),
					'request_url' => array('type' => 'text', 'null' => true, 'length' => 1073741824, 'after' => 'request_string'),
					'request_type' => array('type' => 'string', 'null' => true, 'length' => 10, 'after' => 'request_url'),
					'created' => array('type' => 'datetime', 'null' => true, 'after' => 'request_type'),
					'auth_status' => array('type' => 'string', 'null' => true, 'length' => 7, 'after' => 'created'),
					'indexes' => array('PRIMARY' => array('unique' => true, 'column' => 'id'),
                                            'onlineapp_api_logs_user_id_key' => array('unique' => false, 'column' => 'user_id'),
					),
					'tableParameters' => array(),
				),
			),
			'alter_field' => array(
				'onlineapp_applications' => array(
					'corp_contact_name_title' => array('type' => 'string', 'null' => true, 'length' => 50),
					'term1_type' => array('type' => 'string', 'null' => true, 'length' => 30),
					'term2_type' => array('type' => 'string', 'null' => true, 'length' => 30),
				),
				'onlineapp_multipasses' => array(
					'merchant_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 16),
					'device_number' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 14),
					'username' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
					'pass' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
				),
				'onlineapp_users' => array(
					'api_password' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
				),
			),
//			'drop_field' => array(
//				'onlineapp_applications' => array('', 'indexes' => array('onlineapp_pkey')),
//			),
		),
		'down' => array(
			'drop_field' => array(
				'merchant' => array('aggregated',),
				'onlineapp_applications' => array('callback_url', 'guid', 'redirect_url',),
			),
			'drop_table' => array(
				'onlineapp_api_logs'
			),
			'alter_field' => array(
				'onlineapp_applications' => array(
					'corp_contact_name_title' => array('type' => 'string', 'null' => true, 'length' => 20),
					'term1_type' => array('type' => 'string', 'null' => true, 'length' => 20),
					'term2_type' => array('type' => 'string', 'null' => true, 'length' => 20),
				),
				'onlineapp_multipasses' => array(
					'merchant_id' => array('type' => 'string', 'null' => true, 'length' => 16),
					'device_number' => array('type' => 'string', 'null' => true, 'length' => 14),
					'username' => array('type' => 'string', 'null' => true, 'length' => 20),
					'pass' => array('type' => 'string', 'null' => true, 'length' => 20),
				),
				'onlineapp_users' => array(
					'api_password' => array('type' => 'string', 'null' => true, 'length' => 50),
				),
			),
//			'create_field' => array(
//				'onlineapp_applications' => array(
//					'indexes' => array(
//						'onlineapp_pkey' => array('unique' => true, 'column' => 'id'),
//					),
//				),
//			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
