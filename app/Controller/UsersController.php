<?php
class UsersController extends AppController {

	public $permissions = array(
		'login' => '*',
		'logout' => '*',
		'get_user_templates' => '*'
	);

	public $components = array('Search.Prg');
/**
 * Logic to be applied before page load
 *
 * @return null
 */

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('login', 'logout'));
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
			$this->Session->setFlash('This User already has Valid API Credentials!');
			$this->redirect('/admin/users');
		} else {
			$token = sha1(CakeText::uuid());
			$password = substr(sha1(CakeText::uuid()), 5, 14);

			$this->User->set(array('token' => $token, 'api_password' => $password, 'api_enabled' => true, 'api' => true));
			if (!$this->User->save()) {
				$token = null;
				$this->Session->setFlash('There was an error generating this token');
			}
			$this->Session->setFlash('API access has been enabled for this user');
			$this->set(compact('token', 'password', 'id'));
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
				$this->Session->write('Auth.User.group', $this->User->Group->field('name', array('id' => $this->Auth->user('group_id'))));
				$this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Invalid e-mail / password combination.  Please try again.'));
			}
		}
	}

/**
 * Allow users to logout
 *
 * @return null
 */

	public function logout() {
		$this->Session->setFlash('Good-Bye');
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
		//		$groups = $this->User->Group->find('list');
		//		$templates = $this->User->Template->getList();
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
		$this->Cobrand = ClassRegistry::init('Cobrand');

		$this->set('groups', $this->User->Group->find('list'));
		$this->set('managers', $this->User->getAllManagers(User::MANAGER_GROUP_ID));
		$this->set('cobrands', $this->Cobrand->getList());
		$this->set('templates', $this->User->Template->getList());
		$this->_setViewNavData('');
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The User has been created'));
				$this->redirect(array('action' => 'index', 'admin' => true));
			} else {
				unset($this->request->data['User']['pwd']);
				unset($this->request->data['User']['password_confirm']);
				$this->Session->setFlash(__('The User Could not Be saved'));
			}
		}
		
		$this->set(compact('users'));
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
				$this->Session->setFlash("Users Saved!");
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
		$this->Cobrand = ClassRegistry::init('Cobrand');

		$this->User->id = $id;
		$this->User->read();
		$this->set('groups', $this->User->Group->find('list'));
		$this->set('managers', $this->User->getAllManagers(User::MANAGER_GROUP_ID));
		$this->set('assigned_managers', $this->User->getAssignedManagerIds($id));
		$this->set('assignedRepresentatives', $this->User->getActiveUserList());

		$user = $this->User->read();

		$this->set('cobrands', $this->Cobrand->getList());
		$this->set('templates', $this->User->Template->getList());

		$userTemplates = $this->User->getTemplates($id);
		$this->_setViewNavData('');
		$this->set('userTemplates', $userTemplates);
		$this->set('defaultTemplateId', $user['User']['template_id']);

		// TODO: Replace $this->User->read() with an action query
		if (empty($this->request->data)) {
			$this->request->data = $this->User->read();
		} else {
			if (empty($this->request->data['User']['pwd']) && empty($this->request->data['User']['password_confirm'])) {
				unset($this->request->data['User']['pwd']);
				unset($this->request->data['User']['password_confirm']);
			}
			if ($this->User->saveAll($this->request->data)) {
				$this->Session->setFlash(__("User Saved!"), 'default', array('class' => 'alert alert-success'));
				$this->redirect('/admin/users');
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
				'Bulk Edit Users' => Router::url(array('action' => 'bulk_edit', 'admin' => true)),
				'List Settings' => Router::url(array('controller' => 'settings', 'action' => 'index', 'admin' => true)),
				'List IP Restrictions' => Router::url(array('controller' => 'apips', 'action' => 'index', 'admin' => true)),
				'List Groups' => Router::url(array('controller' => 'groups', 'action' => 'index', 'admin' => true)),
			)
		);
		$this->set(compact('elVars'));
	}

}
// Last Line
