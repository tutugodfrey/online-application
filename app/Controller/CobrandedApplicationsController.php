<?php
App::uses('AppController', 'Controller');
App::uses('TemplateField', 'View/Helper');
App::uses('Setting', 'Model');
App::uses('Validation', 'Utility');
App::uses('Coversheet', 'Model');
App::uses('User', 'Model');
App::uses('EmailTimeline', 'Model');
App::uses('Okta', 'Model');
App::uses('ApplicationGroup', 'Model');

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
 *			description="The Application id is an UUID string",
 *			property="application_id",
 *			type="string"
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

	public $authenticatedAllowedActions = array('edit', 'sign_rightsignature_document', 'index', 'create_rightsignature_document', 'extend_dashboard_expiration', 'send_pdf_to_client');

	public $permissions = array(
		'index' => array(User::ADMIN, User::REP, User::MANAGER),
		'pdf_doc_token_dl' => array('*'),
		'add' => array(User::ADMIN, User::REP, User::MANAGER),
		'api_add' => array(User::API),
		'api_edit' => array(User::API),
		'api_index' => array(User::API),
		'api_view' => array(User::API),
		'retrieve_with_client_token' => array('*'),
		'retrieve' => array('*'),
		'edit' => array(User::ADMIN, User::REP, User::MANAGER),
		'syncApplication' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_index' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_add' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_edit' => array(User::ADMIN),
		'admin_export' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_delete' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_email_timeline' => array(User::ADMIN, User::REP, User::MANAGER),
		'complete_fields' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_app_status' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_open_app_pdf' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_app_extend' => array(User::ADMIN, User::REP, User::MANAGER),
		'create_rightsignature_document' => array(User::ADMIN, User::REP, User::MANAGER),
		'sign_rightsignature_document' => array(User::ADMIN, User::REP, User::MANAGER),
		'signerHasSigned' => array('*'),
		'document_callback' => array('*'),
		'admin_install_sheet_var' => array(User::ADMIN, User::REP, User::MANAGER),
		'sent_var_install' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_var_success' => array(User::ADMIN, User::REP, User::MANAGER),
		'rs_document_audit' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_validate_client_id' => array(User::ADMIN, User::REP, User::MANAGER),
		'submit_for_review' => array('*'),
		'admin_amend_completed_document' => array(User::ADMIN, User::REP, User::MANAGER),
		'app_info_summary' => array(User::ADMIN, User::REP, User::MANAGER),
		'admin_renew_modified_date' => array(User::ADMIN, User::REP, User::MANAGER),
		'extend_dashboard_expiration' => array(User::ADMIN, User::REP, User::MANAGER),
		'send_pdf_to_client' => array(User::ADMIN, User::REP, User::MANAGER),
		'cl_access_auth' => array('*'),
		'cl_logout' => array('*'),
	);

	public $helpers = array('TemplateField');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(
			'pdf_doc_token_dl',
			'signerHasSigned',
			'expired',
			'document_callback',
			'quickAdd',
			'retrieve',
			'retrieve_with_client_token',
			'cl_access_auth',
			'send_pdf_to_client',
			'cl_logout',
			'submit_for_review');

		$this->Security->unlockedActions= array('quickAdd', 'retrieve', 'retrieve_with_client_token','document_callback', 'index');
		$this->Security->blackHoleCallback = 'forcePageRefresh';
		if ($this->requestIsApiJson() || $this->request->is('ajax')) {
			$this->Security->unlockedActions= array('api_add', 'api_edit', $this->action);
		} else {
			// are we authenticated?
			if (is_null($this->Auth->user('id'))) {
				// is this an authenticated external user/client?,
				// the following actions are made accessible by clients with appropriate access credentials.
				// The client user token session is created after client authenticates from action => cl_access_auth()
				if (!is_null($this->Session->read('Client.client_user_token')) && in_array($this->request->params['action'], $this->authenticatedAllowedActions)) {
					$this->Security->unlockedActions[] = 'extend_dashboard_expiration';
					$this->Security->unlockedActions[] = 'send_pdf_to_client';
					//renew session
					$this->_renewAuthenticatedExternalUserSession();

					//inspect the request here check that user is allowed to access the requested resurce
					//external users/client must only be able to access data of the applications they own
					if ($this->_isResourceOwnerAllowedAccess()) {
						//Allow action to authenticated users
						$this->Auth->allow($this->request->params['action']);
					} else {						
						$this->_failure(__('Access not allowed.'), array('action' => 'cl_access_auth', 'admin' => false));
					}
				} else {

					//External user needs to be authenticated
					//Redirect unauthenticated users to client login page
					if (!in_array($this->request->params['action'], $this->Auth->allowedActions)) {
						if ($this->request->params['action'] == 'cl_access_auth' || $this->request->params['action'] == 'cl_logout') {
						$this->redirect(array('action' => 'cl_access_auth', 'admin' => false));
					} else {
						$refUrl = Router::url($this->request->here, true);
						if (!empty($this->request->query)) {
							$refUrl .= '?' . http_build_query($this->request->query);
						}
						$this->redirect(array('action' => 'cl_access_auth', 'admin' => false, '?' => array('ref' => $refUrl)));
					}
					}
				}
			}
		}

		$this->fetchSettings();
		$this->settings = Configure::read('Setting');
	}
	public function forcePageRefresh() {
		$this->Session->setFlash(__('Something went wrong, please try again (sec-csfr).'), 'default', array('class' => 'alert alert-danger'));
		if (Configure::read('debug') > 0) {
			return $this->redirect(env('FULL_BASE_URL'). $this->here);
		} else {
        	return $this->redirect('https://' . env('SERVER_NAME') . $this->here);
		}
    }

	public function beforeRender() {
		parent::beforeRender();
		if ($this->request->accepts('application/json')) {
			Configure::write('debug', 0);
			$this->disableCache();
		}
	}

	public function expired($uuid = null) {
		$this->set('name', 'ERROR 404: The document has expired. For assistance please contact your sales representative.');
		$this->set('url', Router::url(['controller' => 'CobrandedApplications', 'action' => 'edit', $uuid], true));
		$this->render('/Errors/error404');
	}


/**
 * _renewAuthenticatedExternalUserSession
 *
 * Renews existing session of authenticated exteral user.
 * Customers are considered external users and not part of the usual User login mechanism, which is reserved 
 * for internal (company employees) users.
 * Customers only have access to a hanful of actions within this Controller and for that reason customer 
 * autentication and session control is overridden and handled within this controller.
 * 
 * @return null
 */
	protected function _renewAuthenticatedExternalUserSession() {
		if (!is_null($this->Session->read('Client.client_user_token'))) {
			$authenticatedUser = $this->Session->read('Client.client_user_token');
			$clientDashboardId = $this->Session->read('Client.client_dashboard_id');
			$this->Session->destroy();
			Configure::write('Session', array(
			    'defaults' => 'php',
			    'cookie' => 'CAKEPHP',
			    'timeout' => 14, // in minutes
			    'ini' => array(
			        'session.gc_maxlifetime' => 840 // in secs,  controls session expiration when the user will be signed out
			    )
			));
			$this->Session->write('Client.client_user_token', $authenticatedUser);
			$this->Session->write('Client.client_dashboard_id', $clientDashboardId);
		}
	}

