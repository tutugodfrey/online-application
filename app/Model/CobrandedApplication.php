<?php
App::uses('AppModel', 'Model');
App::uses('TemplateField', 'Model');
App::uses('EmailTimeline', 'Model');
App::uses('CakeEmail', 'Network/Email');

/**
 * CobrandedApplication Model
 *
 * @property CobrandedApplicationValues $CobrandedApplicationValues
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
	);

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
							case 5: // 'percents':
							case 7: // 'fees':
								// split default_value on ',' and append split[1] to the merge_field_name
								foreach (split(',', $field['default_value']) as $keyValuePairStr) {
									$keyValuePair = split('::', $keyValuePairStr);
									$name = $field['merge_field_name'].$keyValuePair[1];
									$this->__addApplicationValue(
										array(
											'cobranded_application_id' => $applicationId,
											'template_field_id' => $field['id'],
											'name' => $name,
										)
									);
								}
								break;

							case 6:
							case 8:
								// noop for label or hr items
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
	public function getTemplateAndAssociatedValues($applicationId) {
		$this->id = $applicationId;
		$application = $this->read();

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
									)
								)
							)
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
 * gsaveApplicationValue
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
		);

		// create an application for $userId
		$createAppResponse = $this->createOnlineappForUser($user);
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

			$this->TemplateField = ClassRegistry::init('TemplateField');

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
									foreach ($Model->validationErrors as $key => $value) {
										$response['validationErrors'] = Hash::insert($response['validationErrors'], $key, $value);
									}
								}
							}
							// multirecord data is validated and saved by it's associated Model
							// we don't want to add this to $appValue['CobrandedApplicationValues']['value']
							continue;
						}
					}

					// update the value
					$appValue['CobrandedApplicationValues']['value'] = $value;

					// handle required and empty first
					if ($templateField['required'] == true && empty($value) == true) {
						// update our validationErrors array
						$response['validationErrors'] = Hash::insert($response['validationErrors'], $key, 'required');
					} else {
						// only validate if we are not empty
						if (empty($value) == false) {
							// is the value valid?
							$validValue =  $this->CobrandedApplicationValues->validApplicationValue($appValue['CobrandedApplicationValues'], $templateField['type']);
							if ($validValue) {
								// save it
								$this->CobrandedApplicationValues->save($appValue);
							} else {
								// update our validationErrors array
								$typeStr = $this->TemplateField->fieldTypes[$templateField['type']];
								$response['validationErrors'] = Hash::insert($response['validationErrors'], $key, $typeStr);
							}
						}
					}
				}
			}

			unset($this->TemplateField);

			$response['success'] = (count($response['validationErrors']) == 0);
		}

		if ($response['success'] == false) {
			// delete the app
			$this->delete($createAppResponse['cobrandedApplication']['id']);
		} else {
			$response['application_id'] = $createAppResponse['cobrandedApplication']['id'];
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
						if ($this->__startsWith($app['CobrandedApplicationValues'][$appKey]['name'], "Owner Type - ")) {
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
						'conditions' => '"CobrandedApplicationValue"."cobranded_application_id" = "CobrandedApplication"."id"'
					)
				),
				'conditions' => array(
					'CobrandedApplicationValue.value' => $email,
					// should probably check the state too
				),
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

		$hash = String::uuid();
		$apps = $this->findAppsByEmail($email);
		if (count($apps) == 0) {
			$response['msg'] = 'Could not find any applications with the specified email address.';
		} else {
			// update the hash
			foreach ($apps as $key => $app) {
				$app['CobrandedApplication']['uuid'] = $hash;
				$this->save($app);
			}

			// and send the email
			$link = Router::url('/applications/index/', true).urlencode($email)."/{$hash}";

			$Email = new CakeEmail('default');
			$Email->emailFormat('text')
				->from(array('newapps@axiapayments.com' => 'Axia Online Applications'))
				->template('retrieve_applications')
				->to($email)
				->subject('Your Axia Applications')
				->viewVars(array('email'=>$email, 'hash'=>$hash, 'link'=>$link))
				->send();

			// TODO: record that he email was sent

			$response['success'] = true;
			$response['msg'] = '';
		}
		return $response;
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
