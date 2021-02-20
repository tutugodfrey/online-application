<?php
App::uses('User', 'Model');
App::uses('Okta', 'Model');
class UsersController extends AppController {

	public $permissions = array(
		'login' => '*',
		'logout' => '*',
		'request_pw_reset' => '*',
		'change_pw' => '*',
		'get_user_templates' => '*',
		'reset_api_info' => array(User::ADMIN, User::REP, User::MANAGER, User::API),
		'verify_okta_mfa' => array('*'),
		'reset_okta_mfa' => array(User::ADMIN, User::REP, User::MANAGER, User::API),
		'admin_okta_mfa_enroll' => array(User::ADMIN, User::REP, User::MANAGER, User::API),
	);

	public $components = array('Search.Prg');
/**
 * Logic to be applied before page load
 *
 * @return null
 */

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('login', 'logout', 'request_pw_reset', 'change_pw', 'verify_okta_mfa'));
	}

/**
 * Create and Manage API Tokens
 *
 * @param integer $id Users.user_id
 * @return null
 */

	public function admin_token($id) {
		$this->User->id = $id;
		$conditions = array('conditions' => array('User.id' => $id), 'recursive' => -1);
		$data = $this->User->find('first', $conditions);
		if ($data['User']['api_enabled'] === true) {
			$this->_failure('This user already has valid API Credentials! To reset credentials this user must log in and perform a reset.');
			$this->redirect($this->referer());
		} else {
			$token = sha1(CakeText::uuid());
			$this->User->set(array('token' => $token, 'api_enabled' => true, 'api' => true));
			if (!$this->User->save()) {
				$token = null;
				$this->_failure('There was an error generating this token');
			}

			if ($this->Session->read('Auth.User.id') == $id) {
				$this->Session->write('Auth.User.api_enabled', true);
			}
			$msg = "Hello and Welcome to our API!\n";
			$msg .= Configure::read('Axia.ApiUserEmailBody');
			$msg .= "\nOnline App Login Page: " . Router::url(['controller' => 'Users', 'action' => 'login'], true);
			$msg .= "\nPlease contact us if you need any assistance.";
			$args['subject'] = 'AxiaMed Online Application System API';
			$args['viewVars'] = ['content' => $msg];
			$args = $this->User->getEmailArgs($id, $args);
			$this->User->sendEmail($args);
			$this->_success('API access enabled and username/token generated. Email sent to user with instructions to generate their API password.');
			$this->redirect($this->referer());
		}
	}

/**
 * admin_edit method
 *
 * @param string $id CobrandedApplication id
 * @param boolean $reset CobrandedApplication id
 * @throws NotFoundException
 * @return void
 */
	public function reset_api_info($id = null, $reset = false) {
		if ($this->request->is('ajax') && $this->Session->check('Auth.User.id')) {
			$apiToken = $this->User->field('token', ['id' => $id]);
			$this->set(compact('apiToken'));
			if ($reset === false) {
				$this->render('/Elements/Ajax/api_info');
			} else {
				$apiPassword = $this->User->generateRandPw();
				$this->User->save(['id' => $id, 'api_password' => $apiPassword]);
				$this->set(compact('apiPassword'));
				$this->render('/Elements/Ajax/api_info_content');
			}
		} elseif (!$this->Session->check('Auth.User.id')) {
			$this->response->statusCode(403);
		}
	}

