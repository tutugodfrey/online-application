<?php
App::uses('AppModel', 'Model');
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
		/*
		'id' => array(),
		*/
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
		/*
		'created' => array(),
		'modified' => array(),
		*/
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
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
			'className' => 'OnlineappCobrandedApplicationValue',
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
								$this->__addApplicationValue(
									array(
										'cobranded_application_id' => $applicationId,
										'template_field_id' => $field['id'],
										'name' => $field['merge_field_name'],
									)
								);
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
			// try to update it
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
							}
						}
					}
				}
			} else {
				// something went wrong
				$response = Hash::insert(
					$response,
					'msg',
					'failed to update application value with id ['.$data['id'].']. Check the sql log for more details'
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

	private function __addApplicationValue($applicationValueData) {
		// save this info
		$this->CobrandedApplicationValues->create();
		$this->CobrandedApplicationValues->save($applicationValueData);
	}

}
