<?php
class OnlineappCoversheetModifications extends CakeMigration {

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
                        'create_field' => array(
                                'onlineapp_coversheets' => array(
                                        'cobranded_application_id' => array('type' => 'integer', 'null' => true),
                                ),
                        ),
			'alter_field' => array(
				'onlineapp_coversheets' => array(
					'onlineapp_application_id' => array('null' => true),
				)
			)
                ),
                'down' => array(
                        'drop_field' => array(
                                'onlineapp_coversheets' => array('cobranded_application_id',),
                        ),
			'alter_field' => array(
				'onlineapp_coversheets' => array(
					'onlineapp_application_id' => array('null' => false),
				)
			)
                ),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function before($direction) {
		if ($direction == 'down') {
                        $this->db->execute(
                                "ALTER TABLE onlineapp_coversheets
                                DROP CONSTRAINT onlineapp_coversheets_cobranded_application_id_fkey;

                                ALTER TABLE onlineapp_coversheets
                                DROP CONSTRAINT onlineapp_coversheets_cobranded_application_id_key;"
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
		if ($direction == 'up') {
                        $this->db->execute(
                                "ALTER TABLE onlineapp_coversheets
                                ADD CONSTRAINT onlineapp_coversheets_cobranded_application_id_fkey FOREIGN KEY (cobranded_application_id) REFERENCES onlineapp_cobranded_applications (id);

                                ALTER TABLE onlineapp_coversheets
                                ADD CONSTRAINT onlineapp_coversheets_cobranded_application_id_key UNIQUE (cobranded_application_id);"
                        );
                }
		return true;
	}
}