/**
 * Allow users to login
 *
 * @return null
 */
	public function login() {
		if ($this->request->is('post')) {
			$user = $this->Auth->identify($this->request, $this->response);
			if (!empty($user)) {
				$passwordValidDays = $this->User->getDaysTillPwExpires($user['id']);
				if ($passwordValidDays < 0) {
					$this->Session->setFlash(__('Password expired! Please renew your password.'), 'default', ['class' => 'alert alert-warning strong']);
					$redirectUrl = Router::url(array('controller' => 'Users', 'action' => 'login'));
					$this->set('redirectUrl', $redirectUrl);
					$this->render('/Elements/Ajax/nonAjaxRedirect', 'ajax');
					return;
				} elseif ($passwordValidDays <= 7) {
					$msg = ($passwordValidDays === 0)? "You password expires today!" : "Your password will expire in $passwordValidDays day" . (($passwordValidDays > 1)? 's.' : '.');
					$msg .= " Please renew your password from the login page.";
					$this->Session->setFlash(__($msg), 'default', ['class' => 'alert-danger strong text-center']);
				}
				try {
					$result = $this->User->authenticateOktaUser($user['id']);
				} catch (Exception $e) {
					$this->Session->setFlash(__($e->getMessage()), 'default', ['class' => 'alert alert-warning strong']);
					$redirectUrl = Router::url(array('controller' => 'Users', 'action' => 'login'));
					$this->set('redirectUrl', $redirectUrl);
					$this->render('/Elements/Ajax/nonAjaxRedirect', 'ajax');
					return;
				}
				//user is not MFA enrolled but since at this point credentials are valid proceed to log user in
				if ($result === false) {
					$this->Auth->login($user);
					$this->Session->write('Auth.User.group', $this->User->Group->field('name', array('id' => $this->Auth->user('group_id'))));
					$this->Session->write('Auth.User.Okta.needs_mfa_enrollment', true);
					if ($this->Auth->user('group_id') === User::API_GROUP_ID) {
						$redirectUrl = Router::url(['controller' => 'admin', 'action' => 'index']);
					} else {
						$redirectUrl = $this->Auth->redirect();
					}
					$this->set('redirectUrl', $redirectUrl);
					$this->render('/Elements/Ajax/nonAjaxRedirect', 'ajax');
				} elseif($result['status'] === Okta::MFA_REQ) {
					foreach ($result['_embedded']['factors'] as $factor) {

						if ($factor['factorType'] === 'token:software:totp') {
							$totpFactorId = $factor['id'];
						}
						if ($factor['factorType'] === 'push') {
							$pushFactorId = $factor['id'];
							$deviceName = $factor['profile']['name'];
						}
					}
					$oktaMfaMeta = [
						'User' => ['id' => $user['id']],
						'stateToken' => $result['stateToken'],
						'factors' => ['pushFactorId' => $pushFactorId, 'totpFactorId' => $totpFactorId, 'deviceName' => $deviceName]
					];
					$this->set('oktaMfaMeta', $oktaMfaMeta);
					$this->render('/Elements/Ajax/oktaVerifyPushMfa', 'ajax');
				}
				
			} else {
				$redirectUrl = Router::url(array('controller' => 'Users', 'action' => 'login'));
				$this->set('redirectUrl', $redirectUrl);
				$this->_failure(__('Invalid e-mail/password.'));
				$this->render('/Elements/Ajax/nonAjaxRedirect', 'ajax');
			}
		}
	}
/**
 * admin_edit method
 *
 * @return void
 */
	public function admin_okta_mfa_enroll($pollActivation = false) {
		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$Okta = new Okta();
			try {
				if ($pollActivation) {
					$result = $Okta->pollPushFactorActivation($this->request->data('poll_activation_url'));
					if (Hash::get($result, 'status') === 'ACTIVE') {
						$this->Session->write('Auth.User.Okta.mfa_enrolled', true);
					}
					return json_encode($result);
				}
				$user = $Okta->findUser($this->Session->read('Auth.User.email'));
				//If user not found we need create the user in Okta
				if (empty($user)) {
					$user['User'] = $this->Session->read('Auth.User');
					$user['User']['password'] = $this->User->field('password', ['User.id' => $this->Session->read('Auth.User.id')]);
					$user = $Okta->createUser($user);
				} 
				//Verify user is not already enrolled in MFA by checking if a resetFactors link is present in the data
				//which implies the user is already enrolled
				if (empty(Hash::get($user, '_links.resetFactors.href'))) {
					$response = $Okta->enrollPushFactor($user['id']);
					$data['qrcode'] = $response['_embedded']['activation']['_links']['qrcode']['href'];
					$data['pollActivationUrl'] = $response['_links']['poll']['href'];
					return json_encode($data);
				} else {
					return json_encode(['error' => 'Already enrolled in Okta multifactor authentication, no action needed at this time.']);
				}
			} catch (Exception $e){
				return json_encode(['error' => $e->getMessage()]);
			}
		}
	}

