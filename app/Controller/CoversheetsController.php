<?php
App::uses('User', 'Model');
App::uses('EmailTimeline', 'Model');
App::uses('CakeEmail', 'Network/Email');
class CoversheetsController extends AppController {

        public $components = array('Security','Search.Prg');
        public $helpers = array('Js'); 
        public $permissions = array(
            'admin_index' => array('rep','admin','manager'),
            'admin_search' => array('rep','admin','manager'),
            'add' => array('rep','admin','manager'),
            'edit' => array('rep','admin','manager'),
            'pdf_gen' => array('rep','admin','manager'),
            'email_coversheet' => array('rep','admin','manager'),
//            'delete' => array('rep','admin','manager')
            );
        /**
         * @todo Figure out why search does not work
         */
        function beforeFilter() {
            parent::beforeFilter();
            $this->Security->unlockedActions = array('admin_search');
////            if (!empty($this->Coversheet->id)&& $this->Session->read('Application.coversheet') == 'pdf') $this->Auth->allow(array('pdf','word'));
            //$this->Security->validatePost = false;
            //$this->Security->allowedControllers = array('Applications', 'Users');
        }

	public function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid coversheet'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('Coversheet', $this->Coversheet->read(null, $id));
	}

	public function add($oid = null,$uid = null,$id = null) {
   
            if (!$id && empty($this->request->data)) {
			$this->Coversheet->create();
                            $this->Coversheet->set(array(
                                'onlineapp_application_id' => $oid,
                                'user_id' => $uid,
                                'status' => 'saved'
                                
                        ));
                            
			if ($this->Coversheet->save()) {
				$this->Session->setFlash(__('The coversheet has been created'));
				//$this->redirect(array('action' => 'add'));
                                $id = $this->Coversheet->id;
                                $this->redirect('/coversheets/edit/' . $this->Coversheet->id);
			} else {
				$this->Session->setFlash(__('The coversheet could not be saved. Please, try again.'));
			}
		}
                if ($this->request->is('post')) {
			if ($this->Coversheet->save($this->request->data)) {
				$this->Session->setFlash(__('The coversheet has been saved'));
				$this->redirect(array('controller' => 'applications', 'action' => 'index', 'admin' => true));
			} else {
				$this->Session->setFlash(__('The coversheet could not be saved. Please, try again.'));
                                
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->Coversheet->findById($id);
		}
		$applications = $this->Coversheet->Application->read(null,$oid);
		$users = $this->Coversheet->User->read(null,$uid);
		$this->set(compact('applications', 'users'));
	}
        
	public function edit($id = null) {
            $data = $this->Coversheet->findById($id);
            $this->set(compact('id','data'));
            $moto = false;
            $result = '';
            if ($data['Application']['card_not_present_keyed'] + $data['Application']['card_not_present_internet'] >= '30') $moto = true;
            switch ($moto) {
                case true:
                    if ($data['Application']['monthly_volume'] < '150000' && $data['Application']['average_ticket'] < '1000') {
                        $result = 'tier2';
                    } else if ($data['Application']['monthly_volume'] < '150000' && $data['Application']['average_ticket'] > '1000') {
                        $result = 'tier4';
                    } else {
                        $result = 'tier5';
                    }   
                    break;
                case false:
                    if ($data['Application']['monthly_volume'] < '250000' && $data['Application']['average_ticket'] < '1000') {
                        $result = 'tier1';
                    } else if ($data['Application']['monthly_volume'] > '250000' && $data['Application']['average_ticket'] < '1000') {
                        $result = 'tier3';
                    } else if ($data['Application']['monthly_volume'] < '250000' && $data['Application']['average_ticket'] > '1000') {
                        $result = 'tier4';
                    } else {
                        $result = 'tier5';
                    }   
                    break;
            }
                $this->set('tier',$result);
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__('Invalid coversheet'));
			$this->redirect(array('action' => 'index'));
		}
		if ($id && !empty($this->request->data)) {
                    if(isset($this->request->data['save'])){
                        if ($this->request->data['Coversheet']['gateway_package'] != 'gold') {
                            $this->request->data['Coversheet']['gateway_gold_subpackage'] = '';
                        }
			if ($this->Coversheet->save($this->request->data, array('validate' => false))) {
				$this->Session->setFlash(__('The coversheet has been saved'));
				$this->redirect(array('controller' => 'applications', 'action' => 'index', 'admin' => true));
			} else {
				$this->Session->setFlash(__('The coversheet could not be saved. Please, try again.'));
			}
                    } elseif(isset($this->request->data['uw'])){
                        $this->Coversheet->set(array('status' => 'validated','debit' => $data['Application']['term1_accept_debit'],'moto' => $data['Application']['business_type']));
                        if ($this->request->data['Coversheet']['gateway_package'] != 'gold') {
                            $this->request->data['Coversheet']['gateway_gold_subpackage'] = '';
                        }
			if ($this->Coversheet->save($this->request->data)) {
                                if ($data['Application']['status'] == 'signed') {
                                    $this->pdf_gen($id);
                                    $this->Session->setFlash(__('The coversheet has been submitted to underwriting'));
                                    $this->Coversheet->saveField('status','sent');
                                } else {
                                    $this->Session->setFlash(__('The coversheet has been validated and will be sent to underwriting once the application is signed'));
                                    $this->redirect(array('controller' => 'applications', 'action' => 'index', 'admin' => true));
                                }
                                
				//$this->redirect(array('controller' => 'applications', 'action' => 'index', 'admin' => true));
			} else {
				$this->Session->setFlash(__('The coversheet could not be validated. Please, try again.'));
                                $errors = $this->Coversheet->validationErrors;
                                if (array_key_exists('cp_encrypted_sn', $errors) || array_key_exists('cp_pinpad_ra_attached', $errors) || array_key_exists('cp_check_guarantee_info', $errors) || array_key_exists('cp_pos_contact', $errors)) {
                                    $this->set('cardPresent', true);
                                }
                                if (array_key_exists('micros', $errors)) {
                                    $this->set('micros', true);
                                    
                                }
                                if (array_key_exists('gateway_package', $errors) || array_key_exists('gateway_gold_subpackage', $errors) || array_key_exists('gateway_epay', $errors) || array_key_exists('gateway_billing', $errors)) {
                                    $this->set('gateway', true);
                                }
                                if (array_key_exists('moto_online_chd', $errors)) {
                                    $this->set('moto', true);
                                }
                                //$this->redirect(array('controller' => 'coversheets', 'action' => 'edit', $id));
                                
			}
                    }
		}
                if ($moto === true) {
                    $this->set('cp',false);
                } else {
                    $this->set('cp',true);
                }
		if (empty($this->request->data)) {
                        $this->request->data = $this->Coversheet->findById($id);
                        
                        
		}
	}
        
        public function email_coversheet($id) {
        if ($id && $this->Coversheet->sendCoversheet($id)){          
                unlink(WWW_ROOT . '/files/axia_' . $id . '_coversheet.pdf');
                $this->redirect(array('controller' => 'applications', 'action' => 'index', 'admin' => true));
                
        }
    }
    
    /************  Code to be Deprecated and Removed ***************/
    
