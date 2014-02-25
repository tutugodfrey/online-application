<?php

class OnlineappCobrandFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'partner_name' => array('type' => 'string', 'null' => false),
		'partner_name_short' => array('type' => 'string', 'null' => false),
		'logo_url' => array('type' => 'string', 'null' => true, 'default' => null),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
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
			'partner_name' => 'Partner Name 1',
			'partner_name_short' => 'PN1',
			'logo_url' => 'PN1 logo_url',
			'description' => 'Cobrand "Partner Name 1" description goes here.',
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
		),
		array(
			'id' => 2,
			'partner_name' => 'Partner Name 2',
			'partner_name_short' => 'PN2',
			'logo_url' => 'PN2 logo_url',
			'description' => 'Cobrand "Partner Name 2" description goes here.',
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
		),
		array(
			'id' => 3,
			'partner_name' => 'Partner Name 3',
			'partner_name_short' => 'PN3',
			'logo_url' => 'PN3 logo_url',
			'description' => 'Cobrand "Partner Name 3" description goes here.',
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31'
		)
	);
}
