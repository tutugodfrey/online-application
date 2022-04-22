<?php
class CryptableBehavior extends ModelBehavior {
	public $settings = array();

	function setup(Model $model, $settings = array()) {
		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = array(
				'fields' => array()
			);
		}

		$this->settings[$model->alias] = array_merge($this->settings[$model->alias], $settings);
	}

	function beforeFind(Model $model, $queryData) {
		foreach ($this->settings[$model->alias]['fields'] AS $field) {
			if (isset($queryData['conditions'][$model->alias.'.'.$field])) {
				$queryData['conditions'][$model->alias.'.'.$field] =  $model->encrypt($queryData['conditions'][$model->alias.'.'.$field], Configure::read('Security.OpenSSL.key'));
			}
		}
		return $queryData;
	}

	function afterFind(Model $model, $results, $primary = false) {
		foreach ($this->settings[$model->alias]['fields'] AS $field) {
			if ($primary) {
				foreach ($results AS $key => $value) {
					if (isset($value[$model->alias][$field])) {
						$results[$key][$model->alias][$field] = $model->decrypt($value[$model->alias][$field], Configure::read('Security.OpenSSL.key'));
					}
				}
			} else {
				if (isset($results[$field])) {
					$results[$field] = $model->decrypt($results[$field], Configure::read('Security.OpenSSL.key'));
				}
			}
		}

		return $results;
	}

	function beforeSave(Model $model, $options = array()) {
		foreach ($this->settings[$model->alias]['fields'] AS $field) {
			if (isset($model->data[$model->alias][$field])) {
				$model->data[$model->alias]['cleartext_'.$field] = $model->data[$model->alias][$field];
				$model->data[$model->alias][$field] = $model->encrypt($model->data[$model->alias][$field], Configure::read('Security.OpenSSL.key'));
			}
		}
		return true;
	}
}
?>