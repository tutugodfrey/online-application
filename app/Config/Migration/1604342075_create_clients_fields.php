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
}