/**
 * Authenticate client users
 * Checkes whether the current request action is part of actions that are accessible by clients with appropriate access credentials.
 * Also checks if the requested resource is owned by the currently authenticated client based on request data parameters.
 * Each action has different parameters and must check each differently
 * 
 * @return null
 */
	protected function _isResourceOwnerAllowedAccess() {
		return true;
	}

/**
 * Authenticate client users
 * Checkes whether the current request action is part of actions that are accessible by clients with appropriate access credentials.
 * Also checks if the requested resource is owned by the currently authenticated client based on request data parameters.
 * Each action has different parameters and must check each differently
 * 
 * @return null
 */
	protected function _isResourceOwnerAllowedAccessDeprecated() {
		if (in_array($this->request->params['action'], $this->authenticatedAllowedActions)) {
			switch ($this->request->params['action']) {
			    case 'edit':
			    case 'create_rightsignature_document':
			    case 'send_pdf_to_client':
			        $applicationID = $this->request->params['pass'][0];
			        $conditions = array('CobrandedApplication.id' => $applicationID);
			        if ($this->CobrandedApplication->isValidUUID($applicationID)) {
			        	$conditions = array('CobrandedApplication.uuid' => $applicationID);
			        }
			        $appData = $this->CobrandedApplication->find('first', array(
			    		'recursive' => -1,
			    		'fields' => array('CobrandedApplication.id'),
			    		'conditions' => $conditions,
			    		'joins' => array(
			    			array('table' => 'onlineapp_application_groups',
								'alias' => 'ApplicationGroup',
								'type' => 'INNER',
								'conditions' => array(
									"CobrandedApplication.application_group_id = ApplicationGroup.id",
									"ApplicationGroup.client_access_token = '" .$this->Session->read('Client.client_user_token')."'",
								),
							),
			    		)
			    	));

			        return !empty($appData['CobrandedApplication']['id']);
			        break;
			    case 'extend_dashboard_expiration':
			        $applicationGroupId = $this->request->params['pass'][0];
			        //if the authenticated user token and the dashboard access token are in the same record
			        //the user can access the index page
			        return $this->CobrandedApplication->ApplicationGroup->hasAny(
			        	array(
			        		'id' => $applicationGroupId,
			        		'client_access_token' => $this->Session->read('Client.client_user_token')
			        	)
			        );
			        break;
			    case 'index':
			        $applicationDashboardAccessToken = $this->request->params['pass'][0];
			        //if the authenticated user token and the dashboard access token are in the same record
			        //the user can access the index page
			        return $this->CobrandedApplication->ApplicationGroup->hasAny(
			        	array(
			        		'access_token' => $applicationDashboardAccessToken,
			        		'client_access_token' => $this->Session->read('Client.client_user_token')
			        	)
			        );
			        break;
			    case 'sign_rightsignature_document':
			    	$rsDocUuid = Hash::get($this->request->query, 'guid');
			    	if(empty($rsDocUuid)) {
			    		return false;
			    	}
			    	
			    	$appData = $this->CobrandedApplication->find('first', array(
			    		'recursive' => -1,
			    		'fields' => array('CobrandedApplication.id'),
			    		'conditions' => array(
			    			'CobrandedApplication.rightsignature_document_guid' => $rsDocUuid
			    		),
			    		'joins' => array(
			    			array('table' => 'onlineapp_application_groups',
								'alias' => 'ApplicationGroup',
								'type' => 'INNER',
								'conditions' => array(
									"CobrandedApplication.application_group_id = ApplicationGroup.id",
									"ApplicationGroup.client_access_token = '" .$this->Session->read('Client.client_user_token')."'",
								),
							),
			    		)
			    	));
			        return !empty($appData['CobrandedApplication']['id']);
			        break;
			    default:
       				return false;
			}
		} else {
			return false;
		}
	}

/**
 * Authenticate client users
 *
 * @return null
 */
	public function cl_logout() {
		$this->Session->destroy();
		$this->_success(__('User logged out, thank you.'), array('action' => 'cl_access_auth', 'admin' => false));
	}
/**
 * Authenticate client users
 * Customers are considered external users and not part of the usual User login mechanism, which is reserved 
 * for internal (company employees) users.
 * Customer credentials are stored in $this->CobrandedApplication->ApplicationGroup Model which is used to
 * track the applications each customer has acces to.
 * Customers only have access to a hanful of actions within this Controller and for that reason customer 
 * autentication and session control is overridden and handled within this controller.
 *
 * @return null
 */
	public function cl_access_auth() {
		if ($this->Auth->loggedIn()) {
			//Destroy session of internal user if exists
			$this->Session->destroy();
		}
		if ($this->request->is('post')) {
			$clientAccessToken = $this->request->data('CobrandedApplication.user_token');
			$clientPassword = $this->request->data('CobrandedApplication.password');
			$result = $this->CobrandedApplication->ApplicationGroup->validateClientCredentials($clientAccessToken, $clientPassword);
			$attemptCount = null;
			if (array_key_exists('password_expired', $result) === false) {
				//check if credentials ok 
				if (!empty($result['ApplicationGroup']['id']) && !empty($clientAccessToken) && !empty($clientPassword)) {
					//credentials ok? destroy then create session
					$this->Session->destroy();
					Configure::write('Session', array(
					    'defaults' => 'php',
					    'cookie' => 'CAKEPHP',
					    'timeout' => 14, // in minutes
					    'ini' => array(
					        'session.gc_maxlifetime' => 840 // in secs,  controls session expiration when the user will be signed out
					    )
					));
					$this->Session->write('Client.client_user_token', $result['ApplicationGroup']['client_access_token']);
					//renew client dashboard expiration redirect to client dashboard
					$result = $this->CobrandedApplication->ApplicationGroup->renewAccessToken($result['ApplicationGroup']['id']);
					$this->Session->write('Client.client_dashboard_id', $result['ApplicationGroup']['access_token']);
					//reset any failed login counter
					$this->CobrandedApplication->ApplicationGroup->trackIncorrectLogIn($clientAccessToken, true);

					if (!empty($this->request->query['ref'])) {
						$this->redirect($this->request->query['ref']);
					} else {
						$this->redirect(array('action' => 'index', $result['ApplicationGroup']['access_token'], 'admin' => false));
					}
				} else {
					// increase wrong log in counter
					$attemptCount = $this->CobrandedApplication->ApplicationGroup->trackIncorrectLogIn($clientAccessToken, false);
				}
			}
			$ErrMsg = 'Invalid credentials';
			//check count of prevously attempted failed logins
			if ($attemptCount >= User::MAX_LOG_IN_ATTEMPTS) {
				$ErrMsg .= '. Account is now locked. Use "Forgot password" to request new credentials.';
			}
			$this->request->data['CobrandedApplication']['password'] = null;
			$this->_failure(__($ErrMsg));
		}
	}

