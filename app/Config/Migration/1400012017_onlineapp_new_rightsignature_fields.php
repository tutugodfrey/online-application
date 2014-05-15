<?php
class OnlineappNewRightsignatureFields extends CakeMigration {

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
                                        'rightsignature_document_guid' => array('type' => 'string', 'null' => true),
                                ),
                                'onlineapp_templates' => array(
                                        'rightsignature_template_guid' => array('type' => 'string', 'null' => true),
                                ),
                        ),
                ),
                'down' => array(
                        'drop_field' => array(
                                'onlineapp_cobranded_applications' => array('rightsignature_document_guid',),
                                'onlineapp_templates' => array('rightsignature_template_guid',),
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
