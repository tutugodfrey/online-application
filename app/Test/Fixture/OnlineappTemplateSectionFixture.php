<?php
class OnlineappTemplateSectionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'rep_only' => array('type' => 'boolean', 'null' => false, 'default' => false),
		'width' => array('type' => 'integer', 'null' => false, 'default' => '12'),
		'page_id' => array('type' => 'integer', 'null' => false),
		'order' => array('type' => 'integer', 'null' => false),
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
			'name' => 'Page Section 1',
			'width' => 12,
			'rep_only' => false,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'page_id' => 1,
			'order' => 0,
			'created' => '2013-12-18 13:36:11',
			'modified' => '2013-12-18 13:36:11'
		),
		array(
			'name' => 'Page Section 2',
			'width' => 12,
			'rep_only' => false,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'page_id' => 1,
			'order' => 1,
			'created' => '2013-12-18 13:36:11',
			'modified' => '2013-12-18 13:36:11'
		),
		array(
			'name' => 'Page Section 2',
			'width' => 12,
			'rep_only' => false,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'page_id' => 1,
			'order' => 2,
			'created' => '2013-12-18 13:36:11',
			'modified' => '2013-12-18 13:36:11'
		),
		array(
			'name' => 'Page Section 1',
			'width' => 12,
			'rep_only' => false,
			'description' => '',
			'page_id' => 4,
			'order' => 0,
			'created' => '2013-12-18 13:36:11',
			'modified' => '2013-12-18 13:36:11'
		),
	);
}
