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
			$this->set('rightsignature_install_template_guid', $template['Template']['rightsignature_install_template_guid']);
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
				$response = $this->CobrandedApplication->createRightSignatureDocument($client, $applicationXml);
			} else {
				$url = "/edit/".$cobrandedApplication['CobrandedApplication']['uuid'];
				$this->Session->setFlash(__('error! could not find template guid'));
				$this->redirect(array('action' => $url));
			}

			$response = json_decode($response, true);
	
			if ($response['document']['status'] == 'sent' && $response['document']['guid']) {
				// save the guid
				$this->CobrandedApplication->save(
					array(
						'CobrandedApplication' => array(
							'id' => $applicationId,
							'rightsignature_document_guid' => $response['document']['guid'],
							'status' => 'completed'
						)
					),
					array('validate' => false)
				);

				// check whether they want to sign in person
				if ($signNow) {
					$this->redirect(array('action' => 'sign_rightsignature_document?guid='.$response['document']['guid']));
				} else {
					// if not simply send the documents
					$emailResponse = $this->CobrandedApplication->sendApplicationForSigningEmail($applicationId);
				}
			} else {
				$url = "/edit/".$cobrandedApplication['CobrandedApplication']['uuid'];
				$this->Session->setFlash(__('error! could not send the document'));
				$this->redirect(array('action' => $url));
			}

			$this->autoRender = false;
		}
	}

/**
 * sign_rightsignature_document
 *     
 */
	public function sign_rightsignature_document() {
		$client = $this->CobrandedApplication->createRightSignatureClient();
		$this->set('rightsignature', $client);

		$is_mobile_safari = preg_match("/\bMobile\b.*\bSafari\b/", $_SERVER['HTTP_USER_AGENT']);
		$this->set('is_mobile_safari', $is_mobile_safari);

		// Width of Widget, CANNOT BE CHANGED and it cannot be dynamic. 706 is optimal size
		$widgetWidth = 706;
		$this->set('widgetWidth', $widgetWidth);

		// Height of widget is changeable (Optional)
		$widgetHeight = 500;
		$this->set('widgetHeight', $widgetHeight);

		$guid = htmlspecialchars($_REQUEST["guid"]);
		$this->set('guid', $guid);

		if (empty($guid)) {
			$this->Session->setFlash(__("Cannot find document with given GUID."));
			$this->redirect(array('action' => 'index'));
		}

		// Gets signer link and reloads this page after each successful signature to refresh the signer-links list
		$response = $this->CobrandedApplication->getRightSignatureSignerLinks($client, $guid);
		$this->set('response', $response);

		$xml = Xml::build($response);
		$xml = Xml::toArray($xml);
		$result = Set::normalize($xml);
		$this->set('xml', $xml);

		if ($this->CobrandedApplication->findByRightsignatureDocumentGuid($guid)) {
			$data = $this->CobrandedApplication->findByRightsignatureDocumentGuid($guid);
			$this->layout = 'default';

			if (key_exists('error', $xml) && 
				$xml['error']['message'] == "Document is already signed." &&
				$data['CobrandedApplication']['status'] != 'signed') {

			/* NEED TO DEAL WITH THIS
				if ($data['Coversheet']['status'] == 'validated') {
					$this->Session->write('Application.coversheet', 'pdf');
					App::import('Controller', 'Coversheets');
					$Coversheets = new CoversheetsController;
					$Coversheets->constructClasses();
					$Coversheets->pdf($data['Coversheet']['id']);
					$this->Application->Coversheet->saveField('status', 'sent');
					$this->email_coversheet($data['Coversheet']['id']);
					$this->Session->delete('Application.coversheet');
				}
			*/

				$send_email = 'true';

				foreach ($data['EmailTimeline'] as $emails) {
					if ($emails['email_timeline_subject_id'] == 2) {
						$send_email = 'false';
					}
				}

				if ($send_email != 'false') {
					$this->CobrandedApplication->repNotifySignedEmail($data['CobrandedApplication']['id']);
				}
				
			}          
		}
            
        /* NEED TO DEAL WITH THIS                              
		if ($this->Application->findByInstallVarRsDocumentGuid($guid)) {
			$data = $this->Application->findByInstallVarRsDocumentGuid($guid);
			$this->set('data', $data);
			$varSigner = true;
			$this->set('varSigner', $varSigner);
			if ($xml['error']['message'] == "Document is already signed." && $data['Application']['var_status'] != 'signed') {
				$this->Application->id = $data['Application']['id'];
				$this->Application->saveField('var_status', 'signed');
				$this->set('data', $data);
				$existing = $this->Application->Merchant->TimelineEntry->find('count', array('conditions' => array('TimelineEntry.merchant_id' => $data['Merchant']['merchant_id'], 'TimelineEntry.timeline_item' => 'SIS')));
				if ($existing == 0) {
					$this->Application->Merchant->TimelineEntry->query("INSERT INTO timeline_entries VALUES ('{$data['Merchant']['merchant_id']}', 'SIS', NOW(), 'f')");
				}
			}
		}
		*/

		$alreadySigned = false;
		$this->set('alreadySigned', $alreadySigned);
		$error = false;
		$this->set('error', $error);
		if (isset($xml['error']['message'])) {
			$error = true;
			$this->set('error', $error);

			$data = $this->CobrandedApplication->findByRightsignatureDocumentGuid($guid);
			$this->set('data', $data);

			$send_email = true;

			foreach ($data['EmailTimeline'] as $emails) {
				if ($emails['email_timeline_subject_id'] == 2) {
					$send_email = false;
				}
			}

			if ($send_email != false) {
				$this->CobrandedApplication->repNotifySignedEmail($data['CobrandedApplication']['id']);

				/* NEED TO DEAL WITH THIS
				if ($data['Coversheet']['status'] == 'validated') {
					$this->Session->write('Application.coversheet', 'pdf');
					App::import('Controller', 'Coversheets');
					$Coversheets = new CoversheetsController;
					$Coversheets->constructClasses();
					$Coversheets->pdf($data['Coversheet']['id']);
					$this->Application->Coversheet->saveField('status', 'sent');
					$this->email_coversheet($data['Coversheet']['id']);
					$this->Session->delete('Application.coversheet');
				}
				*/
			}

			/* NEED TO DEAL WITH THIS
			if ($this->Application->findByInstallVarRsDocumentGuid($guid)) {
				$data = $this->Application->findByInstallVarRsDocumentGuid($guid);
				if ($data['Application']['var_status'] != 'signed') {
					$this->Application->save(array('Application' => array('id' => $data['Application']['id'], 'var_status' => 'signed')), array('validate' => false));
				}
				$this->set('data', $data);
				$existing = $this->Application->Merchant->TimelineEntry->find('count', array('conditions' => array('TimelineEntry.merchant_id' => $data['Merchant']['merchant_id'], 'TimelineEntry.timeline_item' => 'SIS')));
				if ($existing == 0) {
					$this->Application->Merchant->TimelineEntry->query("INSERT INTO timeline_entries VALUES ('{$data['Merchant']['merchant_id']}', 'SIS', NOW(), 'f')");
				}
			}
			*/

			$alreadySigned = true;
			$this->set('alreadySigned', $alreadySigned);
		}
	}

