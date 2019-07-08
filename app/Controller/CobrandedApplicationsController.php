<?php
App::uses('AppController', 'Controller');
App::uses('TemplateField', 'View/Helper');
App::uses('Setting', 'Model');
App::uses('Validation', 'Utility');
App::uses('Coversheet', 'Model');
App::uses('User', 'Model');

/**
 * CobrandedApplications Controller
 *
 */
/**
 * @OA\Tag(name="CobrandedApplications", description="Operation and data about Applications")
 *
 * @OA\Schema(
 *	   schema="CobrandedApplications",
 *     description="CobrandedApplications database table schema",
 *     title="CobrandedApplications",
 *     @OA\Property(
 *			description="CobrandedApplication id",
 *			property="id",
 *			type="integer"
 *     ),
 *     @OA\Property(
 *			description="The id of the template used by the application",
 *			property="template_id",
 *			type="integer"
 *     ),
 *     @OA\Property(
 *			description="String representation of date and time when the Application was last modified in unix format yyyy-mm-dd hh:mm:ss",
 *			property="modified",
 *			type="string"
 *     ),
 *     @OA\Property(
 *			description="Application's current process status. ",
 *			property="status",
 *			type="string"
 *     )
 * )
 */
class CobrandedApplicationsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Email', 'RequestHandler', 'Security', 'Search.Prg');

	public $permissions = array(
		'index' => array('*'),
		'add' => array(User::ADMIN, User::REP, User::MANAGER),
		'api_add' => array(User::API),
		'api_index' => array(User::API),
		'retrieve' => array('*'),
		'edit' => array(User::ADMIN, User::REP, User::MANAGER),
		'syncApplication' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_index' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_add' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_edit' => array(User::ADMIN),
		'admin_export' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_copy' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_delete' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_email_timeline' => array(User::ADMIN, User::REP, User::MANAGER),
		'complete_fields' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_app_status' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_open_app_pdf' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_app_extend' => array(User::ADMIN, User::REP, User::MANAGER),
		'create_rightsignature_document' => array('*'),
		'sign_rightsignature_document' => array('*'),
		'document_callback' => array('*'),
		'admin_install_sheet_var' => array(User::ADMIN, User::REP, User::MANAGER),
		'sent_var_install' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_var_success' => array(User::ADMIN, User::REP, User::MANAGER),
		'submit_for_review' => array('*')
	);

	public $helpers = array('TemplateField');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(
			'expired',
			'index',
			'document_callback',
			'quickAdd',
			'retrieve',
			'create_rightsignature_document',
			'sign_rightsignature_document',
			'submit_for_review');

		$this->Security->unlockedActions= array('quickAdd', 'retrieve', 'document_callback');

		if ($this->requestIsApiJson()) {
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

	public function expired($uuid = null) {
		$app = $this->CobrandedApplication->find(
			'first',
			array('conditions' => array('CobrandedApplication.uuid' => $uuid))
		);

		$template = $this->CobrandedApplication->User->Template->find(
			'first',
			array(
				'conditions' => array('Template.id' => $app['CobrandedApplication']['template_id'])
			)
		);

		$this->set('brand_logo_url', $template['Cobrand']['brand_logo_url']);
		$this->set('cobrand_logo_url', $template['Cobrand']['cobrand_logo_url']);
		$this->set('cobrand_logo_position', '1');
		$this->set('logoPositionTypes', array('left', 'center', 'right', 'hide'));
		$this->set('include_brand_logo', false);
	}

/**
 * index method
 *
 * @param $email string
 * @param $timestamp int
 * @return void
 */
	public function index($email, $timestamp) {
		if (!$email || !$timestamp) {
			$this->redirect('/');
		}

		// index URL is only good for 2 days (172800 seconds)
		$currentTimestamp = time();
		if ($timestamp < ($currentTimestamp - 172800)) {
			$this->redirect('/');
		}

		$applications = $this->CobrandedApplication->findAppsByEmail($email);

		$app = $applications[0];

		$template = $this->CobrandedApplication->User->Template->find(
			'first',
			array(
				'conditions' => array('Template.id' => $app['CobrandedApplication']['template_id'])
			)
		);

		$this->set('brand_logo_url', $template['Cobrand']['brand_logo_url']);
		$this->set('cobrand_logo_url', $template['Cobrand']['cobrand_logo_url']);
		$this->set('cobrand_logo_position', '1');
		$this->set('logoPositionTypes', array('left', 'center', 'right', 'hide'));
		$this->set('include_brand_logo', false);

		if ($applications) {
			foreach ($applications as $key => $val) {
				$valuesMap = $this->CobrandedApplication->buildCobrandedApplicationValuesMap($val['CobrandedApplicationValues']);
				$applications[$key]['ValuesMap'] = $valuesMap;
			}
			$this->set('email', $email);
			$this->set('applications', $applications);
		} else {
			header("HTTP/1.0 404 Not Found");
			exit;
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
		if ($this->RequestHandler->isAjax()) {
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
				//Unexpected internal error (client-side will notify user)
				$this->response->statusCode(500);
			}
		} else {
			//Bad Request
			$this->response->statusCode(400);
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
		$response = array('status' => AppModel::API_FAILS, 'messages' => 'HTTP method not allowed');
		if ($this->request->is('post')) {
			//get data from request object
			$data = $this->request->input('json_decode', true);
			//if left side is true then assign and check again
			if ((empty($data)) && (empty($data = $this->request->data))) {
				//response if JSON data or form data was not passed
				$response['messages'] = 'No data was sent';
			} else {
				if (!empty(Hash::get($data, 'template_id'))) {
					$user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->User('id'))));
					$response = $this->CobrandedApplication->saveFields($user['User'], $data);

				} else {
					$response['messages'] = 'A template_id is required to create an application.';
				}
			}
		} else {
			$this->response->statusCode(405); //405 Method Not Allowed
		}
		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}

