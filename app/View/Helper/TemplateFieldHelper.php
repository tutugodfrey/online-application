<?php

App::uses('Helper', 'View');
App::uses('TemplateField', 'Model');

class TemplateFieldHelper extends Helper {

	public $helpers = array('Form', 'Html');

	public $badCharacters = array(' ', '&', '#', '$', '(', ')', '/', '%', '\.', '.', '\'');

	public function buildField($field, $requireRequiredFields) {

		$retVal = '';

		// suppress fields with a source of 0 == API
		if ($field['source'] != 0) {
			$fieldOptions = array();
			$label = ($field['required'] == true ? $field['name'] . '*' : $field['name']);
			$fieldOptions = Hash::insert($fieldOptions, 'label', $label);
			$title = ($field['rep_only'] == true ? ' title="only the rep will see this"' : '');
			// TODO: if rep_only and the merchant is inputting data, don't show this field
			$fieldId = $field['merge_field_name'];
			$fieldOptions = Hash::insert($fieldOptions, 'name', $fieldId);
			$fieldOptions = Hash::insert($fieldOptions, 'id', $fieldId);
			$retVal = $retVal .  String::insert('<div class="col-md-:width":title>', array('width' => $field['width'], 'title' => $title));
			$requiredProp = ($field['required'] && $requireRequiredFields) ? true : false;
			$fieldOptions = Hash::insert($fieldOptions, 'required', $requiredProp);
			// TODO: move the ['CobrandedApplicationValues'][0]['id'] data fetch into the model
			if ($field['type'] < 4 || $field['type'] > 7) {
				$fieldOptions = Hash::insert($fieldOptions, 'data-value-id', $field['CobrandedApplicationValues'][0]['id']);
				$fieldOptions = Hash::insert($fieldOptions, 'value', $field['CobrandedApplicationValues'][0]['value']);
			}

			switch ($field['type']) {
				case 0: // text
					$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
					$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
					$retVal = $retVal . $this->Form->input($field['name'], $fieldOptions);
					break;

				case 1: // date
					$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'date');
					$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
					$retVal = $retVal . $this->Form->input($field['name'], $fieldOptions);
					break;

				case 2: // time
					$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'time12h');
					$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
					$retVal = $retVal . $this->Form->input($field['name'], $fieldOptions);
					break;

				case 3: // checkbox
					$retVal = $retVal . $this->Html->div('checkbox',
						$this->Form->checkbox($field['name'], $fieldOptions).
						$this->Form->label($fieldId, $label)
					);
					break;

				case 4: // radio group (inline)
					$radioOptionsString = $field['default_value'];
					$lis = "";
					$defaultValue = split(',', $field['default_value']);
					$index = 0;
					foreach ($field['CobrandedApplicationValues'] as $radioOption) {
						$nameValuePair = split('::', $defaultValue[$index]);
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
					$fieldOptions = Hash::insert($fieldOptions, 'onkeypress', 'if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;');
					$fieldOptions = Hash::insert($fieldOptions, 'onblur', '$.event.trigger({type: "percentOptionBlur", origin: this, totalFieldId: "#' . $cleanFieldId . '_Total", "fieldset_id": "' . $cleanFieldId . '"});');
					$fieldOptions = Hash::insert($fieldOptions, 'min', 0);
					$fieldOptions = Hash::insert($fieldOptions, 'max', 100);
					$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');

					$retVal = $retVal . '<fieldset id="'.$cleanFieldId.'" class="percent">';
					$retVal = $retVal . "<legend>" . $field['name'] . "</legend>";

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
					$fieldOptions = Hash::insert($fieldOptions, 'onkeypress', 'if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;');
					$fieldOptions = Hash::insert($fieldOptions, 'onblur', '$.event.trigger({type: "feeOptionBlur", origin: this, totalFieldId: "#' . $cleanFieldId . '_Total", "fieldset_id": "' . $cleanFieldId . '"});');
					$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');

					$retVal = $retVal . '<fieldset id="' . $cleanFieldId .'" class="fees">';
					$retVal = $retVal . "<legend>" . $field['name'] . "</legend>";
					$optionsString = $field['default_value'];

					foreach (split(',', $optionsString) as $keyValuePairStr) {
						$keyValuePair = split('::', $keyValuePairStr);
						$fieldOptions['id'] = $cleanFieldId . "_" . str_replace($this->badCharacters, '', $keyValuePair[0]);
						$fieldOptions['name'] = $cleanFieldId . "_" . str_replace($this->badCharacters, '', $keyValuePair[0]);
						$fieldOptions['label'] = $keyValuePair[0];
						$fieldOptions['value'] = $keyValuePair[1];
						$retVal = $retVal . $this->Form->input(str_replace($this->badCharacters, '', $keyValuePair[0]).' $', $fieldOptions);
					}
					$retVal = $retVal . "</fieldset>";
					break;

				case 8:
					$retVal = $retVal . $this->Html->tag('hr');
					break;

				// 'phoneUS',       //  9 - (###) ###-####
				case 9:
					$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'phoneUS');
					$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
					$retVal = $retVal.$this->Form->input($field['name'], $fieldOptions);
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

				// 'ssn',           // 12 - ###-##-####
				case 12:
					$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'ssn');
					$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
					$retVal = $retVal.$this->Form->input($field['name'], $fieldOptions);
					break;

				// 'zipcodeUS',           // 13 - #####[-####]
				case 13:
					$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'zipcodeUS');
					$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
					$retVal = $retVal.$this->Form->input($field['name'], $fieldOptions);
					break;

				// 'email',         // 14 - 
				case 14:
					$fieldOptions = Hash::insert($fieldOptions, 'type', 'email');
					$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
					$retVal = $retVal . $this->Form->input($field['name'], $fieldOptions);
					break;

				// 'lengthoftime',  // 15 - [#+] [year|month|day]s
				// 'creditcard',    // 16 - 

				// 'url'            // 17 - http(s)?://...
				case 17:
					$fieldOptions = Hash::insert($fieldOptions, 'type', 'url');
					$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
					$retVal = $retVal . $this->Form->input($field['name'], $fieldOptions);
					break;

				// 'number'         // 18 - (#)+.(#)+
				case 18:
					$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'number');
					$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
					$retVal = $retVal.$this->Form->input($field['name'], $fieldOptions);
					break;

				// 'digits',        // 19 - (#)+
				case 19:
					$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
					$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'digits');
					$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-12');
					$retVal = $retVal.$this->Form->input($field['name'], $fieldOptions);
					break;

				// 'select'         // 20
				case 20:
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

				case 21:
					$retVal = $retVal.
						$this->Html->tag('label', $field['name'], array('for', $fieldId)).
						$this->Form->textarea($field['name'], $fieldOptions);
					break;

				default:
					$retVal = $retVal . '***** UNRECOGNIZED FIELD TYPE [' . $field['type'] . '] for field [' . $field['merge_field_name'] . ']*****';
					break;
			}
			$retVal = $retVal . '</div>';
		}

		return $retVal;
	}

	private function __buildMoneyField($fieldOptions, $label, $fieldId) {
		$fieldOptions = Hash::insert($fieldOptions, 'type', 'text');
		$fieldOptions = Hash::insert($fieldOptions, 'data-vtype', 'money');
		$fieldOptions = Hash::insert($fieldOptions, 'class', 'col-md-11');
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