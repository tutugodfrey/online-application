<?php
class EquipmentProgramming extends AppModel {
	public $useTable = 'equipment_programming';
	public $primaryKey = 'programming_id';
	public $displayField = 'merchant_dba';
        public $belongsTo = array(
        'Merchant' => array(
            'className' => 'Merchant',
            'foreignKey' => 'merchant_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
?>