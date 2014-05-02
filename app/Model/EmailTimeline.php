<?php

class EmailTimeline extends AppModel {
    
    public $useTable = 'onlineapp_email_timelines';
    //Timeline Subjects
    const COMPLETE_FIELDS = 1;
    const MERCHANT_SIGNED = 2;
    const MERCHANT_PORTION_COMPLETE = 3;
    const SENT_FOR_SIGNING = 4;
    const HZ_SENT_FOR_SIGNING = 5;
    const HZ_FOLLOWUP = 6;
    const INSTALL_SHEET_VAR = 7;
    const COVERSHEET_TO_UW = 8;
    const MULTIPASS_COMPLETE = 9;
    const NEW_API_APPLICATION = 10;
    
    //Senders and Recipients
    const UNDERWRITING_EMAIL = 'underwriting@axiapayments.com';
    const NEWAPPS_EMAIL = 'newapps@axiapayments.com';
    const HOOZA_EMAIL = 'hooza@axiapayments.com';
    const DATA_ENTRY_EMAIL = 'dataentry@axiapayments.com';
    
    public $validate = array(
        'cobranded_application_id' => array(
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

    public $belongsTo = array(
        'CobrandedApplication' => array(
            'className' => 'CobrandedApplication',
            'foreignKey' => 'cobranded_application_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'EmailTimelineSubject' => array(
            'className' => 'EmailTimelineSubject',
            'foreignKey' => 'email_timeline_subject_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>