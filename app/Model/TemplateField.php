<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplateField Model
 *
 */
class TemplateField extends AppModel {

	public $fieldTypes = array(
		'text',             //  0 - free form
		'date',             //  1 - yyyy/mm/dd
		'time',             //  2 - hh:mm:ss
		'checkbox',         //  3 - 
		'radio',            //  4 - 
		'percents',         //  5 - (group of percent)
		'label',            //  6 - no validation
		'fees',             //  7 - (group of money?)
		'hr',               //  8 - no validation
/* newer types */
		'phoneUS',          //  9 - (###) ###-####
		'money',            // 10 - $(#(1-3),)?(#(1-3)).## << needs work
		'percent',          // 11 - (0-100)%
		'ssn',              // 12 - ###-##-####
		'zipcodeUS',        // 13 - #####[-####]
		'email',            // 14 - 
		'lengthoftime',     // 15 - [#+] [year|month|day]s
		'creditcard',       // 16 - 
		'url',              // 17 - 
		'number',           // 18 - (#)+.(#)+
		'digits',           // 19 - (#)+
		'select',           // 20 - 
		'textArea',         // 21 -
		'multirecord',		// 22 - multiple records
		//'taxId',          // 23 - ?
		//'bankRoutingNumber',// 24 - #########
		//'bankAccountNumber',// 25 - ?
		//'amexSENumber',     // 26 - ?
		
	);

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
			'children_model_name' => null,
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
			'rule' => array('validMergeFieldName'),
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

	public function validMergeFieldName(/*$check*/) {
		$valid = false;
		if (key_exists('TemplateField', $this->data)) {
			if (strlen($this->data['TemplateField']['merge_field_name']) == 0) {
				// if the field type is in (4, 5, 7, 20) then the merge_field_name value can be empty
				switch ($this->data['TemplateField']['type']) {
					case 4:
					case 5:
					case 7:
					case 20:
						$valid = true;
						break;

					default:
						// no op, $valid is already false
						break;
				}
			} else {
				$valid = true;
			}
		}
		return $valid;
	}

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $belongsTo = array(
		'TemplateSection' => array(
			'className' => 'TemplateSection',
			'foreignKey' => 'section_id',
		)
	);

	public $hasMany = array(
		'CobrandedApplicationValues' => array(
			'className' => 'CobrandedApplicationValue',
			'foreignKey' => 'template_field_id',
			'dependent' => true
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
