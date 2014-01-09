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

  private $__parent_model_name = 'Template';
  private $__parent_model_foreign_key_name = 'template_id';

  public $actsAs = array(
    'Search.Searchable',
    'Containable',
    'Orderable' => array(
      'fields' => array(
        'name',
        'order',
        'created',
        'modified',
      )
    )
  );

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

  public function beforeSave($options = array()) {
    $this->__neighbors = null;

    // if the order field is not set, figure it out
    if ($this->data[get_class()]['order'] === null) {
      $template = $this->Template->find(
        'first',
        array('conditions' => $this->__parent_model_name . '.id = "' . $this->data[get_class()][$this->__parent_model_foreign_key_name] . '"' ),
        array('contain' => array(get_class()))
      );

      $count = count($template[Inflector::pluralize(get_class())]);
      $this->data[get_class()]['order'] = $count;
    }

    // if we have an id, assume edit case
    if ($this->id) {
      $data = $this->data;
      $this->old = $this->findById($this->id);

      // see if the order value has changed
      if ($this->old[get_class()]['order'] != $data[get_class()]['order']) {
        $template = $this->Template->find(
          'first',
          array('conditions' => $this->__parent_model_name . '.id = "' . $this->data[get_class()][$this->__parent_model_foreign_key_name] . '"'),
          array('contain' => array(get_class()))
        );

        $old_order = $this->old[get_class()]['order'];
        $new_order = $data[get_class()]['order'];

        // get the templatePages
        $this->__neighbors = $template[Inflector::pluralize(get_class())];
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

  public function beforeDelete() {
    $templatePage = $this->read();
    $this->__parent_id = $templatePage[$this->__parent_model_name]['id'];
  }

  public function afterDelete() {
    // if the neighbors are not in sequence (0, 1, 3, 4)
    // reorder the 'orders'
    $template = $this->Template->find(
      'first',
      array('conditions' => $this->__parent_model_name . '.id = "' . $this->__parent_id . '"'),
      array('contain' => array(get_class()))
    );
    $this->__neighbors = $template[Inflector::pluralize(get_class())];

    $rebase_needed = false;
    for ($i = 0; $i < count($this->__neighbors); $i++) {
      if ($this->__neighbors[$i]['order'] != $i) {
        $this->__neighbors[$i]['order'] = $i;
        $rebase_needed = true;
      }
    }

    if ($rebase_needed == true) {
      foreach ($this->__neighbors as $neighbor) {
        $this->save($neighbor, array('callbacks' => false));
      }
    }
  }

  public function getCobrand($template_id = null) {
    // check if we already have data
    if (is_array($this->data) && count($this->data) > 0 && array_key_exists(get_class(), $this->data)) {
      $template_id = $this->data[get_class()][$this->__parent_model_foreign_key_name];
    }
    // look it up
    $parentTemplate = $this->Template->findById($template_id);
    $cobrand = $parentTemplate['Cobrand'];

    // is this the way to access another model?
    return $cobrand;
  }

  public function getTemplate($template_id, $include_assc = false) {
    $this->Template->id = $template_id;
    $template = $this->Template->read();

    return $include_assc ? $template : $template[$this->__parent_model_name];
  }
}
