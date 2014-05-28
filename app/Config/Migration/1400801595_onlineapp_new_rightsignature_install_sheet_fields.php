<?php
class OnlineappNewRightsignatureInstallSheetFields extends CakeMigration {

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
                                'onlineapp_cobranded_applications' => array(
                                        'rightsignature_install_document_guid' => array('type' => 'string', 'length' => 50, 'null' => true),
                                        'rightsignature_install_status' => array('type' => 'string', 'length' => 10, 'null' => true),
                                ),
                                'onlineapp_templates' => array(
                                        'rightsignature_install_template_guid' => array('type' => 'string', 'length' => 50, 'null' => true),
                                ),
				'merchant' => array(
					'cobranded_application_id' => array('type' => 'integer', 'null' => true),
				),
                        ),
                ),
                'down' => array(
                        'drop_field' => array(
                                'onlineapp_cobranded_applications' => array('rightsignature_install_document_guid', 'rightsignature_install_status'),
                                'onlineapp_templates' => array('rightsignature_install_template_guid',),
                                'merchant' => array('cobranded_application_id',),
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
                if ($direction == 'down') {
                        $this->db->execute(
                                "ALTER TABLE merchant
                                DROP CONSTRAINT merchant_cobranded_application_id_fkey;"
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
                                "ALTER TABLE merchant
                                ADD CONSTRAINT merchant_cobranded_application_id_fkey FOREIGN KEY (cobranded_application_id) REFERENCES onlineapp_cobranded_applications (id);"
                        );
                }
		return true;
	}
}
