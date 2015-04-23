<?php
class OnlineappNewCoversheetFields extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'onlineapp_new_coversheet_fields';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
                        'create_field' => array(
                                'onlineapp_coversheets' => array(
                                        'setup_referrer_pct_profit' => array('type' => 'number', 'null' => true, 'length' => 5),
                                        'setup_referrer_pct_volume' => array('type' => 'number', 'null' => true, 'length' => 5),
                                        'setup_referrer_pct_gross' => array('type' => 'number', 'null' => true, 'length' => 5),
                                        'setup_reseller_pct_profit' => array('type' => 'number', 'null' => true, 'length' => 5),
                                        'setup_reseller_pct_volume' => array('type' => 'number', 'null' => true, 'length' => 5),
                                        'setup_reseller_pct_gross' => array('type' => 'number', 'null' => true, 'length' => 5),
                                        'setup_partner' => array('type' => 'string', 'null' => true, 'length' => 20),
                                        'setup_partner_pct_profit' => array('type' => 'number', 'null' => true, 'length' => 5),
                                        'setup_partner_pct_volume' => array('type' => 'number', 'null' => true, 'length' => 5),
                                        'setup_partner_pct_gross' => array('type' => 'number', 'null' => true, 'length' => 5),
                                        'gateway_retail_swipe' => array('type' => 'string', 'null' => true, 'length' => 10),
                                ),
                        ),
                ),
                'down' => array(
                        'drop_field' => array(
                                'onlineapp_coversheets' => array(
					'setup_referrer_pct_profit',
					'setup_referrer_pct_volume',
					'setup_referrer_pct_gross',
					'setup_reseller_pct_profit',
					'setup_reseller_pct_volume',
					'setup_reseller_pct_gross',
					'setup_partner',
					'setup_partner_pct_profit',
					'setup_partner_pct_volume',
					'setup_partner_pct_gross',
					'gateway_retail_swipe',
				),
                        ),
                ),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
