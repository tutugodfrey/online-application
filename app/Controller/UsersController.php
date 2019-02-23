<?php
App::uses('User', 'Model');
class UsersController extends AppController {

	public $permissions = array(
		'login' => '*',
		'logout' => '*',
		'request_pw_reset' => '*',
		'change_pw' => '*',
		'get_user_templates' => '*',
		'reset_api_info' => array(User::ADMIN, User::REP, User::MANAGER, User::API)
	);

	public $components = array('Search.Prg');
/**
 * Logic to be applied before page load
 *
 * @return null
 */

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('login', 'logout', 'request_pw_reset', 'change_pw'));
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
			if ($this->Auth->login()) {
				$userId = $this->Auth->user('id');
				$passwordValidDays = $this->User->getDaysTillPwExpires($userId);
				if ($passwordValidDays < 0) {
					$this->Session->destroy();
					$this->Session->setFlash(__('Password expired! Please renew your password.'), 'default', ['class' => 'alert alert-warning strong']);
					$this->redirect(array('action' => 'login'));
				} elseif ($passwordValidDays <= 7) {
					$msg = ($passwordValidDays === 0)? "You password expires today!" : "Your password will expire in $passwordValidDays day" . (($passwordValidDays > 1)? 's.' : '.');
					$msg .= " Please renew your password from the login page.";
					$this->Session->setFlash(__($msg), 'default', ['class' => 'alert-danger strong text-center']);
				}
				$this->Session->write('Auth.User.group', $this->User->Group->field('name', array('id' => $this->Auth->user('group_id'))));
				if ($this->Auth->user('group_id') === User::API_GROUP_ID){
					$this->redirect(['controller' => 'admin', 'action' => 'index']);
				} else {
					$this->redirect($this->Auth->redirect());
				}
			} else {
				$this->_failure(__('Invalid e-mail/password.'));
			}
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
		if ($this->request->is('post') || $this->request->is('put')) {
			if (empty($this->request->data['User']['pwd']) && empty($this->request->data['User']['password_confirm'])) {
				unset($this->request->data['User']['pwd']);
				unset($this->request->data['User']['password_confirm']);
			}
			if ($this->User->saveAll($this->request->data)) {
				if (array_key_exists('api_enabled', $this->request->data['User'])) {
					$this->Session->write('Auth.User.api_enabled', (bool)$this->request->data('User.api_enabled'));
				}
				$this->_success(__("User Saved!"));
				$this->redirect('/admin/users');
			} else {
				$this->_failure(__("Could not save User! Check for any form validation errors and try again..."));
			}
		} else {
			$this->request->data = $this->User->getEditViewData($id);
		}

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
