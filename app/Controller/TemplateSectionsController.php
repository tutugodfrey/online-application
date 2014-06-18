<?php
App::uses('Sanitize', 'Utility');
App::uses('NestedResourceController', 'Controller');
/**
 * TemplateSections Controller
 *
 */
class TemplateSectionsController extends NestedResourceController {

	protected $_parentCtrlName = 'TemplatePages';

	protected $_controllerName = 'TemplateSections';

	public $permissions = array(		
		'admin_index' => array('admin', 'rep', 'manager'),
		'admin_add' => array('admin', 'rep', 'manager'),
		'admin_edit' => array('admin', 'rep', 'manager'),
		'admin_delete' => array('admin', 'rep', 'manager'),
	);

	public function admin_add() {
		if ($this->request->is('post')) {
			$data = Sanitize::clean($this->request->data);
			// we know the page_id from the uri
			$data['TemplateSection']['page_id'] = $this->_getParentControllerId();
			$this->TemplateSection->create();
			if ($this->TemplateSection->save($data)) {
				$this->Session->setFlash("Template Section Saved!");
				return $this->redirect($this->_getListUrl());
			}
			$this->Session->setFlash(__('Unable to add your section.'));
		}

		$this->__setCommonViewVariables();
	}

	public function admin_edit($idToEdit) {
		$this->TemplateSection->id = $idToEdit;
		if (empty($this->request->data)) {
			$this->request->data = $this->TemplateSection->read();
		} else {
			$data = Sanitize::clean($this->request->data);
			// we know the page_id from the uri
			$data['TemplateSection']['page_id'] = $this->_getParentControllerId();
			if ($this->TemplateSection->save(Sanitize::clean($data))) {
				$this->Session->setFlash("Template Section Saved!");
				return $this->redirect($this->_getListUrl());
			}
			$this->Session->setFlash(__('Unable to update your section.'));
		}

		$this->__setCommonViewVariables();
	}

	public function admin_index() {
		$this->paginate = array(
			'limit' => 25,
			'order' => array('TemplateSection.order' => 'ASC'),
			'conditions' => array('TemplateSection.page_id' => $this->_getParentControllerId()),
			'recursive' => 0,
		);

		$data = $this->paginate('TemplateSection');
		$this->set('templateSections', $data);
		$this->set('scaffoldFields', array_keys($this->TemplateSection->schema()));
		$this->__setCommonViewVariables();
	}

	public function admin_delete($idToDelete) {
		$this->TemplateSection->delete($idToDelete);
		$this->Session->setFlash("Template Section Deleted!");
		$this->redirect($this->_getListUrl());
	}

	private function __setCommonViewVariables() {
		$this->set('list_url', $this->_getListUrl());
		$this->set('cobrand', $this->TemplateSection->getCobrand($this->_getParentControllerId()));
		$this->set('template', $this->TemplateSection->getTemplate($this->_getParentControllerId()));
		$this->set('templatePage', $this->TemplateSection->getTemplatePage($this->_getParentControllerId()));
	}
}