/**
 * reset_okta_mfa
 *
 * @param string $userId a User->id
 * @return null
 */
	public function reset_okta_mfa($userId) {
		$Okta = new Okta();
		$msg = 'Okta MFA has been reset.';
		//Diferent redirect if user is already logged in
		if ($this->Session->check('Auth.User.id')) {
			$redirectUrl = $this->referer();
		} else {
			$msg .= ' Enter your login info to sign in.';
			$redirectUrl = array('controller' => 'Users', 'action' => 'login');
		}
		try {
			if ($Okta->resetFactors($this->User->field('email', ['User.id' => $userId]))) {
				$this->Session->delete('Auth.User.Okta');
				$this->Session->setFlash(__($msg), 'default', ['class' => 'alert alert-info']);
				$this->redirect($redirectUrl);
			}
		} catch (Exception $e) {
			$this->_failure(__('Unexpected API error occurred, please try again in a minute.'), $redirectUrl);
		}
	}
/**
 * verifyOktaPushFactor
 *
 * @param string $userId a User->id
 * @param string $stateToken Okta State token
 * @param string $factorId Okta factor id corresponding to push factor
 * @return JSON string
 */
	public function verify_okta_mfa($userId, $stateToken, $factorId) {
		$this->autoRender = false;
		$Okta = new Okta();
		try {
			$reqData = ["stateToken" => $stateToken];
			if (!empty($this->request->data('User.okta_totp'))) {
				$reqData['passCode'] = $this->request->data('User.okta_totp');
			}

			$response = $Okta->verifyOktaMfa($reqData, $factorId);
			if (Hash::get($response,'status') == 'SUCCESS') {
				$user = $this->User->find('first', [
					'recursive' => 0,
					'conditions' => ['User.id' => $userId]
				]);
				//Set data structure needed for Auth->login;
				$user['User']['Group'] = $user['Group'];
				$user['User']['Template'] = $user['Template'];
				$user = $user['User'];
				$this->Auth->login($user);
				$this->Session->write('Auth.User.Okta.mfa_enrolled', true);
				$this->Session->write('Auth.User.group', $this->User->Group->field('name', array('id' => $this->Auth->user('group_id'))));
				if ($this->Auth->user('group_id') === User::API_GROUP_ID) {
					$redirectUrl = Router::url(['controller' => 'admin', 'action' => 'index']);
				} else {
					$redirectUrl = $this->Auth->redirect();
				}
				return json_encode(['okta_verify_status' => Hash::get($response,'status'), 'redirect_url' => $redirectUrl]);
			} elseif(Hash::get($response, 'factorResult') == 'WAITING') {
				return json_encode(['okta_verify_status' => 'WAITING']);
			} elseif(!empty($response['errorCode'])) {
				return json_encode(['okta_verify_status' => 'ERROR', 'error_msg' => Hash::get($response, 'errorCauses.0.errorSummary')]);
			}
		} catch(Exception $e) {
			$redirectUrl = Router::url(array('controller' => 'Users', 'action' => 'login'));
			$this->_failure(__($e->getMessage()));
			return json_encode(['okta_verify_status' => 'ERROR', 'redirect_url' => $redirectUrl]);
		}
	}


