<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplatePage Model
 *
 * @property Template $Template
 *
 * TODO: create an OrderableChild base class to handle
 *       before save and some other common logic.  Also
 *       need to apply this change to section and field
 *       models.
 */
class TemplatePage extends AppModel {

  public $displayField = 'name';
  public $useTable = 'onlineapp_template_pages';

  public $validate = array(
    'name' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'required' => true,
        'message' => array('Template page name cannot be empty')
      ),
    ),
    'template_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'required' => true,
        'message' => array('Invalid cobrand_id value used')
      ),
    ),
    'order' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => array('Invalid order value used')
      ),
      'notempty' => array(
        'rule' => array('notempty'),
      ),
    ),
  );

  //The Associations below have been created with all possible keys, those that are not needed can be removed
  public $belongsTo = array(
    'Template' => array(
      'className' => 'Template',
      'foreignKey' => 'template_id',
    )
  );

  public $hasMany = array(
    'TemplateSections' => array(
      'className' => 'TemplateSection',
      'foreignKey' => 'page_id',
      'order' => 'TemplateSections.order',
      'dependent' => true,
    )
  );

  private $__neighbors;
  public function beforeSave($options = array()) {
    $this->__neighbors = null;

    // if the order field is not set, figure it out
    if ($this->data['TemplatePage']['order'] === null) {
      $template = $this->Template->find(
        'first',
        array('conditions' => 'Template.id = "' . $this->data['TemplatePage']['template_id'] . '"' ),
        array('contain' => array('TemplatePage'))
      );
      $count = count($template['TemplatePages']);

      $this->data['TemplatePage']['order'] = $count;
    }

    if ($this->id) {
      $data = $this->data;
      $this->old = $this->findById($this->id);

      if ($this->old['TemplatePage']['order'] != $data['TemplatePage']['order']) {
        $template = $this->Template->find(
          'first',
          array('conditions' => 'Template.id = "' . $this->data['TemplatePage']['template_id'] . '"' ),
          array('contain' => array('TemplatePage'))
        );

        $old_order = $this->old['TemplatePage']['order'];
        $new_order = $data['TemplatePage']['order'];

        // get the templatePages
        $this->__neighbors = $template['TemplatePages'];
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

  public function afterDelete() {
    // TODO: handle delete logic
  }

  public function getCobrand($template_id) {
    // is this the way to access another model?
    $this->Template->id = $template_id;
    $parentTemplate = $this->Template->read();
    return $parentTemplate['Cobrand'];
  }

  public function getTemplate($template_id) {
    $this->Template->id = $template_id;
    $template = $this->Template->read();
    return $template['Template'];
  }
}
