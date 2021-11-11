<?php
class CreateApplicationGroupsTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'create_application_groups_table';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
            'create_table' => array(
                'onlineapp_application_groups' => array(
                    'id' => array(
                        'type' => 'integer',
                        'null' => false,
                        'key' => 'primary'
                    ),
                    'access_token' => array(
                        'type' => 'string',
                        'null' => false,
                        'length' => '256'
                    ),
                    'token_renew_count ' => array(
                        'type' => 'integer',
                        'null' => false,
                        'default' => 0
                    ),
                    'token_expiration' => array(
                        'type' => 'datetime',
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
                )
            ),
            'create_field' => array(
                'onlineapp_cobranded_applications' => array(
                    'application_group_id' => array(
                        'type' => 'integer',
                    ),
                )
            )
		),
		'down' => array(
            'drop_table' => array(
                'onlineapp_application_groups'
            ),
            'drop_field' => array(
                    'application_group_id' => array(
                )
            )
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
