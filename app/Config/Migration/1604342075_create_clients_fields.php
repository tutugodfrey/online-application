<?php
class CreateClientsFields extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'create_clients_fields';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_cobranded_applications' => array(
					'client_id_global' => array(
						'type' => 'string',
						'length' => '8',
						'null' => true,
						'default' => null
					),
					'client_name_global' => array(
						'type' => 'string',
						'length' => '100',
						'null' => true,
						'default' => null
					),
				),
				'onlineapp_templates' => array(
					'require_client_data' => array(
						'type' => 'boolean',
						'default' => false
					)
				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_cobranded_applications' => array(
					'client_id_global',
					'client_name_global'
				),
				'onlineapp_templates' => array(
					'require_client_data'
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
		if ($direction == 'up') {
			$Template = ClassRegistry::init('Template');
			//Back up old template guids

			$Template->updateAll(
				['require_client_data' => true],
				['id' => array(168,195,163,130,162,174,172,173,183,187,135,157,129,155,177,134,118,170,124,156,127,113,110,120,179,192)]
			);
		}
		return true;
	}
}
