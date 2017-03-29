<?php
App::uses('AppController', 'Controller');
class GroupsController extends AppController {

/**
 * beforeFilter
 *
 * @return null
 */
	public function beforeFilter() {
		parent::beforeFilter();
	}

/**
 * Create Index
 *
 * @return null
 */
	public function admin_index() {
		$this->paginate = array(
			'order' => array(
				'Group.name' => 'ASC',
			),
		);
		$this->set('groups', $this->paginate());
	}
/**
 * Create Edit view
 *
 * @param integer $id Group id
 * @return null
 */
	public function admin_edit($id = null) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(Inflector::singularize($this->name) . " has been saved", 'default', array('class' => 'alert alert-success'));
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash("Something went wrong " . Inflector::singularize($this->name) . " could not be saved!", 'default', array('class' => 'alert alert-danger'));
			}
		}
		if (!empty($id)) {
			$this->request->data = $this->Group->find('first', ['conditions' => array('id' => $id)]);
		}
	}
}