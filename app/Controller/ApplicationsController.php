<?php

App::uses('User', 'Model');
App::uses('Xml', 'Utility');
App::uses('Setting', 'Model');
App::uses('HttpSocket', 'Network/Http');
App::uses('CakeEmail', 'Network/Email');
class ApplicationsController extends AppController {
    public $helpers = array('Csv');
    //public $components = array ('RequestHandler');
    public $components = array('Email', 'RequestHandler', 'Security', 'Search.Prg');
    public $scaffold = 'admin';
    public $permissions = array(
        'add' => array('admin', 'rep', 'manager'),
        'complete_fields' => array('admin', 'rep', 'manager'),
        'retrieve' => array('*'),
        'index' => array('*'),
        'end' => array('*'),
        'email_end' => array('admin', 'rep', 'manager'),
        'hooza_end' => array('*'),
        'rep_notify' => array('*'),
        'email_app' => array('admin', 'rep', 'manager'),
        'email_app_owner2' => array('admin', 'rep', 'manager'),
        'sent_document' => array('*'),
        'send_document' => array('*'),
        //'send_document' => array('admin', 'rep'),
        'admin_index' => array('admin', 'rep', 'manager'),
        'admin_email_timeline' => array('admin', 'rep', 'manager'),
        'admin_delete' => array('admin', 'rep', 'manager'),
        'admin_copy_document' => array('admin', 'rep', 'manager'),
        'document_callback' => array('*'),
        'multipass_callback' => array('*'),
        'sign_document' => array('*'),
        'app_status' => array('admin', 'rep', 'manager'),
        'admin_search' => array('admin', 'rep', 'manager')
    );

    function beforeFilter() {
        parent::beforeFilter();
        if (($this->params['ext'] == 'json' || $this->request->accepts('application/json'))) {
            $this->Security->unlockedActions= array('api_add', 'api_edit', 'api_index');
            $this->fetchSettings();
            $this->settings = Configure::read('Setting');
        } else {
            if (!empty($this->params['pass']['2']))
                $this->Auth->allow(array('add', 'app', 'send_document'));
            $this->Security->unlockedActions = array('document_callback','multipass_callback');
            $this->Auth->allow(array('retrieve', 'index', 'end', 'hooza_end', 'rep_notify', 'sent_document', 'document_callback', 'sign_document', 'expired', 'multipass_callback'));
            $this->Security->validatePost = false;
            $this->fetchSettings();
            $this->settings = Configure::read('Setting');
        }
    }

//    function afterFilter() {
//        $conditions = array('Apip.ip_address >>=' => $this->RequestHandler->getClientIP(), 'Apip.user_id' => $this->Auth->user('id'));
//        $this->Application->User->Apip->find('first', array('conditions' => $conditions));
//    }
    //New code added omm
//    public function _restLogin($credentials) {
//        
//        $model = $this->Auth->getModel();
//        try {
//            $id = $model->useToken($credentials['username']);
//            if (empty($id)) {
//                $this->redirect(null, 503);
//            }
//        } catch (Exception $e) {
//            $id = null;
//        }
//        if (empty($id) || !$this->Auth->login(strval($id))) {
//            $this->Security->blackhole($this, 'login');
//        }
//    }//--end New code added 02/23/12
//public function _restLogin($credentials) {
//$login = array();
//foreach(array('username', 'password') as $field) {
//$value = $credentials[$field];
//if ($field == 'password' && !empty($value)) {
//    $value = $this->Auth->password($value);
//}
//$login[$this->Auth->fields[$field]] = $value;
//}
//if (!$this->Auth->login($login)) {
//$this->Security->blackhole($this, 'login');
//}
//}

    public function beforeRender() {
        parent::beforeRender();
        if ($this->request->accepts('application/json')) {
            Configure::write('debug', 0);
            $this->disableCache();
        }
    }

    // ******  FUTURE USE Function to List Applications Via the API  ****** //

//    public function api_index() {
//        if ($this->request->accepts('application/json') && !$this->request->is('get')) {
//            $this->redirect(null, 400);
//        }
//
//        $conditions = array('Application.user_id' => $this->Auth->user('id'));
//        $applications = $this->Application->find('all', array('conditions' => array($conditions), 'limit' => '25', 'recursive' => '-1'));
//        $this->set(compact('applications'));
//
//        //json_encode($this->Application->User->Apip->findByUserId($this->Auth->user('id')));
//    }

    public function api_add() {
        $this->autoRender = false;
        $this->setAction('api_edit');
    }

    public function api_edit($id = null) {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            if (!empty($id)) {
                $this->Application->id = $id;
                //$this->request->data = $this->Application->read(null, $id);
                $this->Application->set($this->request->data);
            } else {
                $this->Application->create();
                if ($this->Auth->user('id') == User::HOOZA) {
                    Configure::load('hooza');
                    $hooza = Configure::read('Application');
                    $this->Application->set($hooza);
                    $this->Application->set(array('user_id' => $this->Auth->user('id')));
                    $this->Application->set($this->request->data);
                    $validationArray =  $this->hzFieldList();
                } else if ($this->Auth->user('id') == User::FIRE_SPRING && $id == null) {
                    Configure::load('FireSpring');
                    $fireSpring = Configure::read('Application');
                    $this->Application->set($fireSpring);
                    $this->Application->set(array('user_id' => $this->Auth->user('id')));
                    $this->Application->set($this->request->data);
                    $validationArray = $this->fsFieldList();
                }
            }

            if ($this->Application->validates($validationArray)) {
                if ($this->Application->save($this->request->data, array('validate' => false))) {
                    //exit;
                    $this->Session->setFlash('Application created Successfully');
                    if ($this->Auth->user('id') == User::HOOZA) {
                        $data = array(
                            'data' => array(
                                'url' => 'https://' . $_SERVER['SERVER_NAME'] . '/applications/app/1/' . $this->Application->id . '/' . $hooza['hash'],
                                'guid' => $hooza['guid'],
                            )
                        );
                        echo json_encode($data);
                        $this->redirect(null, 200);
                    } else if ($this->Auth->user('id') == User::FIRE_SPRING) {
                        $data = $this->send_fs($this->Application->id);
                        echo json_encode($data);
                    } else {
                        $this->redirect(array('action' => 'index'));
                    }
                } else {
                    if ($this->request->accepts('application/json')) {
                        echo json_encode($this->Application->validationErrors);
                        $this->redirect(null, 200);
                    } else {
                        $this->Session->setFlash('Please correct the errors marked below');
                    }
                }
            } else {
                if ($this->request->accepts('application/json')) {
                    echo json_encode($this->Application->validationErrors);
                    $this->redirect(null, 200);
                } else {
                    $this->Session->setFlash('Please correct the errors marked below');
                }
            }
        } elseif (!empty($id)) {
            $this->request->data = $this->Application->find('first', array('conditions' => array('Application.id' => $id)));
            if (empty($this->request->data)) {
                if ($this->request->accepts('application/json')) {
                    $this->redirect(null, 404);
                }
                throw new NotFoundException();
            }
        }
        $this->set(compact('id'));
    }
    /**
     * @todo callback_url is not required because the hooza call back needs to
     * be implemented first
     * @return array
     */
    function hzFieldList() {
        $fieldList = array(
            'fieldList' => array(
                'user_id',
                'hash',
                'corporate_email',
                'corp_contact_name',
//                'callback_url',
                'guid',
            )
        );
        return $fieldList;
    }
/**
 * Array of fields to validate for firespring API
 * @return array
 * @todo move to model, and build method of dynamically building list
 */
    function fsFieldList() {
        $fieldList = array(
            'fieldList' => array(
                'user_id',
                'hash',
                'federal_taxid',
                'legal_business_name',
                'dba_business_name',
                'mailing_address',
                'mailing_city',
                'mailing_state',
                'mailing_zip',
                'mailing_phone',
                'corp_contact_name',
                'corp_contact_name_title',
                'corporate_email',
                'website',
                'monthly_volume',
                'average_ticket',
                'bank_name',
                'depository_routing_number',
                'depository_account_number',
                'customer_svc_phone',
                'callback_url',
                'guid',
                'redirect_url',
            )
        );
        return $fieldList;
    }

    // ****** API DELETE FUNCTION FOR FUTURE USE ****** //
    /*
      public function api_delete($id) {
      if ($this->request->accepts('application/json') && !$this->RequestHandler->isDelete()) {
      $this->redirect(null, 400);
      }
      $post = $this->Application->find('first', array('conditions' => array('Application.id' => $id)));
      if (empty ($post)) {
      if ($this->request->accepts('application/json')) {
      $this->redirect(null, 404);
      }
      throw new NotFoundException();
      }
      if (!empty ($this->request->data) || $this->RequestHandler->isDelete()) {
      if ($this->Application->delete($id)) {
      $this->Session->setFlash('Application deleted successfully');
      if ($this->request->accepts('application/json')) {
      $this->redirect(null, 200);
      } else {
      $this->redirect(array('action'=>'index'));
      }
      } else {
      if ($this->request->accepts('application/json')) {
      $this->redirect(null, 403);
      } else {
      $this->Session->setFlash('Could not delete post');
      }
      }
      }
      $this->set(compact('post'));
      }
     */
    function admin_delete($id) {
        if ($this->Application->delete($id)) {
            $this->Session->setFlash('Application deleted successfully');
            $this->redirect(array('action' => 'index'));
        }
    }

