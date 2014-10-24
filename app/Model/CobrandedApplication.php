<?php
App::uses('AppModel', 'Model');
App::uses('TemplateField', 'Model');
App::uses('EmailTimeline', 'Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');

/**
 * CobrandedApplication Model
 *
 * @property CobrandedApplications $CobrandedApplications
 */
class CobrandedApplication extends AppModel {

/**
 * Table to use
 *
 * @var string
 */
	public $useTable = 'onlineapp_cobranded_applications';

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Search.Searchable',
		'Containable',
		'Multivalidatable',
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'rule' => array('numeric'),
			'allowEmpty' => false,
			'required' => true,
		),
		'uuid' => array(
			'rule' => array('uuid'),
			'message' => 'Invalid UUID',
			'allowEmpty' => false,
			'required' => true,
			//'last' => false, // Stop validation after this rule
			//'on' => 'create', // Limit validation to 'create' or 'update' operations
		),
		'template_id' => array(
			'rule' => array('numeric'),
			'allowEmpty' => false,
			'required' => true,
		),
	);

/**
 * Multivalidatable rules
 *
 * @var array
 */
	public $validationSets = array(
		'install_var_select' => array(
			//'select_email_address' => array(
			'rule' => 'email',
			'required' => true
		//)
		),
		'install_var_enter' => array(
			//   'enter_email_address' => array(
			'rule' => 'email',
			'required' => true
		//)
		),
	);

/**
 * Custom find Method
 *
 * @var array
 */
	public $findMethods = array('index' => true);

/**
 * belongsTo association
 * 
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
		),
		'Template' => array(
			'className' => 'Template',
			'foreignKey' => 'template_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'CobrandedApplicationValues' => array(
			'className' => 'CobrandedApplicationValue',
			'foreignKey' => 'cobranded_application_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => 'id',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'EmailTimeline' => array(
			'className' => 'EmailTimeline',
			'foreignKey' => 'cobranded_application_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => 'EmailTimeline.date DESC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'CobrandedApplicationAches' => array(
			'className' => 'CobrandedApplicationAch',
			'foreignKey' => 'cobranded_application_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'Merchant' => array(
			'className' => 'Merchant',
			'foreignKey' => 'cobranded_application_id'
		),
		'Coversheet' => array(
			'className' => 'Coversheet',
			'foreignKey' => 'cobranded_application_id'
		),
	);

	// tests will set this via dependency injection, using a mocked object
	public $CakeEmail = null;

/**
 * afterSave
 *
 * @params
 *     $created boolean
 */
	public function afterSave($created, $options = array()) {
		if ($created === true) {
			$applicationId = $this->data['CobrandedApplication']['id'];
			$template = $this->Template->find('first', array(
				'conditions' => array('Template.id' => $this->data['CobrandedApplication']['template_id']),
				'contain' => array(
					'TemplatePages' => array(
						'TemplateSections' => array(
							'TemplateFields' => array(
								'fields' => array('id', 'type', 'name', 'default_value', 'merge_field_name', 'order', 'width')
							)
						),
					),
				),
			));

			// seed the cobranded application values
			foreach ($template['TemplatePages'] as $page) {
				foreach ($page['TemplateSections'] as $section) {
					foreach ($section['TemplateFields'] as $field) {
						// types with multiple values/options are handled differently
						switch ($field['type']) {
							case 4: // 'radio':
								// split default_value on ',' and append split[1] to the merge_field_name
								foreach (split(',', $field['default_value']) as $keyValuePairStr) {
									$multiTypeHasDefault = false;
			
									if (preg_match('/\{default\}/i', $keyValuePairStr)) {
										$keyValuePairStr = preg_replace('/\{default\}/i', '', $keyValuePairStr);
										$multiTypeHasDefault = true;
									}

									$keyValuePair = split('::', $keyValuePairStr);
									$name = $field['merge_field_name'].$keyValuePair[1];

									$newApplicationValue = array(
										'cobranded_application_id' => $applicationId,
										'template_field_id' => $field['id'],
										'name' => $name,
									);

									if ($multiTypeHasDefault) {
										$newApplicationValue = Hash::insert($newApplicationValue, 'value', 'true');
									}

									$this->__addApplicationValue($newApplicationValue);
								}
								break;

							case 5: // 'percents':
							case 7: // 'fees':
								// split default_value on ',' and append split[1] to the merge_field_name
								foreach (split(',', $field['default_value']) as $keyValuePairStr) {
									$multiTypeDefaultVal = null;
			
									if (preg_match('/\{(.+)\}/', $keyValuePairStr, $matches)) {
										$keyValuePairStr = preg_replace('/\{.+\}/', '', $keyValuePairStr);
										$multiTypeDefaultVal = $matches[1];
									}

									$keyValuePair = split('::', $keyValuePairStr);
									$name = $field['merge_field_name'].$keyValuePair[1];

									$newApplicationValue = array(
										'cobranded_application_id' => $applicationId,
										'template_field_id' => $field['id'],
										'name' => $name,
									);

									if ($multiTypeDefaultVal != null) {
										$newApplicationValue = Hash::insert($newApplicationValue, 'value', $multiTypeDefaultVal);
									}

									$this->__addApplicationValue($newApplicationValue);
								}
								break;

							case 6:
							case 8:
								// noop for label or hr items
								break;

							case 20: // 'select':
							    $multiTypeHasDefault = false;

							    // split default_value on ','
								foreach (split(',', $field['default_value']) as $keyValuePairStr) {
									if (preg_match('/\{default\}/i', $keyValuePairStr)) {
										$keyValuePairStr = preg_replace('/\{default\}/i', '', $keyValuePairStr);
										$keyValuePair = split('::', $keyValuePairStr);

										$multiTypeHasDefault = true;

										$newApplicationValue = array(
											'cobranded_application_id' => $applicationId,
											'template_field_id' => $field['id'],
											'name' => $field['merge_field_name'],
											'value' => $keyValuePair[1]
										);

										$this->__addApplicationValue($newApplicationValue);
									}
								}

								if ($multiTypeHasDefault == false) {
									$newApplicationValue = array(
										'cobranded_application_id' => $applicationId,
										'template_field_id' => $field['id'],
										'name' => $field['merge_field_name'],
									);
									$this->__addApplicationValue($newApplicationValue);
								}

								break;

							default:
								// call $this->__addApplicationValue();
								$newApplicationValue = array(
									'cobranded_application_id' => $applicationId,
									'template_field_id' => $field['id'],
									'name' => $field['merge_field_name'],
								);
								if ($field['type'] != 20 && strlen($field['default_value']) > 0) {
									$newApplicationValue = Hash::insert($newApplicationValue, 'value', $field['default_value']);
								}
								$this->__addApplicationValue($newApplicationValue);
								break;
						}
					}
				}
			}
		}
	}
	
/**
 * getTemplateAndAssociatedValues
 *
 * @params
 *     $applicationId integer
 */
	public function getTemplateAndAssociatedValues($applicationId, $userId = null) {
		$this->id = $applicationId;
		$application = $this->read();

		// if user is not logged in, don't show rep-only fields
		$conditions = '';
		if (is_null($userId)) {
			$conditions = 'rep_only != true';
		}

		return $this->find(
			'first', array(
				'contain' => array(
					'Template' => array(
						'Cobrand',
						'TemplatePages' => array(
							'TemplateSections' => array(
								'TemplateFields' => array(
									'CobrandedApplicationValues' => array(
										'conditions' => array(
											'cobranded_application_id' => $applicationId,
										),
										'order' => array('id')
									),
									'conditions' => array(
										$conditions
									),
								),
								'conditions' => array(
									$conditions
								),
							),
							'conditions' => array(
								$conditions
							),
						)
					)
				),
				'conditions' => array(
					'Template.id' => $application['Template']['id'],
					'CobrandedApplication.id' => $applicationId,
				)
			)
		);
	}

/**
 * getApplicationValue
 *
 * @params
 *     $valueId integer
 */
	public function getApplicationValue($valueId) {
		if (is_null($this->CobrandedApplicationValue)) {
			$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');
		}
		return $this->CobrandedApplicationValue->findById($valueId);
	}

