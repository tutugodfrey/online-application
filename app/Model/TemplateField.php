<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplateField Model
 *
 */
class TemplateField extends AppModel {

	public $fieldTypes = array(
		'text',					//  0 - free form
		'date',					//  1 - yyyy/mm/dd
		'time',					//  2 - hh:mm:ss
		'checkbox',				//  3 -
		'radio',				//  4 -
		'percents',				//  5 - (group of percent)
		'label',				//  6 - no validation
		'fees',					//  7 - (group of money?)
		'hr',					//  8 - no validation
		/* newer types */
		'phoneUS',				//  9 - (###) ###-####
		'money',				// 10 - $(#(1-3),)?(#(1-3)).## << needs work
		'percent',				// 11 - (0-100)%
		'ssn',					// 12 - ###-##-####
		'zipcodeUS',			// 13 - #####[-####]
		'email',				// 14 -
		'lengthoftime',			// 15 - [#+] [year|month|day]s
		'creditcard',			// 16 -
		'url',					// 17 -
		'number',				// 18 - (#)+.(#)+
		'digits',				// 19 - (#)+
		'select',				// 20 -
		'textArea',				// 21 -
		'multirecord',			// 22 - multiple records
		'luhn',					// 23 - luhn validation
	);

	public $sourceTypes = array('api', 'user', 'api/user', 'n/a');

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
			'rule' => array('notBlank'),
			'message' => array('Template field name cannot be empty'),
		),
		'width' => array(
			'between' => array(
				'rule' => array('range', 0, 13),
				'message' => array('Invalid width value used, please select a number between 1 and 12'),
			)
		),
		'type' => array(
			'rule' => array('notBlank'),
			'message' => array('Template field type cannot be empty'),
		),
		'source' => array(
			'rule' => array('notBlank'),
			'message' => array('Template field source cannot be empty'),
		),
		'merge_field_name' => array(
			'rule' => array('validMergeFieldName'),
			'message' => array('Template field merge_field_name cannot be empty'),
		),
		'order' => array(
			'rule' => array('notBlank'),
			'message' => array('Invalid order value used'),
		),
		'section_id' => array(
			'rule' => array('numeric'),
			'message' => array('Invalid section_id value used'),
		),
	);

	public function validMergeFieldName() {
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

/**
 * __getRelated
 * Returns associated model data
 *
 * @param integer $templateSectionId a TemplateSection.id
 * @visibility private
 * @return array
 */
	private function __getRelated($templateSectionId) {
		$data = $this->TemplateSection->find('first', array(
				'recursive' => -1,
				'fields' => array(
						'TemplateSection.*',
						'TemplatePage.*',
						'Template.id',
						'Template.name',
						'Template.logo_position',
						'Template.include_brand_logo',
						'Template.description',
						'Template.cobrand_id',
						'Template.created',
						'Template.modified',
						'Template.rightsignature_template_guid',
						'Template.rightsignature_install_template_guid',
						'Template.owner_equity_threshold',
						'Template.requires_coversheet',
						'Cobrand.id',
						'Cobrand.partner_name',
						'Cobrand.partner_name_short',
						'Cobrand.cobrand_logo_url',
						'Cobrand.description',
						'Cobrand.created',
						'Cobrand.modified',
						'Cobrand.response_url_type',
						'Cobrand.brand_logo_url',
				),
				'conditions' => array('TemplateSection.id' => $templateSectionId),
				'joins' => array(
					array(
						//TemplatePage
						'table' => 'onlineapp_template_pages',
						'alias' => 'TemplatePage',
						'type' => 'LEFT',
						'conditions' => array(
								'TemplatePage.id = TemplateSection.page_id'
						)
					),
					array(
						//Template
						'table' => 'onlineapp_templates',
						'alias' => 'Template',
						'type' => 'LEFT',
						'conditions' => array(
								'TemplatePage.template_id = Template.id'
						)
					),
					array(
						//Cobrand
						'table' => 'onlineapp_cobrands',
						'alias' => 'Cobrand',
						'type' => 'LEFT',
						'conditions' => array(
								'Template.cobrand_id = Cobrand.id'
						)
					),
				)
			)
		);
		return $data;
	}

/**
 * getCobrand
 * Returns associated model data
 *
 * @param integer $templateSectionId a TemplateSection.id
 * @return array
 */
	public function getCobrand($templateSectionId) {
		$data = $this->__getRelated($templateSectionId);
		return Hash::get($data, 'Cobrand');
	}

/**
 * getTemplate
 * Returns associated model data
 *
 * @param integer $templateSectionId a TemplateSection.id
 * @return array
 */
	public function getTemplate($templateSectionId) {
		$data = $this->__getRelated($templateSectionId);
		return Hash::get($data, 'Template');
	}

/**
 * getTemplatePage
 * Returns associated model data
 *
 * @param integer $templateSectionId a TemplateSection.id
 * @return array
 */
	public function getTemplatePage($templateSectionId) {
		$data = $this->__getRelated($templateSectionId);
		return Hash::get($data, 'TemplatePage');
	}

/**
 * getTemplateSection
 * Returns associated model data
 *
 * @param integer $templateSectionId a TemplateSection.id
 * @return array
 */
	public function getTemplateSection($templateSectionId) {
		$data = $this->__getRelated($templateSectionId);
		return Hash::get($data, 'TemplateSection');
	}
}
