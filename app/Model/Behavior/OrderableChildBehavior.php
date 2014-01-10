<?php
class OrderableChildBehavior extends ModelBehavior {

	public function setup(Model $model, $settings) {
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
		if (!array_key_exists('order', $model->data[$this->settings[$model->alias]['class_name']])) {
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

				$oldOrder = $this->old[$this->settings[$model->alias]['class_name']]['order'];
				$newOrder = $data[$this->settings[$model->alias]['class_name']]['order'];

				$this->__neighbors = $parent[Inflector::pluralize($this->settings[$model->alias]['class_name'])];
				$movingItem = array_splice($this->__neighbors, $oldOrder, 1);
				array_splice($this->__neighbors, $newOrder, 0, $movingItem);

				// rebase the neighbors
				$neighborCount = count($this->__neighbors);
				for ($i = 0; $i < $neighborCount; $i++) {
					$this->__neighbors[$i]['order'] = $i;
				}

				// remove the $movingItem from the array
				for ($i = 0; $i < $neighborCount; $i++) {
					if ($this->__neighbors[$i]['id'] == $movingItem[0]['id']) {
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
		$behaviorObject = $model->read();
		$this->__parentId = $behaviorObject[$this->settings[$model->alias]['parent_model_name']]['id'];
	}

	public function afterDelete(Model $model) {
		// if the neighbors are not in sequence (0, 1, 3, 4)
		// reorder the 'orders'

		$parent = $this->__getParent($model, $this->__parentId);
		$this->__neighbors = $parent[Inflector::pluralize($this->settings[$model->alias]['class_name'])];

		$rebaseNeeded = false;
		$neighborCount = count($this->__neighbors);
		for ($i = 0; $i < $neighborCount; $i++) {
			if ($this->__neighbors[$i]['order'] != $i) {
				$this->__neighbors[$i]['order'] = $i;
				$rebaseNeeded = true;
			}
		}

		if ($rebaseNeeded == true) {
			foreach ($this->__neighbors as $neighbor) {
				$model->save($neighbor, array('callbacks' => false));
			}
		}
	}

	private function __getParent($model, $parentId) {
		$parent = ClassRegistry::init($this->settings[$model->alias]['parent_model_name']);
		return $parent->find(
			'first',
			array('conditions' => $this->settings[$model->alias]['parent_model_name'] . '.id = "' . $parentId . '"'),
			array('contain' => array($this->settings[$model->alias]['class_name']))
		);
	}

}