/**
 * retrieve
 *
 *
 */
	public function retrieve() {
		if ($this->request->is('post') || $this->request->is('ajax')) {
			if ($this->request->data['CobrandedApplication']['emailText']) {
				$email = $this->request->data['CobrandedApplication']['emailText'];
			} elseif ($this->request->data['CobrandedApplication']['emailList']) {
				$email = $this->request->data['CobrandedApplication']['emailList'];
			}

			if (isset($this->request->data['CobrandedApplication']['id'])) {
				$id = $this->request->data['CobrandedApplication']['id'];
			} else {
				$id = null;
			}

			if (Validation::email($email)) {
				$response = $this->CobrandedApplication->sendFieldCompletionEmail($email, $id);
				if ($response['success'] == true) {
					$message = "{$response['dba']} application sent to {$response['fullname']} at {$response['email']}";
					$class = ' alert-success';
				} else {
					$class = ' alert-danger';
					$message = $response['msg'];
				}
			} else {
				$class = ' alert-danger';
				$message = 'Invalid email address submitted.';
			}
		}

		$this->set(compact('message', 'class'));
		$this->render('/Elements/Flash/customAlert1', 'ajax');
	}

/**
 * edit method
 *
 * @param string $uuid
 * @return void
 */
	public function edit($uuid = null) {
		if ($this->CobrandedApplication->isExpired($uuid) && !$this->Auth->loggedIn()) {
			$this->redirect(array('action' => '/expired/' . $uuid));
		} elseif (!$this->CobrandedApplication->hasAny(array('CobrandedApplication.uuid' => $uuid))) {
			// redirect to a retrieve page
			$this->redirect(array('action' => 'retrieve'));
		} else {
			$options = array('conditions' => array('CobrandedApplication.uuid' => $uuid));
			$this->request->data = $this->CobrandedApplication->find('first', $options);
			$users = $this->CobrandedApplication->User->find('list');
			$template = $this->CobrandedApplication->getTemplateAndAssociatedValues($this->request->data['CobrandedApplication']['id'], $this->Auth->user('id'));
			$valuesMap = $this->CobrandedApplication->buildCobrandedApplicationValuesMap($this->request->data['CobrandedApplicationValues']);
			$this->set(compact('users'));
			$this->set('valuesMap', $valuesMap);
			$this->set('brand_logo_url', $template['Template']['Cobrand']['brand_logo_url']);
			$this->set('cobrand_logo_url', $template['Template']['Cobrand']['cobrand_logo_url']);
			$this->set('include_brand_logo', $template['Template']['include_brand_logo']);
			$this->set('cobrand_logo_position', $template['Template']['logo_position']);
			$this->set('logoPositionTypes', $this->CobrandedApplication->Template->logoPositionTypes);
			$this->set('rightsignature_template_guid', $template['Template']['rightsignature_template_guid']);
			$this->set('rightsignature_install_template_guid', $template['Template']['rightsignature_install_template_guid']);
			$this->set('templatePages', $template['Template']['TemplatePages']);
			$this->set('bad_characters', array(' ', '&', '#', '$', '(', ')', '/', '%', '\.', '.', '\''));
			$this->set('methodName', $this->Session->read('methodName'));
			$this->Session->delete('methodName');

			// if it is a rep viewing/editing the application don't require fields to be filled in
			// but if they do have data, validate it
			$this->set('requireRequiredFields', false);
			$this->Session->write('applicationStatus', $this->request->data['CobrandedApplication']['status']);
		}
	}