/**
 * saveApplicationValue
 *
 * @params
 *     $date array
 */
	public function saveApplicationValue($data) {
		$response = array('success' => false);

		// grab the appliction value with $data['id']
		$appValue = $this->getApplicationValue($data['id']);

		// if the value is different
		if ($appValue['CobrandedApplicationValue']['value'] != $data['value']) {
			$appValue['CobrandedApplicationValue']['value'] = $data['value'];
			if ($this->CobrandedApplicationValue->save($appValue)) {
				// value saved
				$response['success'] = true;
				// if the template field has a type of 4, all other options must be set to null
				if ($appValue['TemplateField']['type'] == 4) {
					// this could be an after save callback
					$radioOptions = $this->CobrandedApplicationValue->find(
						'all',
						array('conditions' => array(
							'template_field_id' => $appValue['TemplateField']['id'],
							'cobranded_application_id' => $appValue['CobrandedApplication']['id'],
						))
					);
					foreach ($radioOptions as $radioOption) {
						if ($radioOption['CobrandedApplicationValue']['id'] != intval($data['id'])) {
							// udpate the value to null
							$radioOption['CobrandedApplicationValue']['value'] = null;
							if (!$this->CobrandedApplicationValue->save($radioOption)) {
								$response['success'] = false;
								$response['msg'] = 'failed to update application value with id ['.$radioOption['id'].'], to a value of null.';
							}
						}
					}
				}
			} else {
				// something went wrong
				$response = Hash::insert(
					$response,
					'msg',
					'failed to update application value with id ['.$data['id'].'], value ['.$data['value'].'].'
				);
			}
		} else {
			// let the consumer know the value did not change
			$response = Hash::insert(
				$response,
				'msg',
				'failed to update application value with id ['.$data['id'].'] because the value did not change'
			);
		}

		return $response;
	}


/**
 * createOnlineappForUser
 *
 * @param 
 *     $user object
 *     $uuid string [optional]
 * 
 * @returns
 *     $response array(
 *         success [true|false] depending on if the onlineapp was created
 *         cobrandedApplicationId int
 */
	public function createOnlineappForUser($user, $uuid = null) {
		$response = array('success' => false, 'cobrandedApplication' => array('id' => null, 'uuid' => null));
		if (is_null($uuid)) {
			$uuid = String::uuid();
		}

		$this->create(
			array(
				'user_id' => $user['id'],
				'uuid' => $uuid,
				'template_id' => $user['template_id'],
				'status' => 'saved'
			)
		);

		if ($this->save()) {
			$response['success'] = true;
			$response['cobrandedApplication'] = array(
				'id' => $this->id,
				'uuid' => $uuid,
			);
		}
		return $response;
	}


/**
 * saveFields
 * 
 * 	create an application for $user
 *  populate it with the passed $fieldsData
 *  if is the $fieldsData valid then save it
 *  otherwise:
 *      compile a list of the problems,
 *      send the response back and
 *      delete the cobranded application that was created
 * 
 * @params
 *     $user model
 *     $fieldData array
 * 
 * @returns
 *     $response array
 */
	public function saveFields($user, $fieldsData) {
		// default our response
		$response = array(
			'success' => false,
			'validationErrors' => array(),
			'application_id' => null,
			'application_url' => null
		);

		// create an application for $userId
		$createAppResponse = $this->createOnlineappForUser($user);
		$newApp = null;

		if ($createAppResponse['success'] == true) {
			// populate it with the passed $fieldsData
			$newApp = $this->find(
				'first',
				array(
					'conditions' => array(
						'CobrandedApplication.id' => $createAppResponse['cobrandedApplication']['id']
					)
				)
			);

			$this->Session = ClassRegistry::init('Session');
			$this->Cobrand = ClassRegistry::init('Cobrand');
			$this->TemplateField = ClassRegistry::init('TemplateField');
			$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');

			$templateFieldIdMap = array();

			// save the application values
			foreach ($fieldsData as $key => $value) {
				// multirecord data will be in an array, don't trim the array
				if (!is_array($value)) {
					$value = trim($value);
				}

				// look up the field type from the name
				$appValue = $this->CobrandedApplicationValues->find(
					'first',
					array(
						'conditions' => array(
							'cobranded_application_id' => $newApp['CobrandedApplication']['id'],
							'CobrandedApplicationValues.name' => $key
						)
					)
				);

				// is the TemplateField associated with the application value?
				// I am not sure why this would be missing...
				$templateField = null;
				if (key_exists('TemplateField', $appValue)) {
					$templateField = $appValue['TemplateField'];
				} else {
					// look it up
					$templateField = $this->TemplateField->find(
						'first',
						array(
							'conditions' => array(
								'TemplateField.id' => $appValue['CobrandedApplicationValues']['template_field_id']
							)
						)
					);
					$templateField = $templateField['TemplateField'];
				}

				// if the field is rep_only == true, skip it because this value cannot be set via the api
				if ($templateField['rep_only'] == false) {

					// 22 is multirecord data that needs to be handled by it's associated Model
					if ($templateField['type'] == 22) {
						if (!empty($templateField['default_value'])) {
							$defaultValue = $templateField['default_value'];
							$Model = ClassRegistry::init($defaultValue);
				
							foreach ($value as $key => $val) {
								$val['cobranded_application_id'] = $newApp['CobrandedApplication']['id'];
								$Model->create($val);
								$success = $Model->save();
								if (!$success) {
									$errors = array();
									$errors['invalid record'] = $val;
									unset($errors['invalid record']['cobranded_application_id']);
									foreach ($Model->validationErrors as $key => $value) {
										$errors["$key"] = $value;					
									}
									array_push($response['validationErrors'], $errors);
								}
							}

							// multirecord data is validated and saved by it's associated Model
							// we don't want to add this to $appValue['CobrandedApplicationValues']['value']
							continue;
						}
					}

					// if the template field has a type of 4, all other options must be set to null
					if ($templateField['type'] == 4) {
						$radioOptions = $this->CobrandedApplicationValue->find(
							'all',
							array(
								'conditions' => array(
									'template_field_id' => $templateField['id'],
									'cobranded_application_id' => $newApp['CobrandedApplication']['id'],
								)
							)
						);
						
						foreach ($radioOptions as $radioOption) {
							if ($radioOption['CobrandedApplicationValue']['id'] != $appValue['CobrandedApplicationValues']['id']) {
								// udpate the value to null
								$radioOption['CobrandedApplicationValue']['value'] = null;
								if (!$this->CobrandedApplicationValue->save($radioOption)) {
									$response['validationErrors'] = Hash::insert($response['validationErrors'], $templateField['merge_field_name'], 'failed to update application value with id ['.
										$radioOption['CobrandedApplicationValue']['id'].'], to a value of null.');
								}
							}
						}
					}

					// update the value
					$appValue['CobrandedApplicationValues']['value'] = $value;

					// handle required and empty first
					if ($templateField['required'] == true && empty($value) == true) {

						// SSN should not be required if Ownership Type is Non Profit
						if ($appValue['CobrandedApplicationValues']['name'] == 'OwnerSSN' ||
							$appValue['CobrandedApplicationValues']['name'] == 'Owner2SSN') {

							if ($fieldsData['OwnerType-NonProfit'] == true) {
								continue;
							}
						}

						// update our validationErrors array
						$response['validationErrors'] = Hash::insert($response['validationErrors'], $templateField['merge_field_name'], 'required');
					} else {
						// only validate if we are not empty
						if (isset($value)) {
							// if social security number is missing dashes, add them in
							if ($appValue['CobrandedApplicationValues']['name'] == 'OwnerSSN' ||
								$appValue['CobrandedApplicationValues']['name'] == 'Owner2SSN') {

									$tmpValue = $appValue['CobrandedApplicationValues']['value'];

									if (preg_match('/([0-9]{3})-?([0-9]{2})-?([0-9]{4})/', $tmpValue, $matches)) {
										$tmpValue = $matches[1]."-".$matches[2]."-".$matches[3];
										$appValue['CobrandedApplicationValues']['value'] = $tmpValue;
									}
							}
	
							// is the value valid?
							$validValue =  $this->CobrandedApplicationValues->validApplicationValue($appValue['CobrandedApplicationValues'], $templateField['type'], $templateField);
							if ($validValue) {
								// save it
								$this->CobrandedApplicationValues->save($appValue);
							} else {
								// update our validationErrors array
								$typeStr = $this->TemplateField->fieldTypes[$templateField['type']];
								$response['validationErrors'] = Hash::insert($response['validationErrors'], $templateField['merge_field_name'], $typeStr);
							}
						}
					}
				}
			}

			unset($this->TemplateField);

			$response['success'] = (count($response['validationErrors']) == 0);
		}

		$cobrandedApplication = $this->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.id' => $createAppResponse['cobrandedApplication']['id']
				)
			)
		);

		$tmpResponse = $this->validateCobrandedApplication($cobrandedApplication);

		if ($response['success'] == false || $tmpResponse['success'] == false) {
			// delete the app
			$this->delete($createAppResponse['cobrandedApplication']['id']);
			$response['success'] = false;
			foreach ($tmpResponse['validationErrors'] as $key => $val) {
				$response['validationErrors'] = Hash::insert($response['validationErrors'], $key, $val);
			}
		} else {
			$this->Cobrand->id = $newApp['Template']['cobrand_id'];
			$this->Cobrand->recursive = -1;
			$this->Cobrand->find('first');
			$cobrand = $this->Cobrand->read();

			$response['application_id'] = $createAppResponse['cobrandedApplication']['id'];
			$response['application_url_for_email'] = Router::url('/cobranded_applications/edit/', true).$createAppResponse['cobrandedApplication']['uuid'];
			$response['response_url_type'] = $cobrand['Cobrand']['response_url_type'];
			$response['partner_name'] = $cobrand['Cobrand']['partner_name'];

			switch ($cobrand['Cobrand']['response_url_type']) {
				case 1: // return nothing
					break;

				case 2: // return RS signing url
					$client = $this->createRightSignatureClient();
					$templateGuid = $newApp['Template']['rightsignature_template_guid'];
					$getTemplateResponse = $this->getRightSignatureTemplate($client, $templateGuid);
					$getTemplateResponse = json_decode($getTemplateResponse, true);

					if ($getTemplateResponse && $getTemplateResponse['template']['type'] == 'Document' && $getTemplateResponse['template']['guid']) {
						$applicationXml = $this->createRightSignatureApplicationXml(
							$createAppResponse['cobrandedApplication']['id'], $this->Session->read('Auth.User.email'), $getTemplateResponse['template']);
							$createResponse = $this->createRightSignatureDocument($client, $applicationXml);
							$createResponse = json_decode($createResponse, true);

						if ($createResponse['document']['status'] == 'sent' && $createResponse['document']['guid']) {
							// save the guid
							$this->save(
								array(
									'CobrandedApplication' => array(
										'id' => $createAppResponse['cobrandedApplication']['id'],
										'rightsignature_document_guid' => $createResponse['document']['guid'],
										'status' => 'completed'
									)
								),
								array('validate' => false)
							);

							$response['application_url'] = Router::url('/cobranded_applications/sign_rightsignature_document', true).'?guid='.$createResponse['document']['guid'];
						} else {
							$response['validationErrors'] = Hash::insert($response['validationErrors'], 'error: ', $createResponse);
						}
					} else {
						$response['validationErrors'] = Hash::insert($response['validationErrors'], 'error: ', $getTemplateResponse);
					}
	
					break;

				case 3: // return online app url
					$response['application_url'] = Router::url('/cobranded_applications/edit/', true).$createAppResponse['cobrandedApplication']['uuid'];
					break;

				default: // return nothing
					break;
			}
		}
		
		$response['success'] = (count($response['validationErrors']) == 0);

		if ($response['success'] == false) {
			// delete the app
			$this->delete($createAppResponse['cobrandedApplication']['id']);
			$response['success'] = false;
		}

		return $response;
	}

