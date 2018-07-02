<?php
class AddColumnsToUsersTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_pw_related_fields_to_users';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_users' => array(
					'pw_reset_hash' => array(
						'type' => 'string',
						'length' => 32,
						'default' => null
					),
					'pw_expiry_date' => array(
						'type' => 'date',
						'default' => null
					)
				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_users' => array(
					'pw_reset_hash',
					'pw_expiry_date'
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
		if ($direction === 'up') {
			$User = ClassRegistry::init('User');
			$User->updateAll(
				['pw_expiry_date' => "'" . $User->newPwExpiration() . "'"],
				['active' => true]
			);
			$User->updateAll(
				['pw_expiry_date' => "'1999-01-01'"],
				['active' => false]
			);
			$User->query('ALTER TABLE onlineapp_users ALTER COLUMN pw_expiry_date SET NOT NULL');
		}
		return true;
	}
}
