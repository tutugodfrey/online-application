<?php
class Epayment extends AppModel {
       public $useTable = 'onlineapp_epayments';
    
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
?>
