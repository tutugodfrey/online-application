<?php
App::uses('User', 'Model');
App::uses('EmailTimeline', 'Model');
App::uses('CakeEmail', 'Network/Email');

class CoversheetsController extends AppController {

    public $components = array('Security', 'Search.Prg');
    public $helpers = array('Js'); 
    public $permissions = array(
        'admin_index' => array('rep', 'admin', 'manager'),
        'add' => array('rep', 'admin', 'manager'),
        'edit' => array('rep', 'admin', 'manager'),
        'admin_delete' => array('rep', 'admin', 'manager')
    );

    function beforeFilter() {
        parent::beforeFilter(); 
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
            $this->Coversheet->set(
                array(
                    'cobranded_application_id' => $oid,
                    'user_id' => $uid,
                    'status' => 'saved'
                )
            );
                
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
				$this->redirect(array('controller' => 'cobranded_applications', 'action' => 'index', 'admin' => true));
            } else {
				$this->Session->setFlash(__('The coversheet could not be saved. Please, try again.'));
            }
        }
		
        if (empty($this->request->data)) {
			$this->request->data = $this->Coversheet->findById($id);
		}
		
        $applications = $this->Coversheet->CobrandedApplication->read(null,$oid);
		$users = $this->Coversheet->User->read(null,$uid);
		$this->set(compact('applications', 'users'));
	}
        
	public function edit($id = null) {
        $data = $this->Coversheet->findById($id);

        $valuesMap = $this->getCobrandedApplicationValues($data['CobrandedApplication']['id']);
        foreach ($valuesMap as $key => $val) {
            $data['CobrandedApplication'][$key] = $val;
        }

        $this->set(compact('id', 'data'));
        $moto = false;
        $result = '';  

        if ($data['CobrandedApplication']['MethodofSales-CardNotPresent-Keyed'] + $data['CobrandedApplication']['MethodofSales-CardNotPresent-Internet'] >= '30') $moto = true;
        
        switch ($moto) {
            case true:
                if ($data['CobrandedApplication']['MonthlyVol'] < '150000' && $data['CobrandedApplication']['AvgTicket'] < '1000') {
                    $result = 'tier2';
                } else if ($data['CobrandedApplication']['MonthlyVol'] < '150000' && $data['CobrandedApplication']['AvgTicket'] > '1000') {
                    $result = 'tier4';
                } else {
                    $result = 'tier5';
                }   
                break;
            case false:
                if ($data['CobrandedApplication']['MonthlyVol'] < '250000' && $data['CobrandedApplication']['AvgTicket'] < '1000') {
                    $result = 'tier1';
                } else if ($data['CobrandedApplication']['MonthlyVol'] > '250000' && $data['CobrandedApplication']['AvgTicket'] < '1000') {
                    $result = 'tier3';
                } else if ($data['CobrandedApplication']['MonthlyVol'] < '250000' && $data['CobrandedApplication']['AvgTicket'] > '1000') {
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
            if(isset($this->request->data['save'])) {
                if ($this->request->data['Coversheet']['gateway_package'] != 'gold') {
                    $this->request->data['Coversheet']['gateway_gold_subpackage'] = '';
                }
			
                if ($this->Coversheet->save($this->request->data, array('validate' => false))) {
				    $this->Session->setFlash(__('The coversheet has been saved'));
				    $this->redirect(array('controller' => 'cobranded_applications', 'action' => 'index', 'admin' => true));
                } else {
				    $this->Session->setFlash(__('The coversheet could not be saved. Please, try again.'));
                }
            } elseif (isset($this->request->data['uw'])) {
                $term1AcceptDebit = 'no';
                if ($data['CobrandedApplication']['TermAcceptDebit-Yes'] == true) {
                    $term1AcceptDebit = 'yes'; 
                }

                $businessType = '';

                if ($data['CobrandedApplication']['BusinessType-Retail'] == true) {
                    $businessType = 'BusinessType-Retail';
                } elseif ($data['CobrandedApplication']['BusinessType-Restaurant'] == true) {
                    $businessType = 'BusinessType-Restaurant';
                } elseif ($data['CobrandedApplication']['BusinessType-Lodging'] == true) {
                    $businessType = 'BusinessType-Lodging';
                } elseif ($data['CobrandedApplication']['BusinessType-MOTO'] == true) {
                    $businessType = 'BusinessType-MOTO';
                } elseif ($data['CobrandedApplication']['BusinessType-Internet'] == true) {
                    $businessType = 'BusinessType-Internet';
                } elseif ($data['CobrandedApplication']['BusinessType-Grocery'] == true) {
                    $businessType = 'BusinessType-Grocery';
                }

                $this->Coversheet->set(array('status' => 'validated', 'debit' => $term1AcceptDebit, 'moto' => $businessType));

                if ($this->request->data['Coversheet']['gateway_package'] != 'gold') {
                    $this->request->data['Coversheet']['gateway_gold_subpackage'] = '';
                }

                if ($this->Coversheet->save($this->request->data)) {
                    if ($data['CobrandedApplication']['status'] == 'signed') {
                        $coversheetData = $this->Coversheet->findById($id);

                        $valuesMap = $this->getCobrandedApplicationValues($coversheetData['CobrandedApplication']['id']);
                        foreach ($valuesMap as $key => $val) {
                            $coversheetData['CobrandedApplication'][$key] = $val;
                        }

                        $View = new View($this, false);

                        if ($coversheetData['CobrandedApplication']['MethodofSales-CardNotPresent-Keyed'] + $coversheetData['CobrandedApplication']['MethodofSales-CardNotPresent-Internet'] >= '30') {
                            $View->set('cp',false);
                        } else {
                            $View->set('cp',true);
                        }

                        $View->set('data', $coversheetData);
                        $View->viewPath = 'Elements';
                        $View->layout = false;
                        $viewData = $View->render('/Elements/coversheets/pdf_export'); 

                        if ($this->Coversheet->pdfGen($id, $viewData)) {
                            if ($this->Coversheet->sendCoversheet($id)) {
                                if ($this->Coversheet->unlinkCoversheet($id)) {
                                    $this->Session->setFlash(__('The coversheet has been submitted to underwriting'));
                                    $this->Coversheet->saveField('status', 'sent');
                                } else {
                                    $this->Session->setFlash(__('There was a problem deleting the Coversheet pdf file'));
                                }
                            } else {
                                $this->Session->setFlash(__('There was a problem sending the Coversheet pdf'));
                            }
                        } else {
                            $this->Session->setFlash(__('There was a problem generating the Coversheet pdf'));
                        }
                    } else {
                        $this->Session->setFlash(__('The coversheet has been validated and will be sent to underwriting once the application is signed'));
                        $this->redirect(array('controller' => 'cobranded_applications', 'action' => 'index', 'admin' => true));
                    }
				    $this->redirect(array('controller' => 'cobranded_applications', 'action' => 'index', 'admin' => true));
                } else {
				    $this->Session->setFlash(__('The coversheet could not be validated. Please, try again.'));
                    $errors = $this->Coversheet->validationErrors;
                    $this->set('errors', $errors);
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

            $valuesMap = $this->getCobrandedApplicationValues($this->request->data['CobrandedApplication']['id']);
            foreach ($valuesMap as $key => $val) {
                $this->request->data['CobrandedApplication'][$key] = $val;
            }

            $this->set('data', $this->request->data);
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
 * Display a list of coversheets
 */        
	public function admin_index() {
		//reset all of the search parameters
		if(isset($this->request->data['reset'])) {
			foreach($this->request->data['Coversheet'] as $i => $value){
				$this->request->data['Coversheet'][$i]= '';
			}
		}
		//paginate the applications
		$this->Prg->commonProcess();
		//grab results from the custom finder _findIndex and pass them to the paginator
		$this->paginate = array('index');
		$this->Paginator->settings = $this->paginate;
		$this->Paginator->settings['conditions'] = $this->Coversheet->parseCriteria($this->passedArgs);
		$this->Paginator->settings['order'] = array('Coversheet.modified' => ' DESC');

		$users = $this->Coversheet->User->assignableUsers($this->Auth->user('id'), $this->Auth->user('group_id'));
		$userIds = $this->Coversheet->User->getAssignedUserIds($this->Auth->user('id'));

		if (empty($users)) {
			$users = $this->Coversheet->User->find(
				'list', 
				array(
					'conditions' => array('User.id' => $this->Auth->user('id')),
				)
			);
		}
		// default to only show logged in user unless user is admin
		// perform some permissions checks
		// Reps can see only their own apps
		// Managers can see their own plus reps assigned to them
		// Admins can see everything
		switch($this->Auth->user('group_id')) {
			case User::REPRESENTATIVE_GROUP_ID:
				$this->Paginator->settings['conditions']['Coversheet.user_id'] = $this->Auth->user('id');
				break;
			case User::MANAGER_GROUP_ID:
				if(array_key_exists('search', $this->passedArgs)) {
					if(in_array($this->passedArgs['user_id'], $userIds)) {
						$this->Paginator->settings['conditions'] = $this->Coversheet->parseCriteria($this->passedArgs);
					} else if (!in_array($this->passedArgs['user_id'], $userIds)) {
						$this->Paginator->settings['conditions']['Coversheet.user_id'] = $userIds;
					}
				} else {
					$this->Paginator->settings['conditions'] = array(
                                        	'Coversheet.user_id' => $this->Auth->user('id')
                                	);
				}
				break;
		}
		$this->set('coversheets',  $this->Paginator->paginate());

		$userTemplate = $this->User->Template->find(
			'first',
			array(
				'conditions' => array('Template.id' => $this->Auth->user('template_id')),
			)
		);


		$this->set('users', $users);
		$this->set('user_id', $this->Auth->user('id'));
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
            $data = $this->Coversheet->CobrandedApplication->findById($id);
            //debug($data);
            $moto = false;
            $result = '';

            $valuesMap = $this->getCobrandedApplicationValues($data['CobrandedApplication']['id']);
            foreach ($valuesMap as $key => $val) {
                $data['CobrandedApplication'][$key] = $val;
            }

            if ($data['CobrandedApplication']['MethodofSales-CardNotPresent-Keyed'] + $data['CobrandedApplication']['MethodofSales-CardNotPresent-Internet'] >= '30') $moto = true;
            switch ($moto) {
                case true:
                    if ($data['CobrandedApplication']['MonthlyVol'] < '150000' && $data['CobrandedApplication']['AvgTicket'] < '1000') {
                        $result = 'tier2';
                    } else if ($data['CobrandedApplication']['MonthlyVol'] < '150000' && $data['CobrandedApplication']['AvgTicket'] > '1000') {
                        $result = 'tier4';
                    } else {
                        $result = 'tier5';
                    }   //return $result;
                    break;
                case false:
                    if ($data['CobrandedApplication']['MonthlyVol'] < '250000' && $data['CobrandedApplication']['AvgTicket'] < '1000') {
                        $result = 'tier1';
                    } else if ($data['CobrandedApplication']['MonthlyVol'] > '250000' && $data['CobrandedApplication']['AvgTicket'] < '1000') {
                        $result = 'tier3';
                    } else if ($data['CobrandedApplication']['MonthlyVol'] < '250000' && $data['CobrandedApplication']['AvgTicket'] > '1000') {
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
		$Applications = $this->Coversheet->CobrandedApplication->find('list');
		$Users = $this->Coversheet->User->find('list', array('order' => 'User.firstname ASC', 'fields' => array('User.id', 'User.fullname')));
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

/*
 * getCobrandedApplicationValues
 *
 * @param $applicationId integer
 * @param $valueConditions array
 * @param $recursive integer
 * @return $valuesMap array
 */
    public function getCobrandedApplicationValues($applicationId, $valueConditions = array(), $recursive = null) {
        $CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');
	
        if (!isset($recursive)) {
            $recursive = 1;
        }
	
        $conditions = array(
            'conditions' => array(
                'cobranded_application_id' => $applicationId,
            ),
            'recursive' => $recursive
        );
	
        if (!empty($valueConditions)) {
            $conditions['conditions'][] = $valueConditions;
        }
        
        $appValues = $CobrandedApplicationValue->find(
            'all',
            $conditions  	
        );
	
        $appValueArray = array();
        foreach ($appValues as $arr) {
            $appValueArray[] = $arr['CobrandedApplicationValue'];
        }

        $valuesMap = $this->Coversheet->CobrandedApplication->buildCobrandedApplicationValuesMap($appValueArray);
        return $valuesMap;
    }
}
?>
