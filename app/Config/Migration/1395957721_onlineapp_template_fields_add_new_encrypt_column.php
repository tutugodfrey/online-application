<?php
class OnlineappTemplateFieldsAddNewEncryptColumn extends CakeMigration {

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
                                'onlineapp_template_fields' => array(
                                        'encrypt' => array('type' => 'boolean', 'null' => false, 'default' => false),
                                ),
                	),
		),
		'down' => array(
                	'drop_field' => array(
                                'onlineapp_template_fields' => array('encrypt',),
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
