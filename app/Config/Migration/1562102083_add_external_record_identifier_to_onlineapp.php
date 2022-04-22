<?php
class AddExternalRecordIdentifierToOnlineapp extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_external_record_identifier_to_onlineapp';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_cobranded_applications' => array(
					'external_foreign_id' => array(
						'type' => 'string',
						'length' => 50
					)
				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_cobranded_applications' => array(
					'external_foreign_id' 
				)
			)
		),
	);

}