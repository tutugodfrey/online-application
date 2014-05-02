<?php

class EmailTimelineSubject extends AppModel {

    public $useTable = 'onlineapp_email_timeline_subjects';
    public $validate = array(
        'subject' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $hasMany = array(
        'EmailTimeline' => array(
            'className' => 'EmailTimeline',
            'foreignKey' => 'email_timeline_subject_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}

?>