/**
 * extend_dashboard_expiration method
 *
 * @param $applicationGroupId string a ApplicationGroup.id
 * @return void
 */
	public function extend_dashboard_expiration($applicationGroupId) {
		if ($this->request->is('post') && !empty($applicationGroupId)) {

			$appGroupList = $this->CobrandedApplication->find('list', array(
				'recursive' => -1,
				'fields' => array('id'),
				'conditions' => array('application_group_id' => $applicationGroupId)

			));
			$emailValue = $this->CobrandedApplication->CobrandedApplicationValues->find('first', array(
				'callbacks' => false,
				'recursive' => -1,
				'fields' => array('cobranded_application_id', 'value'),
				'conditions' => array(
					'cobranded_application_id' => $appGroupList,
					'name' => 'Owner1Email',
					"value !=''" 
				)
			));
			$appGroupData = $this->CobrandedApplication->ApplicationGroup->find('first', array(
				'recursive' => -1,
				'conditions' => array('id' => $applicationGroupId),
			));
			$response = $this->CobrandedApplication->sendFieldCompletionEmail($emailValue['CobrandedApplicationValues']['value'], $emailValue['CobrandedApplicationValues']['cobranded_application_id'], true);
			if ($response['success'] == true) {
				$appGroupCounter = array(
					'id' => $appGroupData['ApplicationGroup']['id'],
					'token_renew_count' => 0
				);
				//the token_renew_count will be set to zero or increased if this is not an internal company user
				if (empty($this->Auth->user('id'))) {
					$appGroupCounter['token_renew_count'] = $appGroupData['ApplicationGroup']['token_renew_count'] +1;
				}

				$this->CobrandedApplication->ApplicationGroup->clear();
				$this->CobrandedApplication->ApplicationGroup->save($appGroupCounter, array('validate' => false));
				$this->_success("Expiration extended and new credentials have been sent to ". $emailValue['CobrandedApplicationValues']['value'], array('action' => 'index', 'admin' => false, $appGroupData['ApplicationGroup']['access_token']), 'alert-success lead');
			} else {
				$this->_failure("Something went wrong: " . $response['msg'],array('action' => 'index', 'admin' => false, $appGroupData['ApplicationGroup']['access_token']), 'alert-danger');
			}
			
		} else {
			$this->renderError404('ERROR 404: Page does not exist.', Router::url(['controller' => 'CobrandedApplications', 'action' => 'extend_dashboard_expiration'], true));
		}
	}
