<?php
App::uses('AppModel', 'Model');
/**
 * Cobrand Model
 *
 * @property Template $Template
 */
class Cobrand extends AppModel {

	public $displayField = 'partner_name';

	public $useTable = 'onlineapp_cobrands';

	public $responseUrlTypes = array(
		1 => 'return nothing',
		2 => 'return RS signing url',
		3 => 'return online app url'
	);

	public $validate = array(
		'partner_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'required' => true,
				'message' => 'Partner name cannot be empty'
			),
		),
		'partner_name_short' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'required' => true,
				'message' => 'Short partner name cannot be empty'
			),
		),
		'logo' => array(
			'logoRule-1' => array(
				'rule' => 'logoExists',
				'message' => 'A Logo with this name already exists',
			),
			'logoRule-2' => array(
				'rule' => 'isUploadedFile',
				'message' => 'File Could not be uploaded'
			),
		),
	);

	public function beforeValidate(array $options = array()) {
		if(isset($this->data['Cobrand']['logo']))
		{
			if (empty($this->data['Cobrand']['logo']['name']) &&
			empty($this->data['Cobrand']['logo']['name']) &&
			$this->data['Cobrand']['logo']['error'] === '4'
		) {
			unset($this->data['Cobrand']['logo']);
			return $this->data;
		}
		}
	}

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Templates' => array(
			'className' => 'Template',
			'foreignKey' => 'cobrand_id',
			'dependent' => true,
		)
	);

	public function getList() {
		return $this->find('list',
			array('order' => array('Cobrand.partner_name' => 'asc')));
	}
/**
 * logoExists
 *
 * @params array
 */
	public function logoExists($params) {
		$val= array_shift($params);
		$file =  WWW_ROOT . 'img' . DS . $val['name'];
		if (!file_exists($file)) {
			return true;
		}
		return false;
	}
/**
 * isUploadedFile
 *
 * @params array
 */
	public function isUploadedFile($params) {
		$val = array_shift($params);
		$file =  WWW_ROOT . 'img' . DS . $val['name'];
		if((isset($val['error']) && $val['error'] == 0) ||
			(!empty( $val['tmp_name']) && $val['tmp_name'] != 'none')
		) {
			if (is_uploaded_file($val['tmp_name']) && move_uploaded_file($val['tmp_name'], $file)) {
				return true;
			}
		}
		return false;
	}
	public function setLogoUrl($cobrand) {
		if (!empty($cobrand['Cobrand']['logo']['name'])) {
			$cobrand['Cobrand']['logo_url'] = DS . 'img' . DS . $cobrand['Cobrand']['logo']['name']; 
			return $cobrand;
		}
		return $cobrand;
	}

}
