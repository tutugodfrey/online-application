<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplateField Model
 *
 */
class TemplateField extends AppModel {

  public $displayName = 'name';
  public $useTable = 'onlineapp_template_fields';

  public $validate = array(
    'name' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => array('Template field name cannot be empty'),
      ),
    ),
    'type' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => array('Template field type cannot be empty'),
      ),
    ),
    'required' => array(
      'boolean' => array(
        'rule' => array('boolean'),
        'message' => array('Invalid required value used'),
      ),
    ),
    'source' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => array('Template field source cannot be empty'),
      ),
    ),
    'merge_field_name' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => array('Template field merge_field_name cannot be empty'),
      ),
    ),
    'order' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => array('Invalid order value used'),
      ),
    ),
    'section_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => array('Invalid section_id value used'),
      ),
    ),
  );

  //The Associations below have been created with all possible keys, those that are not needed can be removed
  public $belongsTo = array(
    'TemplateSection' => array(
      'className' => 'TemplateSection',
      'foreignKey' => 'section_id',
    )
  );

  public function beforeSave($options = array()) {
    // if the order field is not set, figure it out
    if (empty($this->data['TemplateField']['order'])) {
      $templateSection = $this->TemplateSection->find(
        'first',
        array('conditions' => 'TemplateSection.id = "' . $this->data['TemplateField']['section_id'] . '"' ),
        array('contain' => array('TemplateField'))
      );
      $count = count($templateSection['TemplateFields']);

      $this->data['TemplateField']['order'] = $count;
    }

    // TODO: if the order is dirty we may have to change some siblings
  }

  private $__cobrand;
  private $__template;
  private $__templatePage;
  private $__templateSection;

  private function getRelated($template_section_id) {
    $this->TemplateSection->id = $template_section_id;
    $parentTemplateSection = $this->TemplateSection->read();

    $this->__templatePage = $parentTemplateSection['TemplatePage'];
    $this->__templateSection = $parentTemplateSection['TemplateSection'];

    // look up the template
    $Template = ClassRegistry::init('Template');
    $Template->id = $this->__templatePage['template_id'];
    $myTemplate = $Template->read();
    $this->__template = $myTemplate['Template'];

    // look up the cobrand
    $Cobrand = ClassRegistry::init('Cobrand');
    $Cobrand->id = $this->__template['cobrand_id'];
    $myCobrand = $Cobrand->read();
    $this->__cobrand = $myCobrand['Cobrand'];
  }

  public function getCobrand($template_section_id) {
    if ($this->__cobrand == null) {
      $this->getRelated($template_section_id);
    }
    return $this->__cobrand;
  }

  public function getTemplate($template_section_id) {
    if ($this->__template == null) {
      $this->getRelated($template_section_id);
    }
    return $this->__template;
  }

  public function getTemplatePage($template_section_id) {
    if ($this->__templatePage == null) {
      $this->getRelated($template_section_id);
    }
    return $this->__templatePage;
  }

  public function getTemplateSection($template_section_id) {
    if ($this->__templateSection == null) {
      $this->getRelated($template_section_id);
    }
    return $this->__templateSection;
  }
}
