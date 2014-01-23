<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplateField Model
 *
 */
class TemplateField extends AppModel {

	public $fieldTypes = array('text', 'date', 'time', 'checkbox', 'radio', 'percents', 'label', 'fees', 'hr');

	public $sourceTypes = array('api', 'user', 'n/a');

	public $requiredTypes = array('no', 'yes');

	public $displayName = 'name';

	public $useTable = 'onlineapp_template_fields';

	public $actsAs = array(
		'Search.Searchable',
		'Containable',
		'OrderableChild' => array(
			'parent_model_name' => 'TemplateSection',
			'parent_model_foreign_key_name' => 'section_id',
			'class_name' => 'TemplateField',
			'children_model_name' => '',
		)
	);

	public $validate = array(
		'name' => array(
			'rule' => array('notempty'),
			'message' => array('Template field name cannot be empty'),
		),
		'width' => array(
			'between' => array(
				'rule' => array('range', 0, 13),
				'message' => array('Invalid width value used, please select a number between 1 and 12'),
			)
		),
		'type' => array(
			'rule' => array('notempty'),
			'message' => array('Template field type cannot be empty'),
		),
		'source' => array(
			'rule' => array('notempty'),
			'message' => array('Template field source cannot be empty'),
		),
		'merge_field_name' => array(
			'rule' => array('notempty'),
			'message' => array('Template field merge_field_name cannot be empty'),
		),
		'order' => array(
			'rule' => array('notempty'),
			'message' => array('Invalid order value used'),
		),
		'section_id' => array(
			'rule' => array('numeric'),
			'message' => array('Invalid section_id value used'),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $belongsTo = array(
		'TemplateSection' => array(
			'className' => 'TemplateSection',
			'foreignKey' => 'section_id',
		)
	);

	private $__cobrand;

	private $__template;

	private $__templatePage;

	private $__templateSection;

	private function __getRelated($templateSectionId) {
		$this->TemplateSection->id = $templateSectionId;
		$parent = $this->TemplateSection->read();

		$this->__templatePage = $parent['TemplatePage'];
		$this->__templateSection = $parent['TemplateSection'];

		// look up the template
		$Template = ClassRegistry::init('Template');
		$Template->id = $this->__templatePage['template_id'];
		$myTemplate = $Template->read();
		$this->__template = $myTemplate['Template'];

		// look up the cobrand
		$Cobrand = ClassRegistry::init('Cobrand');
		$Cobrand->id = $this->__template['cobrand_id'];
		$myCobrand = $Cobrand->read();
		$this->__cobrand = $myCobrand['Cobrand'];
	}

	public function getCobrand($templateSectionId) {
		if ($this->__cobrand == null) {
			$this->__getRelated($templateSectionId);
		}
		return $this->__cobrand;
	}

	public function getTemplate($templateSectionId) {
		if ($this->__template == null) {
			$this->__getRelated($templateSectionId);
		}
		return $this->__template;
	}

	public function getTemplatePage($templateSectionId) {
		if ($this->__templatePage == null) {
			$this->__getRelated($templateSectionId);
		}
		return $this->__templatePage;
	}

	public function getTemplateSection($templateSectionId) {
		if ($this->__templateSection == null) {
			$this->__getRelated($templateSectionId);
		}
		return $this->__templateSection;
	}
}
