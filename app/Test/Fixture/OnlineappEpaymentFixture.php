<?php
/**
 * OnlineappEpaymentFixture
 *
 */
class OnlineappEpaymentFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'pin' => array('type' => 'integer', 'null' => false),
		'application_id' => array('type' => 'integer', 'null' => false),
		'merchant_id' => array('type' => 'string', 'null' => true, 'length' => 40),
		'user_id' => array('type' => 'integer', 'null' => true),
		'onlineapp_application_id' => array('type' => 'integer', 'null' => true),
		'date_boarded' => array('type' => 'datetime', 'null' => false),
		'date_retrieved' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'onlineapp_epayments_merchant_id_key' => array('unique' => true, 'column' => 'merchant_id'),
			'onlineapp_epayments_onlineapp_applications_id_key' => array('unique' => true, 'column' => 'onlineapp_application_id')
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
			'pin' => 1,
			'application_id' => 1,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'user_id' => 1,
			'onlineapp_application_id' => 1,
			'date_boarded' => '2013-12-31 12:23:39',
			'date_retrieved' => '2013-12-31 12:23:39'
		),
		array(
			'id' => 2,
			'pin' => 2,
			'application_id' => 2,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'user_id' => 2,
			'onlineapp_application_id' => 2,
			'date_boarded' => '2013-12-31 12:23:39',
			'date_retrieved' => '2013-12-31 12:23:39'
		),
		array(
			'id' => 3,
			'pin' => 3,
			'application_id' => 3,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'user_id' => 3,
			'onlineapp_application_id' => 3,
			'date_boarded' => '2013-12-31 12:23:39',
			'date_retrieved' => '2013-12-31 12:23:39'
		),
		array(
			'id' => 4,
			'pin' => 4,
			'application_id' => 4,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'user_id' => 4,
			'onlineapp_application_id' => 4,
			'date_boarded' => '2013-12-31 12:23:39',
			'date_retrieved' => '2013-12-31 12:23:39'
		),
		array(
			'id' => 5,
			'pin' => 5,
			'application_id' => 5,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'user_id' => 5,
			'onlineapp_application_id' => 5,
			'date_boarded' => '2013-12-31 12:23:39',
			'date_retrieved' => '2013-12-31 12:23:39'
		),
		array(
			'id' => 6,
			'pin' => 6,
			'application_id' => 6,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'user_id' => 6,
			'onlineapp_application_id' => 6,
			'date_boarded' => '2013-12-31 12:23:39',
			'date_retrieved' => '2013-12-31 12:23:39'
		),
		array(
			'id' => 7,
			'pin' => 7,
			'application_id' => 7,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'user_id' => 7,
			'onlineapp_application_id' => 7,
			'date_boarded' => '2013-12-31 12:23:39',
			'date_retrieved' => '2013-12-31 12:23:39'
		),
		array(
			'id' => 8,
			'pin' => 8,
			'application_id' => 8,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'user_id' => 8,
			'onlineapp_application_id' => 8,
			'date_boarded' => '2013-12-31 12:23:39',
			'date_retrieved' => '2013-12-31 12:23:39'
		),
		array(
			'id' => 9,
			'pin' => 9,
			'application_id' => 9,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'user_id' => 9,
			'onlineapp_application_id' => 9,
			'date_boarded' => '2013-12-31 12:23:39',
			'date_retrieved' => '2013-12-31 12:23:39'
		),
		array(
			'id' => 10,
			'pin' => 10,
			'application_id' => 10,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'user_id' => 10,
			'onlineapp_application_id' => 10,
			'date_boarded' => '2013-12-31 12:23:39',
			'date_retrieved' => '2013-12-31 12:23:39'
		),
	);

}
