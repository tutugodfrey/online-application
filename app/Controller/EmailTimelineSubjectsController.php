<?php
App::uses('AppController', 'Controller');
class EmailTimelineSubjectsController extends AppController {

	public $scaffold = 'admin';
	//public $permissions = array();
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
			'recursive' => -1,
			'order' => array(
				'EmailTimelineSubject.subject' => 'ASC',
			),
		);
		$this->set('emailTimelineSubjects', $this->paginate());
	}

/**
 * Create Edit view
 *
 * @param integer $id Group id
 * @return null
 */
	public function admin_edit($id = null) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->EmailTimelineSubject->save($this->request->data)) {
				$this->_success(Inflector::singularize($this->name) . " has been saved");
				$this->redirect($this->referer());
			} else {
				$this->_failure("Something went wrong " . Inflector::singularize($this->name) . " could not be saved!");
			}
		}
		if (!empty($id)) {
			$this->request->data = $this->EmailTimelineSubject->find('first', ['conditions' => array('id' => $id)]);
		}
	}
}
?>