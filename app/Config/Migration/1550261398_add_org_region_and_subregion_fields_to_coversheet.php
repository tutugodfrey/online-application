<?php
class AddOrgRegionAndSubregionFieldsToCoversheet extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_org_region_and_subregion_fields_to_coversheet';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_coversheets' => array(
					'org_name' => array('type' => 'string', 'length' => 100),
					'region_name' => array('type' => 'string', 'length' => 100),
					'subregion_name' => array('type' => 'string', 'length' => 100),
				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_coversheets' => array(
					'org_name',
					'region_name',
					'subregion_name',
				)
			)
		),
	);
}