//    function admin_index() {
//        $this->paginate = array(
//            'limit' => 50,
//            'order' => array('Application.id' => 'desc')
//        );
//        $criteria = '';
//        $conditions = array();
//        if ($this->Auth->user('group_id') != User::ADMIN_GROUP_ID) {
//            $conditions = array(
//                'Application.user_id' => $this->Application->User->getAssignedUserIds($this->Auth->user('id'))
//            );
//        }
//        $data = $this->paginate('Application', $conditions);
//        $this->set('users', $this->Application->User->assignableUsers($this->Auth->user('id'), $this->Auth->user('group_id')));
//        $this->set('applications', $data);
//        $this->set(compact('criteria'));
//        $this->set('scaffoldFields', array_keys($this->Application->schema()));
//        Inflector::pluralize('warranty');
//    }
    public function admin_index() {
	    $this->paginate = $this->Application->paginationRules();
	    $criteria = '';
	    if ($this->Auth->user('group_id') != User::ADMIN_GROUP_ID) {
			$conditions = array(
			    'Application.user_id' => $this->Application->User->getAssignedUserIds($this->Auth->user('id'))
			);
		}
	    $data = $this->paginate('Application');
	    $this->set('applications', $data);
	    $this->set('scaffoldFields', array_keys($this->Application->schema()));
	    $this->set('users', $this->Application->User->assignableUsers($this->Auth->user('id'), $this->Auth->user('group_id')));
    }

    public function admin_search() {
        $this->Prg->commonProcess();
        $this->set('users', $this->Application->User->assignableUsers($this->Auth->user('id'), $this->Auth->user('group_id')));
        $criteria = trim($this->passedArgs['search']);
        $criteria = '%' . $criteria . '%';
        $conditions = array('OR' => array(
                'Application.legal_business_name ILIKE' => $criteria,
                'Application.mailing_city ILIKE' => $criteria,
                'Application.corp_contact_name ILIKE' => $criteria,
                'Application.dba_business_name ILIKE' => $criteria,
                'Application.owner1_fullname ILIKE' => $criteria,
                'Application.owner2_fullname ILIKE' => $criteria,
                'User.email ILIKE' => $criteria,
                'CAST(Application.id AS TEXT) ILIKE' => $criteria,
            ),
        );

        if ($this->passedArgs['Select User'] != '') {
            $conditions[] = array('Application.user_id' => $this->passedArgs['Select User']);
        } else if ($this->Auth->user('group_id') != User::ADMIN_GROUP_ID) {
            $conditions[] = array('Application.user_id' => $this->Application->User->getAssignedUserIds($this->Auth->user('id')));
        }
        if ($this->passedArgs['Status'] != '') {
            $conditions[] = array('Application.status' => $this->passedArgs['Status']);
        }

//        $this->paginate = array(
//            'limit' => 50,
//            'order' => array('Application.id' => 'desc'));
	$this->paginate = $this->Application->paginationRules();
        $applications = $this->paginate('Application', $conditions);
        $this->set(compact('applications'));
        $this->set('scaffoldFields', array_keys($this->Application->schema()));
        Inflector::pluralize('warranty');
        $this->set('criteria', $this->passedArgs['search']); //I do it this way because I dont want to include the % chars
        $this->render('admin_index');
    }

    public function admin_override($id = null) {
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash(__('Invalid Application'));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Application->save($this->request->data, array('validate' => false))) {
                $this->Session->setFlash(__('The application has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The application could not be saved. Please, try again.'));
            }
        }
        if (empty($this->request->data)) {
            $this->request->data = $this->Application->read(null, $id);
        }
        $applications = $this->Application->findById($id);
        $users = $this->Application->User->find('list', array('order' => 'User.firstname ASC', 'fields' => array('User.id', 'User.fullname')));
        $this->set(compact('applications', 'users'));
    }

    function admin_add() {
        echo 'Admin add is disabled!';
        exit;
    }

    function admin_edit() {
        echo 'Admin edit is disabled!';
        exit;
    }

    function admin_view() {
        echo 'Admin view is disabled!';
        exit;
    }

    function admin_email_timeline($id) {
        $data = $this->paginate = array(
            'conditions' => array('Application.id' => $id),
            'contain' => array('User.email', 'EmailTimeline' => array('EmailTimelineSubject.subject')),
            'limit' => 50,
            'recursive' => 2
        );

        $data = $this->paginate('Application');
        $this->set('applications', $data);
    }

    function admin_export_document($id, $hash) {
        $this->Application->id = $id;
        $data = $this->Application->read();

        if (!$data || $data['Application']['hash'] != $hash) {
            echo 'error! no data found';
            exit;
        }

        $this->set('data', $data);
        $csv = $this->render('/Elements/applications/export_document', false);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=\"{$id}.csv\"");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
        echo $csv;
        exit;
    }

    function admin_copy_document($id, $hash) {
        $this->Application->id = $id;
        $data = $this->Application->read();

        if (!$data || $data['Application']['hash'] != $hash) {
            echo 'error! no data found';
            exit;
        }

        $this->Application->create();

        $data['Application']['id'] = '';
        $data['Application']['rs_document_guid'] = '';
        $data['Application']['status'] = 'saved';
        $data['Application']['hash'] = md5(String::uuid());
        $data['Application']['var_status'] = '';
        $data['Application']['install_var_rs_document_guid'] = '';

        $this->Application->set($data);
        $this->Application->save($data, array('validate' => false));

        $this->redirect(array('action' => 'add', 'admin' => false, 1, $this->Application->id, $data['Application']['hash']));
    }

    /* ----- FUNCTION COPIED FROM onlineapp.axiapayments.com to send data to RS for in person Signing ------ */

    function send_fs($id) {
        $this->render(false);
        $this->autoRender = false;
        $this->layout = false;
        $view = new View($this, false);
        $this->Application->id = $id;
        $application = $this->Application->read();
        App::import('Vendor', 'oauth', array('file' => 'OAuth' . DS . 'rightsignature.php'));
        $rightsignature = $this->createOAuth();
        $response = $rightsignature->post('/api/templates/' . 'a_2577021_be942b268dad46218ff394bb9ff6470e' . '/prepackage.json', "<?xml version='1.0' encoding='UTF-8'?><callback_location></callback_location>");
        $response = json_decode($response, true);
        if ($response && $response['template']['type'] == 'Document' && $response['template']['guid']) {
            // send the document
            $this->set('document_guid', $response['template']['guid']);
            $this->set('data', $application);
            $guid = $response['template']['guid'];
            $xml = $this->requestAction(
                    array(
                'controller' => 'Applications',
                'action' => 'fs_xml'
                    ), array(
                'pass' => array($id, $guid),
                'return',
                'bare' => 1
                    )
            );
            $response = $rightsignature->post('/api/templates.json', $xml);
            $response = json_decode($response, true);
            if (!empty($response['error']['message'])) {
                return $response;
                exit;
            }
        } else {
            return array('error' => array('message' => 'could not find document guid'));
            exit;
        }
        if ($response['document']['status'] == 'sent' && $response['document']['guid']) {
            // save the guid
            $this->Application->save(array('Application' => array('id' => $id, 'rs_document_guid' => $response['document']['guid'], 'status' => 'completed')), array('validate' => false));
            $url = "https://" . $_SERVER['SERVER_NAME'] . "/applications/sign_document?guid=" . $response['document']['guid'];
            //return $url;
            $data = array('data' => array('url' => $url, 'guid' => $application['Application']['guid']));
        return $data;
        } else{
            return array('error' => array('message' => 'Document Creation Failed'));
        }
    }

    function api_fs_xml($id = null, $guid = null) {
        //$this->autoRender = false;
        $this->Application->id = $id;
        $application = $this->Application->read();
        $this->set('document_guid', $guid);
        $this->set('data', $application);
    }
        function fill_rs_template($id = null, $guid = null) {
        //$this->autoRender = false;
        $this->Application->id = $id;
        $application = $this->Application->read();
        $this->set('document_guid', $guid);
        $this->set('data', $application);
    }

    function send_document($id, $signNow = null, $isOwner = null) {


        $this->Application->id = $id;
        $application = $this->Application->read();



        // Perform validation
        if ($application['Application']['api'] == 0) {
            if (!in_array($application['Application']['status'], array('completed', 'signed')) && $this->validate_steps(1, $application) !== true) {
                $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                $this->redirect(array('action' => 'add', 1, $this->Application->id, $application['Application']['hash']));
            } elseif (!in_array($application['Application']['status'], array('completed', 'signed')) && $this->validate_steps(2, $application) !== true) {
                $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                $this->redirect(array('action' => 'add', 2, $this->Application->id, $application['Application']['hash']));
            } elseif (!in_array($application['Application']['status'], array('completed', 'signed')) && $this->validate_steps(3, $application) !== true) {
                $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                $this->redirect(array('action' => 'add', 3, $this->Application->id, $application['Application']['hash']));
            } elseif (!in_array($application['Application']['status'], array('completed', 'signed')) && $this->validate_steps(4, $application) !== true) {
                $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                $this->redirect(array('action' => 'add', 4, $this->Application->id, $application['Application']['hash']));
            } elseif (!in_array($application['Application']['status'], array('completed', 'signed')) && $this->validate_steps(5, $application) !== true) {
                $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                $this->redirect(array('action' => 'add', 5, $this->Application->id, $application['Application']['hash']));
            } elseif (!in_array($application['Application']['status'], array('completed', 'signed')) && $this->validate_steps(6, $application) !== true) {
                $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                $this->redirect(array('action' => 'add', 6, $this->Application->id, $application['Application']['hash']));
            }
        } else {
            if (!in_array($application['Application']['status'], array('completed', 'signed')) && $this->validate_hooza(1, $application) !== true) {
                $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                $this->redirect(array('action' => 'app', 1, $this->Application->id, $application['Application']['hash']));
            } elseif (!in_array($application['Application']['status'], array('completed', 'signed')) && $this->validate_hooza(2, $application) !== true) {
                $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                $this->redirect(array('action' => 'app', 2, $this->Application->id, $application['Application']['hash']));
            } elseif (!in_array($application['Application']['status'], array('completed', 'signed')) && $this->validate_hooza(3, $application) !== true) {
                $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                $this->redirect(array('action' => 'app', 3, $this->Application->id, $application['Application']['hash']));
            } elseif (!in_array($application['Application']['status'], array('completed', 'signed')) && $this->validate_hooza(4, $application) !== true) {
                $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                $this->redirect(array('action' => 'app', 4, $this->Application->id, $application['Application']['hash']));
            } elseif (!in_array($application['Application']['status'], array('completed', 'signed')) && $this->validate_hooza(5, $application) !== true) {
                $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                $this->redirect(array('action' => 'app', 5, $this->Application->id, $application['Application']['hash']));
            } elseif (!in_array($application['Application']['status'], array('completed', 'signed')) && $this->validate_hooza(6, $application) !== true) {
                $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                $this->redirect(array('action' => 'app', 6, $this->Application->id, $application['Application']['hash']));
            }
        }
        if (!$application) {
            echo 'error! no data found';
            exit;
        }

        if (!empty($application['Application']['rs_document_guid']) && $signNow == true) {
            $this->redirect(array('action' => '/sign_document?guid=' . $application['Application']['rs_document_guid']));
        } else {

            App::import('Vendor', 'oauth', array('file' => 'OAuth' . DS . 'rightsignature.php'));
            $rightsignature = $this->createConsumer();
            //$rightsignature->access_token = new OAuthConsumer($this->settings['OAUTH_TOKEN_KEY'], $this->settings['OAUTH_TOKEN_SECRET'], 1);
            $rightsignature->access_token = new OAuthConsumer('FvpRze1k6JbP7HHm64IxQiWLHL9p0Jl4pw3x7PBP', 'cHrzepxhF7t9QMyO8CGUJlbSg4Lon23JEVYnD70Z', 1);
            //$rightsignature = new RightSignature('HbrHc09UP40iLLMv8kneQTzUTSdsGwpFPGlbvdsa', 'gpkSEoWrVRYgb6EjeHf1Syw1ucfjiWe0d1D1lQks');
            //$rightsignature->access_token = new OAuthConsumer('INq3XUDpo0Pr4rMogpJ3g95tPxlh4vwm1s8ikdiG', 'FHwaomX0XLmAx3p2rK76HbivPxHH1LJ1BIDgzZa4', 1);
            // prepare/package the document template
            //echo 'I did Something';
            /*
              $response = $rightsignature->post('/api/templates/' . $this->settings['RS_TEMPLATE_GUID'] . '/prepackage.json', "<?xml version='1.0' encoding='UTF-8'?><callback_location></callback_location>");
             */


            if ($application['Application']['api'] == 1) {
                $response = $rightsignature->post('/api/templates/' . 'a_1544915_c2696c89c6604549a3d2c27b4d715705' . '/prepackage.json', "<?xml version='1.0' encoding='UTF-8'?><callback_location></callback_location>");
            } ELSE {
                $response = $rightsignature->post('/api/templates/' . 'a_994126_1efe7bc5413f492fa9cef482e9bb4e8b' . '/prepackage.json', "<?xml version='1.0' encoding='UTF-8'?><callback_location></callback_location>");
            }
            //echo 'Some Response';
            $response = json_decode($response, true);

            //echo '<br />This is the Response String: ' . $response;
            if ($response && $response['template']['type'] == 'Document' && $response['template']['guid']) {
                // send the document
                $this->set('document_guid', $response['template']['guid']);
                $this->set('data', $application);

                //if ($signNow)
                //$xml = $this->render('/Elements/applications/sign_document', false);
                $xml = $this->requestAction(
                    array(
                'controller' => 'Applications',
                'action' => 'fill_rs_template'
                    ), array(
                'pass' => array($id,$response['template']['guid']),
                'return',
                'bare' => 1
                    )
            );
                //else //If not simply send the documents
                //$xml = $this->render('/Elements/applications/send_document', false);

                $response = $rightsignature->post('/api/templates.json', $xml);
            } else {
                echo 'error! could not find document guid';
                exit;
            }
            if($_POST){
            $numRoles = count($_POST["role_names"]);
            //debug($_POST);
            $this->set('numRoles', $numRoles);
            $roles = array();
            $this->set('roles', $roles);
            for ($i = 0; $i < $numRoles; $i++) {
                // Ensure each field has values.
                $role_name = $_POST["role_names"][$i];
                if (empty($role_name)) {
                    $_SESSION["error_message"] = "Role Name is missing";
                    // Redirect to Select Template with error message
                    header("Location: $errorURL");
                    exit;
                }

                $name = $_POST["recipient_names"][$i];
                if (empty($name)) {
                    $_SESSION["error_message"] = "Name is blank for $role_name";
                    // Redirect to Select Template with error message
                    header("Location: $errorURL");
                    exit;
                }

                $email = $_POST["recipient_emails"][$i];
                if ($_REQUEST["document_is_embedded"] != "true" && empty($email)) {
                    error_log("~~~~~GIONG to $errorURL~~~~~");
                    $_SESSION["error_message"] = "Email is blank for $role_name";
                    // Redirect to Select Template with error message
                    header("Location: $errorURL");
                    exit;
                }

                $roles[$role_name] = array('name' => htmlspecialchars($name), 'email' => htmlspecialchars($email), 'locked' => 'true');
            }
            }
            $response = json_decode($response, true);
            if ($response['document']['status'] == 'sent' && $response['document']['guid']) {
                // save the guid
                $this->Application->save(array('Application' => array('id' => $id, 'rs_document_guid' => $response['document']['guid'], 'status' => 'completed')), array('validate' => false));
                //Check whether they want to sign in person
                if ($signNow) {
                    if ($isOwner) {
                        $this->redirect(array('action' => 'sign_document?guid=' . $response['document']['guid']));
                    } elseif ($application['Application']['api'] == 1) {
                        $this->hooza_email_app($id);
                    } else {
                        $this->redirect(array('action' => 'sign_document?guid=' . $response['document']['guid']));
                    }
                } else {////If not simply send the documents
                    $this->email_app($id);
                    //$this->redirect(array('action' => 'sent_document'));
                    //$this->redirect(array('action' => 'sign_document?guid=' . $response['document']['guid']));
                }
            } else {
                echo "<pre>";
                echo "An error was encountered sending the document.\n\n";
                echo "</pre>";
                exit;
            }

            $this->autoRender = false;
        }
    }

    /* ----- FUNCTION COPIED FROM rstest.axia-eft.local APP ------- */

    /*
     * Most of this logic should be moved to document_callback function
     * When moved appropriate pieces of logic should be moved to model
     */
    public function sign_document() {
        App::import('Vendor', 'oauth', array('file' => 'OAuth' . DS . 'rightsignature.php'));
        $rightsignature = $this->createConsumer();
        $rightsignature = new RightSignature('J7PQlPSlm3jaa2DbfCP989mIFrKRHUH1NqcjJugT', 'ZAYx4jEy6BVYPuad4kPQAw6lTrOxAeqWU8DGT6A1');
        $this->set('rightsignature', $rightsignature);
        $rightsignature->request_token = new OAuthConsumer('v1cfHXdnHbD8in6ruqsb3MDVbuhdtZMaHTKVw1XI', 'tTyOsXYMAgoPQY5NXlsB9sKAYRZXsuLIcBzTiOpB', 1);
        $rightsignature->access_token = new OAuthConsumer('FvpRze1k6JbP7HHm64IxQiWLHL9p0Jl4pw3x7PBP', 'cHrzepxhF7t9QMyO8CGUJlbSg4Lon23JEVYnD70Z', 1);
        $rightsignature->oauth_verifier = 'jmV0StucajLmdz2gc7hw';
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
            $_SESSION["error_message"] = "Cannot find document with given GUID.";
            header("Location: " . "https://" . $_SERVER['SERVER_NAME'] . "/applications");
        }


