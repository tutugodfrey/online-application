<?php
App::uses('AppController', 'Controller');
App::uses('TemplateField', 'View/Helper');
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

	public $helper = array('TemplateField');

	public function beforeFilter() {
		$this->Auth->allow('quickAdd');
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;
		//$this->DebugKit->
	}

	public function quickAdd($uuid = null) {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if($this->RequestHandler->isAjax()) {
			if (!$this->CobrandedApplication->hasAny(array('CobrandedApplication.uuid' => $uuid))) {
				throw new NotFoundException(__('Invalid application'));
			}

			if (strlen($this->request->data['id']) == 0) {
				throw new NotFoundException(__('Invalid application value id'));
			}

			$this->RequestHandler->setContent('json', 'application/json');

			if (!$this->CobrandedApplication->CobrandedApplicationValues->hasAny(
					array(
						'id' => $this->request->data['id'],
					))) {
				throw new NotFoundException(__('Invalid application value'));
			}

			// make sure the value has changed
			$applicationValue = $this->CobrandedApplication->CobrandedApplicationValues->find($this->request->data['id']);
			if ($applicationValue['CobrandedApplicationValue']['value'] != $this->request->data['value']) {
				$this->CobrandedApplication->CobrandedApplicationValues->id = $this->request->data['id'];
				$succeeded = false;
				if ($this->CobrandedApplication->CobrandedApplicationValues->savefield('value', $this->request->data['value'])) {
					$response = 'CobrandedApplicationValue with id ['.$this->request->data['id'].'] succeeded';
					$succeeded = true;
				} else {
					$response = 'Failed to update CobrandedApplicationValue with id ['.$this->request->data['id'].']';
				}
				$response = json_encode($response);
				$this->set(compact('response', 'succeeded'));
				$this->set('_serialize', 'response');
				$this->render('quickAdd');
			} else {
				$error = json_encode("Application Value could not be saved. Try later");
				$this->set(compact('error'));
				$this->set('_serialize', 'error');
				$this->render('error');
			}
		} else {
			$error = array("Not an Ajax request");
			$this->set(compact('error'));
			$this->set('_serialize', 'error');
			$this->render('error');
		}
	}


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

		$template = $this->CobrandedApplication->getTemplateAndAssociatedValues($this->request->data['CobrandedApplication']['id']);

		$this->set('templatePages', $template['Template']['TemplatePages']);
		$this->set('cobrandedApplicationValues', $template['CobrandedApplicationValues']);
		$this->set('bad_characters', array(' ', '&', '#', '$', '(', ')', '/', '%', '\.', '.', '\''));

		// if it is a rep viewing/editing the application don't require fields to be filled in
		// but if they do have data, validate it
		$this->set('requireRequiredFields', false);
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
		// look up the user to make sure the we don't get stale session data
		$this->User->read(null, $this->Session->read('Auth.User.id'));

		if (is_null($this->User->field('template_id'))) {
			if ($this->request->is('post')) {
				// save the template_id for the user
				$this->User->read(null, $this->User->id);
				$this->User->set('template_id', $this->request->data['CobrandedApplication']['template_id']);
				$this->User->save();

				// now try to save with the data from the user model
				if ($this->__createOnlineappForUser($this->User, $this->request->data['CobrandedApplication']['uuid'])) {
					$this->Session->setFlash(__('Application created'));
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
		} else {
			// just create it, we know all the info
			if ($this->__createOnlineappForUser($this->User)) {
				$this->Session->setFlash(__('Application created'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The application could not be saved. Please, try again.'));
			}
		}
	}

/**
 * __createOnlineappForUser method
 *
 * @param none
 * @return [true|false] depending on if the onlineapp was created
 */
	private function __createOnlineappForUser($user, $uuid = null) {
		if (is_null($uuid)) {
			$uuid = String::uuid();
		}

		$this->CobrandedApplication->create(
			array(
				'user_id' => $this->User->field('id', array('User.id' => $user->id)),
				'uuid' => $uuid,
				'template_id' => $this->User->field('template_id', array('User.id' => $user->id)),
			)
		);

		if ($this->CobrandedApplication->save()) {
			return true;
		}
		return false;
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
