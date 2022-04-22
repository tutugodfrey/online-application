<?php
/**
 * TimelineItemFixture
 *
 */
class TimelineItemFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'timeline_item';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'timeline_item' => array('type' => 'string', 'null' => false, 'length' => 50),
		'timeline_item_description' => array('type' => 'string', 'null' => true, 'length' => 100),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'timeline_item')
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
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_item_description' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_item_description' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_item_description' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_item_description' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_item_description' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_item_description' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_item_description' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_item_description' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_item_description' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_item_description' => 'Lorem ipsum dolor sit amet'
		),
	);

}