/**
 * buildExportData
 *
 * @params
 *     $appId int
 *     $keys array
 *     $values array
 */
	public function buildExportData($appId, &$keys = '', &$values = '') {
		$options = array(
			'conditions' => array(
				'CobrandedApplication.' . $this->primaryKey => $appId
			),
		);
		$app = $this->find('first', $options);

		$keys = '"MID"';
		$values = '""';
		$referrals = array('','','');

		$this->TemplateField = ClassRegistry::init('TemplateField');

		foreach ($app['CobrandedApplicationValues'] as $appKey => $appValue) {
			// could use strrpos != false to check for these names
			if ($app['CobrandedApplicationValues'][$appKey]['name'] == 'AENotExisting' ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == 'AENotNew' ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == 'DiscNotNew' ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == 'NoAutoclose' ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == 'NoAutoClose_2' ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == 'term_accept_debitYes' ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == 'term_accept_debitNo' ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == 'term_accept_debit_2Yes' ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == 'term_accept_debit_2No' ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == 'QTY - PP1' ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == 'QTY - PP2') {

				// skip me
			} else if ($app['CobrandedApplicationValues'][$appKey]['name'] == "Referral1Business" ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == "Referral1Owner/Officer" ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == "Referral1Phone") {

				$referrals[0] = $referrals[0].' '.$app['CobrandedApplicationValues'][$appKey]['value'];
				if ($app['CobrandedApplicationValues'][$appKey]['name'] == "Referral1Phone") {
					$keys = $this->__addKey($keys, 'Referral1');
					$values = $this->__addValue($values, $referrals[0]);
				}
			} else if ($app['CobrandedApplicationValues'][$appKey]['name'] == "Referral2Business" ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == "Referral2Owner/Officer" ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == "Referral2Phone") {

				$referrals[1] = $referrals[1].' '.$app['CobrandedApplicationValues'][$appKey]['value'];
				if ($app['CobrandedApplicationValues'][$appKey]['name'] == "Referral2Phone") {
					$keys = $this->__addKey($keys, 'Referral2');
					$values = $this->__addValue($values, $referrals[1]);
				}
			} else if ($app['CobrandedApplicationValues'][$appKey]['name'] == "Referral3Business" ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == "Referral3Owner/Officer" ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == "Referral3Phone") {

				$referrals[2] = $referrals[2].' '.$app['CobrandedApplicationValues'][$appKey]['value'];
				if ($app['CobrandedApplicationValues'][$appKey]['name'] == "Referral3Phone") {
					$keys = $this->__addKey($keys, 'Referral3');
					$values = $this->__addValue($values, $referrals[2]);
				}
			} else {
				// just addit
				$keys = $this->__addKey($keys, $app['CobrandedApplicationValues'][$appKey]['name']);

				// look up the template field that this value was built from
				$templateField = $this->TemplateField->find(
					'first',
					array(
						'conditions' => array('id' => $app['CobrandedApplicationValues'][$appKey]['template_field_id']),
						'recursive' => -1
					)
				);
				if ($templateField['TemplateField']['type'] == 4 ||
					$templateField['TemplateField']['type'] == 5) {
					// dealing with a multiple option input/field
					// need to special case the OwnerType because it expects "Yes"/"Off" instead of "On"/"Off"
					if ($app['CobrandedApplicationValues'][$appKey]['value'] == 'true') {
						if ($this->__startsWith($app['CobrandedApplicationValues'][$appKey]['name'], "OwnerType-")) {
							$values = $this->__addValue($values, 'Yes');
						} else {
							$values = $this->__addValue($values, 'On');
						}
					} else {
						$values = $this->__addValue($values, 'Off');
					}
				} else {
					$values = $this->__addValue($values, $app['CobrandedApplicationValues'][$appKey]['value']);
				}
			}
		}
		unset($this->TemplateField);

		// add "oaID", "api", "aggregated" to the end of the keys and values
		$keys = $keys.',"oaID","api","aggregated"';
		$values = $values.',"'.$app['CobrandedApplication']['id'].'","",""';

		if (!empty($app['CobrandedApplicationAches'])) {
			foreach ($app['CobrandedApplicationAches'] as $index => $array) {
				foreach ($array as $key => $val) {
					if ($key == 'auth_type' || $key == 'routing_number' || $key == 'account_number') {
						$val = trim(mcrypt_decrypt(Configure::read('Cryptable.cipher'), Configure::read('Cryptable.key'),
									base64_decode($val), 'cbc', Configure::read('Cryptable.iv')));
					}
					$keys = $this->__addKey($keys, 'AddlACH-'.$key.'-'.$index);
					$values = $this->__addValue($values, $val);
				}
			}
		}
	}

/**
 * copyApplication
 * 
 * @params
 *     $appId int
 *     $userId int
 * @retuns
 *     true|false depending on if the application was copied or not
 */
	public function copyApplication($appId, $userId) {
		// create a new application for $userId
		// need to look up the template_id from the appId
		$app = $this->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.id' => $appId
				),
				'recursive' => -1,
				'contain' => array('CobrandedApplicationValues'),
			)
		);

		$this->create(
			array(
				'user_id' => $userId,
				'uuid' => String::uuid(),
				'template_id' => $app['CobrandedApplication']['template_id'],
				'status' => 'saved'
			)
		);
		if ($this->save()) {
			$newApp = $this->read();

			// copy each value over
			foreach ($app['CobrandedApplicationValues'] as $key => $value) {
				if ($app['CobrandedApplicationValues'][$key]['name'] == 'Unknown Type for testing') {
					// skip it
				} else {
					$newApp['CobrandedApplicationValues'][$key]['value'] = $app['CobrandedApplicationValues'][$key]['value'];
					$this->CobrandedApplicationValues->save($newApp['CobrandedApplicationValues'][$key]);
				}
			}
			return true;
		}
		return false;
	}

	public function findAppsByEmail($email) {
		$apps = $this->find(
			'all',
			array(
				'joins' => array(
					array(
						'alias' => 'CobrandedApplicationValue',
						'table' => 'onlineapp_cobranded_application_values',
						'type' => 'LEFT',
						'conditions' => '"CobrandedApplicationValue"."cobranded_application_id" = "CobrandedApplication"."id"',
					)
				),
				'conditions' => array(
					'CobrandedApplicationValue.value' => $email,
					// should probably check the state too
				),
				'group' => array('CobrandedApplication.id', 'User.id', 'Template.id', 'Merchant.merchant_id', 'Coversheet.id'),
				'order' => 'CobrandedApplication.created desc'
			)
		);

		return $apps;
	}

