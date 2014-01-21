<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplateSection Model
 *
 * @property Page $Page
 */
class TemplateSection extends AppModel {

	public $displayField = 'name';

	public $useTable = 'onlineapp_template_sections';

	public $actsAs = array(
		'Search.Searchable',
		'Containable',
		'OrderableChild' => array(
			'parent_model_name' => 'TemplatePage',
			'parent_model_foreign_key_name' => 'page_id',
			'class_name' => 'TemplateSection',
		)
	);

	public $validate = array(
		'name' => array(
			'rule' => array('notempty'),
			'message' => array('Template section name cannot be empty'),
		),
		'width' => array(
			'between' => array(
				'rule' => array('range', 0, 13),
				'message' => array('Invalid width value used, please select a number between 1 and 12'),
			)
		),
		'page_id' => array(
			'rule' => array('numeric'),
			'message' => array('Invalid page_id value used'),
		),
		'order' => array(
			'rule' => array('notempty'),
			'message' => array('Invalid order value used'),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $belongsTo = array(
		'TemplatePage' => array(
			'className' => 'TemplatePage',
			'foreignKey' => 'page_id',
			'class_name' => 'TemplateSection',
		)
	);

	public $hasMany = array(
		'TemplateFields' => array(
			'className' => 'TemplateField',
			'foreignKey' => 'section_id',
			'order' => 'TemplateFields.order',
			'dependent' => true,
		)
	);

	private $__cobrand;

	private $__template;

	private $__templatePage;

	private function __getRelated($templatePageId) {
		$this->TemplatePage->id = $templatePageId;
		$parentTemplatePage = $this->TemplatePage->read();

		$this->__template = $parentTemplatePage['Template'];
		$this->__templatePage = $parentTemplatePage['TemplatePage'];

		// look up the cobrand from __template
		$Cobrand = ClassRegistry::init('Cobrand');
		$Cobrand->id = $this->__template['cobrand_id'];
		$myCobrand = $Cobrand->read();
		$this->__cobrand = $myCobrand['Cobrand'];
	}

	public function getCobrand($templatePageId) {
		if ($this->__cobrand == null) {
			$this->__getRelated($templatePageId);
		}
		return $this->__cobrand;
	}

	public function getTemplate($templatePageId) {
		if ($this->__template == null) {
			$this->__getRelated($templatePageId);
		}
		return $this->__template;
	}

	public function getTemplatePage($templatePageId) {
		if ($this->__templatePage == null) {
			$this->__getRelated($templatePageId);
		}
		return $this->__templatePage;
	}
}
