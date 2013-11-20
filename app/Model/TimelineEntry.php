<?php
class TimelineEntry extends AppModel {
	public $useTable = 'timeline_entries';
        public $primaryKey = 'merchant_id';
	//public $primaryKeyArray = array('merchant_id', 'timeline_item');
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
?>