/**
 *
 * Handles API GET request for a list of Applications assigned to the API consumer performing the request.
 * Request parameters are all optional.
 * A full list of all applications assigned to the authenticated API consumer will be returned when no parameters are passed in query
 *
 * @OA\Get(
 *   path="/api/CobrandedApplications/index?search={text to seach for in application}&status={one of: saved/pending/validate/completed/signed}",
 *	 tags={"CobrandedApplications"},
 *   summary="list applications assigned to current authehticated user",
 *	 @OA\Parameter(
 *		   name="search",
 *		   description="Text to search for in the application, for example the company name, or DBA or even the integer id of the application if known.
 *	 			Example 1: Health Systems Corp,
 *	 			Example 2: John Doe (a company contact name),
 *	 			Example 3: 99521 (an application id)",
 *         in="query",
 *         @OA\Schema(type="string")
 *   ),
 *	 @OA\Parameter(
 *		   name="status",
 *		   description="Searches for applications with given status. Available statuses are: saved/pending/validate/completed/signed.
 *	 			Example 1: saved,
 *	 			Example 2: signed",
 *         in="query",
 *         @OA\Schema(type="string")
 *   ),
 *	 @OA\Response(
 *     response=200,
 *     @OA\MediaType(
 *         mediaType="application/json",
 *		   example={{"id": 1234,"user_id": 1234,"status": "signed","partner_name": "Experian","template_id": 1234,"template_name": "Default with BO","dba": "Test Co (DBA)","corp_name": "Test Co.","modified": "2030-06-13 14:40:21"}},
 *         @OA\Schema(
 *	   	   	 ref="#/components/schemas/CobrandedApplications",
 *         )
 *     ),
 *     description="
 * 			status=success detailed JSON array of user's applications (empty if no aplications found with given search parameters for authenticated user).",
 *   ),
 *   @OA\Response(
 *     response=405,
 *     description="HTTP method not allowed when request method is not GET"
 *   )
 * )
 *
 * @return void
 */
	public function api_index() {
		$this->autoRender = false;
		$response = array('status' => AppModel::API_FAILS, 'messages' => 'HTTP method not allowed');
		if ($this->request->is('get')) {
			$this->request->query['user_id'] = $this->Auth->user('id');
			$conditions = $this->CobrandedApplication->parseCriteria($this->request->query);
			$appData = $this->CobrandedApplication->find('index', array(
				'conditions' => $conditions,
				'order' => array('CobrandedApplication.modified DESC')
				)
			);
			$response['data'] = [];
			$response['status'] = API_SUCCESS;
			if (!empty($appData)) {
				$response['messages'] = null;
				foreach($appData as $application) {
					$response['data'][] = array(
						'id' => $application['CobrandedApplication']['id'],
						'user_id' => $application['CobrandedApplication']['user_id'],
						'status' => $application['CobrandedApplication']['status'],
						'partner_name' => $application['Cobrand']['partner_name'],
						'template_id' => $application['Template']['id'],
						'template_name' => $application['Template']['name'],
						'dba' => $application['Dba']['value'],
						'corp_name' => $application['CorpName']['value'],
						'modified' => $application['CobrandedApplication']['modified'],
					);
				}
			} else {
				$response['messages'] = "No aplications found for current user and/or specified search parameters.";
			}
		} else {
			$this->response->statusCode(405); //405 Method Not Allowed
		}

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}
/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		//reset all of the search parameters
		if(isset($this->request->data['reset'])) {
			foreach($this->request->data['CobrandedApplication'] as $i => $value){
				$this->request->data['CobrandedApplication'][$i]= '';
			}
		}

		$userIds = $this->CobrandedApplication->User->getAssignedUserIds($this->Auth->user('id'));

		//paginate the applications
		$this->Prg->commonProcess();
		//grab results from the custom finder _findIndex and pass them to the paginator
		$this->paginate = array('index');
		$this->Paginator->settings = $this->paginate;
		$this->Paginator->settings['conditions'] = $this->CobrandedApplication->parseCriteria($this->passedArgs);
		$this->Paginator->settings['order'] = array('CobrandedApplication.modified' => ' DESC');
		// default to only show logged in user unless user is admin
		// perform some permissions checks
		// Reps can see only their own apps
		// Managers can see their own plus reps assigned to them
		// Admins can see everything
		switch($this->Auth->user('group_id')) {
			case User::REPRESENTATIVE_GROUP_ID:
				$this->Paginator->settings['conditions']['CobrandedApplication.user_id'] = $this->Auth->user('id');
				break;
			case User::MANAGER_GROUP_ID:
				if (array_key_exists('search', $this->passedArgs)) {
					if (in_array($this->passedArgs['user_id'], $userIds)) {
						$this->Paginator->settings['conditions'] = $this->CobrandedApplication->parseCriteria($this->passedArgs);
					} elseif (!in_array($this->passedArgs['user_id'], $userIds)) {
						$this->Paginator->settings['conditions']['CobrandedApplication.user_id'] = $userIds;
					}
				} else {
					$this->Paginator->settings['conditions'] = array(
											'CobrandedApplication.user_id' => $this->Auth->user('id')
					);
				}
				break;
		}

		if (empty($this->request->query['limit'])) {
			$this->request->query['limit'] = 25;
		}

		$this->Paginator->settings['limit'] = $this->request->query['limit'];
		try {
			$this->set('cobrandedApplications', $this->Paginator->paginate());
		} catch (NotFoundException $e) {
			$lastPage = $this->request->params['paging']['CobrandedApplication']['options']['page'] - 1;
			$queryParams = array_merge($this->request->query, array('limit' => $this->request->params['paging']['CobrandedApplication']['limit']));
			$this->redirect(Router::url(array('action' => 'admin_index', 'page:' . $lastPage, '?' => $queryParams)));
		}

		$userTemplate = $this->User->Template->find(
			'first',
			array(
				'conditions' => array('Template.id' => $this->Auth->user('template_id')),
			)
		);

		$users = $this->CobrandedApplication->User->assignableUsers($this->Auth->user('id'), $this->Auth->user('group_id'));

		if (empty($users)) {
			$users = $this->CobrandedApplication->User->find(
				'list',
				array(
					'conditions' => array('User.id' => $this->Auth->user('id')),
				)
			);
		}

		$this->set('users', $users);
		$this->set('user_id', $this->Auth->user('id'));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add($applicationId = null) {
		// look up the user to make sure we don't get stale session data
		$this->User->id = $this->Session->read('Auth.User.id');
		$user = $this->User->getById($this->User->id);
		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$this->CobrandedApplication->create(
				array(
					'user_id' => $this->Session->read('Auth.User.id'),
					'uuid' => CakeText::uuid()
				)
			);
			$this->request->data = $this->CobrandedApplication->data;
			$users = $this->CobrandedApplication->User->assignableUsers($this->Auth->user('id'), $this->Auth->user('group_id'));
			if (empty($users)) {
				$users = $this->CobrandedApplication->User->find(
					'list',
					array(
						'conditions' => array('User.id' => $this->Auth->user('id')),
					)
				);
			}

			$defaultTemplateId = $user['User']['template_id'];
			$templates = $this->User->getTemplates($this->User->id);

			if ($applicationId != null) {
				$this->set('applicationId');
			}

			$this->set(compact('templates', 'users', 'defaultTemplateId'));
			$this->render('admin_add');

		} elseif ($this->request->is('post')) {
			// if applicationId exists, we're copying the application
			if ($applicationId) {
				$this->redirect(array('action' => "/copy/" . $applicationId . "/" . $this->request->data['CobrandedApplication']['template_id']));
			}

			// now try to save with the data from the user model
			$tmpUser = $user;
			$tmpUser['User']['template_id'] = $this->request->data['CobrandedApplication']['template_id'];

			$response = $this->CobrandedApplication->createOnlineappForUser($tmpUser['User'], $this->request->data['CobrandedApplication']['uuid']);

			if ($response['success'] == true) {
				$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');

				$userId = $this->request->data['CobrandedApplication']['user_id'];
				$user = $this->CobrandedApplication->User->find(
					'first',
					array(
						'conditions' => array('User.id' => $userId),
					)
				);

				$app = $this->CobrandedApplication->find(
					'first',
					array(
						'contain' => array('User.firstname', 'User.lastname'),
						'conditions' => array('CobrandedApplication.uuid' => $this->request->data['CobrandedApplication']['uuid']),
						'recursive' => -1
					)
				);

				$appValue = $this->CobrandedApplicationValue->find(
					'first',
					array(
						'conditions' => array(
							'CobrandedApplicationValue.cobranded_application_id' => $app['CobrandedApplication']['id'],
							'CobrandedApplicationValue.name' => 'ContractorID'
						),
						'recursive' => -1
					)
				);
				if (isset($appValue['CobrandedApplicationValue']['name'])) {
					$appValue['CobrandedApplicationValue']['value'] = $user['User']['firstname'] . ' ' . $user['User']['lastname'];
					$this->CobrandedApplicationValue->save($appValue);
				}
				$this->_success(__('Application created'));
				$this->redirect(array('action' => "/edit/".$response['cobrandedApplication']['uuid'], 'admin' => false));
			} else {
				$this->_failure(__('The application could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @param string $id CobrandedApplication id
 * @throws NotFoundException
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->CobrandedApplication->exists($id)) {
			throw new NotFoundException(__('Invalid application'));
		}
		if ($this->request->is('ajax')) {
			$options = array(
				'conditions' => array(
					'CobrandedApplication.' . $this->CobrandedApplication->primaryKey => $id
				),
				'recursive' => -1
			);
			$this->request->data = $this->CobrandedApplication->find('first', $options);
			$users = $this->CobrandedApplication->User->find('list',
				array('order' => 'User.firstname ASC'));
			$this->set(compact('users'));
			$this->render('admin_edit');
		} elseif ($this->request->is('post') || $this->request->is('put')) {
			//If original status was changed from not signed to signed and send_coversheet option was selected then send
			$csSentMsg = "";
			if ($this->request->data('CobrandedApplication.status') === 'signed' && $this->request->data('CobrandedApplication.send_coversheet') == 1 &&
				$this->CobrandedApplication->hasAny(array('id' => $this->request->data('CobrandedApplication.id'), 'status' => 'signed')) === false) {
				$data = $this->CobrandedApplication->findById($this->request->data('CobrandedApplication.id'));
				if (Hash::get($data, 'Coversheet.status') == 'validated') {
					$this->sendCoversheet($data);
					$csSentMsg = ", and coversheet sent to underwriting.";
				}
			}
			if ($this->CobrandedApplication->save($this->request->data)) {
				$this->_success(__("Application number $id has been saved" . $csSentMsg));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_failure(__("Application number $id could not be saved. Please, try again."), array('action' => 'index'));
			}
		}
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
		//first render menu from which user can select the method of export
		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$appRep = $this->CobrandedApplication->CobrandedApplicationValues->getValuesByAppId($id, array('conditions' => array('name' => 'ContractorID')));
			$csPartner = $this->CobrandedApplication->Coversheet->field('setup_partner', array('cobranded_application_id' => $id));
			$axDbApiClient = $this->CobrandedApplication->createAxiaDbApiAuthClient();
			$reponse = $axDbApiClient->get('https://db.axiatech.com/api/Users/get_reps', array('user_name' =>  Hash::get($appRep, 'ContractorID')));
			$responseData = json_decode($reponse->body, true);
			$repList = array();
			$assocPartnerList = array();
			if (empty($responseData) || $responseData['status'] === 'failed') {
				$this->_failure(__("API request failed! Please try again"));
			} elseif (empty($responseData['data'])) {
				$this->_success(__("Warning: Axia database could not find any users with names similar to " .'"'. Hash::get($appRep, 'ContractorID') .'".' . 
									" Export may fail if Rep account hasn't been created & configured in the database system."), null, 'alert alert-warning');
			} else {
				foreach ($responseData['data'] as $rep) {
					$repList[$rep['user_id']] = "{$rep['first_name']} {$rep['last_name']}";
					if ($rep['status'] == 'inactive') {
						$repList[$rep['user_id']] .= " (DB user inactive)";
					}
					if (is_array($rep['assoc_partners'])) {
						$assocPartnerList[$rep['user_id']] = $rep['assoc_partners'];
					}
				}
				
				$repName = Hash::get($appRep, 'ContractorID');
				$this->set('repName', $repName);
			}
			$assocPartnerList = json_encode($assocPartnerList);
			$this->set(compact('assocPartnerList', 'repList', 'appRep', 'csPartner'));
			$this->set('appId', $id);
			$this->render('/Elements/cobranded_applications/export_menu', 'ajax');

		//If User opts to export via API a POST requiest will be made
		} elseif ($this->request->is('post')) {
			if (empty($this->request->data('CobrandedApplication.assign_mid'))) {
				$this->_failure(__("Cannot export without assigning an MID!"), array('action' => 'index'));
			}

			$keys = '';
			$values = '';
			$this->CobrandedApplication->buildExportData($id, $keys, $values, true);
			$data = array_combine($keys, $values);
			$data['MID'] = trim($this->request->data('CobrandedApplication.assign_mid'));
			if (!empty($this->request->data('CobrandedApplication.ContractorID'))) {
				$data['ContractorID'] = $this->request->data('CobrandedApplication.ContractorID');
			}
			if (!empty($this->request->data('CobrandedApplication.setup_partner'))) {
				$data['setup_partner'] = $this->request->data('CobrandedApplication.setup_partner');
			}
			$data = json_encode($data);
			$axDbApiClient = $this->CobrandedApplication->createAxiaDbApiAuthClient();
			$reponse = $axDbApiClient->post('https://db.axiatech.com/api/Merchants/add', $data);
			$responseData = json_decode($reponse->body, true);
			$alertMsg = $responseData['messages'];

			if (is_array($alertMsg)) {
				$alertMsg = implode("\n", $responseData['messages']);
				$alertMsg = nl2br($alertMsg);
			}
			if ($responseData['status'] == 'success') {
				$this->CobrandedApplication->setExportedDate($id, true);
				$this->_success(__($alertMsg), array('action' => 'index'), 'alert alert-success');
			}
			if ($responseData['status'] == 'failed') {
				$this->_success(__(nl2br("EXPORT FAILED! The request returned the following error(s):\n") . $alertMsg), array('action' => 'index'), 'alert alert-danger strong');
			}
		//User opts to export as CSV file
		} else {
			$keys = '';
			$values = '';
			$this->CobrandedApplication->buildExportData($id, $keys, $values);

			// the easy way...
			$this->set('keys', $keys);
			$this->set('values', $values);
			$this->CobrandedApplication->setExportedDate($id, false);
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=\"{$id}.csv\"");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");

			$csv = $this->render('/Elements/cobranded_applications/export', false);
		}
	}

