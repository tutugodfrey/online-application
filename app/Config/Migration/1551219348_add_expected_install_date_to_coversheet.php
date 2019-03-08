<?php
class AddExpectedInstallDateToCoversheet extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_expected_install_date_to_coversheet';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_coversheets' => array(
					'expected_install_date' => array(
						'type' => 'date',
						'default' => null
					)
				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_coversheets' => array(
					'expected_install_date'
				)
			)
		),
	);
}
