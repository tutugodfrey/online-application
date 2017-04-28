<?php
class AddFieldToCobrandedAppsTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_field_to_cobranded_apps_table';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_cobranded_applications' => array(
					'data_to_sync' => array(
						'type' => 'text',
						'default' => null,
					)
				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_cobranded_applications' => array(
					'data_to_sync'
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
