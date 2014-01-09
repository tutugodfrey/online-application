<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplateField Model
 *
 */
class TemplateField extends AppModel {

  public $field_types = array('text', 'date', 'time', 'checkbox', 'radio', 'percents');
  public $source_types = array('api', 'user');
  public $required_types = array('no', 'yes');

  public $displayName = 'name';
  public $useTable = 'onlineapp_template_fields';

  public $actsAs = array(
    'Search.Searchable',
    'Containable',
    'Orderable' => array(
      'fields' => array(
        'order',
        'name',
        'width',
        'type',
        'required',
        'source',
        'default_value',
        'merge_field_name',
        'created',
        'modified',
      )
    )
  );

  public $validate = array(
    'name' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => array('Template field name cannot be empty'),
      ),
    ),
    'width' => array(
      'between' => array(
        'rule'    => array('range', 0, 13),
        'message' => array('Invalid width value used, please select a number between 1 and 12'),
      )
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

  private $__neighbors;
  public function beforeSave($options = array()) {
    // if the order field is not set, figure it out
    if ($this->data['TemplateField']['order'] === null) {
      $templateSection = $this->TemplateSection->find(
        'first',
        array('conditions' => 'TemplateSection.id = "' . $this->data['TemplateField']['section_id'] . '"' ),
        array('contain' => array('TemplateField'))
      );
      $count = count($templateSection['TemplateFields']);

      $this->data['TemplateField']['order'] = $count;
    }
    if ($this->id) {
      $data = $this->data;
      $this->old = $this->findById($this->id);

      if ($this->old['TemplateField']['order'] != $data['TemplateField']['order']) {
        $templateSection = $this->TemplateSection->find(
          'first',
          array('conditions' => 'TemplateSection.id = "' . $this->data['TemplateField']['section_id'] . '"' ),
          array('contain' => array('TemplateField'))
        );

        $old_order = $this->old['TemplateField']['order'];
        $new_order = $data['TemplateField']['order'];

        // get the templateFields
        $this->__neighbors = $templateSection['TemplateFields'];
        $moving_item = array_splice($this->__neighbors, $old_order, 1);
        array_splice($this->__neighbors, $new_order, 0, $moving_item);

        // rebase the template pages
        for ($i = 0; $i < count($this->__neighbors); $i++) {
          $this->__neighbors[$i]['order'] = $i;
        }

        // remove the $moving_item from the array
        for ($i = 0; $i < count($this->__neighbors); $i++) {
          if ($this->__neighbors[$i]['id'] == $moving_item[0]['id']) {
            unset($this->__neighbors[$i]);
          }
        }
      }
    }

    return true;
  }

  public function afterSave($created, $options = array()) {
    if ($this->__neighbors != null) {
      if (count($this->__neighbors) > 0) {
        foreach ($this->__neighbors as $neighbor) {
          $this->save($neighbor, array('callbacks' => false));
        }
      }
    }
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
