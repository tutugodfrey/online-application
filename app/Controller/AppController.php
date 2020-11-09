<?php
class AppController extends Controller {
	public $components = array(
		'Auth' => array(
			'authorize' => 'Controller',
			'allowedActions' => array('display'),
			  'authenticate' => array(
				'Form' => array(
					'fields' => array('username' => 'email'),
					'scope' => array('User.active' => 1))),
			'loginAction' => array('controller' => 'users', 'action' => 'login'),
			'loginRedirect' => array('controller' => 'cobranded_applications', 'action' => 'index', 'admin' => true),
			'logoutRedirect' => array('controller' => 'users', 'action' => 'login')
		),
		'Paginator',
		'Search.Prg',
		'Session',
		'DebugKit.Toolbar',
		'RequestHandler'
	);

	public $actsAs = array('Containable');
	
	public $helpers = array(
		'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
		'Form' => array('className' => 'BoostCake.BoostCakeForm'),
		'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
		'Session',
	);

/**
 * beforeRender callback
 *
 * @return void
 */
	public function beforeRender() {
		//Deny rendering site in anything other than the parent browser window
	     $this->response->header('X-Frame-Options', 'DENY');
	     parent::beforeFilter();
	}

	public function beforeFilter() {
		// Force SSL
		$excludeSSL = array("document_callback");
		if (Configure::read('debug') === 0 && !$this->RequestHandler->isSSL() && !in_array($this->request->action, $excludeSSL)) {
				$this->redirect('https://' . env('HTTP_HOST') . $this->request->here);
		}

		if ($this->requestIsApiJson()) {

			$this->Auth->authenticate = array(
				'Basic' => array(
					'realm' => 'api',
					'passwordHasher' => 'Blowfish',
					'fields' => array('username' => 'token','password' => 'api_password'),
					'scope' => array('User.active' => 1)
				)
			);
			$this->Auth->authorize = array('Controller');
			$this->Auth->unauthorizedRedirect = false;
			AuthComponent::$sessionKey = false;

		}

	}

 	public function requestIsApiJson() {
 		return $this->params['prefix'] == 'api' || $this->params['ext'] == 'json' || $this->request->accepts('application/json');
 	}

	public function isAuthorized() {
		// Load the User model to retrieve group info
		$this->loadModel('User');
		if ($this->requestIsApiJson()){
			$this->apiLog();
			$conditions = array('Apip.ip_address >>=' => $this->request->clientIP(), 'Apip.user_id' => $this->Auth->user('id'));
			if ($this->User->Apip->find('first', array('conditions' => $conditions))) {
				return true;
			}
		}

		// Look up user & group info (from auth params...should be safe!)
		$user = $this->User->find('first', array(
			'conditions' => array(
				'User.email' => $this->Auth->user('email')
			),
			'contain' => array(
				'Group'
			)
		));
		$group = $user['Group']['name'];
		$this->Session->write('Auth.User.group', $group);

		// Check permissions
		if ($group == 'admin') return true; // Remove this line if you don't want admins to have access to everything by default
		if (!empty($this->permissions[$this->request->action])) {
			if ($this->permissions[$this->request->action] == '*') return true;
			if (in_array($group, $this->permissions[$this->request->action])) return true;
		}

		// Disallow by default
		return false;
	}

	/* Function which read settings from DB and populate them in constants */
	function fetchSettings() {
		//Loading model on the fly
		Controller::loadModel('Setting');
		//Fetching All params
		$settings = $this->Setting->find('all');
		foreach($settings as $setting) {
		  Configure::write('Setting.'.$setting['Setting']['key'], $setting['Setting']['value']);
		}
	}

