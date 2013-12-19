<?php
App::uses('AppModel', 'Model');
/**
 * Cobrand Model
 *
 * @property Template $Template
 * @property User $User
 */
class Cobrand extends AppModel {
  public $displayField = 'partner_name';
  public $useTable = 'onlineapp_cobrands';
/**
 * Validation rules
 *
 * @var array
 */
  public $validate = array(
    'partner_name' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'required' => true,
        'message'  => 'Partner name cannot be empty'
      ),
    ),
    'partner_name_short' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'required' => true,
        'message'  => 'Short partner name cannot be empty'
      ),
    ),
  );

  //The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
  public $hasMany = array(
    'Users' => array(
      'className' => 'User',
      'foreignKey' => 'cobrand_id',
      'dependent' => false,
    ),
    'Templates' => array(
      'className' => 'Template',
      'foreignKey' => 'cobrand_id',
      'dependent' => true,
    )
  );

  public function getList() {
    return $this->find('list',
      array('order' => array('Cobrand.partner_name' => 'asc')));
  }
}
