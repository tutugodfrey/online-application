<?php
App::uses('Sanitize', 'Utility');
class UsersController extends AppController {

	public $scaffold = 'admin';

	public $permissions = array(
		'login' => '*',
		'logout' => '*'
	);

	public $components = array('Search.Prg');
	function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('login', 'logout'));
		if ($this->request->action != 'login' && $this->request->action != 'logout' && $this->request->action != 'admin_login' && $this->request->action != 'admin_logout') {
			if (!$this->Auth->user('group_id') || $this->User->Group->field('name', array('id' => $this->Auth->user('group_id'))) != 'admin') {
				header("HTTP/1.0 403 Forbidden");
				exit;
			}
		}
	}

	public function admin_token($id) {
		$this->User->id = $id;
		$conditions = array('conditions' => array('User.id' => $id), 'recursive' => -1);
		$data = $this->User->find('first', $conditions);
		if($data['User']['api_enabled'] === true) {
			$this->Session->setFlash('This User already has Valid API Credentials!');
			$this->redirect('/admin/users');
		} else {
		$token = sha1(String::uuid());
		$password = substr(sha1(String::uuid()),5,14);

		$this->User->set(array('token' => $token, 'api_password' => $password, 'api_enabled' => true, 'api' => true));
		if (!$this->User->save()) {
			$token = null;
			$this->Session->setFlash('There was an error generating this token');
		}
		$this->Session->setFlash('API access has been enabled for this user');
		$this->set(compact('token', 'password','id'));
		}
	}

	function login() {

		if($this->request->is('post')){
			if($this->Auth->login()) {
				$this->Session->write('Auth.User.group', $this->User->Group->field('name', array('id' => $this->Auth->user('group_id'))));
				$this->redirect($this->Auth->redirect());   
			} else {
				$this->Session->setFlash(__('Invalid e-mail / password combination.  Please try again.'));
			}
		}
	}

	function logout() {
		$this->Session->setFlash('Good-Bye');
		$this->redirect($this->Auth->logout());
	}
	
	function admin_all() {
		$this->paginate = array(
			'limit' => 100,
			'order' => array('User.active' => 'ASC'),
		);

		$data = $this->paginate('User');
		$this->set('users', $data);
		$this->set('scaffoldFields', array_keys($this->User->schema()));
		$this->render('admin_index');
		
	}

	function admin_index($all = null) {
		$this->paginate = array(
			'limit' => 25,
			'order' => array('User.firstname' => 'ASC'),
			'conditions' => array('User.active' => 't'),
			'recursive' => 0
		);
		$data = $this->paginate('User');
		$this->set('users', $data);
		$this->set('scaffoldFields', array_keys($this->User->schema()));
		
	}

	function admin_add() {
		$this->set('groups', $this->User->Group->find('list'));
		$this->set('managers', $this->User->getAllManagers(User::MANAGER_GROUP_ID));

		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save(Sanitize::clean($this->request->data))) {
				$this->Session->setFlash(__('The User has been created'));
				$this->redirect(array('action'=> 'index', 'admin' => true));
			} else {
				unset($this->request->data['User']['pwd']);
				unset($this->request->data['User']['password_confirm']);
				$this->Session->setFlash(__('The User Could not Be saved'));
			}
		}
		$this->set(compact('users'));
	}

	function admin_bulk_edit() {
		if (empty($this->request->data)) {
			$this->paginate = array(
				'limit' => 100,
				'order' => array('User.firstname' => 'ASC'),
			);

			$users = $this->paginate('User');
			$groups = $this->User->Group->find('list');
			$this->set(compact('users','groups'));

			//unset($this->request->data['User']['password']);
			} else {
			$this->User->arrayDiff($this->request->data);

			if ($this->User->saveAll(Sanitize::clean($this->User->arrayDiff($this->request->data)))){
				$this->Session->setFlash("Users Saved!");
				$this->redirect('/admin/users');
			}
		}
	}

	function admin_edit($id) {
		$this->User->id = $id;
		$this->User->read();
		$this->set('groups', $this->User->Group->find('list'));
		$this->set('managers', $this->User->getAllManagers(User::MANAGER_GROUP_ID));
		$this->set('assigned_managers', $this->User->getAssignedManagerIds($id));
		$this->set('assignedRepresentatives', $this->User->getActiveUserList());
		$this->set('cobrands', $this->User->Cobrand->getList());
		$user = $this->User->read();

		$cobrandIds = $this->User->getCobrandIds($id);
		$templates = $this->User->Template->getList($cobrandIds);
		$this->set('templates', $templates);

		// TODO: add templates
		if (empty($this->request->data)){
			$this->request->data = $this->User->read();
		} else {
			if(empty($this->request->data['User']['pwd']) && empty($this->request->data['User']['password_confirm'])) {
				unset($this->request->data['User']['pwd']);
				unset($this->request->data['User']['password_confirm']);
			}
			if ($this->User->saveAll(Sanitize::clean($this->request->data))) {
				$this->Session->setFlash("User Saved!");
				$this->redirect('/admin/users');
			}
		}
	}

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

	function admin_login() {
		$this->redirect('/users/login');
	}

	function admin_logout() {
		$this->redirect('/users/logout');
	}
}
?>