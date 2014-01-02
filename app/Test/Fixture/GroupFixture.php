<?php
/**
 * GroupFixture
 *
 */
class GroupFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'group';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'group_id' => array('type' => 'string', 'null' => false, 'length' => 10),
		'group_description' => array('type' => 'string', 'null' => true, 'length' => 50),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'group_id')
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
			'group_id' => 'Lorem ip',
			'group_description' => 'Lorem ipsum dolor sit amet',
			'active' => 1
		),
		array(
			'group_id' => 'Lorem ip',
			'group_description' => 'Lorem ipsum dolor sit amet',
			'active' => 1
		),
		array(
			'group_id' => 'Lorem ip',
			'group_description' => 'Lorem ipsum dolor sit amet',
			'active' => 1
		),
		array(
			'group_id' => 'Lorem ip',
			'group_description' => 'Lorem ipsum dolor sit amet',
			'active' => 1
		),
		array(
			'group_id' => 'Lorem ip',
			'group_description' => 'Lorem ipsum dolor sit amet',
			'active' => 1
		),
		array(
			'group_id' => 'Lorem ip',
			'group_description' => 'Lorem ipsum dolor sit amet',
			'active' => 1
		),
		array(
			'group_id' => 'Lorem ip',
			'group_description' => 'Lorem ipsum dolor sit amet',
			'active' => 1
		),
		array(
			'group_id' => 'Lorem ip',
			'group_description' => 'Lorem ipsum dolor sit amet',
			'active' => 1
		),
		array(
			'group_id' => 'Lorem ip',
			'group_description' => 'Lorem ipsum dolor sit amet',
			'active' => 1
		),
		array(
			'group_id' => 'Lorem ip',
			'group_description' => 'Lorem ipsum dolor sit amet',
			'active' => 1
		),
	);

}