/**
 * sendFieldCompletionEmail
 * 
 * @params
 *     $email string
 * @returns
 *     $response array
 */
	public function sendFieldCompletionEmail($email) {
		$response = array(
			'success' => false,
			'msg' => 'Failed to send email to ['.$email.']. Please contact your rep.',
		);

		$apps = $this->findAppsByEmail($email);

		if (count($apps) == 0) {
			$response['msg'] = 'Could not find any applications with the specified email address.';
			return $response;
		} else {
			// send the email
			$timestamp = time();
			$link = Router::url('/cobranded_applications/index/', true).urlencode($email)."/{$timestamp}";

			$args = array(
				'from' => array('newapps@axiapayments.com' => 'Axia Online Applications'),
				'to' => $email,
				'subject' => 'Your Axia Applications',
				'format' => 'text',
				'template' => 'retrieve_applications',
				'viewVars' => array('email'=>$email, 'link'=>$link)
			);

			$response = $this->sendEmail($args);

			unset($args);

			if ($response['success'] == true) {
				$args['cobranded_application_id'] = $apps[0]['CobrandedApplication']['id'];
				$args['email_timeline_subject_id'] = EmailTimeline::COMPLETE_FIELDS;
				$args['recipient'] = $email;
				$response = $this->createEmailTimelineEntry($args);
				unset($args);
			}

			$dbaBusinessName = '';
			$ownerName = '';
			$ownerEmail = '';

			$valuesMap = $this->buildCobrandedApplicationValuesMap($apps[0]['CobrandedApplicationValues']);

			if (!empty($valuesMap['DBA'])) {
				$dbaBusinessName = $valuesMap['DBA'];
			}
			if (!empty($valuesMap['CorpContact'])) {
				$ownerName = $valuesMap['CorpContact'];
			}
			if (!empty($valuesMap['Owner1Email'])) {
				$ownerEmail = $valuesMap['Owner1Email'];
			}

			$response['dba'] = $dbaBusinessName;
			$response['email'] = $ownerEmail;
			$response['fullname'] = $ownerName;
		}
		return $response;
	}

/**
 * sendNewApiApplicationEmail
 * 
 * @params
 *     $args array
 * @returns
 *     $response array
 */

	public function sendNewApiApplicationEmail($args) {
		$from = array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications');
		if (key_exists('from', $args)) {
			$from = $args['from'];
		}

		$to = EmailTimeline::DATA_ENTRY_EMAIL;
		if (key_exists('to', $args)) {
			$to = $args['to'];
		}

		$viewVars = array();
		$viewVars['recipient'] = 'Axia Data Entry';

		$viewVars['cobrand'] = '';

		if (key_exists('cobrand', $args)) {
			$viewVars['cobrand'] = $args['cobrand'];
		} 

		$viewVars['link'] = '';
		
		if (key_exists('link', $args)) {
			$viewVars['link'] = $args['link'];
		}

		$attachments = null;
		if (key_exists('attachments', $args)) {
			$attachments = $args['attachments'];
		}

		$args = array(
			'from' => $from,
			'to' => $to,
			'subject' => 'New API Axia Application',
			'format' => 'text',
			'template' => 'new_api_application',
			'viewVars' => $viewVars,
			'attachments' => $attachments
		);

		$response = $this->sendEmail($args);

		return $response;
	}

/**
 * sendApplicationForSigningEmail
 * 
 * @params
 *     $applicationId int
 * @returns
 *     $response array
 */
	public function sendApplicationForSigningEmail($applicationId) {
		$response = array(
			'success' => false,
			'msg' => 'Missing owner information.',
		);

		if (!$this->exists($applicationId)) {
			$response['msg'] = 'Invalid application.';
			return $response;
		}

		$this->id = $applicationId;
		$cobrandedApplication = $this->read();

		$dbaBusinessName = '';

		$owners = array();
		$valuesMap = $this->buildCobrandedApplicationValuesMap($cobrandedApplication['CobrandedApplicationValues']);

		if (!empty($valuesMap['Owner1Email'])) {
			$owners['owner1']['email'] = $valuesMap['Owner1Email'];	
		}
		if (!empty($valuesMap['Owner1Name'])) {
			$owners['owner1']['fullname'] = $valuesMap['Owner1Name'];	
		}
		if (!empty($valuesMap['Owner2Email'])) {
			$owners['owner2']['email'] = $valuesMap['Owner2Email'];	
		}
		if (!empty($valuesMap['Owner2Name'])) {
			$owners['owner2']['fullname'] = $valuesMap['Owner2Name'];
		}
		if (!empty($valuesMap['DBA'])) {
			$dbaBusinessName = $valuesMap['DBA'];
		}

		foreach ($owners as $key => $val) {
			$response['msg'] = '';
			
			$ownerEmail = $owners[$key]['email'];
			$ownerFullname = $owners[$key]['fullname'];

			$from = array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications');
			$to = $ownerEmail;
			$subject = $dbaBusinessName.' - Merchant Application';
			$format = 'both';
			$template = 'email_app';
			$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : exec("hostname");
			$viewVars = array();
			$viewVars['url'] = "https://".$hostname."/cobranded_applications/sign_rightsignature_document?guid=".$cobrandedApplication['CobrandedApplication']['rightsignature_document_guid'];
			$viewVars['ownerName'] = $ownerFullname;
			$viewVars['merchant'] = $dbaBusinessName;

			$args = array(
				'from' => $from,
				'to' => $to,
				'subject' => $subject,
				'format' => $format,
				'template' => $template,
				'viewVars' => $viewVars
			);

			$response = $this->sendEmail($args);
			unset($args);

			if ($response['success'] == true) {
				$args['cobranded_application_id'] = $applicationId;
				$args['email_timeline_subject_id'] = EmailTimeline::SENT_FOR_SIGNING;
				$args['recipient'] = $ownerEmail;
				$response = $this->createEmailTimelineEntry($args);
				unset($args);
			}
		}

		return $response;
	}

/**
 * sendForCompletion
 * 
 * @params
 *     $applicationId int
 * @returns
 *     $response array
 */
	public function sendForCompletion($applicationId) {
		if (!$this->exists($applicationId)) {
			$response = array(
				'success' => false,
				'msg' => 'Invalid application.',
			);
			return $response;
		}

		$this->id = $applicationId;
		$cobrandedApplication = $this->read();

		$hash = $cobrandedApplication['CobrandedApplication']['uuid'];

		$dbaBusinessName = '';
		$ownerName = '';
		$ownerEmail = '';

		$valuesMap = $this->buildCobrandedApplicationValuesMap($cobrandedApplication['CobrandedApplicationValues']);

		if (!empty($valuesMap['DBA'])) {
			$dbaBusinessName = $valuesMap['DBA'];
		}
		if (!empty($valuesMap['CorpContact'])) {
			$ownerName = $valuesMap['CorpContact'];
		}
		if (!empty($valuesMap['Owner1Email'])) {
			$ownerEmail = $valuesMap['Owner1Email'];
		}
			
		$from = array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications');
		$to = $ownerEmail;
		$subject = 'Your Axia Applications';
		$format = 'text';
		$template = 'retrieve_applications';
		$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : exec("hostname");
		$viewVars = array();
		$viewVars['email'] = $ownerEmail;
		$viewVars['dba'] = $dbaBusinessName;
		$viewVars['fullname'] = $ownerName;
		$viewVars['hash'] = $hash;
		$viewVars['link'] = "https://".$hostname."/cobranded_applications/edit/".$hash;
		$viewVars['ownerName'] = $ownerName;


		$args = array(
			'from' => $from,
			'to' => $to,
			'subject' => $subject,
			'format' => $format,
			'template' => $template,
			'viewVars' => $viewVars
		);

		$response = $this->sendEmail($args);
		
		unset($args);

		if ($response['success'] == true) {
			$args['cobranded_application_id'] = $applicationId;
			$args['email_timeline_subject_id'] = EmailTimeline::COMPLETE_FIELDS;
			$args['recipient'] = $ownerEmail;
			$response = $this->createEmailTimelineEntry($args);
			unset($args);
		}

		$response['dba'] = $dbaBusinessName;
		$response['email'] = $ownerEmail;
		$response['fullname'] = $ownerName;

		return $response;
	}

