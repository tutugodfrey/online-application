<?php
App::uses('AppModel', 'Model');
class SysEventLog extends AppModel {

	public $belongsTo = [
		'EventType' => [
            'className' => 'EventType',
            'foreignKey' => 'event_type_id'
        ],
        'User' => [
            'className' => 'User',
            'foreignKey' => 'user_id'
        ],
	];
	
}
