<?php

App::uses('Helper', 'View');
App::uses('TemplateField', 'Model');

class TemplateFieldHelper extends Helper {

	public $helpers = array('Form', 'Html');

	public $badCharacters = array(' ', '&', '#', '$', '(', ')', '/', '%', '\.', '.', '\'');

	public function buildField($field, $requireRequired) {

		$retVal = '';

		$fieldOptions = array();
		$label = ((key_exists('required', $field) && $field['required'] == true) ? $field['name'] . '*' : $field['name']);
		$fieldOptions = Hash::insert($fieldOptions, 'label', $label);
		$title = ($field['rep_only'] == true ? ' title="only the rep will see this"' : '');
		// TODO:
		//    if rep_only and the merchant/end-user is inputting data
		//    then don't show this field
		$fieldId = $field['merge_field_name'];
		$fieldOptions = Hash::insert($fieldOptions, 'name', $fieldId);
		$fieldOptions = Hash::insert($fieldOptions, 'id', $fieldId);
		// hide fields with a source of 0 == API
		$apiField = ($field['source'] == 0);
		if ($apiField) {
			$fieldOptions = Hash::insert($fieldOptions, 'disabled', 'disabled');
		}

		// make fields with a source of 3 read only... 3 = n/a (not available)
		if ($field['source'] == 3) {
			$fieldOptions = Hash::insert($fieldOptions, 'disabled', 'disabled');
		}

		$retVal = $retVal . String::insert(
			'<div class="col-md-:width:api_field":title>',
			array(
				'width' => $field['width'],
				'title' => $title,
				'api_field' => ($apiField ? ' api-field': ''),
			)
		);
		$requiredProp = ($field['required'] && $requireRequired) ? true : false;
		$fieldOptions = Hash::insert($fieldOptions, 'required', $requiredProp);
		// TODO: move the ['CobrandedApplicationValues'][0]['id'] data fetch into the model
		if ($field['type'] < 4 || $field['type'] > 7) {
			if (is_array($field) && count($field['CobrandedApplicationValues']) > 0) {
				$fieldOptions = Hash::insert($fieldOptions, 'data-value-id', $field['CobrandedApplicationValues'][0]['id']);
				$fieldOptions = Hash::insert($fieldOptions, 'value', $field['CobrandedApplicationValues'][0]['value']);
			}
		}

		switch ($field['type']) {
			case 0: // text
				$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
				$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
				$retVal = $retVal . $this->Form->input($field['name'], $fieldOptions);
				break;

			case 1:  // 'date'
			case 2:  // 'time'
			case 9:  // 'phoneUS',       //  9 - (###) ###-####
			case 12: // 'ssn',           // 12 - ###-##-####
			case 13: // 'zipcodeUS',     // 13 - #####[-####]
			case 18: // 'number'         // 18 - (#)+.(#)+
			case 19: // 'digits',        // 19 - (#)+
				$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
				if ($field['type'] == 1) {
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'date');
				} else if ($field['type'] == 2) {
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'time12h');
				} else if ($field['type'] == 9) {
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'phoneUS');
				} else if ($field['type'] == 12) {
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'ssn');
				} else if ($field['type'] == 13) {
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'zipcodeUS');
				} else if ($field['type'] == 18) {
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'number');
				} else if ($field['type'] == 19) {
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'digits');
				}
				
				$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
				$retVal = $retVal . $this->Form->input($field['name'], $fieldOptions);
				break;

			case 3: // checkbox
				if ($field['CobrandedApplicationValues'][0]['value'] == 'true') {
					$fieldOptions = Hash::insert($fieldOptions, 'checked', 'checked');
				}
				$retVal = $retVal . $this->Html->div('checkbox',
					$this->Form->checkbox($field['name'], $fieldOptions).
					$this->Form->label($fieldId, $label)
				);
				break;

			case 4: // radio group (inline)
				$radioOptionsString = $field['default_value'];
				$lis = "";
				$defaultValues = split(',', $field['default_value']);
				$index = 0;
				foreach ($field['CobrandedApplicationValues'] as $radioOption) {
					$disabled = '';
					// make fields with a source of 3 read only... 3 = n/a (not available)
					if ($field['source'] == 3) {
						$disabled = 'disabled';
					}

					$nameValuePair = split('::', $defaultValues[$index]);
					$lis = $lis.$this->Html->tag('li',
						$this->Html->tag('label',
							$this->Html->tag(
								'input',
								$nameValuePair[0], // no value <input />
								array(
									'type' => 'radio',
									'name' => $field['name'],
									'data-value-id' => $radioOption['id'],
									'checked' => ($radioOption['value'] == null ? '' : 'checked'),
									'disabled' => $disabled,
								)
							)
						)
					);
					$index = $index + 1;
				}
				$retVal = $retVal.
					$this->Html->tag('label', $label).
					$this->Html->tag('ul', $lis, array('class' => 'list-inline'));
				break;

			case 5: // percent group
				$cleanFieldId = str_replace($this->badCharacters, '', $field['name']);
				$fieldOptions = Hash::insert($fieldOptions, 'type', 'number');
				$fieldOptions = Hash::insert($fieldOptions, 'onkeypress', 'if ( isNaN(this.value + String.fromCharCode(event.which) )) return false;');
				$fieldOptions = Hash::insert($fieldOptions, 'onblur', '$.event.trigger({type: "percentOptionBlur", origin: this, totalFieldId: "#' . $cleanFieldId . '_Total", "fieldset_id": "' . $cleanFieldId . '"});');
				$fieldOptions = Hash::insert($fieldOptions, 'min', 0);
				$fieldOptions = Hash::insert($fieldOptions, 'max', 100);
				$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');

				$retVal = $retVal . '<fieldset id="'.$cleanFieldId.'" class="percent">';
				$retVal = $retVal . '<legend>'.$field['name'].' <span class="small">(total must equal 100%)</span></legend>';

				foreach ($field['CobrandedApplicationValues'] as $percentOption) {
					$fieldOptions = Hash::insert($fieldOptions, 'id', $percentOption['name']);
					$fieldOptions = Hash::insert($fieldOptions, 'name', $percentOption['name']);
					$fieldOptions = Hash::insert($fieldOptions, 'data-value-id', $percentOption['id']);
					$fieldOptions = Hash::insert($fieldOptions, 'label', $percentOption['name']);
					$fieldOptions = Hash::insert($fieldOptions, 'value', $percentOption['value']);
					$retVal = $retVal . $this->Form->input($percentOption['name'], $fieldOptions);
				}

				// lastly add the total
				$retVal = $retVal . $this->Form->input('Total',
					array(
						'id' => $cleanFieldId . '_Total',
						'name' => $cleanFieldId . '_Total',
						'disabled' => 'disabled',
						'onclick' => 'return false;',
						'class' => 'col-md-'.$field['width'],
						'required' => $requiredProp
					)
				);
				$retVal = $retVal . "</fieldset>";
				break;

			case 6: // label
				$retVal = $retVal . $this->Html->tag('h4', $field['name'], array());
				break;

			case 7: // fees group
				$cleanFieldId = str_replace($this->badCharacters, '', $field['merge_field_name']);
				$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
				$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');

				$retVal = $retVal . '<fieldset id="' . $cleanFieldId .'" class="fees">';
				$retVal = $retVal . "<legend>" . $field['name'] . "</legend>";
				$defaultValues = split(',', $field['default_value']);

				$index = 0;
				foreach ($field['CobrandedApplicationValues'] as $feeOption) {
					$nameValuePair = split('::', $defaultValues[$index]);
					$fieldOptions = Hash::insert($fieldOptions, 'name', $feeOption['name']);
					$fieldOptions = Hash::insert($fieldOptions, 'id', $nameValuePair[1]);
					$fieldOptions = Hash::insert($fieldOptions, 'data-value-id', $feeOption['id']);
					$fieldOptions = Hash::insert($fieldOptions, 'value', $feeOption['value']);
					$retVal = $retVal.$this->__buildMoneyField($fieldOptions, $nameValuePair[0], $nameValuePair[1]);
					$index = $index + 1;
				}
				$retVal = $retVal . "</fieldset>";
				break;

			case 8:
				$retVal = $retVal . $this->Html->tag('hr');
				break;

			// 'money',         // 10 - $###.##
			case 10:
				$retVal = $retVal.$this->__buildMoneyField($fieldOptions, $label, $fieldId);
				break;
			// 'percent',       // 11 - (0-100)%
			case 11:
				$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
				$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'digits');
				$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-2');
				$fieldOptions = Hash::insert($fieldOptions, 'min', '0');
				$fieldOptions = Hash::insert($fieldOptions, 'max', '100');
				$retVal = $retVal.$this->Html->tag(
					'div',
					$this->Html->tag('label', $label, array('for' => $fieldId)).
					$this->Html->tag('input', '', $fieldOptions).
					$this->Html->tag('span', '%', array('class' => 'input-group-addon col-md-1')),
					array('class' => 'input-group col-md-12')
				);
				break;

			case 14: // 'url'            // 17 - http(s)?://...
				$fieldOptions = Hash::insert($fieldOptions, 'type', 'email');
				$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
				$retVal = $retVal . $this->Form->input($field['name'], $fieldOptions);
				break;

			// 'lengthoftime',  // 15 - [#+] [year|month|day]s
			// 'creditcard',    // 16 - 

			case 17: // 'url'            // 17 - http(s)?://...
				$fieldOptions = Hash::insert($fieldOptions, 'type', 'url');
				$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
				$retVal = $retVal . $this->Form->input($field['name'], $fieldOptions);
				break;

			case 20: // 'select'         // 20
				$radioOptionsString = $field['default_value'];
				$radioOptions = array();
				foreach (split(',', $radioOptionsString) as $keyValuePairStr) {
					$keyValuePair = split('::', $keyValuePairStr);
					$radioOptions[$keyValuePair[1]] = $keyValuePair[0];
				}
				$fieldOptions = Hash::insert($fieldOptions, 'empty', __('(choose one)'));
				$fieldOptions = Hash::insert($fieldOptions, 'options', $radioOptions);
				$retVal = $retVal . $this->Form->input($field['name'], $fieldOptions);
				break;

			case 21: // textarea
				$retVal = $retVal.
					$this->Html->tag('label', $field['name'], array('for', $fieldId)).
					$this->Form->textarea($field['name'], $fieldOptions);
				break;

			case 23: // number
				$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
				$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'number');
				$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
				$retVal = $retVal.$this->Form->input($field['name'], $fieldOptions);
				break;

			default:
				$retVal = $retVal . '***** UNRECOGNIZED FIELD TYPE [' . $field['type'] . '] for field [' . $field['merge_field_name'] . ']*****';
				break;
		}
		$retVal = $retVal . '</div>';

		return $retVal;
	}

	private function __buildMoneyField($fieldOptions, $label, $fieldId) {
		$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
		$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'money');
		$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-10');
		$retVal = $this->Html->tag('label', $label, array('for' => $fieldId)).
		$this->Html->tag(
			'div',
			$this->Html->tag('span', '$', array('class' => 'input-group-addon col-md-1')).
			$this->Html->tag('input', '', $fieldOptions),
			array('class' => 'input-group col-md-12')
		);
		return $retVal;
	}
}