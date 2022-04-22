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
		'data_to_sync' => array('type' => 'text', 'default' => null),
		'api_exported_date' => array('type' => 'datetime', 'null' => true),
		'csv_exported_date' => array('type' => 'datetime', 'null' => true),
		'external_foreign_id' => array('type' => 'string', 'length' => 50),
		'doc_secret_token' => array('type' => 'string', 'length' => 200),
		'client_id_global' => array('type' => 'string', 'length' => 8),
		'client_name_global' => array('type' => 'string', 'length' => 100),
		'sf_opportunity_id' => array('type' => 'string', 'length' => 50),
		'application_group_id' => array('type' => 'integer'),
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
		array(
			'template_id' => 1,
			'uuid' => 'c118ac22d3cd4ab49148b05d5254ed59',
			'created' => '2014-01-24 09:07:08',
			'modified' => '2014-01-24 09:07:08',
			'rightsignature_document_guid' => null,
			'status' => 'signed',
			'rightsignature_install_document_guid' => null,
			'rightsignature_install_status' => null,
		),
		array(
			'template_id' => 6,
			'uuid' => 'd118ac22d3cd4ab49148b05d5254ed59',
			'created' => '2014-01-24 09:07:08',
			'modified' => '2014-01-24 09:07:08',
			'rightsignature_document_guid' => null,
			'status' => 'signed',
			'rightsignature_install_document_guid' => null,
			'rightsignature_install_status' => null,
		),
		array(
			'template_id' => 6,
			'uuid' => 'e118ac22d3cd4ab49148b05d5254ed59',
			'created' => '2014-01-24 09:07:08',
			'modified' => '2014-01-24 09:07:08',
			'rightsignature_document_guid' => 'AU7WN8IYL3TRHH56RVU93P',
			'status' => 'signed',
			'rightsignature_install_document_guid' => null,
			'rightsignature_install_status' => null,
		)
	);

}