/**
 * admin_copy method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_copy($id = null, $templateId = null) {
		if (!$this->CobrandedApplication->exists($id)) {
			throw new NotFoundException(__('Invalid application'));
		}

		if ($this->CobrandedApplication->copyApplication($id, $this->Session->read('Auth.User.id'), $templateId)) {
			$this->_success(__("Application $id copied"), array('action' => 'index'));
		}
		$this->_failure(__("Failed to copy application $id"), array('action' => 'index'));
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
		if ($this->CobrandedApplication->delete()) {
			$this->_success(__("Application $id deleted"), array('action' => 'index'));
		}
		$this->_failure(__("Application $id was not deleted"), array('action' => 'index'));
	}

/**
 * admin_email_timeline method
 *
 * @param $id int
 * @return void
 */
	public function admin_email_timeline($id) {
		$data = $this->paginate = array(
			'conditions' => array(
				'CobrandedApplication.id' => $id
			),
			'contain' => array(
				'CobrandedApplicationValues',
				'User.email',
				'EmailTimeline' => array(
					'EmailTimelineSubject.subject'
				)
			),
			'limit' => 50,
			'recursive' => -1
		);
		$data = $this->paginate('CobrandedApplication');
		$valuesMap = $this->CobrandedApplication->buildCobrandedApplicationValuesMap($data[0]['CobrandedApplicationValues']);
		$this->set('applications', $data);
		$this->set('dba', Hash::get($valuesMap, 'DBA'));
	}

