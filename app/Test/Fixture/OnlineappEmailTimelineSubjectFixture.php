<?php
/**
 * OnlineappEmailTimelineSubjectFixture
 *
 */
class OnlineappEmailTimelineSubjectFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'subject' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 40),
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
			'subject' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 2,
			'subject' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 3,
			'subject' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 4,
			'subject' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 5,
			'subject' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 6,
			'subject' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 7,
			'subject' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 8,
			'subject' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 9,
			'subject' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 10,
			'subject' => 'Lorem ipsum dolor sit amet'
		),
	);

}
