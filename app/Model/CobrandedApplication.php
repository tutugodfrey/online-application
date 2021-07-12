<?php
App::uses('AppModel', 'Model');
App::uses('TemplateField', 'Model');
App::uses('EmailTimeline', 'Model');
App::uses('RightSignature', 'Model');
App::uses('SalesForce', 'Model');
App::uses('CakeTime', 'Utility');
App::uses('HttpSocket', 'Network/Http');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

/**
 * CobrandedApplication Model
 *
 * @property CobrandedApplications $CobrandedApplications
 */

/**
 * Begin API Annotations for swagger-php for CobrandedApplicationsController::api_add() method
 *
 * Use this API endpoint to create applications for signing up potentially new clients. 
 *
 * The data to be submitted varies depending on the Template being used to create the application and on who is making the request.
 *
 * If the API consumer is a not a human user, the requests are considered machine-to-machine requests. This type of requests require the flag/key "m2m" to be present in the data being submitted
 * in order to disable interactive creation of applications.
 *
 * 		With m2m set to true {"m2m":true} data validation will be turned off for most fields only a small subset of fields will be required to create the application: "external_record_id", "DBA", "EMail" (Company contact email address). 
 *
 *		With m2m set to false (or when this flag is not present in the data) data validation will be enabled and the application will only be created when all data submitted passes validation.
 * 
 * Information about what data and/or fields are submitted when creating new applications can be found in the documentation about api/Templates/view API endpoint.
 * 
 * @OA\Post(
 *   path="/api/CobrandedApplications/add",
 *	 tags={"CobrandedApplications"},
 *   summary="Create a new client application and a corresponding Coversheet as well if the app template is configured to require a coversheet.",
 *   @OA\RequestBody(ref="#/components/requestBodies/CobrandedApplications"),
 *	 @OA\Response(
 *     response=200,
 *     @OA\MediaType(
 *         mediaType="application/json",
 *		   example={"validationErrors": {}, "application_id": "<UUID>", "application_url": null, "application_gui_url":"<web URL to edit application>", "response_url_type":"<pre-configured int val controls the returned application_url>", "partner_name":"<partner associated with template if any>",  "status": "<success/failed>", "messages": "<string or single dimentional array of status related messages>"}
 *     ),
 *     description="
 * 			On status success: 
 *				{'validationErrors': [],'status': 'success', 'messages': ['Application created!'], 'application_id':'d1b5ba0c-d614-4856-a49e-fdd6b86682a0', 'application_url': null, 'application_gui_url': 'https://app.<domain_name>.com/cobranded_applications/edit/d1b5ba0c-d614-4856-a49e-fdd6b86682a0', 'response_url_type': 1, 'partner_name': 'ABC Partners'}
 *			The value for the application_url may vary in the future and it will depend on how the response_url_type value was configured for the template. Currently for most applications the application_url value will be null.
 *			On failure status, a string or array of operation-specific errors will be returned as the value for the 'messages' array key.
 *			If any validation errors ocurr, they will be listed in the 'validationErrors' array.
 *			Furthermore, if no data is sent operation will return status:failed"
 *   ),
 *   @OA\Response(
 *     response=405,
 *     description="HTTP method not allowed when request method is not POST",
 *	   @OA\MediaType(
 *         mediaType="application/json",
 *  	   example={"status": "failed", "messages": "HTTP method not allowed"},
 *     ),
 *   ),
 * )
 */
 /**
 *
 *   @OA\RequestBody(
 *		request="CobrandedApplications",
 *	    description="IMPORTANT: The JSON data submitted varies depending on which Template is used to create the application, for that reason this example should not be considered a definitive data structure for all requests.",
 *      @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(
 *              @OA\Property(
 *                  property="template_id",
 *                  type="integer",
 *					description="Integer id of the template that should be used to create this application (Always required). See api/Templates/index for a list of templates.
 *									This field is always required.",
 *					example=1234
 *              ),
 *				 @OA\Property(
 *                  property="ContractorID",
 *                  type="string",
 *					description="The name of the sales rep on the client's account. The name must match an active user's account in the online application system.
 *									This field is always required.",
 *					example=1234
 *              ),
 *              @OA\Property(
 *                  property="external_record_id",
 *                  type="string",
 *                  maxLength=50,
 *					description="The unique id of the customer record or account from the external system. This will be used to submit periodic updates back to that specific record in the external system.
 *									Required when requests are done programmatically from machine to machine (m2m=true)",
 *					example="<id format varies from system to system>"
 *              ),
 *              @OA\Property(
 *                  property="Opportunity_id",
 *                  type="string",
 *                  maxLength=50,
 *					description="This field relates specifically to a salesforce opportunity id",
 *					example="0012345678IjfanAAB"
 *              ),
 *              @OA\Property(
 *                  property="m2m",
 *                  type="boolean",
 *					description="
 *						When set to true, API calls will be treated as machine to machine (m2m) or programmatically by another system, therefore data validation will be turned off for most fields except for DBA and Email.
 *						When false or when absent from the JSON data, data validation will be enabled and the application will only be created when all data submitted passes validation.",
 *					example="<'m2m':true>"
 *              ),
 *              @OA\Property(
 *                  property="CorpName",
 *                  type="string",
 *					maxLength=150,
 *					description="Corporation/Company Legal Name",
 *					example="Family Health Service"
 *              ),
 *              @OA\Property(
 *                  property="DBA",
 *                  type="string",
 *					maxLength=100,
 *					description="DBA Name.
 *									This field is always required.",
 *					example="Family Health"
 *              ),
 *              @OA\Property(
 *                  property="CorpAddress",
 *                  type="string",
 *					maxLength=100,
 *					description="Address",
 *					example="1234 Narrow Street"
 *              ),
 *              @OA\Property(
 *                  property="CorpCity",
 *                  type="string",
 *					maxLength=50,
 *					description="City",
 *					example="Red Bluff"
 *              ),
 *              @OA\Property(
 *                  property="CorpState",
 *                  type="string",
 *					maxLength=2,
 *					description="Two-letter State",
 *					example="CA"
 *              ),
 *              @OA\Property(
 *                  property="CorpZip",
 *                  type="string",
 *					maxLength=20,
 *					description="Zip code",
 *					example="96080"
 *              ),
 *              @OA\Property(
 *                  property="CorpPhone",
 *                  type="string",
 *					maxLength=20,
 *					description="Phone",
 *					example="(530)555-5789"
 *              ),
 *              @OA\Property(
 *                  property="EMail",
 *                  type="string",
 *					maxLength=50,
 *					description="Company contact email.
 *									This field is always required.",
 *					example="janedoe@nomail.com"
 *              ),
 *              @OA\Property(
 *                  property="expected_install_date",
 *                  type="date",
 *					description="The approximate date expected to install in Unix format (Y-m-d)",
 *					example="2021-01-25"
 *              ),
 *              @OA\Property(
 *                  property="org_name",
 *                  type="string",
 *					description="The name of the client's parent organization.",
 *					example="Office Ally"
 *              ),
 *              @OA\Property(
 *                  property="region_name",
 *                  type="string",
 *					description="The name of the region under the client's parent organization. This is in most cases hierarchical and not necessarily geographical.",
 *					example="Office Ally's region"
 *              ),
 *              @OA\Property(
 *                  property="setup_partner",
 *                  type="string",
 *					description="Name of the partner if any",
 *					example="Office Ally"
 *              ),
 *              example={
 *					"template_id": "12345",
 *					"ContractorID": "John Sales Man",
 *					"external_record_id": "abcdef123456",
 *					"Opportunity_id": "0012345678IjfanAAB",
 *					"m2m": true,
 *					"CorpName": "Family Health Service",
 *					"DBA": "Family Health",
 *					"CorpAddress": "1234 Narrow Street",
 *					"CorpCity": "Red Bluff",
 *					"CorpState": "CA",
 *					"CorpZip": "96080",
 *					"CorpPhone": "(530)555-5789",
 *					"EMail": "janedoe@nomail.com",
 *					"setup_partner": "Office Ally",
 *					"expected_install_date": "2021-01-25",
 *					"org_name": "Office Ally",
 *					"region_name": "Office Ally's region",
 *				}
 *          )
 *      )
 *   ),
 */
class CobrandedApplication extends AppModel {

	const RIGHTSIGNATURE_NO_TEMPLATE_ERROR = "error! The signature template has not been configured";
	const CORRAL_ACH_TEMPLATE_GUID = "a_10508841_8c16eeeefe42498e9eaf13bc5ca13ba7";
	const STATUS_SAVED = "saved";
	const STATUS_VALIDATE = "validate";
	const STATUS_PENDING = "pending";
	const STATUS_COMPLETED = "completed";
	const STATUS_SIGNED = "signed";

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
 * @param bool $created True if save created a new record
 * @param array $options Options passed from Model::save()
 * @return null
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
								foreach (explode(',', $field['default_value']) as $keyValuePairStr) {
									$multiTypeHasDefault = false;

									if (preg_match('/\{default\}/i', $keyValuePairStr)) {
										$keyValuePairStr = preg_replace('/\{default\}/i', '', $keyValuePairStr);
										$multiTypeHasDefault = true;
									}

									$keyValuePair = explode('::', $keyValuePairStr);
									$name = $field['merge_field_name'] . $keyValuePair[1];

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
								foreach (explode(',', $field['default_value']) as $keyValuePairStr) {
									$multiTypeDefaultVal = null;

									if (preg_match('/\{(.+)\}/', $keyValuePairStr, $matches)) {
										$keyValuePairStr = preg_replace('/\{.+\}/', '', $keyValuePairStr);
										$multiTypeDefaultVal = $matches[1];
									}

									$keyValuePair = explode('::', $keyValuePairStr);
									$name = $field['merge_field_name'] . $keyValuePair[1];

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
								foreach (explode(',', $field['default_value']) as $keyValuePairStr) {
									if (preg_match('/\{default\}/i', $keyValuePairStr)) {
										$keyValuePairStr = preg_replace('/\{default\}/i', '', $keyValuePairStr);
										$keyValuePair = explode('::', $keyValuePairStr);

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
 * @param uuid $applicationId Application Unique Identifier
 * @param int $userId User Identification Number
 * @return mixed Result of find Operation
 */
	public function getTemplateAndAssociatedValues($applicationId, $userId = null) {
		$application = $this->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.id' => $applicationId
				)
			)
		);

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
 * @param int $valueId Cobranded Application Value ID
 * @return mixed Result of the find operation
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
 * @param array $data array of application keys and values to be saved
 * @return mixed response from the save operation
 */
	public function saveApplicationValue($data) {
		$response = array('success' => false);

		// grab the appliction value with $data['id']
		$appValue = $this->getApplicationValue($data['id']);

		// if the value is different
		if ($appValue['CobrandedApplicationValue']['value'] !== $data['value']) {
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
								$response['msg'] = 'failed to update application value with id [' . $radioOption['id'] . '], to a value of null.';
							}
						}
					}
				}
			} else {
				// something went wrong
				$response = Hash::insert(
					$response,
					'msg',
					'failed to update application value with id [' . $data['id'] . '], value [' . $data['value'] . '].'
				);
			}
		} else {
			// let the consumer know the value did not change
			$response = Hash::insert(
				$response,
				'msg',
				'failed to update application value with id [' . $data['id'] . '] because the value did not change'
			);
		}

		return $response;
	}

/**
 * createOnlineappForUser
 *
 * @param array $user object
 * @param array $newAppData data to create the new CobrandedApplication record. The following key=>value pairs are recognized:
 * $newAppData = [
 * 		'uuid' => '<an UUID to assign to the new app>'
 * 		'templateId' => '<a Template.id to associate the app with>'
 * 		'externalForeignId' => '<an externalForeignId to associate the app with>'
 * 		'sfOpportunityId' => '<a salesforce opporunity id to associate the app with>'
 * 		'clientIdGlobal' => '<a clientIdGlobal to associate the app with>'
 * 		'clientNameGlobal' => '<the clientNameGlobal that corresponds to the clientIdGlobal to associate the app with>'
 * ]
 *
 * @return
 *     $response array(
 *         success [true|false] depending on if the onlineapp was created
 *         cobrandedApplicationId int
 */
	// public function createOnlineappForUser($user, $uuid = null, $templateId = null, $externalForeignId = null, $clientIdGlobal = null, $clientNameGlobal = null) {
	public function createOnlineappForUser($user, $newAppData = ["uuid" => null, "templateId" => null, "externalForeignId" => null, 'sfOpportunityId' => null, "clientIdGlobal" => null, "clientNameGlobal" => null]) {
		$response = array('success' => false, 'cobrandedApplication' => array('id' => null, 'uuid' => null));
		if (empty($newAppData['uuid'])) {
			$newAppData['uuid'] = CakeText::uuid();
		}
		//use user's template_id value by default (may or may not be the user's default template)
		$newAppData['templateId'] = (empty($newAppData['templateId']))? $user['template_id'] : $newAppData['templateId'];
		$this->create(
			array(
				'user_id' => $user['id'],
				'uuid' => $newAppData['uuid'],
				'template_id' => $newAppData['templateId'],
				'external_foreign_id' => empty($newAppData['externalForeignId'])? null : $newAppData['externalForeignId'], //no zero length strings
				'sf_opportunity_id' => empty($newAppData['sfOpportunityId'])? null : $newAppData['sfOpportunityId'], //no zero length strings
				'client_id_global' => empty($newAppData['clientIdGlobal'])? null : $newAppData['clientIdGlobal'], //no zero length strings
				'client_name_global' => empty($newAppData['clientNameGlobal'])? null : $this->trimExtra($newAppData['clientNameGlobal']), //no zero length strings
				'status' => 'saved'
			)
		);

		if ($this->save()) {
			$response['success'] = true;
			$response['cobrandedApplication'] = array(
				'id' => $this->id,
				'uuid' => $newAppData['uuid'],
			);
		}
		return $response;
	}
