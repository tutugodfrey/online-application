<?php
class OnlineappAddFieldTemplates extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'onlineapp_add_';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_templates' => array(
					'requires_coversheet' => array('type' => 'boolean', 'default' => true, 'after' => 'include_brand_logo')),
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_templates' => array(
					'requires_coversheet'
				),
			),
		)
	);
}
