<?php
App::uses('NestedResourceController', 'Controller');
/**
 * OnlineappTemplates Controller
 *
 */
class TemplatesController extends NestedResourceController {

	protected $_parentCtrlName = "Cobrands";

	protected $_controllerName = "Templates";

	public $permissions = array(		
		'admin_index' => array('admin', 'rep', 'manager'),
		'admin_add' => array('admin', 'rep', 'manager'),
		'admin_edit' => array('admin', 'rep', 'manager'),
		'admin_delete' => array('admin', 'rep', 'manager'),
		'admin_preview' => array('admin', 'rep', 'manager'),
	);

	public function admin_add() {
		if ($this->request->is('post')) {
			$data = $this->request->data;
			// set the cobrand_id
			$data['Template']['cobrand_id'] = $this->_getParentControllerId();
			$this->Template->create();
			if ($this->Template->save($data)) {
				$this->Session->setFlash("Template Saved!");
				return $this->redirect($this->_getListUrl());
			}
			$this->Session->setFlash(__('Unable to add your template'));
		}

		$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
			
		$client = $this->CobrandedApplication->createRightSignatureClient();
		$results = $this->CobrandedApplication->getRightSignatureTemplates($client);

		$templates = array();
		$installTemplates = array();

		foreach ($results as $guid => $filename) {
			if (preg_match('/install/i', $filename)) {
				$installTemplates[$guid] = $filename;
			} else {
				$templates[$guid] = $filename;
			}
		}
		$this->set('templateList', $templates);
		$this->set('installTemplateList', $installTemplates);

		$this->__setCommonViewVariables();
	}

	public function admin_edit($idToEdit) {
		$this->Template->id = $idToEdit;
		if (empty($this->request->data)) {
			$this->request->data = $this->Template->read();

			$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
			
			$client = $this->CobrandedApplication->createRightSignatureClient();
			$results = $this->CobrandedApplication->getRightSignatureTemplates($client);

			$templates = array();
			$installTemplates = array();

			foreach ($results as $guid => $filename) {
				if (preg_match('/install/i', $filename)) {
					$installTemplates[$guid] = $filename;
				} else {
					$templates[$guid] = $filename;
				}
			}

			$this->set('templateList', $templates);
			$this->set('installTemplateList', $installTemplates);
		} else {
			$data = $this->request->data;
			// we know the cobrand_id from the uri
			$data['Template']['cobrand_id'] = $this->_getParentControllerId();
			if ($this->Template->saveAll($data)) {
				$this->Session->setFlash("Template Saved!");
				return $this->redirect($this->_getListUrl());
			}
			$this->Session->setFlash(__('Unable to update your template'));
		}

		$this->__setCommonViewVariables();
	}

	public function admin_index() {
		$this->paginate = array(
			'limit' => 25,
			'order' => array('Template.name' => 'asc'),
			'conditions' => array('Template.cobrand_id' => $this->_getParentControllerId())
		);

		$data = $this->paginate('Template');
		$this->set('templates', $data);
		$this->set('scaffoldFields', array_keys($this->Template->schema()));
		$this->_setViewNavData();
		$this->__setCommonViewVariables();
	}

	public function admin_delete($idToDelete) {
		$this->Template->delete($idToDelete);
		$this->Session->setFlash("Template Deleted!");
		$this->redirect($this->_getListUrl());
	}

	private function __setCommonViewVariables() {
		$this->_setViewNavData();
		$this->set('list_url', $this->_getListUrl());
		$this->set('cobrand', $this->Template->getCobrand($this->_getParentControllerId()));
		$this->set('logoPositionTypes', $this->Template->logoPositionTypes);
	}

	public function admin_preview($idToPreview) {
		$this->Template->id = $idToPreview;
		$template = $this->Template->find(
			'first', array(
				'contain' => array(
					'Cobrand',
					'TemplatePages' => array(
						'TemplateSections' => array(
							'TemplateFields' => array(
								'CobrandedApplicationValues'
							)
						)
					)
				),
				'conditions' => array('Template.id' => $idToPreview)
			)
		);

		$this->set('template', $template);
		$this->set('logoPositionTypes', $this->Template->logoPositionTypes);
		$this->set('bad_characters', array(' ', '&', '#', '$', '(', ')', '/', '%', '\.', '.', '\''));
		$this->set('requireRequiredFields', true);
	}


/**
 * _setViewNavContent
 * Utility method sets an array of urls to use as left navigation items on views
 *
 * @param string $showActive string representation of boolean value
 * @return array
 */
	protected function _setViewNavData() {
		$elVars = array(
			'navLinks' => array(
				'Templates Index' => $this->_getListUrl(),
				'New Template' =>$this->_getListUrl() . '/add',
			)
		);

		$this->set(compact('elVars'));
	}

}