/**
 * _removeDataInsertedOnExport
 * Removes key=> value pairs of data related to Coversheet and data that is known to injected as a result of buiding the data array from calling $this->buildExportData();
 * The end result is an array containing only CobrandedApplicationValues that are assumed to have been added to the array by $this->buildExportData()
 * 
 * @param array &$exportedData array originally built from calling $this->buildExportData()
 * @return void 
 */
	protected function _removeDataInsertedOnExport(&$exportedData) {
		unset(
			$exportedData['CompanyBrandName'],
			$exportedData['template_id'],
			$exportedData['m2m'],
			$exportedData['external_record_id'],
			$exportedData['Opportunity_id'],
			$exportedData['ClientId'],
			$exportedData['ClientName'],
			$exportedData['uuid'],
			$exportedData['status'],
			$exportedData['MID'],
			$exportedData['oaID'],
			$exportedData['api'],
			$exportedData['ach_accepted'],
			$exportedData['aggregated']
		);
		foreach ($this->Coversheet->getColumnTypes() as $fieldName => $types) {
			unset($exportedData[$fieldName]);
		}
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
 * @param int $user user identification number
 * @param array $fieldsData array of fields data to be saved
 *
 * @return
 *     $response array
 */
	public function saveFields($user, $fieldsData) {
		if (empty(Hash::get($fieldsData, 'template_id')) || $this->Template->hasAny(array('id' => Hash::get($fieldsData, 'template_id'))) === false) {
			return array('status' => AppModel::API_FAILS, 'messages' => "A Template with id = '" . Hash::get($fieldsData, 'template_id') . "' does not exist.");
		}
		// default our response
		$response = array(
			'success' => false,
			'validationErrors' => array(),
			'application_id' => null,
			'application_url' => null
		);
		$isM2mRequest = Hash::get($fieldsData, 'm2m');
		$templateId = Hash::get($fieldsData, 'template_id');
		$appUuid = Hash::get($fieldsData, 'uuid');
		$externalForeignId = Hash::get($fieldsData, 'external_record_id');
		$sfOpportunityId = Hash::get($fieldsData, 'Opportunity_id');
		$clientIdGlobal = Hash::get($fieldsData, 'ClientId');
		$clientNameGlobal = Hash::get($fieldsData, 'ClientName');
		//remove data potentially present that is not related to the CobrandedApplicationValues
		$this->_removeDataInsertedOnExport($fieldsData);

		if ($isM2mRequest) {
			if (empty(Hash::get($fieldsData, 'DBA', null))) {
				$errMsgs[] = "DBA is required.";
			}
			if (empty(Hash::get($fieldsData, 'EMail', null))) {
				$errMsgs[] = "EMail is required.";
			}
			if (isset($errMsgs)) {
				return array(
					'status' => AppModel::API_FAILS,
					'messages' => $errMsgs
				);
			}
		}
		//check if existing application uuid
		if (!empty($appUuid)) {
			$updatingExistingData = true;
			$createAppResponse = array('success' => true, 'cobrandedApplication' => array('id' => $this->field('id', array('uuid' => $appUuid)), 'uuid' => $appUuid));
		} else {
			$updatingExistingData = false;
			// create an application for $userId
			$appNewData = ["templateId" => $templateId, "externalForeignId" => $externalForeignId, 'sfOpportunityId' => $sfOpportunityId, "clientIdGlobal" => $clientIdGlobal, "clientNameGlobal" => $clientNameGlobal];
			$createAppResponse = $this->createOnlineappForUser($user, $appNewData);
		}
		
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

			$this->Cobrand = ClassRegistry::init('Cobrand');
			$this->TemplateField = ClassRegistry::init('TemplateField');
			$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');

			// save the application values
			foreach ($fieldsData as $key => $value) {
				// multirecord data will be in an array, don't trim the array
				if (!is_array($value)) {
					$value = trim($value);
				}

				// look up the field type from the name
				$appValue = $this->CobrandedApplicationValue->find(
					'first',
					array(
						'conditions' => array(
							'cobranded_application_id' => $newApp['CobrandedApplication']['id'],
							'CobrandedApplicationValue.name' => $key
						)
					)
				);

				//If $key is not a TemplateField or not associated with the application value appValue will be empty therefore skip unrecognized keys/fields
				if (empty($appValue)) {
					continue;
				}
				$templateField = null;
				if (key_exists('TemplateField', $appValue)) {
					$templateField = $appValue['TemplateField'];
				} else {
					// look it up
					$templateField = $this->TemplateField->find(
						'first',
						array(
							'conditions' => array(
								'TemplateField.id' => Hash::get($appValue, 'CobrandedApplicationValue.template_field_id')
							)
						)
					);
					$templateField = Hash::get($templateField, 'TemplateField');
				}
				//override the rep_only designation for ContractorID for API calls we want a rep to own this application
				if ($isM2mRequest == true && $key === 'ContractorID') {
					$templateField['rep_only'] = false;
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
							if ($radioOption['CobrandedApplicationValue']['id'] != $appValue['CobrandedApplicationValue']['id']) {
								// udpate the value to null
								$radioOption['CobrandedApplicationValue']['value'] = null;
								if (!$this->CobrandedApplicationValue->save($radioOption)) {
									$response['validationErrors'] = Hash::insert($response['validationErrors'], $templateField['merge_field_name'], 'failed to update application value with id [' .
										$radioOption['CobrandedApplicationValue']['id'] . '], to a value of null.');
								}
							}
						}
					}

					// update the value
					$appValue['CobrandedApplicationValue']['value'] = $value;
					//machine to machine API requests only require DBA and email data
					if ($isM2mRequest == true) {

						$validValue = $this->CobrandedApplicationValue->validApplicationValue($appValue['CobrandedApplicationValue'], $templateField['type'], $templateField);
						// Allow saving valid values or zero-length strings which are considered clearing the field data
						if ($validValue || $value === "") {
							// save it
							$this->CobrandedApplicationValue->save($appValue);
						} else {
							// update our validationErrors array
							$typeStr = $this->TemplateField->fieldTypes[$templateField['type']];
							$response['validationErrors'] = Hash::insert($response['validationErrors'], $templateField['merge_field_name'], "Invalid format for $typeStr: $value");
						}


						if (count($response['validationErrors']) == 0) {
							$tmpResponse['success'] = true;
						} else {
							$tmpResponse['success'] = false;
							$tmpResponse['validationErrors'] = $response['validationErrors'];
						}
						// handle required and empty first
					} elseif ($templateField['required'] == true && ($value === "" || is_null($value))) {

						// SSN should not be required if Ownership Type is Non Profit
						if ($appValue['CobrandedApplicationValue']['name'] == 'OwnerSSN' || $appValue['CobrandedApplicationValue']['name'] == 'Owner2SSN') {

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
							if (Hash::get($appValue,'CobrandedApplicationValue.name') == 'OwnerSSN' ||
								Hash::get($appValue,'CobrandedApplicationValue.name') == 'Owner2SSN') {

									$tmpValue = $appValue['CobrandedApplicationValue']['value'];

									if (preg_match('/([0-9]{3})-?([0-9]{2})-?([0-9]{4})/', $tmpValue, $matches)) {
										$tmpValue = $matches[1] . "-" . $matches[2] . "-" . $matches[3];
										$appValue['CobrandedApplicationValue']['value'] = $tmpValue;
									}
							}

							$validValue = $this->CobrandedApplicationValue->validApplicationValue($appValue['CobrandedApplicationValue'], $templateField['type'], $templateField);
							// Allow saving valid values or zero-length strings which are considered clearing the field data
							if ($validValue || $value === "") {
								// save it
								$this->CobrandedApplicationValue->save($appValue);
							} else {
								// update our validationErrors array
								$typeStr = $this->TemplateField->fieldTypes[$templateField['type']];
								$response['validationErrors'] = Hash::insert($response['validationErrors'], $templateField['merge_field_name'], "Invalid input format for $typeStr");
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

		if ($isM2mRequest == false) {
			$tmpResponse = $this->validateCobrandedApplication($cobrandedApplication);
			if (!empty(Hash::get($tmpResponse, 'validationErrorsArray'))) {
				//remove any rep-only-related field validation errors
				foreach (Hash::get($tmpResponse, 'validationErrorsArray') as $invalidField) {
					if ($invalidField['rep_only']) {
						unset($tmpResponse['validationErrors'][$invalidField['mergeFieldName']]);
					}
				}
				$tmpResponse['success'] = empty($tmpResponse['validationErrors']);
			}
		}

		if ($response['success'] == false || $tmpResponse['success'] == false) {
			// delete the app iff we are updating an existing one rather than creating a new one
			if (!$updatingExistingData) {
				$this->delete($createAppResponse['cobrandedApplication']['id']);
			}
			$response['success'] = false;
			foreach ($tmpResponse['validationErrors'] as $key => $val) {
				$response['validationErrors'] = Hash::insert($response['validationErrors'], $key, $val);
			}
		} else {
			$cobrand = $this->Cobrand->find(
				'first',
				array(
					'conditions' => array(
						'Cobrand.id' => $newApp['Template']['cobrand_id']
					),
					'recursive' => -1
				)
			);

			$response['application_id'] = $createAppResponse['cobrandedApplication']['uuid'];
			$response['application_gui_url'] = Router::url('/cobranded_applications/edit/', true) . $createAppResponse['cobrandedApplication']['uuid'];
			$response['response_url_type'] = $cobrand['Cobrand']['response_url_type'];
			$response['partner_name'] = $cobrand['Cobrand']['partner_name'];

			switch ($cobrand['Cobrand']['response_url_type']) {
				case 1: // return nothing
					break;

				case 2: // return RS signing url
					$client = $this->createRightSignatureClient();
					$rsTemplateUuid = $newApp['Template']['rightsignature_template_guid'];
					$getTemplateResponse = $this->getRightSignatureTemplate($client, $rsTemplateUuid);
					$getTemplateResponse = json_decode($getTemplateResponse, true);

					if ($getTemplateResponse && !empty($getTemplateResponse['reusable_template']['id'])) {
						$applicationJSON = $this->createRightSignatureDocumentJSON(
							$createAppResponse['cobrandedApplication']['id'], $user['email'], $getTemplateResponse['reusable_template']);
							$createResponse = $this->createRightSignatureDocument($getTemplateResponse['reusable_template']['id'], $client, $applicationJSON);
							$createResponse = json_decode($createResponse, true);

						if ($createResponse['document']['state'] == 'pending' && $createResponse['document']['id']) {
							// save the guid
							$this->save(
								array(
									'CobrandedApplication' => array(
										'id' => $createAppResponse['cobrandedApplication']['id'],
										'rightsignature_document_guid' => $createResponse['document']['id'],
										'status' => 'completed'
									)
								),
								array('validate' => false)
							);

							$response['application_url'] = Router::url('/cobranded_applications/sign_rightsignature_document', true) . '?guid=' . $createResponse['document']['id'];
						} else {
							$response['validationErrors'] = Hash::insert($response['validationErrors'], 'error: ', $createResponse);
						}
					} else {
						$response['validationErrors'] = Hash::insert($response['validationErrors'], 'error: ', $getTemplateResponse);
					}

					break;

				case 3: // return online app url
					$response['application_url'] = Router::url('/cobranded_applications/edit/', true) . $createAppResponse['cobrandedApplication']['uuid'];
					break;

				default: // return nothing
					break;
			}
		}

		if (count($response['validationErrors']) > 0 ) {
			$response['status'] = AppModel::API_FAILS;
			// delete the app
			if (!$updatingExistingData) {
				$this->delete($createAppResponse['cobrandedApplication']['id']);
			}
		} else {
			$response['status'] = AppModel::API_SUCCESS;
			$response['messages'][] = (!$updatingExistingData)? 'Application created' : 'Application updated';
		}
		unset($response['success']);
		return $response;
	}

/**
 * setExportedDate
 *
 * @param int $id Cobranded Application ID
 * @param boolean apiExported whether the data was exported via API
 * @return void
 */
	public function setExportedDate($id, $apiExported) {
		$modified = $this->field('modified', array('id' => $id));
		$data = array(
			'id' => $id,
			'modified' => $modified //keep modified date unchanged
		);
		if ($apiExported) {
			$data['api_exported_date'] = date("Y-m-d H:i:s");
		} else {
			$data['csv_exported_date'] = date("Y-m-d H:i:s");
		}
		$this->save($data, array('validate' => false, 'callbacks' => false));
	}
/**
 * buildExportData
 *
 * @param int $appId Cobranded Application ID
 * @param array &$keys array of keys for the values to be exported
 * @param array &$values array of values to be exported
 * @param boolean $asArray if true the keys and values will be set as arrays instead of a coma delimited strings
 * @return array
 */
	public function buildExportData($appId, &$keys, &$values, $asArray = false) {
		$options = array(
			'conditions' => array(
				'CobrandedApplication.' . $this->primaryKey => $appId
			),
			'contain' => array(
				'CobrandedApplicationValues',
				'Template' => array('fields' => array('Template.name', 'Template.cobrand_id')),
				'Coversheet',
				'CobrandedApplicationAches',
			)

		);
		$app = $this->find('first', $options);
		$cobrandData = $this->Template->Cobrand->find('first', array(
			'fields' => array('brand_name', 'partner_name'),
			'conditions' => array('id' => $app['Template']['cobrand_id'])
		));
		$enquote = !$asArray; //no quotes when $asArray = true
		$keys = ($enquote)? '"MID"' : 'MID';
		$values = ($enquote)? '""' : '';
		$referrals = array('', '', '');

		$this->TemplateField = ClassRegistry::init('TemplateField');
		//Insert company brand name from cobrands
		$keys = $this->__addKey($keys, 'CompanyBrandName', $enquote);
		$values = $this->__addValue($values, $cobrandData['Cobrand']['brand_name'], $enquote);
		if (stripos($cobrandData['Cobrand']['partner_name'], 'VeriCheck') !== false) {
			$keys = $this->__addKey($keys, 'ach_accepted', $enquote);
			$values = $this->__addValue($values, 'TRUE', $enquote);
		}
		if (!empty($app['CobrandedApplication']['external_foreign_id'])) {
			$keys = $this->__addKey($keys, 'external_foreign_id', $enquote);
			$values = $this->__addValue($values, $app['CobrandedApplication']['external_foreign_id'], $enquote);
		}
		if (!empty($app['CobrandedApplication']['sf_opportunity_id'])) {
			$keys = $this->__addKey($keys, 'sf_opportunity_id', $enquote);
			$values = $this->__addValue($values, $app['CobrandedApplication']['sf_opportunity_id'], $enquote);
		}
		if (!empty($app['CobrandedApplication']['client_id_global'])) {
			$keys = $this->__addKey($keys, 'client_id_global', $enquote);
			$values = $this->__addValue($values, $app['CobrandedApplication']['client_id_global'], $enquote);
		}
		if (!empty($app['CobrandedApplication']['client_name_global'])) {
			$keys = $this->__addKey($keys, 'client_name_global', $enquote);
			$values = $this->__addValue($values, $app['CobrandedApplication']['client_name_global'], $enquote);
		}
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
			} elseif ($app['CobrandedApplicationValues'][$appKey]['name'] == "Referral1Business" ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == "Referral1Owner/Officer" ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == "Referral1Phone") {

				$referrals[0] = $referrals[0] . ' ' . $app['CobrandedApplicationValues'][$appKey]['value'];
				if ($app['CobrandedApplicationValues'][$appKey]['name'] == "Referral1Phone") {
					$keys = $this->__addKey($keys, 'Referral1', $enquote);
					$values = $this->__addValue($values, $referrals[0], $enquote);
				}
			} elseif ($app['CobrandedApplicationValues'][$appKey]['name'] == "Referral2Business" ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == "Referral2Owner/Officer" ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == "Referral2Phone") {

				$referrals[1] = $referrals[1] . ' ' . $app['CobrandedApplicationValues'][$appKey]['value'];
				if ($app['CobrandedApplicationValues'][$appKey]['name'] == "Referral2Phone") {
					$keys = $this->__addKey($keys, 'Referral2', $enquote);
					$values = $this->__addValue($values, $referrals[1], $enquote);
				}
			} elseif ($app['CobrandedApplicationValues'][$appKey]['name'] == "Referral3Business" ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == "Referral3Owner/Officer" ||
				$app['CobrandedApplicationValues'][$appKey]['name'] == "Referral3Phone") {

				$referrals[2] = $referrals[2] . ' ' . $app['CobrandedApplicationValues'][$appKey]['value'];
				if ($app['CobrandedApplicationValues'][$appKey]['name'] == "Referral3Phone") {
					$keys = $this->__addKey($keys, 'Referral3', $enquote);
					$values = $this->__addValue($values, $referrals[2], $enquote);
				}
			} else {
				// just addit
				$keys = $this->__addKey($keys, $app['CobrandedApplicationValues'][$appKey]['name'], $enquote);

				// look up the template field that this value was built from
				$templateField = $this->TemplateField->find(
					'first',
					array(
						'conditions' => array('id' => $app['CobrandedApplicationValues'][$appKey]['template_field_id']),
						'recursive' => -1
					)
				);
				if ($templateField['TemplateField']['type'] == 4) {
					// dealing with a multiple option input/field
					// need to special case the OwnerType because it expects "Yes"/"Off" instead of "On"/"Off"
					if ($app['CobrandedApplicationValues'][$appKey]['value'] == 'true') {
						if ($this->__startsWith($app['CobrandedApplicationValues'][$appKey]['name'], "OwnerType-")) {
							$values = $this->__addValue($values, 'Yes', $enquote);
						} else {
							$values = $this->__addValue($values, 'On', $enquote);
						}
					} else {
						$values = $this->__addValue($values, 'Off', $enquote);
					}
				} else {
					$values = $this->__addValue($values, $app['CobrandedApplicationValues'][$appKey]['value'], $enquote);
				}
			}
		}
		unset($this->TemplateField);

		// add "oaID", "api", "aggregated" to the end of the keys and values
		if ($enquote) {
			$keys = $keys . ',"oaID","api","aggregated"';
			$values = $values . ',"' . $app['CobrandedApplication']['id'] . '","",""';
		} else {
			$keys = $keys . '|oaID|api|aggregated';
			$values = $values . '|' . $app['CobrandedApplication']['id'] . '||';
		}

		if (!empty($app['CobrandedApplicationAches'])) {
			foreach ($app['CobrandedApplicationAches'] as $index => $array) {
				foreach ($array as $key => $val) {
					if ($key == 'auth_type' || $key == 'routing_number' || $key == 'account_number') {
						$val = trim(mcrypt_decrypt(Configure::read('Cryptable.cipher'), Configure::read('Cryptable.key'),
									base64_decode($val), 'cbc', Configure::read('Cryptable.iv')));
					}
					$keys = $this->__addKey($keys, 'AddlACH-' . $key . '-' . $index, $enquote);
					$values = $this->__addValue($values, $val, $enquote);
				}
			}
		}

		if (!empty($app['Coversheet'])) {
			foreach ($app['Coversheet'] as $key => $val) {
				if ($key == 'id' || $key == 'cobranded_application_id') {
					continue;
				}
				$keys = $this->__addKey($keys, $key, $enquote);
				$values = $this->__addValue($values, $val, $enquote);
			}
		}
		//The following key value pairs are intended as a sort of META data which the database system requires and will recognize during the import if this CSV data.
		//The database system will create the merchant account and will set it up based on which this META data key value pairs.
		if (stripos(Hash::get($app, 'Template.name'), 'Payment Fusion') !== false) {
			$keys = $this->__addKey($keys, 'PaymentFusion', $enquote);
			$values = $this->__addValue($values, 'YES', $enquote);
		}
		if ($asArray) {
			$keys = explode('|', $keys);
			$values = explode('|', $values);
		}
	}

/**
 * copyApplication
 *
 * @param int $appId Cobranded Application ID
 * @param int $userId User ID
 * @param int $templateId Template ID
 *
 * @return
 *     The new CobrandedApplication.id | false depending on whether the application was copied or not
 */
	public function copyApplication($appId, $userId, $templateId = null) {
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

		$lookupId = null;

		if ($templateId != null) {
			$lookupId = $templateId;
		} else {
			$lookupId = $app['CobrandedApplication']['template_id'];
		}

		$this->create(
			array(
				'user_id' => $userId,
				'uuid' => CakeText::uuid(),
				'template_id' => $lookupId,
				'status' => 'saved'
			)
		);

		if ($this->save()) {
			$settings = array('contain' => array('CobrandedApplicationValues'));
			$newApp = $this->getById($this->id, $settings);

			//check if template is for cancelations
			$isCancellation = $this->Template->hasAny(['id' => $lookupId, "name ilike '%cancel%'" ]);
			if ($isCancellation) {
				$existingClientData = $this->getDbApiClientData(['application_id' => $appId]);

				if ($existingClientData !== false) {
					$midForCancellation = Hash::get($existingClientData, 'merchant_mid');
				} else {
					$isCancellation = false;
				}
			}

			// copy each value over
			foreach ($app['CobrandedApplicationValues'] as $key => $value) {
				if ($app['CobrandedApplicationValues'][$key]['name'] == 'Unknown Type for testing') {
					// skip it
				} else {
					$found = false;
					foreach ($newApp['CobrandedApplicationValues'] as $newKey => $newVal) {
						if ($found == true) {
							break;
						}
						$newAppVal = null;
						if ($isCancellation && stripos($newApp['CobrandedApplicationValues'][$newKey]['name'], 'MIDToCancel') !== false) {
							$newAppVal = $midForCancellation;
							$isCancellation = false;
						}

						if ($newApp['CobrandedApplicationValues'][$newKey]['name'] == $app['CobrandedApplicationValues'][$key]['name']) {
							$newAppVal = $app['CobrandedApplicationValues'][$key]['value'];
						}
						if (isset($newAppVal)) {
							$newApp['CobrandedApplicationValues'][$newKey]['value'] = $newAppVal;
							$this->CobrandedApplicationValues->save($newApp['CobrandedApplicationValues'][$newKey]);
							$found = true;
						}
					}
				}
			}
			return $newApp['CobrandedApplication']['id'];
		}
		return false;
	}

/**
 * getDbApiClientData
 * Performs API GET call to the AxiaMed DB get_merchant endpoint to seach and retrieve data about a merchant.
 * Supported conditions for search are at least one of the following :
 * 		merchant_mid
 * 		merchant_dba
 * 		application_id
 * 		external_record_id
 *
 * @param array $conditions one dimentional array of key =>val pared conditions to use a search parameters un URL query
 * @return mixed boolean|array false when nothing is found or API call fails. Array of data when merchant is found.
 */
	public function getDbApiClientData($conditions) {
		if (empty($conditions)) {
			return false;
		}
		$axDbApiClient = $this->createAxiaDbApiAuthClient();

		$reponse = $axDbApiClient->get('https://db.axiatech.com/api/Merchants/get_merchant', array($conditions));
		$responseData = json_decode($reponse->body, true);
		if (!empty($responseData['data'])) {
			return $responseData['data'];
		} else {
			if ($responseData['status'] === 'failed') {
				$msgs = is_array($responseData['messages'])? implode("\n", $responseData['messages']): $responseData['messages'];
				$this->log("AxiaMed API call failed:\n$msgs\nURL:" . $msgs);
			}
			return false;
		}
	}

/**
 * findAppsByEmail
 *
 * @param string $email email address
 * @param int $id application id
 *
 * @return
 *     $response array
 */
	public function findAppsByEmail($email, $id = null) {
		$conditions[] = array('CobrandedApplicationValue.value' => $email);

		// should probably check the state too
		if (!empty($id)) {
			$conditions[]['CobrandedApplicationValue.cobranded_application_id'] = $id;
			$conditions[]['CobrandedApplicationValue.name'] = 'Owner1Email';
		}

		$apps = $this->find(
			'all',
			array(
				'fields' => array(
					'CobrandedApplication.id',
					'CobrandedApplication.user_id',
					'CobrandedApplication.template_id',
					'CobrandedApplication.uuid',
					'CobrandedApplication.created',
					'CobrandedApplication.modified',
					'CobrandedApplication.rightsignature_document_guid',
					'CobrandedApplication.status',
					'CobrandedApplication.rightsignature_install_document_guid',
					'CobrandedApplication.rightsignature_install_status',
					'User.id',
					'Template.id',
					'Merchant.id',
					'Coversheet.id'
				),
				'joins' => array(
					array(
						'alias' => 'CobrandedApplicationValue',
						'table' => 'onlineapp_cobranded_application_values',
						'type' => 'LEFT',
						'conditions' => '"CobrandedApplicationValue"."cobranded_application_id" = "CobrandedApplication"."id"',
					)
				),
				'conditions' => $conditions,
				'group' => array(
					'CobrandedApplication.id',
					'CobrandedApplication.user_id',
					'CobrandedApplication.template_id',
					'CobrandedApplication.uuid',
					'CobrandedApplication.created',
					'CobrandedApplication.modified',
					'CobrandedApplication.rightsignature_document_guid',
					'CobrandedApplication.status',
					'CobrandedApplication.rightsignature_install_document_guid',
					'CobrandedApplication.rightsignature_install_status',
					'User.id',
					'Template.id',
					'Merchant.id',
					'Coversheet.id'
				),
				'order' => 'CobrandedApplication.created desc'
			)
		);

		return $apps;
	}

/**
 * sendFieldCompletionEmail
 *
 * @param string $email email address
 * @param int $id application id
 *
 * @return
 *     $response array
 */
	public function sendFieldCompletionEmail($email, $id = null) {
		$response = array(
			'success' => false,
			'msg' => 'Failed to send email to [' . $email . ']. Please contact your rep.',
		);

		$apps = $this->findAppsByEmail($email, $id);

		if (count($apps) == 0) {
			if (!empty($id)) {
				$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');
				$cav = $this->CobrandedApplicationValue->find(
					'first', array(
						'conditions' => array(
							'CobrandedApplicationValue.name' => 'Owner1Email',
							'CobrandedApplicationValue.cobranded_application_id' => $id
						),
						'recursive' => -1,
						'fields' => array('CobrandedApplicationValue.id')
					)
				);
				$appValue = $this->getApplicationValue($cav['CobrandedApplicationValue']['id']);
				$appValue['CobrandedApplicationValue']['value'] = $email;
				if ($this->CobrandedApplicationValue->save($appValue)) {
					return $this->sendFieldCompletionEmail($email);
				}
			} else {
				$response['msg'] = 'Could not find any applications with the specified email address.';
				return $response;
			}
		} else {
			// send the email
			$timestamp = time();
			$link = Router::url('/cobranded_applications/index/', true) . urlencode($email) . "/{$timestamp}";

			$args = array(
				'from' => array('newapps@axiapayments.com' => 'Axia Online Applications'),
				'to' => $email,
				'subject' => 'Your Axia Applications',
				'format' => 'text',
				'template' => 'retrieve_applications',
				'viewVars' => array('email' => $email, 'link' => $link)
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
 * @param array $args array of arguments to control how the email is sent
 *
 * @return $response array
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
 * @param int $applicationId Cobranded Application Id
 *
 * @return $response array
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

		$settings = array('contain' => array(
				'Template',
				'CobrandedApplicationValues',
			));
		$cobrandedApplication = $this->getById($applicationId, $settings);

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

			$ownerEmail = '';
			if (isset($owners[$key]['email'])) {
				$ownerEmail = $owners[$key]['email'];
			}

			$ownerFullname = '';
			if (isset($owners[$key]['fullname'])) {
				$ownerFullname = $owners[$key]['fullname'];
			}

			if (!empty($ownerEmail)) {
				$from = array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications');
				$to = $ownerEmail;
				$subject = $dbaBusinessName . ' - Merchant Application';
				$format = 'both';
				$template = 'email_app';
				$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : exec("hostname");
				$viewVars = array();
				$viewVars['url'] = "https://" . $hostname . "/cobranded_applications/sign_rightsignature_document?guid=" . $cobrandedApplication['CobrandedApplication']['rightsignature_document_guid'];
				$viewVars['ownerName'] = $ownerFullname;
				$viewVars['merchant'] = $dbaBusinessName;

				$this->Cobrand = ClassRegistry::init('Cobrand');
				$cobrand = $this->Cobrand->find(
					'first',
					array(
						'conditions' => array(
							'Cobrand.id' => $cobrandedApplication['Template']['cobrand_id']
						)
					)
				);

				$viewVars['brandLogo'] = $cobrand['Cobrand']['brand_logo_url'];

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
		}

		return $response;
	}

/**
 * sendForCompletion
 *
 * @param int $applicationId Cobranded Application Id
 *     $applicationId int
 * @return $response array
 */
	public function sendForCompletion($applicationId) {
		if (!$this->exists($applicationId)) {
			$response = array(
				'success' => false,
				'msg' => 'Invalid application.',
			);
			return $response;
		}

		$settings = array('contain' => array(
				'CobrandedApplicationValues',
			));
		$cobrandedApplication = $this->getById($applicationId, $settings);

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
		$userEmail = $this->User->field('email', ['id' => $cobrandedApplication['CobrandedApplication']['user_id']]);
		$from = array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications');
		if (stripos($userEmail, EmailTimeline::ENTITY1_EMAIL_DOMAIN) !== false) {
			$from = array(EmailTimeline::ENTITY1_NEWAPPS_EMAIL => 'AxiaMed Online Applications');
		}
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
		$viewVars['link'] = "https://" . $hostname . "/cobranded_applications/edit/" . $hash;
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
 * sendSignedAppToUw
 *
 * @param int $applicationId Cobranded Application Id
 * @param string $optionalTemplate optional template to use
 *
 * @return $response array
 */
	public function sendSignedAppToUw($applicationId, $optionalTemplate = null) {

		if (!$this->exists($applicationId)) {
			return false;
		}
		//check if an email was sent already
		if ($this->EmailTimeline->hasAny(['cobranded_application_id' => $applicationId, 'email_timeline_subject_id' => EmailTimeline::APP_SENT_TO_UW])) {
			return true;
		}

		$settings = array('contain' => array(
			'User',
			'CobrandedApplicationValues',
			'Template' => array(
				'fields' => array('email_app_pdf', 'name'),
				'Cobrand.partner_name'
			),
		));
		$cobrandedApplication = $this->getById($applicationId, $settings);
		$templateName = strtolower(Hash::get($cobrandedApplication, 'Template.name'));
		$cobrandName = strtolower(Hash::get($cobrandedApplication, 'Template.Cobrand.partner_name'));
		//No email should be sent for Apps using these templates with these keywords in the name
		if ((stripos($templateName, 'payment fusion') !== false) || 
			(stripos($cobrandName, 'vericheck') !== false) ||
			(stripos($templateName, 'text&pay') !== false) ||
			(stripos($templateName, 'cancellation') !== false)) {
			return true;
		}

		$dbaBusinessName = '';
		$repName = '';
		$valuesMap = $this->buildCobrandedApplicationValuesMap($cobrandedApplication['CobrandedApplicationValues']);

		if (!empty($valuesMap['DBA'])) {
			$dbaBusinessName = $valuesMap['DBA'];
		}
		if (!empty($valuesMap['ContractorID'])) {
			$repName = $valuesMap['ContractorID'];
		}

		
		$from = array(EmailTimeline::ENTITY1_NEWAPPS_EMAIL => 'Axia Online Application Signed');
		if (stripos($cobrandedApplication['User']['email'], EmailTimeline::ENTITY1_EMAIL_DOMAIN) !== false) {
			$to = array(EmailTimeline::I3_UNDERWRITING_EMAIL);
		} else {
			$to = array(EmailTimeline::ENTITY2_APPS_EMAIL);
		}

		//Generate secret access key for secured doc download by receipient
		$randPass = $this->User->generateRandPw();
		$passwordHasher = new BlowfishPasswordHasher();
		$secretHash = $passwordHasher->hash($randPass);
		$secretToken = base64_encode($applicationId . ':' . $secretHash);
		$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : exec("hostname");

		$pdfUrl = "https://" . $hostname . '/CobrandedApplications/pdf_doc_token_dl?token=' . $secretToken;

		$subject = $dbaBusinessName . ' - Online Application Signed';
		$format = 'html';
		$template = 'notify_uw_signed_app';
		
		$description = "Application Description: ";
		$description .= Hash::get($cobrandedApplication, 'Template.Cobrand.partner_name') . ' (' . Hash::get($cobrandedApplication, 'Template.name') . ' template)';
		$viewVars['merchant'] = $dbaBusinessName;
		$viewVars['description'] = $description;
		$viewVars['appPdfUrl'] = $pdfUrl;
		$viewVars['repName'] = $repName;

		if ($optionalTemplate != null) {
			$template = $optionalTemplate;
		}

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
			$args['email_timeline_subject_id'] = EmailTimeline::APP_SENT_TO_UW;
			$args['recipient'] = implode(';', $to);
			$response = $this->createEmailTimelineEntry($args);
			$this->id = $applicationId;
			$this->saveField('doc_secret_token', $secretHash);
		}

		return $response['success'];
	}

/**
 * repNotifySignedEmail
 *
 * @param int $applicationId Cobranded Application Id
 * @param string $optionalTemplate optional template to use
 *
 * @return $response array
 */
	public function repNotifySignedEmail($applicationId, $optionalTemplate = null) {
		if (!$this->exists($applicationId)) {
			$response = array(
				'success' => false,
				'msg' => 'Invalid application.',
			);
			return $response;
		}

		$settings = array('contain' => array(
			'User',
			'CobrandedApplicationValues',
			'Template' => array(
				'fields' => array('email_app_pdf', 'name'),
				'Cobrand.partner_name'
			),
		));
		$cobrandedApplication = $this->getById($applicationId, $settings);

		$dbaBusinessName = '';
		$valuesMap = $this->buildCobrandedApplicationValuesMap($cobrandedApplication['CobrandedApplicationValues']);

		if (!empty($valuesMap['DBA'])) {
			$dbaBusinessName = $valuesMap['DBA'];
		}

		
		$from = array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications');
		$to = array($cobrandedApplication['User']['email']);

		$subject = $dbaBusinessName . ' - Online Application Signed';
		$format = 'text';
		$template = 'rep_notify_signed';
		
		$description = "Application Description: ";
		if (stripos(Hash::get($cobrandedApplication, 'Template.name'), 'Cancellation') !== false) {
			$description = 'Description: ';
			$template = 'rep_notify_cancellation_signed';
			$to[] = EmailTimeline::CANCELLATIONS_EMAIL;
		}
		$description .= Hash::get($cobrandedApplication, 'Template.Cobrand.partner_name') . ' (' . Hash::get($cobrandedApplication, 'Template.name') . ' template)';
		$viewVars['rep'] = $cobrandedApplication['User']['email'];
		$viewVars['merchant'] = $dbaBusinessName;
		$viewVars['description'] = $description;
		$viewVars['link'] = Router::url('/users/login', true);
		if (Hash::get($cobrandedApplication, 'Template.email_app_pdf')) {
			$viewVars['appPdfUrl'] = Router::url("/admin/CobrandedApplications/open_app_pdf/$applicationId", true);
		}

		if ($optionalTemplate != null) {
			$template = $optionalTemplate;
		}

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
			$args['recipient'] = implode(';', $to);
			$response = $this->createEmailTimelineEntry($args);
		}

		return $response;
	}

/**
 * getAppPdfUrl
 * Makes a RightSignature API call to retrieve the PDF URL.
 *
 * @param int $id Cobranded Application Id
 * @param boolean $overrideTemplate wheter to force retrival of the PDF URL regardless of whether the template allows PDF template to be emailed or not
 * @param boolean $getOriginalPdf true to specify whether to retrieve the original blank pdf template used to create this application's completed document.
 * @return string The URL where the PDF is located.
 */
	public function getAppPdfUrl($id, $overrideTemplate = false, $getOriginalPdf = false) {
		$appData = $this->find('first', array(
			'recursive' => -1,
			'fields' => array(
				'CobrandedApplication.rightsignature_document_guid',
			),
			'conditions' => array('CobrandedApplication.id' => $id),
			'contain' => array('Template.email_app_pdf'),
		));

		$appPdfUrl = null;

		if ($overrideTemplate || $appData['Template']['email_app_pdf'] === true) {
			$guid = $appData['CobrandedApplication']['rightsignature_document_guid'];
			$client = $this->createRightSignatureClient();
			$docDetals = $client->getDocumentDetails($guid);
			$docData = json_decode($docDetals, true);

			if (!empty($docData)) {
				$appPdfUrl = ($getOriginalPdf == false)? Hash::get($docData, 'document.merged_document_certificate_url') : Hash::get($docData, 'document.original_file_url');
			}
		}
		return $appPdfUrl;
	}

/**
 * submitForReviewEmail
 *
 * @param int $applicationId Cobranded Application Id
 *
 * @return $response array
 */
	public function submitForReviewEmail($applicationId) {
		if (!$this->exists($applicationId)) {
			$response = array(
				'success' => false,
				'msg' => 'Invalid application.',
			);
			return $response;
		}

		$settings = array('contain' => array(
				'User',
				'CobrandedApplicationValues',
			));
		$cobrandedApplication = $this->getById($applicationId, $settings);

		$dbaBusinessName = '';
		$valuesMap = $this->buildCobrandedApplicationValuesMap($cobrandedApplication['CobrandedApplicationValues']);

		if (!empty($valuesMap['DBA'])) {
			$dbaBusinessName = $valuesMap['DBA'];
		}

		$from = array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications');
		$to = $cobrandedApplication['User']['email'];
		$subject = $dbaBusinessName . ' - Online Application Merchant Portion Completed';
		$format = 'text';
		$template = 'rep_notify';
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
			$args['email_timeline_subject_id'] = EmailTimeline::MERCHANT_PORTION_COMPLETE;
			$args['recipient'] = $cobrandedApplication['User']['email'];
			$response = $this->createEmailTimelineEntry($args);
		}

		return $response;
	}

/**
 * sendRightsignatureInstallSheetEmail
 *
 * @param int $applicationId Cobranded Application Id
 * @param string $email email address for the user that should recieve the email
 *
 * @return mixed response message
 */
	public function sendRightsignatureInstallSheetEmail($applicationId, $email) {
		if (!$this->exists($applicationId)) {
			$response = array(
				'success' => false,
				'msg' => 'Invalid application.',
			);
			return $response;
		}

		$settings = array('contain' => array(
				'Template',
				'CobrandedApplicationValues',
			));
		$cobrandedApplication = $this->getById($applicationId, $settings);

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
		$subject = $dbaBusinessName . ' - Install Sheet';
		$format = 'both';
		$template = 'email_install_var';
		$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : exec("hostname");
		$viewVars = array();
		$viewVars['ownerName'] = $ownerName;
		$viewVars['merchant'] = $dbaBusinessName;
		$viewVars['url'] = "https://" . $hostname . "/cobranded_applications/sign_rightsignature_document?guid=" .
			$cobrandedApplication['CobrandedApplication']['rightsignature_install_document_guid'];

		$this->Cobrand = ClassRegistry::init('Cobrand');
		$cobrand = $this->Cobrand->find(
			'first',
			array(
				'conditions' => array(
					'Cobrand.id' => $cobrandedApplication['Template']['cobrand_id']
				)
			)
		);

		$viewVars['brandLogo'] = $cobrand['Cobrand']['brand_logo_url'];

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
 * createNewApiApplicationEmailTimelineEntry
 *
 * @param array $args arguments that dictate the information for the timeline entry
 *
 * @return $response array
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
 * @param array $args arguments that dictate the information for the timeline entry
 *
 * @return $response array
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

		$dboSource = $this->EmailTimeline->getDataSource();

		$EmailTimeline->create();
		$success = $EmailTimeline->save(
			array(
				'cobranded_application_id' => $args['cobranded_application_id'],
				'date' => $dboSource->expression('NOW()'),
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
 * @param object $client RightSignature OAuth Client Object
 * @param string $templateGuid RightSignature template GUID
 *
 * @return $response array
 */
	public function getRightSignatureTemplate($client, $templateId) {
		$response = $client->api->get($client->base_url.RightSignature::API_V1_PATH. "/reusable_templates/$templateId");
		return $response;
	}

/**
 * createRightSignatureDocument
 *
 * @params
 *     $templateId string id of the reusable template to use to create the new document
 *     $client object
 *     $appJsonStr string JSON string data to pupulate the document merge field values
 * @returns
 *     $response array
 */
	public function createRightSignatureDocument($templateId, $client, $appJsonStr) {
		$response = $client->api->post($client->base_url.RightSignature::API_V1_PATH. "/reusable_templates/$templateId/send_document", $appJsonStr);
		return $response;
	}

/**
 * getRightSignatureTemplates
 *
 * @params
 *     $client object
 * @returns
 *     $templates array
 */
	public function getRightSignatureTemplates($client) {
		$page = 1;
		$totalPages = 99;
		$templates = array();

		while ($page <= $totalPages) {
			$response = $client->api->get($client->base_url.RightSignature::API_V1_PATH."/reusable_templates?page=". $page);
			$response = json_decode($response, true);
			if (empty($response) || !empty(Hash::get($response, 'error'))) {
				return empty($response)? [] : $response;
			}
			$totalPages = $response['meta']['total_pages'];

			foreach ($response['reusable_templates'] as $arr) {
				$filename = $arr['filename'];
				$id = $arr['id'];
				$templates[$id] = $filename;
			}
			$page++;
		}

		array_multisort($templates);
		return $templates;
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
		return new RightSignature();
	}

/**
 * arrayDiffOneSignerTwoSigners
 * This method retreives all details about each template from RightSignature, analyzes the roles
 * in each RightSignature template and differenciates the templates that are intended for one owner/signer
 * to sign the corresponding applications from those intended for two owners/signers.
 * Additionally it finds templates intended for install sheets and separates it from the rest.
 * 
 * @param array $templateList a one dimensional array of all RightSignature templates with the Id as key
 *                            and some description as the value ['<id>' => 'Template Description']
 * @return array a two timetinal array containing three sub-array enties at the top dimention, one entry
 * contains a list of templates intended for one owner/signer to sign, another entry contains templates
 * with two owners/signer, and a last one containing install sheet templates
 * i.e: [
 * 		'single_signers' => ['<id>' => 'Template Description', <...[]> ],
 * 	 	'two_signers' => ['<id>' => 'Template Description', <...[]> ]
 * 	 	'install_templates' => ['<id>' => 'Template Description', <...[]> ]
 * 	];
 * 	@throws Exception when an API error ocurrs
 */
	public function arrayDiffTemplateTypes ($templateList) {
		if (empty($templateList)) {
			return [];
		}
		$orderedList = [
			'templates' => [],
			'install_templates' => [],
		];
		foreach ($templateList as $rsUuid => $filename) {
			if (empty($orderedList['install_templates']) && preg_match('/install/i', $filename)) {
				//there is only one install sheet template
				$orderedList['install_templates'][$rsUuid] = $filename;
			} else {
				$orderedList['templates'][$rsUuid] = $filename;
			}
		}
		return $orderedList;
	}

/**
 * createRightSignatureApplicationXml
 *
 * @params
 *     $applicationId int
 *     $sender string
 *     $rightSignatureTemplate array
 *     $subject string
 *     $terminalType string
 * @returns
 *     $xml string
 */
	public function createRightSignatureApplicationXml($applicationId, $sender, $rightSignatureTemplate, $subject = null, $terminalType = null) {
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

		$this->Cobrand = ClassRegistry::init('Cobrand');

		$cobrand = $this->Cobrand->find(
			'first',
			array(
				'conditions' => array(
					'Cobrand.id' => $cobrandedApplication['Template']['cobrand_id']
				)
			)
		);

		$partnerName = $cobrand['Cobrand']['partner_name'];

		$owner1Fullname = '';
		$owner2Fullname = '';
		$dbaBusinessName = '';

		$owner1Email = '';
		$owner2Email = '';

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
		if (!empty($valuesMap['Owner1Email'])) {
			$owner1Email = $valuesMap['Owner1Email'];
		}
		if (!empty($valuesMap['Owner2Email'])) {
			$owner2Email = $valuesMap['Owner2Email'];
		}

		$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : exec("hostname");

		$xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
		$xml .= "	<template>\n";
		$xml .= "		<guid>" . $rightSignatureTemplate['guid'] . "</guid>\n";
		if ($subject == null) {
			$xml .= "		<subject>" . htmlspecialchars($dbaBusinessName) . " Axia Merchant Application</subject>\n";
		} else {
			$xml .= "		<subject>" . htmlspecialchars($dbaBusinessName) . " " . $subject . "</subject>\n";
		}
		$xml .= "		<description>Sent for signature by " . $sender . "</description>\n";
		$xml .= "		<action>send</action>\n";
		$xml .= "		<expires_in>10 days</expires_in>\n";
		$xml .= "		<roles>\n";

		if ($subject == 'Axia Install Sheet - VAR') {
			if (!empty($owner1Fullname)) {
				$xml .= "			<role role_name='Signer'>\n";
				$xml .= "				<name>" . htmlspecialchars($owner1Fullname ) . "</name>\n";
				$xml .= "				<email>" . htmlspecialchars('noemail@rightsignature.com') . "</email>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</role>\n";
			}
		} else {
			if (!empty($owner1Fullname) && !empty($owner1Email)) {
				$xml .= "			<role role_name='Owner/Officer 1 PG'>\n";
				$xml .= "				<name>" . htmlspecialchars($owner1Fullname ) . "</name>\n";
				$xml .= "				<email>" . htmlspecialchars('noemail@rightsignature.com') . "</email>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</role>\n";
				$xml .= "			<role role_name='Owner/Officer 1'>\n";
				$xml .= "				<name>" . htmlspecialchars($owner1Fullname ) . "</name>\n";
				$xml .= "				<email>" . htmlspecialchars('noemail@rightsignature.com') . "</email>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</role>\n";
			}

			if (!empty($owner2Fullname) && !empty($owner2Email)) {
				$xml .= "			<role role_name='Owner/Officer 2 PG'>\n";
				$xml .= "				<name>" . htmlspecialchars($owner2Fullname) . "</name>\n";
				$xml .= "				<email>" . htmlspecialchars('noemail@rightsignature.com') . "</email>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</role>\n";
				$xml .= "			<role role_name='Owner/Officer 2'>\n";
				$xml .= "				<name>" . htmlspecialchars($owner2Fullname) . "</name>\n";
				$xml .= "				<email>" . htmlspecialchars('noemail@rightsignature.com') . "</email>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</role>\n";
			}
		}

		$xml .= "		</roles>\n";
		$xml .= "		<merge_fields>\n";

		foreach ($rightSignatureTemplate['merge_fields'] as $mergeField) {
			$appValue = null;

			if ($partnerName == 'Corral' && $mergeField['name'] == 'Terminal2-') {
				$appValue = $this->CobrandedApplicationValues->find(
					'first',
					array(
						'conditions' => array(
							'cobranded_application_id' => $applicationId,
							'CobrandedApplicationValues.name LIKE' => 'Terminal2-%',
							'CobrandedApplicationValues.value' => 'true'
						),
					)
				);

			if (!empty($appValue)) {
					$name = $appValue['CobrandedApplicationValues']['name'];
					$name = preg_replace('/^Terminal2-/', '', $name);
					$appValue['CobrandedApplicationValues']['value'] = $name;
					$appValue['TemplateField']['type'] = 0;
				}
			} else {
				$appValue = $this->CobrandedApplicationValues->find(
					'first',
					array(
						'conditions' => array(
							'cobranded_application_id' => $applicationId,
							'CobrandedApplicationValues.name' => $mergeField['name']
						),
					)
				);
			}

			// we don't want to send null or empty values
			if (isset($appValue['CobrandedApplicationValues']['value'])) {
				$fieldType = $appValue['TemplateField']['type'];

				// type 3 is checkbox, 4 is radio
				// we send different elements for multi option types
				if ($fieldType == 3 || $fieldType == 4) {
					if ($appValue['CobrandedApplicationValues']['value'] == 'true') {
						$xml .= "			<merge_field merge_field_name='" . $mergeField['name'] . "'>\n";
						$xml .= "				<value>X</value>\n";
						$xml .= "				<locked>true</locked>\n";
						$xml .= "			</merge_field>\n";
					}
				} else {
					$xml .= "			<merge_field merge_field_name='" . $mergeField['name'] . "'>\n";
					$xml .= "				<value>" . htmlspecialchars($appValue['CobrandedApplicationValues']['value']) . "</value>\n";
					$xml .= "				<locked>true</locked>\n";
					$xml .= "			</merge_field>\n";
				}
			}

			if ($mergeField['name'] == "SystemType") {
				$xml .= "			<merge_field merge_field_name='" . $mergeField['name'] . "'>\n";
				$xml .= "				<value>" . htmlspecialchars($terminalType) . "</value>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</merge_field>\n";
			}

			if ($mergeField['name'] == "MID") {
				$xml .= "			<merge_field merge_field_name='" . $mergeField['name'] . "'>\n";
				$xml .= "				<value>" . htmlspecialchars($cobrandedApplication['Merchant']['merchant_mid']) . "</value>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</merge_field>\n";
			}

			if ($mergeField['name'] == "Customer Service") {
				$xml .= "			<merge_field merge_field_name='" . $mergeField['name'] . " Checkbox'>\n";
				$xml .= "				<value>" . htmlspecialchars('x') . "</value>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</merge_field>\n";
			}
			if ($mergeField['name'] == "Product Shipment") {
				$xml .= "			<merge_field merge_field_name='" . $mergeField['name'] . " Checkbox'>\n";
				$xml .= "				<value>" . htmlspecialchars('x') . "</value>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</merge_field>\n";
			}
			if ($mergeField['name'] == "Handling of Returns") {
				$xml .= "			<merge_field merge_field_name='" . $mergeField['name'] . " Checkbox'>\n";
				$xml .= "				<value>" . htmlspecialchars('x') . "</value>\n";
				$xml .= "				<locked>true</locked>\n";
				$xml .= "			</merge_field>\n";
			}
		}

		if ($subject == 'Axia Install Sheet - VAR') {
			$xml .= "			<merge_field merge_field_name='Phone#'>\n";
			if ($cobrandedApplication['User']['extension'] != "") {
				$xml .= "				<value>" . htmlspecialchars('877.875.6114' . " x " . $cobrandedApplication['User']['extension']) . "</value>\n";
			} else {
				$xml .= "				<value>" . htmlspecialchars('877.875.6114') . "</value>\n";
			}
			$xml .= "				<locked>true</locked>\n";
			$xml .= "			</merge_field>\n";

			$xml .= "			<merge_field merge_field_name='RepFax#'>\n";
			$xml .= "				<value>" . htmlspecialchars('877.875.5135') . "</value>\n";
			$xml .= "				<locked>true</locked>\n";
			$xml .= "			</merge_field>\n";
		}

		$now = date('m/d/Y');

		$xml .= "			<merge_field merge_field_name='Application Date'>\n";
		$xml .= "				<value>" . htmlspecialchars($now) . "</value>\n";
		$xml .= "				<locked>true</locked>\n";
		$xml .= "			</merge_field>\n";

		$xml .= "		</merge_fields>\n";
		$xml .= "		<callback_location>https://" . $hostname . "/cobranded_applications/document_callback</callback_location>\n";
		$xml .= "	</template>\n";

		return $xml;
	}

/**
 * createRightSignatureDocumentJSON
 *
 * @params
 *     $applicationId int
 *     $sender string
 *     $rightSignatureTemplate array
 *     $subject string
 *     $terminalType string
 * @returns
 *     JSON string containing new app data
 */
	public function createRightSignatureDocumentJSON($applicationId, $sender, $rightSignatureTemplate, $subject = null, $terminalType = null) {
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

		$this->Cobrand = ClassRegistry::init('Cobrand');

		$cobrand = $this->Cobrand->find(
			'first',
			array(
				'conditions' => array(
					'Cobrand.id' => $cobrandedApplication['Template']['cobrand_id']
				)
			)
		);

		$partnerName = $cobrand['Cobrand']['partner_name'];

		$owner1Fullname = '';
		$owner2Fullname = '';
		$dbaBusinessName = '';

		$owner1Email = '';
		$owner2Email = '';

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
		if (!empty($valuesMap['Owner1Email'])) {
			$owner1Email = $valuesMap['Owner1Email'];
		}
		if (!empty($valuesMap['Owner2Email'])) {
			$owner2Email = $valuesMap['Owner2Email'];
		}

		$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : exec("hostname");

		
		$docData = array();
		if ($subject == null) {
			$docData['name'] = htmlspecialchars($dbaBusinessName) . ' Axia Merchant Application';
		} else {
			$docData['name'] = htmlspecialchars($dbaBusinessName) . ' ' . $subject;
		}
		$docData['message'] = "Please sign this document. (Sent for signature by $sender)";
		$docData['expires_in'] = 20;

		$docRoles = array();
		foreach ($rightSignatureTemplate['roles'] as $rsRole) {
			//map officer owner 1 to $owner1Fullname
			if (($subject == 'Axia Install Sheet - VAR') || (strpos($rsRole['name'], '1') !== false && !empty($owner1Fullname) && !empty($owner1Email))) {
				$docRoles[] = array(
					'name' => $rsRole['name'],
					'signer_name' => htmlspecialchars($owner1Fullname),
					'signer_email' => htmlspecialchars('noemail@rightsignature.com'),
				);
			}
			//map officer owner 2 to $owner2Fullname
			if (strpos($rsRole['name'], '2') !== false) {
				$docRoles[] = array(
					'name' => $rsRole['name'],
					'signer_name' => (empty($owner2Fullname))? 'N/A' : htmlspecialchars($owner2Fullname),
					'signer_email' => htmlspecialchars('noemail@rightsignature.com'),
					'signer_omitted' => (empty($owner2Email) || empty($owner2Fullname)),
				);
			}
		}
		$docData['roles'] = $docRoles;
		$mergefieldVals = array();
		$installSheetFieldsAdded = false;
		$addedSheetField1 = false;
		$addedSheetField2 = false;
		foreach ($rightSignatureTemplate['merge_field_components'] as $mergeField) {
			$appValue = null;
			if ($partnerName == 'Corral' && $mergeField['name'] == 'Terminal2-') {
				$appValue = $this->CobrandedApplicationValues->find(
					'first',
					array(
						'conditions' => array(
							'cobranded_application_id' => $applicationId,
							'CobrandedApplicationValues.name LIKE' => 'Terminal2-%',
							'CobrandedApplicationValues.value' => 'true'
						),
					)
				);

			if (!empty($appValue)) {
					$name = $appValue['CobrandedApplicationValues']['name'];
					$name = preg_replace('/^Terminal2-/', '', $name);
					$appValue['CobrandedApplicationValues']['value'] = $name;
					$appValue['TemplateField']['type'] = 0;
				}
			} else {
				$appValue = $this->CobrandedApplicationValues->find(
					'first',
					array(
						'conditions' => array(
							'cobranded_application_id' => $applicationId,
							'CobrandedApplicationValues.name' => $mergeField['name']
						),
					)
				);
			}

			// we don't want to send null or empty values
			if (isset($appValue['CobrandedApplicationValues']['value'])) {
				$fieldType = $appValue['TemplateField']['type'];

				// type 3 is checkbox, 4 is radio
				// we send different elements for multi option types
				if ($fieldType == 3 || $fieldType == 4) {
					if ($appValue['CobrandedApplicationValues']['value'] == 'true') {
						$mergefieldVals[] = array(
							'id' => $mergeField['id'],
							'value' => 'X',
						);
					}
				} else {
					$mergefieldVals[] = array(
						'id' => $mergeField['id'],
						'value' => $appValue['CobrandedApplicationValues']['value'],
					);
				}
			}

			if ($mergeField['name'] == "SystemType") {
				$mergefieldVals[] = array(
					'id' => $mergeField['id'],
					'value' => htmlspecialchars($terminalType),
				);
			}

			if ($mergeField['name'] == "MID") {
				$mergefieldVals[] = array(
					'id' => $mergeField['id'],
					'value' => htmlspecialchars($cobrandedApplication['Merchant']['merchant_mid']),
				);
			}

			if ($mergeField['name'] == "Customer Service" || $mergeField['name'] == "Product Shipment" || $mergeField['name'] == "Handling of Returns") {
				$mergefieldVals[] = array(
					'id' => $mergeField['id'],
					'value' => 'x',
				);
			}

			if ($subject == 'Axia Install Sheet - VAR' && $installSheetFieldsAdded === false) {

				if ($mergeField['name'] == 'Phone#') {
					$tmpFieldVal = '877.875.6114';
					if ($cobrandedApplication['User']['extension'] != "") {
						$tmpFieldVal .= " x " . htmlspecialchars($cobrandedApplication['User']['extension']);
					}
					$mergefieldVals[] = array(
						'id' => $mergeField['id'],
						'value' => $tmpFieldVal,
					);
					$addedSheetField1 = true;
				}
				if ($mergeField['name'] == "RepFax#") {
					$mergefieldVals[] = array(
						'id' => $mergeField['id'],
						'value' => htmlspecialchars('877.875.5135'),
					);
					$addedSheetField2 = true;
				}
				$installSheetFieldsAdded = ($addedSheetField1 && $addedSheetField2);
			}

		}// end foreach merge_field_components
		//Add merge_field_values
		$docData['merge_field_values'] = $mergefieldVals;
		/****************************************************************************************/
		//Some of the parameters below this comment may be ignored by rightsignature when creating 
		//the documents depending on which endpoint is used to create the document.		
		//Callback url only used if creating document using either of these endpoints 
		//POST /reusable_templates/:id/send_document
		//Otherwise ignored
		$docData['callback_url'] = "https://" . $hostname . "/cobranded_applications/document_callback";
		//this param will allow marking document as signed without the need of email indentity verification
		$docData['in_person'] = true;
		return json_encode($docData);
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

		$autocloseTime1Page;
		$merchantDoesAutoclose = false;
		$autocloseTime;

		$ach = false;

		foreach ($cobrandedApplication['CobrandedApplicationValues'] as $tmpVal) {
			if ($tmpVal['name'] == 'OwnerType-NonProfit' && $tmpVal['value'] == true) {
				$isNonProfit = true;
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

			if ($tmpVal['name'] == '%OfProductSold-DirectToGovernment') {
				$productSoldDirectToGovernment = $tmpVal['value'];
			}

			if ($tmpVal['name'] == '%OfProductSold-DirectToCustomer') {
				$productSoldDirectToCustomer = $tmpVal['value'];
			}

			if ($tmpVal['name'] == '%OfProductSold-DirectToBusiness') {
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

			if ($tmpVal['name'] == 'Owner1Equity' || $tmpVal['name'] == 'OwnerEquity') {
				$owner1Equity = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'Owner2Equity') {
				$owner2Equity = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'DoYouUseAutoclose-Autoclose') {
				$merchantDoesAutoclose = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'Autoclose Time 1') {
				$autocloseTime = $tmpVal['value'];
			}

			if ($tmpVal['name'] == 'ACH-Yes' && $tmpVal['value'] == true) {
				$ach = true;
			}
		}

		$methodofSalesTotal = $methodofSalesCardNotPresentInternet + $methodofSalesCardNotPresentKeyed + $methodofSalesCardPresentImprint + $methodofSalesCardPresentSwiped;

		$methodofSalesCardNotPresentTotal = $methodofSalesCardNotPresentInternet + $methodofSalesCardNotPresentKeyed;

		$productSoldDirectTotal = $productSoldDirectToGovernment + $productSoldDirectToCustomer + $productSoldDirectToBusiness;

		$percentOfPayTotal = $percentFullPayUpFront + $percentPartialPayUpFront + $percentPayReceivedAfter + $percentAndWithin;

		$ownerEquityTotal = $owner1Equity + $owner2Equity;

		$owner2FieldsNotComplete = false;

		$template = $this->Template->find('first', array(
			'conditions' => array('Template.id' => $cobrandedApplication['CobrandedApplication']['template_id']),
			'contain' => array(
				'TemplatePages' => array(
					'TemplateSections' => array(
						'TemplateFields' => array(
							'fields' => array('id', 'type', 'name', 'default_value', 'merge_field_name', 'order', 'width', 'required', 'rep_only')
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

					if ($templateField['merge_field_name'] == '%OfProductSold-') {
						$productSoldDirectPage = $pageOrder;
					}

					if ($templateField['merge_field_name'] == 'PercentFullPayUpFront') {
						$percentOfPayPage = $pageOrder;
					}

					if ($templateField['merge_field_name'] == 'Owner1Equity') {
						$ownerEquityPage = $pageOrder;
					}

					if ($templateField['merge_field_name'] == 'Autoclose Time 1') {
						$autocloseTime1Page = $pageOrder;
					}

					// Owner2 information should be required if Owner1Equity < owner_equity_threshold
					if ($owner1Equity < $template['Template']['owner_equity_threshold']) {
						if ($templateField['merge_field_name'] == 'Owner2Name' ||
							$templateField['merge_field_name'] == 'Owner2Title' ||
							$templateField['merge_field_name'] == 'Owner2Address' ||
							$templateField['merge_field_name'] == 'Owner2City' ||
							$templateField['merge_field_name'] == 'Owner2State' ||
							$templateField['merge_field_name'] == 'Owner2Zip' ||
							$templateField['merge_field_name'] == 'Owner2Phone' ||
							$templateField['merge_field_name'] == 'Owner2DOB' ||
							$templateField['merge_field_name'] == 'Owner2SSN' ||
							$templateField['merge_field_name'] == 'Owner2Email' ||
							$templateField['merge_field_name'] == 'Owner2Equity') {

							$templateField['required'] = true;
						}
					}

					if ($templateField['required'] == true) {
						// don't validate MOTO/Internet Questionnaire section if
						// methodOfSalesCardNotPresentKeyed + methodOfSalesCardNotPresentInternet < 30
						if (preg_match('/MOTO\/Internet/', $section['name']) && $methodofSalesCardNotPresentTotal < 30) {
							continue;
						}

						// SSN should not be required if Ownership Type is Non Profit
						if (($templateField['merge_field_name'] == 'OwnerSSN' || $templateField['merge_field_name'] == 'Owner2SSN') && $isNonProfit) {
							continue;
						}

						// WebAddress can be empty
						if ($templateField['merge_field_name'] == 'WebAddress') {
							continue;
						}

						// don't validate if ach is not true
						if ($templateField['merge_field_name'] == 'AnnualCheckVolume' && $ach != true) {
							continue;
						}

						// don't validate if ach is not true
						if ($templateField['merge_field_name'] == 'TotalSalesVolume' && $ach != true) {
							continue;
						}

						if ($templateField['type'] == 4) {
							$found = false;
							foreach ($cobrandedApplication['CobrandedApplicationValues'] as $tmpVal) {
								if ($tmpVal['template_field_id'] == $templateField['id']) {
									if (empty($tmpVal['value']) == false) {
										$found = true;
									}
								}
							}

							if ($found == false) {
								$mergeFieldName = $templateField['merge_field_name'];

								// update our validationErrors array
								$response['validationErrors'] = Hash::insert($response['validationErrors'], $mergeFieldName, 'required');

								$errorArray = array();
								$errorArray['fieldName'] = $fieldName;
								$errorArray['mergeFieldName'] = $mergeFieldName;
								$errorArray['msg'] = 'Required field is empty: ' . $fieldName;
								$errorArray['page'] = $pageOrder;
								$errorArray['rep_only'] = $templateField['rep_only'];

								$response['validationErrorsArray'][] = $errorArray;
							}
						} else {
							foreach ($cobrandedApplication['CobrandedApplicationValues'] as $tmpVal) {
								$found = true;

								if ($tmpVal['template_field_id'] == $templateField['id']) {
									$found = false;
									if (empty($tmpVal['value']) == false || preg_match('/\d+/', $tmpVal['value'])) {
										// is the value valid?
										$validValue = $this->CobrandedApplicationValue->validApplicationValue($tmpVal, $templateField['type'], $templateField);
										if ($validValue == true) {
											$found = true;

											// federal tax id should be 12-3456789 or 123-45-6789
											if ($templateField['merge_field_name'] == 'TaxID') {
												if (!preg_match("/^\d{2}-?\d{7}$/", $tmpVal['value']) && !preg_match("/^\d{3}-?\d{2}-?\d{4}$/", $tmpVal['value'])) {
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
									$mergeFieldName = $templateField['merge_field_name'];

									if (empty($mergeFieldName)) {
										$fieldName = $tmpVal['name'];
									}

									$mergeFieldName = $tmpVal['name'];

									// update our validationErrors array
									$response['validationErrors'] = Hash::insert($response['validationErrors'], $mergeFieldName, 'required');

									$errorArray = array();
									$errorArray['fieldName'] = $fieldName;
									$errorArray['mergeFieldName'] = $mergeFieldName;
									$errorArray['msg'] = 'Required field is empty: ' . $fieldName;
									$errorArray['page'] = $pageOrder;
									$errorArray['rep_only'] = $templateField['rep_only'];

									$response['validationErrorsArray'][] = $errorArray;

									if ($templateField['merge_field_name'] == 'Owner2Name' ||
										$templateField['merge_field_name'] == 'Owner2Title' ||
										$templateField['merge_field_name'] == 'Owner2Address' ||
										$templateField['merge_field_name'] == 'Owner2City' ||
										$templateField['merge_field_name'] == 'Owner2State' ||
										$templateField['merge_field_name'] == 'Owner2Zip' ||
										$templateField['merge_field_name'] == 'Owner2Phone' ||
										$templateField['merge_field_name'] == 'Owner2DOB' ||
										$templateField['merge_field_name'] == 'Owner2SSN' ||
										$templateField['merge_field_name'] == 'Owner2Email' ||
										$templateField['merge_field_name'] == 'Owner2Equity') {

										$owner2FieldsNotComplete = true;
									}
								}
							}
						}
					}
				}
			}
		}

		if (isset($methodofSalesPage) && $methodofSalesTotal != 100 && $source == 'ui') {
			// update our validationErrors array
			$response['validationErrors'] = Hash::insert($response['validationErrors'], 'MethodofSales_Total', 'does not equal 100');

			$errorArray = array();
			$errorArray['fieldName'] = 'Method of Sales Total';
			$errorArray['mergeFieldName'] = 'MethodofSales_Total';
			$errorArray['msg'] = 'Method of Sales Total does not equal 100';
			$errorArray['page'] = $methodofSalesPage;

			$response['validationErrorsArray'][] = $errorArray;
		}

		if (isset($productSoldDirectPage) && $productSoldDirectTotal != 100 && $source == 'ui') {
			// update our validationErrors array
			$response['validationErrors'] = Hash::insert($response['validationErrors'], 'ofProductSold_Total', 'does not equal 100');

			$errorArray = array();
			$errorArray['fieldName'] = '% of Product Sold';
			$errorArray['mergeFieldName'] = 'ofProductSold_Total';
			$errorArray['msg'] = '% of Product Sold Total does not equal 100';
			$errorArray['page'] = $productSoldDirectPage;

			$response['validationErrorsArray'][] = $errorArray;
		}

		if ($methodofSalesCardNotPresentTotal >= 30 && $percentOfPayTotal < 100 && $source == 'ui') {
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

		if ($owner1Equity < $template['Template']['owner_equity_threshold'] && $owner2FieldsNotComplete == true) {
			// update our validationErrors array
			$response['validationErrors'] = Hash::insert($response['validationErrors'], 'Owner1Equity', 'Combined Ownership Needs to Exceed ' . $template['Template']['owner_equity_threshold'] . '%');

			$errorArray = array();
			$errorArray['fieldName'] = 'Owner 1 Equity';
			$errorArray['mergeFieldName'] = 'Owner1Equity';
			$errorArray['msg'] = 'Combined Ownership Needs to Exceed ' . $template['Template']['owner_equity_threshold'] . '%';
			$errorArray['page'] = $ownerEquityPage;

			$response['validationErrorsArray'][] = $errorArray;
		}

		if ($merchantDoesAutoclose == true && $autocloseTime == '') {
			// update our validationErrors array
			$response['validationErrors'] = Hash::insert($response['validationErrors'], 'Autoclose Time 1', 'is empty');

			$errorArray = array();
			$errorArray['fieldName'] = 'Autoclose Time 1';
			$errorArray['mergeFieldName'] = 'Autoclose Time 1';
			$errorArray['msg'] = 'Autoclose Time 1 is empty';
			$errorArray['page'] = $autocloseTime1Page;

			$response['validationErrorsArray'][] = $errorArray;
		}

		if (count($response['validationErrors']) == 0) {
			$response['success'] = true;
			$response['msg'] = '';
		}

		return $response;
	}

/**
 * getRightsignatureTemplateGuid
 *
 * @params
 *     $cobrandName string
 *     $type string
 *
 * @returns
 *     $guid string
 */
	public function getRightsignatureTemplateGuid($cobrandName = null, $type = null) {
		$guid = null;

		if ($cobrandName == 'Corral' && $type == 'ach') {
			// Corral AxiaMed Vericheck Merchant Agreement
			$guid = self::CORRAL_ACH_TEMPLATE_GUID;
				//'a_9862353_e947fd71b87a43539a4e06a95c184ce6';
		}

		return $guid;
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
				'CobrandedApplication.api_exported_date',
				'CobrandedApplication.csv_exported_date',
				'CobrandedApplication.rightsignature_install_document_guid',
				'CobrandedApplication.rightsignature_install_status',
				'CobrandedApplication.data_to_sync',
				'CobrandedApplication.client_id_global',
				'CobrandedApplication.client_name_global',
				'Cobrand.id',
				'Cobrand.partner_name',
				'Template.id',
				'Template.name',
				'Template.requires_coversheet',
				'Template.email_app_pdf',
				'User.id',
				'User.firstname',
				'User.lastname',
				'User.email',
				'Coversheet.id',
				'Dba.value',
				'CorpName.value',
				'CorpContact.value',
				'Owner1Name.value',
				'CorpPhone.value',
				'PhoneNum.value',
				'Owner1Email.value',
				'Owner2Email.value',
				'EMail.value',
				'LocEMail.value',
				'Merchant.id'
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
				array('table' => 'onlineapp_cobranded_application_values',
					'alias' => 'CorpPhone',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = CorpPhone.cobranded_application_id and CorpPhone.name =' . "'CorpPhone'",
					),
				),
				array('table' => 'onlineapp_cobranded_application_values',
					'alias' => 'PhoneNum',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = PhoneNum.cobranded_application_id and PhoneNum.name =' . "'PhoneNum'",
					),
				),
				array('table' => 'onlineapp_cobranded_application_values',
					'alias' => 'Owner1Name',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = Owner1Name.cobranded_application_id and Owner1Name.name =' . "'Owner1Name'",
					),
				),
				array('table' => 'onlineapp_cobranded_application_values',
					'alias' => 'Owner1Email',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = Owner1Email.cobranded_application_id and Owner1Email.name =' . "'Owner1Email'",
					),
				),
				array('table' => 'onlineapp_cobranded_application_values',
					'alias' => 'Owner2Email',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = Owner2Email.cobranded_application_id and Owner2Email.name =' . "'Owner2Email'",
					),
				),
				array('table' => 'onlineapp_cobranded_application_values',
					'alias' => 'EMail',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = EMail.cobranded_application_id and EMail.name =' . "'EMail'",
					),
				),
				array('table' => 'onlineapp_cobranded_application_values',
					'alias' => 'LocEMail',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = LocEMail.cobranded_application_id and LocEMail.name =' . "'LocEMail'",
					),
				),
				array('table' => 'onlineapp_templates',
					'alias' => 'Template',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.template_id = Template.id',
					),
				),
				array('table' => 'onlineapp_cobrands',
					'alias' => 'Cobrand',
					'type' => 'LEFT',
					'conditions' => array(
						"Template.cobrand_id = Cobrand.id",
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
				array('table' => 'merchants',
					'alias' => 'Merchant',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = Merchant.cobranded_application_id',
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
 * Function to determine whether a given Application should be displayed
 * to a non logged in user.
 * This function will take a single argument, the uuid for the application
 * in question.  If the Application has not been modified in the last 30 days
 * or if it has been signed it will be considered expired and visible only to
 * logged in users.
 *
 * @param string $uuid cobranded application uuid
 * @return bool
 */

	public function isExpired($uuid) {
		$application = $this->find('first',
			array(
				'conditions' => array(
					$this->alias . '.uuid' => $uuid
				),
				'fields' => array(
					'CobrandedApplication.status',
					'CobrandedApplication.modified'
				),
				'recursive' => -1
			)
		);

		//Use date without time
		$modDate = CakeTime::format(Hash::get($application, 'CobrandedApplication.modified'), '%Y-%m-%d');
		if (!empty(Hash::get($application, 'CobrandedApplication')) && (CakeTime::wasWithinLast('30 days', $modDate)) && $application['CobrandedApplication']['status'] !== 'signed') {

			return false;
		}
		return true;
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
					'Owner1Email.value ILIKE' => '%' . $filter . '%',
					'Owner1Email.value ILIKE' => '%' . $filter . '%',
					'EMail.value ILIKE' => '%' . $filter . '%',
					'LocEMail.value ILIKE' => '%' . $filter . '%',
					"{$this->alias}.uuid" => $filter,
					'CAST(' . $this->alias . '.id AS TEXT) ILIKE' => '%' . $filter . '%',
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
	public function beforeFind($query) {
		parent::beforeFind($query);
		if (empty($query['order']['0']) && isset($query['sort'])) {
			$query['order']['0'] = array($query['sort'] => $query['direction']);
		} return $query;
	}

/**
 * __addKey
 *
 * @params
 * @param string $keys string to keys to concatenate to
 * @param string $newKey new key to concatenate
 * @param boolean $enquote whether add double quotation marks around the new key 
 */
	private function __addKey($keys, $newKey, $enquote = true) {
		if ($enquote) {
			return $keys . ',"' . $newKey . '"';
		}
		return $keys . '|' . $newKey;
	}

/**
 * __addValue
 *
 * @params
 * @param string $values string to values to concatenate to
 * @param string $newValue new value to concatenate
 * @param boolean $enquote whether add double quotation marks around the new value
 */
	private function __addValue($values, $newValue, $enquote = true) {
		if ($enquote) {
			return $values . ',"' . trim($newValue) . '"';
		}
		return $values . '|' . trim($newValue);
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

/**
 * setDataToSync
 * Saves serialized data with which an application needs to be synced.
 * This method updates all aplications that use the template field that was modified in some way.
 *
 * @param array $data containing a single TemplateField record which was modified/deleted/added.
 * @return boolean
 * @throws InvalidArgumentException
 */
	public function setDataToSync($data) {
		if (!array_key_exists('TemplateField', $data) || empty($data['TemplateField']['section_id'])) {
			throw new InvalidArgumentException(__("Expected TemplateField data is missing array argument."));
		}

		$templateId = $this->_getAssociatedTemplateId($data);

		//If no template id was found then no apps were ever using it and/or the template was deleted along with its fields
		if (empty($templateId)) {
			return true;
		}

		//Get all applications (up to 3 months back) that use this template with their serialized data_to_sync string 
		$settings = array(
			'fields' => array('CobrandedApplication.id', 'CobrandedApplication.data_to_sync', 'CobrandedApplication.modified'),
			'conditions' => array(
				"CobrandedApplication.modified > '" .  date("Y-m-d" , strtotime("-3 months")) .  "'",
				'CobrandedApplication.status NOT IN' => array(self::STATUS_SIGNED, self::STATUS_COMPLETED)
			),
			'order' => 'CobrandedApplication.modified DESC'
		);

		$cbApps = $this->getByTemplateId($templateId, $settings);
		if (empty($cbApps)) {
			return true; //nothing out-of-sync
		}
		//Only want TemplateField data to be saved
		$data['TemplateField'] = $data['TemplateField'];

		//Iterate throug each app
		foreach ($cbApps as $cpAppDat) {
				$dataToSync = unserialize($cpAppDat['CobrandedApplication']['data_to_sync']); //decode as array
				$id = $cpAppDat['CobrandedApplication']['id'];
				//Keep original modified date, Sync operations shold not change it
				$modDate = $cpAppDat['CobrandedApplication']['modified'];
			if (empty($dataToSync)) {
				//Encode TemplateField Data structure as {n}.TemplateField.{field}.{val}
				$dataToSync = serialize(array($data));
				$updated[] = array('id' => $id, 'data_to_sync' => $dataToSync, 'modified' => $modDate);
			} else {
				//Iterate through and find existing data to sync in order to find a match and update it
				$matchFound = false;
				//We expect existing to-be-synced TemplateField Data structure to be {n}.TemplateField.{field}.{val}
				foreach ($dataToSync as $idx => $oldDat) {
					//Find a match and exit loop
					if ($oldDat['TemplateField']['id'] === $data['TemplateField']['id']) {
						$matchFound = true;
						break;
					}
				}
				if ($matchFound) {
					//Use index at which the match was found to update data
					$dataToSync[$idx]['TemplateField'] = $data['TemplateField'];
				} else {
					//Insert new data
					$dataToSync[]['TemplateField'] = $data['TemplateField'];
				}
				$dataToSync = serialize($dataToSync);
				$updated[] = array('id' => $id, 'data_to_sync' => $dataToSync, 'modified' => $modDate);
			}
		}

		//Updating existing prevalidated data no need to validate here
		return $this->saveMany($updated, array('validate' => false));
	}

/**
 * _getAssociatedTemplateId
 * Finds template id associated with the TemplateField passed in the first param.
 *
 * @param array $templateFieldData A singe TemplateField record
 * @param array $settings query settings
 * @visibility protected
 * @return mixed string|null the template id if found
 */
	protected function _getAssociatedTemplateId($templateFieldData) {
		if (!array_key_exists('TemplateField', $templateFieldData) || empty($templateFieldData['TemplateField']['section_id'])) {
			throw new InvalidArgumentException(__("Expected TemplateField data is missing array argument."));
		}
		//We don't know whether data from associated Template/TemplatePage/TemplateSection models might have been deleted
		//so can't use those models to find Template associated to the TemplateField.
		//So first attempt to find a sample of a single CobrandedApplicationValue record that uses this field.
		$sampleAppTemplate = $this->CobrandedApplicationValues->find('first', array(
				'recursive' => -1,
				'contain' => array('CobrandedApplication'),
				'fields' => array('CobrandedApplication.template_id'), //we only care about this piece of data
				'conditions' => array(
						'CobrandedApplicationValues.template_field_id' => Hash::get($templateFieldData, 'TemplateField.id')
					),
			));

		$templateId = Hash::get($sampleAppTemplate, 'CobrandedApplication.template_id');

		//If not found then it could be a new field that no CobrandedApplicationValues is using yet
		if (empty($templateId)) {
			//Attempt to find Template id directly through the TemplateField class
			$templateData = ClassRegistry::init('TemplateField')->getTemplate($templateFieldData['TemplateField']['section_id']);
			$templateId = Hash::get($templateData, 'id');
		}

		return $templateId;
	}
/**
 * getByTemplateId
 * Finds a list of applications by template_id
 *
 * @param integer $templateId An associated Template.id
 * @param array $settings query settings
 * @return array
 */
	public function getByTemplateId($templateId, $settings) {
		$default = array(
				'contain' => false,
			);
		$settings = array_merge($default, $settings);
		$settings['conditions']['CobrandedApplication.template_id'] = $templateId;
		return $this->find('all', $settings);
	}

/**
 * sycApp
 * Uses datasource transactions
 * Synchronizes Application with all the models that they are out of sync.
 * Synchronizable Associated models are Template, TemplatePages, TemplateSection and TemplateFields
 *
 * @param integer $id $this->id
 * @return mixed | boolean false on falure otherwise an array with changes that were made to the TemplateField
 */
	public function syncApp($id) {
		$appData = $this->find('first', array(
				'recursive' => -1,
				'fields' => array('CobrandedApplication.id', 'CobrandedApplication.data_to_sync', 'CobrandedApplication.modified'),
				'conditions' => array('CobrandedApplication.id' => $id),
			));
		$dataToSync = unserialize($appData['CobrandedApplication']['data_to_sync']);
		$updatedValues = array();
		$outDatedIds = array();
		$dataSource = $this->getDataSource();
		//Begin transaction
		$dataSource->begin();
		foreach ($dataToSync as $modedField) {
			$TemplateField = ClassRegistry::init('TemplateField');
			//get App values that are using this field
			$outOfSyncVals = $this->CobrandedApplicationValues->find('all', array(
						'recursive' => -1,
						'fields' => array(
							'CobrandedApplicationValues.id',
							'CobrandedApplicationValues.cobranded_application_id',
							'CobrandedApplicationValues.template_field_id',
							'CobrandedApplicationValues.name',
							'CobrandedApplicationValues.value',
						),
						'conditions' => array(
							'CobrandedApplicationValues.cobranded_application_id' => $id,
							'CobrandedApplicationValues.template_field_id' => $modedField['TemplateField']['id']
						)
					)
				);
			//TemplateFields and CobrandedApplicationValue Model association is dependent,
			$sycronized = $this->syncAppValues($appData['CobrandedApplication']['id'], $outOfSyncVals, $modedField);

			if ($sycronized === false) {
				return false;
			} else {
				$updatedValues = array_merge($updatedValues, $sycronized);
				$outDatedIds = array_merge($outDatedIds, Hash::extract($outOfSyncVals, '{n}.CobrandedApplicationValues.id'));
			}
		}
		//Keep original modified date, Sync operations shold not change it
		$modDate = $appData['CobrandedApplication']['modified'];
		$this->create();
		$syncedDat = array('id' => $id, 'data_to_sync' => null, 'modified' => $modDate);

		//any outdated data to delete?
		if (!empty($outDatedIds) && $this->CobrandedApplicationValues->deleteAll(array('CobrandedApplicationValues.id IN' => $outDatedIds)) === false) {
			$dataSource->rollback();
			return false;
		}

			//save updated data
		if ($this->CobrandedApplicationValues->saveAll($updatedValues) === false ||

			//Finally clear out data_to_sync
			$this->save($syncedDat, array('validate' => false)) === false) {

			//If any operation fails rollback
			$dataSource->rollback();
			return false;
		}
		$dataSource->commit();
		//Get data to sync
		return true;
	}

/**
 * syncAppValues
 * Synchronizes CobrabdedApplicationValues (CAVs) and corresponding TemplateFields
 * Multiple CAVs will be created for multi-choice fields defined in TemplateFields.default_value
 * CAVs will be removed if multi-choice definitions within TemplateFields.default_value are removed
 * CAV.value if not set will be set with any default value(s) defined in TemplateFields.default_value
 *
 * @param int $appId CobrandedApplication.id
 * @param array $outOfSyncData this is the counterpart of the $modedTemplateField param which will be synced and added to param 1.
 *				If this param is empty new CobrabdedApplicationValues will be created for each corresponding TemplateField.
 * @param array $templateField this is the latest TemplateFieldData which will be used to sync CAVs in param 2
 * @return mixed boolean|array Will return false when the TemplateField.type is unknown and therefore sync cannot be handled
 *						or array with data already in sync with corresponding modified TemplateFields data.
 */
	public function syncAppValues($appId, $outOfSyncData, $templateField) {
		$type = $templateField['TemplateField']['type'];
		$synced = array();

		if ($type == 4 || $type == 5 || $type == 7) { //radio | percents | fees - all multi-choice (',' delimited) and multi-CAV types
			//default_value contains delimited string of otions with structure <key>::<value>{[default value]||default}
			$choices = explode(',', $templateField['TemplateField']['default_value']);
			foreach ($choices as $keyValStr) {
				$key = Hash::get(explode('::', $keyValStr), '0');
				$val = Hash::get(explode('::', $keyValStr), '1');
				$val = preg_replace('/\{.*\}$/', '', $val);//remove default option value from $val
				$default = null;

				//Type 4 radio's default if present indicates that the radio input should be active
				if ($type == 4 && preg_match('/\{default\}/i', $keyValStr)) {
					$default = 'true';
				} else {
					//For all others the default is potentially present in $keyValStr
					preg_match('/\{(.+)\}/', $keyValStr, $matches); //$matches array will be filled with 2 entries
					$default = Hash::get($matches, '1'); //we want the second match or null
				}

				//If there are no old values we need to create new ones
				if (empty($outOfSyncData)) {
					$synced[] = $this->_setNewCoAppVal($appId, $templateField, $key, $val, $default);
				} else {
					//Attempt to find unchaged data and keep it
					$matchFound = false;
					//Use Hash::extract to shrink $outOfSyncData on the fly without mesing up contiguity iteration
					foreach (Hash::extract($outOfSyncData, '{n}') as $idx => $cavData) {
						if (($cavData['CobrandedApplicationValues']['name'] === $templateField['TemplateField']['merge_field_name'] . $val) ||
							($cavData['CobrandedApplicationValues']['name'] === $val)) {
							//Set corresponding default CAV.value IFF is blank for existing data. (possibly the mergefield default value was changed that was made)
							if (isset($default) && (is_null($cavData['CobrandedApplicationValues']['value']) || $cavData['CobrandedApplicationValues']['value'] === '')) {
								$cavData['CobrandedApplicationValues']['value'] = $default;
							}
							//Add to collection of synced fields
							$synced[] = $cavData['CobrandedApplicationValues'];

							//remove processed CAV
							unset($outOfSyncData[$idx]);
							$matchFound = true;
							break;//one to one field rel no need to continue loop after match found
						}
					}
					//Create new CAV if there wasn't an existing one matching
					if (!$matchFound) {
						$synced[] = $this->_setNewCoAppVal($appId, $templateField, $key, $val, $default);
					}
				}
			}
			return $synced;
		} else {
			$default = null;
			//extract any default value
			if ($type == 20) { //select - special case is multi-choice but not multi-CAV
				foreach (explode(',', $templateField['TemplateField']['default_value']) as $keyValStr) {
					if (preg_match('/\{default\}/i', $keyValStr)) {
						$default = Hash::get(explode('::', $keyValStr), '1'); //we want the option value set as the default
						$default = preg_replace('/\{default\}$/', '', $default);//remove '{default}'' from default
						break;
					}
				}
			} else {
				$default = $templateField['TemplateField']['default_value'];
			}
			if (empty($outOfSyncData)) {
					$synced[] = $this->_setNewCoAppVal($appId, $templateField, '', '', $default);
			} else {
				$cavData = Hash::get($outOfSyncData, '0.CobrandedApplicationValues');
				$cavData['name'] = $templateField['TemplateField']['merge_field_name'];
				if (isset($default)) {
					//If the CAV does not validate, then it means that the template field option value that is supposed to match
					//the corresponding current CAV has been removed or changed, therefore set it to default
					if (empty($cavData['value']) || ($type == 20 && $this->CobrandedApplicationValues->validApplicationValue($cavData, $type, $templateField['TemplateField']) === false)) {
						$cavData['value'] = $default;
					}
				}
				$synced[] = $cavData;
			}
			return $synced;
		}

		//for all other unknown field types return false
		return false;
	}

/**
 * _setNewCoAppVal
 * Sets the cobranded application field name and value in the proper format depending on field type.
 *
 * @param string $appId the cobranded application id that uses this merge field
 * @param array $templateField the data of the template field
 * @param string $mergeFieldKey the mergefield key originally defined as <key>::<value>{[default value]||default}
 * @param mixed $mergeFieldVal the mergefield value originally defined as <key>::<value>{[default value]||default}
 * @param mixed $mergeFieldDefaultVal the mergefield default value originally defined as <key>::<value>{[default value]||default}
 * @return array
 */
	protected function _setNewCoAppVal($appId, $templateField, $mergeFieldKey, $mergeFieldVal, $mergeFieldDefaultVal = null) {
		$newCAV = array(
			'cobranded_application_id' => $appId,
			'template_field_id' => $templateField['TemplateField']['id'],
		);

		if ($templateField['TemplateField']['type'] == 4 || $templateField['TemplateField']['type'] == 5) {
			$newCAV['name'] = $templateField['TemplateField']['merge_field_name'] . $mergeFieldVal;
		} elseif ($templateField['TemplateField']['type'] == 7) {
			$newCAV['name'] = $mergeFieldVal;
		} else {
			$newCAV['name'] = $templateField['TemplateField']['merge_field_name'];
		}
		$newCAV['value'] = $mergeFieldDefaultVal;

		//Add to collection of synced fields
		return $newCAV;
	}

/**
 * getSfClientNameByClientId
 * Makes GET request to find a SalesForce account using provided Client ID.
 * Returns an array cotaining Client ID and client name.
 * In salesforce the Client ID/Name comes from the overarching parent company not from it's children locations.
 * Children accounts all have a reference to the parent Client ID/Name in SF
 * 
 * @param string $clientId an 8 digit client id numbers or string representation of numbers
 * @return mixed array | boolean false when nothing is found or array containing matching result
 * @throws Exception
 */
	public function getSfClientNameByClientId($clientId) {
		if (!empty($clientId)) {
			$SalesForce = new SalesForce();
			if ($SalesForce->api != false) {
				$sfObjAcctName = $SalesForce->fieldNames[SalesForce::GLOBAL_CLIENT_ID]['sobject'];
				$params = "/?q=$clientId&";
				$params .= "sobject=$sfObjAcctName&";
				$params .= "$sfObjAcctName.where=" . SalesForce::GLOBAL_CLIENT_ID . "='$clientId'&";
				$params .= "$sfObjAcctName.fields=" . SalesForce::GLOBAL_CLIENT_ID . "," . SalesForce::GLOBAL_CLIENT_NAME ."&";
				$params .= "$sfObjAcctName.limit=1";
				$url = $SalesForce->host . SalesForce::API_PATH . "/parameterizedSearch";
				$url .= $params;
				
				$response = $SalesForce->api->get($url);
				if ($response->isOk()) {
					$responseBody = json_decode($response->body, true);
					$result = Hash::get($responseBody, 'searchRecords.0', false);
					return $result;
				} else {
					throw new Exception("Salesforce API ERROR:Response Body: " . $response->body);
					return;
				}
			} else {
				throw new Exception("Salesforce API connection configuration not found! Cannot connect to salesforce!");
			}
		} else {
			return false;
		}
	}

}