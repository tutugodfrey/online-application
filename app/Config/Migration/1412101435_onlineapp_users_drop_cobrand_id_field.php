<?php
class OnlineappUsersDropCobrandIdField extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
                        'drop_field' => array(
                                'onlineapp_users' => array(
                                        'cobrand_id'
                                ),
                        ),
		),
		'down' => array(
			'create_field' => array(
                                'onlineapp_users' => array(
                                        'cobrand_id' => array(
                                                'type' => 'integer',
                                                'null' => true,
                                        ),
                                ),
                        ),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function before($direction) {
		if ($direction == 'up') {
                        $this->db->execute(
                                "ALTER TABLE onlineapp_users
                                DROP CONSTRAINT IF EXISTS onlineapp_users_cobrand_fk CASCADE;"
                        );
                }
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function after($direction) {
		if ($direction == 'down') {
                        $this->db->execute(
                                "ALTER TABLE onlineapp_users
                                ADD CONSTRAINT onlineapp_users_cobrand_fk FOREIGN KEY (cobrand_id) REFERENCES onlineapp_cobrands (id);"
                        );
                }
		return true;
	}
}
