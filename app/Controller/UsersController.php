<?php
class UsersController extends AppController {

	public $scaffold = 'admin';

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
			$token = sha1(String::uuid());
			$password = substr(sha1(String::uuid()), 5, 14);

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
 * Provide Paginated results for the admin index
 *
 * @todo this function should be removed
 * @return null
 */

	public function admin_all() {
		$this->paginate = array(
			'limit' => 100,
			'order' => array('User.active' => 'ASC'),
		);

		$data = $this->paginate('User');
		$this->set('users', $data);
		$this->set('scaffoldFields', array_keys($this->User->schema()));
		$this->render('admin_index');
	}

/**
 * Create Index for Managing Users
 *
 * @param integer $all What does this do?
 * @todo this function should be refactored
 * @return null
 */

	public function admin_index($all = null) {
		$this->paginate = array(
			'limit' => 25,
			'order' => array('User.firstname' => 'ASC'),
			'conditions' => array('User.active' => 't'),
			'recursive' => 0
		);
		$groups = $this->User->Group->find('list');
		$templates = $this->User->Template->getList();
		$users = $this->paginate('User');
		$this->set(compact('groups', 'templates', 'users'));
		$this->set('scaffoldFields', array_keys($this->User->schema()));
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

		$this->set('userTemplates', $userTemplates);
		$this->set('defaultTemplateId', $user['User']['template_id']);

		// TODO: add templates
		if (empty($this->request->data)) {
			$this->request->data = $this->User->read();
		} else {
			if (empty($this->request->data['User']['pwd']) && empty($this->request->data['User']['password_confirm'])) {
				unset($this->request->data['User']['pwd']);
				unset($this->request->data['User']['password_confirm']);
			}
			if ($this->User->saveAll($this->request->data)) {
				$this->Session->setFlash("User Saved!");
				$this->redirect('/admin/users');
			}
		}
	}

/**
 * Search functionality for the Users Index
 *
 * @todo this can be refactored along with the users index
 * @return null
 */

	public function admin_search() {
		$this->Prg->commonProcess();
		$criteria = trim($this->passedArgs['search']);
		$criteria = '%' . $criteria . '%';
		$conditions = array(
			'OR' => array(
				'User.firstname ILIKE' => $criteria,
				'User.lastname ILIKE' => $criteria,
				'User.fullname ILIKE' => $criteria,
				'User.email ILIKE' => $criteria,
				'CAST(User.extension AS TEXT) ILIKE' => $criteria,
				'CAST(User.id AS TEXT) ILIKE' => $criteria,
			),
		);
		$this->paginate = array(
			'limit' => 100,
			'order' => array('User.firstname' => 'ASC')
		);
		$users = $this->paginate('User', $conditions);
		$this->set(compact('users'));
		$this->set('scaffoldFields', array_keys($this->User->schema()));
		$this->set('criteria', $this->passedArgs['search']); //I do it this way because I dont want to include the % chars
		$this->render('admin_index');
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
}
// Last Line
