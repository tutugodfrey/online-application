<?php
App::uses('AppModel', 'Model');
App::uses('TemplateField', 'Model');

/**
 * CobrandedApplication Model
 *
 * @property CobrandedApplicationValues $CobrandedApplicationValues
 */
class CobrandedApplication extends AppModel {

	public $useTable = 'onlineapp_cobranded_applications';

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

	public function afterSave($created/*, $options*/) {
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

	public function getApplicationValue($valueId) {
		if (is_null($this->CobrandedApplicationValue)) {
			$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');
		}
		return $this->CobrandedApplicationValue->findById($valueId);
	}

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

	private function __addKey($keys, $newKey) {
		return $keys.',"'.$newKey.'"';
	}

	private function __addValue($values, $newValue) {
		return $values.',"'.trim($newValue).'"';
	}

	private function __addApplicationValue($applicationValueData) {
		// save this info
		$this->CobrandedApplicationValues->create();
		$this->CobrandedApplicationValues->save($applicationValueData);
	}

	private function __startsWith($haystack, $needle) {
		return $needle === "" || strpos($haystack, $needle) === 0;
	}

}
