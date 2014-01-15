<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplate Model
 *
 */
class Template extends AppModel {

	public $logoPositionTypes = array('left', 'center', 'right', 'hide');

	public $displayField = 'name';

	public $useTable = 'onlineapp_templates';

	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => array('Template name cannot be empty'),
			),
		),
		'cobrand_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => array('Invalid cobrand_id value used'),
			),
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => array('Cobrand_id name cannot be empty'),
			),
		),
		'logo_position' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => array('Logo position value not selected'),
			),
		),
	);

	public $hasMany = array(
		'Users' => array(
			'className' => 'User',
			'foreignKey' => 'template_id',
			'dependent' => false,
		),
		'TemplatePages' => array(
			'className' => 'TemplatePage',
			'foreignKey' => 'template_id',
			'order' => 'TemplatePages.order',
			'dependent' => true,
		)
	);

	public $belongsTo = array(
		'Cobrand' => array(
			'className' => 'Cobrand',
			'foreignKey' => 'cobrand_id'
		)
	);

	public function getList() {
		return $this->find('list',
			array('order' => array('Template.name' => 'asc')));
	}

	public function getCobrand($cobrandId) {
		// is this the way to access another model?
		$this->Cobrand->id = $cobrandId;
		$this->Cobrand->recursive = -1;
		$this->Cobrand->find('first');
		return $this->Cobrand->read();
	}
}