/**
 * repNotifySignedEmail
 * 
 * @params
 *     $applicationId int
 * @returns
 *     $response array
 */
	public function repNotifySignedEmail($applicationId) {
		if (!$this->exists($applicationId)) {
			$response = array(
				'success' => false,
				'msg' => 'Invalid application.',
			);
			return $response;
		}
		
		$this->id = $applicationId;
		$cobrandedApplication = $this->read();

		$dbaBusinessName = '';
		$valuesMap = $this->buildCobrandedApplicationValuesMap($cobrandedApplication['CobrandedApplicationValues']);

		if (!empty($valuesMap['DBA'])) {
			$dbaBusinessName = $valuesMap['DBA'];
		}
			
		$from = array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications');
		$to = $cobrandedApplication['User']['email'];
		$subject = $dbaBusinessName.' - Online Application Signed';
		$format = 'text';
		$template = 'rep_notify_signed';
		$viewVars = array();
		$viewVars['rep'] = $cobrandedApplication['User']['email'];
		$viewVars['merchant'] = $dbaBusinessName;
		$viewVars['link'] = Router::url('/users/login', true);

		$args = array(
			'from' => $from,
			'to' => $to,
			'subject' => $subject,
			'format' => $format,
			'template' => $template,
			'viewVars' => $viewVars
		);

		$response = $this->sendEmail($args);
		unset($args);

		if ($response['success'] == true) {
			$args['cobranded_application_id'] = $applicationId;
			$args['email_timeline_subject_id'] = EmailTimeline::MERCHANT_SIGNED;
			$args['recipient'] = $cobrandedApplication['User']['email'];
			$response = $this->createEmailTimelineEntry($args);
		}

		return $response;
	}

/**
 * sendRightsignatureInstallSheetEmail
 *
 * @params
 *     $applicationId int
 *     $email string
 */
	public function sendRightsignatureInstallSheetEmail($applicationId, $email) {
		if (!$this->exists($applicationId)) {
			$response = array(
				'success' => false,
				'msg' => 'Invalid application.',
			);
			return $response;
		}
		
		$this->id = $applicationId;
		$cobrandedApplication = $this->read();

		$dbaBusinessName = '';
		$ownerName = '';

		$valuesMap = $this->buildCobrandedApplicationValuesMap($cobrandedApplication['CobrandedApplicationValues']);

		if (!empty($valuesMap['DBA'])) {
			$dbaBusinessName = $valuesMap['DBA'];
		}
		if (!empty($valuesMap['CorpContact'])) {
			$ownerName = $valuesMap['CorpContact'];
		}
			
		$from = array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications');
		$to = $email;
		$subject = $dbaBusinessName.' - Install Sheet';
		$format = 'both';
		$template = 'email_install_var';
		$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : exec("hostname");
		$viewVars = array();
		$viewVars['ownerName'] = $ownerName;
		$viewVars['merchant'] = $dbaBusinessName;
		$viewVars['url'] = "https://".$hostname."/cobranded_applications/sign_rightsignature_document?guid=".
			$cobrandedApplication['CobrandedApplication']['rightsignature_install_document_guid'];

		$args = array(
			'from' => $from,
			'to' => $to,
			'subject' => $subject,
			'format' => $format,
			'template' => $template,
			'viewVars' => $viewVars
		);

		$response = $this->sendEmail($args);
		unset($args);

		if ($response['success'] == true) {
			$args['cobranded_application_id'] = $applicationId;
			$args['email_timeline_subject_id'] = EmailTimeline::INSTALL_SHEET_VAR;
			$args['recipient'] = $email;
			$response = $this->createEmailTimelineEntry($args);
		}

		return $response;
	}

/**
 * sendEmail
 * 
 * @params
 *     $args array
 * @returns
 *     $response array
 */
	public function sendEmail($args) {
		$response = array(
			'success' => false,
			'msg' => 'Failed to send email.',
		);

		if (!$this->CakeEmail) {
			$this->CakeEmail = new CakeEmail('default');
		}

		if (key_exists('from', $args)) {
			$this->CakeEmail->from($args['from']);

		} else {
			$response['msg'] = 'from argument is missing.';
			return $response;
		}

		if (key_exists('to', $args)) {
			if (Validation::email($args['to'])) {
				$this->CakeEmail->to($args['to']);
			}
			else {
				$response['msg'] = 'invalid email address submitted.';
				return $response;
			}
		} else {
			$response['msg'] = 'to argument is missing.';
			return $response;
		}

		$subject = 'No subject';
		if (key_exists('subject', $args)) {
			$subject = $args['subject'];
		}

		$this->CakeEmail->subject($subject);

		if (key_exists('format', $args)) {
			$this->CakeEmail->emailFormat($args['format']);
		}

		if (key_exists('template', $args)) {
			$this->CakeEmail->template($args['template']);
		}

		if (key_exists('viewVars', $args)) {
			$this->CakeEmail->viewVars($args['viewVars']);
		}

		if (key_exists('attachments', $args) && !empty($args['attachments'])) {
			$this->CakeEmail->attachments($args['attachments']);
		}

		if ($this->CakeEmail->send()) {
			$response['success'] = true;
			$response['msg'] = '';
		}
	
		return $response;
	}

/**
 * createNewApiApplicationEmailTimelineEntry
 * 
 * @params
 *     $args array
 * @returns
 *     $response array
 */
	public function createNewApiApplicationEmailTimelineEntry($args) {
		$args['email_timeline_subject_id'] = EmailTimeline::NEW_API_APPLICATION;
		$args['recipient'] = EmailTimeline::DATA_ENTRY_EMAIL;
		$response = $this->createEmailTimelineEntry($args);
		return $response;
	}

/**
 * createEmailTimelineEntry
 * 
 * @params
 *     $args array
 * @returns
 *     $response array
 */
	public function createEmailTimelineEntry($args) {
		$response = array(
			'success' => false,
			'msg' => 'Failed to create email timeline entry.',
		);

		$EmailTimeline = ClassRegistry::init('EmailTimeline');
		
		if (!key_exists('cobranded_application_id', $args)) {
			$response['msg'] = 'cobranded_application_id argument is missing.';
			return $response;
		}
	
		if (!key_exists('email_timeline_subject_id', $args)) {
			$response['msg'] = 'email_timeline_subject_id argument is missing.';
			return $response;
		}

		if (!key_exists('recipient', $args)) {
			$response['msg'] = 'recipient argument is missing.';
			return $response;
		}

		$EmailTimeline->create();
		$success = $EmailTimeline->save(
			array(
				'cobranded_application_id' => $args['cobranded_application_id'],
				'date' => DboSource::expression('NOW()'),
				'email_timeline_subject_id' => $args['email_timeline_subject_id'],
				'recipient' => $args['recipient']
			)
		);

		if ($success) {
			$response['success'] = true;
			$response['msg'] = '';
		}

		return $response;
	}

/**
 * getRightSignatureTemplate
 * 
 * @params
 *     $client object
 *     $templateGuid string
 * @returns
 *     $response array
 */
	public function getRightSignatureTemplate($client, $templateGuid) {
		$response = $client->post('/api/templates/'.$templateGuid.'/prepackage.json',
			"<?xml version='1.0' encoding='UTF-8'?><callback_location></callback_location>");
		return $response;
	}

/**
 * createRightSignatureDocument
 * 
 * @params
 *     $client object
 *     $applicationXml string
 * @returns
 *     $response array
 */
	public function createRightSignatureDocument($client, $applicationXml) {
		$response = $client->post('/api/templates.json', $applicationXml);
		return $response;
	}

/**
 * extendRightSignatureDocumentLife
 * 
 * @params
 *     $client object
 *     $documentGuid string
 * @returns
 *     $response array
 */
	public function extendRightSignatureDocumentLife($client, $documentGuid) {
		$response = $client->post('/api/documents/'.$documentGuid.'/extend_expiration.xml');
		return $response;
	}

/**
 * getRightSignatureTemplateDetails
 * 
 * @params
 *     $client object
 *     $templateGuid string
 * @returns
 *     $response array
 */
	public function getRightSignatureTemplateDetails($client, $templateGuid) {
		$response = $client->signAndSendRequest("GET", '/api/templates/'.$templateGuid.'.json');
		return $response;
	}

