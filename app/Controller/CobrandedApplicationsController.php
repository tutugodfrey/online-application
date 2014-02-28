<?php
App::uses('AppController', 'Controller');
App::uses('TemplateField', 'View/Helper');
App::uses('Setting', 'Model');
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
		parent::beforeFilter();
		$this->Auth->allow('quickAdd');
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;

		if (($this->params['ext'] == 'json' || $this->request->accepts('application/json'))) {
			$this->Security->unlockedActions= array('api_add');
		}
		$this->fetchSettings();
		$this->settings = Configure::read('Setting');
	}

	public function beforeRender() {
		parent::beforeRender();
		if ($this->request->accepts('application/json')) {
			Configure::write('debug', 0);
			$this->disableCache();
		}
	}


/**
 * quickAdd method
 *
 * @param $uuid (optional)
 * @throws NotFoundException
 * @return void
 */
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
			$response = $this->CobrandedApplication->saveApplicationValue($this->request->data);

			if ($response['success'] == true) {
				// all good
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
 * api_add method
 *
 * @param none (post data)
 * @return void
 */
	public function api_add() {
		$this->autoRender = false;
		$response = array('success' => false);

		$user = $this->User->find(
			'first', 
			array(
				'conditions' => array('User.id' => $this->Auth->user('id')),
			)
		);

		if ($this->request->is('get')) {
			// dump the template form
			// pull the template_id from the db because the Auth object could be cached
			if (!is_null($user['User']['template_id'])) {
				$response['success'] = true;
				$response['template'] = array(
					'fields' => $this->User->Template->getTemplateApiFields($user['User']['template_id']),
				);
			} else {
				$response = Hash::insert(
					$response,
					'msg',
					'A template has not been configured for partners with id ['.$user['User']['cobrand_id'].']');
			}
		} else if ($this->request->is('post')) {
			switch ($this->Auth->user('cobrand_id')) {
				case 8: // Appfolio
					$response = $this->CobrandedApplication->saveFields($user['User'], $this->request->data);
					break;

				default:
					// no other api partners have been set up as of yet
					$response = Hash::insert(
						$response,
						'msg',
						'This API has not been enabled for partners with id ['.$this->Auth->user('cobrand_id').']');
					break;
			}
		} else {
			$response = Hash::insert($response, 'msg', 'Expecting POST data.');
		}

		echo json_encode($response);
		$this->redirect(null, 200);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $uuid
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
 * @param string $uuid
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
				$user = $this->User->read();
				$response = $this->CobrandedApplication->createOnlineappForUser($user['User'], $this->request->data['CobrandedApplication']['uuid']);
				if ($response['success'] == true) {
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
			$templates = $this->CobrandedApplication->User->Template->getList($this->Auth->user('cobrand_id'));
			$this->set(compact('templates'));
		} else {
			// just create it, we know all the info
			$user = $this->User->read();
			$response = $this->CobrandedApplication->createOnlineappForUser($user['User']);
			if ($response['success'] == true) {
				$this->Session->setFlash(__('Application created'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The application could not be saved. Please, try again.'));
			}
		}
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
 * admin_export method
 * 
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_export($id = null) {
		if (!$this->CobrandedApplication->exists($id)) {
			throw new NotFoundException(__('Invalid application'));
		}

		$keys = '';
		$values = '';
		$this->CobrandedApplication->buildExportData($id, $keys, $values);

		// the easy way...
		$this->set('keys', $keys);
		$this->set('values', $values);

		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=\"{$id}.csv\"");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
		header("Pragma: public");

		$csv = $this->render('/Elements/cobranded_applications/export', false);
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
