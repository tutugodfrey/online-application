<?php
/**
 * OnlineappCobrandedApplicationFixture
 *
 */
class OnlineappCobrandedApplicationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true),
		'template_id' => array('type' => 'integer', 'null' => true),
		'uuid' => array('type' => 'string', 'null' => false, 'length' => 36),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'rightsignature_document_guid' => array('type' => 'string', 'length' => 32, 'null' => true),
		'status' => array('type' => 'string', 'length' => 10, 'null' => true),
		'rightsignature_install_document_guid' => array('type' => 'string', 'length' => 32, 'null' => true),
		'rightsignature_install_status' => array('type' => 'string', 'length' => 10, 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'unique_uuid' => array('unique' => true, 'column' => 'uuid')
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
			'template_id' => 1,
			'uuid' => 'b118ac22d3cd4ab49148b05d5254ed59',
			'created' => '2014-01-24 09:07:08',
			'modified' => '2014-01-24 09:07:08',
			'rightsignature_document_guid' => null,
			'status' => null,
			'rightsignature_install_document_guid' => null,
			'rightsignature_install_status' => null,
		),
	);

}
