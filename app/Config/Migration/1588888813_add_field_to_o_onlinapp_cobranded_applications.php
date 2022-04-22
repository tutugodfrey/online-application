<?php
class AddFieldToOOnlinappCobrandedApplications extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_field_to_o_onlinapp_cobranded_applications';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_cobranded_applications' => array(
					'doc_secret_token' => array(
						'type' => 'string',
						'length' => '200',
					),
				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_cobranded_applications' => array(
					'doc_secret_token'
				)
			)
		),
	);
}
