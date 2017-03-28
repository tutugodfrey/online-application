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
			'children_model_name' => 'TemplateSection'
		)
	);

	public $validate = array(
		'name' => array(
			'rule' => array('notBlank'),
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
		$cobrand = Hash::get($parentTemplate, 'Cobrand');

		// is this the way to access another model?
		return $cobrand;
	}

	public function getTemplate($templateId, $includeAssc = false) {
		$this->Template->id = $templateId;
		$template = $this->Template->find('first', array('conditions' => array('Template.id' => $templateId)));

		if (empty($template)) {
			return $template;
		}
		return ($includeAssc == true ? $template : $template['Template']);
	}

	public function nameEditable($pageName) {
		return ($pageName != 'Validate Application');
	}

	public function orderEditable($pageName) {
		return $this->nameEditable($pageName);
	}

	public function afterSave($created, $options = array()) {
		// make sure 'Validate Application' page is the last page
		// we have to have $this->data to perform our task
		$template = $this->getTemplate($this->data['TemplatePage']['template_id'], true);
		$pages = Hash::get($template, 'TemplatePages');
		$validateAppPageIndex = 0;
		$pagesCount = count($pages);
		if ($pagesCount > 1) {
			for ($index=0; $index < $pagesCount; $index++) { 
				if ($pages[$index]['name'] == 'Validate Application') {
					$validateAppPageIndex = $index;
				}
			}

			$validateAppPage = array_splice($pages, $validateAppPageIndex, 1);
			array_splice($pages, count($pages), 0, $validateAppPage);

			// rebase the pages
			$pagesCount = count($pages);
			for ($i = 0; $i < $pagesCount; $i++) {
				$pages[$i]['order'] = $i;
			}

			foreach ($pages as $page) {
				$this->save($page, array('callbacks' => false));
			}

		}

	}
}