/**
 * complete_fields method
 *
 * @param $id int
 * @return void
 */
	public function complete_fields($id) {
		$response = $this->CobrandedApplication->sendForCompletion($id);
		if ($response['success'] == true) {
			$this->set('dba', $response['dba']);
			$this->set('email', $response['email']);
			$this->set('fullname', $response['fullname']);
			$this->render('retrieve_thankyou');
		} else {
			$this->set('error', $response['msg']);
		}
	}

/**
 * Grab document status via the RightSignature API
 * https://rightsignature.com/apidocs/api_calls?api_method=documentDetails
 * @param integer $id
 * @param varchar $renew
 * RightSignature Document Guid Allows for extending life of application
 */
	function admin_app_status($id, $renew = null) {
		$guid = $this->CobrandedApplication->field('rightsignature_document_guid', array('id' => $id));
		$client = $this->CobrandedApplication->createRightSignatureClient();
		$results = $client->getDocumentDetails($guid);
		$data = json_decode($results, true);
		$pg = 'Personal Guarantee';
		$app = 'Application';
		$recipients = array_reverse($data['document']['recipients']);
		$state = $data['document']['state'];

		if ($renew != '') {
			$renewed = $client->extendDocument($guid);
			$extension = json_decode($renewed, true);
			if (isset($extension['document'])) {
				$this->_success($extension['document']['status']);
			} else {
				$this->_failure($extension['error']['message']);
			}
			$this->redirect($this->referer());
		}

		$this->set(compact('id', 'data', 'recipients', 'pg', 'app', 'guid'));
	}

/**
 * Redirects user to the application pdf
 *
 * @param integer $id application id
 * @return void
 */
	function admin_open_app_pdf($id) {
		$pdfUrl = $this->CobrandedApplication->getAppPdfUrl($id);
		if (!empty($pdfUrl)) {
			$this->redirect($pdfUrl);
		} else {
			$this->_failure(__('Error: Application PDF could not be found!'), array('action' => index, 'admin' => true));
		}
	}