/**
 * request_pw_reset
 * Handles requests to reset the user's password.
 * Generates a unique psudo-random md5 hash for one-time use as URI parameter to reset the user password.
 * This md5 hash is saved in the user's record and deleted after the password is reset, efectively expiring the URL that was sent to the user to reset the password.
 * 
 * @param boolean $hasExpired whether the request to reset password is because it hasExpired
 * @param string $id a user id
 * @param boolean $setRandPw whether to assign a randomized password to the user
 * @return void
 */
	public function request_pw_reset($hasExpired = false, $id = null, $setRandPw = false) {
		if ($this->request->is('post')) {
			if (!empty($id)) {
				$conditions = ['id' => $id];
			} else {
				$conditions = ['email' => $this->request->data('User.email')];
			}
			$user = $this->User->find('first', [
				'recursive' => -1,
				'conditions' => $conditions,
				'fields' => ['id', 'email']
			]);

			if (!empty($user['User']['id']) && !empty($user['User']['email'])) {
				$data['pw_reset_hash'] = $pwResetHash = $this->User->getRandHash();
				if ($setRandPw) {
					$data['pwd'] = $this->User->generateRandPw();
					//Set expriation date to sometime in the past to prevent user from logging in without updating the temporary password
					$data['pw_expiry_date'] = '1999-01-01';
				}
				$this->User->id = $user['User']['id'];
				if (!$this->User->save($data, ['validate' => false])) {
					$this->_failure(__('Error: Failed to persist temporary password reset hash parameter. Try again later.'));
				} else {
					$tmpPwMsg = '';
					if ($setRandPw) {
						$tmpPwMsg = "and the following temporary password has been assigned to your account: {$data['pwd']}\n";
					}
					$msg = "A request to reset/renew your password has been submitted\n";
					$msg .= $tmpPwMsg;
					$msg .= "Follow the link below to change your password:\n";
					$msg .= Router::url(['controller' => 'Users', 'action' => 'change_pw', (int)$hasExpired, $pwResetHash], true) . "\n";
					$args['viewVars'] = ['content' => $msg];
					$args = $this->User->getEmailArgs($user['User']['id'], $args);
					$this->User->sendEmail($args);
				}
				$redirectTo = ['action' => 'login'];
				if ($this->Session->check('Auth.User.id')) {
					if ($this->Session->read('Auth.User.id') == $id) {
						$this->Session->destroy();
					} else {
						$redirectTo = $this->referer();
					}
				}
				$this->_success(__('A password reset link has been emailed to the user.'), $redirectTo, 'alert alert-success');
				if ($setRandPw) {
					$this->redirect($this->referer());
				} else {
					$this->redirect($redirectTo);
				}
			} else {
				$this->_failure(__('That is not a valid registered email'));
			}
		}
	}

/**
 * Users change_pw method implements first time login functionality
 *
 * @param boolean $renew whether this request is to renew password
 * @param string $pwResetHash the md5 hash for one-time use as URI parameter to reset the user password 
 * @return void
 */
	public function change_pw($renew, $pwResetHash) {
		$this->User->setPwFieldsValidation($renew);
		$id = $this->User->field('id', ['pw_reset_hash' => $pwResetHash]);

		if (empty($id) || !isset($renew) || !isset($pwResetHash)) {
			$this->set('name', 'ERROR 404: Page Not Found Or Has Expired.');
			$this->set('url', Router::url(['controller' => 'Users', 'action' => 'change_pw', $renew, $pwResetHash], true));
			$this->render('/Errors/error404');
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($renew) {
				$newPW = $this->request->data['User']['pwd'];
				$currentPW = $this->request->data['User']['cur_password'];
				if ($this->User->pwIsValid($this->request->data['User']['id'], $currentPW) === false) {
					$this->_failure(__('Current password is invalid.'));
					$this->redirect(['action' => 'change_pw', $renew, $pwResetHash]);
				}
				if ($newPW === $currentPW) {
					$this->_failure(__('New password must be different from current.'));
					$this->redirect(['action' => 'change_pw', $renew, $pwResetHash]);
				}
			}
			//save pw_reset_hash as null
			$this->request->data['User']['pw_reset_hash'] = null;
			if ($this->User->save($this->request->data['User'])) {
				$this->_success(__('Password updated!'), ['action' => 'login'], 'alert-success');
			} else {
				$this->_failure(__('Failed to update password.'));
			}

		}
		$this->request->data['User']['id'] = $id;
		$this->set('id', $id);
		$this->set('renewingPw', $renew);
	}

