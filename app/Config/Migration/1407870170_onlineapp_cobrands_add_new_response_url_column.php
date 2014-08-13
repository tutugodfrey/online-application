<?php
class OnlineappCobrandsAddNewResponseUrlColumn extends CakeMigration {

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
                                'onlineapp_cobrands' => array(
                                        'response_url_type' => array('type' => 'integer'),
                                ),
                        ),
                ),
                'down' => array(
                        'drop_field' => array(
                                'onlineapp_cobrands' => array('response_url_type',),
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
