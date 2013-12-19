<?php
App::uses('Sanitize', 'Utility');
App::uses('AppController', 'Controller');
/**
 * Cobrands Controller
 *
 */
class CobrandsController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
  public $scaffold = 'admin';

  protected $_list_url = '/admin/cobrands';

  public function admin_add() {
    $this->set('title_for_layout', 'Add Cobrand');

    if ($this->request->is('post')) {
      $data = Sanitize::clean($this->request->data);
      if ($this->Cobrand->save($data)) {
        $this->Session->setFlash("Cobrand Saved!");
        $this->redirect($this->_list_url);
      }
    }
  }

  public function admin_edit($id) {
    $this->Cobrand->id = $id;
    if (empty($this->request->data)){
        $this->request->data = $this->Cobrand->read();
    } else {
      // try to update the cobrand
      if ($this->Cobrand->saveAll(Sanitize::clean($this->request->data))) {
        $this->Session->setFlash("Cobrand Saved!");
        $this->redirect($this->_list_url);
      }
    }
  }

  public function admin_index() {
    $this->paginate = array(
      'limit' => 25,
      'order' => array('Cobrand.partner_name' => 'asc'),
    );

    $data = $this->paginate('Cobrand');
    $this->set('cobrands', $data);
    $this->set('scaffoldFields', array_keys($this->Cobrand->schema()));
  }

  public function admin_delete($id) {
    $this->Cobrand->delete($id, false);
    $this->Session->setFlash("Cobrand Deleted!");
    $this->redirect($this->_list_url);
  }
}
