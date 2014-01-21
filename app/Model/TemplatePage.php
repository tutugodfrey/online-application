<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplatePage Model
 *
 */
class TemplatePage extends AppModel {

	public $displayField = 'name';

	public $useTable = 'onlineapp_template_pages';

	public $actsAs = array(
		'Search.Searchable',
		'Containable',
		'OrderableChild' => array(
			'parent_model_name' => 'Template',
			'parent_model_foreign_key_name' => 'template_id',
			'class_name' => 'TemplatePage',
		)
	);

	public $validate = array(
		'name' => array(
			'rule' => array('notempty'),
			'required' => true,
			'message' => array('Template page name cannot be empty')
		),
		'template_id' => array(
			'rule' => array('numeric'),
			'required' => true,
			'message' => array('Invalid cobrand_id value used')
		),
		'order' => array(
			'rule' => array('numeric'),
			'message' => array('Invalid order value used')
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $belongsTo = array(
		'Template' => array(
			'className' => 'Template',
			'foreignKey' => 'template_id',
		)
	);

	public $hasMany = array(
		'TemplateSections' => array(
			'className' => 'TemplateSection',
			'foreignKey' => 'page_id',
			'order' => 'TemplateSections.order',
			'dependent' => true,
		)
	);

	public function getCobrand($templateId = null) {
		// check if we already have data
		if (is_array($this->data) && count($this->data) > 0 && array_key_exists(get_class(), $this->data)) {
			$templateId = $this->data[get_class()]['template_id'];
		}
		// look it up
		$parentTemplate = $this->Template->findById($templateId);
		$cobrand = $parentTemplate['Cobrand'];

		// is this the way to access another model?
		return $cobrand;
	}

	public function getTemplate($templateId, $includeAssc = false) {
		$this->Template->id = $templateId;
		$template = $this->Template->read();
		return ($includeAssc == true ? $template : $template['Template']);
	}
}
