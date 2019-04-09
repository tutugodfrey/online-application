<?php
class AddExportedDateFieldToCobrandedApplications extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_exported_date_field_to_cobranded_applications';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_cobranded_applications' => array(
					'api_exported_date' => array(
						'type' => 'datetime',
						'null' => true
					),
					'csv_exported_date' => array(
						'type' => 'datetime',
						'null' => true
					),
				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_cobranded_applications' => array(
					'api_exported_date',
					'csv_exported_date',
				)
			)
		),
	);

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
