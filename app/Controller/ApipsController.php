<?php
App::uses('AppController', 'Controller');
class ApipsController extends AppController {

    function beforeFilter() {
        parent::beforeFilter();
    }

/**
 * Create Index
 *
 * @return null
 */
	public function admin_index() {
		$this->paginate = array(
			'contain' => array('User' => array('fields' => array('id', 'fullname'))),
		);
		$this->set('apips', $this->paginate());
	}
/**
 * Create Edit view
 *
 * @param integer $id Group id
 * @return null
 */
	public function admin_edit($id = null) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Apip->save($this->request->data)) {
				$this->Session->setFlash(Inflector::singularize($this->name) . " has been saved", 'default', array('class' => 'alert alert-success'));
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash("Something went wrong " . Inflector::singularize($this->name) . " could not be saved!", 'default', array('class' => 'alert alert-danger'));
			}
		}
		if (!empty($id)) {
			$this->request->data = $this->Apip->find('first', ['conditions' => array('Apip.id' => $id)]);
		}
		$users = $this->Apip->User->find('list', array('order' => 'fullname ASC'));
		$this->set('users', $users);
	}
}
?>