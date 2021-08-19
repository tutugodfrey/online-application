<?php
App::uses('AppModel', 'Model');
App::uses('Validation', 'Utility');

/**
 * CobrandedApplicationValue Model
 *
 * @property CobrandedApplication $CobrandedApplication
 */
class CobrandedApplicationValue extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	public $actsAs = array(
		'Search.Searchable',
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'cobranded_application_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Numeric value expected',
				//'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'template_field_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Numeric value expected',
				//'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Name is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'CobrandedApplication' => array(
			'className' => 'CobrandedApplication',
			'foreignKey' => 'cobranded_application_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'TemplateField' => array(
			'className' => 'TemplateField',
			'foreignKey' => 'template_field_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function beforeSave($options = array()) {
		$retVal = true;

		// need to be able to clear values in the db
		if (empty($this->data[$this->alias]['value'])) {
			return true;
		}

		// only validate in the update case, ignore during create; null will not be valid in all cases
		if (key_exists('id', $this->data[$this->alias])) {
			// look up the value's template field
			$field = $this->TemplateField->find(
				'first',
				array(
					'conditions' => array(
						'TemplateField.id' => $this->data[$this->alias]['template_field_id']
					),
					'recursive' => -1
				)
			);

			// if WebAddress field is not empty, check if protocol exists
			// if it doesn't, add it in
			if ($field['TemplateField']['merge_field_name'] == 'WebAddress') {
				if (!empty($this->data[$this->alias]['value'])) {
					if (!preg_match('/^http:\/\//i', $this->data[$this->alias]['value'])) {
						$this->data[$this->alias]['value'] = 'https://'.$this->data[$this->alias]['value'];
					}
				}
			}

			// if field is set to encrypt, check for masking
			// if it's masked, do not update value, otherwise value in db will be masked
			if ($field['TemplateField']['encrypt'] && preg_match('/^X+/', $this->data[$this->alias]['value'])) {
				return false;
			}

			// if field type is money, remove commas and dollar signs if they exist
			if ($field['TemplateField']['type'] == 10) {
				$data = $this->data[$this->alias]['value'];
				$data = str_replace(',', '', $data);
				$data = str_replace('$', '', $data);
				if (gettype($data) === 'string' && is_numeric($data)) {
					$data = floatval($data);
				}
				$this->data[$this->alias]['value'] = $data;
			}

			$retVal = $this->validApplicationValue($this->data[$this->alias], $field['TemplateField']['type'], $field['TemplateField']);

			// check if field is set to encrypt
			// if it is, encrypt and store data
			if ($retVal && $field['TemplateField']['encrypt']) {
				$data = $this->data[$this->alias]['value'];
				if (!empty($data)) {
					$this->data[$this->alias]['value'] = $this->encrypt($data, Configure::read('Security.OpenSSL.key'));
				}
			}

		}
		return $retVal;
	}

	public function save($data = null, $validate = true, $fieldList = array()) {
		// clear modified field value before each save
		$this->set($data);
		if (isset($this->data[$this->alias]['modified'])) {
			unset($this->data[$this->alias]['modified']);
		}

		$dboSource = $this->CobrandedApplication->getDataSource();

		$this->CobrandedApplication->id = $this->data[$this->alias]['cobranded_application_id'];
		$this->CobrandedApplication->saveField('modified', $dboSource->expression('LOCALTIMESTAMP(0)'));

		return parent::save($this->data, $validate, $fieldList);
	}

	public function validApplicationValue($data, $fieldType, $templateField = null) {
		$retVal = false;
		$trimmedDataValue = trim($data['value']);

		switch ($fieldType) {
			case 0:  // text      		- no validation
			case 3:  // checkbox  		- no validation
			case 4:  // radio    	 	- no validation
			case 6:  // label    	 	- no validation
			case 7:  // fees     	 	- (group of money?)
			case 8:  // hr       	 	- no validation
			case 21: // textArea		- no validation
			case 22: // multirecord		- no validation here, will happen in the multirecord Model
				// always valid
				$retVal = true;
				break;

			case 1:  //  1 - yyyy[/-.]mm[/-.]dd (dateISO)
				$retVal = Validation::date($trimmedDataValue);
				break;

			case 2:  // time      - hh:mm:ss [a|p]m
				$retVal = Validation::time($trimmedDataValue);
				break;

			case 5:  // percents  - between [0-100]
			case 11: // percent   - (0-100)%
				if (is_numeric($trimmedDataValue)) {
					$newValue = intval($trimmedDataValue);
					if ($newValue >= 0 && $newValue <= 100) {
						$retVal = true;
					}
				}
				break;

			case 9:  // phoneUS   - (###) ###-####
				$retVal = Validation::phone($trimmedDataValue);
				break;

			case 10: // money     - $(#(1-3),)?(#(1-3)).### << needs work
				//Validation fails if the value is a string representation of a floating point number
				if (gettype($trimmedDataValue) === 'string' && is_numeric($trimmedDataValue)) {
					$trimmedDataValue = floatval($data);
				}
				$retVal = Validation::money($trimmedDataValue);
				break;

			case 12: // ssn       - ###-##-####
				if (preg_match('/^\d{3}-?\d{2}-?\d{4}$/', $trimmedDataValue)) {
					$retVal = true;
				}
				break;

			case 13: // zipcodeUS - #####[-####]
				$retVal = Validation::postal($trimmedDataValue);
				break;

			case 14: // email     - name@domian.com
				$retVal = Validation::email($trimmedDataValue);

				break;

			//case 15: // lengthoftime - [#+] [year|month|day]s
			//case 16: // creditcard -

			case 17: // url       - http(s)?://domain.com
				$retVal = Validation::url($trimmedDataValue);
				break;

			case 18: // number    - (#)+.(#)+
				$retVal = Validation::numeric($trimmedDataValue);
				break;

			case 19: // digits    - (#)+
				$retVal = (preg_match("/\d+/", $trimmedDataValue) > 0);
				break;

			case 20: // select - one of the options should be selected
				if (!empty($templateField['default_value'])) {
					foreach (explode(',', $templateField['default_value']) as $keyValuePairStr) {
						$keyValuePair = explode('::', $keyValuePairStr);
						if ($trimmedDataValue == $keyValuePair[0] || $trimmedDataValue == $keyValuePair[1]) {
							$retVal = true;
							break;
						}
					}
				}

				break;

			case 23: // luhn - luhn validation
				$retVal = $this->checkRoutingNumber($trimmedDataValue);
				break;

			default:
				throw new Exception("Unknown field type, cannot validate it.", 1);
				break;
		}

		return $retVal;
	}