/**
 * install_sheet_var
 *
 * @params
 *     $applicationId int
 */
	public function install_sheet_var($applicationId) {
		$this->CobrandedApplication->id = $applicationId;

		if (!$this->CobrandedApplication->exists()) {
			throw new NotFoundException(__('Invalid application'));
		}

		$data = $this->CobrandedApplication->find(
			'first', array(
				'conditions' => array(
					'CobrandedApplication.id' => $applicationId
				),
				'contain' => array(
					'User',
					'CobrandedApplicationValues',
					'Merchant' => array('EquipmentProgramming'),
				),
				'recursive' => 2
			)
		);

		$this->set('data', $data);

		if ($this->request->data) {
			$this->CobrandedApplication->set($this->request->data);

			if (!empty($this->request->data['CobrandedApplication']['select_email_address'])) {
				$this->CobrandedApplication->setValidation('install_var_select');
			} else {
				$this->CobrandedApplication->setValidation('install_var_enter');
			}

			if ($this->CobrandedApplication->validates()) {
				$this->sent_var_install($applicationId);
			} else {
				$errors = $this->CobrandedApplication->invalidFields();
				$this->set('errors', $errors);
			}
		}
	}

/**
 * sent_var_install
 *
 * @params
 *     $applicationId int
 */
	public function sent_var_install($applicationId) {

		$this->CobrandedApplication->id = $applicationId;

		if (!$this->CobrandedApplication->exists()) {
			throw new NotFoundException(__('Invalid application'));
		}

		$cobrandedApplication = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.id' => $applicationId
				),
				'contain' => array(
					'User',
					'Template',
					'Merchant' => array('EquipmentProgramming'),
				),
				'recursive' => 2
			)
		);

		$client = $this->CobrandedApplication->createRightSignatureClient();
		$templateGuid = $cobrandedApplication['Template']['rightsignature_install_template_guid'];
		$response = $this->CobrandedApplication->getRightSignatureTemplate($client, $templateGuid);
		$response = json_decode($response, true);

debug($response['template']);

		if ($response && $response['template']['type'] == 'Document' && $response['template']['guid']) {
			$subject = "Axia Install Sheet - VAR";
			$applicationXml = $this->CobrandedApplication->createRightSignatureApplicationXml(
				$applicationId, $this->Session->read('Auth.User.email'), $response['template'], $subject);

			$response = $this->CobrandedApplication->createRightSignatureDocument($client, $applicationXml);
			$response = json_decode($response, true);

			if ($this->Application->save(array('Application' => array('id' => $id, 'install_var_rs_document_guid' => $response['document']['guid'])), array('validate' => false))) {
				if ($this->request->data['Application']['select_email_address'] != "") {
					$this->Session->write('Application.email', $this->request->data['Application']['select_email_address']);
				} else {
					$this->Session->write('Application.email', $this->request->data['Application']['enter_email_address']);
				}
				$this->Session->write('Application.installer', $this->request->data['Application']['installer']);
				if ($this->Application->save(array('Application' => array('id' => $id, 'var_status' => 'sent')), array('validate' => false))) {
					$this->email_var_install($id);
					$this->redirect(array('action' => 'var_success'));
				} else {
					echo 'Document Not Sent Please Try Again';
				}
			}
		}
	}
}








