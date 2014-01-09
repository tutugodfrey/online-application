<?php
class OrderableChildBehavior extends ModelBehavior {

  function setup(Model $model, $settings) {
    if (!isset($this->settings[$model->alias])) {
      $this->settings[$model->alias] = array(
        'fields' => array()
      );
    }
    $this->settings[$model->alias] = array_merge($this->settings[$model->alias], $settings);
  }

  public function beforeSave(Model $model, $options = array()) {
    $this->__neighbors = null;

    // if the order field is not set, figure it out
    if ($model->data[$this->settings[$model->alias]['class_name']]['order'] === null) {
      $parent = $this->__getParent($model, $model->data[$this->settings[$model->alias]['class_name']][$this->settings[$model->alias]['parent_model_foreign_key_name']]);

      $count = count($parent[Inflector::pluralize($this->settings[$model->alias]['class_name'])]);
      $model->data[$this->settings[$model->alias]['class_name']]['order'] = $count;
    }

    // if we have an id, assume edit case
    if ($model->id) {
      $data = $model->data;
      $this->old = $model->findById($model->id);

      // see if the order value has changed
      if ($this->old[$this->settings[$model->alias]['class_name']]['order'] != $data[$this->settings[$model->alias]['class_name']]['order']) {
        $parent = $this->__getParent($model, $model->data[$this->settings[$model->alias]['class_name']][$this->settings[$model->alias]['parent_model_foreign_key_name']]);

        $old_order = $this->old[$this->settings[$model->alias]['class_name']]['order'];
        $new_order = $data[$this->settings[$model->alias]['class_name']]['order'];

        $this->__neighbors = $parent[Inflector::pluralize($this->settings[$model->alias]['class_name'])];
        $moving_item = array_splice($this->__neighbors, $old_order, 1);
        array_splice($this->__neighbors, $new_order, 0, $moving_item);

        // rebase the neighbors
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

  public function afterSave(Model $model, $created, $options = array()) {
    if ($this->__neighbors != null) {
      if (count($this->__neighbors) > 0) {
        foreach ($this->__neighbors as $neighbor) {
          $model->save($neighbor, array('callbacks' => false));
        }
      }
    }
  }

  public function beforeDelete(Model $model) {
    $behavior_object = $model->read();
    $this->__parent_id = $behavior_object[$this->settings[$model->alias]['parent_model_name']]['id'];
  }

  public function afterDelete(Model $model) {
    // if the neighbors are not in sequence (0, 1, 3, 4)
    // reorder the 'orders'
    $parent = $this->__getParent($model, $this->__parent_id);
    $this->__neighbors = $parent[Inflector::pluralize($this->settings[$model->alias]['class_name'])];

    $rebase_needed = false;
    for ($i = 0; $i < count($this->__neighbors); $i++) {
      if ($this->__neighbors[$i]['order'] != $i) {
        $this->__neighbors[$i]['order'] = $i;
        $rebase_needed = true;
      }
    }

    if ($rebase_needed == true) {
      foreach ($this->__neighbors as $neighbor) {
        $model->save($neighbor, array('callbacks' => false));
      }
    }
  }

  private function __getParent($model, $parent_id) {
    $parent = ClassRegistry::init($this->settings[$model->alias]['parent_model_name']);
    return $parent->find(
      'first',
      array('conditions' => $this->settings[$model->alias]['parent_model_name'] . '.id = "' . $parent_id . '"'),
      array('contain' => array($this->settings[$model->alias]['class_name']))
    );
  }

}
?>