<?php
class ApiLog extends AppModel {
    public $useTable = 'onlineapp_api_logs';
    public $belongsTo = 'User';
    public $actsAs = array('Cryptable' => array(
            'fields' => array('request_string'
                )
        )
        );
}
?>
