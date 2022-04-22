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
		'cobranded_application_id' => array('type' => 'integer', 'null' => true),
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
			'app_id' => null,
			'date' => '2013-12-31 12:29:29',
			'email_timeline_subject_id' => 1,
			'recipient' => 'Lorem ipsum dolor sit amet',
			'cobranded_application_id' => 1,
		)
	);

}
