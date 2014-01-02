<?php
/**
 * OnlineappMultipassFixture
 *
 */
class OnlineappMultipassFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
		'merchant_id' => array('type' => 'string', 'null' => true, 'length' => 16),
		'device_number' => array('type' => 'string', 'null' => true, 'length' => 14),
		'username' => array('type' => 'string', 'null' => true, 'length' => 20),
		'pass' => array('type' => 'string', 'null' => true, 'length' => 20),
		'in_use' => array('type' => 'boolean', 'null' => false),
		'application_id' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'application_id_key' => array('unique' => true, 'column' => 'application_id'),
			'device_number_key' => array('unique' => true, 'column' => 'device_number'),
			'merchant_id_key' => array('unique' => true, 'column' => 'merchant_id'),
			'username_key' => array('unique' => true, 'column' => 'username')
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
			'id' => '52c32868-04a4-4b6c-b846-7ec234627ad4',
			'merchant_id' => 'Lorem ipsum do',
			'device_number' => 'Lorem ipsum ',
			'username' => 'Lorem ipsum dolor ',
			'pass' => 'Lorem ipsum dolor ',
			'in_use' => 1,
			'application_id' => 1,
			'created' => '2013-12-31 12:26:16',
			'modified' => '2013-12-31 12:26:16'
		),
		array(
			'id' => '52c32868-8fb4-473f-893b-7ec234627ad4',
			'merchant_id' => 'Lorem ipsum do',
			'device_number' => 'Lorem ipsum ',
			'username' => 'Lorem ipsum dolor ',
			'pass' => 'Lorem ipsum dolor ',
			'in_use' => 1,
			'application_id' => 2,
			'created' => '2013-12-31 12:26:16',
			'modified' => '2013-12-31 12:26:16'
		),
		array(
			'id' => '52c32868-e220-4870-8ec8-7ec234627ad4',
			'merchant_id' => 'Lorem ipsum do',
			'device_number' => 'Lorem ipsum ',
			'username' => 'Lorem ipsum dolor ',
			'pass' => 'Lorem ipsum dolor ',
			'in_use' => 1,
			'application_id' => 3,
			'created' => '2013-12-31 12:26:16',
			'modified' => '2013-12-31 12:26:16'
		),
		array(
			'id' => '52c32868-3234-436a-aa34-7ec234627ad4',
			'merchant_id' => 'Lorem ipsum do',
			'device_number' => 'Lorem ipsum ',
			'username' => 'Lorem ipsum dolor ',
			'pass' => 'Lorem ipsum dolor ',
			'in_use' => 1,
			'application_id' => 4,
			'created' => '2013-12-31 12:26:16',
			'modified' => '2013-12-31 12:26:16'
		),
		array(
			'id' => '52c32868-8d38-4c3a-96df-7ec234627ad4',
			'merchant_id' => 'Lorem ipsum do',
			'device_number' => 'Lorem ipsum ',
			'username' => 'Lorem ipsum dolor ',
			'pass' => 'Lorem ipsum dolor ',
			'in_use' => 1,
			'application_id' => 5,
			'created' => '2013-12-31 12:26:16',
			'modified' => '2013-12-31 12:26:16'
		),
		array(
			'id' => '52c32868-dce8-4d1e-a951-7ec234627ad4',
			'merchant_id' => 'Lorem ipsum do',
			'device_number' => 'Lorem ipsum ',
			'username' => 'Lorem ipsum dolor ',
			'pass' => 'Lorem ipsum dolor ',
			'in_use' => 1,
			'application_id' => 6,
			'created' => '2013-12-31 12:26:16',
			'modified' => '2013-12-31 12:26:16'
		),
		array(
			'id' => '52c32868-31ac-4671-a49b-7ec234627ad4',
			'merchant_id' => 'Lorem ipsum do',
			'device_number' => 'Lorem ipsum ',
			'username' => 'Lorem ipsum dolor ',
			'pass' => 'Lorem ipsum dolor ',
			'in_use' => 1,
			'application_id' => 7,
			'created' => '2013-12-31 12:26:16',
			'modified' => '2013-12-31 12:26:16'
		),
		array(
			'id' => '52c32868-815c-40d9-88f2-7ec234627ad4',
			'merchant_id' => 'Lorem ipsum do',
			'device_number' => 'Lorem ipsum ',
			'username' => 'Lorem ipsum dolor ',
			'pass' => 'Lorem ipsum dolor ',
			'in_use' => 1,
			'application_id' => 8,
			'created' => '2013-12-31 12:26:16',
			'modified' => '2013-12-31 12:26:16'
		),
		array(
			'id' => '52c32868-d238-4734-8fd2-7ec234627ad4',
			'merchant_id' => 'Lorem ipsum do',
			'device_number' => 'Lorem ipsum ',
			'username' => 'Lorem ipsum dolor ',
			'pass' => 'Lorem ipsum dolor ',
			'in_use' => 1,
			'application_id' => 9,
			'created' => '2013-12-31 12:26:16',
			'modified' => '2013-12-31 12:26:16'
		),
		array(
			'id' => '52c32868-224c-4a4d-967f-7ec234627ad4',
			'merchant_id' => 'Lorem ipsum do',
			'device_number' => 'Lorem ipsum ',
			'username' => 'Lorem ipsum dolor ',
			'pass' => 'Lorem ipsum dolor ',
			'in_use' => 1,
			'application_id' => 10,
			'created' => '2013-12-31 12:26:16',
			'modified' => '2013-12-31 12:26:16'
		),
	);

}