	/**
	 * Log Api Requests, whether failed or not
	 * @return boolean
	 */
	function apiLog() {
		if ($this->requestIsApiJson()){
			$this->loadModel('ApiLog');
			if ($this->request->is('post') || $this->request->is('put')) {
				$data = $this->request->input('json_decode', true);
				if ($data == NULL) {
					$data = $this->request->data;
				}
				$this->request->data = $data;
				$request = serialize($this->request->data);
			} elseif($this->request->is('get')) {
				$request = serialize($this->request->query);
			}

			$apiUser = $this->ApiLog->User->find('first', array(
				'conditions' => array('User.token' => env('PHP_AUTH_USER'),
					array("NOT" => array('User.token' => null))),
				'fields' => array('User.id'),
				'recursive' => -1
			));

			if (!$this->Auth->user('id')) {
				$authStatus = 'Failure';
			} else {
				$authStatus = 'Success';
			}

			$this->ApiLog->create();
			$apiLog = array(
				'auth_status' => $authStatus,
				'user_id' => $apiUser['User']['id'],
				'user_token' => env('PHP_AUTH_USER'),
				'request_type' => $this->request->method(),
				'ip_address' => $this->request->clientIp(),
				'request_url' => $this->request->host().$this->request->here,
				'request_string' => $request
			);

			return $this->ApiLog->save($apiLog);
		}
	}

/**
 * generic delete method to be used by all controllers
 *
 * @param string $id Group id
 * @return void
 */
	public function admin_delete($id) {
		if (!$this->request->is('post')) {
			$this->_failure(__(" - Error 405:  Method Not Allowed!"), $this->referer());
		}
		$this->{$this->modelClass}->id = $id;
		if (!$this->{$this->modelClass}->exists()) {
			$this->_failure(__("Error: {$this->modelClass} id does not exist!"), $this->referer());
		}

		if($this->{$this->modelClass}->delete()) {
			$this->_success(__("{$this->modelClass} has been deleted!"), $this->referer());
		} else {
			$this->_failure(__("Error: Could not delete {$this->modelClass}!"), $this->referer());
		}
	}


/**
 * Display a success message
 *
 * @param string $message Message
 * @param array $redirectUrl url
 * @return mixed
 */
	protected function _success($message = null, $redirectUrl = array(), $cssClass = '') {
		if (empty($message)) {
			$message = __('%s successfully saved', $this->modelClass);
		}
		$alertCss = 'alert-info';
		if (!empty($cssClass)) {
			$alertCss = $cssClass;
		}
		$this->Session->setFlash($message, 'alert', array(
				'plugin' => 'BoostCake',
				'class' => $alertCss
			));
		if (!empty($redirectUrl)) {
			return $this->redirect($redirectUrl);
		}

		return true;
	}

/**
 * Display a failure message
 *
 * @param string $message Message
 * @param array $redirectUrl Url
 * @return mixed
 */
	protected function _failure($message = null, $redirectUrl = array()) {
		if (empty($message)) {
			$message = __('%s could not be saved', $this->modelClass);
		}
		$this->Session->setFlash($message, 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-danger'
			));
		if (!empty($redirectUrl)) {
			return $this->redirect($redirectUrl);
		}

		return true;
	}

/**
 * swaggerSelfUpdate
 * Creates OpenAPI definitions file in JSON and saves it in the swagger UI folder
 * which will be used to autogenerate API documentation.
 * The scanner searches all files for Doctrine annotations in the specified directory and generates a json file.
 *
 * @param boolean $refreshNow
 * @return void
 */
	public function swaggerSelfUpdate() {
		require(APP . "Vendor/autoload.php");
		//Including paths/files known to have Doctrine annotations to avoid too many uncessesary scans
		$includePaths = [
			APP.'Model/Merchant.php',
			APP.'Controller',
		];

		$openapi = \OpenApi\scan($includePaths);
		$jsonData = $openapi->toJson();

		$path = WWW_ROOT . 'AxiaApiDocs' . DS;
		$fp = @fopen($path . 'openapi_axia.json', 'w');
		if ($fp === false) {
			throw new Exception("Internal Error: Unable to generate JSON definitin for swagger --cannot open file openapi_axia.json");
		}
		fwrite($fp, $jsonData);
		fclose($fp);
	}

}
