<?php
class OnlineappBrandCobrandLogoUrlColumns extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'onlineapp_brand_cobrand_logo_url_columns';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_cobrands' => array(
					'brand_logo_url' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 255),
				),
			),
			'rename_field' => array(
				'onlineapp_cobrands' => array(
					'logo_url' => 'cobrand_logo_url',
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_cobrands' => array('brand_logo_url',),
			),
			'rename_field' => array(
				'onlineapp_cobrands' => array(
					'cobrand_logo_url' => 'logo_url',
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
