<?php
class TimelineItem extends AppModel {

	public $primaryKey = 'timeline_item';

	public $displayField = 'timeline_item_description';

	public $belongsTo = array(
		'TimelineEntry' => array(
			'className' => 'TimelineEntry',
			'foreignKey' => 'timeline_item',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
