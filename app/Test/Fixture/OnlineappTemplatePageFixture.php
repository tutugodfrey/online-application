<?php
class OnlineappTemplatePageFixture extends CakeTestFixture {

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
		'template_id' => array('type' => 'integer', 'null' => false),
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
			'id' => 1,
			'name' => 'Page 1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'template_id' => 1,
			'order' => 0,
			'rep_only' => false,
			'created' => '2013-12-18 09:26:45',
			'modified' => '2013-12-18 09:26:45'
		),
		array(
			'id' => 2,
			'name' => 'Page 2',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'template_id' => 1,
			'order' => 1,
			'rep_only' => false,
			'created' => '2013-12-18 09:26:45',
			'modified' => '2013-12-18 09:26:45'
		),
		array(
			'id' => 3,
			'name' => 'Page 3',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'template_id' => 1,
			'order' => 2,
			'rep_only' => false,
			'created' => '2013-12-18 09:26:45',
			'modified' => '2013-12-18 09:26:45'
		),
		array(
			'id' => 4,
			'name' => 'Page 1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'template_id' => 4,
			'order' => 0,
			'rep_only' => false,
			'created' => '2013-12-18 09:26:45',
			'modified' => '2013-12-18 09:26:45'
		),
		array(
			'id' => 5,
			'name' => 'Page 1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'template_id' => 5,
			'order' => 0,
			'rep_only' => false,
			'created' => '2013-12-18 09:26:45',
			'modified' => '2013-12-18 09:26:45'
		),
		array(
			'id' => 6,
			'name' => 'Page 4',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'template_id' => 1,
			'order' => 3,
			'rep_only' => true,
			'created' => '2013-12-18 09:26:45',
			'modified' => '2013-12-18 09:26:45'
		),
	);
}
