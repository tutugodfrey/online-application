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
                                        'rightsignature_install_document_guid' => array('type' => 'string', 'length' => 32, 'null' => true),
                                        'rightsignature_install_status' => array('type' => 'string', 'length' => 10, 'null' => true),
                                ),
                                'onlineapp_templates' => array(
                                        'rightsignature_install_template_guid' => array('type' => 'string', 'length' => 32, 'null' => true),
                                ),
                        ),
                ),
                'down' => array(
                        'drop_field' => array(
                                'onlineapp_cobranded_applications' => array('rightsignature_install_document_guid', 'rightsignature_install_status'),
                                'onlineapp_templates' => array('rightsignature_install_template_guid',),
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
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function after($direction) {
		return true;
	}
}
