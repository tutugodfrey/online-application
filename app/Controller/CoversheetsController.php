<?php
App::uses('User', 'Model');
App::uses('EmailTimeline', 'Model');
App::uses('CakeEmail', 'Network/Email');

class CoversheetsController extends AppController {

	public $components = array('Security', 'Search.Prg');
	public $permissions = array(
		'admin_index' => array('rep', 'admin', 'manager'),
		'add' => array('rep', 'admin', 'manager'),
		'edit' => array('rep', 'admin', 'manager'),
		'admin_delete' => array('rep', 'admin', 'manager'),
		'get_orgs_suggestions' => array(User::ADMIN, User::REP, User::MANAGER),
		'get_regions_suggestions' => array(User::ADMIN, User::REP, User::MANAGER),
		'get_subregions_suggestions' => array(User::ADMIN, User::REP, User::MANAGER),
	);

	function beforeFilter() {
		parent::beforeFilter(); 
		if ($this->request->is('ajax')) {
			$this->Security->unlockedActions= array($this->action);
		}
	}

/**
 * get_orgs_suggestions
 * Method handles ajaxRequests to get organizations
 * 
 * @param string $orgName all or part of the name of a region which may or may not exist in the database
 * @return void
 */
	public function get_orgs_suggestions($orgName) {
		//this method can handle ajax and non-ajax calls
		$this->autoRender = false;
		if (!isset($orgName)) {
			echo json_encode([]);
			return;
		}
		$orgName = trim($orgName);
		if (strlen($orgName) <= 1) {
			echo json_encode([]);
			return;
		}
		$Org = new Model(array('table' => 'organizations', 'ds' => $this->Coversheet->connection));
		$orgs = $Org->find('list', [
			'fields' => ['name'],
			'conditions' => ["name ILIKE '%$orgName%'"]
		]);

		echo json_encode($orgs);
	}

/**
 * get_regions_suggestions
 * Method handles ajaxRequests to get regions
 * 
 * @param string $regionName all or part of the name of a region which may or may not exist in the database
 * @return void
 */
	public function get_regions_suggestions($regionName) {
		//this method can handle ajax and non-ajax calls
		$this->autoRender = false;
		$regionName = trim($regionName);
		if (strlen($regionName) <= 1) {
			echo json_encode([]);
			return;
		}
		$Region = new Model(array('table' => 'regions', 'ds' => $this->Coversheet->connection));
		$regions = $Region->find('list', [
			'fields' => ['name'],
			'conditions' => ["name ILIKE '%$regionName%'"]
		]);

		echo json_encode($regions);
	}

/**
 * get_subregions_suggestions
 * Method handles ajaxRequests to get subregions
 * 
 * @param string $subRegionName all or part of the name of a subregion which may or may not exist in the database
 * @return void
 */
	public function get_subregions_suggestions($subRegionName) {
		//this method can handle ajax and non-ajax calls
		$this->autoRender = false;
		$subRegionName = trim($subRegionName);
		if (strlen($subRegionName) <= 1) {
			echo json_encode([]);
			return;
		}
		$SubRegion = new Model(array('table' => 'subregions', 'ds' => $this->Coversheet->connection));
		$subregions = $SubRegion->find('list', [
			'fields' => ['name'],
			'conditions' => ["name ILIKE '%$subRegionName%'"]
		]);

		echo json_encode($subregions);
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
				$this->_success(__("New Coversheet #$id has been created"));
				$id = $this->Coversheet->id;
				$this->redirect('/coversheets/edit/' . $this->Coversheet->id);
			} else {
				$this->_failure(__('The coversheet could not be saved. Please, try again.'));
			}
		}
	}

	public function edit($id = null) {
		$data = $this->Coversheet->findById($id);

        $valuesMap = $this->Coversheet->CobrandedApplication->CobrandedApplicationValues->getValuesByAppId(Hash::get($data, 'CobrandedApplication.id'));
        foreach ($valuesMap as $key => $val) {
            $data['CobrandedApplication'][$key] = $val;
        }
        $dbUserAssocParters = [];
        if (Hash::get($data, 'Coversheet.status') === 'saved') {
        	$requestUri = '/api/Users/get_reps?'. http_build_query(["user_name" => Hash::get($data, 'CobrandedApplication.ContractorID')], "", null, PHP_QUERY_RFC3986);
	        $axDbApiClient = $this->Coversheet->createAxiaDbApiAuthClient('GET', $requestUri);
			$reponse = $axDbApiClient->get('https://db.axiatech.com'.$requestUri);
			$responseData = json_decode($reponse->body, true);
			if (!empty($responseData['data'])) {
				$dbUserAssocParters = Hash::extract($responseData, 'data.{n}.assoc_partners.{s}');
			}
        }
		$dbUserAssocParters = json_encode($dbUserAssocParters);
		$this->set(compact('id', 'data', 'dbUserAssocParters'));
		$moto = false;
		$result = '';

		if (Hash::get($data,'CobrandedApplication.MethodofSales-CardNotPresent-Keyed') + Hash::get($data,'CobrandedApplication.MethodofSales-CardNotPresent-Internet') >= '30') {
			$moto = true;
		}

		switch ($moto) {
			case true:
				if (Hash::get($data, 'CobrandedApplication.MonthlyVol') < '150000' && Hash::get($data, 'CobrandedApplication.AvgTicket') < '1000') {
					$result = 'tier2';
				} elseif (Hash::get($data, 'CobrandedApplication.MonthlyVol') < '150000' && Hash::get($data, 'CobrandedApplication.AvgTicket') > '1000') {
					$result = 'tier4';
				} else {
					$result = 'tier5';
				}
				break;
			case false:
				if (Hash::get($data, 'CobrandedApplication.MonthlyVol') < '250000' && Hash::get($data, 'CobrandedApplication.AvgTicket') < '1000') {
					$result = 'tier1';
				} elseif (Hash::get($data, 'CobrandedApplication.MonthlyVol') > '250000' && Hash::get($data, 'CobrandedApplication.AvgTicket') < '1000') {
					$result = 'tier3';
				} elseif (Hash::get($data, 'CobrandedApplication.MonthlyVol') < '250000' && Hash::get($data, 'CobrandedApplication.AvgTicket') > '1000') {
					$result = 'tier4';
				} else {
					$result = 'tier5';
				}   
				break;
		}
		
		$this->set('tier',$result);
		
        if (!$id && empty($this->request->data)) {
			$this->_failure(__('Invalid coversheet'));
			$this->redirect(array('action' => 'index'));
		}

		if ($id && !empty($this->request->data)) {
			if(isset($this->request->data['save'])) {

				if ($this->request->data['Coversheet']['gateway_package'] != 'gold') {
					$this->request->data['Coversheet']['gateway_gold_subpackage'] = '';
				}
			
                if ($this->Coversheet->save($this->request->data, array('validate' => false))) {
				    $this->_success(__('The coversheet #' . $this->request->data('Coversheet.id') . ' has been saved'));
				    $this->redirect(array('controller' => 'cobranded_applications', 'action' => 'index', 'admin' => true));
                } else {
				    $this->_failure(__('The coversheet #' . $this->request->data('Coversheet.id') . 'could not be saved. Please, try again.'));
                }
            } elseif (isset($this->request->data['uw'])) {
                $term1AcceptDebit = 'no';
                if (Hash::get($data, 'CobrandedApplication.TermAcceptDebit-Yes') == true) {
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

                        $valuesMap = $this->Coversheet->CobrandedApplication->CobrandedApplicationValues->getValuesByAppId($coversheetData['CobrandedApplication']['id']);
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

                        try {
                        	$pdfReady = $this->Coversheet->pdfGen($id, $viewData);
                        } catch (Exception $e) {
                        	 $this->_failure(__($e->getMessage()));
                        	 $this->redirect(array('controller' => 'coversheets', 'action' => 'index', 'admin' => true));
                        }

                        if ($pdfReady) {
                            if ($this->Coversheet->sendCoversheet($id)) {
                                if ($this->Coversheet->unlinkCoversheet($id)) {
                                    $this->_success(__("The coversheet $id has been submitted to underwriting"));
                                    $this->Coversheet->saveField('status', 'sent');
                                } else {
                                    $this->_failure(__("There was a problem deleting the Coversheet pdf file"));
                                }
                            } else {
                                $this->_failure(__("There was a problem sending the Coversheet pdf"));
                            }
                        } else {
                            $this->_failure(__("There was a problem generating the Coversheet pdf"));
                        }
                    } else {
                        $this->_success(__("The coversheet $id has been validated and will be sent to underwriting once the application is signed"));
                        $this->redirect(array('controller' => 'cobranded_applications', 'action' => 'index', 'admin' => true));
                    }
				    $this->redirect(array('controller' => 'cobranded_applications', 'action' => 'index', 'admin' => true));
                } else {
				    $this->_failure(__("The coversheet $id could not be validated. Please, try again."));
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

            $valuesMap = $this->Coversheet->CobrandedApplication->CobrandedApplicationValues->getValuesByAppId($this->request->data('CobrandedApplication.id'));
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
			$this->_failure(__('Invalid id for coversheet'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Coversheet->delete($id)) {
			$this->_success(__('coversheet deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->_failure(__('coversheet was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
		
/*
 * Display a list of coversheets
 */        
	public function admin_index() {
		//reset all of the search parameters
		if(isset($this->request->data['reset'])) {
			foreach($this->request->data['Coversheet'] as $i => $value) {
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

		if (empty($this->request->query['limit'])) {
			$this->request->query['limit'] = 25;
		}

		$this->Paginator->settings['limit'] = $this->request->query['limit'];
		try {
			$this->set('coversheets', $this->Paginator->paginate());
		} catch (NotFoundException $e) {
			$lastPage = $this->request->params['paging']['Coversheet']['options']['page'] - 1;
			$queryParams = array_merge($this->request->query, array('limit' => $this->request->params['paging']['Coversheet']['limit']));
			$this->redirect(Router::url(array('action' => 'admin_index', 'page:' . $lastPage, '?' => $queryParams)));
		}

		$userTemplate = $this->User->Template->find(
			'first',
			array(
				'conditions' => array('Template.id' => $this->Auth->user('template_id')),
			)
		);


		$this->set('users', $users);
		$this->set('user_id', $this->Auth->user('id'));
	}

/*
 * This should probably be a model function.
 * determines the underwriting guideline tiers based on data entered through the
 * online app
 */        
        protected function tier($id) {
            $data = $this->Coversheet->CobrandedApplication->findById($id);
            $moto = false;
            $result = '';

            $valuesMap = $this->Coversheet->CobrandedApplication->CobrandedApplicationValues->getValuesByAppId($data['CobrandedApplication']['id']);
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
			}$this->set('tier',$result);
		}
		function admin_edit($id = null) {

		}
		
/*
 * Provide functionality to override certian aspects of the coversheet, in the
 * even that something was done incorrectly and needs to be done again
 */        
    public function admin_override($id = null) {
        if (!$id && empty($this->request->data)) {
            $this->_failure(__('Invalid coversheet'));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->request->is('ajax')) {
            $data = $this->Coversheet->findById($id);
            $Applications = $this->Coversheet->CobrandedApplication->find('list');
            $Users = $this->Coversheet->User->find('list', array('order' => 'User.firstname ASC', 'fields' => array('User.id', 'User.fullname')));
            $this->set(compact('Applications', 'Users', 'data'));
        } elseif ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Coversheet->save($this->request->data)) {
                $this->_success(__('The coversheet has been saved'), array('action' => 'index'));
            } else {
                $this->_failure(__('The coversheet could not be saved. Please, try again.'), array('action' => 'index'));
            }
        }
    }

/*
* Delete a coversheet
*/
		
	public function admin_delete($id = null) {
		if (!$id) {
			$this->_failure(__('Invalid id for coversheet'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Coversheet->delete($id)) {
			$this->_success(__('coversheet deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->_failure(__('coversheet was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