/**
 * Extend the life of a RightSignature document via Right Signature API
 * https://rightsignature.com/apidocs/api_calls?api_method=extendExpiration
 * @param varchar $guid
 * Right Signature Unique Identifier
 */
	function admin_app_extend($guid) {
		$client = $this->CobrandedApplication->createRightSignatureClient();
		$results = $HttpSocket->post('https://rightsignature.com/api/documents/' . $guid . '/extend_expiration.xml');
		$this->render('admin_app_status');
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
		$settings = array('contain' => array('CobrandedApplicationValues', 'Template'));
		$cobrandedApplication = $this->CobrandedApplication->getById($applicationId, $settings);

		$response = null;

		// Perform validation
		if (!in_array($cobrandedApplication['CobrandedApplication']['status'], array('completed', 'signed'))) {
			$response = $this->CobrandedApplication->validateCobrandedApplication($cobrandedApplication, 'ui');

			if ($response['success'] !== true) {
				$this->CobrandedApplication->save(array('CobrandedApplication' => array('status' => 'validate')), array('validate' => false));
				$this->Session->write('validationErrorsArray', $response['validationErrorsArray']);
				$this->Session->write('methodName', 'create_rightsignature_document');
				$this->redirect(array('action' => "/edit/".$cobrandedApplication['CobrandedApplication']['uuid']."#tab".$response['validationErrorsArray'][0]['page']));
			}
		}

		if (!empty($cobrandedApplication['CobrandedApplication']['rightsignature_document_guid']) && $signNow == true) {
			$this->redirect(array('action' => '/sign_rightsignature_document?guid='.$cobrandedApplication['CobrandedApplication']['rightsignature_document_guid']));
		} else {
			if (empty($cobrandedApplication['CobrandedApplication']['rightsignature_document_guid'])) {
				$client = $this->CobrandedApplication->createRightSignatureClient();

				$this->Cobrand = ClassRegistry::init('Cobrand');
				$cobrand = $this->Cobrand->find('first',
					array(
						'conditions' => array(
							'id' => $cobrandedApplication['Template']['cobrand_id']
						)
					)
				);

				$achYes = false;

				$valuesMap = $this->CobrandedApplication->CobrandedApplicationValues->getValuesByAppId($cobrandedApplication['CobrandedApplication']['id']);
					foreach ($valuesMap as $key => $val) {
					if (preg_match('/ACH-Yes/', $key)) {
						$achYes = $val;
					}
				}

				$templateGuid = $cobrandedApplication['Template']['rightsignature_template_guid'];

				if ($achYes == true) {
					$templateGuid = $this->CobrandedApplication->getRightsignatureTemplateGuid($cobrand['Cobrand']['partner_name'], 'ach');
				}

				$response = $this->CobrandedApplication->getRightSignatureTemplate($client, $templateGuid);
				$response = json_decode($response, true);

				if ($response && $response['template']['type'] == 'Document' && $response['template']['guid']) {
					$applicationXml = $this->CobrandedApplication->createRightSignatureApplicationXml(
						$applicationId, $this->Session->read('Auth.User.email'), $response['template']);
					$response = $this->CobrandedApplication->createRightSignatureDocument($client, $applicationXml);
					$tmpResponse = json_decode($response, true);

					if ($tmpResponse && key_exists('error', $tmpResponse)) {
						$url = "/edit/" . $cobrandedApplication['CobrandedApplication']['uuid'];
						$this->_failure(__('error! ' . $tmpResponse['error']['message']));
						$this->redirect(array('action' => $url));
					}
				} else {
					$url = "/edit/" . $cobrandedApplication['CobrandedApplication']['uuid'];
					$this->_failure(__(CobrandedApplication::RIGHTSIGNATURE_NO_TEMPLATE_ERROR));
					$this->redirect(array('action' => $url));
				}

				$response = json_decode($response, true);
			} else {
				$response['document']['status'] = 'sent';
				$response['document']['guid'] = $cobrandedApplication['CobrandedApplication']['rightsignature_document_guid'];
			}
			$appValues = Hash::combine(
				$cobrandedApplication,
				'CobrandedApplicationValues.{n}.name',
				'CobrandedApplicationValues.{n}.value'
			);
			$merchDBA = (!empty($appValues['DBA']))? $appValues['DBA'] : Hash::get($appValues, 'CorpName');
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
					$this->redirect(array('action' => 'sign_rightsignature_document?guid=' . $response['document']['guid']));
				} else {
					// if not simply send the documents
					$emailResponse = $this->CobrandedApplication->sendApplicationForSigningEmail($applicationId);
					if ($emailResponse['success'] === true) {
						$this->_success(
							__("$merchDBA Application has been emailed to: " . $appValues['Owner1Email'])
						);
						$this->redirect(array('action' => 'index', 'admin' => true));
					} else {
						$this->_failure(__($emailResponse['msg']));
						$this->redirect($this->referer());
					}
				}
			} else {
				$url = "/edit/" . $cobrandedApplication['CobrandedApplication']['uuid'];
				$this->_failure(__("Error! Could not send the document for $merchDBA application"));
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
		$widgetHeight = 900;
		$this->set('widgetHeight', $widgetHeight);

		$guid = htmlspecialchars($_REQUEST["guid"]);

		$this->set('guid', $guid);

		if (empty($guid)) {
			$this->_failure(__("Cannot find document with given GUID."));
			$this->redirect(array('action' => 'index'));
		}

		// Gets signer link and reloads this page after each successful signature to refresh the signer-links list
		$response = $this->CobrandedApplication->getRightSignatureSignerLinks($client, $guid);
		$this->set('response', $response);

		$xml = Xml::build($response);
		$xml = Xml::toArray($xml);
		$result = Set::normalize($xml);
		$this->set('xml', $xml);

		$appTemplateId = null;

		$data = array();

		if ($this->CobrandedApplication->findByRightsignatureDocumentGuid($guid)) {
			$data = $this->CobrandedApplication->findByRightsignatureDocumentGuid($guid);
			$appTemplateId = $data['CobrandedApplication']['template_id'];
			$this->layout = 'default';

			if (key_exists('error', $xml) &&
				$xml['error']['message'] == "Document is already signed." &&
				$data['CobrandedApplication']['status'] != 'signed') {

				if ($data['Coversheet']['status'] == 'validated') {
					$this->sendCoversheet($data);
				}
			}
		}
		$varSigner = false;
		if ($this->CobrandedApplication->findByRightsignatureInstallDocumentGuid($guid)) {
			$data = $this->CobrandedApplication->findByRightsignatureInstallDocumentGuid($guid);
			$appTemplateId = $data['CobrandedApplication']['template_id'];
			$this->set('data', $data);
			$varSigner = true;
			$this->set('varSigner', $varSigner);
			if ($xml['error']['message'] == "Document is already signed." && $data['CobrandedApplication']['rightsignature_install_status'] != 'signed') {
				$this->CobrandedApplication->id = $data['CobrandedApplication']['id'];
				$this->CobrandedApplication->saveField('rightsignature_install_status', 'signed');
				$existing = $this->CobrandedApplication->Merchant->TimelineEntry->find(
					'count',
					array(
						'conditions' => array(
							'TimelineEntry.merchant_id' => $data['Merchant']['merchant_id'],
							'TimelineEntry.timeline_item' => 'SIS'
						)
					)
				);
				if ($existing == 0) {
					$this->CobrandedApplication->Merchant->TimelineEntry->query("INSERT INTO timeline_entries VALUES ('{$data['Merchant']['merchant_id']}', 'SIS', NOW(), 'f')");
				}
			}
		}
		$this->set('varSigner', $varSigner);
		$template = $this->CobrandedApplication->User->Template->find(
			'first',
			array(
				'conditions' => array('Template.id' => $appTemplateId),
			)
		);

		$this->set('brand_logo_url', Hash::get($template, 'Cobrand.brand_logo_url'));
		$this->set('cobrand_logo_url', Hash::get($template, 'Cobrand.cobrand_logo_url'));
		$this->set('cobrand_logo_position', '1');
		$this->set('logoPositionTypes', array('left', 'center', 'right', 'hide'));
		$this->set('include_brand_logo', false);

		$alreadySigned = false;
		$this->set('alreadySigned', $alreadySigned);
		$error = false;
		$this->set('error', $error);
		if (isset($xml['error']['message'])) {
			$error = true;
			$this->set('error', $error);
			$data = $this->CobrandedApplication->findByRightsignatureDocumentGuid($guid);
			$this->set('data', $data);

			if ($data['Coversheet']['status'] == 'validated') {
				$this->sendCoversheet($data);
			}

			if ($this->CobrandedApplication->findByRightsignatureInstallDocumentGuid($guid)) {
				$data = $this->CobrandedApplication->findByRightsignatureInstallDocumentGuid($guid);
				if ($data['CobrandedApplication']['rightsignature_install_status'] != 'signed') {
					$this->CobrandedApplication->id = $data['CobrandedApplication']['id'];
					$this->CobrandedApplication->saveField('rightsignature_install_status', 'signed');
				}
				$this->set('data', $data);

				$existing = $this->CobrandedApplication->Merchant->TimelineEntry->find(
					'count',
					array(
						'conditions' => array(
							'TimelineEntry.merchant_id' => $data['Merchant']['merchant_id'],
							'TimelineEntry.timeline_item' => 'SIS'
						)
					)
				);
				if ($existing == 0) {
					$this->CobrandedApplication->Merchant->TimelineEntry->query("INSERT INTO timeline_entries VALUES ('{$data['Merchant']['merchant_id']}', 'SIS', NOW(), 'f')");
				}
			}

			$alreadySigned = true;
			$this->set('alreadySigned', $alreadySigned);
		}
		$appPdfUrl = null;
		if ($alreadySigned === false) {
			$docDetals = $client->getDocumentDetails($guid);
			$docData = json_decode($docDetals, true);

			if (!empty($docData)) {
				$appPdfUrl = Hash::get($docData, 'document.pdf_url');
				if (!empty($appPdfUrl)) {
					$appPdfUrl = urldecode($appPdfUrl);
				}
			}
		}
		$this->set('appPdfUrl', $appPdfUrl);
	}

