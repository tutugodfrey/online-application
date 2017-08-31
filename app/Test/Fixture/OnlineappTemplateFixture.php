<?php

class OnlineappTemplateFixture extends CakeTestFixture {
/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false),
		'logo_position' => array('type' => 'integer', 'null' => false, 'default' => '3'),
		'include_brand_logo' => array('type' => 'boolean', 'null' => false, 'default' => true),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'cobrand_id' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'rightsignature_template_guid' => array('type' => 'string', 'null' => true),
		'rightsignature_install_template_guid' => array('type' => 'string', 'null' => true),
		'owner_equity_threshold' => array('type' => 'integer', 'null' => true),
		'requires_coversheet' => array('type' => 'boolean', 'default' => true),
		'email_app_pdf' => array('type' => 'boolean', 'default' => false),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'name' => 'Template 1 for PN1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'cobrand_id' => 1,
			'logo_position' => 0,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'owner_equity_threshold' => 50,
			'requires_coversheet' => false,
			'email_app_pdf' => true
		),
		array(
			'id' => 2,
			'name' => 'Template 2 for PN1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'cobrand_id' => 1,
			'logo_position' => 0,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'owner_equity_threshold' => 50
		),
		array(
			'id' => 3,
			'name' => 'Template 1 for PN2',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'cobrand_id' => 2,
			'logo_position' => 0,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'owner_equity_threshold' => 50
		),
		array(
			'id' => 4,
			'name' => 'Template used to test afterSave of app values',
			'description' => '',
			'cobrand_id' => 2,
			'logo_position' => 0,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'owner_equity_threshold' => 50
		),
		array(
			'id' => 5,
			'name' => 'Template used to test getFields',
			'description' => '',
			'cobrand_id' => 2,
			'logo_position' => 0,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'owner_equity_threshold' => 50
		),
		array(
			'id' => 6,
			'name' => 'Template 1 for Corral',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'cobrand_id' => 4,
			'logo_position' => 0,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'owner_equity_threshold' => 50,
			'email_app_pdf' => true,
		),
	);
}