// Gets signer link and reload this page after each successfuly signature to refresh the signer-links list

        $response = $rightsignature->getSignerLinks($guid, "https://" . $_SERVER['SERVER_NAME'] . "/applications/sign_document?guid=$guid");
        $this->set('response', $response);
        $xml = Xml::build($response);
        $xml = Xml::toArray($xml);
        $result = Set::normalize($xml);
        $this->set('xml', $xml);
        if ($this->Application->findByRsDocumentGuid($guid)) {
            $data = $this->Application->findByRsDocumentGuid($guid);
            if ($data['Application']['api'] == Application::API_ENABLED && $data['Application']['user_id'] == User::HOOZA) {
                $this->layout = 'default_hz';
            } else if ($data['Application']['api'] == Application::API_ENABLED && $data['Application']['user_id'] == User::FIRE_SPRING) {
                $this->layout = 'default_fs';
                if ($xml['error']['message'] == "Document is already signed.") {
                    $this->redirect($data['Application']['redirect_url']);
                }
            } else $this->layout = 'default';
// Checks xml for error->message node
            
            if ($xml['error']['message'] == "Document is already signed." && $data['Application']['status'] != 'signed') {
                $this->Application->id = $data['Application']['id'];
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
                $send_email = 'true';
                foreach ($data['EmailTimeline'] as $emails):
                    if ($emails['subject_id'] == 2) {
                        $send_email = 'false';
                    }
                endforeach;
                if ($send_email != 'false') {
                    if ($data['Application']['api'] == Application::API_ENABLED && $data['Application']['user_id'] == User::HOOZA) {
//                        if ($this->Application->saveField('status', 'signed')) {
                        $this->hooza_email_followup($data['Application']['id']);
//                        }
                    } else if($data['Application']['api'] != Application::API_ENABLED) {
//                        if ($this->Application->saveField('status', 'signed')) {
                        $this->rep_notify_signed($data['Application']['id']);
//                        }
                    }
                }
                
            }
        }

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

        $alreadySigned = false;
        $this->set('alreadySigned', $alreadySigned);
        $error = false;
        $this->set('error', $error);
        if (isset($xml['error']['message'])) {
            //debug($xml->message);
            $error = true;
            $this->set('error', $error);
            //if ($xml->message == "Document is already signed.")
            //Add Logic for determining if merchant was boarded using API
            //$this->request->data = $this->Application->read();
            //$this->request->data['Application']['rs_document_guid'] = $guid;
            $data = $this->Application->findByRsDocumentGuid($guid);
            $this->set('data', $data);
            $send_email = true;
            foreach ($data['EmailTimeline'] as $emails):
                if ($emails['subject_id'] == 2) {
                    $send_email = false;
                }
            endforeach;
            if ($send_email != false) {
                if ($data['Application']['api'] == Application::API_ENABLED && $data['Application']['user_id'] == User::HOOZA) {
                    $this->hooza_email_followup($data['Application']['id']);
                } else {
                    $this->rep_notify_signed($data['Application']['id']);
                }
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
            }
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
            //End Logic -- Added 3-19-2012 SJT
            $alreadySigned = true;
            $this->set('alreadySigned', $alreadySigned);
        }
    }

    /* --------------------------------------------------------------- */

    function sent_document() {
        //$this->redirect(array('controller' => 'admin', 'action' => 'applications'));
        // ...
    }

    function expired() {
        
    }

    
    /*
     * API callback for the RightSignature API, the RS API hits this callback
     * after a document has been successfully signed.
     * callback implements logic for what should happen to applications after
     * they have been signed.
     */
    function document_callback() {
        $this->request->data = array_change_key_case($this->request->data);
        CakeLog::write('debug', print_r($this->request->data, true));
        if ($this->request->data['callback']['guid'] && $this->data['callback']['status'] == 'signed') {
            $data = $this->Application->findByRsDocumentGuid($this->request->data['callback']['guid']);
            if (empty($data)) {
                $data = $this->Application->findByInstallVarRsDocumentGuid($this->request->data['callback']['guid']);
                if (!empty($data)) {
                    $this->Application->id = $data['Application']['id'];
                    $this->Application->saveField('var_status', 'signed');
                }
                exit;
            }
            if (!empty($data)) {
                $this->Application->id = $data['Application']['id'];
                if ($data['Application']['user_id'] == User::FIRE_SPRING && $data['Application']['api'] == true) {
                    $multipassData = $this->assignMultipass($data);
                        if (is_array($multipassData) && $this->Application->saveField('status', 'signed')) {
                            $this->emailMultipassToDataEntry($data, $multipassData['Multipass']['merchant_id']);
                        }
                } else {
                    $this->Application->saveField('status', 'signed');
                }
            }
        }
        exit;
    }
    /**
     * Put all of the pieces together to make an OAuth connection to RightSignature
     * https://rightsignature.com/apidocs/oauth_documentation
     * @return type
     */
    protected function createOAuth() {
        App::import('Vendor', 'oauth', array('file' => 'OAuth' . DS . 'rightsignature.php'));
        App::import('Vendor', 'oauth', array('file' => 'OAuth' . DS . 'OAuth.php'));
        App::uses('HttpSocket', 'Network/Http');
        $HttpSocket = new HttpSocket();
        $rightsignature = $this->createConsumer();
        $rightsignature->request_token = new OAuthConsumer($this->settings['OAUTH_REQUEST_TOKEN_KEY'], $this->settings['OAUTH_REQUEST_PRIVATE_KEY'], 1);
        $rightsignature->access_token = new OAuthConsumer($this->settings['OAUTH_ACCESS_TOKEN_KEY'], $this->settings['OAUTH_ACCESS_PRIVATE_KEY'], 1);
        $rightsignature->oauth_verifier = $this->settings['OAUTH_VERIFIER'];
        return $rightsignature;
    }
    /**
     * Grab document status via the RightSignature API
     * https://rightsignature.com/apidocs/api_calls?api_method=documentDetails
     * @param integer $id
     * @param varchar $renew
     * RightSignature Document Guid Allows for extending life of application
     */
    function app_status($id, $renew = null) {
        $application = $this->Application->findById($id);
        $rightsignature = $this->createOAuth();
        App::import('Vendor', 'oauth', array('file' => 'OAuth' . DS . 'rightsignature.php'));
        $this->set('rightsignature', $rightsignature);
        $results = $rightsignature->getDocumentDetails($application['Application']['rs_document_guid']);
        $data = json_decode($results, true);
        $pg = 'Personal Guarantee';
        $app = 'Application';
        $recipients = array_reverse($data['document']['recipients']);
        $state = $data['document']['state'];
        $guid = $application['Application']['rs_document_guid'];
        if ($renew != '') {
            $renewed = $rightsignature->extendDocument($guid);
            $extension = json_decode($renewed, true);
            if (isset($extension['document'])) {
                $this->Session->setFlash($extension['document']['status']);
            } else {
                $this->Session->setFlash($extension['error']['message']);
            }
            $this->redirect('app_status/' . $id);
        }
        $this->set(compact('id', 'data', 'recipients', 'pg', 'app', 'guid'));
    }
    /**
     * Extend the life of a RightSignature document via Right Signature API
     * https://rightsignature.com/apidocs/api_calls?api_method=extendExpiration
     * @param varchar $guid
     * Right Signature Unique Identifier
     */
    function app_extend($guid) {
        $rightsignature = $this->createOAuth();
        App::import('Vendor', 'oauth', array('file' => 'OAuth' . DS . 'rightsignature.php'));
        $this->set('rightsignature', $rightsignature);
        App::uses('HttpSocket', 'Network/Http');
        $HttpSocket = new HttpSocket();
        $results = $HttpSocket->post('https://rightsignature.com/api/documents/' . $guid . '/extend_expiration.xml');
        $this->render('app_status');
    }
    /**
     * logic for 6 steps of the online app
     * @param integer $step
     * the step that the user is on
     * @param type $id
     * the id of the application being accessed
     * @param type $hash
     * a unique hash used to assist merchants in retrieving applications
     */
    function add($step = 1, $id = null, $hash = null) {
        $this->set('step', $step);
        $this->set('id', (is_numeric($id) && $id ? $id : ''));
        $this->set('hash', $hash);

        switch ($step) {
            case 1:
                // ***************
                // PROCESS STEP #1
                // ***************
                if ($this->request->is('post') || $this->request->is('put')) {
                    if (!$id && !$this->request->data['Application']['id']) {

                        // create a new application
                        $this->Application->create();
                        $hash = md5(String::uuid());

                        $this->Application->set(array(
                            'status' => 'saved',
                            'hash' => $hash
                        ));
                        // Set user_id if an authenticated user is creating the app
                        if ($this->Auth->user('id'))
                            $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->set('hash', $hash);
                        //$this->Application->test();
                    }
                    else {

                        $this->Application->id = ($this->request->data['Application']['id'] ? $this->request->data['Application']['id'] : $id);
                        // check for appropriate hash
                        $application = $this->Application->read();
                        if ($application['Application'] && $application['Application']['hash'] != $hash) {
                            $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                            $this->redirect(array('action' => 'retrieve'));
                        }
                    }

                    // PERFORM VALIDATION
                    if ($application['Application'] && in_array($application['Application']['status'], array('completed', 'signed'))) {
                        $this->request->data = $application;
                        $validation = false;
                    } elseif ($application['Application'] && $application['Application']['status'] != 'saved') {
                            if ($this->request->data['Application']['location_type'] != 'other') {
                            unset($this->request->data['Application']['location_type_other']);
                        }
                        $validation = $this->validate_steps(1, $this->request->data);
                    }
                    else
                        $validation = true;

                    if ($validation === true) {
                        // SAVE THE DATA
                        $this->Application->set($this->request->data);
                        //.if ($this->Auth->user('id')) $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        if ($this->Application->save($this->request->data, array('validate' => false))) {
                            // REDIRECT TO THE NEXT STEP
                            $this->redirect(array('action' => 'add', 2, $this->Application->id, $hash));
                        }
                    } else {
                        $this->set('id', (is_numeric($id) && $id ? $id : $this->request->data['Application']['id']));
                        $this->set('errors', $validation);
                    }
                }
                if ($id && $this->request->is('get')) {
                    $this->Application->id = $id;
                    $this->request->data = $this->Application->read();
                    // check for appropriate hash
                    if ($this->request->data['Application']['hash'] != $hash) {
                        $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                        $this->redirect(array('action' => 'retrieve'));
                    }
                    // check for document expiration
                    if (!$this->Application->expiration($this->request->data) && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Expired');
                        $this->redirect(array('action' => 'expired'));
                    }
                    // check whether application is signed
                    if ($this->request->data['Application']['status'] == 'signed' && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Ben Signed');
                        $this->redirect(array('action' => 'signed'));
                    }
                    if ($this->request->data['Application']['status'] != 'saved') {
                        $this->set('errors', $validation = $this->validate_steps(1, $this->request->data));
                    }
                } 
                $this->render('step_1');
                break;
            case 2:

                if ($this->request->is('post') || $this->request->is('put')) {
                    if (!$id && !$this->request->data['Application']['id']) {
                        // create a new application
                        $this->Application->create();
                        $hash = md5(String::uuid());

                        $this->Application->set(array(
                            'status' => 'saved',
                            'hash' => $hash
                        ));
                        // Set user_id if an authenticated user is creating the app
                        if ($this->Auth->user('id'))
                            $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->set('hash', $hash);
                    }
                    else {
                        $this->Application->id = ($this->request->data['Application']['id'] ? $this->request->data['Application']['id'] : $id);

                        // check for appropriate hash
                        $application = $this->Application->read();
                        if ($application['Application'] && $application['Application']['hash'] != $hash) {
                            $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                            $this->redirect(array('action' => 'retrieve'));
                        }
                    }

                    // PERFORM VALIDATION
                    if ($application['Application'] && in_array($application['Application']['status'], array('completed', 'signed'))) {
                        $this->request->data = $application;
                        $validation = false;
                    } elseif ($application['Application'] && $application['Application']['status'] != 'saved')
                        $validation = $this->validate_steps(2, $this->request->data);
                    else
                        $validation = true;

                    if ($validation === true) {
                        // SAVE THE DATA
                        $this->Application->set($this->request->data);
                        //.if ($this->Auth->user('id')) $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->Application->save($this->request->data, array('validate' => false));
                        // REDIRECT TO THE NEXT STEP
                        $this->redirect(array('action' => 'add', 3, $this->Application->id, $hash));
                    } else {
                        $this->set('id', (is_numeric($id) && $id ? $id : $this->request->data['Application']['id']));
                        $this->set('errors', $validation);
                    }
                } 
                if ($id && $this->request->is('get')) {
                    $this->Application->id = $id;
                    $this->request->data = $this->Application->read();
                    // check for appropriate hash
                    if ($this->request->data['Application']['hash'] != $hash) {
                        $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                        $this->redirect(array('action' => 'retrieve'));
                    }
                    // check for document expiration
                    if (!$this->Application->expiration($this->request->data) && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Expired');
                        $this->redirect(array('action' => 'expired'));
                    }
                    // check whether application is signed
                    if ($this->request->data['Application']['status'] == 'signed' && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Ben Signed');
                        $this->redirect(array('action' => 'signed'));
                    }
                    // check for invalid fields
                    if ($this->request->data['Application']['status'] != 'saved')
                        $this->set('errors', $validation = $this->validate_steps(2, $this->request->data));
                }

                $this->render('step_2');
                break;
            case 3:
                if ($this->request->is('post') || $this->request->is('put')) {
                    if (!$id && !$this->request->data['Application']['id']) {
                        // create a new application
                        $this->Application->create();
                        $hash = md5(String::uuid());

                        $this->Application->set(array(
                            'status' => 'saved',
                            'hash' => $hash
                        ));
                        // Set user_id if an authenticated user is creating the app
                        if ($this->Auth->user('id'))
                            $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->set('hash', $hash);
                    }
                    else {
                        $this->Application->id = ($this->request->data['Application']['id'] ? $this->request->data['Application']['id'] : $id);

                        // check for appropriate hash
                        $application = $this->Application->read();
                        if ($application['Application'] && $application['Application']['hash'] != $hash) {
                            $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                            $this->redirect(array('action' => 'retrieve'));
                        }
                    }

                    // PERFORM VALIDATION
                    if ($application['Application'] && in_array($application['Application']['status'], array('completed', 'signed'))) {
                        $this->request->data = $application;
                        $validation = false;
                    } elseif ($application['Application'] && $application['Application']['status'] != 'saved')
                        $validation = $this->validate_steps(3, $this->request->data);
                    else
                        $validation = true;

                    if ($validation === true) {
                        // SAVE THE DATA
                        $this->Application->set($this->request->data);
                        //.if ($this->Auth->user('id')) $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->Application->save($this->request->data, array('validate' => false));
                        // REDIRECT TO THE NEXT STEP
                        $this->redirect(array('action' => 'add', 4, $this->Application->id, $hash));
                    } else {
                        $this->set('id', (is_numeric($id) && $id ? $id : $this->request->data['Application']['id']));
                        $this->set('errors', $validation);
                    }
                } 
                if ($id && $this->request->is('get')) {
                    $this->Application->id = $id;
                    $this->request->data = $this->Application->read();

                    // check for appropriate hash
                    if ($this->request->data['Application']['hash'] != $hash) {
                        $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                        $this->redirect(array('action' => 'retrieve'));
                    }
                    // check for document expiration
                    if (!$this->Application->expiration($this->request->data) && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Expired');
                        $this->redirect(array('action' => 'expired'));
                    }
                    // check whether application is signed
                    if ($this->request->data['Application']['status'] == 'signed' && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Ben Signed');
                        $this->redirect(array('action' => 'signed'));
                    }
                    // check for invalid fields
                    if ($this->request->data['Application']['status'] != 'saved')
                        $this->set('errors', $validation = $this->validate_steps(3, $this->request->data));
                }

                $this->render('step_3');
                break;


            case 4:
                if ($this->request->is('post') || $this->request->is('put')) {
                    if (!$id && !$this->request->data['Application']['id']) {
                        // create a new application
                        $this->Application->create();
                        $hash = md5(String::uuid());

                        $this->Application->set(array(
                            'status' => 'saved',
                            'hash' => $hash
                        ));
                        // Set user_id if an authenticated user is creating the app
                        if ($this->Auth->user('id'))
                            $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->set('hash', $hash);
                    }
                    else {
                        $this->Application->id = ($this->request->data['Application']['id'] ? $this->request->data['Application']['id'] : $id);

                        // check for appropriate hash
                        $application = $this->Application->read();
                        if ($application['Application'] && $application['Application']['hash'] != $hash) {
                            $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                            $this->redirect(array('action' => 'retrieve'));
                        }
                    }

                    // PERFORM VALIDATION
                    if ($application['Application'] && in_array($application['Application']['status'], array('completed', 'signed'))) {
                        $this->request->data = $application;
                        $validation = false;
                    } elseif ($application['Application'] && $application['Application']['status'] != 'saved')
                        $validation = $this->validate_steps(4, $this->request->data);
                    else
                        $validation = true;

                    if ($validation === true) {
                        // SAVE THE DATA
                        $this->Application->set($this->request->data);
                        //.if ($this->Auth->user('id')) $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->Application->save($this->request->data, array('validate' => false));
                        // REDIRECT TO THE NEXT STEP
                        $this->redirect(array('action' => 'add', 5, $this->Application->id, $hash));
                    } else {
                        $this->set('id', (is_numeric($id) && $id ? $id : $this->request->data['Application']['id']));
                        $this->set('errors', $validation);
                    }
                } 
                if ($id && $this->request->is('get')) {
                    $this->Application->id = $id;
                    $this->request->data = $this->Application->read();

                    // check for appropriate hash
                    if ($this->request->data['Application']['hash'] != $hash) {
                        $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                        $this->redirect(array('action' => 'retrieve'));
                    }
                    // check for document expiration
                    if (!$this->Application->expiration($this->request->data) && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Expired');
                        $this->redirect(array('action' => 'expired'));
                    }
                    // check whether application is signed
                    if ($this->request->data['Application']['status'] == 'signed' && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Ben Signed');
                        $this->redirect(array('action' => 'signed'));
                    }
                    // check for invalid fields
                    if ($this->request->data['Application']['status'] != 'saved')
                        $this->set('errors', $validation = $this->validate_steps(4, $this->request->data));
                }

                $this->render('step_4');
                break;


            case 5:
                if ($this->request->is('post') || $this->request->is('put')) {
                    if (!$id && !$this->request->data['Application']['id']) {
                        // create a new application
                        $this->Application->create();
                        $hash = md5(String::uuid());

                        $this->Application->set(array(
                            'status' => 'saved',
                            'hash' => $hash
                        ));
                        // Set user_id if an authenticated user is creating the app
                        if ($this->Auth->user('id'))
                            $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->set('hash', $hash);
                    }
                    else {
                        $this->Application->id = ($this->request->data['Application']['id'] ? $this->request->data['Application']['id'] : $id);

                        // check for appropriate hash
                        $application = $this->Application->read();
                        if ($application['Application'] && $application['Application']['hash'] != $hash) {
                            $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                            $this->redirect(array('action' => 'retrieve'));
                        }
                    }

                    // PERFORM VALIDATION
                    if ($application['Application'] && in_array($application['Application']['status'], array('completed', 'signed'))) {
                        $this->request->data = $application;
                        $validation = false;
                    } elseif ($application['Application'] && $application['Application']['status'] != 'saved')
                        $validation = $this->validate_steps(5, $this->request->data);
                    else
                        $validation = true;

                    if ($validation === true) {
                        // SAVE THE DATA
                        $this->Application->set($this->request->data);
                        //.if ($this->Auth->user('id')) $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->Application->save($this->request->data, array('validate' => false));
                        // REDIRECT TO THE NEXT STEP
                        $this->redirect(array('action' => 'add', 6, $this->Application->id, $hash));
                    } else {
                        $this->set('id', (is_numeric($id) && $id ? $id : $this->request->data['Application']['id']));
                        $this->set('errors', $validation);
                    }
                } 
                if ($id && $this->request->is('get')) {
                    $this->Application->id = $id;
                    $this->request->data = $this->Application->read();

                    // check for appropriate hash
                    if ($this->request->data['Application']['hash'] != $hash) {
                        $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                        $this->redirect(array('action' => 'retrieve'));
                    }
                    // check for document expiration
                    if (!$this->Application->expiration($this->request->data) && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Expired');
                        $this->redirect(array('action' => 'expired'));
                    }
                    // check whether application is signed
                    if ($this->request->data['Application']['status'] == 'signed' && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Ben Signed');
                        $this->redirect(array('action' => 'signed'));
                    }
                    // check for invalid fields
                    if ($this->request->data['Application']['status'] != 'saved')
                        $this->set('errors', $validation = $this->validate_steps(5, $this->request->data));
                }


                $this->render('step_5');
                break;
            case 6:
                // ***************
                // PROCESS STEP #6
                // ***************

                if ($this->request->is('post') || $this->request->is('put')) {

                    if (!$id && !$this->request->data['Application']['id']) {
                        // create a new application
                        $this->Application->create();
                        $hash = md5(String::uuid());

                        $this->Application->set(array(
                            'status' => 'saved',
                            'hash' => $hash
                        ));
                        // Set user_id if an authenticated user is creating the app
                        if ($this->Auth->user('id'))
                            $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->set('hash', $hash);
                    }
                    else {

                        $this->Application->id = ($this->request->data['Application']['id'] ? $this->request->data['Application']['id'] : $id);

                        // check for appropriate hash
                        $application = $this->Application->read();
                        $flatrate = $this->Application->User->flatRateUsers($application['Application']['user_id']);
                        $this->set('flatrate', $flatrate);
                        if ($application['Application']['hash'] != $hash) {
                            $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                            $this->redirect(array('action' => 'retrieve'));
                        }
                    }

                    // PERFORM VALIDATION
                    if (in_array($this->Auth->user('group'), array('admin', 'rep', 'manager'))) {
                        if (in_array($application['Application']['status'], array('completed', 'signed'))) {
                            $this->request->data = $application;
                            $validation = false;
                        }
                        else
                            $validation = true;
                    }
                    else {
                        if ($this->validate_steps(1, $application) !== true && in_array($application['Application']['status'], array('saved', 'validate'))) {
                            $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                            $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                            $this->redirect(array('action' => 'add', 1, $this->Application->id, $hash));
                        } elseif ($this->validate_steps(2, $application) !== true && in_array($application['Application']['status'], array('saved', 'validate'))) {
                            $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                            $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                            $this->redirect(array('action' => 'add', 2, $this->Application->id, $hash));
                        } elseif ($this->validate_steps(3, $application) !== true && in_array($application['Application']['status'], array('saved', 'validate'))) {
                            $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                            $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                            $this->redirect(array('action' => 'add', 3, $this->Application->id, $hash));
                        } elseif ($this->validate_steps(4, $application) !== true && in_array($application['Application']['status'], array('saved', 'validate'))) {
                            $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                            $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                            $this->redirect(array('action' => 'add', 4, $this->Application->id, $hash));
                        } elseif ($this->validate_steps(5, $application) !== true && in_array($application['Application']['status'], array('saved', 'validate'))) {
                            $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                            $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                            $this->redirect(array('action' => 'add', 5, $this->Application->id, $hash));
                        } elseif (in_array($application['Application']['status'], array('completed', 'signed'))) {
                            $this->request->data = $application;
                            $validation = false;
                        }
                        else
                            $validation = $this->validate_steps(6, $this->request->data);
                    }

                    if ($validation === true) {
                        // SAVE THE DATA
                       
                        $this->Application->set($this->request->data);
                        // if ($this->Auth->user('id')) $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        if (in_array($application['Application']['status'], array('saved', 'validate')) && !in_array($this->Auth->user('group'), array('admin', 'rep', 'manager')))
                            $this->request->data['Application']['status'] = 'pending';
                        $this->Application->save($this->request->data, array('validate' => false));
                        // REDIRECT TO THE END
                        if (!in_array($this->Auth->user('group'), array('admin', 'rep', 'manager'))) {
                            $this->rep_notify($application['Application']['id']); /* $this->redirect(array('action'=>'end')); */
                        }
                    } else {
                        $this->set('id', ($id ? $id : $this->request->data['Application']['id']));
                        $this->set('errors', $validation);
                    }
                } 
                if ($id && $this->request->is('get')) {
                    $this->Application->id = $id;
                    $this->request->data = $this->Application->read();
                        $flatrate = $this->Application->User->flatRateUsers($this->request->data['Application']['user_id']);
                        $this->set('flatrate', $flatrate);
                    // check for appropriate id/data
                    if (!$id || empty($this->request->data)) {
                        $this->Session->setFlash('That application ID was not found! Please start a new application or retrieve your existing applications.');
                        $this->redirect('/');
                    }

                    // check for appropriate hash
                    if ($this->request->data['Application']['hash'] != $hash) {
                        $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                        $this->redirect(array('action' => 'retrieve'));
                    }
                    // check for document expiration
                    if (!$this->Application->expiration($this->request->data) && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Expired');
                        $this->redirect(array('action' => 'expired'));
                    }
                    // check whether application is signed
                    if ($this->request->data['Application']['status'] == 'signed' && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Ben Signed');
                        $this->redirect(array('action' => 'signed'));
                    }
                    // check for invalid fields
                    if ($this->request->data['Application']['status'] != 'saved')
                        $this->set('errors', $validation = $this->validate_steps(6, $this->request->data));
                }

                $this->render('step_6');
                break;
            default:
                $this->redirect(array('action' => 'add', 1));
        }
    }
    /**
     * Provides logic for the hooza version of the online app
     * @param type $hooza
     * @param type $id
     * @param type $hash
     */
    function app($hooza = 1, $id = null, $hash = null) {
        $this->layout = 'default_hz';
        $this->Application->setValidation('app');
        $this->set('hooza', $hooza);
        $this->set('id', (is_numeric($id) && $id ? $id : ''));
        $this->set('hash', $hash);

        switch ($hooza) {
            case 1:
                // ***************
                // PROCESS STEP #1
                // ***************
                if ($this->request->is('post') || $this->request->is('put')) {
                    if (!$id && !$this->request->data['Application']['id']) {
                        // create a new application
                        $this->Application->create();
                        $hash = md5(String::uuid());

                        $this->Application->set(array(
                            'status' => 'saved',
                            'hash' => $hash
                        ));
                        // Set user_id if an authenticated user is creating the app
                        if ($this->Auth->user('id'))
                            $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->set('hash', $hash);
                    }
                    else {
                        $this->Application->id = ($this->request->data['Application']['id'] ? $this->request->data['Application']['id'] : $id);

                        // check for appropriate hash
                        $application = $this->Application->read();
                        if ($application['Application'] && $application['Application']['hash'] != $hash) {
                            $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                            $this->redirect(array('action' => 'retrieve'));
                        }
                    }

                    // PERFORM VALIDATION
                    if ($application['Application'] && in_array($application['Application']['status'], array('completed', 'signed'))) {
                        $this->request->data = $application;
                        $validation = false;
                    } elseif ($application['Application'] && $application['Application']['status'] != 'saved')
                        $validation = $this->validate_hooza(1, $this->request->data);
                    else
                        $validation = true;

                    if ($validation === true) {
                        // SAVE THE DATA
                        $this->Application->set($this->request->data);
                        //.if ($this->Auth->user('id')) $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->Application->save($this->request->data, array('validate' => false));
                        // REDIRECT TO THE NEXT STEP
                        $this->redirect(array('action' => 'app', 2, $this->Application->id, $hash));
                    } else {
                        $this->set('id', (is_numeric($id) && $id ? $id : $this->request->data['Application']['id']));
                        $this->set('errors', $validation);
                    }
                } elseif ($id && $this->request->is('get')) {
                    $this->Application->id = $id;
                    $this->request->data = $this->Application->read();

                    // check for appropriate hash
                    if ($this->request->data['Application']['hash'] != $hash) {
                        $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                        $this->redirect(array('action' => 'retrieve'));
                    }
                    // check for document expiration
                    if (!$this->Application->expiration($this->request->data) && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Expired');
                        $this->redirect(array('action' => 'expired'));
                    }
                    // check whether application is signed
                    if ($this->request->data['Application']['status'] == 'signed' && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Ben Signed');
                        $this->redirect(array('action' => 'signed'));
                    }
                    // check for invalid fields
                    if ($this->request->data['Application']['status'] != 'saved')
                        $this->set('errors', $validation = $this->validate_hooza(1, $this->request->data));
                }

                $this->render('hooza_1');
                break;
            case 2:
                if ($this->request->is('post') || $this->request->is('put')) {
                    if (!$id && !$this->request->data['Application']['id']) {
                        // create a new application
                        $this->Application->create();
                        $hash = md5(String::uuid());

                        $this->Application->set(array(
                            'status' => 'saved',
                            'hash' => $hash
                        ));
                        // Set user_id if an authenticated user is creating the app
                        if ($this->Auth->user('id'))
                            $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->set('hash', $hash);
                    }
                    else {
                        $this->Application->id = ($this->request->data['Application']['id'] ? $this->request->data['Application']['id'] : $id);

                        // check for appropriate hash
                        $application = $this->Application->read();
                        if ($application['Application'] && $application['Application']['hash'] != $hash) {
                            $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                            $this->redirect(array('action' => 'retrieve'));
                        }
                    }

                    // PERFORM VALIDATION
                    if ($application['Application'] && in_array($application['Application']['status'], array('completed', 'signed'))) {
                        $this->request->data = $application;
                        $validation = false;
                    } elseif ($application['Application'] && $application['Application']['status'] != 'saved')
                        $validation = $this->validate_hooza(2, $this->request->data);
                    else
                        $validation = true;

                    if ($validation === true) {
                        // SAVE THE DATA
                        $this->Application->set($this->request->data);
                        //.if ($this->Auth->user('id')) $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->Application->save($this->request->data, array('validate' => false));
                        // REDIRECT TO THE NEXT STEP
                        $this->redirect(array('action' => 'app', 3, $this->Application->id, $hash));
                    } else {
                        $this->set('id', (is_numeric($id) && $id ? $id : $this->request->data['Application']['id']));
                        $this->set('errors', $validation);
                    }
                } elseif ($id && $this->request->is('get')) {
                    $this->Application->id = $id;
                    $this->request->data = $this->Application->read();

                    // check for appropriate hash
                    if ($this->request->data['Application']['hash'] != $hash) {
                        $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                        $this->redirect(array('action' => 'retrieve'));
                    }
                    // check for document expiration
                    if (!$this->Application->expiration($this->request->data) && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Expired');
                        $this->redirect(array('action' => 'expired'));
                    }
                    // check whether application is signed
                    if ($this->request->data['Application']['status'] == 'signed' && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Ben Signed');
                        $this->redirect(array('action' => 'signed'));
                    }
                    // check for invalid fields
                    if ($this->request->data['Application']['status'] != 'saved')
                        $this->set('errors', $validation = $this->validate_hooza(2, $this->request->data));
                }

                $this->render('hooza_2');
                break;
            case 3:
                if ($this->request->is('post') || $this->request->is('put')) {
                    if (!$id && !$this->request->data['Application']['id']) {
                        // create a new application
                        $this->Application->create();
                        $hash = md5(String::uuid());

                        $this->Application->set(array(
                            'status' => 'saved',
                            'hash' => $hash
                        ));
                        // Set user_id if an authenticated user is creating the app
                        if ($this->Auth->user('id'))
                            $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->set('hash', $hash);
                    }
                    else {
                        $this->Application->id = ($this->request->data['Application']['id'] ? $this->request->data['Application']['id'] : $id);

                        // check for appropriate hash
                        $application = $this->Application->read();
                        if ($application['Application'] && $application['Application']['hash'] != $hash) {
                            $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                            $this->redirect(array('action' => 'retrieve'));
                        }
                    }

                    // PERFORM VALIDATION
                    if ($application['Application'] && in_array($application['Application']['status'], array('completed', 'signed'))) {
                        $this->request->data = $application;
                        $validation = false;
                    } elseif ($application['Application'] && $application['Application']['status'] != 'saved')
                        $validation = $this->validate_hooza(3, $this->request->data);
                    else
                        $validation = true;

                    if ($validation === true) {
                        // SAVE THE DATA
                        $this->Application->set($this->request->data);
                        //.if ($this->Auth->user('id')) $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->Application->save($this->request->data, array('validate' => false));
                        // REDIRECT TO THE NEXT STEP
                        $this->redirect(array('action' => 'app', 4, $this->Application->id, $hash));
                    } else {
                        $this->set('id', (is_numeric($id) && $id ? $id : $this->request->data['Application']['id']));
                        $this->set('errors', $validation);
                    }
                } elseif ($id && $this->request->is('get')) {
                    $this->Application->id = $id;
                    $this->request->data = $this->Application->read();

                    // check for appropriate hash
                    if ($this->request->data['Application']['hash'] != $hash) {
                        $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                        $this->redirect(array('action' => 'retrieve'));
                    }
                    // check for document expiration
                    if (!$this->Application->expiration($this->request->data) && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Expired');
                        $this->redirect(array('action' => 'expired'));
                    }
                    // check whether application is signed
                    if ($this->request->data['Application']['status'] == 'signed' && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Ben Signed');
                        $this->redirect(array('action' => 'signed'));
                    }
                    // check for invalid fields
                    if ($this->request->data['Application']['status'] != 'saved')
                        $this->set('errors', $validation = $this->validate_hooza(3, $this->request->data));
                }

                $this->render('hooza_3');
                break;


            case 4:
                if ($this->request->is('post') || $this->request->is('put')) {
                    if (!$id && !$this->request->data['Application']['id']) {
                        // create a new application
                        $this->Application->create();
                        $hash = md5(String::uuid());

                        $this->Application->set(array(
                            'status' => 'saved',
                            'hash' => $hash
                        ));
                        // Set user_id if an authenticated user is creating the app
                        if ($this->Auth->user('id'))
                            $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->set('hash', $hash);
                    }
                    else {
                        $this->Application->id = ($this->request->data['Application']['id'] ? $this->request->data['Application']['id'] : $id);

                        // check for appropriate hash
                        $application = $this->Application->read();
                        if ($application['Application'] && $application['Application']['hash'] != $hash) {
                            $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                            $this->redirect(array('action' => 'retrieve'));
                        }
                    }

                    // PERFORM VALIDATION
                    if ($application['Application'] && in_array($application['Application']['status'], array('completed', 'signed'))) {
                        $this->request->data = $application;
                        $validation = false;
                    } elseif ($application['Application'] && $application['Application']['status'] != 'saved')
                        $validation = $this->validate_hooza(4, $this->request->data);
                    else
                        $validation = true;

                    if ($validation === true) {
                        // SAVE THE DATA
                        $this->Application->set($this->request->data);
                        //Add Amex Pricing to for folks that want Amex
                        if ($this->request->data['Application']['want_to_accept_amex'] == 'yes')
                            $this->Application->saveField('rep_amex_discount_rate', '3.50%');
                        //.if ($this->Auth->user('id')) $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->Application->save($this->request->data, array('validate' => false));
                        // REDIRECT TO THE NEXT STEP
                        $this->redirect(array('action' => 'app', 5, $this->Application->id, $hash));
                    }
                    else {
                        $this->set('id', (is_numeric($id) && $id ? $id : $this->request->data['Application']['id']));
                        $this->set('errors', $validation);
                    }
                } elseif ($id && $this->request->is('get')) {
                    $this->Application->id = $id;
                    $this->request->data = $this->Application->read();

                    // check for appropriate hash
                    if ($this->request->data['Application']['hash'] != $hash) {
                        $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                        $this->redirect(array('action' => 'retrieve'));
                    }
                    // check for document expiration
                    if (!$this->Application->expiration($this->request->data) && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Expired');
                        $this->redirect(array('action' => 'expired'));
                    }
                    // check whether application is signed
                    if ($this->request->data['Application']['status'] == 'signed' && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Ben Signed');
                        $this->redirect(array('action' => 'signed'));
                    }
                    // check for invalid fields
                    if ($this->request->data['Application']['status'] != 'saved')
                        $this->set('errors', $validation = $this->validate_hooza(4, $this->request->data));
                }

                $this->render('hooza_4');
                break;


            case 5:
                if ($this->request->is('post') || $this->request->is('put')) {
                    if (!$id && !$this->request->data['Application']['id']) {
                        // create a new application
                        $this->Application->create();
                        $hash = md5(String::uuid());

                        $this->Application->set(array(
                            'status' => 'saved',
                            'hash' => $hash
                        ));
                        // Set user_id if an authenticated user is creating the app
                        if ($this->Auth->user('id'))
                            $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->set('hash', $hash);
                    }
                    else {
                        $this->Application->id = ($this->request->data['Application']['id'] ? $this->request->data['Application']['id'] : $id);

                        // check for appropriate hash
                        $application = $this->Application->read();
                        if ($application['Application'] && $application['Application']['hash'] != $hash) {
                            $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                            $this->redirect(array('action' => 'retrieve'));
                        }
                    }

                    // PERFORM VALIDATION
                    if ($application['Application'] && in_array($application['Application']['status'], array('completed', 'signed'))) {
                        $this->request->data = $application;
                        $validation = false;
                    } elseif ($application['Application'] && $application['Application']['status'] != 'saved')
                        $validation = $this->validate_hooza(5, $this->request->data);
                    else
                        $validation = true;

                    if ($validation === true) {
                        // SAVE THE DATA
                        $this->Application->set($this->request->data);
                        //.if ($this->Auth->user('id')) $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->Application->save($this->request->data, array('validate' => false));
                        // REDIRECT TO THE NEXT STEP
                        $this->redirect(array('action' => 'app', 6, $this->Application->id, $hash));
                    } else {
                        $this->set('id', (is_numeric($id) && $id ? $id : $this->request->data['Application']['id']));
                        $this->set('errors', $validation);
                    }
                } elseif ($id && $this->request->is('get')) {
                    $this->Application->id = $id;
                    $this->request->data = $this->Application->read();

                    // check for appropriate hash
                    if ($this->request->data['Application']['hash'] != $hash) {
                        $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                        $this->redirect(array('action' => 'retrieve'));
                    }
                    // check for document expiration
                    if (!$this->Application->expiration($this->request->data) && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Expired');
                        $this->redirect(array('action' => 'expired'));
                    }
                    // check whether application is signed
                    if ($this->request->data['Application']['status'] == 'signed' && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Ben Signed');
                        $this->redirect(array('action' => 'signed'));
                    }
                    // check for invalid fields
                    if ($this->request->data['Application']['status'] != 'saved')
                        $this->set('errors', $validation = $this->validate_hooza(5, $this->request->data));
                }

                $this->render('hooza_5');
                break;
            case 6:
                // ***************
                // PROCESS STEP #6
                // ***************

                if ($this->request->is('post') || $this->request->is('put')) {
                    if (!$id && !$this->request->data['Application']['id']) {
                        // create a new application
                        $this->Application->create();
                        $hash = md5(String::uuid());

                        $this->Application->set(array(
                            'status' => 'saved',
                            'hash' => $hash
                        ));
                        // Set user_id if an authenticated user is creating the app
                        if ($this->Auth->user('id'))
                            $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        $this->set('hash', $hash);
                    }
                    else {

                        $this->Application->id = ($this->request->data['Application']['id'] ? $this->request->data['Application']['id'] : $id);
                        // check for appropriate hash
                        $application = $this->Application->read();
                        if ($application['Application']['hash'] != $hash) {
                            $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                            $this->redirect(array('action' => 'retrieve'));
                        }
                    }

                    // PERFORM VALIDATION
                    /* if (in_array($this->Auth->user('group'), array('admin', 'rep', 'manager'))) {
                      if (in_array($application['Application']['status'], array('completed', 'signed'))) {
                      $this->request->data = $application;
                      $validation = false;
                      }
                      else $validation = true;
                      }
                      else { */
                    if ($this->validate_hooza(1, $application) !== true && in_array($application['Application']['status'], array('saved', 'validate'))) {
                        $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                        $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                        $this->redirect(array('action' => 'app', 1, $this->Application->id, $hash));
                    } elseif ($this->validate_hooza(2, $application) !== true && in_array($application['Application']['status'], array('saved', 'validate'))) {
                        $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                        $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                        $this->redirect(array('action' => 'app', 2, $this->Application->id, $hash));
                    } elseif ($this->validate_hooza(3, $application) !== true && in_array($application['Application']['status'], array('saved', 'validate'))) {
                        $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                        $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                        $this->redirect(array('action' => 'app', 3, $this->Application->id, $hash));
                    } elseif ($this->validate_hooza(4, $application) !== true && in_array($application['Application']['status'], array('saved', 'validate'))) {
                        $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                        $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                        $this->redirect(array('action' => 'app', 4, $this->Application->id, $hash));
                    } elseif ($this->validate_hooza(5, $application) !== true && in_array($application['Application']['status'], array('saved', 'validate'))) {
                        $this->Application->save(array('Application' => array('status' => 'validate')), array('validate' => false));
                        $this->Session->setFlash('The application is incomplete. Please check each page for errors.');
                        $this->redirect(array('action' => 'app', 5, $this->Application->id, $hash));
                    } elseif (in_array($application['Application']['status'], array('completed', 'signed'))) {
                        $this->request->data = $application;
                        $validation = false;
                    }
                    else
                        $validation = $this->validate_hooza(6, $this->request->data);
                    //}

                    if ($validation === true) {
                        // SAVE THE DATA
                        $this->Application->set($this->request->data);
                        // if ($this->Auth->user('id')) $this->Application->set(array('user_id' => $this->Auth->user('id')));
                        if (in_array($application['Application']['status'], array('saved', 'validate')) && !in_array($this->Auth->user('group'), array('admin', 'rep', 'manager')))
                            $this->request->data['Application']['status'] = 'pending';
                        $this->Application->save($this->request->data, array('validate' => false));
                        // REDIRECT TO THE END
                        $this->request->data = $this->Application->read();
                        //if ($this->Auth->user('group') != 'rep' && $this->Auth->user('group') != 'admin') $this->redirect(array('action'=>'end'));
                    }
                    else {
                        $this->set('id', ($id ? $id : $this->request->data['Application']['id']));
                        $this->set('errors', $validation);
                    }
                } elseif ($id) {
                    $this->Application->id = $id;
                    $this->request->data = $this->Application->read();

                    // check for appropriate id/data
                    if (!$id || empty($this->request->data)) {
                        $this->Session->setFlash('That application ID was not found! Please start a new application or retrieve your existing applications.');
                        $this->redirect('/');
                    }

                    // check for appropriate hash
                    if ($this->request->data['Application']['hash'] != $hash) {
                        $this->Session->setFlash('Your token key was invalid. Please re-retrieve your applications for an updated token.');
                        $this->redirect(array('action' => 'retrieve'));
                    }
                    // check for document expiration
                    if (!$this->Application->expiration($this->request->data) && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Expired');
                        //$this->redirect(array('action' => 'expired'));
                    }
                    // check whether application is signed
                    if ($this->request->data['Application']['status'] == 'signed' && !$this->Auth->user('id')) {
                        $this->Session->setFlash('Your Application Has Ben Signed');
                        $this->redirect(array('action' => 'signed'));
                    }
                    // check for invalid fields
                    if ($this->request->data['Application']['status'] != 'saved')
                        $this->set('errors', $validation = $this->validate_hooza(6, $this->request->data));
                }

                $this->render('hooza_6');
                break;
            default:
                $this->redirect(array('action' => 'app', 1));
        }
    }
    /**
     * Proof of concept for testing multipass callback
     * @todo depricate before pushing live
     */
    function multipass_callback() {
            App::uses('CakeEmail', 'Network/Email');
            $Email = new CakeEmail();
    if ($this->request->data) {
            $data = $this->request->data;
            $Email->from(array('newapps@axiapayments.com' => 'Axia Online Applications'));
            $Email->to('stanner@axiapayments.com');
            $Email->subject('multipass');
            if (!$data['Callback']['error']) {
            $Email->send(
                    $data['Callback']['callback_type'] . ' ' .
                    $data['Callback']['guid'] . ' ' .
                    $data['Callback']['Merchant']['merchant_id'] . ' ' .
                    $data['Callback']['Merchant']['device_id'] . ' ' .
                    $data['Callback']['Merchant']['username'] . ' ' .
                    $data['Callback']['Merchant']['pass']
                    );
            } else {
                $Email->send(
                $data['Callback']['callback_type'] . ' ' .
                $data['Callback']['guid'] . ' ' .
                $data['Callback']['error']
                        );
            }
            exit;
    } else {
        $this->Application->Multipass->callbackFailure();
        exit;
    }
    
    /**
     * retrieve multipass data and pass it to client callback if one is present
     * @param array $data
     * @return array $multipassData
     */
    }
    function assignMultipass($data) {
        App::uses('HttpSocket', 'Network/Http');
        $HttpSocket = new HttpSocket();
        $multipassData = $this->Application->Multipass->initializeMultipass($data);
        if (isset($multipassData['Multipass']['merchant_id'])) {
        $multipassArray = array('Callback' => array(
            'callback_type' => 'Multipass',
            'guid' => $data['Application']['guid'], 
            'Merchant' => array(
            'merchant_id' => $multipassData['Multipass']['merchant_id'],
            'device_id' => $multipassData['Multipass']['device_number'], 
            'username' => $multipassData['Multipass']['username'], 
            'pass' => $multipassData['Multipass']['pass']
                ) 
            ));
        $results = $HttpSocket->post($data['Application']['callback_url'], $multipassArray);
        if ($results->code == 200) {return $multipassData;}
        } else {
             $multipassArray = array('Callback' => array(
            'callback_type' => 'Multipass',
            'guid' => $data['Application']['guid'], 
            'error' => 'Multipass Creation Failed'
                ) 
            );
        $results = $HttpSocket->post($data['Application']['callback_url'], $multipassArray);
        if ($results->code == 200) {return $multipassData;}
        }
    }
    
    /**
     * @todo Consider refactoring
     */
    function retrieve() {
        if (!empty($this->request->data['Application']['email'])) {
            $emailStr = $this->request->data['Application']['email'];
            $this->Session->setFlash('Email sent to ' . $emailStr);
        }
        if ($this->request->data['Application']['email']) {
            $applications = Set::combine($this->Application->find(
                                    'all', array(
                                'conditions' => array(
                                    'or' => array(
                                        'corporate_email' => $this->request->data['Application']['email'],
                                        'location_email' => $this->request->data['Application']['email'],
                                        'owner1_email' => $this->request->data['Application']['email'],
                                        'owner2_email' => $this->request->data['Application']['email']
                                    )
                                )
                                    )
                            ), '{n}.Application.id', '{n}.Application');
            if ($applications) {
                // Update the hash
                $hash = md5(String::uuid());
                $this->Application->updateAll(
                        array('hash' => "'" . $hash . "'"), array(
                    'or' => array(
                        'corporate_email' => $this->request->data['Application']['email'],
                        'location_email' => $this->request->data['Application']['email'],
                        'owner1_email' => $this->request->data['Application']['email'],
                        'owner2_email' => $this->request->data['Application']['email']
                    )
                        )
                );
                $this->Email->from = 'Axia Online Applications <' . EmailTimeline::NEWAPPS_EMAIL . '>';
                $this->Email->to = $this->request->data['Application']['email'];
                $this->Email->subject = 'Your Axia Applications';
                $this->Email->sendAs = 'text';
                $this->Email->template = 'retrieve_applications';
                $this->set('email', $this->request->data['Application']['email']);
                $this->set('hash', $hash);
                $this->set('link', Router::url('/applications/index/', true) . urlencode($this->request->data['Application']['email']) . "/{$hash}");
                $this->Email->send();
                $this->render('retrieve_thankyou');
            } else {
                $this->set('error', 'Could not find any applications with the specified email address.');
            }
        }
    }

    // Copy Retreive to work-around bug with sending retrieve from admin console
    
    /**
     * @todo the hash should be constant and unique should it change?
     * @param type $id integer
     */
    function complete_fields($id) {

        if ($id) {
            $this->Application->id = $id;
            $this->request->data = $this->Application->read();


            // Update the hash
            $hash = md5(String::uuid());
            $this->Application->saveField('hash', $hash);
            //$this->Application->save();
            $this->Email->from = 'Axia Online Applications <' . EmailTimeline::NEWAPPS_EMAIL . '>';
            $this->Email->to = $this->request->data['Application']['owner1_email'];
            $this->Email->subject = 'Your Axia Applications';
            $this->Email->sendAs = 'text';
            $this->Email->template = 'retrieve_applications';
            $this->set('email', $this->request->data['Application']['owner1_email']);
            $this->set('dba', $this->request->data['Application']['dba_business_name']);
            $this->set('fullname', $this->request->data['Application']['owner1_fullname']);
            $this->set('hash', $hash);
            $this->set('link', Router::url('/applications/index/', true) . urlencode($this->request->data['Application']['owner1_email']) . "/{$hash}");
            $this->Application->EmailTimeline->create();
            $this->Application->EmailTimeline->save(array('app_id' => $id, 'date' => DboSource::expression('NOW()'), 'subject_id' => EmailTimeline::COMPLETE_FIELDS, 'recipient' => $this->request->data['Application']['owner1_email']));
            $this->Email->send();
            $this->render('retrieve_thankyou');
        } else {
            $this->set('error', 'Could not find any applications with the specified email address.');
        }
    }

    //********* Copied retrieve function to use for rep notify function  *************//

    function rep_notify($id) {
        if ($id) {
            $this->Application->id = $id;
            $this->request->data = $this->Application->read();
            $emailaddress = $this->Application->User->find('all', array('conditions' => array('User.id' => $this->request->data['Application']['user_id'])));

            $this->Email->from = 'Axia Online Applications <' . EmailTimeline::NEWAPPS_EMAIL . '>';
            $this->Email->to = $emailaddress['0']['User']['email'];
            $this->Email->subject = $this->request->data['Application']['dba_business_name'] . '- Online Application Merchant Portion Completed';
            $this->Email->sendAs = 'text';
            $this->Email->template = 'rep_notify';
            $this->set('rep', $emailaddress['0']['User']['email']);
            $this->set('merchant', $this->request->data['Application']['dba_business_name']);
            //$this->set('hash', $hash);
            $this->set('link', Router::url('/users/login', true));
            $this->Application->EmailTimeline->create();
            $this->Application->EmailTimeline->save(array('app_id' => $id, 'date' => DboSource::expression('NOW()'), 'subject_id' => '3', 'recipient' => $emailaddress['0']['User']['email']));
            $this->Email->send();
            $this->set('smtp-errors', $this->Email->smtpError);
            $this->redirect(array('action' => 'end'));
            //$this->render('end');
        }
    }

    function rep_notify_signed($id) {
        if ($id) {
            $this->Application->id = $id;
            $this->request->data = $this->Application->read();


            $this->Email->from = 'Axia Online Applications <' . EmailTimeline::NEWAPPS_EMAIL . '>';
            $this->Email->to = $this->request->data['User']['email'];
            $this->Email->subject = $this->request->data['Application']['dba_business_name'] . ' - Online Application Signed';
            $this->Email->sendAs = 'text';
            $this->Email->template = 'rep_notify_signed';
            $this->set('rep', $this->request->data['User']['email']);
            $this->set('merchant', $this->request->data['Application']['dba_business_name']);
            //$this->set('hash', $hash);
            $this->set('link', Router::url('/users/login', true));
            $this->Application->EmailTimeline->create();
            $this->Application->EmailTimeline->save(array('app_id' => $id, 'date' => DboSource::expression('NOW()'), 'subject_id' => '2', 'recipient' => $this->request->data['User']['email']));
            $this->Email->send();
            $this->set('smtp-errors', $this->Email->smtpError);
            $this->redirect(array('action' => 'end'));
            //$this->render('end');
        }
    }
     /*
     * Email data required to run the multipass macro to data entry
     * 
     * 
     */
    
    function emailMultipassToDataEntry($data, $multipassMerchantId) {
        $csv = $this->Application->multipassCsv($data, $multipassMerchantId);      
        $Email = new CakeEmail();
        $Email->viewVars(
                array(
                    'id' => $data['Multipass']['id'],
                    'merchant_id' => $data['Multipass']['merchant_id'],
                    'application_id'
                )
        );
        $Email->from(array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications'));
        $Email->to(EmailTimeline::DATA_ENTRY_EMAIL);
        $Email->to('stanner@axiapayments.com');
        $Email->subject('New Multipass Merchant ' . $multipassMerchantId);
        $Email->emailFormat('html');
        $Email->template('multipass_complete');
        $Email->attachments(array($csv));
        $Email->send();
        $this->Application->EmailTimeline->create();
        $this->Application->EmailTimeline->save(
                array(
                    'app_id' => $data['Application']['id'],
                    'date' => DboSource::expression('NOW()'),
                    'subject_id' => EmailTimeline::MULTIPASS_COMPLETE,
                    'recipient' => EmailTimeline::DATA_ENTRY_EMAIL
                )
        );
        unlink($csv);
    }

    function email_app($id) {
        if ($id) {
            $this->Application->id = $id;
            $this->request->data = $this->Application->read();

            $this->Email->from = 'Axia Online Applications <' . EmailTimeline::NEWAPPS_EMAIL . '>';
            $this->Email->to = $this->request->data['Application']['owner1_email'];
            $this->Email->subject = $this->request->data['Application']['dba_business_name'] . ' - Merchant Application';
            $this->Email->sendAs = 'both';
            $this->Email->template = 'email_app';
            $this->set('url', "https://" . $_SERVER['SERVER_NAME'] . "/applications/sign_document?guid=" . $this->request->data['Application']['rs_document_guid']);
            $this->set('ownerName', $this->request->data['Application']['owner1_fullname']);
            $this->set('merchant', $this->request->data['Application']['dba_business_name']);
            //$this->set('hash', $hash);
            //$this->set('link', Router::url('/users/login', true));
            $this->Application->EmailTimeline->create();
            $this->Application->EmailTimeline->save(array('app_id' => $id, 'date' => DboSource::expression('NOW()'), 'subject_id' => '4', 'recipient' => $this->request->data['Application']['owner1_email']));
            $this->Email->send();
            //$this->Application->EmailTimeline->saveField('date',DboSource::expression('NOW()'));
            $this->set('smtp-errors', $this->Email->smtpError);
            if ($this->request->data['Application']['owner2_email'] != '') {
                $this->email_app_owner2($this->Application->id);
            } else {
                $this->redirect(array('action' => 'email_end', $id));
            }

            //$this->render('end');
        }
    }

    function email_app_owner2($id) {
        if ($id) {
            $this->Application->id = $id;
            $this->request->data = $this->Application->read();

            $this->Email->from = 'Axia Online Applications <' . EmailTimeline::NEWAPPS_EMAIL . '>';
            $this->Email->to = $this->request->data['Application']['owner2_email'];
            $this->Email->subject = $this->request->data['Application']['dba_business_name'] . ' - Merchant Application';
            $this->Email->sendAs = 'both';
            $this->Email->template = 'email_app_owner2';
            $this->set('url', "https://" . $_SERVER['SERVER_NAME'] . "/applications/sign_document?guid=" . $this->request->data['Application']['rs_document_guid']);
            $this->set('ownerName', $this->request->data['Application']['owner2_fullname']);
            $this->set('merchant', $this->request->data['Application']['dba_business_name']);
            //$this->set('hash', $hash);
            //$this->set('link', Router::url('/users/login', true));
            $this->Application->EmailTimeline->create();
            $this->Application->EmailTimeline->save(array('app_id' => $id, 'date' => DboSource::expression('NOW()'), 'subject_id' => '4', 'recipient' => $this->request->data['Application']['owner2_email']));
            $this->Email->send();
            $this->set('smtp-errors', $this->Email->smtpError);
            $this->redirect(array('action' => 'email_end', $id));
            //$this->render('end');
        }
    }

    function hooza_email_app($id) {
        if ($id) {
            $this->Application->id = $id;
            $this->request->data = $this->Application->read();

            $this->Email->from = 'Axia Online Applications <' . EmailTimeline::NEWAPPS_EMAIL . '>';
            $this->Email->to = $this->request->data['Application']['owner1_email'];
            $this->Email->subject = $this->request->data['Application']['dba_business_name'] . ' - Merchant Application';
            $this->Email->sendAs = 'both';
            $this->Email->template = 'hooza_email_app';
            $this->set('url', "https://" . $_SERVER['SERVER_NAME'] . "/applications/sign_document?guid=" . $this->request->data['Application']['rs_document_guid']);
            $this->set('ownerName', $this->request->data['Application']['owner1_fullname']);
            $this->set('merchant', $this->request->data['Application']['dba_business_name']);
            //$this->set('hash', $hash);
            //$this->set('link', Router::url('/users/login', true));
            $this->Application->EmailTimeline->create();
            $this->Application->EmailTimeline->save(array('app_id' => $id, 'date' => DboSource::expression('NOW()'), 'subject_id' => '5', 'recipient' => $this->request->data['Application']['owner1_email']));
            $this->Email->send();
            $this->set('smtp-errors', $this->Email->smtpError);
            if ($this->request->data['Application']['owner2_email'] != '') {
                $this->hooza_email_app_owner2($this->Application->id);
            } else {
                $this->redirect(array('action' => 'hooza_end', $id));
                //$this->render('end');
            }
        }
    }

    function hooza_email_app_owner2($id) {
        if ($id) {
            $this->Application->id = $id;
            $this->request->data = $this->Application->read();

            $this->Email->from = 'Axia Online Applications <' . EmailTimeline::HOOZA . '>';
            $this->Email->to = $this->request->data['Application']['owner2_email'];
            $this->Email->subject = $this->request->data['Application']['dba_business_name'] . ' - Merchant Application';
            $this->Email->sendAs = 'both';
            $this->Email->template = 'hooza_email_app_owner2';
            $this->set('url', "https://" . $_SERVER['SERVER_NAME'] . "/applications/sign_document?guid=" . $this->request->data['Application']['rs_document_guid']);
            $this->set('ownerName', $this->request->data['Application']['owner2_fullname']);
            $this->set('merchant', $this->request->data['Application']['dba_business_name']);
            //$this->set('hash', $hash);
            //$this->set('link', Router::url('/users/login', true));
            $this->Application->EmailTimeline->create();
            $this->Application->EmailTimeline->save(array('app_id' => $id, 'date' => DboSource::expression('NOW()'), 'subject_id' => '5', 'recipient' => $this->request->data['Application']['owner2_email']));
            $this->Email->send();
            $this->set('smtp-errors', $this->Email->smtpError);
            //$this->redirect(array('action' => 'hooza_end', $id));
            $this->render('end');
        }
    }

    function hooza_email_followup($id) {
        if ($id) {
            $this->Application->id = $id;
            $data = $this->Application->read();

            $this->Email->from = 'Axia Online Applications <' . EmailTimeline::HOOZA_EMAIL . '>';
            $this->Email->to = $data['Application']['owner1_email'];
            $this->Email->subject = $data['Application']['dba_business_name'] . ' - Merchant Application Next Steps.';
            $this->Email->sendAs = 'both';
            $this->Email->template = 'hooza_email_followup';
            $this->set('ownerName', $data['Application']['owner1_fullname']);
            $this->set('merchant', $data['Application']['dba_business_name']);
            //$this->set('hash', $hash);
            //$this->set('link', Router::url('/users/login', true));
            $this->Application->EmailTimeline->create();
            $this->Application->EmailTimeline->save(array('app_id' => $id, 'date' => DboSource::expression('NOW()'), 'subject_id' => EmailTimeline::HZ_FOLLOWUP, 'recipient' => $data['Application']['owner1_email']));
            $this->Email->send();
            $this->set('smtp-errors', $this->Email->smtpError);
            $this->redirect(array('action' => 'hooza_end', $id));
            //$this->render('end');
        }
    }

    function email_coversheet($id) {
        if ($id) {
            $this->Application->Coversheet->sendCoversheet($id);
            unlink(WWW_ROOT . '/files/axia_' . $id . '_coversheet.pdf');

        }
    }

    function index($email, $hash) {
        if (!$email || !$hash) {
            header("HTTP/1.0 403 Forbidden");
            exit;
        }

        $applications = Set::combine($this->Application->find(
                                'all', array(
                            'conditions' => array(
                                'or' => array(
                                    'corporate_email' => $email,
                                    'location_email' => $email,
                                    'owner1_email' => $email,
                                    'owner2_email' => $email
                                ),
                                'hash' => $hash
                            )
                                )
                        ), '{n}.Application.id', '{n}.Application');

        if ($applications) {
            $this->set('email', $email);
            $this->set('hash', $hash);
            $this->set('applications', $applications);
        } else {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
    }

    function end() {
        // ...
    }

    function pdf_gen($id) {
        if ($id) {
            $this->Application->id = $id;
            $data = $this->Application->read();
            $this->set('data', $data);
            //debug($data);
            //$xml = $pdf->element('pdf_export');
            $path = WWW_ROOT . 'files/';
            $fp = (fopen($path . 'axia_app.xfdf', 'w'));
            fwrite($fp, $this->render('/Elements/applications/pdf_export', false));
            fclose($fp);

            //echo $xml;
            //debug($xml);
            exec('pdftk ' . $path . 'axia_app.pdf fill_form ' . $path . 'axia_app.xfdf output ' . $path . 'axia_' . $data['Application']['id'] . '_final.pdf flatten');
            unlink($path . 'axia_coversheet.xfdf');
            $this->redirect(array('action' => 'pdf', $id));
        }
    }

    function pdf($id) {
        if ($id) {
            $this->set('id', $id);

            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="axia_' . $id . '_final.pdf"');
            readfile(WWW_ROOT . 'files/axia_' . $id . '_final.pdf');
            unlink(WWW_ROOT . '/files/axia_' . $id . '_final.pdf');
        }
    }

    function hooza_end($id) {
        if ($id) {
            $this->Application->id = $id;
            $this->request->data = $this->Application->read();
            $this->layout = 'default_hz';
            $this->set('owner1name', $this->request->data['Application']['owner1_fullname']);
            $this->set('owner2name', $this->request->data['Application']['owner2_fullname']);
            $this->set('owner1email', $this->request->data['Application']['owner1_email']);
            $this->set('owner2email', $this->request->data['Application']['owner2_email']);
        }
    }

    function email_end($id) {
        if ($id) {
            $this->Application->id = $id;
            $this->request->data = $this->Application->read();
            $this->set('owner1name', $this->request->data['Application']['owner1_fullname']);
            $this->set('owner2name', $this->request->data['Application']['owner2_fullname']);
            $this->set('owner1email', $this->request->data['Application']['owner1_email']);
            $this->set('owner2email', $this->request->data['Application']['owner2_email']);
        }
    }

    function prepackage_template() {
        App::import('Vendor', 'oauth', array('file' => 'OAuth' . DS . 'rightsignature.php'));
        $rightsignature = $this->createConsumer();
        //$rightsignature->access_token = new OAuthConsumer($this->settings['OAUTH_TOKEN_KEY'], $this->settings['OAUTH_TOKEN_SECRET'], 1);
        $rightsignature->access_token = new OAuthConsumer('FvpRze1k6JbP7HHm64IxQiWLHL9p0Jl4pw3x7PBP', 'cHrzepxhF7t9QMyO8CGUJlbSg4Lon23JEVYnD70Z', 1);
        /*
          $response = $rightsignature->post('/api/templates/' . $this->settings['RS_TEMPLATE_GUID'] . '/prepackage.json', "<?xml version='1.0' encoding='UTF-8'?><callback_location></callback_location>");
         */

        //4 signer app Template GUID

        $response = $rightsignature->post('/api/templates/' . $this->settings['a_994126_1efe7bc5413f492fa9cef482e9bb4e8b'] . '/prepackage.json', "<?xml version='1.0' encoding='UTF-8'?><callback_location></callback_location>");

        $data = json_decode($response, true);

        if ($data && $data['template']['type'] == 'Document' && $data['template']['guid']) {
            echo "Document guid: " . $data['template']['guid'];
        } else {
            echo 'error! could not find document guid';
            exit;
        }

        $this->autoRender = false;
    }

    function install_sheet_var($id) {
        $data = $this->Application->find(
                'first', array(
            'conditions' => array(
                'Application.id' => $id
            ),
            'contain' => array(
                'User', 'Merchant' => array('EquipmentProgramming'),
            ),
            'recursive' => 2)
        );
        $this->set('data', $data);
        if ($this->request->data) {
            $this->Application->set($this->request->data);
            if (!empty($this->request->data['Application']['select_email_address'])) {
                $this->Application->setValidation('install_var_select');
            } else {
                $this->Application->setValidation('install_var_enter');
            }

            if ($this->Application->validates()) {
                $this->sent_var_install($id);
            } else {

                $errors = $this->Application->invalidFields();
                $this->set('errors', $errors);
            }
        }
    }

    function sent_var_install($id) {
        $data = $this->Application->find(
                'first', array(
            'conditions' => array(
                'Application.id' => $id
            ),
            'contain' => array(
                'User', 'Merchant' => array('EquipmentProgramming'),
            ),
            'recursive' => 2)
        );


        App::import('Vendor', 'oauth', array('file' => 'OAuth' . DS . 'rightsignature.php'));
        $rightsignature = $this->createConsumer();
        //$rightsignature->access_token = new OAuthConsumer($this->settings['OAUTH_TOKEN_KEY'], $this->settings['OAUTH_TOKEN_SECRET'], 1);
        $rightsignature->access_token = new OAuthConsumer('FvpRze1k6JbP7HHm64IxQiWLHL9p0Jl4pw3x7PBP', 'cHrzepxhF7t9QMyO8CGUJlbSg4Lon23JEVYnD70Z', 1);
        //$rightsignature = new RightSignature('HbrHc09UP40iLLMv8kneQTzUTSdsGwpFPGlbvdsa', 'gpkSEoWrVRYgb6EjeHf1Syw1ucfjiWe0d1D1lQks');
        //$rightsignature->access_token = new OAuthConsumer('INq3XUDpo0Pr4rMogpJ3g95tPxlh4vwm1s8ikdiG', 'FHwaomX0XLmAx3p2rK76HbivPxHH1LJ1BIDgzZa4', 1);
        // prepare/package the document template
        //echo 'I did Something';
        /*
          $response = $rightsignature->post('/api/templates/' . $this->settings['RS_TEMPLATE_GUID'] . '/prepackage.json', "<?xml version='1.0' encoding='UTF-8'?><callback_location></callback_location>");
         */

        $response = $rightsignature->post('/api/templates/' . 'a_1370816_63b4c6fa71634ac5b2d35a9a69a7e64d' . '/prepackage.json', "<?xml version='1.0' encoding='UTF-8'?><callback_location></callback_location>");
        //echo 'Some Response';
        $response = json_decode($response, true);
        //echo '<br />This is the Response String: ' . $response;
        if ($response && $response['template']['type'] == 'Document' && $response['template']['guid']) {
            // send the document

            $this->set('document_guid', $response['template']['guid']);
            $this->set('data', $data);
            //if ($signNow)
            $xml = $this->render('/Elements/applications/send_var_install', false);
            //else //If not simply send the documents
            //$xml = $this->render('/Elements/applications/send_document', false);

            $response = $rightsignature->post('/api/templates.json', $xml);
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

    function email_var_install($id) {
        if ($id) {
            $this->Application->id = $id;
            $this->request->data = $this->Application->read();
            $email = $this->Session->read('Application.email');

            $this->Email->from = 'Axia Online Applications <newapps@axiapayments.com>';
            $this->Email->to = $email;
            $this->Email->subject = $this->request->data['Application']['dba_business_name'] . ' - Install Sheet';
            $this->Email->sendAs = 'both';
            $this->Email->template = 'email_install_var';
            $this->set('url', "https://" . $_SERVER['SERVER_NAME'] . "/applications/sign_document?guid=" . $this->request->data['Application']['install_var_rs_document_guid']);
            $this->set('ownerName', $this->request->data['Application']['corp_contact_name']);
            $this->set('merchant', $this->request->data['Application']['dba_business_name']);
            //$this->set('hash', $hash);
            //$this->set('link', Router::url('/users/login', true));
            $this->Email->send();
            $this->Application->EmailTimeline->create();
            $this->Application->EmailTimeline->save(array('app_id' => $id, 'date' => DboSource::expression('NOW()'), 'subject_id' => '7', 'recipient' => $email));
            //$this->Application->EmailTimeline->saveField('date',DboSource::expression('NOW()'));
            $this->set('smtp-errors', $this->Email->smtpError);

            //$this->render('end');
        }
    }

    function var_success() {
        $email = $this->Session->read('Application.email');
        $this->Session->setFlash('Install sheet Successfully sent to: ' . $email);
    }

    protected function createConsumer() {
        return new RightSignature($this->settings['OAUTH_KEY'], $this->settings['OAUTH_SECRET']);
        // return new RightSignature('J7PQlPSlm3jaa2DbfCP989mIFrKRHUH1NqcjJugT', 'ZAYx4jEy6BVYPuad4kPQAw6lTrOxAeqWU8DGT6A1');
    }

    /*
      function view_pdf($id = null) {
      if (!$id) {
      $this->Session->setFlash('Sorry, there was no PDF selected.');
      $this->redirect(array('action' => 'index'), null, true);
      }
      $this->set('id', $this->Application->id);
      $this->layout = 'pdf'; //this will use the pdf.ctp layout
      $this->render();
      }
     */

    protected function validate_steps($step, $data) {
        switch ($step) {
            case 1:
                // ****************
                // VALIDATE STEP #1
                // ****************
                // prep validation for special fields
                if (!isset($data['Application']['ownership_type']))
                    $data['Application']['ownership_type'] = null;
                if (!isset($data['Application']['location_type']))
                    $data['Application']['location_type'] = null;

                $this->Application->set($data);

                // pre-validation for specific fields
                if ($data['Application']['existing_axia_merchant'] == 'yes' && !$data['Application']['current_mid_number'])
                    $this->Application->invalidate('current_mid_number');
                if ($data['Application']['merchant_status'] == 'leases' && !$data['Application']['landlord_name'])
                    $this->Application->invalidate('landlord_name');
                if ($this->Application->validates(array('fieldList' => array('ownership_type', 'legal_business_name', 'mailing_address', 'mailing_city', 'mailing_state', 'mailing_zip', 'mailing_phone', 'mailing_fax', 'corp_contact_name', 'corp_contact_name_title', 'corporate_email', 'dba_business_name', 'location_address', 'location_city', 'location_state', 'location_zip', 'location_phone', 'location_fax', 'loc_contact_name', 'loc_contact_name_title', 'location_email', 'federal_taxid', 'website', 'customer_svc_phone', 'bus_open_date', 'length_current_ownership', 'existing_axia_merchant', 'location_type')))) {
                    return true;
                }

                else
                    return $this->Application->invalidFields();

                break;
            case 2:
                // ****************
                // VALIDATE STEP #2
                // ****************
                // check for card not present
                if ($data['Application']['card_not_present_keyed'] > 0 || $data['Application']['card_not_present_internet'] > 0) {
                    $data['Application']['rep_mail_tel_activity'] = 'yes';
                }

                $noMoto = array('business_type', 'products_services_sold', 'return_policy', 'days_until_prod_delivery', 'monthly_volume', 'average_ticket', 'highest_ticket', 'current_processor', 'card_present_swiped', 'card_present_imprint', 'card_not_present_keyed', 'card_not_present_internet', 'method_total', 'direct_to_customer', 'direct_to_business', 'direct_to_govt', 'products_total');
                if ($data['Application']['card_not_present_keyed'] + $data['Application']['card_not_present_internet'] >= '30') {
                    $moto = array(
                    'moto_storefront_location',
                    'moto_orders_at_location',
                    'moto_inventory_housed',
                    'moto_policy_full_up_front',
                    'moto_policy_partial_up_front',
                    'moto_policy_after',
                    'moto_policy_days_until_delivery',
                    'moto_policy_partial_with',
                    'moto_policy_days_until_final',
                    'billing_delivery_policy',
                );
                    $stepTwoList = array_merge($noMoto, $moto);
                } else {
                    $stepTwoList = $noMoto;
                }
                $this->Application->set($data);
                if ($this->Application->validates(array('fieldList' => $stepTwoList))) {
                    return true;
                }
                else
                    return $this->Application->invalidFields();

                break;
            case 3:
                // ****************
                // VALIDATE STEP #3
                // ****************
                // pre-validation for specific fields
                if ($data['Application']['monthly_volume'] > 1000000 || $data['Application']['average_ticket'] > 2000) {
                    if (!$data['Application']['trade2_business_name'])
                        $this->Application->invalidate('trade2_business_name');
                    if (!$data['Application']['trade2_contact_person'])
                        $this->Application->invalidate('trade2_contact_person');
                    if (!$data['Application']['trade2_phone'])
                        $this->Application->invalidate('trade2_phone');
                    if (!$data['Application']['trade2_acct_num'])
                        $this->Application->invalidate('trade2_acct_num');
                    if (!$data['Application']['trade2_city'])
                        $this->Application->invalidate('trade2_city');
                    if (!$data['Application']['trade2_state'])
                        $this->Application->invalidate('trade2_state');
                }

                $this->Application->set($data);
                if ($this->Application->validates(array('fieldList' => array('bank_name','bank_zip', 'depository_routing_number', 'depository_account_number', 'fees_routing_number', 'fees_account_number', 'trade1_business_name', 'trade1_contact_person', 'trade1_phone', 'trade1_acct_num', 'trade1_city', 'trade1_state')))) {
                    return true;
                }
                else
                    return $this->Application->invalidFields();

                break;
            case 4:
                // ****************
                // VALIDATE STEP #4
                // ****************
                // prep validation for special fields
                if (!isset($data['Application']['currently_accept_amex']))
                    $data['Application']['currently_accept_amex'] = null;

                $this->Application->set($data);

                // pre-validation for specific fields
                if ($data['Application']['currently_accept_amex'] == 'yes' && !$data['Application']['existing_se_num'])
                    $this->Application->invalidate('existing_se_num');
                elseif ($data['Application']['currently_accept_amex'] == 'no' && !$data['Application']['want_to_accept_amex'])
                    $this->Application->invalidate('want_to_accept_amex');
                if ($data['Application']['term1_use_autoclose'] == 'yes' && !$data['Application']['term1_what_time'])
                    $this->Application->invalidate('term1_what_time');
                if ($data['Application']['term1_accept_debit'] == 'yes' && !$data['Application']['term1_pin_pad_type'])
                    $this->Application->invalidate('term1_pin_pad_type');
                if ($data['Application']['term1_accept_debit'] == 'yes' && !$data['Application']['term1_pin_pad_qty'])
                    $this->Application->invalidate('term1_pin_pad_qty');
                if ($data['Application']['term2_quantity'] && !is_numeric($data['Application']['term2_quantity']))
                    $this->Application->invalidate('term2_quantity');
                elseif ($data['Application']['term2_quantity'] && is_numeric($data['Application']['term2_quantity']) && $data['Application']['term2_quantity'] > 0) {
                    // validation for (optional) term2
                    if (!$data['Application']['term2_type'])
                        $this->Application->invalidate('term2_type');
                    if (!$data['Application']['term2_provider'])
                        $this->Application->invalidate('term2_provider');

                    if (!$data['Application']['term2_use_autoclose'])
                        $this->Application->invalidate('term2_use_autoclose');
                    elseif ($data['Application']['term2_use_autoclose'] == 'yes' && !$data['Application']['term2_what_time'])
                        $this->Application->invalidate('term2_what_time');

                    if (!$data['Application']['term2_accept_debit'])
                        $this->Application->invalidate('term2_accept_debit');
                    else {
                        if ($data['Application']['term2_accept_debit'] == 'yes' && !$data['Application']['term2_pin_pad_type'])
                            $this->Application->invalidate('term2_pin_pad_type');
                        if ($data['Application']['term2_accept_debit'] == 'yes' && !$data['Application']['term2_pin_pad_qty'])
                            $this->Application->invalidate('term2_pin_pad_qty');
                    }
                }

                if ($this->Application->validates(array('fieldList' => array('currently_accept_amex', 'want_to_accept_discover', 'term1_type', 'term1_quantity', 'term1_provider', 'term1_use_autoclose', 'term1_accept_debit')))) {
                    return true;
                }
                else
                    return $this->Application->invalidFields();

                break;
            case 5:
                // ****************
                // VALIDATE STEP #5
                // ****************
                // pre-validation for specific fields
                if ($data['Application']['owner1_percentage'] && $data['Application']['owner1_percentage'] < 50) {
                    if (!$data['Application']['owner2_percentage'])
                        $this->Application->invalidate('owner2_percentage');
                    if (!$data['Application']['owner2_fullname'])
                        $this->Application->invalidate('owner2_fullname');
                    if (!$data['Application']['owner2_title'])
                        $this->Application->invalidate('owner2_title');
                    if (!$data['Application']['owner2_address'])
                        $this->Application->invalidate('owner2_address');
                    if (!$data['Application']['owner2_city'])
                        $this->Application->invalidate('owner2_city');
                    if (!$data['Application']['owner2_state'])
                        $this->Application->invalidate('owner2_state');
                    if (!$data['Application']['owner2_zip'])
                        $this->Application->invalidate('owner2_zip');
                    if (!$data['Application']['owner2_phone'])
                        $this->Application->invalidate('owner2_phone');
                    if (!$data['Application']['owner2_email'])
                        $this->Application->invalidate('owner2_email');
                    if (!$data['Application']['owner2_ssn'])
                        $this->Application->invalidate('owner2_ssn');
                    if (!$data['Application']['owner2_dob'])
                        $this->Application->invalidate('owner2_dob');
                }

                $this->Application->set($data);
                if ($this->Application->validates(array('fieldList' => array('owner1_percentage', 'owner1_fullname', 'owner1_title', 'owner1_address', 'owner1_city', 'owner1_state', 'owner1_zip', 'owner1_phone', 'owner1_email', 'owner1_ssn', 'owner1_dob')))) {
                    return true;
                }
                else
                    return $this->Application->invalidFields();

                break;
            case 6:
                // ****************
                // VALIDATE STEP #6
                // ****************
                $this->Application->set($data);
                if (in_array($this->Auth->user('group'), array('admin', 'rep', 'manager'))) {
                    $fields = array('referral1_business', 'referral1_owner_officer', 'referral1_phone', 'referral2_business', 'referral2_owner_officer', 'referral2_phone', 'referral3_business', 'referral3_owner_officer', 'referral3_phone', 'fees_rate_discount', 'fees_rate_structure', 'fees_qualification_exemptions', 'fees_startup_application', 'fees_auth_transaction', 'fees_monthly_statement', 'fees_misc_annual_file', 'fees_startup_equipment', 'fees_auth_amex', 'fees_monthly_minimum', 'fees_misc_chargeback', 'fees_startup_expedite', 'fees_auth_aru_voice', 'fees_monthly_debit_access', 'fees_startup_reprogramming', 'fees_auth_wireless', 'fees_monthly_ebt', 'fees_startup_training', 'fees_monthly_gateway_access', 'fees_startup_wireless_activation', 'fees_monthly_wireless_access', 'fees_startup_tax', 'fees_startup_total', 'fees_pin_debit_auth', 'fees_ebt_discount', 'fees_pin_debit_discount', 'fees_ebt_auth', 'rep_contractor_name', 'rep_amex_discount_rate');
                }
                else
                    $fields = array('referral1_business', 'referral1_owner_officer', 'referral1_phone', 'referral2_business', 'referral2_owner_officer', 'referral2_phone', 'referral3_business', 'referral3_owner_officer', 'referral3_phone');

                if ($this->Application->validates(array('fieldList' => $fields))) {
                    return true;
                }
                else
                    return $this->Application->invalidFields();

                break;
        }
    }

    // **************************
    // VALIDATE HOOZA APPLICATION
    // Different from Regular App
    // **************************
    // Added 3-19-2011 SJT
    protected function validate_hooza($hooza, $data) {
        $this->Application->setValidation('app');
        switch ($hooza) {

            case 1:
                // ****************
                // VALIDATE STEP #1
                // ****************
                // prep validation for special fields
                if (!isset($data['Application']['ownership_type']))
                    $data['Application']['ownership_type'] = null;
                if (!isset($data['Application']['location_type']))
                    $data['Application']['location_type'] = null;

                $this->Application->set($data);

                // pre-validation for specific fields
                //if ($data['Application']['existing_axia_merchant'] == 'yes' && !$data['Application']['current_mid_number']) $this->Application->invalidate('current_mid_number');
                //if ($data['Application']['merchant_status'] == 'leases' && !$data['Application']['landlord_name']) $this->Application->invalidate('landlord_name');
                if ($this->Application->validates(array('fieldList' => array('ownership_type', 'legal_business_name', 'mailing_address', 'mailing_city', 'mailing_state', 'mailing_zip', 'mailing_phone', 'mailing_fax', 'corp_contact_name', 'corp_contact_name_title', 'corporate_email', 'dba_business_name', 'location_address', 'location_city', 'location_state', 'location_zip', 'location_phone', 'location_fax', 'loc_contact_name', 'loc_contact_name_title', 'location_email', 'federal_taxid', 'website', 'customer_svc_phone', 'bus_open_date', 'length_current_ownership')))) {
                    return true;
                }

                else
                    return $this->Application->invalidFields();

                break;
            case 2:
                // ****************
                // VALIDATE STEP #2
                // ****************
                // check for card not present
                //if ($data['Application']['card_not_present_keyed'] > 0 || $data['Application']['card_not_present_internet'] > 0) $data['Application']['rep_mail_tel_activity'] = 'yes';              
                $this->Application->set($data);
                if ($this->Application->validates(array('fieldList' => array('products_services_sold', 'return_policy', 'days_until_prod_delivery', 'monthly_volume', 'average_ticket', 'highest_ticket', 'direct_to_customer', 'direct_to_business', 'direct_to_govt', 'products_total', 'moto_storefront_location', 'moto_orders_at_location', 'moto_inventory_housed', 'moto_policy_full_up_front', 'moto_policy_partial_up_front', 'moto_policy_after', 'moto_policy_days_until_delivery')))) {
                    return true;
                }
                else
                    return $this->Application->invalidFields();

                break;
            case 3:
                // ****************
                // VALIDATE STEP #3
                // ****************
                // pre-validation for specific fields
                if ($data['Application']['monthly_volume'] > 1000000 || $data['Application']['average_ticket'] > 2000) {
                    if (!$data['Application']['trade2_business_name'])
                        $this->Application->invalidate('trade2_business_name');
                    if (!$data['Application']['trade2_contact_person'])
                        $this->Application->invalidate('trade2_contact_person');
                    if (!$data['Application']['trade2_phone'])
                        $this->Application->invalidate('trade2_phone');
                    if (!$data['Application']['trade2_acct_num'])
                        $this->Application->invalidate('trade2_acct_num');
                    if (!$data['Application']['trade2_city'])
                        $this->Application->invalidate('trade2_city');
                    if (!$data['Application']['trade2_state'])
                        $this->Application->invalidate('trade2_state');
                }

                $this->Application->set($data);
                if ($this->Application->validates(array('fieldList' => array('bank_name', 'depository_routing_number', 'depository_account_number', 'fees_routing_number', 'fees_account_number')))) {
                    return true;
                }
                else
                    return $this->Application->invalidFields();

                break;
            case 4:
                // ****************
                // VALIDATE STEP #4
                // ****************
                // prep validation for special fields
                if (!isset($data['Application']['currently_accept_amex']))
                    $data['Application']['currently_accept_amex'] = null;

                $this->Application->set($data);

                // pre-validation for specific fields
                if ($data['Application']['want_to_accept_amex'] == 'yes')
                    $this->Application->saveField('rep_amex_discount_rate', '3.50%');
                elseif ($data['Application']['currently_accept_amex'] == 'no' && !$data['Application']['want_to_accept_amex'])
                    $this->Application->invalidate('want_to_accept_amex');
                if ($data['Application']['term1_use_autoclose'] == 'yes' && !$data['Application']['term1_what_time'])
                    $this->Application->invalidate('term1_what_time');
                if ($data['Application']['term1_accept_debit'] == 'yes' && !$data['Application']['term1_pin_pad_type'])
                    $this->Application->invalidate('term1_pin_pad_type');
                if ($data['Application']['term1_accept_debit'] == 'yes' && !$data['Application']['term1_pin_pad_qty'])
                    $this->Application->invalidate('term1_pin_pad_qty');
                if ($data['Application']['term2_quantity'] && !is_numeric($data['Application']['term2_quantity']))
                    $this->Application->invalidate('term2_quantity');
                elseif ($data['Application']['term2_quantity'] && is_numeric($data['Application']['term2_quantity']) && $data['Application']['term2_quantity'] > 0) {
                    // validation for (optional) term2
                    if (!$data['Application']['term2_type'])
                        $this->Application->invalidate('term2_type');
                    if (!$data['Application']['term2_provider'])
                        $this->Application->invalidate('term2_provider');

                    if (!$data['Application']['term2_use_autoclose'])
                        $this->Application->invalidate('term2_use_autoclose');
                    elseif ($data['Application']['term2_use_autoclose'] == 'yes' && !$data['Application']['term2_what_time'])
                        $this->Application->invalidate('term2_what_time');

                    if (!$data['Application']['term2_accept_debit'])
                        $this->Application->invalidate('term2_accept_debit');
                    else {
                        if ($data['Application']['term2_accept_debit'] == 'yes' && !$data['Application']['term2_pin_pad_type'])
                            $this->Application->invalidate('term2_pin_pad_type');
                        if ($data['Application']['term2_accept_debit'] == 'yes' && !$data['Application']['term2_pin_pad_qty'])
                            $this->Application->invalidate('term2_pin_pad_qty');
                    }
                }

                if ($this->Application->validates(array('fieldList' => array('want_to_accept_amex')))) {
                    return true;
                }
                else
                    return $this->Application->invalidFields();

                break;
            case 5:
                // ****************
                // VALIDATE STEP #5
                // ****************
                // pre-validation for specific fields
                if ($data['Application']['owner1_percentage'] && $data['Application']['owner1_percentage'] < 50) {
                    if (!$data['Application']['owner2_percentage'])
                        $this->Application->invalidate('owner2_percentage');
                    if (!$data['Application']['owner2_fullname'])
                        $this->Application->invalidate('owner2_fullname');
                    if (!$data['Application']['owner2_title'])
                        $this->Application->invalidate('owner2_title');
                    if (!$data['Application']['owner2_address'])
                        $this->Application->invalidate('owner2_address');
                    if (!$data['Application']['owner2_city'])
                        $this->Application->invalidate('owner2_city');
                    if (!$data['Application']['owner2_state'])
                        $this->Application->invalidate('owner2_state');
                    if (!$data['Application']['owner2_zip'])
                        $this->Application->invalidate('owner2_zip');
                    if (!$data['Application']['owner2_phone'])
                        $this->Application->invalidate('owner2_phone');
                    if (!$data['Application']['owner2_email'])
                        $this->Application->invalidate('owner2_email');
                    if (!$data['Application']['owner2_ssn'])
                        $this->Application->invalidate('owner2_ssn');
                    if (!$data['Application']['owner2_dob'])
                        $this->Application->invalidate('owner2_dob');
                }
                $this->Application->set($data);
                if ($this->Application->validates(array('fieldList' => array('owner1_percentage', 'owner1_fullname', 'owner1_title', 'owner1_address', 'owner1_city', 'owner1_state', 'owner1_zip', 'owner1_phone', 'owner1_email', 'owner1_ssn', 'owner1_dob')))) {
                    return true;
                }
                else
                    return $this->Application->invalidFields();

                break;
            case 6:
                // ****************
                // VALIDATE STEP #6
                // ****************
                $this->Application->set($data);

                if (in_array($this->Auth->user('group'), array('admin', 'rep', 'manager'))) {
                    $fields = array('referral1_business', 'referral1_owner_officer', 'referral1_phone', 'referral2_business', 'referral2_owner_officer', 'referral2_phone', 'referral3_business', 'referral3_owner_officer', 'referral3_phone', 'fees_rate_discount', 'fees_rate_structure', 'fees_qualification_exemptions', 'fees_startup_application', 'fees_auth_transaction', 'fees_monthly_statement', 'fees_misc_annual_file', 'fees_startup_equipment', 'fees_auth_amex', 'fees_monthly_minimum', 'fees_misc_chargeback', 'fees_startup_expedite', 'fees_auth_aru_voice', 'fees_monthly_debit_access', 'fees_startup_reprogramming', 'fees_auth_wireless', 'fees_monthly_ebt', 'fees_startup_training', 'fees_monthly_gateway_access', 'fees_startup_wireless_activation', 'fees_monthly_wireless_access', 'fees_startup_tax', 'fees_startup_total', 'fees_pin_debit_auth', 'fees_ebt_discount', 'fees_pin_debit_discount', 'fees_ebt_auth', 'rep_contractor_name', 'rep_amex_discount_rate');
                }
                $fields = array('referral1_business', 'referral1_owner_officer', 'referral1_phone', 'referral2_business', 'referral2_owner_officer', 'referral2_phone', 'referral3_business', 'referral3_owner_officer', 'referral3_phone');
                if ($this->Application->validates(array('fieldList' => $fields))) {
                    return true;
                }
                else
                    return $this->Application->invalidFields();

                break;
        }
    }

}

?>