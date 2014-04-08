<?php
class OnlineappCobrandedApplicationAchesNewTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
                		'onlineapp_cobranded_application_aches' => array(
                                        'id' => array(
                                                'type' => 'integer',
                                                'null' => false,
                                                'key' => 'primary'
                                        ),
					'cobranded_application_id' => array(
                                                'type' => 'integer',
                                                'null' => false
                                        ),
                                        'description' => array(
                                                'type' => 'string',
                                                'null' => true,
                                        ),
                                        'auth_type' => array(
                                                'type' => 'string',
                                                'null' => false,
                                        ),
                                        'routing_number' => array(
                                                'type' => 'string',
                                                'null' => false,
                                        ),
                                        'account_number' => array(
                                                'type' => 'string',
                                                'null' => false,
                                        ),
                                        'bank_name' => array(
                                                'type' => 'string',
                                                'null' => false,
                                        ),
                                        'created' => array(
                                                'type' => 'datetime',
                                                'null' => false,
                                        ),
                                        'modified' => array(
                                                'type' => 'datetime',
                                                'null' => false,
                                        ),
                                        'indexes' => array(
                                                'PRIMARY' => array(
                                                'column' => 'id',
                                                'unique' => 1,
                                                )
                                        )
                                ),
			)
		),
		'down' => array(
			'drop_table' => array(
                                'onlineapp_cobranded_application_aches',
                        ),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function after($direction) {
		return true;
	}
}