/**
 * getRightSignatureSignerLinks
 *
 * @params
 *     $client object
 *     $documentGuid string
 * @returns
 *     $response array
 */
	public function getRightSignatureSignerLinks($client, $documentGuid) {
		$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : exec("hostname");
		$response = $client->getSignerLinks($documentGuid, "https://".$hostname."/cobranded_applications/sign_rightsignature_document?guid=".$documentGuid);
		return $response;
	}

/**
 * createRightSignatureClient
 * 
 * @params
 *     none
 * @returns
 *     $client
 */
	public function createRightSignatureClient() {
		App::import('Vendor', 'oauth', array('file' => 'OAuth' . DS . 'rightsignature.php'));
		
		$rightsignature = new RightSignature('J7PQlPSlm3jaa2DbfCP989mIFrKRHUH1NqcjJugT', 'ZAYx4jEy6BVYPuad4kPQAw6lTrOxAeqWU8DGT6A1');
		$rightsignature->request_token = new OAuthConsumer('v1cfHXdnHbD8in6ruqsb3MDVbuhdtZMaHTKVw1XI', 'tTyOsXYMAgoPQY5NXlsB9sKAYRZXsuLIcBzTiOpB', 1);
		$rightsignature->access_token = new OAuthConsumer('FvpRze1k6JbP7HHm64IxQiWLHL9p0Jl4pw3x7PBP', 'cHrzepxhF7t9QMyO8CGUJlbSg4Lon23JEVYnD70Z', 1);
		$rightsignature->oauth_verifier = 'jmV0StucajLmdz2gc7hw';

		return $rightsignature;
	}

/**
 * createRightSignatureApplicationXml
 * 
 * @params
 *     $applicationId int
 *     $sender string
 *     $rightSignatureTemplate array
 *     $subject string
 * @returns
 *     $xml string
 */
	public function createRightSignatureApplicationXml($applicationId, $sender, $rightSignatureTemplate, $subject = null) {
		$cobrandedApplication = $this->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.id' => $applicationId
				),
				'contain' => array(
					'User',
					'Template',
					'Merchant' => array('EquipmentProgramming'),
					'CobrandedApplicationValues'
				),
				'recursive' => 2
			)
		);

		$owner1Fullname = '';
		$owner2Fullname = '';
		$dbaBusinessName = '';

		$valuesMap = $this->buildCobrandedApplicationValuesMap($cobrandedApplication['CobrandedApplicationValues']);

		if (!empty($valuesMap['DBA'])) {
			$dbaBusinessName = $valuesMap['DBA'];
		}
		if (!empty($valuesMap['Owner1Name'])) {
			$owner1Fullname = $valuesMap['Owner1Name'];
		}
		if (!empty($valuesMap['Owner2Name'])) {
			$owner2Fullname = $valuesMap['Owner2Name'];
		}

		$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : exec("hostname");

		$xml  = "<?xml version='1.0' encoding='UTF-8'?>\n";
		$xml .= "	<template>\n";
		$xml .= "		<guid>".$rightSignatureTemplate['guid']."</guid>\n";
		if ($subject == null) {
			$xml .= "		<subject>".htmlspecialchars($dbaBusinessName)." Axia Merchant Application</subject>\n";
		} else {
			$xml .= "		<subject>".htmlspecialchars($dbaBusinessName)." ".$subject."</subject>\n";
		}
		$xml .= "		<description>Sent for signature by ".$sender."</description>\n";
		$xml .= "		<action>send</action>\n";
		$xml .= "		<expires_in>10 days</expires_in>\n";
		$xml .= "		<roles>\n";

        if ($subject == 'Axia Install Sheet - VAR') {
        	if (!empty($owner1Fullname)) {
        		$xml .= "			<role role_name='Signor'>\n";
        		$xml .= "				<name>".htmlspecialchars($owner1Fullname )."</name>\n";
        		$xml .= "				<email>".htmlspecialchars('noemail@rightsignature.com')."</email>\n";
        		$xml .= "				<locked>true</locked>\n";
        		$xml .= "			</role>\n";
        	}
        } else {
			if (!empty($owner1Fullname)) {
				$xml .= "			<role role_name='Owner/Officer 1 PG'>\n";
        		$xml .= "				<name>".htmlspecialchars($owner1Fullname )."</name>\n";
        		$xml .= "				<email>".htmlspecialchars('noemail@rightsignature.com')."</email>\n";
        		$xml .= "				<locked>true</locked>\n";
	        	$xml .= "			</role>\n";
    	    	$xml .= "			<role role_name='Owner/Officer 1'>\n";
				$xml .= "				<name>".htmlspecialchars($owner1Fullname )."</name>\n";
				$xml .= "				<email>".htmlspecialchars('noemail@rightsignature.com')."</email>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</role>\n";
			}

	        if (!empty($owner2Fullname)) {
				$xml .= "			<role role_name='Owner/Officer 2 PG'>\n";
				$xml .= "				<name>".htmlspecialchars($owner2Fullname)."</name>\n";
				$xml .= "				<email>".htmlspecialchars('noemail@rightsignature.com')."</email>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</role>\n";
				$xml .= "			<role role_name='Owner/Officer 2'>\n";
				$xml .= "				<name>".htmlspecialchars($owner2Fullname)."</name>\n";
				$xml .= "				<email>".htmlspecialchars('noemail@rightsignature.com')."</email>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</role>\n";
    	    }
    	}

		$xml .= "		</roles>\n";		
		$xml .= "		<merge_fields>\n";

		foreach ($rightSignatureTemplate['merge_fields'] as $mergeField) {
			$appValue = $this->CobrandedApplicationValues->find(
				'first',
				array(
					'conditions' => array(
						'cobranded_application_id' => $applicationId,
						'CobrandedApplicationValues.name' => $mergeField['name']
					),
				)
			);

			// we don't want to send null or empty values
			if (isset($appValue['CobrandedApplicationValues']['value'])) {
				$fieldType = $appValue['TemplateField']['type'];

				// type 3 is checkbox, 4 is radio
				// we send different elements for multi option types
				if ($fieldType == 3 || $fieldType == 4) {
					$xml .= "			<merge_field merge_field_name='".$mergeField['name']."'>\n";
					$xml .= "				<value>X</value>\n";
					$xml .= "				<locked>true</locked>\n";
					$xml .= "			</merge_field>\n";
				} else {
					$xml .= "			<merge_field merge_field_name='".$mergeField['name']."'>\n";
					$xml .= "				<value>".htmlspecialchars($appValue['CobrandedApplicationValues']['value'])."</value>\n";
					$xml .= "				<locked>true</locked>\n";
					$xml .= "			</merge_field>\n";
				}
			}

			if ($mergeField['name'] == "SystemType") {
				$xml .= "			<merge_field merge_field_name='".$mergeField['name']."'>\n";
				foreach ($cobrandedApplication['Merchant']['EquipmentProgramming'] as $programming) {
					$xml .= "				<value>".htmlspecialchars($programming['terminal_type'])."</value>\n";
				}
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</merge_field>\n";
			}

			if ($mergeField['name'] == "MID") {
				$xml .= "			<merge_field merge_field_name='".$mergeField['name']."'>\n";
				$xml .= "				<value>".htmlspecialchars($cobrandedApplication['Merchant']['merchant_id'])."</value>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</merge_field>\n";
			}
		}

		if ($subject == 'Axia Install Sheet - VAR') {
			$xml .= "			<merge_field merge_field_name='Phone#'>\n";
			if ($cobrandedApplication['User']['extension'] != "") {
				$xml .= "				<value>".htmlspecialchars('877.875.6114' . " x " . $cobrandedApplication['User']['extension'])."</value>\n";
			} else {
				$xml .= "				<value>".htmlspecialchars('877.875.6114')."</value>\n";
			}
			$xml .= "				<locked>true</locked>\n";
			$xml .= "			</merge_field>\n";

			$xml .= "			<merge_field merge_field_name='RepFax#'>\n";
			$xml .= "				<value>".htmlspecialchars('877.875.5135')."</value>\n";
			$xml .= "				<locked>true</locked>\n";
			$xml .= "			</merge_field>\n";
		}

		$now = date('m/d/Y');

		$xml .= "			<merge_field merge_field_name='Application Date'>\n";
		$xml .= "				<value>".htmlspecialchars($now)."</value>\n";
		$xml .= "				<locked>true</locked>\n";
		$xml .= "			</merge_field>\n";

		$xml .= "		</merge_fields>\n";
		$xml .= "		<callback_location>http://".$hostname."/cobranded_applications/document_callback</callback_location>\n";
		$xml .= "	</template>\n";

		return $xml;
	}

