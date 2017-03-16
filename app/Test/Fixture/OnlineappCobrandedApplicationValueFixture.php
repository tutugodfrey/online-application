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
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 19,
			'name' => 'email',
			'value' => 'testing@axiapayments.com',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 19,
			'name' => 'Owner1Email',
			'value' => 'testing@axiapayments.com',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 4,
			'name' => 'Owner1Name',
			'value' => 'Owner1NameTest',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 34,
			'name' => 'Owner2Email',
			'value' => 'testing@axiapayments.com',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 35,
			'name' => 'Owner2Name',
			'value' => 'Owner2NameTest',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 36,
			'name' => 'TextField1',
			'value' => 'text field 1',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 37,
			'name' => 'TextField2',
			'value' => 'text field 2',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 38,
			'name' => 'TextField3',
			'value' => 'text field 3',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 39,
			'name' => 'TextField4',
			'value' => 'text field 4',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 40,
			'name' => 'DBA',
			'value' => 'Doing Business As',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 41,
			'name' => 'CorpName',
			'value' => 'Corporate Name',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'template_field_id' => 42,
			'name' => 'CorpContact',
			'value' => 'Corporate Contact',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 3,
			'template_field_id' => 43,
			'name' => 'Terminal2-',
			'value' => 'true',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
	);

}
