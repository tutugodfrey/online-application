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
			'notBlank' => array(
				'rule' => array('notBlank'),
				'required' => true,
				'message' => array('Template field name cannot be empty'),

			),
			'input_has_only_valid_chars' => array(
	            'rule' => array('inputHasOnlyValidChars'),
	            'message' => 'Special characters (i.e "<>`()[]"... etc) are not permitted!',
	            'required' => true,
	            'allowEmpty' => false,
	        ),
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

/**
 * beforeSave callback
 *
 * @param $options array
 * @return void
 */
	public function beforeSave($options = array()) {
		if (!empty($this->data[$this->alias]['description'])) {
            $this->data[$this->alias]['description'] = $this->removeAnyMarkUp($this->data[$this->alias]['description']);
        }
        if (!empty($this->data[$this->alias]['merge_field_name'])) {
            $this->data[$this->alias]['merge_field_name'] = $this->removeAnyMarkUp($this->data[$this->alias]['merge_field_name']);
        }
		//If a change was made add a new value to indicate so in the data.
		//Then check for this value in the afterSave callback
		$this->data['TemplateField']['record_changed'] = $this->_recordChanged($this->data);
		return true;
	}

/**
 * _recordChanged
 * Analyzes any form-submitted data and determines if changes were actually made by comparing to current data of the same record in the DB.
 *
 * @param array $data array request data submitted from the view or same as $this->data
 * @return boolean
 */
	protected function _recordChanged($data) {
		if (!empty($data['TemplateField']['id']) &&
				//Type label no need to track changes
				$data['TemplateField']['type'] !== $this->fieldTypes[6] &&
				//Type hr no need to track changes
				$data['TemplateField']['type'] !== $this->fieldTypes[8]) {
			//Get Existing data
			$curData = $this->find('first', array('recursive' => -1, 'conditions' => array('id' => $data['TemplateField']['id'])));
			//Check current data exists
			if (empty($curData)) {
				return false;
			}
			//get all existing fields;
			$fields = $this->getColumnTypes();

			//remove fields that aren't important for comparison
			unset($fields['id']);
			if (array_key_exists('created', $fields)) {
				unset($fields['created']);
			}
			if (array_key_exists('modified', $fields)) {
				unset($fields['modified']);
			}
			if (array_key_exists('section_id', $fields)) {
				unset($fields['section_id']);
			}

			foreach ($fields as $key => $val) {
				//Compare value not data type
				if ($curData['TemplateField'][$key] != $data['TemplateField'][$key]) {
					//If a change was made return true
					return true;
				}
			}
		}
		return false;
	}

/**
 * afterSave callback
 *
 * @param boolean $created whether a new record was created
 * @param $options array same options as calling save method
 * @return void
 */
	public function afterSave($created, $options = array()) {
		//call CobrandedApplication->setDataToSync if we are saving a new field
		if ($created) {
			$this->_setAppsOutOfSync($this->data);
		} elseif (is_array($this->data) && Hash::get($this->data, 'TemplateField.record_changed') === true) {
			//call CobrandedApplication->setDataToSync if record was changed
			$this->_setAppsOutOfSync($this->data);
		}
	}

/**
 * _setAppsOutOfSync
 *
 * @param array $data TemplateData
 * @return boolean true on success false on falure
 * @visibility protected
 */
	protected function _setAppsOutOfSync($data) {
		try {
			return ClassRegistry::init('CobrandedApplication')->setDataToSync($this->data);
		} catch (InvalidArgumentException $e) {
			return false;
		}
	}

}