/**
 * buildCobrandedApplicationValuesMap
 * 
 * @params
 *     $cobrandedApplicationValues array
 *
 * @returns
 *     $valuesMap array
 */
	public function buildCobrandedApplicationValuesMap($cobrandedApplicationValues) {
		$valuesMap = array();
		if (!empty($cobrandedApplicationValues)) {
			foreach ($cobrandedApplicationValues as $val) {
				$valuesMap[$val['name']] = $val['value'];
			}
		}
		return $valuesMap;
	}

/**
 * validateCobrandedApplication
 * 
 * @params
 *     $cobrandedApplication array
 *     $source string
 *
 * @returns
 *     $response array
 */
	public function validateCobrandedApplication($cobrandedApplication, $source = null) {
		$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');

		$response['success'] = false;
		$response['validationErrors'] = array();

		$isNonProfit = false;
		$owner1Equity = 0;

		$methodofSalesPage;
		$methodofSalesTotal = 0;
		$methodofSalesCardNotPresentInternet = 0;
 		$methodofSalesCardNotPresentKeyed = 0;
 		$methodofSalesCardPresentImprint = 0;
 		$methodofSalesCardPresentSwiped = 0;

 		$productSoldDirectPage;
		$productSoldDirectTotal = 0;
 		$productSoldDirectToGovernment = 0;
 		$productSoldDirectToCustomer = 0;
 		$productSoldDirectToBusiness = 0;

 		$percentOfPayPage;
		$percentOfPayTotal = 0;
 		$percentFullPayUpFront = 0;
 		$percentPartialPayUpFront = 0;
 		$percentAndWithin = 0;
 		$percentPayReceivedAfter = 0;

 		$ownerEquityPage;
 		$ownerEquityTotal = 0;
 		$owner1Equity = 0;
 		$owner2Equity = 0;

		foreach ($cobrandedApplication['CobrandedApplicationValues'] as $tmpVal) {
			if ($tmpVal['name'] == 'OwnerType-NonProfit' && $tmpVal['value'] == true) {
				$isNonProfit = true;
			}

			if ($tmpVal['name'] == 'Owner1Equity') {
				$owner1Equity = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'MethodofSales-CardNotPresent-Internet') {
				$methodofSalesCardNotPresentInternet = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'MethodofSales-CardNotPresent-Keyed') {
				$methodofSalesCardNotPresentKeyed = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'MethodofSales-CardPresentImprint') {
				$methodofSalesCardPresentImprint = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'MethodofSales-CardPresentSwiped') {
				$methodofSalesCardPresentSwiped = $tmpVal['value'];
			}

			if ($tmpVal['name'] == '%OfProductSoldDirectToGovernment') {
				$productSoldDirectToGovernment = $tmpVal['value'];
			}

			if ($tmpVal['name'] == '%OfProductSoldDirectToCustomer') {
				$productSoldDirectToCustomer = $tmpVal['value'];
			}

			if ($tmpVal['name'] == '%OfProductSoldDirectToBusiness') {
				$productSoldDirectToBusiness = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'PercentFullPayUpFront') {
				$percentFullPayUpFront = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'PercentPartialPayUpFront') {
				$percentPartialPayUpFront = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'PercentAndWithin') {
				$percentAndWithin = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'PercentPayReceivedAfter') {
				$percentPayReceivedAfter = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'Owner1Equity') {
				$owner1Equity = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'Owner2Equity') {
				$owner2Equity = $tmpVal['value'];
			}
		}

		$methodofSalesTotal = $methodofSalesCardNotPresentInternet + $methodofSalesCardNotPresentKeyed + $methodofSalesCardPresentImprint + $methodofSalesCardPresentSwiped;

		$productSoldDirectTotal = $productSoldDirectToGovernment + $productSoldDirectToCustomer + $productSoldDirectToBusiness;

		$percentOfPayTotal =  $percentFullPayUpFront + $percentPartialPayUpFront + $percentPayReceivedAfter + $percentAndWithin;

		$ownerEquityTotal = $owner1Equity + $owner2Equity;

		$template = $this->Template->find('first', array(
			'conditions' => array('Template.id' => $cobrandedApplication['CobrandedApplication']['template_id']),
			'contain' => array(
				'TemplatePages' => array(
					'TemplateSections' => array(
						'TemplateFields' => array(
							'fields' => array('id', 'type', 'name', 'default_value', 'merge_field_name', 'order', 'width', 'required')
						)
					),
				),
			),
		));

		foreach ($template['TemplatePages'] as $page) {
			$pageOrder = $page['order'];
			$pageOrder++;
			foreach ($page['TemplateSections'] as $section) {
				foreach ($section['TemplateFields'] as $templateField) {
					$fieldName = $templateField['name'];

					if ($templateField['merge_field_name'] == 'MethodofSales-') {
						$methodofSalesPage = $pageOrder;
					}

					if ($templateField['merge_field_name'] == '%OfProductSold') {
						$productSoldDirectPage = $pageOrder;
					}

					if ($templateField['merge_field_name'] == 'PercentFullPayUpFront') {
						$percentOfPayPage = $pageOrder;
					}

					if ($templateField['merge_field_name'] == 'Owner1Equity') {
						$ownerEquityPage = $pageOrder;
					}

					// Owner2 information should be required if Owner1Equity < 40
					if ($owner1Equity < 40) {
						if ($templateField['merge_field_name'] == 'Owner2Name' ||
							$templateField['merge_field_name'] == 'Owner2Title' ||
							$templateField['merge_field_name'] == 'Owner2Address' ||
							$templateField['merge_field_name'] == 'Owner2City' ||
							$templateField['merge_field_name'] == 'Owner2State' ||
							$templateField['merge_field_name'] == 'Owner2Zip' ||
							$templateField['merge_field_name'] == 'Owner2Phone' ||
							$templateField['merge_field_name'] == 'Owner2Fax' ||
							$templateField['merge_field_name'] == 'Owner2DOB' ||
							$templateField['merge_field_name'] == 'Owner2SSN' ||
							$templateField['merge_field_name'] == 'Owner2Email' ||
							$templateField['merge_field_name'] == 'Owner2Equity') {

							$templateField['required'] = true;
						}
					}


					if ($templateField['required'] == true) {
						// SSN should not be required if Ownership Type is Non Profit
						if (($templateField['merge_field_name'] == 'OwnerSSN' || $templateField['merge_field_name'] == 'Owner2SSN') && $isNonProfit) {
							continue;
						}

						$found = false;

						foreach ($cobrandedApplication['CobrandedApplicationValues'] as $tmpVal) {
							if ($tmpVal['template_field_id'] == $templateField['id'] && empty($tmpVal['value']) == false) {
								// is the value valid?
								$validValue =  $this->CobrandedApplicationValue->validApplicationValue($tmpVal, $templateField['type'], $templateField);
								if ($validValue == true) {
									$found = true;

									// federal tax id should be 12-3456789
									if ($templateField['merge_field_name'] == 'TaxID') {
										if (!preg_match("/^\d{2}-\d{7}$/", $tmpVal['value'])) {
											$found = false;
										}
									}

									// existing SE# should not be longer than 10 digits
									if ($templateField['merge_field_name'] == 'AmexNum') {
										if (strlen($tmpVal['value']) > 10) {
											$found = false;
										}
									}
								}
							}
						}

						if ($found == false) {
							// update our validationErrors array
							$response['validationErrors'] = Hash::insert($response['validationErrors'], $templateField['merge_field_name'], 'required');

							$errorArray = array();
							$errorArray['fieldName'] = $fieldName;
							$errorArray['mergeFieldName'] = $templateField['merge_field_name'];
							$errorArray['msg'] = 'Required field is empty: '.$fieldName;
							$errorArray['page'] = $pageOrder;
							
							$response['validationErrorsArray'][] = $errorArray;
						}
					}
				}
			}
		}

		if ($methodofSalesTotal < 100 && $source == 'ui') {
			// update our validationErrors array
			$response['validationErrors'] = Hash::insert($response['validationErrors'], 'MethodofSales_Total', 'less than 100');

			$errorArray = array();
			$errorArray['fieldName'] = 'Method of Sales Total';
			$errorArray['mergeFieldName'] = 'MethodofSales_Total';
			$errorArray['msg'] = 'Method of Sales Total is less than 100';
			$errorArray['page'] = $methodofSalesPage;
							
			$response['validationErrorsArray'][] = $errorArray;
		}

		if ($productSoldDirectTotal < 100 && $source == 'ui') {
			// update our validationErrors array
			$response['validationErrors'] = Hash::insert($response['validationErrors'], 'ofProductSold_Total', 'less than 100');

			$errorArray = array();
			$errorArray['fieldName'] = '% of Product Sold';
			$errorArray['mergeFieldName'] = 'ofProductSold_Total';
			$errorArray['msg'] = '% of Product Sold Total is less than 100';
			$errorArray['page'] = $productSoldDirectPage;
							
			$response['validationErrorsArray'][] = $errorArray;
		}

		if ($percentOfPayTotal < 100 && $source == 'ui') {
			// update our validationErrors array
			$response['validationErrors'] = Hash::insert($response['validationErrors'], 'PercentFullPayUpFront', 'less than 100');

			$errorArray = array();
			$errorArray['fieldName'] = 'Percent Full Pay Up Front';
			$errorArray['mergeFieldName'] = 'PercentFullPayUpFront';
			$errorArray['msg'] = '';
			$errorArray['page'] = $percentOfPayPage;
							
			$response['validationErrorsArray'][] = $errorArray;

			$response['validationErrors'] = Hash::insert($response['validationErrors'], 'PercentPartialPayUpFront', 'less than 100');

			$errorArray = array();
			$errorArray['fieldName'] = 'Percent Partial Pay Up Front';
			$errorArray['mergeFieldName'] = 'PercentPartialPayUpFront';
			$errorArray['msg'] = '';
			$errorArray['page'] = $percentOfPayPage;
							
			$response['validationErrorsArray'][] = $errorArray;

			$response['validationErrors'] = Hash::insert($response['validationErrors'], 'PercentAndWithin', 'less than 100');

			$errorArray = array();
			$errorArray['fieldName'] = 'Percent And Within';
			$errorArray['mergeFieldName'] = 'PercentAndWithin';
			$errorArray['msg'] = '';
			$errorArray['page'] = $percentOfPayPage;
							
			$response['validationErrorsArray'][] = $errorArray;

			$response['validationErrors'] = Hash::insert($response['validationErrors'], 'PercentPayReceivedAfter', 'less than 100');

			$errorArray = array();
			$errorArray['fieldName'] = 'Percent Pay Received After';
			$errorArray['mergeFieldName'] = 'PercentPayReceivedAfter';
			$errorArray['msg'] = '';
			$errorArray['page'] = $percentOfPayPage;
							
			$response['validationErrorsArray'][] = $errorArray;
		}

		if ($ownerEquityTotal > 100 && $source == 'ui') {
			// update our validationErrors array
			$response['validationErrors'] = Hash::insert($response['validationErrors'], 'Owner1Equity', 'owner equity is greater than 100%');

			$errorArray = array();
			$errorArray['fieldName'] = 'Owner 1 Equity';
			$errorArray['mergeFieldName'] = 'Owner1Equity';
			$errorArray['msg'] = 'owner equity is greater than 100%';
			$errorArray['page'] = $ownerEquityPage;
							
			$response['validationErrorsArray'][] = $errorArray;

			$response['validationErrors'] = Hash::insert($response['validationErrors'], 'Owner2Equity', 'owner equity is greater than 100%');

			$errorArray = array();
			$errorArray['fieldName'] = 'Owner 2 Equity';
			$errorArray['mergeFieldName'] = 'Owner2Equity';
			$errorArray['msg'] = 'owner equity is greater than 100%';
			$errorArray['page'] = $ownerEquityPage;
							
			$response['validationErrorsArray'][] = $errorArray;
		}

		if (count($response['validationErrors']) == 0) {
			$response['success'] = true;
			$response['msg'] = '';
		}

		return $response;
	}
	
