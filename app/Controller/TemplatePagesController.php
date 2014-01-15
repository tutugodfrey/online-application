<?php
App::uses('Sanitize', 'Utility');
App::uses('NestedResourceController', 'Controller');
/**
 * TemplatePages Controller
 *
 */
class TemplatePagesController extends NestedResourceController {

	protected $_parentCtrlName = 'Templates';

	protected $_controllerName = 'TemplatePages';

	public function admin_add() {
		if ($this->request->is('post')) {
			$data = Sanitize::clean($this->request->data);
			// we know the template_id from the uri
			$data['TemplatePage']['template_id'] = $this->_getParentControllerId();
			debug($data);
			if ($this->TemplatePage->save($data)) {
				$this->Session->setFlash("Template Page Saved!");
				$this->redirect($this->_getListUrl());
			}
		}

		// is this the way to access another model?
		$Template = ClassRegistry::init('Template');
		$this->set('templates', $Template->getList());
		$this->set('list_url', $this->_getListUrl());
		$this->set('template', $this->TemplatePage->getTemplate($this->_getParentControllerId()));
		$this->set('cobrand', $this->TemplatePage->getCobrand($this->_getParentControllerId()));
	}

	public function admin_edit($idToEdit) {
		$this->TemplatePage->id = $idToEdit;
		if (empty($this->request->data)) {
			$this->request->data = $this->TemplatePage->read();
		} else {
			// try to update the templatePage
			if ($this->TemplatePage->saveAll(Sanitize::clean($this->request->data))) {
				$this->Session->setFlash("Template Page Saved!");
				$this->redirect($this->_getListUrl());
			}
		}
		$this->set('list_url', $this->_getListUrl());

		// TODO: update the model to get the parent template and also the cobrand
		$this->set('template_id', $this->_getParentControllerId());
		$this->set('cobrand', $this->TemplatePage->getCobrand($this->_getParentControllerId()));
		$this->set('template', $this->TemplatePage->getTemplate($this->_getParentControllerId()));
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
		$this->set('list_url', $this->_getListUrl());

		// TODO: update the model to get the parent template and also the cobrand
		$this->set('template_id', $this->_getParentControllerId());
		$this->set('cobrand', $this->TemplatePage->getCobrand($this->_getParentControllerId()));
		$this->set('template', $this->TemplatePage->getTemplate($this->_getParentControllerId()));
	}

	public function admin_delete($idToDelete) {
		$this->TemplatePage->delete($idToDelete);
		$this->Session->setFlash("TemplatePage Deleted!");
		$this->redirect($this->_getListUrl());
	}

}