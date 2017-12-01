<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplate Model
 *
 */
class Template extends AppModel {

	public $logoPositionTypes = array('left', 'center', 'right', 'hide');

	public $displayField = 'name';

	public $actsAs = array(
		'Search.Searchable',
		'Containable',
	);

	public $validate = array(
		'name' => array(
			'rule' => array('notBlank'),
			'message' => array('Template name cannot be empty'),
		),
		'cobrand_id' => array(
			'rule' => array('numeric'),
			'message' => array('Invalid cobrand_id value used'),
		),
		'logo_position' => array(
			'rule' => array('notBlank'),
			'message' => array('Logo position value not selected'),
		),
	);

	public $hasMany = array(
		'Users' => array(
			'className' => 'User',
			'foreignKey' => 'template_id',
			'dependent' => false,
		),
		'Users' => array(
			'className' => 'User',
			'foreignKey' => 'template_id',
			'dependent' => false,
		),
		'UsersTemplate' => array(
			'className' => 'UsersTemplate',
			'foreignKey' => 'template_id',
			'dependent' => true,
		),
		'TemplatePages' => array(
			'className' => 'TemplatePage',
			'foreignKey' => 'template_id',
			'order' => 'TemplatePages.order',
			'dependent' => true,
		),
	);

	public $belongsTo = array(
		'Cobrand' => array(
			'className' => 'Cobrand',
			'foreignKey' => 'cobrand_id'
		)
	);

	public function getList($cobrandId = null) {
		$conditions = array();

		if ($cobrandId != null) {
			$conditions['conditions'] = array('Template.cobrand_id' => $cobrandId);
		}

		$templates = $this->getTemplatesAndCobrands($conditions);
		return $this->setCobrandsTemplatesList($templates);
	}
/**
 * getTemplatesAndCobrandsById
 *
 * @param array $options containing search query options
 * @return array
 */
	public function setCobrandsTemplatesList($tmpltsCobrands) {
		return Hash::combine($tmpltsCobrands, '{n}.Template.id', array('%2$s - %1$s', '{n}.Template.name', '{n}.Cobrand.partner_name'));
	}
/**
 * getTemplatesAndCobrandsById
 *
 * @param array $options containing search query options
 * @return array
 */
	public function getTemplatesAndCobrands($options) {
		$dafaultOptns = array(
				'contain' => array('Cobrand.partner_name'),
				'fields' => array('Template.id', 'Template.name'),
				'order' => array('Cobrand.partner_name' => 'ASC'),
			);

		$options = array_merge($dafaultOptns, $options);
		return $this->find('all', $options);
	}

	public function getCobrand($cobrandId) {
		return $this->Cobrand->getById($cobrandId);
	}

	public function getTemplateApiFields($templateId) {
		return $this->getTemplateFields($templateId, 0, false);
	}

	// to return all fields call VVV with only the templateId
	public function getTemplateFields($templateId, $fieldSource = null, $repOnly = null, $required = null) {
		// build the conditions array
		$conditions = array(
			'field.section_id = section.id',
		);

		$fields = array(
			'field.merge_field_name',
			'field.type',
			'field.required',
			'field.name',
			'field.description',
			'field.source',
			'field.rep_only',
			'field.default_value'
		);

		if (!is_null($fieldSource)) {
			// source 0 = api, source 2 = api/user
			// in either of these cases, we want both 0 and 2 fields
			if ($fieldSource == 0 || $fieldSource == 2) {
				$conditions['field.source'] = array(0, 2);
			} else {
				$conditions['field.source'] = $fieldSource;
			}
		}

		if (!is_null($repOnly)) {
			$conditions['field.rep_only'] = $repOnly;
		}

		if (!is_null($required)) {
			$conditions['field.required'] = $required;
		}

		$fields = $this->find('all',
			array(
				'joins' => array(
					array(
						'table' => 'onlineapp_template_pages',
						'alias' => 'page',
						'foreignKey' => 'onlineapp_template_pages.template_id',
						'conditions' => array(
							'page.template_id = Template.id'
						),
					),
					array(
						'table' => 'onlineapp_template_sections',
						'alias' => 'section',
						'foreignKey' => 'onlineapp_template_sections.page_id',
						'conditions' => array(
							'section.page_id = page.id'
						),
					),
					array(
						'table' => 'onlineapp_template_fields',
						'alias' => 'field',
						'foreignKey' => 'onlineapp_template_fields.section_id',
						'conditions' => $conditions
					),
				),
				'fields' => $fields,
				'conditions' => array(
					'"Template".id' => $templateId
				),
				'order' => array('field.id'),
			)
		);

		$formattedData = array();
		$TemplateField = ClassRegistry::init('TemplateField');
		foreach ($fields as $key => $value) {
			$type = 'unknown';
			if (key_exists($value['field']['type'], $TemplateField->fieldTypes)) {
				$type = $TemplateField->fieldTypes[$value['field']['type']];
			}

			// types with multiple values/options are handled differently
			switch ($type) {
				case 'radio': // 'radio':
				case 'percents': // 'percents':
				case 'fees': // 'fees':
					// split default_value on ',' and append split[1] to the merge_field_name
					foreach (explode(',', $value['field']['default_value']) as $keyValuePairStr) {
						$keyValuePair = explode('::', $keyValuePairStr);
						$name = $value['field']['merge_field_name'] . $keyValuePair[1];
						$formattedData[$name] = array(
								"type" => $type,
								"required" => $value['field']['required'],
								"description" => $value['field']['description'],
						);
					}
					break;
				case 'multirecord':
					if (!empty($value['field']['default_value'])) {
						$defaultValue = $value['field']['default_value'];
						$Model = ClassRegistry::init($defaultValue);
						$schema = $Model->fields;
						$formattedData[$value['field']['merge_field_name']] = array(
							$schema
						);
					}
					break;
				default:
					$formattedData[$value['field']['merge_field_name']] = array(
						"type" => $type,
						"required" => $value['field']['required'],
						"description" => $value['field']['description'],
					);
					break;
			}
		}

		return $formattedData;
	}

	private function __buildMergeFieldName($pageName, $sectionName, $fieldName) {
		return CakeText::insert(
			":pageName_:sectionName_:fieldName",
			array(
				'pageName' => $this->__getFLOEW($pageName),
				'sectionName' => $this->__getFLOEW($sectionName),
				'fieldName' => $this->__stripString($fieldName, true),
			)
		);
	}

	private function __getFLOEW($words) {
		// get the "first letter of each word" ==>> FLOEW
		// replace & and # with ''
		$cleanWords = $this->__stripString($words);
		$wordsArray = explode(' ', $cleanWords);
		$FLOEW = '';
		foreach ($wordsArray as $word) {
			$FLOEW = $FLOEW . substr($word, 0, 1);
		}
		return $FLOEW;
	}

	private function __stripString($str, $removeSpaces = false) {
		$badChars = array('&', '#', '(', ')', '?');
		if ($removeSpaces) {
			$badChars[count($badChars)] = ' ';
		}
		return str_replace($badChars, '', $str);
	}

/**
 * isRemovable
 * Checks if a template is already associated with a CobrandedApplication.
 * If it is then template can not be deleted
 *
 * @param integer $id template id
 * @return boolean
 */
	public function removable($id) {
		return (!ClassRegistry::init('CobrandedApplication')->hasAny(array('template_id' => $id)));
	}
}
