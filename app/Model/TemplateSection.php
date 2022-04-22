<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplateSection Model
 *
 * @property Page $Page
 */
class TemplateSection extends AppModel {

	public $displayField = 'name';

	public $actsAs = array(
		'Search.Searchable',
		'Containable',
		'OrderableChild' => array(
			'parent_model_name' => 'TemplatePage',
			'parent_model_foreign_key_name' => 'page_id',
			'class_name' => 'TemplateSection',
			'children_model_name' => 'TemplateField'
		)
	);

	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'required' => true,
				'message' => array('Template section name cannot be empty'),
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
		'page_id' => array(
			'rule' => array('numeric'),
			'message' => array('Invalid page_id value used'),
		),
		'order' => array(
			'rule' => array('notBlank'),
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

/**
 * beforeSave callback
 *
 * @param array $options options param required by callback
 * @return void
 */
	public function beforeSave($options = array()) {
		if (!empty($this->data[$this->alias]['description'])) {
            $this->data[$this->alias]['description'] = $this->removeAnyMarkUp($this->data[$this->alias]['description']);
        }
	}

/**
 * __getRelated
 * Returns associated model data
 *
 * @param integer $templatePageId a TemplatePage.id
 * @visibility private
 * @return array
 */

	private function __getRelated($templatePageId) {
		$data = $this->TemplatePage->find('first', array(
				'recursive' => -1,
				'fields' => array(
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
				'conditions' => array('TemplatePage.id' => $templatePageId),
				'joins' => array(
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
 * @param integer $templatePageId a TemplatePage.id
 * @return array
 */
	public function getCobrand($templatePageId) {
		$data = $this->__getRelated($templatePageId);
		return Hash::get($data, 'Cobrand');
	}

/**
 * getTemplate
 * Returns associated model data
 *
 * @param integer $templatePageId a TemplatePage.id
 * @return array
 */
	public function getTemplate($templatePageId) {
		$data = $this->__getRelated($templatePageId);
		return Hash::get($data, 'Template');
	}

/**
 * getTemplatePage
 * Returns associated model data
 *
 * @param integer $templatePageId a TemplatePage.id
 * @return array
 */
	public function getTemplatePage($templatePageId) {
		$data = $this->__getRelated($templatePageId);
		return Hash::get($data, 'TemplatePage');
	}
}
