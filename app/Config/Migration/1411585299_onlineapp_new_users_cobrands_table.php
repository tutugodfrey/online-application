<?php
class OnlineappNewUsersCobrandsTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'onlineapp_users_onlineapp_cobrands' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary'
					),
					'user_id' => array(
						'type' => 'integer',
						'null' => false,
					),
					'cobrand_id' => array(
						'type' => 'integer',
						'null' => false,
					),
					'indexes' => array(
						'PRIMARY' => array(
						'column' => 'id',
						'unique' => 1,
						),
					),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'onlineapp_users_onlineapp_cobrands',
			),
		)
	);


/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
                if ($direction == 'down') {
                        $this->db->execute(
                                "ALTER TABLE onlineapp_users_onlineapp_cobrands
                                DROP CONSTRAINT onlineapp_users_onlineapp_cobrands_user_fk;
                                ALTER TABLE onlineapp_users_onlineapp_cobrands
                                DROP CONSTRAINT onlineapp_users_onlineapp_cobrands_cobrand_fk;"
                        );
                }
                return true;
        }

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		if ($direction == 'up') {
                        $this->db->execute(
                                "ALTER TABLE onlineapp_users_onlineapp_cobrands
                                ADD CONSTRAINT onlineapp_users_onlineapp_cobrands_user_fk FOREIGN KEY (user_id) REFERENCES onlineapp_users (id);
                                ALTER TABLE onlineapp_users_onlineapp_cobrands
                                ADD CONSTRAINT onlineapp_users_onlineapp_cobrands_cobrand_fk FOREIGN KEY (cobrand_id) REFERENCES onlineapp_cobrands (id);"
                        );
                }
                return true;
	}
}