//        function pdf($id = null) {
//        if (!$id) {
//            $this->Session->setFlash('Sorry, there was no PDF selected.');
//            //$this->redirect(array('controller' => 'applications', 'action' => 'index', 'admin' => true));
//            }
//            $data = $this->Coversheet->findById($id);
//                $this->set('data', $data);
//                    if ($data['Coversheet']['cp_encrypted_sn'] == '' && $data['Coversheet']['cp_pinpad_ra_attached'] == 'f' && $data['Coversheet']['cp_giftcards'] == '' && $data['Coversheet']['cp_check_guarantee'] == '' && $data['Coversheet']['cp_pos'] == '') {
//                    $this->set('cp',false);
//                } else {
//                    $this->set('cp',true);
//                }
//            $this->request->data = $this->Coversheet->findById($id);
//                        $testvar = $this->requestAction(
//                       array(
//                           'controller' => 'Coversheets',
//                           'action' => 'word'
//                       ),
//                       array(
//                           'pass' => array($id),
//                           'return',
//                           'bare' => 1
//                       )
//                    );
//            $file = new File(APP.DS.'webroot'.DS.'css'.DS.'coversheet.css', false); //1
//            $this->set('inlineCss',$file->read());
//            $this->set('testvar', $testvar);
//            //$this->set('coversheet', $this->render('edit/'.$id));
//            $this->layout = 'pdf'; //this will use the pdf.ctp layout
//            if (($this->Session->read('Application.coversheet')) == 'pdf'){
//                $this->pdf_gen($id);
//            } else {
//                $this->pdf_gen($id);
//                $this->email_coversheet($id);
//            }
//            
//            }
            function pdf_gen($id = null) {
                if ($id) {
            $this->Coversheet->id = $id;
            $data = $this->Coversheet->findById($id);
                if ($data['Application']['card_not_present_keyed'] + $data['Application']['card_not_present_internet'] >= '30') {
                    $this->set('cp',false);
                } else {
                    $this->set('cp',true);
                }
            $this->set('data', $data);;
            $path = WWW_ROOT . 'files/';
            $fp = (fopen($path . 'axia_coversheet.xfdf', 'w'));
            fwrite($fp, $this->render('/Elements/coversheets/pdf_export', false));
            fclose($fp);

            exec('pdftk ' . $path . 'axia_coversheet.pdf fill_form ' . $path . 'axia_coversheet.xfdf output ' . $path . 'axia_' . $data['Coversheet']['id'] . '_coversheet.pdf flatten');
            unlink($path . 'axia_coversheet.xfdf');
            $this->email_coversheet($id);
        }
    }
    
