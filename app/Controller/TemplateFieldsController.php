<?php
App::uses('Sanitize', 'Utility');
App::uses('NestedResourceController', 'Controller');
/**
 * TemplateFields Controller
 *
 */
class TemplateFieldsController extends NestedResourceController {

  protected $_parent_controller_name = 'TemplateSections';
  protected $_controller_name = 'TemplateFields';

  public function admin_add() {
    if ($this->request->is('post')) {
      $data = Sanitize::clean($this->request->data);
      // we know the page_id from the uri
      $data['TemplateField']['section_id'] = $this->_getParentControllerId();
      if ($this->TemplateField->save($data)) {
        $this->Session->setFlash("Template Field Saved!");
        $this->redirect($this->_getListUrl());
      }
    }

    $this->set('list_url', $this->_getListUrl());

    // set the object(s) the view will need
    $this->set('cobrand', $this->TemplateField->getCobrand($this->_getParentControllerId())); 
    $this->set('template', $this->TemplateField->getTemplate($this->_getParentControllerId()));
    $this->set('templatePage', $this->TemplateField->getTemplatePage($this->_getParentControllerId()));
    $this->set('templateSection', $this->TemplateField->getTemplateSection($this->_getParentControllerId()));
  }

  public function admin_edit($id) {
    $this->TemplateField->id = $id;
    if (empty($this->request->data)){
        $this->request->data = $this->TemplateField->read();
    } else {
      $data = Sanitize::clean($this->request->data);
      // we know the page_id from the uri
      $data['TemplateField']['section_id'] = $this->_getParentControllerId();
      if ($this->TemplateField->saveAll(Sanitize::clean($data))) {
        $this->Session->setFlash("Template Field Saved!");
        $this->redirect($this->_getListUrl());
      }
    }

    $this->set('list_url', $this->_getListUrl());

    // set the object(s) the view will need
    $this->set('cobrand', $this->TemplateField->getCobrand($this->_getParentControllerId())); 
    $this->set('template', $this->TemplateField->getTemplate($this->_getParentControllerId()));
    $this->set('templatePage', $this->TemplateField->getTemplatePage($this->_getParentControllerId()));
    $this->set('templateSection', $this->TemplateField->getTemplateSection($this->_getParentControllerId()));
  }

  public function admin_index() {
    $this->pthaginate = array(
      'limit' => 25,
      'order' => array('TemplateField.name' => 'ASC'),
      'conditions' => array('TemplateField.section_id' => $this->_getParentControllerId())
    );
        
    $data = $this->paginate('TemplateField');
    $this->set('templateFields', $data);
    $this->set('scaffoldFields', array_keys($this->TemplateField->schema()));
    $this->set('list_url', $this->_getListUrl());

    // set the object(s) the view will need
    $this->set('cobrand', $this->TemplateField->getCobrand($this->_getParentControllerId())); 
    $this->set('template', $this->TemplateField->getTemplate($this->_getParentControllerId()));
    $this->set('templatePage', $this->TemplateField->getTemplatePage($this->_getParentControllerId()));
    $this->set('templateSection', $this->TemplateField->getTemplateSection($this->_getParentControllerId()));
  }

  public function admin_delete($id) {
    $this->TemplateField->delete($id);
    $this->Session->setFlash("Template Field Deleted!");
    $this->redirect($this->_getListUrl());
  }
}