/**
 * install_sheet_var
 *
 * @params
 *     $applicationId int
 */
	public function admin_install_sheet_var($applicationId) {
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
				'recursive' => -1
			)
		);

		if (empty($data['Merchant']['id'])) {
			$this->Session->setFlash(
					__('This Application Has not been boarded into the Database!'),
					'Flash/installSheetAlert',
					array('class' => 'alert-warning', 'subjectSubString' => 'not boarded', 'appId' => $data['CobrandedApplication']['id'])
				);
			$this->redirect($this->referer());
		} elseif (empty($data['Merchant']['EquipmentProgramming'])) {
			$this->Session->setFlash(
					__('A terminal associated with that application has not yet been configured!'),
					'Flash/installSheetAlert',
					array('class' => 'alert-warning', 'subjectSubString' => 'no terminal configured', 'appId' => $data['CobrandedApplication']['id'])
				);
			$this->redirect($this->referer());
		}

		$this->set('data', $data);

		if ($this->request->is('post') || $this->request->is('put')) {
			if (empty($this->request->data['CobrandedApplication']['select_terminal_type'])) {
				$url = "/install_sheet_var/" . $data['CobrandedApplication']['id'];
				$this->_failure(__('error! terminal type not selected'));
				$this->redirect(array('action' => $url));
			}

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

		if ($response && $response['template']['type'] == 'Document' && $response['template']['guid']) {
			$subject = "Axia Install Sheet - VAR";
			$applicationXml = $this->CobrandedApplication->createRightSignatureApplicationXml(
				$applicationId, $this->Session->read('Auth.User.email'), $response['template'], $subject,
				$this->request->data['CobrandedApplication']['select_terminal_type']);

			$response = $this->CobrandedApplication->createRightSignatureDocument($client, $applicationXml);
			$response = json_decode($response, true);

			if ($response['document']['status'] == 'sent' && $response['document']['guid']) {
				if ($this->CobrandedApplication->save(
						array(
							'CobrandedApplication' => array(
								'id' => $applicationId,
								'rightsignature_install_document_guid' => $response['document']['guid']
							)
						),
						array('validate' => false)
					)
				) {
					if ($this->request->data['CobrandedApplication']['select_email_address'] != "") {
					$this->Session->write('CobrandedApplication.email', $this->request->data['CobrandedApplication']['select_email_address']);
					} else {
						$this->Session->write('CobrandedApplication.email', $this->request->data['CobrandedApplication']['enter_email_address']);
					}

					if ($this->CobrandedApplication->save(
							array(
								'CobrandedApplication' => array(
									'id' => $applicationId,
									'rightsignature_install_status' => 'sent'
								)
							),
							array('validate' => false)
						)
					) {
						$email = $this->Session->read('CobrandedApplication.email');
						$this->CobrandedApplication->sendRightsignatureInstallSheetEmail($applicationId, $email);
						$this->redirect(array('action' => 'var_success'));
					} else {
						$this->_failure(__('Document Not Sent Please Try Again'));
					}
				}
			} else {
				$url = "/edit/".$cobrandedApplication['CobrandedApplication']['uuid'];
				$this->_failure(__('error! could not send the document'));
				$this->redirect(array('action' => $url));
			}
		}
	}

