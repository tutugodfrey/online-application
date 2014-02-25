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

	public $useTable = 'onlineapp_cobranded_application_values';

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
			'notempty' => array(
				'rule' => array('notempty'),
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

	public function beforeSave() {
		// only validate in the update case, ignore durring create; null will not be valid in all cases
		if (key_exists('id', $this->data[$this->alias])) {
			// look up the value's template field
			$field = $this->TemplateField->find(
				'first',
				array(
					'conditions' => array(
						'TemplateField.id' => $this->data[$this->alias]['template_field_id']
					)
				)
			);
			return $this->__validApplicationValue($this->data[$this->alias], $field['TemplateField']);
		}
		return true;
	}

	private function __validApplicationValue($data, $field) {
		$retVal = false;
		$trimmedDataValue = trim($data['value']);

		switch ($field['type']) {
			case 0:  // text      - no validation
			case 3:  // checkbox  - no validation
			case 4:  // radio     - no validation
			case 6:  // label     - no validation
			case 7:  // fees      - (group of money?)
			case 8:  // hr        - no validation
			case 20: // select    - no validation
			case 21: // textArea  - no validation
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

			case 10: // money     - $(#(1-3),)?(#(1-3)).## << needs work
				$retVal = Validation::money($trimmedDataValue);
				break;

			case 12: // ssn       - ###-##-####
				$retVal = Validation::ssn($trimmedDataValue, null, 'us');
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
				$retVal = preg_match("/\d+/", $trimmedDataValue);
				break;

			default:
				throw new Exception("Unknown field type, cannot validate it.", 1);
				break;
		}

		return $retVal;
	}
}
