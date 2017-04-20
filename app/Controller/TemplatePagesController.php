<?php
App::uses('NestedResourceController', 'Controller');
/**
 * TemplatePages Controller
 *
 */
class TemplatePagesController extends NestedResourceController {

	protected $_parentCtrlName = 'Templates';

	protected $_controllerName = 'TemplatePages';

	public $permissions = array(		
		'admin_index' => array('admin', 'rep', 'manager'),
		'admin_add' => array('admin', 'rep', 'manager'),
		'admin_edit' => array('admin', 'rep', 'manager'),
		'admin_delete' => array('admin', 'rep', 'manager'),
	);

	public function admin_add() {
		if ($this->request->is('post')) {
			$data = $this->request->data;
			// we know the template_id from the uri
			$data['TemplatePage']['template_id'] = $this->_getParentControllerId();
			$this->TemplatePage->create();
			if ($this->TemplatePage->save($data)) {
				$this->_success("Template Page Saved!");
				return $this->redirect($this->_getListUrl());
			}
			$this->_failure(__('Unable to add your page.'));
		}

		$this->__setCommonViewVariables();
	}

	public function admin_edit($idToEdit) {
		$this->TemplatePage->id = $idToEdit;
		if (empty($this->request->data)) {
			$this->request->data = $this->TemplatePage->getById($idToEdit);
		} else {
			$data = $this->request->data;
			// we know the template_id from the uri
			$data['TemplatePage']['template_id'] = $this->_getParentControllerId();
			if ($this->TemplatePage->save($data)) {
				$this->_success("Template Page Saved!");
				return $this->redirect($this->_getListUrl());
			}
			$this->_failure(__('Unable to update your page'));
		}

		$this->__setCommonViewVariables();
		$template = $this->TemplatePage->getTemplate($this->request->data['TemplatePage']['template_id'], true);
		$this->set('maxOrderValue', count($template['TemplatePages']) - 2); // minus 2 because we are using a zero based index and we are not including the Validate Application page.
		$this->set('nameDisabled', !$this->TemplatePage->nameEditable($this->request->data['TemplatePage']['name']));
		$this->set('orderDisabled', !$this->TemplatePage->orderEditable($this->request->data['TemplatePage']['name']));
	}

	public function admin_index() {
		$this->paginate = array(
			'limit' => 25,
			'order' => array('TemplatePage.order' => 'ASC'),
			'conditions' => array('TemplatePage.template_id' => $this->_getParentControllerId())
		);

		$data = $this->paginate('TemplatePage');
		$this->set('templatePages', $data);
		$this->set('scaffoldFields', array_keys($this->TemplatePage->schema()));
		$this->__setCommonViewVariables();
	}

	public function admin_delete($idToDelete) {
		$this->TemplatePage->delete($idToDelete);
		$this->_success("TemplatePage Deleted!");
		$this->redirect($this->_getListUrl());
	}

	private function __setCommonViewVariables() {
		$this->set('list_url', $this->_getListUrl());
		$this->set('cobrand', $this->TemplatePage->getCobrand($this->_getParentControllerId()));
		$this->set('template', $this->TemplatePage->getTemplate($this->_getParentControllerId()));
	}

}
