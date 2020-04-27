<?php
App::uses('AppModel', 'Model');
class ApiConfiguration extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'configuration_name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'You must enter a unique configuration name.',
				'allowEmpty' => false,
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'Name is taken, please enter an unique configuration name..',
				'allowEmpty' => false,
			)
		),
	);

/**
 * beforeSave callback
 * 
 * @param array $options
 */
	public function beforeSave($options = array()) {
		if (!empty($this->data[$this->alias]['client_secret'])) {
			$this->data[$this->alias]['client_secret'] = $this->mcryptEncryptStr($this->data[$this->alias]['client_secret']);
		}
		if(!empty($this->data[$this->alias]['access_token'])) {
			$this->data[$this->alias]['access_token'] = $this->mcryptEncryptStr($this->data[$this->alias]['access_token']);
		}
		if(!empty($this->data[$this->alias]['refresh_token'])) {
			$this->data[$this->alias]['refresh_token'] = $this->mcryptEncryptStr($this->data[$this->alias]['refresh_token']);
		}
		return true;
	}

/**
 * afterFind
 *
 * @params
 *     $results array
 *     $primary boolean
 */
	public function afterFind($results, $primary = false) {
		if (!empty($results)) {
			if ($primary) {
				foreach ($results as $idx => $data) {
					if (!empty($data[$this->alias]['client_secret'])){
						$results[$idx][$this->alias]['client_secret'] = $this->mcryptDencrypt($data[$this->alias]['client_secret']);
					}
					if (!empty($data[$this->alias]['access_token'])){
						$results[$idx][$this->alias]['access_token'] = $this->mcryptDencrypt($data[$this->alias]['access_token']);
					}
					if (!empty($data[$this->alias]['refresh_token'])){
						$results[$idx][$this->alias]['refresh_token'] = $this->mcryptDencrypt($data[$this->alias]['refresh_token']);
					}
				}
			} else {
				if (!empty($results['client_secret'])){
					$results['client_secret'] = $this->mcryptDencrypt($results['client_secret']);
				}
				if (!empty($results['access_token'])){
					$results['access_token'] = $this->mcryptDencrypt($results['access_token']);
				}
				if (!empty($results['refresh_token'])){
					$results['refresh_token'] = $this->mcryptDencrypt($results['refresh_token']);
				}
			}
		}
		return $results;
	}

}
