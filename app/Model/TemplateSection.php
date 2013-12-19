<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplateSection Model
 *
 * @property Page $Page
 */
class TemplateSection extends AppModel {

  public $displayField = 'name';
  public $useTable = 'onlineapp_template_sections';

  public $validate = array(
    'name' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => array('Template section name cannot be empty'),
      ),
    ),
    'page_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => array('Invalid page_id value used'),
      ),
    ),
    'order' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => array('Invalid order value used'),
      ),
    ),
  );

  //The Associations below have been created with all possible keys, those that are not needed can be removed
  public $belongsTo = array(
    'TemplatePage' => array(
      'className' => 'TemplatePage',
      'foreignKey' => 'page_id',
    )
  );

  public $hasMany = array(
    'TemplateFields' => array(
      'className' => 'TemplateField',
      'foreignKey' => 'section_id',
      'order' => 'TemplateFields.order',
      'dependent' => true,
    )
  );

  public function beforeSave($options = array()) {
    // if the order field is not set, figure it out
    if ($this->data['TemplateSection']['order'] == null) {
      $templatePage = $this->TemplatePage->find(
        'first',
        array('conditions' => 'TemplatePage.id = "' . $this->data['TemplateSection']['page_id'] . '"' ),
        array('contain' => array('TemplateSection'))
      );
      $count = count($templatePage['TemplateSections']);

      $this->data['TemplateSection']['order'] = $count;
    }

    // TODO: if the order is dirty we may have to change some siblings
  }

  private $__cobrand;
  private $__template;
  private $__templatePage;

  private function __getRelated($templatePage_id) {
    $this->TemplatePage->id = $templatePage_id;
    $parentTemplatePage = $this->TemplatePage->read();

    $this->__template = $parentTemplatePage['Template'];
    $this->__templatePage = $parentTemplatePage['TemplatePage'];

    // look up the cobrand from __template
    $Cobrand = ClassRegistry::init('Cobrand');
    $Cobrand->id = $this->__template['cobrand_id'];
    $myCobrand = $Cobrand->read();
    $this->__cobrand = $myCobrand['Cobrand'];
  }

  public function getCobrand($templatePage_id) {
    if ($this->__cobrand == null) {
      $this->__getRelated($templatePage_id);
    }
    return $this->__cobrand;
  }

  public function getTemplate($templatePage_id) {
    if ($this->__template == null) {
      $this->__getRelated($templatePage_id);
    }
    return $this->__template;
  }

  public function getTemplatePage($templatePage_id) {
    if ($this->__templatePage == null) {
      $this->__getRelated($templatePage_id);
    }
    return $this->__templatePage;
  }
}
