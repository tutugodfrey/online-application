<?php
/**
 * EquipmentProgrammingFixture
 *
 */
class EquipmentProgrammingFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'equipment_programming';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'programming_id' => array('type' => 'integer', 'null' => false),
		'merchant_id' => array('type' => 'string', 'null' => false, 'length' => 50),
		'terminal_number' => array('type' => 'string', 'null' => true, 'length' => 20),
		'hardware_serial' => array('type' => 'string', 'null' => true, 'length' => 20),
		'terminal_type' => array('type' => 'string', 'null' => true, 'length' => 20),
		'network' => array('type' => 'string', 'null' => true, 'length' => 20),
		'provider' => array('type' => 'string', 'null' => true, 'length' => 20),
		'app_id' => array('type' => 'string', 'null' => true, 'length' => 20),
		'status' => array('type' => 'string', 'null' => true, 'length' => 5),
		'date_entered' => array('type' => 'date', 'null' => true),
		'date_changed' => array('type' => 'date', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'serial_number' => array('type' => 'string', 'null' => true, 'length' => 20),
		'pin_pad' => array('type' => 'string', 'null' => true, 'length' => 20),
		'printer' => array('type' => 'string', 'null' => true, 'length' => 20),
		'auto_close' => array('type' => 'string', 'null' => true, 'length' => 20),
		'chain' => array('type' => 'string', 'null' => true, 'length' => 6),
		'agent' => array('type' => 'string', 'null' => true, 'length' => 6),
		'gateway_id' => array('type' => 'integer', 'null' => true),
		'version' => array('type' => 'string', 'null' => true, 'length' => 20),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'programming_id'),
			'equipment_programming_appid' => array('unique' => false, 'column' => 'app_id'),
			'equipment_programming_merch_idx' => array('unique' => false, 'column' => 'merchant_id'),
			'equipment_programming_serialnum' => array('unique' => false, 'column' => 'serial_number'),
			'equipment_programming_status' => array('unique' => false, 'column' => 'status'),
			'equipment_programming_userid' => array('unique' => false, 'column' => 'user_id')
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
			'programming_id' => 1,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'terminal_number' => 'Lorem ipsum dolor ',
			'hardware_serial' => 'Lorem ipsum dolor ',
			'terminal_type' => 'Lorem ipsum dolor ',
			'network' => 'Lorem ipsum dolor ',
			'provider' => 'Lorem ipsum dolor ',
			'app_id' => 'Lorem ipsum dolor ',
			'status' => 'Lor',
			'date_entered' => '2013-12-31',
			'date_changed' => '2013-12-31',
			'user_id' => 1,
			'serial_number' => 'Lorem ipsum dolor ',
			'pin_pad' => 'Lorem ipsum dolor ',
			'printer' => 'Lorem ipsum dolor ',
			'auto_close' => 'Lorem ipsum dolor ',
			'chain' => 'Lore',
			'agent' => 'Lore',
			'gateway_id' => 1,
			'version' => 'Lorem ipsum dolor '
		),
		array(
			'programming_id' => 2,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'terminal_number' => 'Lorem ipsum dolor ',
			'hardware_serial' => 'Lorem ipsum dolor ',
			'terminal_type' => 'Lorem ipsum dolor ',
			'network' => 'Lorem ipsum dolor ',
			'provider' => 'Lorem ipsum dolor ',
			'app_id' => 'Lorem ipsum dolor ',
			'status' => 'Lor',
			'date_entered' => '2013-12-31',
			'date_changed' => '2013-12-31',
			'user_id' => 2,
			'serial_number' => 'Lorem ipsum dolor ',
			'pin_pad' => 'Lorem ipsum dolor ',
			'printer' => 'Lorem ipsum dolor ',
			'auto_close' => 'Lorem ipsum dolor ',
			'chain' => 'Lore',
			'agent' => 'Lore',
			'gateway_id' => 2,
			'version' => 'Lorem ipsum dolor '
		),
		array(
			'programming_id' => 3,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'terminal_number' => 'Lorem ipsum dolor ',
			'hardware_serial' => 'Lorem ipsum dolor ',
			'terminal_type' => 'Lorem ipsum dolor ',
			'network' => 'Lorem ipsum dolor ',
			'provider' => 'Lorem ipsum dolor ',
			'app_id' => 'Lorem ipsum dolor ',
			'status' => 'Lor',
			'date_entered' => '2013-12-31',
			'date_changed' => '2013-12-31',
			'user_id' => 3,
			'serial_number' => 'Lorem ipsum dolor ',
			'pin_pad' => 'Lorem ipsum dolor ',
			'printer' => 'Lorem ipsum dolor ',
			'auto_close' => 'Lorem ipsum dolor ',
			'chain' => 'Lore',
			'agent' => 'Lore',
			'gateway_id' => 3,
			'version' => 'Lorem ipsum dolor '
		),
		array(
			'programming_id' => 4,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'terminal_number' => 'Lorem ipsum dolor ',
			'hardware_serial' => 'Lorem ipsum dolor ',
			'terminal_type' => 'Lorem ipsum dolor ',
			'network' => 'Lorem ipsum dolor ',
			'provider' => 'Lorem ipsum dolor ',
			'app_id' => 'Lorem ipsum dolor ',
			'status' => 'Lor',
			'date_entered' => '2013-12-31',
			'date_changed' => '2013-12-31',
			'user_id' => 4,
			'serial_number' => 'Lorem ipsum dolor ',
			'pin_pad' => 'Lorem ipsum dolor ',
			'printer' => 'Lorem ipsum dolor ',
			'auto_close' => 'Lorem ipsum dolor ',
			'chain' => 'Lore',
			'agent' => 'Lore',
			'gateway_id' => 4,
			'version' => 'Lorem ipsum dolor '
		),
		array(
			'programming_id' => 5,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'terminal_number' => 'Lorem ipsum dolor ',
			'hardware_serial' => 'Lorem ipsum dolor ',
			'terminal_type' => 'Lorem ipsum dolor ',
			'network' => 'Lorem ipsum dolor ',
			'provider' => 'Lorem ipsum dolor ',
			'app_id' => 'Lorem ipsum dolor ',
			'status' => 'Lor',
			'date_entered' => '2013-12-31',
			'date_changed' => '2013-12-31',
			'user_id' => 5,
			'serial_number' => 'Lorem ipsum dolor ',
			'pin_pad' => 'Lorem ipsum dolor ',
			'printer' => 'Lorem ipsum dolor ',
			'auto_close' => 'Lorem ipsum dolor ',
			'chain' => 'Lore',
			'agent' => 'Lore',
			'gateway_id' => 5,
			'version' => 'Lorem ipsum dolor '
		),
		array(
			'programming_id' => 6,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'terminal_number' => 'Lorem ipsum dolor ',
			'hardware_serial' => 'Lorem ipsum dolor ',
			'terminal_type' => 'Lorem ipsum dolor ',
			'network' => 'Lorem ipsum dolor ',
			'provider' => 'Lorem ipsum dolor ',
			'app_id' => 'Lorem ipsum dolor ',
			'status' => 'Lor',
			'date_entered' => '2013-12-31',
			'date_changed' => '2013-12-31',
			'user_id' => 6,
			'serial_number' => 'Lorem ipsum dolor ',
			'pin_pad' => 'Lorem ipsum dolor ',
			'printer' => 'Lorem ipsum dolor ',
			'auto_close' => 'Lorem ipsum dolor ',
			'chain' => 'Lore',
			'agent' => 'Lore',
			'gateway_id' => 6,
			'version' => 'Lorem ipsum dolor '
		),
		array(
			'programming_id' => 7,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'terminal_number' => 'Lorem ipsum dolor ',
			'hardware_serial' => 'Lorem ipsum dolor ',
			'terminal_type' => 'Lorem ipsum dolor ',
			'network' => 'Lorem ipsum dolor ',
			'provider' => 'Lorem ipsum dolor ',
			'app_id' => 'Lorem ipsum dolor ',
			'status' => 'Lor',
			'date_entered' => '2013-12-31',
			'date_changed' => '2013-12-31',
			'user_id' => 7,
			'serial_number' => 'Lorem ipsum dolor ',
			'pin_pad' => 'Lorem ipsum dolor ',
			'printer' => 'Lorem ipsum dolor ',
			'auto_close' => 'Lorem ipsum dolor ',
			'chain' => 'Lore',
			'agent' => 'Lore',
			'gateway_id' => 7,
			'version' => 'Lorem ipsum dolor '
		),
		array(
			'programming_id' => 8,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'terminal_number' => 'Lorem ipsum dolor ',
			'hardware_serial' => 'Lorem ipsum dolor ',
			'terminal_type' => 'Lorem ipsum dolor ',
			'network' => 'Lorem ipsum dolor ',
			'provider' => 'Lorem ipsum dolor ',
			'app_id' => 'Lorem ipsum dolor ',
			'status' => 'Lor',
			'date_entered' => '2013-12-31',
			'date_changed' => '2013-12-31',
			'user_id' => 8,
			'serial_number' => 'Lorem ipsum dolor ',
			'pin_pad' => 'Lorem ipsum dolor ',
			'printer' => 'Lorem ipsum dolor ',
			'auto_close' => 'Lorem ipsum dolor ',
			'chain' => 'Lore',
			'agent' => 'Lore',
			'gateway_id' => 8,
			'version' => 'Lorem ipsum dolor '
		),
		array(
			'programming_id' => 9,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'terminal_number' => 'Lorem ipsum dolor ',
			'hardware_serial' => 'Lorem ipsum dolor ',
			'terminal_type' => 'Lorem ipsum dolor ',
			'network' => 'Lorem ipsum dolor ',
			'provider' => 'Lorem ipsum dolor ',
			'app_id' => 'Lorem ipsum dolor ',
			'status' => 'Lor',
			'date_entered' => '2013-12-31',
			'date_changed' => '2013-12-31',
			'user_id' => 9,
			'serial_number' => 'Lorem ipsum dolor ',
			'pin_pad' => 'Lorem ipsum dolor ',
			'printer' => 'Lorem ipsum dolor ',
			'auto_close' => 'Lorem ipsum dolor ',
			'chain' => 'Lore',
			'agent' => 'Lore',
			'gateway_id' => 9,
			'version' => 'Lorem ipsum dolor '
		),
		array(
			'programming_id' => 10,
			'merchant_id' => 'Lorem ipsum dolor sit amet',
			'terminal_number' => 'Lorem ipsum dolor ',
			'hardware_serial' => 'Lorem ipsum dolor ',
			'terminal_type' => 'Lorem ipsum dolor ',
			'network' => 'Lorem ipsum dolor ',
			'provider' => 'Lorem ipsum dolor ',
			'app_id' => 'Lorem ipsum dolor ',
			'status' => 'Lor',
			'date_entered' => '2013-12-31',
			'date_changed' => '2013-12-31',
			'user_id' => 10,
			'serial_number' => 'Lorem ipsum dolor ',
			'pin_pad' => 'Lorem ipsum dolor ',
			'printer' => 'Lorem ipsum dolor ',
			'auto_close' => 'Lorem ipsum dolor ',
			'chain' => 'Lore',
			'agent' => 'Lore',
			'gateway_id' => 10,
			'version' => 'Lorem ipsum dolor '
		),
	);

}
