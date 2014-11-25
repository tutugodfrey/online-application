<?php
class OnlineappNewUsersTemplatesTable extends CakeMigration {

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
                        'create_table' => array(
                                'onlineapp_users_onlineapp_templates' => array(
                                        'id' => array(
                                                'type' => 'integer',
                                                'null' => false,
                                                'key' => 'primary'
                                        ),
                                        'user_id' => array(
                                                'type' => 'integer',
                                                'null' => false,
                                        ),
                                        'template_id' => array(
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
                                'onlineapp_users_onlineapp_templates',
                        ),
                )
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
                                "ALTER TABLE onlineapp_users_onlineapp_templates
                                DROP CONSTRAINT onlineapp_users_onlineapp_templates_user_fk;
                                ALTER TABLE onlineapp_users_onlineapp_templates
                                DROP CONSTRAINT onlineapp_users_onlineapp_templates_template_fk;"
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
                                "ALTER TABLE onlineapp_users_onlineapp_templates
                                ADD CONSTRAINT onlineapp_users_onlineapp_templates_user_fk FOREIGN KEY (user_id) REFERENCES onlineapp_users (id);
                                ALTER TABLE onlineapp_users_onlineapp_templates
                                ADD CONSTRAINT onlineapp_users_onlineapp_templates_template_fk FOREIGN KEY (template_id) REFERENCES onlineapp_templates (id);"
                        );
                }
                return true;
	}
}