/**
 * Allow users to logout
 *
 * @return null
 */

	public function logout() {
		$this->_success('Good-Bye');
		$this->redirect($this->Auth->logout());
	}

/**
 * Create Index for Managing Users
 *
 * @return null
 */

	public function admin_index() {
		$queryString = (isset($this->request->query['all']) ? $this->request->query['all'] : null);
		if ($queryString == '1') {
			$conditions = array();
		} else {
			$conditions = array('User.active' => 'true');
		}

		$this->Prg->commonProcess();

		$this->paginate = array(
			'contain' => array('Group'),
			'limit' => 25,
			'order' => array(
				'User.firstname' => 'ASC',
				'User.lastname' => 'ASC'
			),
			'conditions' => $conditions,
		);
		$params = $this->Prg->parsedParams();
		if (!empty($params)) {
			$this->Paginator->settings['conditions'] = $this->User->parseCriteria($this->Prg->parsedParams());
		}

		$users = $this->paginate();
		$this->_setViewNavData($queryString);
		$this->set(compact('users'));
	}

/**
 * Provides functionality to add users
 *
 * @return null
 */

	public function admin_add() {
		$this->_setViewNavData('');
		if ($this->request->is('post')) {
			$this->request->data['User']['pw_reset_hash'] = $this->User->getRandHash();
			$this->request->data['User']['pwd'] = $this->request->data['User']['password_confirm'] = $this->User->generateRandPw();
			//Set expriation date to sometime in the past to prevent user from logging in without updating the temporary password
			$this->request->data['User']['pw_expiry_date'] = '1999-01-01';
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$notificationStr = '';
				if (!empty($this->request->data('User.email'))) {
					$args = $this->_getNewUserEmailParams($this->request->data);
					$this->User->sendEmail($args);
					$notificationStr = 'User has been notified of this via email.';
				}
				$this->_success(__("User has been created and assigned a random password. $notificationStr"));
				$this->redirect(array('action' => 'index', 'admin' => true));
			} else {
				unset($this->request->data['User']['pwd']);
				unset($this->request->data['User']['password_confirm']);
				$this->_failure(__('The User Could not Be saved'));
			}
		}
		$this->set('groups', $this->User->Group->find('list'));
		$this->set('allManagers', $this->User->getAllManagers(User::MANAGER_GROUP_ID));
		$this->set('allCobrands', ClassRegistry::init('Cobrand')->getList());
		$this->set('allTemplates', $this->User->getJsonCobrandsTemplates());
		$this->_persistMultiselectData($this->request->data);
	}

/**
 * _getNewUserEmailParams
 *
 * @return array
 */
	protected function _getNewUserEmailParams($newUserData) {
		$msg = "Hello " . $newUserData['User']['firstname'] . "\n";
		$msg .= "Your user acount has been created for the Axia Online App company site\n";
		$msg .= "and the following temporary password has been assigned to your account: {$newUserData['User']['pwd']}\n";
		$msg .= "Before you can access the site please follow the link below to change this temporary password:\n";
		$msg .= Router::url(['controller' => 'Users', 'action' => 'change_pw', 'admin' => false, 1, $newUserData['User']['pw_reset_hash']], true) . "\n";
		return [
			'from' => ['newapps@axiapayments.com' => 'Axia Online Applications'],
			'to' => $newUserData['User']['email'],
			'subject' => 'Axia Online App Account Password Reset',
			'format' => 'html',
			'template' => 'default',
			'viewVars' => ['content' => $msg],
		];
	}
