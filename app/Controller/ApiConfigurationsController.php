<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/**
 * ApiConfigurations Controller
 */
class ApiConfigurationsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Email', 'RequestHandler', 'Security');

	public $permissions = array(
		'admin_index' => array(User::ADMIN),
		'admin_add' => array(User::ADMIN),
		'admin_edit' => array(User::ADMIN),
	);
/**
 * index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = [
				'type' => 'all',
				'findType' => 'all',
				'order' => 'configuration_name ASC'
		];
		$this->set('apiConfigs', $this->paginate());
	}

/**
 * add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			if ($this->ApiConfiguration->save($this->request->data)) {
				$this->_success(__($this->request->data['ApiConfiguration']['configuration_name'] . ' connection config has been saved. In order to use it additional dev work may be required.'), ['action' => 'index']);
			} else {
				$this->_failure(__('Unexpected error! Could not save data. Please try again'));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id ApiConfigurations id
 * @throws NotFoundException
 * @return void
 */
	public function admin_edit($id = null) {
		$this->ApiConfiguration->id = $id;
		if (!$this->ApiConfiguration->exists()) {
			$this->_failure(__('Invalid API configuration id!'), $this->referer());
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ApiConfiguration->save($this->request->data)) {
				$this->_success(__('The configuration has been saved'), ['action' => 'index']);
			} else {
				$this->_failure(__('The configuration could not be saved. Please, try again.'), ['action' => 'index']);
			}
		} else {
			$this->request->data = $this->ApiConfiguration->find('first', array('conditions' => array('id' => $id)));
		}
	}

}
