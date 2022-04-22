<?php
/**
 * TimelineEntryFixture
 *
 */
class TimelineEntryFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'merchant_id' => array('type' => 'string', 'null' => false, 'length' => 50),
		'timeline_item' => array('type' => 'string', 'null' => false, 'length' => 50),
		'timeline_date_completed' => array('type' => 'date', 'null' => true),
		'action_flag' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => array('merchant_id', 'timeline_item')),
			'tl_ent_merchant_idx' => array('unique' => false, 'column' => 'merchant_id'),
			'tl_timeline_date_completed' => array('unique' => false, 'column' => 'timeline_date_completed'),
			'tl_timeline_item' => array('unique' => false, 'column' => 'timeline_item')
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
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_date_completed' => '2013-12-31',
			'action_flag' => 1
		),
		array(
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_date_completed' => '2013-12-31',
			'action_flag' => 1
		),
		array(
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_date_completed' => '2013-12-31',
			'action_flag' => 1
		),
		array(
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_date_completed' => '2013-12-31',
			'action_flag' => 1
		),
		array(
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_date_completed' => '2013-12-31',
			'action_flag' => 1
		),
		array(
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_date_completed' => '2013-12-31',
			'action_flag' => 1
		),
		array(
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_date_completed' => '2013-12-31',
			'action_flag' => 1
		),
		array(
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_date_completed' => '2013-12-31',
			'action_flag' => 1
		),
		array(
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_date_completed' => '2013-12-31',
			'action_flag' => 1
		),
		array(
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'timeline_item' => 'Lorem ipsum dolor sit amet',
			'timeline_date_completed' => '2013-12-31',
			'action_flag' => 1
		),
	);

}
