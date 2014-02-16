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
            'loginRedirect' => array('controller' => 'applications', 'action' => 'index', 'admin' => true),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login')
        ),
        'Session',
        'DebugKit.Toolbar',
        'RequestHandler'
    );
    public $helpers = array(
        'Html' => array(
            'className' => 'TwitterBootstrap.BootstrapHtml'),
        'Form' => array(
            'className' => 'TwitterBootstrap.BootstrapForm'),
        'Paginator' => array(
            'className' => 'TwitterBootstrap.BootstrapPaginator'),
        'Session',
        'Js' => array('AppJs'),
        /*'Ajax', 'Javascript'*/);

    public function beforeFilter() {
        // Force SSL
        $excludeSSL = array("multipass_callback","document_callback");
        if (!$this->RequestHandler->isSSL() && !in_array($this->request->action, $excludeSSL))$this->redirect('https://' . env('HTTP_HOST') . $this->request->here);
        if ($this->params['ext'] == 'json' || $this->request->accepts('application/json')){
            $this->Auth->authenticate = array(
                    'Basic' => array(
                    'realm' => 'api',
                    'fields' => array('username' => 'token','password' => 'api_password'),
                    'scope' => array('User.active' => 1)
                    )
                );
            if(!$this->Auth->login()){
                $this->apiLog ();
            }
        }
    }

    public function isAuthorized() {
        // Load the User model to retrieve group info
        $this->loadModel('User');
 
        if ($this->params['ext'] == 'json' || $this->request->accepts('application/json')){
            $this->apiLog();
            $conditions = array('Apip.ip_address >>=' => $this->request->clientIP(), 'Apip.user_id' => $this->Auth->user('id'));
            if ($this->User->Apip->find('first', array('conditions' => $conditions))) {
                return true;
            }
        }

        
        // Look up user & group info (from auth params...should be safe!)
        // $user = $this->User->findByEmail($this->Auth->user('email'));
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
    * CSV Import functionality for all controllers
    *    
    */
    function import() {
        $modelClass = $this->modelClass;
        if ( $this->request->is('POST') ) {
            $records_count = $this->$modelClass->find( 'count' );
            try {
                $this->$modelClass->importCSV( $this->request->data[$modelClass]['CsvFile']['tmp_name']  );
            } catch (Exception $e) {
                $import_errors = $this->$modelClass->getImportErrors();
                $this->set( 'import_errors', $import_errors );
                $this->Session->setFlash( __('Error Importing') . ' ' . $this->request->data[$modelClass]['CsvFile']['name'] . ', ' . __('column name mismatch.')  );
                $this->redirect( array('action'=>'import') );
            }
             
            $new_records_count = $this->$modelClass->find( 'count' ) - $records_count;
            $this->Session->setFlash(__('Successfully imported') . ' ' . $new_records_count .  ' records from ' . $this->request->data[$modelClass]['CsvFile']['name'] );
            $this->redirect( array('action'=>'index') );
        }
        $this->set('modelClass', $modelClass );
        $this->render('../Common/import');
    } //end import()

    /**
     * Log Api Requests, whether failed or not
     * @return boolean
     */
    function apiLog() {
        if ($this->params['ext'] == 'json' || $this->request->accepts('application/json')){
            $this->loadModel('ApiLog');
            if($this->request->is('post') || $this->request->is('put')){
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
                   'request_string' => $request);
            if ($this->ApiLog->save($apiLog)) {
                return true;
            } else {
                return false;
            }
        }
    } 
}
?>
