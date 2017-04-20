<?php
App::uses('AppController', 'Controller');
class SettingsController extends AppController {

	public $permissions = array();

	public function beforeFilter() {
		parent::beforeFilter();

		$this->loadModel('User');
		if (!$this->Auth->user('group_id') || $this->User->Group->field('name', array('id' => $this->Auth->user('group_id'))) != 'admin') {
			header("HTTP/1.0 403 Forbidden");
			exit;
		}
	}

/**
 * Create Index
 *
 * @return null
 */
	public function admin_index() {
		$this->set('settings', $this->paginate());
	}

/**
 * Create Edit view
 *
 * @param integer $id Setting id
 * @return null
 */
	public function admin_edit($id = null) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Setting->save($this->request->data)) {
				$this->_success(Inflector::singularize($this->name) . " has been saved");
				$this->redirect($this->referer());
			} else {
				$this->_failure("Something went wrong " . Inflector::singularize($this->name) . " could not be saved!");
			}
		}
		if (!empty($id)) {
			$this->request->data = $this->Setting->find('first', ['conditions' => array('key' => $id)]);
		}
	}

}
?>