/**
 * afterFind
 *
 * @params
 *     $results array
 *     $primary boolean
 */
	public function afterFind($results, $primary = false) {
		parent::afterFind($results, $primary);
		$session = new CakeSession();

		if (!empty($results) && is_array($results)) {
			foreach ($results as $resultKey => $resultValue) {

				if (key_exists('CobrandedApplicationValues', $resultValue)) {
					$resultValue['CobrandedApplicationValue'] = $resultValue['CobrandedApplicationValues'];
				}

				if (!empty($resultValue['CobrandedApplicationValue']) && is_array($resultValue['CobrandedApplicationValue'])) {
					if (key_exists('value', $resultValue['CobrandedApplicationValue'])
						&& $resultValue['CobrandedApplicationValue']['value'] !== ''
						&& $resultValue['CobrandedApplicationValue']['value'] !== null) {

						$templateField = $this->TemplateField->find(
							'first',
							array(
								'conditions' => array(
									'TemplateField.id' => $resultValue['CobrandedApplicationValue']['template_field_id']
								),
								'recursive' => -1
							)
						);

						// only decrypt fields set to encrypt
						if ($templateField['TemplateField']['encrypt']) {
							$data = $resultValue['CobrandedApplicationValue']['value'];
							$data = (!empty($data))? $this->decrypt($data, Configure::read('Security.OpenSSL.key')) : $data;

							if (!in_array($session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
								$maskValue = true;

								$e = new Exception;
								$stackTrace = $e->getTraceAsString();

								if (strpos($stackTrace, 'createRightSignatureApplicationJSON') !== false ||
									strpos($stackTrace, 'getValuesByAppId') !== false ||
									strpos($stackTrace, 'CobrandedApplication->buildExportData') !== false ||
									strpos($stackTrace, 'CobrandedApplicationsController->create_rightsignature_document') !== false ||
									strpos($stackTrace, 'CobrandedApplicationsController->api_add()') !== false) {
									$maskValue = false;
								}

								if ($maskValue) {
									// mask all but last 4 values
									$dataArray = str_split($data);
									$dataLength = count($dataArray);
									$data = '';
									for ($x = 0; $x < $dataLength; $x++) {
										if ($x < ($dataLength - 4)) {
											$data .= 'X';
										}
										else {
											$data .= $dataArray[$x];
										}
									}
								}
							}

							$results[$resultKey]['CobrandedApplicationValue']['value'] = $data;
							$results[$resultKey]['CobrandedApplicationValues']['value'] = $data;
						}
					}
				}
			}
		}

		return $results;
	}

	/**
	 * Custom Validation Rule
	 * Checks to see if the routing number entered passes Mod 10 (LUHN)
	 * @param numeric $routingNumber
	 * @return boolean
	 */
	public function checkRoutingNumber($routingNumber = 0) {
		$routingNumber = preg_replace('[\D]', '', $routingNumber); //only digits

		if (strlen($routingNumber) != 9) {
			return false;
		}

		$checkSum = 0;
		for ($i = 0, $j = strlen($routingNumber); $i < $j; $i+= 3) {
			//loop through routingNumber character by character
			$checkSum += ($routingNumber[$i] * 3);
			$checkSum += ($routingNumber[$i + 1] * 7);
			$checkSum += ($routingNumber[$i + 2]);
		}

		if ($checkSum != 0 and ($checkSum % 10) == 0) {
			return true;
		} else {
			return false;
		}
	}

/**
 * getValuesByAppId
 *
 * @param integer $appId A CobrandedApplication.id
 * @param array $settings Settings for search query
 * @return $valuesMap array
 */
	public function getValuesByAppId($appId, $settings = array()) {
		$default = array(
			'contain' => false
		);
		$settings = array_merge($default, $settings);
		$settings['conditions']['cobranded_application_id'] = $appId;
		$appValues = $this->find('all', $settings);
		//Pop all up by one dim
		$appValues = Hash::extract($appValues, '{n}.CobrandedApplicationValues');

		$valuesMap = $this->CobrandedApplication->buildCobrandedApplicationValuesMap($appValues);
		return $valuesMap;
	}
}
