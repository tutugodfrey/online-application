<?php
class AddOpportunityId extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_opportunity_id';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_cobranded_applications' => array(
					'sf_opportunity_id' => array(
						'type' => 'string',
						'length' => 50
					)
				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_cobranded_applications' => array(
					'sf_opportunity_id' 
				)
			)
		),
	);
}
