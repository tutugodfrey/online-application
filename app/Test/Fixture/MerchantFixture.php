<?php
/**
 * MerchantFixture
 *
 */
class MerchantFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'merchants';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'onlineapp_application_id' => array('type' => 'integer', 'null' => false),
		'cobranded_application_id' => array('type' => 'integer', 'null' => false),
		'user_id' => array('type' => 'string', 'null' => false),
		'merchant_mid' => array('type' => 'string', 'null' => false),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
}