/*
* used to export the pdf directly from the web browser
* instead of emailing the pdf to underwriting
*/
    
        function export($id) {
        if ($id) {
            $this->set('id', $id);

            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="axia_' . $id . '_coversheet.pdf"');
            readfile(WWW_ROOT . 'files/axia_' . $id . '_coversheet.pdf');
            unlink(WWW_ROOT . '/files/axia_' . $id . '_coversheet.pdf');
        }
    }
    
/*
* Delete a coversheet
*/
    
	public function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for coversheet'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Coversheet->delete($id)) {
			$this->Session->setFlash(__('coversheet deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('coversheet was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
        
/*
 * Display a list of of coversheets
 */        
        
	public function admin_index() {
        $this->paginate = array(
            'limit' => 50,            
            'order' => array('Coversheet.id' => 'desc')
        );  

        if ($this->Auth->user('group_id') != User::ADMIN_GROUP_ID) {
            $conditions =  array(
                    'Coversheet.user_id' => $this->Coversheet->User->getAssignedUserIds($this->Auth->user('id'))
            );
        }
        $data = $this->paginate('Coversheet', $conditions);
        $this->set('users', $this->Coversheet->User->assignableUsers($this->Auth->user('id'), $this->Auth->user('group_id')));
        $this->set('Coversheets', $data);
	}
        
/*
 * Search for a coversheet, criteria available is determined by user rights
 */        
        
           public function admin_search() {
                $this->Prg->commonProcess();
                $this->set('users', $this->Coversheet->User->assignableUsers($this->Auth->user('id'), $this->Auth->user('group_id')));
                $criteria = trim($this->passedArgs['search']);
                $criteria = '%' . $criteria . '%';
                $conditions = array('OR' => array(
                                'Application.legal_business_name ILIKE' => $criteria,
                                'Application.mailing_city ILIKE' => $criteria,
                                'Application.corp_contact_name ILIKE' => $criteria,
                                'Application.dba_business_name ILIKE' => $criteria,
                                'Application.owner1_fullname ILIKE' => $criteria,
                                'Application.owner2_fullname ILIKE' => $criteria,
                                'CAST(Application.id AS TEXT) ILIKE' => $criteria,
                        ),                       
                );
                if ($this->passedArgs['Select User'] != '') {
                        $conditions[] = array('Application.user_id' => $this->passedArgs['Select User']);
                }
                else if ($this->Auth->user('group_id') != User::ADMIN_GROUP_ID) {
                        $conditions[] = array('Application.user_id' => $this->Coversheet->User->getAssignedUserIds($this->Auth->user('id')));
                }
                                if ($this->passedArgs['Application Status'] != '') {
                        $conditions[] = array('Application.status' => $this->passedArgs['Application Status']);
                }
                if ($this->passedArgs['Coversheet Status'] != '') {
                        $conditions[] = array('Coversheet.status' => $this->passedArgs['Coversheet Status']);
                }
                $this->paginate = array(
                        'limit' => 50,            
                        'order' => array('Coversheet.id' => 'desc'));
                $Coversheets = $this->paginate('Coversheet', $conditions);
                $this->set(compact('Coversheets'));
                $this->set('criteria', $this->passedArgs['search']);
                $this->render('admin_index');
        }
        
	public function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid coversheet'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('Coversheet', $this->Coversheet->read(null, $id));
	}

	public function admin_add() {

	}
        
/*
 * This should probably be a model function.
 * determines the underwriting guideline tiers based on data entered through the
 * online app
 */        
        protected function tier($id) {
            $data = $this->Coversheet->Application->findById($id);
            //debug($data);
            $moto = false;
            $result = '';
            if ($data['Application']['card_not_present_keyed'] + $data['Application']['card_not_present_internet'] >= '30') $moto = true;
            switch ($moto) {
                case true:
                    if ($data['Application']['monthly_volume'] < '150000' && $data['Application']['average_ticket'] < '1000') {
                        $result = 'tier2';
                    } else if ($data['Application']['monthly_volume'] < '150000' && $data['Application']['average_ticket'] > '1000') {
                        $result = 'tier4';
                    } else {
                        $result = 'tier5';
                    }   //return $result;
                    break;
                case false:
                    if ($data['Application']['monthly_volume'] < '250000' && $data['Application']['average_ticket'] < '1000') {
                        $result = 'tier1';
                    } else if ($data['Application']['monthly_volume'] > '250000' && $data['Application']['average_ticket'] < '1000') {
                        $result = 'tier3';
                    } else if ($data['Application']['monthly_volume'] < '250000' && $data['Application']['average_ticket'] > '1000') {
                        $result = 'tier4';
                    } else {
                        $result = 'tier5';
                    }   //return $result;
                    //debug($result);
                    break;
            }$this->set('tier',$result);
                  //debug($result);  
        }
        function admin_edit($id = null) {

        }
        
/*
 * Provide functionality to override certian aspects of the coversheet, in the
 * even that something was done incorrectly and needs to be done again
 */        
	public function admin_override($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__('Invalid coversheet'));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Coversheet->save($this->request->data)) {
				$this->Session->setFlash(__('The coversheet has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The coversheet could not be saved. Please, try again.'));
			}
		}
		if (empty($this->request->data)) {
			$data = $this->Coversheet->findById($id);
                        $this->request->data = $data;
		}
		$Applications = $this->Coversheet->Application->find('list');
		$Users = $this->Coversheet->User->find('list', array('order' => 'User.firstname ASC','fields' => array('User.id','User.fullname')));
		$this->set(compact('Applications', 'Users', 'data'));
	}

/*
* Delete a coversheet
*/
        
	public function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for coversheet'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Coversheet->delete($id)) {
			$this->Session->setFlash(__('coversheet deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('coversheet was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
?>