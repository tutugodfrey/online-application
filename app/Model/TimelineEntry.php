<?php
class TimelineEntry extends AppModel {

	public $primaryKey = 'merchant_id';

	public $displayField = 'timeline_date_completed';

	public $belongsTo = array(
		'Merchant' => array(
			'className' => 'Merchant',
			'foreignKey' => 'merchant_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'TimelineItem' => array(
			'className' => 'TimelineItem',
			'foreignKey' => 'timeline_item',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