/**
 * index method
 *
 * @param $email string
 * @param $timestamp int
 * @return void
 */
	public function index($accessToken = null) {
		if (empty($accessToken) || $this->CobrandedApplication->ApplicationGroup->hasAny(array('access_token' => $accessToken)) == false) {
			$this->renderError404('ERROR 404: Page does not exist.', Router::url(['controller' => 'CobrandedApplications', 'action' => 'index', $accessToken], true));
			return;
		}

		$applications = [];
		$appGroupData = $this->CobrandedApplication->ApplicationGroup->findByAccessToken($accessToken, false);

		//check if pw expired
		if ($this->request->is('post')) {
			if (!empty($appGroupData) && $this->CobrandedApplication->isValidUUID($this->request->data('CobrandedApplication.doc_id'))) {
				if (!empty($this->Session->read('Auth.User.id')) || $this->CobrandedApplication->ApplicationGroup->isClientPwExpired($appGroupData['ApplicationGroup']['id']) == false) {
					set_time_limit(0);
					$applications = $this->CobrandedApplication->findGroupedApps($appGroupData['ApplicationGroup']['id'], $this->request->data('CobrandedApplication.doc_id'));
					if (empty($applications)) {
						$this->_failure(__("Sorry no documents found with that id, contact your sales representative if you need assistance."));
					}
				}
			} elseif($this->CobrandedApplication->isValidUUID($this->request->data('CobrandedApplication.doc_id'))) {
				$this->_failure(__("Please enter a valid document id to retrieve your document."));
			}
		}

		$template = [];
		if (!empty($applications)) {
			$app = Hash::get($applications, '0');

			$template = $this->CobrandedApplication->User->Template->find(
				'first',
				array(
					'conditions' => array('Template.id' => $app['CobrandedApplication']['template_id'])
				)
			);
		}
		
		$this->set('appGroupData', $appGroupData);
		$this->set('applications', $applications);
		$this->set('brand_logo_url', Hash::get($template, 'Cobrand.brand_logo_url'));
		$this->set('cobrand_logo_url', Hash::get($template, 'Cobrand.cobrand_logo_url'));
		$this->set('cobrand_logo_position', '1');
		$this->set('logoPositionTypes', array('left', 'center', 'right', 'hide'));
		$this->set('include_brand_logo', false);
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

			if (empty($this->request->data['id'])) {
				throw new NotFoundException(__('Invalid application value id'));
			}

			$this->RequestHandler->setContent('json', 'application/json');
			$response = $this->CobrandedApplication->saveApplicationValue($this->request->data);

			if ($response['success'] == true) {
				// all good
				$response = json_encode($response);
				$this->set(compact('response'));
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
 * retrieve
 *
 *
 */
	public function retrieve() {
		if ($this->request->is('ajax')) {
			$email = $this->request->data('CobrandedApplication.emailText');
			if (empty($email)) {
				$email = $this->request->data('CobrandedApplication.emailList');
			}

			if (isset($this->request->data['CobrandedApplication']['id'])) {
				$id = $this->request->data['CobrandedApplication']['id'];
			} else {
				$id = null;
			}

			if (Validation::email($email)) {
				$response = $this->CobrandedApplication->sendFieldCompletionEmail($email, $id);
				if ($response['success'] == true) {
					$message = "Instructions to access this application sent to {$response['email']}.";
					$class = ' alert-success';
				} else {
					$class = ' alert-danger';
					$message = $response['msg'];
				}
				//Do not hint unauthenticated users of non existing emails (more secure)
				if (empty($this->Session->read('Auth.User.id'))) {
					$class = ' alert-success';
					$message = 'If this email is registered in your applications, a message will be sent with additional instructions. Thank you!';
				}
			} else {
				$class = ' alert-danger';
				$message = 'Email address format invalid, please try again.';
			}
			$this->set(compact('message', 'class'));
			$this->render('/Elements/Flash/customAlert1', 'ajax');
		} else {
			$this->renderError404('ERROR 404: Page does not exist.', Router::url(['controller' => 'CobrandedApplications', 'action' => 'retrieve'], true));
			return;
		}
	}

/**
 * retrieve
 *
 *
 */
	public function retrieve_with_client_token() {
		if ($this->request->is('ajax')) {
			$clientAccessToken = $this->CobrandedApplication->removeAnyMarkUp($this->request->data('CobrandedApplication.client_user_token'));

			if (!empty($clientAccessToken) && $this->CobrandedApplication->inputHasOnlyValidChars(array('value' => $clientAccessToken))) {
				$clApplicationGroup = $this->CobrandedApplication->ApplicationGroup->findByClientAccessToken($clientAccessToken);

				if (!empty($clApplicationGroup)) {
					$appGroupList = $this->CobrandedApplication->find('list', array(
						'recursive' => -1,
						'fields' => array('id'),
						'conditions' => array('application_group_id' => $clApplicationGroup['ApplicationGroup']['id'])

					));

					$emailValue = $this->CobrandedApplication->CobrandedApplicationValues->find('first', array(
						'callbacks' => false,
						'recursive' => -1,
						'fields' => array('cobranded_application_id', 'value'),
						'conditions' => array(
							'cobranded_application_id' => $appGroupList,
							'name' => 'Owner1Email',
							"value !=''" 
						)
					));
					$response = $this->CobrandedApplication->sendFieldCompletionEmail($emailValue['CobrandedApplicationValues']['value'], $emailValue['CobrandedApplicationValues']['cobranded_application_id']);
					if ($response['success'] == true) {
						$message = "Instructions to access this application sent to {$response['email']}.";
						$class = ' alert-success';
					} else {
						$class = ' alert-danger';
						$message = $response['msg'];
					}
					//Do not hint unauthenticated users of non existing emails (more secure)
					if (empty($this->Session->read('Auth.User.id'))) {
						$class = ' alert-success';
						$message = 'If this username is registered, a message will be sent with additional instructions. Thank you!';
					}
				}
			} else {
				$class = ' alert-danger';
				$message = 'Please enter a user token and try again.';
			}
			$this->request->data['CobrandedApplication']['client_user_token'] = null;
			$this->set(compact('message', 'class'));
			$this->render('/Elements/Flash/customAlert1', 'ajax');
		} else {
			$this->renderError404('ERROR 404: Page does not exist.', Router::url(['controller' => 'CobrandedApplications', 'action' => 'retrieve'], true));
			return;
		}
	}

/**
 * edit method
 *
 * @param string $uuid
 * @return void
 */
	public function edit($uuid = null) {
		if(!$this->CobrandedApplication->hasAny(array('CobrandedApplication.uuid' => $uuid))) {
			$this->renderError404('ERROR 404: Page does not exist.', Router::url(['controller' => 'CobrandedApplications', 'action' => 'edit', $uuid], true));
			return;
		}
		if ($this->CobrandedApplication->isExpired($uuid) && !$this->Auth->loggedIn()) {
			$this->redirect(array('action' => '/expired/' . $uuid));
		} else {
			//check if app is out of sync with template
			$appId = $this->CobrandedApplication->field('id', array('uuid' => $uuid, "data_to_sync != ''", 'status NOT IN' => array(CobrandedApplication::STATUS_SIGNED, CobrandedApplication::STATUS_COMPLETED)));
			if (!empty($appId)) {
				$this->CobrandedApplication->syncApp($appId);
			}

			$options = array('conditions' => array('CobrandedApplication.uuid' => $uuid));
			$this->request->data = $this->CobrandedApplication->find('first', $options);

			if (empty($this->request->data['CobrandedApplication']['application_group_id']) && $this->Auth->loggedIn()) {
				$this->CobrandedApplication->addAppToGroup($this->request->data['CobrandedApplication']['id']);
			}
			$template = $this->CobrandedApplication->getTemplateAndAssociatedValues($this->request->data['CobrandedApplication']['id'], $this->Auth->user('id'));
			$valuesMap = $this->CobrandedApplication->buildCobrandedApplicationValuesMap($this->request->data['CobrandedApplicationValues']);
			$rsTemplateUuid = $template['Template']['rightsignature_template_guid'];

			$this->set('valuesMap', $valuesMap);
			$this->set('brand_logo_url', $template['Template']['Cobrand']['brand_logo_url']);
			$this->set('cobrand_logo_url', $template['Template']['Cobrand']['cobrand_logo_url']);
			$this->set('include_brand_logo', $template['Template']['include_brand_logo']);
			$this->set('cobrand_logo_position', $template['Template']['logo_position']);
			$this->set('logoPositionTypes', $this->CobrandedApplication->Template->logoPositionTypes);
			$this->set('rightsignature_template_guid', $rsTemplateUuid);
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
 * api_add method
 * Sagger Documentation for this API endpoint was moved to the top CobrandedApplications Model due to its large size.
 * 
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
				$preCheckErrors = null;
				if (Hash::get($data, 'm2m') == true && empty($data['external_record_id'])) {
					$preCheckErrors[] = 'An external_record_id is required.';
				} elseif (!empty($data['external_record_id']) && $this->CobrandedApplication->hasAny(array('external_foreign_id' => $data['external_record_id']))) {
					$preCheckErrors[] = 'Could not create application because an application already exists for this client.';
				} else {
					if (empty(Hash::get($data, 'template_id'))) {
						$preCheckErrors[] = 'A template_id is required to create an application.';
					} elseif ($this->CobrandedApplication->Template->hasAny(array('id' => $data['template_id'], 'require_client_data' => true))) {
						if (empty(Hash::get($data, 'ClientId'))) {
							$preCheckErrors[] = 'Client ID is required to create this application but none detected.';
						}
						if (empty(Hash::get($data, 'ClientName'))) {
							$preCheckErrors[] = 'Client Name is required to create this application but none detected.';
						}
					}
					if (empty(Hash::get($data, 'ContractorID'))) {
						$preCheckErrors[] = 'The ContractorID is required to create application';
					}
				}
				if (empty($preCheckErrors)) {
					$user = $this->User->find('first', array('conditions' => array(
						'User.fullname' => Hash::get($data, 'ContractorID')
						)));
					if (empty($user)) {
						$response['messages'] = "A user account for Rep named '" . Hash::get($data, 'ContractorID'). "' was not found!";
					} else {
						$response = $this->CobrandedApplication->saveFields($user['User'], $data);
						$requireCSheet = $this->CobrandedApplication->Template->hasAny(array('id' => $data['template_id'], 'requires_coversheet' => true));
						if ($requireCSheet && $response['status'] = AppModel::API_SUCCESS) {
							$app = $this->CobrandedApplication->find('first', array('fields' => array('id', 'user_id'), 'conditions' => array('uuid' => Hash::get($response, 'application_id'))));
							$cSheetMsg = 'Failed to create a coversheet!';
							try {
								if ($this->CobrandedApplication->Coversheet->createNew($app['CobrandedApplication']['id'], $app['CobrandedApplication']['user_id'], $data)) {
									$cSheetMsg = 'Coversheet created successfully';
								}
							} catch (Exception $e) {/*no need to do anything*/}
							$response['messages'][] = $cSheetMsg;
						}
					}
				} else {
					$response['messages'] = $preCheckErrors;
				}
			}
		} else {
			$this->response->statusCode(405); //405 Method Not Allowed
		}
		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}

/**
 *
 * Update existing application data.
 * The data to be submitted varies depending on the Template used to create the applicaton.
 * API consumers may perform an API call to /api/CobrandedApplications/view?application_id={uuid string for the application} and use an updated version of the returned JSON data to perform this update. 
 * 
 * @OA\Put(
 *   path="/api/CobrandedApplications/edit/{application_id}",
 *	 tags={"CobrandedApplications"},
 *   summary="Updates application data with JSON data. Any non-required fields that do not need to be updated should be omitted from the JSON data",
 *	 @OA\RequestBody(ref="#/components/requestBodies/CobrandedApplications"),
 *   @OA\Parameter(
 *     name="application_id",
 *     in="path",
 *     description="UUID of application that needs to be updated",
 *     required=true,
 *     @OA\Schema(
 *         type="string",
 *         format="/^[0-9a-f]{8}-([0-9a-f]{4}-){3}[0-9a-f]{12}$/"
 *     )
 *   ),
 *	 @OA\Response(
 *     response=200,
 *     @OA\MediaType(
 *         mediaType="application/json",
 *		   example={"status": "success","messages": null, "data": {"status": "saved", "fieldName1":"value", "fieldName2":"value", "etc":"..."}},
 *         @OA\Schema(
 *	   	   	 ref="#/components/schemas/CobrandedApplications",
 *         )
 *     ),
 *     description="
 * 			status=success when all data passed validation and was saved successfully.,
 * 			status=failed when application status is 'completed' or 'signed' and can no longer be edited,
 * 			status=failed when some or all data fails to save due to data validation errors. Fields listed in the 'validationErrors' array will not be updated but all others that do pass validation will indeed be updated,
 * 			status=failed when no application is found for the currently authenticated API consumer and the given application_id parameter,
 * 			status=failed when no data was sent",
 *   ),
 *   @OA\Response(
 *     response=400,
 *     description="Bad Request when required parameter is missing or is not a uuid string"
 *   ),
 *   @OA\Response(
 *     response=405,
 *     description="HTTP method not allowed when request method is not PUT"
 *   )
 * )
 */
	public function api_edit($appUuid) {
		$this->autoRender = false;
		$response = array('status' => AppModel::API_FAILS, 'messages' => 'HTTP method not allowed');
		if ($this->request->is('put')) {
			if (!empty($appUuid) && Validation::uuid($appUuid)) {
				$appStatus = $this->CobrandedApplication->field('status', ['user_id' => $this->Auth->user('id'), 'uuid' => $appUuid]);
				//if false the application does not exist
				if ($appStatus !== false && $appStatus === CobrandedApplication::STATUS_SAVED) {
					//get data from request object
					$data = $this->request->input('json_decode', true);
					if (empty($data)){
						$data = $this->request->data;
					}
					//response if JSON data or form data was not passed
					$response = array('status'=>'failed', 'messages'=>'No data was sent');
					if (!empty($data)) {
						$user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->User('id'))));
						$data['uuid'] = $appUuid;
						$response = $this->CobrandedApplication->saveFields($user['User'], $data);
					}
				} else {
					$message = "No aplication found for current user and specified application_id.";
					if ($appStatus !== false) {
						$message = "Application locked for editing, application status is: $appStatus";
					}
					$response['messages'] = $message;
				}
			} else {
				$this->response->statusCode(400); //400 Bad Request
				$response['messages'] = 'Missing or invalid query parameter.';
			}
		} else {
			$this->response->statusCode(405); //405 Method Not Allowed
		}
		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}

