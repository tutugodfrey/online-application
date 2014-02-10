<?php
App::uses('AppController', 'Controller');
/**
 * CobrandedApplications Controller
 *
 * @property CobrandedApplication $CobrandedApplication
 * @property 'EmailComponent $'Email
 * @property RequestHandlerComponent $RequestHandler
 * @property SecurityComponent $Security
 * @property Search.Prg'Component $Search.Prg'
 */
class CobrandedApplicationsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Email', 'RequestHandler', 'Security', 'Search.Prg');

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($uuid = null) {
		if (!$this->CobrandedApplication->hasAny(array('CobrandedApplication.uuid' => $uuid))) {
			throw new NotFoundException(__('Invalid application'));
		}
		$options = array('conditions' => array('CobrandedApplication.uuid' => $uuid));
		$this->set('cobrandedApplication', $this->CobrandedApplication->find('first', $options));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($uuid = null) {
		if (!$this->CobrandedApplication->hasAny(array('CobrandedApplication.uuid' => $uuid))) {
			throw new NotFoundException(__('Invalid application'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CobrandedApplication->save($this->request->data)) {
				$this->Session->setFlash(__('The application has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The application could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CobrandedApplication.uuid' => $uuid));
			$this->request->data = $this->CobrandedApplication->find('first', $options);
		}
		$users = $this->CobrandedApplication->User->find('list');
		$this->set(compact('users'));

		$template = $this->CobrandedApplication->Template->find(
			'first', array(
				'contain' => array(
					'Cobrand',
					'TemplatePages' => array(
						'TemplateSections' => array(
							'TemplateFields'
						)
					)
				),
				'conditions' => array('Template.id' => $this->request->data['Template']['id'])
			)
		);
		$this->set('templatePages', $template['TemplatePages']);
		$this->set('bad_characters', array(' ', '&', '#', '$', '(', ')', '/', '%', '\.', '.', '\''));
		$this->set('states', array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York", 'NC'=>"North Carolina", 'ND'=>"North Dakota", 'OH'=>"Ohio", 'OK'=>"Oklahoma", 'OR'=>"Oregon", 'PA'=>"Pennsylvania", 'RI'=>"Rhode Island", 'SC'=>"South Carolina", 'SD'=>"South Dakota", 'TN'=>"Tennessee", 'TX'=>"Texas", 'UT'=>"Utah", 'VT'=>"Vermont", 'VA'=>"Virginia", 'WA'=>"Washington", 'WV'=>"West Virginia", 'WI'=>"Wisconsin", 'WY'=>"Wyoming"));
	}


/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->CobrandedApplication->recursive = 0;
		$this->set('cobrandedApplications', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->CobrandedApplication->exists($id)) {
			throw new NotFoundException(__('Invalid application'));
		}
		$options = array('conditions' => array('CobrandedApplication.' . $this->CobrandedApplication->primaryKey => $id));
		$this->set('cobrandedApplication', $this->CobrandedApplication->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->CobrandedApplication->create();
			if ($this->CobrandedApplication->save($this->request->data)) {
				$this->Session->setFlash(__('The application has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The application could not be saved. Please, try again.'));
			}
		} else {
			$this->CobrandedApplication->create(
				array(
					'user_id' => $this->Session->read('Auth.User.id'),
					'uuid' => String::uuid()
				)
			);
			$this->request->data = $this->CobrandedApplication->data;
		}
		$users = $this->CobrandedApplication->User->find('list', array('order' => 'firstname, lastname'));
		$this->set(compact('users'));
		$templates = $this->CobrandedApplication->User->Template->getList();
		$this->set(compact('templates'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->CobrandedApplication->exists($id)) {
			throw new NotFoundException(__('Invalid application'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CobrandedApplication->save($this->request->data)) {
				$this->Session->setFlash(__('The application has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The application could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CobrandedApplication.' . $this->CobrandedApplication->primaryKey => $id));
			$this->request->data = $this->CobrandedApplication->find('first', $options);
		}
		$users = $this->CobrandedApplication->User->find('list');
		$this->set(compact('users'));
		$templates = $this->CobrandedApplication->User->Template->getList();
		$this->set(compact('templates'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->CobrandedApplication->id = $id;
		if (!$this->CobrandedApplication->exists()) {
			throw new NotFoundException(__('Invalid application'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->CobrandedApplication->delete()) {
			$this->Session->setFlash(__('Application deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Application was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