/**
 * admin_var_success
 *
 */
	public function admin_var_success() {
		$email = $this->Session->read('CobrandedApplication.email');
		$this->_success('Install sheet Successfully sent to: '.$email);
	}

/*
 * API callback for the RightSignature API, the RS API hits this callback
 * after a document has been successfully signed.
 * callback implements logic for what should happen to applications after
 * they have been signed.
 */
	public function document_callback() {
		$this->data = array_change_key_case($this->data, CASE_LOWER);
		CakeLog::write('debug', print_r($this->request->data, true));

		if ($this->request->data['callback']['guid'] && $this->data['callback']['status'] == 'signed') {

			$data = $this->CobrandedApplication->findByRightsignatureDocumentGuid($this->request->data['callback']['guid']);

			if (empty($data)) {
				$data = $this->CobrandedApplication->findByRightsignatureInstallDocumentGuid($this->request->data['callback']['guid']);
				if (!empty($data)) {
					$this->CobrandedApplication->id = $data['CobrandedApplication']['id'];
					$this->CobrandedApplication->saveField('rightsignature_install_status', 'signed');
					$this->CobrandedApplication->repNotifySignedEmail($data['CobrandedApplication']['id'], 'rep_notify_signed_install_sheet');
				}
				exit;
			}

			if (!empty($data)) {
				$this->CobrandedApplication->id = $data['CobrandedApplication']['id'];
				$this->CobrandedApplication->saveField('status', 'signed');
				$this->CobrandedApplication->repNotifySignedEmail($data['CobrandedApplication']['id']);
			}
		}

		exit;
	}

/**
 * submit_for_review
 *
 * @params
 *     $applicationId int
 */
	public function submit_for_review($applicationId = null) {
		$this->CobrandedApplication->id = $applicationId;

		if (!$this->CobrandedApplication->exists()) {
			throw new NotFoundException(__('Invalid application'));
		}

		$response = $this->CobrandedApplication->submitForReviewEmail($applicationId);

		if ($response['success'] != true) {
			$this->set('error', $response['msg']);
		}

		$this->render('end');
	}

/**
 * sendCoversheet
 *
 * @params
 *     $data array
 */
	public function sendCoversheet($data) {
		$coversheetData = $this->CobrandedApplication->Coversheet->findById($data['Coversheet']['id']);

		$valuesMap = $this->CobrandedApplication->CobrandedApplicationValues->getValuesByAppId($coversheetData['CobrandedApplication']['id']);
		foreach ($valuesMap as $key => $val) {
			$coversheetData['CobrandedApplication'][$key] = $val;
		}

		$View = new View($this, false);

		if ($coversheetData['CobrandedApplication']['MethodofSales-CardNotPresent-Keyed'] + $coversheetData['CobrandedApplication']['MethodofSales-CardNotPresent-Internet'] >= '30') {
			$View->set('cp', false);
		} else {
			$View->set('cp', true);
		}

		$View->set('data', $coversheetData);
		$View->viewPath = 'Elements';
		$View->layout = false;
		$viewData = $View->render('/Elements/coversheets/pdf_export');

		if ($this->CobrandedApplication->Coversheet->pdfGen($data['Coversheet']['id'], $viewData)) {
			if ($this->CobrandedApplication->Coversheet->sendCoversheet($data['Coversheet']['id'])) {
				if ($this->CobrandedApplication->Coversheet->unlinkCoversheet($data['Coversheet']['id'])) {
					$this->CobrandedApplication->Coversheet->saveField('status', 'sent');
				}
			}
		}
	}

/**
 * syncApplication()
 *
 * @param integer $id a CobrandedApplication.id
 * @param integer $templateId the id of the template associated with the Cobrandedapplication.id
 * @return void
 */
	public function syncApplication($id, $templateId) {
		$this->CobrandedApplication->id = $id;
		if (!$this->CobrandedApplication->exists()) {
			$this->_failure(__('Error: Application does not exist!'));
			$this->redirect($this->referer());
		}

		if (!$this->CobrandedApplication->Template->hasAny(array('id' => $templateId))) {
			$this->_failure(__("The Application's Template could not be found! Application cannot be synchronized."));
		} else {
			if ($this->CobrandedApplication->syncApp($id)) {
				$this->_success(__("Application synchronized with its Template! Please review application, data has been synced and may have changed if corresponding Template Fields were modified."));
			} else {
				$this->_failure(__("Error: Synchronization process failed!"));
			}
		}
		$this->redirect($this->referer());
	}
}
