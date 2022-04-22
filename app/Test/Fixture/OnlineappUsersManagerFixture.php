<?php
/**
 * OnlineappUsersManagerFixture
 *
 */
class OnlineappUsersManagerFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true),
		'manager_id' => array('type' => 'integer', 'null' => true),
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
			'id' => '52c32849-9ebc-414c-95e6-7eb634627ad4',
			'user_id' => 1,
			'manager_id' => 1
		),
		array(
			'id' => '52c32849-e570-4911-a252-7eb634627ad4',
			'user_id' => 2,
			'manager_id' => 2
		),
		array(
			'id' => '52c32849-02bc-4063-a4e0-7eb634627ad4',
			'user_id' => 3,
			'manager_id' => 3
		),
		array(
			'id' => '52c32849-1edc-4db1-ab1f-7eb634627ad4',
			'user_id' => 4,
			'manager_id' => 4
		),
		array(
			'id' => '52c32849-3a98-44ac-9b26-7eb634627ad4',
			'user_id' => 5,
			'manager_id' => 5
		),
		array(
			'id' => '52c32849-5c30-4c69-bc90-7eb634627ad4',
			'user_id' => 6,
			'manager_id' => 6
		),
		array(
			'id' => '52c32849-7850-4006-8bb7-7eb634627ad4',
			'user_id' => 7,
			'manager_id' => 7
		),
		array(
			'id' => '52c32849-93a8-4a45-83d5-7eb634627ad4',
			'user_id' => 8,
			'manager_id' => 8
		),
		array(
			'id' => '52c32849-af00-4afa-9cf6-7eb634627ad4',
			'user_id' => 9,
			'manager_id' => 9
		),
		array(
			'id' => '52c32849-cabc-4404-a2f4-7eb634627ad4',
			'user_id' => 10,
			'manager_id' => 10
		),
		array(
			'id' => '2f79e561-660c-485b-aadc-3d60c828dfe7',
			'user_id' => 11,
			'manager_id' => 1
		),
		array(
			'id' => '82b4905e-bb3a-4739-b98b-80c644605f6e',
			'user_id' => 12,
			'manager_id' => 2
		),
	);

}
