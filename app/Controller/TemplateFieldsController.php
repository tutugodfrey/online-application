<?php
App::uses('Sanitize', 'Utility');
App::uses('NestedResourceController', 'Controller');
/**
 * TemplateFields Controller
 *
 */
class TemplateFieldsController extends NestedResourceController {

	protected $_parentCtrlName = 'TemplateSections';

	protected $_controllerName = 'TemplateFields';

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
			$data['TemplateField']['section_id'] = $this->_getParentControllerId();
			$this->TemplateField->create();
			if ($this->TemplateField->save($data)) {
				$this->Session->setFlash("Template Field Saved!");
				return $this->redirect($this->_getListUrl());
			}
			$this->Session->setFlash(__('Unable to add your field.'));
		}

		$this->__setCommonViewVariables();
	}

	public function admin_edit($idToEdit) {
		$this->TemplateField->id = $idToEdit;
		if (empty($this->request->data)) {
			$this->request->data = $this->TemplateField->read();
		} else {
			$data = Sanitize::clean($this->request->data);
			// we know the page_id from the uri
			$data['TemplateField']['section_id'] = $this->_getParentControllerId();
			if ($this->TemplateField->save(Sanitize::clean($data))) {
				$this->Session->setFlash("Template Field Saved!");
				return $this->redirect($this->_getListUrl());
			}
			$this->Session->setFlash(__('Unable to update your field.'));
		}

		$this->__setCommonViewVariables();
	}

	public function admin_index() {
		$this->paginate = array(
			'limit' => 25,
			'order' => array('TemplateField.order' => 'ASC'),
			'conditions' => array('TemplateField.section_id' => $this->_getParentControllerId())
		);

		$data = $this->paginate('TemplateField');
		$this->set('templateFields', $data);
		$this->set('scaffoldFields', array_keys($this->TemplateField->schema()));
		$this->set('requiredTypes', $this->TemplateField->requiredTypes);
		$this->__setCommonViewVariables();
	}

	public function admin_delete($idToDelete) {
		$this->TemplateField->delete($idToDelete);
		$this->Session->setFlash("Template Field Deleted!");
		$this->redirect($this->_getListUrl());
	}

	private function __setCommonViewVariables() {
		$this->set('list_url', $this->_getListUrl());

		// set the object(s) the view will need
		$this->set('cobrand', $this->TemplateField->getCobrand($this->_getParentControllerId()));
		$this->set('template', $this->TemplateField->getTemplate($this->_getParentControllerId()));
		$this->set('templatePage', $this->TemplateField->getTemplatePage($this->_getParentControllerId()));
		$this->set('templateSection', $this->TemplateField->getTemplateSection($this->_getParentControllerId()));

		$this->set('fieldTypes', $this->TemplateField->fieldTypes);
		$this->set('sourceTypes', $this->TemplateField->sourceTypes);
	}
}
