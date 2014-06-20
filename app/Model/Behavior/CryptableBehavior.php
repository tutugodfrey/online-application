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
				$queryData['conditions'][$model->alias.'.'.$field] = $this->encrypt($queryData['conditions'][$model->alias.'.'.$field]);
			}
		}
		return $queryData;
	}

	function afterFind(Model $model, $results, $primary = false) {
		foreach ($this->settings[$model->alias]['fields'] AS $field) {
			if ($primary) {
				foreach ($results AS $key => $value) {
					if (isset($value[$model->alias][$field])) {
						$results[$key][$model->alias][$field] = $this->decrypt($value[$model->alias][$field]);
					}
				}
			} else {
				if (isset($results[$field])) {
					$results[$field] = $this->decrypt($results[$field]);
				}
			}
		}

		return $results;
	}

	function beforeSave(Model $model, $options = array()) {
		foreach ($this->settings[$model->alias]['fields'] AS $field) {
			if (isset($model->data[$model->alias][$field])) {
				$model->data[$model->alias]['cleartext_'.$field] = $model->data[$model->alias][$field];
				$model->data[$model->alias][$field] = $this->encrypt($model->data[$model->alias][$field]);
			}
		}
		return true;
	}

	public function encrypt($data) {
		if ($data !== '') {
			return base64_encode(mcrypt_encrypt(Configure::read('Cryptable.cipher'), Configure::read('Cryptable.key'), $data, 'cbc', Configure::read('Cryptable.iv')));
		} else {
			return '';
		}
	}

	public function decrypt($data, $data2 = null) {
		if (is_object($data)) {
			unset($data);
			$data = $data2;
		}

		if ($data != '') {
			return trim(mcrypt_decrypt(Configure::read('Cryptable.cipher'), Configure::read('Cryptable.key'), base64_decode($data), 'cbc', Configure::read('Cryptable.iv')));
		} else {
			return '';
		}
	}
}
?>