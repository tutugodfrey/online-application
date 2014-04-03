<?php
/**
 * OnlineappCobrandedApplicationValueFixture
 *
 */
class OnlineappCobrandedApplicationValueFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'cobranded_application_id' => array('type' => 'integer', 'null' => false),
		'template_field_id' => array('type' => 'integer', 'null' => false),
		'name' => array('type' => 'string', 'null' => false),
		'value' => array('type' => 'string', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
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
			'cobranded_application_id' => 1,
			'template_field_id' => 1,
			'name' => 'Field 1',
			'value' => null,
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 4,
			'name' => 'name1',
			'value' => null,
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 4,
			'name' => 'name2',
			'value' => null,
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 4,
			'name' => 'name3',
			'value' => null,
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 2,
			'name' => 'Encrypt1',
			'value' => null,
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
	);

}
