<?php
class RenameTablesToMatchConventions extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'rename_tables_to_match_conventions';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'rename_table' => array(
				'onlineapp_users_onlineapp_templates' => 'onlineapp_users_templates',
				'onlineapp_users_onlineapp_cobrands' => 'onlineapp_users_cobrands'
			)
		),
		'down' => array(
			'rename_table' => array(
				'onlineapp_users_templates' => 'onlineapp_users_onlineapp_templates',
				'onlineapp_users_cobrands' => 'onlineapp_users_onlineapp_cobrands'
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
