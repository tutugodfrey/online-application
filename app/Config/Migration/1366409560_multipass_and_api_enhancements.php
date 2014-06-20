<?php
class MultipassAndApiEnhancements extends CakeMigration {

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
				'onlineapp_applications' => array(
					'callback_url' => array('type' => 'string', 'null' => true, 'after' => 'tickler_id'),
				),
				'onlineapp_users' => array(
					'api_password' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'after' => 'active'),
					'api_enabled' => array('type' => 'boolean', 'null' => true, 'after' => 'api_password'),
				),
			),
			'alter_field' => array(
				'onlineapp_applications' => array(
					'corp_contact_name_title' => array('type' => 'string', 'null' => true, 'length' => 50),
					'term1_type' => array('type' => 'string', 'null' => true, 'length' => 30),
					'term2_type' => array('type' => 'string', 'null' => true, 'length' => 30),
				),
			),
			'create_table' => array(
				'onlineapp_multipasses' => array(
					'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
					'merchant_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 16, 'after' => 'id'),
					'device_number' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 14, 'after' => 'merchant_id'),
					'username' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'after' => 'device_number'),
					'pass' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'after' => 'username'),
					'in_use' => array('type' => 'boolean', 'null' => false, 'default' => 'false', 'after' => 'pass'),
					'application_id' => array('type' => 'integer', 'null' => true, 'after' => 'in_use'),
					'created' => array('type' => 'datetime', 'null' => true, 'after' => 'application_id'),
					'modified' => array('type' => 'datetime', 'null' => true, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('unique' => true, 'column' => 'id'),
						'application_id_key' => array('unique' => true, 'column' => 'application_id'),
						'device_number_key' => array('unique' => true, 'column' => 'device_number'),
						'merchant_id_key' => array('unique' => true, 'column' => 'merchant_id'),
						'username_key' => array('unique' => true, 'column' => 'username'),
					),
					'tableParameters' => array(),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_applications' => array('callback_url',),
				'onlineapp_users' => array('api_password', 'api_enabled'),
			),
			'alter_field' => array(
				'onlineapp_applications' => array(
					'corp_contact_name_title' => array('type' => 'string', 'null' => true, 'length' => 20),
					'term1_type' => array('type' => 'string', 'null' => true, 'length' => 20),
					'term2_type' => array('type' => 'string', 'null' => true, 'length' => 20),
				),
			),
			'create_field' => array(
				'onlineapp_applications' => array(
					'indexes' => array(
						'onlineapp_pkey' => array('unique' => true, 'column' => 'id'),
					),
				),
			),
			'drop_table' => array(
				'onlineapp_multipasses'
			),
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
            $EmailTimelineSubject = ClassRegistry::init('EmailTimelineSubject');
            $Users = ClassRegistry::init('User');
            if ($direction == 'up') {
                $data['EmailTimelineSubject']['subject'] = 'PaymentSpring Multipass Merchant Created';
                $EmailTimelineSubject->create();
                if($EmailTimelineSubject->save($data)) {
                    echo "Email Timeline Subject Added";
                    return true;
                } else {
                    echo "Failed to add Email Timeline Subject";
                    return false;
                }
            } 
		return true;
	}
}
