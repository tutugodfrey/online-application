<?php
/**
 * OnlineappEmailTimelineFixture
 *
 */
class OnlineappEmailTimelineFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'app_id' => array('type' => 'integer', 'null' => true),
		'date' => array('type' => 'datetime', 'null' => true),
		'email_timeline_subject_id' => array('type' => 'integer', 'null' => true),
		'recipient' => array('type' => 'string', 'null' => true, 'length' => 50),
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
			'app_id' => 1,
			'date' => '2013-12-31 12:29:29',
			'email_timeline_subject_id' => 1,
			'recipient' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 2,
			'app_id' => 2,
			'date' => '2013-12-31 12:29:29',
			'email_timeline_subject_id' => 2,
			'recipient' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 3,
			'app_id' => 3,
			'date' => '2013-12-31 12:29:29',
			'email_timeline_subject_id' => 3,
			'recipient' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 4,
			'app_id' => 4,
			'date' => '2013-12-31 12:29:29',
			'email_timeline_subject_id' => 4,
			'recipient' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 5,
			'app_id' => 5,
			'date' => '2013-12-31 12:29:29',
			'email_timeline_subject_id' => 5,
			'recipient' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 6,
			'app_id' => 6,
			'date' => '2013-12-31 12:29:29',
			'email_timeline_subject_id' => 6,
			'recipient' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 7,
			'app_id' => 7,
			'date' => '2013-12-31 12:29:29',
			'email_timeline_subject_id' => 7,
			'recipient' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 8,
			'app_id' => 8,
			'date' => '2013-12-31 12:29:29',
			'email_timeline_subject_id' => 8,
			'recipient' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 9,
			'app_id' => 9,
			'date' => '2013-12-31 12:29:29',
			'email_timeline_subject_id' => 9,
			'recipient' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 10,
			'app_id' => 10,
			'date' => '2013-12-31 12:29:29',
			'email_timeline_subject_id' => 10,
			'recipient' => 'Lorem ipsum dolor sit amet'
		),
	);

}
