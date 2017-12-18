<?php
App::uses('AppModel', 'Model');
App::uses('Folder', 'Utility');

/**
 * Cobrand Model
 *
 * @property Template $Template
 */
class Cobrand extends AppModel {

	public $displayField = 'partner_name';

	public $responseUrlTypes = array(
		1 => 'return nothing',
		2 => 'return RS signing url',
		3 => 'return online app url'
	);

	public $actsAs = array(
        'Upload.Upload' => array(
            'cobrand_logo' => array(
            	'pathMethod' => 'flat',
				'path' => '{ROOT}webroot{DS}img{DS}'
            ),
            'brand_logo' => array(
            	'pathMethod' => 'flat',
				'path' => '{ROOT}webroot{DS}img{DS}'
            )
        )
    );

	public $validate = array(
		'partner_name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'required' => true,
				'message' => 'Partner name cannot be empty'
			),
		),
		'partner_name_short' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'required' => true,
				'message' => 'Short partner name cannot be empty'
			),
		),
	);

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

	public function setLogoUrl($cobrand) {
		if (!empty($cobrand['Cobrand']['cobrand_logo']['name'])) {
			$cobrand['Cobrand']['cobrand_logo_url'] = DS . 'img' . DS . $cobrand['Cobrand']['cobrand_logo']['name'];
		}

		if (!empty($cobrand['Cobrand']['brand_logo']['name'])) {
			$cobrand['Cobrand']['brand_logo_url'] = DS . 'img' . DS . $cobrand['Cobrand']['brand_logo']['name'];
		}

		return $cobrand;
	}

	public function getTemplateIds($cobrandId){
		$this->Template = ClassRegistry::init('Template');

		$templateIds = $this->Template->find(
			'list',
			array(
				'conditions' => array(
					'Template.cobrand_id' => $cobrandId
				),
				'fields' => array(
					'Template.id'
				),
			)
		);

		return $templateIds;
	}

	public function getExistingLogos() {
		$dir = new Folder(WWW_ROOT . 'img');
		$files = $dir->find('.*', true);
		return $files;
	}
}
