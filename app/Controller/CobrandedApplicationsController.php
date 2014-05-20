<?php
App::uses('AppController', 'Controller');
App::uses('TemplateField', 'View/Helper');
App::uses('Setting', 'Model');
App::uses('Validation', 'Utility');

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

	public $permissions = array(
		'add' => array('admin', 'rep', 'manager'),
		'api_add' => array('api'),
	);

	public $helper = array('TemplateField');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('quickAdd');
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;

		if (($this->params['ext'] == 'json' || $this->request->accepts('application/json'))) {
			$this->Security->unlockedActions= array('api_add');
		} else {
			// are we authenticated?
			if (is_null($this->Auth->user('id'))) {
				// not authenticated
				// next -> were we passed a valid uuid?
				$uuid = (isset($this->params['pass'][0]) ? $this->params['pass'][0] : '');
				if (Validation::uuid($uuid)) {
					// yup, allow edit action
					$this->Auth->allow('edit');
					// could look it up even
				} else {
					// invalid uuid - allow retrievel of their application via their email
					$this->Auth->allow('retrieve', 'retrieve_thankyou');
				}
			}
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
			// make sure user is api_enabled
			if ($user['User']['api_enabled']) {
				// also make sure a template is setup
				if (!is_null($user['User']['template_id'])) {
					$response = $this->CobrandedApplication->saveFields($user['User'], $this->request->data);

					if ($response['success'] == true) {
						$this->Cobrand = ClassRegistry::init('Cobrand');
						$cobrand = $this->Cobrand->find(
							'first', 
							array(
								'conditions' => array('Cobrand.id' => $user['User']['cobrand_id']),
							)
						);

						$args = array(
							'cobrand' => $cobrand['Cobrand']['partner_name'],
							'link' => $response['application_url']
						);

						// send email to data entry
						$emailResponse = $this->CobrandedApplication->sendNewApiApplicationEmail($args);

						// add email timeline event
						unset($args);
						$args = array(
							'cobranded_application_id' => $response['application_id']
						);
						$timelineResponse = $this->CobrandedApplication->createNewApiApplicationEmailTimelineEntry($args);
					}
				} else {
					$response = Hash::insert(
					$response,
					'msg',
					'A template has not been configured for partners with id ['.$user['User']['cobrand_id'].']');
				}
			} else {
				$response = Hash::insert(
					$response,
					'msg',
					'This API has not been enabled for partners with id ['.$this->Auth->user('cobrand_id').']');
			}
		} else {
			$response = Hash::insert($response, 'msg', 'Expecting POST data.');
		}

		echo json_encode($response);
		$this->redirect(null, 200);
	}

/**
 * retrieve
 * 
 * 
 */
	public function retrieve() {
		$error = '';
		if ($this->request->is('post')) {
			// did we get a valid email?
			$email = $this->request->data['CobrandedApplication']['email'];
			if (Validation::email($email)) {
				// yes, does this email have any applications associated with it?

				// yes, update the application uuid and send the email via the model
				$response = $this->CobrandedApplication->sendFieldCompletionEmail($email);
				debug($response);

				$this->render('retrieve_thankyou');
			} else {
				$error = 'Invalid email address submitted.';
			}
		}

		$this->set('error', $error);
	}

/**
 * edit method
 *
 * @param string $uuid
 * @return void
 */
	public function edit($uuid = null) {
		if (!$this->CobrandedApplication->hasAny(array('CobrandedApplication.uuid' => $uuid))) {
			// redirect to a retrieve page
			$this->redirect(array('action' => 'retrieve'));
		} else {
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

			$template = $this->CobrandedApplication->getTemplateAndAssociatedValues($this->request->data['CobrandedApplication']['id'], $this->Auth->user('id'));

			$this->set('cobrand_logo_url', $template['Template']['Cobrand']['logo_url']);
			$this->set('include_axia_logo', $template['Template']['include_axia_logo']);
			$this->set('cobrand_logo_position', $template['Template']['logo_position']);
			$this->set('rightsignature_template_guid', $template['Template']['rightsignature_template_guid']);
			$this->set('templatePages', $template['Template']['TemplatePages']);
			$this->set('bad_characters', array(' ', '&', '#', '$', '(', ')', '/', '%', '\.', '.', '\''));

			// if it is a rep viewing/editing the application don't require fields to be filled in
			// but if they do have data, validate it
			$this->set('requireRequiredFields', false);
		}
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
 * admin_copy method
 * 
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_copy($id = null) {
		if (!$this->CobrandedApplication->exists($id)) {
			throw new NotFoundException(__('Invalid application'));
		}

		$flashMsg = 'Failed to copy application';
		if ($this->CobrandedApplication->copyApplication($id, $this->Session->read('Auth.User.id'))) {
			$flashMsg = 'Application copied';
		}
		$this->Session->setFlash(__($flashMsg));
		$this->redirect(array('action' => 'index'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->CobrandedApplication->id = $id;
		if (!$this->CobrandedApplication->exists()) {
			throw new NotFoundException(__('Invalid application'));
		}
		$this->request->onlyAllow('post', 'delete');
		$flashMsg = 'Application was not deleted';
		if ($this->CobrandedApplication->delete()) {
			$flashMsg = 'Application deleted';
		}
		$this->Session->setFlash(__($flashMsg));
		$this->redirect(array('action' => 'index'));
	}

/**
 * create_rightsignature_document
 *
 * @params
 *     $applicationId int
 *     $signNow boolean
 */
	public function create_rightsignature_document($applicationId = null, $signNow = null) {
		$this->autoRender = false;
		$this->CobrandedApplication->id = $applicationId;

		if (!$this->CobrandedApplication->exists()) {
			throw new NotFoundException(__('Invalid application'));
		}

		$cobrandedApplication = $this->CobrandedApplication->read();

		if (!empty($cobrandedApplication['CobrandedApplication']['rightsignature_document_guid']) && $signNow == true) {
			$this->redirect(array('action' => '/sign_rightsignature_document?guid='.$cobrandedApplication['CobrandedApplication']['rightsignature_document_guid']));
		} else {
			$client = $this->CobrandedApplication->createRightSignatureClient();
			$templateGuid = $cobrandedApplication['Template']['rightsignature_template_guid'];
			$response = $this->CobrandedApplication->getRightSignatureTemplate($client, $templateGuid);
			$response = json_decode($response, true);

			if ($response && $response['template']['type'] == 'Document' && $response['template']['guid']) {
				$applicationXml = $this->CobrandedApplication->createRightSignatureApplicationXml(
					$applicationId, $this->Session->read('Auth.User.email'), $response['template']);

				// send the document
				//$this->set('document_guid', $response['template']['guid']);
				//$this->set('data', $cobrandedApplication);

				$response = $this->CobrandedApplication->createRightSignatureDocument($client, $applicationXml);
				debug($response);

			} else {
				$url = "/edit/".$cobrandedApplication['CobrandedApplication']['uuid'];
				$this->Session->setFlash(__('error! could not find template guid'));
				$this->redirect(array('action' => $url));
			}

		}
	}
}