/**
 * Return options array to be used for custom pagination and filtering
 *
 * @return array
 */
	
	protected function _findIndex($state, $query, $results = array()) {
		if ($state === 'before') {
			$query['fields'] = array(
				'DISTINCT CobrandedApplication.id',
				'CobrandedApplication.user_id',
				'CobrandedApplication.template_id',
				'CobrandedApplication.uuid',
				'CobrandedApplication.modified',
				'CobrandedApplication.rightsignature_document_guid',
				'CobrandedApplication.status',
				'CobrandedApplication.rightsignature_install_document_guid',
				'CobrandedApplication.rightsignature_install_status',
				'Template.id',
				'Template.name',
				'User.id',
				'User.firstname',
				'User.lastname',
				'User.email',
				'Coversheet.id',
				'Dba.value',
				'CorpName.value',
				'CorpContact.value',
			);
			$query['recursive'] = -1;
			$query['joins'] = array(
				array('table' => 'onlineapp_cobranded_application_values',
					'alias' => 'Dba',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = Dba.cobranded_application_id and Dba.name =' . "'DBA'",
					),
				),
				array('table' => 'onlineapp_cobranded_application_values',
					'alias' => 'CorpName',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = CorpName.cobranded_application_id and CorpName.name =' . "'CorpName'",
					),
				),
				array('table' => 'onlineapp_cobranded_application_values',
					'alias' => 'CorpContact',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = CorpContact.cobranded_application_id and CorpContact.name =' . "'CorpContact'",
					),
				),
				array('table' => 'onlineapp_templates',
					'alias' => 'Template',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.template_id = Template.id',
					),
				),				
				array('table' => 'onlineapp_users',
					'alias' => 'User',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.user_id = User.id',
					),
				),
				array('table' => 'onlineapp_coversheets',
					'alias' => 'Coversheet',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = Coversheet.cobranded_application_id',
					),
				),
			);
			//Because we are using a key value store for the application values instead of abiding by cake conventions 
			//we have to manipulate the count parameters to get the appropriate results 
			if (!empty($query['operation']) && $query['operation'] === 'count') {
				if (isset($query['sort'])) {
					unset($query['sort']);
				}
				return $query;
				
			}
			return $query;
		}
		return $results;
	}

/**
 * Array of Arguments to be used by the search plugin
 */
	public $filterArgs = array(
		'search' => array('type' => 'query', 'method' => 'orConditions'),
		'user_id' => array('type' => 'value'),
		'status' => array('type' => 'value')
	);

/**
 * Return conditions to be used when searching for users
 *
 * @return array
 */
	public function orConditions($data = array()) {
		$filter = $data['search'];
			$conditions = array(
				'OR' => array(
					'Dba.value ILIKE' => '%' . $filter . '%',
					'CorpName.value ILIKE' => '%' . $filter . '%',
					'CorpContact.value ILIKE' => '%' . $filter . '%', 
					'User.email ILIKE' => '%' . $filter . '%',
					'CAST(' . $this->alias . '.id AS TEXT) ILIKE' => '% '. $filter .'%',	
				)
			);
		return $conditions;
	}

/**
 * Work-a-round for sorting on aliased columns that have been custom joined
 * without this code we are unable to properly sort all columns in the 
 * cobrandedApplications/admin/index
 * namely DBA, CorpName, CorpContact
 * 
 * @param array $query
 * @return array
 * @see Model::find()
 */	
	function beforeFind($query) {
		parent::beforeFind($query);
		if(empty($query['order']['0']) && isset($query['sort'])){
			$query['order']['0'] = array($query['sort'] => $query['direction']);
		} return $query;
	}
/**
 * Return list of user_id and username for use in ajax searches
 *
 * @return array
 */
	public function findUserByUsername($searchStr, $active) {
		$dbCol = 'username';
			$searchStr = '%' . $searchStr;

		return $this->find('list', array('recursive' => -1,
			'conditions' => array(
				$dbCol . ' ILIKE' => $searchStr,
				'active' => $active
			),
			'fields' => array($this->alias . '.' . $dbCol),
			'limit' => 10
			)
		);
	}

/**
 * __addKey
 * 
 * @params
 *     $keys array
 *     $newKey string
 */
	private function __addKey($keys, $newKey) {
		return $keys.',"'.$newKey.'"';
	}

/**
 * __addValue
 * 
 * @params
 *     $values array
 *     $newValue string
 */
	private function __addValue($values, $newValue) {
		return $values.',"'.trim($newValue).'"';
	}

/**
 * __addApplicationValue
 * 
 * @params
 *     $applicationValueData array
 */
	private function __addApplicationValue($applicationValueData) {
		// save this info
		$this->CobrandedApplicationValues->create();
		$this->CobrandedApplicationValues->save($applicationValueData);
	}

/**
 * __startsWith
 * 
 * @params
 *     $haystack string
 *     $needle string
 */
	private function __startsWith($haystack, $needle) {
		return $needle === "" || strpos($haystack, $needle) === 0;
	}
}
