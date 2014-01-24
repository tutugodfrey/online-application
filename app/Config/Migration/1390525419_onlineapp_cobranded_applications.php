<?php
class OnlineappCobrandedApplications extends CakeMigration {

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
			'create_table' => array(
				'onlineapp_cobranded_applications' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary',
					),
					'user_id' => array(
						'type' => 'integer',
						'unll' => false,
					),
					'uuid' => array(
						'type' => 'string',
						'null' => false,
						'length' => 36,
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
							'unique' => 1
						),
						'UNIQUE_UUID' => array(
							'column' => 'uuid',
							'unique' => true,
						),
					),
				),
				'onlineapp_cobranded_application_values' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary',
					),
					'cobranded_application_id' => array(
						'type' => 'integer',
						'null' => false,
					),
					'template_field_id' => array(
						'type' => 'integer',
						'null' => false,
					),
					'name' => array(
						'type' => 'string',
						'null' => false,
					),
					'value' => array(
						'type' => 'string',
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
							'unique' => 1
						),
					),
				),
			)
		),
		'down' => array(
			'drop_table' => array(
				'onlineapp_cobranded_application_values',
				'onlineapp_cobranded_applications'
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
		if ($direction == 'down') {
			echo "\nDrop the foreign key relationship for onlineapp_cobranded_application_values and onlineapp_cobranded_applications table\n";
			$CobrandedApplication = ClassRegistry::init('CobrandedApplication');
			$CobrandedApplication->query("
				ALTER TABLE onlineapp_cobranded_application_values
				DROP CONSTRAINT IF EXISTS onlineapp_cobranded_applications_applications_values_fk CASCADE;
				ALTER TABLE onlineapp_cobranded_application_values
				DROP CONSTRAINT IF EXISTS onlineapp_template_fields_applications_values_fk CASCADE;
				ALTER TABLE onlineapp_cobranded_applications
				DROP CONSTRAINT onlineapp_users_cobranded_applications_fk;
			");
			
		}
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
		if ($direction == 'up') {
			echo "\nCreate the foreign key relationship for onlineapp_cobranded_application_values and onlineapp_cobranded_applications\n";
			$CobrandedApplication = ClassRegistry::init('CobrandedApplication');
			$CobrandedApplication->query("
				ALTER TABLE onlineapp_cobranded_application_values
				ADD CONSTRAINT onlineapp_cobranded_applications_applications_values_fk FOREIGN KEY (cobranded_application_id) REFERENCES onlineapp_cobranded_applications (id);
				ALTER TABLE onlineapp_cobranded_application_values
				ADD CONSTRAINT onlineapp_template_fields_applications_values_fk FOREIGN KEY (template_field_id) REFERENCES onlineapp_template_fields (id);
				ALTER TABLE onlineapp_cobranded_applications
				ADD CONSTRAINT onlineapp_users_cobranded_applications_fk FOREIGN KEY (user_id) REFERENCES onlineapp_users (id);
			");
		}
		return true;
	}
}
