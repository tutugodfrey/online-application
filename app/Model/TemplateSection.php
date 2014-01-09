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

  public $actsAs = array(
    'Search.Searchable',
    'Containable',
    'Orderable' => array(
      'fields' => array(
        'order',
        'name',
        'width',
        'rep_only',
        'created',
        'modified',
      )
    )
  );

  public $validate = array(
    'name' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => array('Template section name cannot be empty'),
      ),
    ),
    'width' => array(
      'between' => array(
        'rule'    => array('range', 0, 13),
        'message' => array('Invalid width value used, please select a number between 1 and 12'),
      )
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

  private $__neighbors;
  public function beforeSave($options = array()) {
    $this->__neighbors = null;

    // if the order field is not set, figure it out
    if ($this->data['TemplateSection']['order'] === null) {
      $templatePage = $this->TemplatePage->find(
        'first',
        array('conditions' => 'TemplatePage.id = "' . $this->data['TemplateSection']['page_id'] . '"' ),
        array('contain' => array('TemplateSection'))
      );
      $count = count($templatePage['TemplateSections']);

      $this->data['TemplateSection']['order'] = $count;
    }

    if ($this->id) {
      $data = $this->data;
      $this->old = $this->findById($this->id);

      if ($this->old['TemplateSection']['order'] != $data['TemplateSection']['order']) {
        $templatePage = $this->TemplatePage->find(
          'first',
          array('conditions' => 'TemplatePage.id = "' . $this->data['TemplateSection']['page_id'] . '"' ),
          array('contain' => array('TemplateSection'))
        );

        $old_order = $this->old['TemplateSection']['order'];
        $new_order = $data['TemplateSection']['order'];

        // get the templateSections
        $this->__neighbors = $templatePage['TemplateSections'];
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
