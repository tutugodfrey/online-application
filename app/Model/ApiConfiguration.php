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
			$this->data[$this->alias]['client_secret'] = $this->encrypt($this->data[$this->alias]['client_secret'], Configure::read('Security.OpenSSL.key'));
		}
		if(!empty($this->data[$this->alias]['access_token'])) {
			$this->data[$this->alias]['access_token'] = $this->encrypt($this->data[$this->alias]['access_token'], Configure::read('Security.OpenSSL.key'));
		}
		if(!empty($this->data[$this->alias]['refresh_token'])) {
			$this->data[$this->alias]['refresh_token'] = $this->encrypt($this->data[$this->alias]['refresh_token'], Configure::read('Security.OpenSSL.key'));
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
						$results[$idx][$this->alias]['client_secret'] = $this->decrypt($data[$this->alias]['client_secret'], Configure::read('Security.OpenSSL.key'));
					}
					if (!empty($data[$this->alias]['access_token'])){
						$results[$idx][$this->alias]['access_token'] = $this->decrypt($data[$this->alias]['access_token'], Configure::read('Security.OpenSSL.key'));
					}
					if (!empty($data[$this->alias]['refresh_token'])){
						$results[$idx][$this->alias]['refresh_token'] = $this->decrypt($data[$this->alias]['refresh_token'], Configure::read('Security.OpenSSL.key'));
					}
				}
			} else {
				if (!empty($results['client_secret'])){
					$results['client_secret'] = $this->decrypt($results['client_secret'], Configure::read('Security.OpenSSL.key'));
				}
				if (!empty($results['access_token'])){
					$results['access_token'] = $this->decrypt($results['access_token'], Configure::read('Security.OpenSSL.key'));
				}
				if (!empty($results['refresh_token'])){
					$results['refresh_token'] = $this->decrypt($results['refresh_token'], Configure::read('Security.OpenSSL.key'));
				}
			}
		}
		return $results;
	}

}