/**
 *
 * Handles API GET request for a list of Applications assigned a specified sales representative.
 * Request parameters are all optional except for the rep_name. No results will be returned if the name of the rep is ommited.
 * A full list of all applications assigned to the specified sales rep_name will be returned when no parameters are passed in query
 *
 * @OA\Get(
 *   path="/api/CobrandedApplications/index?rep_name={sales rep full name}&search={text to seach for in application}&status={one of: saved/pending/validate/completed/signed}",
 *	 tags={"CobrandedApplications"},
 *   summary="list applications assigned to specified sales representative's name",
 *	 @OA\Parameter(
 *		   name="rep_name",
 *		   description="The full name of the sales repesentative (first name last name) who created or is assigned to the application. Value must match their name in their user profile in the online application system.
 *	 			Example 1: Sam Salesman,
 *	 			Example 2: John Doe",
 *         in="query",
 *         @OA\Schema(type="string")
 *   ),
 *	 @OA\Parameter(
 *		   name="search",
 *		   description="Text to search for in the application, for example the company name, or DBA or even the string uuid of the application.
 *	 			Example 1: Health Systems Corp,
 *	 			Example 2: John Doe (a company contact name),
 *	 			Example 3: d1b5ba0c-d614-4856-a49e-fdd6b86682a0 (an application_id)",
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
 *		   example={{"application_id": "d1b5ba0c-d614-4856-a49e-fdd6b86682a0","user_id": 1234,"status": "signed","partner_name": "Experian","template_id": 1234,"template_name": "Default with BO","dba": "Test Co (DBA)","corp_name": "Test Co.","modified": "2030-06-13 14:40:21"}},
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
			$appData = null;
			$user = $this->User->find('first', array('recursive' => -1, 'conditions' => array(
				'User.fullname' => $this->request->query('rep_name'),
			)));
			if (!empty($user)) {
				$this->request->query['user_id'] = $user['User']['id'];
				$conditions = $this->CobrandedApplication->parseCriteria($this->request->query);
				$appData = $this->CobrandedApplication->find('index', array(
					'conditions' => $conditions,
					'order' => array('CobrandedApplication.modified DESC')
					)
				);
				$response['data'] = [];
				$response['status'] = AppModel::API_SUCCESS;
			}
			
			if (!empty($appData)) {
				$response['messages'] = null;
				foreach($appData as $application) {
					$response['data'][] = array(
						'application_id' => $application['CobrandedApplication']['uuid'],
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
				$response['messages'] = "No aplications found for specified sales rep and/or search parameters.";
			}
		} else {
			$this->response->statusCode(405); //405 Method Not Allowed
		}

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}

/**
 *
 * Handles API GET request to view the data for an existing Application that the authenticated API consumer has access to.
 * Request parameter is required.
 * An array containing all data including empty fields or fields that lack data will be returned.
 *
 * @OA\Get(
 *   path="/api/CobrandedApplications/view?application_id={uuid string for the application}",
 *	 tags={"CobrandedApplications"},
 *   summary="Returns JSON array containing all data for a specific application including empty fields.",
 *	 @OA\Parameter(
 *		   name="application_id",
 *		   description="A universally unique id (UUID) of an existing application, which the authenticated API consumer can access.
 *	 			Example: d1b5ba0c-d614-4856-a49e-fdd6b86682a0",
 *         in="query",
 *         @OA\Schema(type="string")
 *   ),
 *	 @OA\Response(
 *     response=200,
 *     @OA\MediaType(
 *         mediaType="application/json",
 *		   example={"status": "success","messages": null, "data": {"status": "saved", "fieldName1":"value", "fieldName2":"value", "etc":"..."}},
 *         @OA\Schema(
 *	   	   	 ref="#/components/schemas/CobrandedApplications",
 *         )
 *     ),
 *     description="
 * 			status=success JSON array containing all application data, including application status. The array keys are the field names which differ depending on the Template that was used to create the application.
 * 			status=failed when no application is found for the currently authenticated API consumer and the given application_id parameter",
 *   ),
 *   @OA\Response(
 *     response=400,
 *     description="Bad Request when required parameter is missing or is not a uuid string"
 *   ),
 *   @OA\Response(
 *     response=405,
 *     description="HTTP method not allowed when request method is not GET"
 *   )
 * )
 *
 * @return void
 */
	public function api_view() {
		$this->autoRender = false;
		$response = array('status' => AppModel::API_FAILS, 'messages' => 'HTTP method not allowed');
		if ($this->request->is('get')) {
			if (!empty($this->request->query['application_id']) && Validation::uuid($this->request->query['application_id'])) {
				$userId = $this->Auth->user('id');
				$appUuid = $this->request->query['application_id'];
				if ($this->CobrandedApplication->hasAny(['user_id' => $userId, 'uuid' => $appUuid])) {
					$keys = '';
					$values = '';
					$id = $this->CobrandedApplication->field('id', ['uuid' => $appUuid]);
					$status = $this->CobrandedApplication->field('status', ['id' => $id]);
					$this->CobrandedApplication->buildExportData($id, $keys, $values, true);
					//combine key=>vals and prepend app status not included in returned data
					$data = array('status' => $status) + array_combine($keys, $values); 
					$response['data'] = $data;
					$response['status'] = AppModel::API_SUCCESS;
					$response['messages'] = null;
				} else {
					$response['messages'] = "No aplication found for current user and specified application_id.";
				}
			} else {
				$this->response->statusCode(400); //400 Bad Request
				$response['messages'] = 'Missing or invalid query parameter.';
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
		$this->CobrandedApplication->updateAll(
			['status' => "'completed'"],
			['id' => 22616]
		);
		//reset all of the search parameters
		if(isset($this->request->data['reset'])) {
			foreach($this->request->data['CobrandedApplication'] as $i => $value) {
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
			$clIdGlobal = $this->request->data('CobrandedApplication.client_id_global');
			$clNameGlobal = $this->request->data('CobrandedApplication.client_name_global');
			$appTemplate = $this->request->data['CobrandedApplication']['template_id'];
			if ($applicationId) {
				$appCopyId = $this->CobrandedApplication->copyApplication($applicationId, $this->Session->read('Auth.User.id'), $appTemplate);
				if ($appCopyId !== false) {
					if (!empty($clIdGlobal)) {
						$this->CobrandedApplication->save(array('id' => $appCopyId, 'client_id_global' => $clIdGlobal, 'client_name_global' => $clNameGlobal), array('validate' => false));
					}
					$this->_success(__("Application $applicationId copied"), array('action' => 'index'));
				} else {
					$this->_failure(__("Failed to copy application $applicationId"), array('action' => 'index'));
				}
			}

			// now try to save with the data from the user model
			$tmpUser = $user;
			$tmpUser['User']['template_id'] = $appTemplate;
			$appNewData = ["uuid" => $this->request->data['CobrandedApplication']['uuid'], "clientIdGlobal" => $clIdGlobal, "clientNameGlobal" => $clNameGlobal];
			$response = $this->CobrandedApplication->createOnlineappForUser($tmpUser['User'], $appNewData);

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
 * admin_is_client_data_required
 * Ajax method to check if the given global client ID is valid.
 *
 * @param string $clientId string client id
 * @return void
 */
	public function admin_validate_client_id($clientId) {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			if ($this->Session->read('Auth.User.id')) {
				if (!empty($clientId)) {
					try {
						$result = $this->CobrandedApplication->getSfClientNameByClientId($clientId);
						return ($result === false)? json_encode(['valid' => false]) : json_encode($result);
					} catch (Exception $e) {
						$this->response->statusCode(500);
						return;
					}
					
				} else {
					//Bad Request
					$this->response->statusCode(400);
				}
			} else {
				//session expired
				$this->response->statusCode(401);
			}
		} else {
			//Bad Request
			$this->response->statusCode(400);
		}
	}

/**
 * admin_update_client_id
 * Ajax method to update client ids.
 *
 * @param string $id string a CobrandedApplication.id
 * @param string $clientId string an 8 digit client id
 * @return void
 */
	public function admin_update_client_id($id, $clientId) {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			if ($this->Session->read('Auth.User.id')) {
				if (!empty($clientId)) {
					try {
						$result = $this->CobrandedApplication->getSfClientNameByClientId($clientId);
						if ($result === false) {
							return json_encode(['valid' => false]);
						} else {
							$update = array('id' => $id, 'client_id_global' => $result['Client_ID__c'], 'client_name_global' => $result['Client_Name__c']);
							$this->CobrandedApplication->save($update, ['validate' => false]);
							return json_encode($result);
						}
					} catch (Exception $e) {
						$this->response->statusCode(500);
						return;
					}
					
				} else {
					//Bad Request
					$this->response->statusCode(400);
				}
			} else {
				//session expired
				$this->response->statusCode(401);
			}
		} else {
			//Bad Request
			$this->response->statusCode(400);
		}
	}

/**
 * admin_amend_completed_document method
 *
 * @param int|string $id the CobrandedApplication.id integrer or string representation of integer id
 * @throws NotFoundException
 * @return void
 */
	public function admin_amend_completed_document($id, $rsDocIsExpired = false) {
		if (!$this->CobrandedApplication->exists($id)) {
			$this->_failure(__("Application number $id does not exist!"));
			$this->redirect($this->referer());
		}
		$appData = $this->CobrandedApplication->find('first', [
			'recursive' => -1,
			'fields' => ['id','rightsignature_document_guid', 'status'],
			'conditions' => ['id' => $id],
		]);
		if (empty($appData['CobrandedApplication']['rightsignature_document_guid'])) {
			$this->_failure(__("This application does not have a document for signing and thus there is nothing to amend."));
			$this->redirect($this->referer());
		}
		$client = $this->CobrandedApplication->createRightSignatureClient();
		if ($this->request->is('ajax')) {
			$docDetals = $client->getDocumentDetails($appData['CobrandedApplication']['rightsignature_document_guid']);
			$rsDocData = json_decode($docDetals, true);

			$this->set(compact('appData', 'rsDocData'));
			$this->render('admin_amend_completed_document');
		} elseif ($this->request->is('post') || $this->request->is('put')) {
			// Void existing RS document if not expired
			if ($rsDocIsExpired == false) {
				$response = $client->api->post($client->base_url.RightSignature::API_V1_PATH. "/documents/" . $appData['CobrandedApplication']['rightsignature_document_guid']."/void");
				$resData = json_decode($response, true);
			}
			
			if ($rsDocIsExpired || $response->isOk()) {
				// on success delete original RS doc reference in CobrandedApplication.rightsignature_document_guid
				$this->CobrandedApplication->save(['id'=> $id, 'status' => CobrandedApplication::STATUS_SAVED, 'rightsignature_document_guid'=> null], ['validate' => false]);
				//show success message
				$this->_success(__('Application status has been reverted to "saved" and its document has been deleted. You may now edit the application and make any necessary updates.'));
				$this->redirect($this->referer());
			} else {
				$this->_failure('API error ocurred! ' . $resData['error']);
				$this->redirect($this->referer());
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
 * app_info_summary method
 *
 * @throws NotFoundException
 * @param string $id CobrandedApplication.id
 * @return void
 */
	public function app_info_summary($id = null) {
		$app = $this->CobrandedApplication->find('index', array(
			'conditions' => array('CobrandedApplication.id' => $id)
		));
		$app = array_pop($app);
		$clientIdValid = true;
		if (!empty($app['CobrandedApplication']['client_id_global'])) {
			$result = $this->CobrandedApplication->getSfClientNameByClientId($app['CobrandedApplication']['client_id_global']);
			$clientIdValid = ($result !== false);
		}

		if ($this->CobrandedApplication->isEncrypted($app['ApplicationGroup']['client_password'])) {
			$app['ApplicationGroup']['client_password'] = $this->CobrandedApplication->decrypt($app['ApplicationGroup']['client_password'], Configure::read('Security.OpenSSL.key'));
		}

		$this->set(compact('app','clientIdValid'));
		$this->render('/Elements/cobranded_applications/info_summary', 'ajax');
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
			$requestUri = '/api/Users/get_reps?'. http_build_query(["user_name" => Hash::get($appRep, 'ContractorID')], "", null, PHP_QUERY_RFC3986);

			$axDbApiClient = $this->CobrandedApplication->createAxiaDbApiAuthClient('GET', $requestUri);
			$reponse = $axDbApiClient->get('https://db.axiatech.com'.$requestUri);
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
			$axDbApiClient = $this->CobrandedApplication->createAxiaDbApiAuthClient('POST', '/api/Merchants/add');
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
			$this->_success(__("Application $id deleted"));
			$this->redirect($this->referer());
		}
		$this->_failure(__("Application $id was not deleted"));
		$this->redirect($this->referer());
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
 * send_pdf_to_client method
 * Handles requests to email executed PDF documents to a known clients email address.
 *
 * @param $id int a CobrandedApplication.id
 * @return void
 */
	public function send_pdf_to_client($id) {
		$emails = array();
		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$values = $this->CobrandedApplication->getDataForCommonAppValueSearch($id);
			foreach($values as $value) {
				if (strpos($value, '@') !==false) {
					$emails[$this->CobrandedApplication->encrypt($value, Configure::read('Security.OpenSSL.key'))] = $this->CobrandedApplication->maskUsernamePartOfEmail($value);
				}
			}

			if (empty($emails) ) {
				$this->_failure('Sorry a copy of this document cannot be requested from our site at this time, contact your sales representative and we will gladly provide you a copy.');
			}
			$this->set(compact('emails', 'id'));
			$this->render('/Elements/Ajax/send_client_pdf_email', 'ajax');
		} elseif($this->request->is('post')) {
			$clientEmail = $this->CobrandedApplication->decrypt($this->request->data('CobrandedApplication.client_email'), Configure::read('Security.OpenSSL.key'));
			$appId = $this->request->data('CobrandedApplication.id');
			//Verify input to make sure eamil form field value was not tampered with.
			try {
				$this->CobrandedApplication->emailSignedDocToClient($appId, $clientEmail);
				$this->_success("An email has been sent to the selected address containing download instructions.");
			} catch (Exception $e) {
				$this->_failure($e->getMessage());
			}

			$this->redirect($this->referer());
		}
	}

/**
 * admin_renew_mod_date
 * Updates last modified date to be within the timeframe after which apps are considered to be "Access expired".
 * See Configure::read('App.access_validity_age');
 * 
 * @param integer $id
 * @param varchar $renew
 * RightSignature Document Guid Allows for extending life of application
 */
	function admin_renew_modified_date($uuid) {
		if ($this->CobrandedApplication->isExpired($uuid)) {
			$daysValid = intval(Configure::read('App.access_validity_age'));
			//For security we dont want to add the full access_validity_age, instead we will subtract two thirds from it
			//and by doing so we are effectiely adding one third of those days as the new expiration.
			$dayRangeToSubtract = intval((intval($daysValid) / 3) - $daysValid);
			//Check for zero value and default to T-1 day
			$dayRangeToSubtract = ($dayRangeToSubtract == 0)? "-1 day" : $dayRangeToSubtract . " day";
			$newModDate = (new \DateTime())->modify($dayRangeToSubtract)->format("Y-m-d H:i:s");
			$app = $this->CobrandedApplication->find('first', array(
				'recursive' => -1,
				'fields' => array('id', 'modified'),
				'conditions' => array('uuid' => $uuid)
			));
			$app['CobrandedApplication']['modified'] = $newModDate;
			if ($this->CobrandedApplication->save($app, ['validate' => false])) {
				$this->_success('Application renewed, client may now access it for an additional ' . intval((intval($daysValid) / 3)) . ' days');
			} else {
				$this->_failure('Could not renew application at this time please try again.');
			}
		} else {
			$this->_failure('Application has not yet expired.');
		}
		$this->redirect($this->referer());
	}

/**
 * Redirects user to the application pdf
 *
 * @param integer $id application id
 * @param boolean $getOriginalPdf true to specify whether to retrieve the original blank pdf template used to create this application's completed document.
 * @return void
 */
	function admin_open_app_pdf($id, $getOriginalPdf = false) {
		$canOverrideTemplate = ($this->Auth->user('group') == User::ADMIN);
		$pdfUrl = $this->CobrandedApplication->getAppPdfUrl($id, $canOverrideTemplate, $getOriginalPdf);
		if (!empty($pdfUrl)) {
			$this->redirect($pdfUrl);
		} else {
			$this->_failure(__('Application PDF is not available or could not be found! Contact support for help retrieving this document PDF.'), array('action' => 'index', 'admin' => true));
		}
	}

/**
 * Redirects external user to the application pdf
 * Authenticates request with a one-time-use request token asociated with the app
 *
 * @param integer $id application id
 * @return void
 */
	function pdf_doc_token_dl() {
		if ($this->request->is('get')) {
			$encToken = Hash::get($this->request->query, 'token');
			$tokenParts = explode(':', base64_decode($encToken));
			$appId = Hash::get($tokenParts, 0);
			$secret = Hash::get($tokenParts, 1);
			if ($this->CobrandedApplication->hasAny(['id' => $appId, 'doc_secret_token' => $secret])) {
				$pdfUrl = $this->CobrandedApplication->getAppPdfUrl($appId, true);
				if (!empty($pdfUrl)) {
					$this->CobrandedApplication->id = $appId;
					$this->CobrandedApplication->saveField('doc_secret_token', null);
					$this->redirect($pdfUrl);
				}
			}
		}
		$this->set('name', 'ERROR 404: Document Not Found Or Has Expired. For assistance contact AxiaMed support.');
		$this->set('url', Router::url(['controller' => 'CobrandedApplication', 'action' => 'pdf_doc_token_dl'], true));
		$this->render('/Errors/error404');
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

				$rsTemplateUuid = $cobrandedApplication['Template']['rightsignature_template_guid'];

				if ($achYes == true) {
					$rsTemplateUuid = $this->CobrandedApplication->getRightsignatureTemplateGuid($cobrand['Cobrand']['partner_name'], 'ach');
				}

				$response = $this->CobrandedApplication->getRightSignatureTemplate($client, $rsTemplateUuid);
				$response = json_decode($response, true);

				if ($response && !empty($response['reusable_template']['id'])) {
					$applicationJSON = $this->CobrandedApplication->createRightSignatureDocumentJSON(
						$applicationId, $this->Session->read('Auth.User.email'), $response['reusable_template']);
					$response = $this->CobrandedApplication->createRightSignatureDocument($response['reusable_template']['id'], $client, $applicationJSON);
					$tmpResponse = json_decode($response, true);

					if ($tmpResponse && key_exists('error', $tmpResponse)) {
						$url = "/edit/" . $cobrandedApplication['CobrandedApplication']['uuid'];
						$this->_failure(__('Cannot render document for signing, the following error ocurred Error!: ' . $tmpResponse['error']. '. Please contact your representative.'));
						$this->redirect(array('action' => $url));
					}
				} else {
					$url = "/edit/" . $cobrandedApplication['CobrandedApplication']['uuid'];
					$this->_failure(__(CobrandedApplication::RIGHTSIGNATURE_NO_TEMPLATE_ERROR));
					$this->redirect(array('action' => $url));
				}

				$response = json_decode($response, true);
			} else {
				$response['document']['state'] = 'pending';
				$response['document']['id'] = $cobrandedApplication['CobrandedApplication']['rightsignature_document_guid'];
			}
			$appValues = Hash::combine(
				$cobrandedApplication,
				'CobrandedApplicationValues.{n}.name',
				'CobrandedApplicationValues.{n}.value'
			);
			$merchDBA = (!empty($appValues['DBA']))? $appValues['DBA'] : Hash::get($appValues, 'CorpName');
			if ($response['document']['state'] == 'pending' && $response['document']['id']) {
				// save the guid
				$this->CobrandedApplication->save(
					array(
						'CobrandedApplication' => array(
							'id' => $applicationId,
							'rightsignature_document_guid' => $response['document']['id'],
							'status' => 'completed'
						)
					),
					array('validate' => false)
				);

				// check whether they want to sign in person
				if ($signNow) {
					$this->redirect(array('action' => 'sign_rightsignature_document?guid=' . $response['document']['id']));
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
 * signerHasSigned method
 * Makes API request to rightsignature to get document details and checks if the signer provided in the 
 * function parameter has signed the document.
 *
 * @param string $documentId RightSignature document UUID
 * @param string $signerId a UUID that was assigned to a signer by rightsignature after creation of document
 * @return void
 */
	public function signerHasSigned($documentId, $signerId) {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			if (!empty($documentId) && !empty($signerId)) {
				$client = $this->CobrandedApplication->createRightSignatureClient();
				$docDetails = $client->getDocumentDetails($documentId);
				$docDetails = json_decode($docDetails, true);
				$signerHasSigned = false;
				if (empty(Hash::get($docDetails, 'error'))) {
					// all good
					foreach($docDetails['document']['recipients'] as $signer) {
						if ($signer['id'] == $signerId && $signer['status'] == 'signed') {
							$signerHasSigned = true;
							break;
						}
					}
				} else {
					//Unexpected internal error
					$this->response->statusCode(500);
					echo json_encode(['error' => Hash::get($docDetails, 'error')]);
					return;
				}
				echo json_encode(['signerHasSigned' => $signerHasSigned]);
				return;
			} else {
				echo json_encode(['error' => 'Missing required parameters']);
			}
		} else {
			//Bad Request
			$this->response->statusCode(400);
		}
	}
/**
 * sign_rightsignature_document
 *
 */
	public function sign_rightsignature_document() {
		$guid = Hash::get($this->request->query, 'guid');

		if (empty($guid) || strlen($guid) != 36) {
			$this->set('name', 'ERROR 404: Document Not Found Or Has Expired. Please contact your representative or AxiaMed support for assistance with this document.');
			$this->set('url', Router::url(['controller' => 'CobrandedApplication', 'action' => 'sign_rightsignature_document', '?' => ['guid' => $guid]], true));
			$this->render('/Errors/error404');
			return;
		}
		$client = $this->CobrandedApplication->createRightSignatureClient();
		$this->set('rightsignature', $client);

		$is_mobile_safari = preg_match("/\bMobile\b.*\bSafari\b/", Hash::get($_SERVER, 'HTTP_USER_AGENT'));
		$this->set('is_mobile_safari', $is_mobile_safari);

		// Width of Widget, CANNOT BE CHANGED and it cannot be dynamic. 706 is optimal size
		$widgetWidth = 706;
		$this->set('widgetWidth', $widgetWidth);

		// Height of widget is changeable (Optional)
		$widgetHeight = 900;
		$this->set('widgetHeight', $widgetHeight);

		$this->set('guid', $guid);

		// Extract signer links from docuent
		$docDetails = $client->getDocumentDetails($guid);
		$docDetails = json_decode($docDetails, true);
		$this->set('docDetails', $docDetails);
		$appTemplateId = null;
		$appTemplateId = $this->CobrandedApplication->field('template_id', ['rightsignature_document_guid' => $guid]);
		$varSigner = false;
		if ($appTemplateId) {
			$this->layout = 'default';
		} else {
			$appTemplateId = $this->CobrandedApplication->field('template_id', ['rightsignature_install_document_guid' => $guid]);
			$varSigner = true;
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
		$error = false;
		if (!empty(Hash::get($docDetails, 'error'))) {
			$error = true;
		} elseif (Hash::get($docDetails, 'document.state') == 'executed') {
			$error = true;
			$alreadySigned = true;
		}
		$this->set('error', $error);
		$this->set('alreadySigned', $alreadySigned);
		$this->set('apiErrorMsg', Hash::get($docDetails, 'error'));
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
		$templateUuid = $cobrandedApplication['Template']['rightsignature_install_template_guid'];
		$response = $this->CobrandedApplication->getRightSignatureTemplate($client, $templateUuid);
		$response = json_decode($response, true);

		if ($response && !empty($response['reusable_template']['id'])) {
			$subject = "Axia Install Sheet - VAR";
			$applicationJSON = $this->CobrandedApplication->createRightSignatureDocumentJSON(
				$applicationId, $this->Session->read('Auth.User.email'), $response['reusable_template'], $subject,
				$this->request->data['CobrandedApplication']['select_terminal_type']);

			$response = $this->CobrandedApplication->createRightSignatureDocument($response['reusable_template']['id'], $client, $applicationJSON);
			$response = json_decode($response, true);

			if ($response['document']['state'] == 'pending' && $response['document']['id']) {
				if ($this->CobrandedApplication->save(
						array(
							'CobrandedApplication' => array(
								'id' => $applicationId,
								'rightsignature_install_document_guid' => $response['document']['id']
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
				$this->_failure(__('error! could not send the document ' . Hash::get($response, 'error')));
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
 *
 * 
 */
	public function document_callback() {
		CakeLog::write('debug', print_r($this->request->data, true));

		if ($this->request->data['id'] && $this->data['event'] == 'executed') {

			$data = $this->CobrandedApplication->findByRightsignatureDocumentGuid($this->request->data['id']);

			if (empty($data)) {
				$data = $this->CobrandedApplication->findByRightsignatureInstallDocumentGuid($this->request->data['id']);
				if (!empty($data)) {
					$this->CobrandedApplication->id = $data['CobrandedApplication']['id'];
					$this->CobrandedApplication->saveField('rightsignature_install_status', 'signed');
					$this->CobrandedApplication->repNotifySignedEmail($data['CobrandedApplication']['id'], 'rep_notify_signed_install_sheet');

					$hasEntry = $this->CobrandedApplication->Merchant->TimelineEntry->hasAny(
						array(
								'TimelineEntry.merchant_id' => $data['Merchant']['merchant_id'],
								'TimelineEntry.timeline_item' => 'SIS'
							)
					);
					if (!$hasEntry) {
						$this->CobrandedApplication->Merchant->TimelineEntry->query("INSERT INTO timeline_entries VALUES ('{$data['Merchant']['merchant_id']}', 'SIS', NOW(), 'f')");
					}
				}
				exit;
			}

			if (!empty($data)) {
				$this->CobrandedApplication->id = $data['CobrandedApplication']['id'];
				$this->CobrandedApplication->saveField('status', 'signed');
				$this->CobrandedApplication->repNotifySignedEmail($data['CobrandedApplication']['id']);
				$this->CobrandedApplication->sendSignedAppToUw($data['CobrandedApplication']['id']);

				if ($data['Coversheet']['status'] == 'validated') {
					$this->sendCoversheet($data);
				}
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

/**
 * rs_document_audit
 * Ajax method gets rightsignature document access audit details
 *
 * @param string $rsDocumentId string RightSignature external document id
 * @return void
 */
	public function rs_document_audit($rsDocumentId) {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			if ($this->Session->read('Auth.User.id')) {
				if (!empty($rsDocumentId)) {
					$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
					$client = $this->CobrandedApplication->createRightSignatureClient();
					$docDetals = $client->getDocumentDetails($rsDocumentId);
					$docDetals = json_decode($docDetals, true);
					if (empty(Hash::get($docDetals, 'error'))) {
						//all good
						$docStatus = $docDetals['document']['state'];
						$auditTrail = $docDetals['document']['audits'];
						$this->set(compact('auditTrail', 'docStatus'));
						$this->render('/Elements/cobranded_applications/rs_doc_audit', 'ajax');
					} else {
						//Unexpected internal error
						$this->response->statusCode(500);
						return;
					}
				} else {
					//Bad Request
					$this->response->statusCode(400);
				}
			} else {
				//session expired
				$this->response->statusCode(401);
			}
		} else {
			//Bad Request
			$this->response->statusCode(400);
		}
	}

}
