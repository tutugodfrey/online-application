<?php
App::uses('Sanitize', 'Utility');
App::uses('NestedResourceController', 'Controller');
/**
 * OnlineappTemplates Controller
 *
 */
class TemplatesController extends NestedResourceController {

  protected $_parent_controller_name = "Cobrands";
  protected $_controller_name = "Templates";

  public function admin_add() {
    if ($this->request->is('post')) {
      $data = Sanitize::clean($this->request->data);
      // set the cobrand_id
      $data['Template']['cobrand_id'] = $this->_getParentControllerId();
      if ($this->Template->save($data)) {
        $this->Session->setFlash("Template Saved!");
        $this->redirect($this->_getListUrl());
      }
    }

    // is this the way to access another model?
    $Cobrand = ClassRegistry::init('Cobrand');
    $this->set('cobrands', $Cobrand->getList());
    $this->set('cobrand', $this->Template->getCobrand($this->_getParentControllerId()));
    $this->set('logo_position_types', $this->Template->logo_position_types);
  }

  public function admin_edit($id) {
    $this->Template->id = $id;
    if (empty($this->request->data)){
        $this->request->data = $this->Template->read();
    } else {
      // try to update the template
      if ($this->Template->saveAll(Sanitize::clean($this->request->data))) {
        $this->Session->setFlash("Template Saved!");
        $this->redirect($this->_getListUrl());
      }
    }

    // is this the way to access another model?
    $Cobrand = ClassRegistry::init('Cobrand');
    $this->set('cobrands', $Cobrand->getList());
    $this->set('cobrand', $this->Template->getCobrand($this->_getParentControllerId()));
    $this->set('logo_position_types', $this->Template->logo_position_types);
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
    $this->set('cobrand', $this->Template->getCobrand($this->_getParentControllerId()));
    $this->set('list_url', $this->_getListUrl());
    $this->set('logo_position_types', $this->Template->logo_position_types);
  }

  public function admin_delete($id) {
    $this->Template->delete($id, false);
    $this->Session->setFlash("Template Deleted!");
    $this->redirect($this->_getListUrl());
  }

  public function admin_preview($id) {
    $this->Template->id = $id;
    $template = $this->Template->find(
      'first', array(
        'contain' => array(
          'Cobrand',
          'TemplatePages' => array(
            'TemplateSections' => array(
              'TemplateFields'
            )
          )
        ),
        'conditions' => array('Template.id' => $id)
      )
    );

    $this->set('template', $template);
    $this->set('logo_position_types', $this->Template->logo_position_types);
    $this->set('bad_characters', array(' ', '&', '#', '$', '(', ')', '/', '%', '\.', '.', '\''));
  }
}
