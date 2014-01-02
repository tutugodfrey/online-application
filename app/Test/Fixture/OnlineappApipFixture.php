<?php
/**
 * OnlineappApipFixture
 *
 */
class OnlineappApipFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true),
		'ip_address' => array('type' => 'inet', 'null' => true),
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
			'user_id' => 1,
			'ip_address' => 1
		),
		array(
			'id' => 2,
			'user_id' => 2,
			'ip_address' => 2
		),
		array(
			'id' => 3,
			'user_id' => 3,
			'ip_address' => 3
		),
		array(
			'id' => 4,
			'user_id' => 4,
			'ip_address' => 4
		),
		array(
			'id' => 5,
			'user_id' => 5,
			'ip_address' => 5
		),
		array(
			'id' => 6,
			'user_id' => 6,
			'ip_address' => 6
		),
		array(
			'id' => 7,
			'user_id' => 7,
			'ip_address' => 7
		),
		array(
			'id' => 8,
			'user_id' => 8,
			'ip_address' => 8
		),
		array(
			'id' => 9,
			'user_id' => 9,
			'ip_address' => 9
		),
		array(
			'id' => 10,
			'user_id' => 10,
			'ip_address' => 10
		),
	);

}
