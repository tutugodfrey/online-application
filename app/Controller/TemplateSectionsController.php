<?php
App::uses('Sanitize', 'Utility');
App::uses('NestedResourceController', 'Controller');
/**
 * TemplateSections Controller
 *
 */
class TemplateSectionsController extends NestedResourceController {

  protected $_parent_controller_name = 'TemplatePages';
  protected $_controller_name = 'TemplateSections';

  public function admin_add() {
    if ($this->request->is('post')) {
      $data = Sanitize::clean($this->request->data);
      // we know the page_id from the uri
      $data['TemplateSection']['page_id'] = $this->_getParentControllerId();
      if ($this->TemplateSection->save($data)) {
        $this->Session->setFlash("Template Section Saved!");
        $this->redirect($this->_getListUrl());
      }
    }

    $this->set('list_url', $this->_getListUrl());

    // expose the cobrand and template objects
    $this->set('cobrand', $this->TemplateSection->getCobrand($this->_getParentControllerId()));
    $this->set('template', $this->TemplateSection->getTemplate($this->_getParentControllerId()));
    $this->set('templatePage', $this->TemplateSection->getTemplatePage($this->_getParentControllerId()));
  }

  public function admin_edit($id) {
    $this->TemplateSection->id = $id;
    if (empty($this->request->data)){
        $this->request->data = $this->TemplateSection->read();
    } else {
      $data = Sanitize::clean($this->request->data);
      // we know the page_id from the uri
      $data['TemplateSection']['page_id'] = $this->_getParentControllerId();
      if ($this->TemplateSection->saveAll(Sanitize::clean($data))) {
        $this->Session->setFlash("Template Section Saved!");
        $this->redirect($this->_getListUrl());
      }
    }
    $this->set('list_url', $this->_getListUrl());

    $this->set('cobrand', $this->TemplateSection->getCobrand($this->_getParentControllerId()));
    $this->set('template', $this->TemplateSection->getTemplate($this->_getParentControllerId()));
    $this->set('templatePage', $this->TemplateSection->getTemplatePage($this->_getParentControllerId()));
  }

  public function admin_index() {
    $this->paginate = array(
      'limit' => 25,
      'order' => array('TemplateSection.order' => 'ASC'),
      'conditions' => array('TemplateSection.page_id' => $this->_getParentControllerId())
    );
        
    $data = $this->paginate('TemplateSection');
    $this->set('templateSections', $data);
    $this->set('scaffoldFields', array_keys($this->TemplateSection->schema()));
    $this->set('list_url', $this->_getListUrl());

    // expose the cobrand and template objects
    $this->set('cobrand', $this->TemplateSection->getCobrand($this->_getParentControllerId()));
    $this->set('template', $this->TemplateSection->getTemplate($this->_getParentControllerId()));
    $this->set('templatePage', $this->TemplateSection->getTemplatePage($this->_getParentControllerId()));
  }

  public function admin_delete($id) {
    $this->TemplateSection->delete($id);
    $this->Session->setFlash("Template Section Deleted!");
    $this->redirect($this->_getListUrl());
  }

}
