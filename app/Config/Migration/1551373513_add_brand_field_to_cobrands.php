<?php
class AddBrandFieldToCobrands extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_brand_field_to_cobrands';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_cobrands' => array(
					'brand_name' => array('type' => 'string', 'length' => 100)
				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_cobrands' => array(
					'brand_name'
				)
			)
		),
	);

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		if ($direction === 'up') {
			$axiaMedCobrands = array(
				'Amdee',
				'AxiaMed',
				'Axia Technologies LLC.',
				'Bonafide',
				'Capistrano Valley Christian Schools',
				'Christian Heritage School',
				'Corral',
				'Dexter Chaney',
				'ESI',
				'Experian',
				'EZ Healthcare',
				'FullCount',
				'Gearco',
				'Henry Schein',
				'Office Ally',
				'Paradigm',
				'Payment Fusion',
				'PracticeAuthority',
				'PT Practice Pro',
				'QRS Healthcare Solutions',
				'Relatient',
				'Shortcuts',
				'Tracker',
				'VersaSuite'
			);
			$Cobrand = ClassRegistry::init('Cobrand');

			$Cobrand->updateAll(
				array('brand_name' => "'".$Cobrand->brandNames['Axia Med']."'"),
				array('partner_name' => $axiaMedCobrands)
			);
		}
		return true;
	}
}