/**
 * Provides Bulk Edit functionality
 *
 * @return null
 */

	public function admin_bulk_edit() {
		if (empty($this->request->data)) {
			$this->paginate = array(
				'limit' => 150,
				'contain' => array(
					'Group',
					'Template' => array('fields' => array('id')),
					'Cobrand' => array('fields' => array('id')),
				),
				'recursive' => -1,
				'order' => array('User.firstname' => 'ASC'),
			);

			$users = $this->paginate('User');
			$cobrands = $this->User->Cobrand->getList();
			$templates = $this->User->Template->getList();
			$groups = $this->User->Group->find('list');
			$this->set(compact('cobrands', 'users', 'groups', 'templates'));
		} else {
			$relatedData = Hash::extract($this->request->data, 'User');
			$userData = Hash::remove($this->request->data, 'User');
			$mergeData = Hash::merge($userData, $relatedData);
			$changedUsers = $this->User->arrayDiff($mergeData);
			if ($this->User->saveAll($changedUsers, array('deep' => true))) {
				$this->_success("Users Saved!");
				$this->redirect('/admin/users');
			}
		}
	}

/**
 * Provides functionality for editing users
 *
 * @param integer $id the user id to be edited
 * @return null
 */

	public function admin_edit($id) {
		$this->User->id = $id;
		$Okta = new Okta();
		if ($this->request->is('post') || $this->request->is('put')) {
			if (empty($this->request->data['User']['pwd']) && empty($this->request->data['User']['password_confirm'])) {
				unset($this->request->data['User']['pwd']);
				unset($this->request->data['User']['password_confirm']);
			}
			if ($this->User->saveAll($this->request->data)) {
				$this->Session->write('Auth.User.email', $this->request->data['User']['email']);
				if (array_key_exists('api_enabled', $this->request->data['User'])) {
					$this->Session->write('Auth.User.api_enabled', (bool)$this->request->data('User.api_enabled'));
				}

				try {
					$activeOktaUser = $this->request->data('User.has_okta_user_account');
					$oktaUserEmail = $this->request->data('User.okta_user_current_email');
					$oktaUpdateMsg = '';
					//If user is deactivated localy, also deactivate from Okta
					if ($activeOktaUser && $this->request->data('User.active') == false) {
						$Okta->deactivateUser($oktaUserEmail);
						$activeOktaUser = false;
						$oktaUpdateMsg = "Also, this user's Okta account has been deactivated.";
					}
					//Check if user email changed. okta_user_current_email is a hidden field with its value set programmatically
					//to track email changes and update the correspondint okta user with this change
					if ($activeOktaUser && !empty($OktaUserEmail) && $this->request->data('User.email') !== $this->request->data('User.okta_user_current_email')) {
						$Okta->updateLoginEmail($this->request->data('User.okta_user_current_email'), $this->request->data('User.email'));
						$oktaUpdateMsg = 'Okta user account successfully updated';
					}
					$this->_success(__("User Saved! " . $oktaUpdateMsg));
				} catch (Exception $e) {
					$this->_failure(__("Warning! User updated successfully, but updates failed to sync with Okta. Please report this to administrator as this user's Okta data is now out of sync. Okta error: " . $e->getMessage()));
				}
				$this->redirect('/admin/users');
			} else {
				$this->_failure(__("Could not save User! Check for any form validation errors and try again..."));
			}
		} else {
			$this->request->data = $this->User->getEditViewData($id);
		}

		$oktaUser = $Okta->findUser($this->request->data('User.email'));
		$this->request->data['User']['has_okta_user_account'] = !empty(Hash::get($oktaUser, 'id'));

		$oktaMfaEnrolled = !empty(Hash::get($oktaUser, '_links.resetFactors.href'));
		$this->Session->write('Auth.User.Okta.mfa_enrolled', $oktaMfaEnrolled);
		
		$this->set('oktaMfaEnrolled', $oktaMfaEnrolled);
		$this->set('groups', $this->User->Group->find('list'));
		$this->set('allManagers', $this->User->getAllManagers(User::MANAGER_GROUP_ID));
		$this->set('allRepresentatives', $this->User->getActiveUserList());
		$this->set('allCobrands', ClassRegistry::init('Cobrand')->getList());
		$this->_setViewNavData('');
		$this->set('allTemplates', $this->User->getJsonCobrandsTemplates());
		$this->set('userDefaultTemplates', $this->User->getTemplates($id));
		$this->_persistMultiselectData($this->request->data);
	}

