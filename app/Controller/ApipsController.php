<?php
App::uses('AppController', 'Controller');
class ApipsController extends AppController {
	public $scaffold = 'admin';
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
				$this->_success(Inflector::singularize($this->name) . " has been saved", $this->referer());
			} else {
				$this->_failure(__("Something went wrong " . Inflector::singularize($this->name) . " could not be saved!"));
			}
		}
		if (!empty($id)) {
			$this->request->data = $this->Apip->find('first', array('conditions' => array('Apip.id' => $id)));
		}
		$users = $this->Apip->User->find('list', array('order' => 'fullname ASC'));
		$this->set('users', $users);
	}
}
?>