/**
 * _persistMultiselectData
 * Utility function to persist multiselect options data submitted.
 *
 * @param array $data request data submitted or queried.
 * @return null
 */
	protected function _persistMultiselectData($data) {
		$modelsFields = array(
			'AssignedRepresentative' => array('fullname'),
			'Manager' => array('fullname'),
			'Cobrand' => array('partner_name'),
			'Template' => array('name'),
		);
		foreach ($modelsFields as $model => $fields) {
			$varName = Inflector::variable(Inflector::tableize($model));
			$modelIds = Hash::extract($data, "$model.{n}.id");
			//If the data structure is from a form submission, we must persist request data.
			if (array_key_exists($model, Hash::extract($data, $model)) && !empty($data[$model][$model])) {
				if ($model === 'Template') {
					$data[$model][$model] = $this->User->getCombinedCobrandTemplateList($data[$model][$model]);
				} else {
					$data[$model][$model] = $this->User->{$model}->find('list', array(
								'conditions' => array('id' => $data[$model][$model]),
								'fields' => array('id', $fields[0])
							)
						);
				}

				$this->set($varName, $data[$model][$model]);
			} elseif (!empty($modelIds)) {
				if ($model === 'Template') {
					$combinedData = $this->User->getCombinedCobrandTemplateList($modelIds);
					$this->set($varName, $combinedData);
				} else {
					$keys = $modelIds;
					$vals = Hash::extract($data, "$model.{n}." . $fields[0]);
					$this->set($varName, array_combine($keys, $vals));
				}
			}
		}
	}

/**
 * There is no action for /admin/users/login
 * In the event that someone tries to go there
 * redirect they to the regular login page
 *
 * @return null
 */

	public function admin_login() {
		$this->redirect('/users/login');
	}

/**
 * There is no action for /admin/users/logout
 * In the event that someone tries to go there
 * redirect they to the regular login page
 *
 * @return null
 */

	public function admin_logout() {
		$this->redirect('/users/logout');
	}

/**
 * Function used by AJAX calls to get data about user templates
 *
 * @param integer $id the user id belonging to the templates
 * @return null
 */

	public function get_user_templates($id) {
		$this->autoRender = false;

		$userTemplates = $this->User->getTemplates($id);

		if (!empty($userTemplates) && is_array($userTemplates)) {
			foreach ($userTemplates as $key => $val) {
				echo '<option value="' . $key . '">' . $val . '</option>';
			}
		} else {
			echo '<option value="">NO TEMPLATES FOR USER</option>';
		}
	}

/**
 * _setViewNavContent
 * Utility method sets an array of urls to use as left navigation items on views
 *
 * @param string $showActive string representation of boolean value
 * @return array
 */
	protected function _setViewNavData($showActive) {
		if ($showActive == '1') {
			$labelActiveInactive = 'Show Active Users';
			$userIndexUrl = Router::url(array('action' => 'index', 'admin' => true));
		} else {
			$labelActiveInactive = 'Show All Users';
			$userIndexUrl = Router::url(array('action' => '?all=1', 'admin' => true));
		}

		$elVars = array(
			'navLinks' => array(
				'New User' => Router::url(array('action' => 'add', 'admin' => true)),
				$labelActiveInactive => $userIndexUrl,
				'List Settings' => Router::url(array('controller' => 'settings', 'action' => 'index', 'admin' => true)),
				'List IP Restrictions' => Router::url(array('controller' => 'apips', 'action' => 'index', 'admin' => true)),
				'List Groups' => Router::url(array('controller' => 'groups', 'action' => 'index', 'admin' => true)),
			)
		);
		$this->set(compact('elVars'));
	}

}
// Last Line
