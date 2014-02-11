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
			$label = ($field['required'] == true ? $field['name'] . '*' : $field['name']);
			$title = ($field['rep_only'] == true ? ' title="only the rep will see this"' : '');
			// TODO: if rep_only and the merchant is inputting data, don't show this field
			$fieldId = $field['merge_field_name'];
			$retVal = $retVal .  String::insert('<div class="col-md-:width":title>', array('width' => $field['width'], 'title' => $title));

			$requiedProp = ($field['required'] && $requireRequiredFields) ? true : false;
			switch ($field['type']) {
				case 0: // text
					$retVal = $retVal . $this->Form->input($field['name'], array('type' => 'text', 'label' => $label, 'class' => 'col-md-12', 'id' => $fieldId, 'name' => $fieldId, 'required' => $requiedProp));
					break;

				case 1: // date
					$retVal = $retVal . $this->Form->input($field['name'], array('type' => 'date', 'label' => $label, 'id' => $fieldId, 'name' => $fieldId, 'required' => $requiedProp, 'empty' => true));
					break;

				case 2: // time
					$retVal = $retVal . $this->Form->input($field['name'], array('type' => 'time', 'label' => $label, 'id' => $fieldId, 'name' => $fieldId, 'required' => $requiedProp, 'empty' => true));
					break;

				case 3: // checkbox
					$retVal = $retVal . $this->Html->div('checkbox',
						$this->Form->checkbox($field['name'], array('id' => $fieldId, 'required' => $requiedProp)) . $this->Form->label($fieldId, $field['name'])
					);
					break;

				case 4: // radio group
					$radioOptionsString = $field['default_value'];
					$radioOptions = array();
					foreach (split(',', $radioOptionsString) as $keyValuePairStr) {
						$keyValuePair = split('::', $keyValuePairStr);
						$radioOptions[$keyValuePair[1]] = $keyValuePair[0];
					}
					$options = array('options' => $radioOptions, 'empty' => __('(choose one)'), 'label' => $label, 'id' => $fieldId, 'required' => $requiedProp);
					$retVal = $retVal . $this->Form->input($field['name'], $options);
					break;

				case 5: // percent group
					$cleanFieldId = str_replace($this->badCharacters, '', $fieldId);
					$retVal = $retVal . '<fieldset id="'.$cleanFieldId.'" class="percent">';
					$retVal = $retVal . "<legend>" . $field['name'] . "</legend>";
					$percentOptionsString = $field['default_value'];
					foreach (split(',', $percentOptionsString) as $keyValuePairStr) {
						$keyValuePair = split('::', $keyValuePairStr);
						$retVal = $retVal . $this->Form->input(
							str_replace($this->badCharacters, '', $keyValuePair[0]),
							array(
								'type' => 'number',
								'id' => $cleanFieldId . "_" . str_replace($this->badCharacters, '', $keyValuePair[0]),
								'onkeypress' => 'if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;',
								'onblur' => '$.event.trigger({type: "percentOptionBlur", origin: this, totalFieldId: "#' . $cleanFieldId . '_Total", "fieldset_id": "' . $cleanFieldId . '"});',
								'min' => 0,
								'max' => 100,
								'class' => 'col-md-12',
								'required' => $requiedProp,
							)
						);
					}
					// lastly add the total
					$retVal = $retVal . $this->Form->input('Total', array('id' => $cleanFieldId . '_Total', 'disabled' => 'disabled', 'onclick' => 'return false;', 'class' => String::insert('col-md-:width', array('width' => $field['width'])), 'required' => $requiedProp));
					$retVal = $retVal . "</fieldset>";
					break;

				case 6: // label
					$retVal = $retVal . $this->Html->tag('h4', $field['name'], array());
					break;

				case 7: // fees
					$cleanFieldId = str_replace($this->badCharacters, '', $field['name']);
					$retVal = $retVal . '<fieldset id="' . $cleanFieldId .'" class="fees">';
					$retVal = $retVal . "<legend>" . $field['name'] . "</legend>";
					$percentOptionsString = $field['default_value'];
					foreach (split(',', $percentOptionsString) as $keyValuePairStr) {
						$keyValuePair = split('::', $keyValuePairStr);
						$retVal = $retVal . $this->Form->input(
							$keyValuePair[0].' $',
							array(
								'type' => 'text',
								'id' => $cleanFieldId . "_" . str_replace($this->badCharacters, '', $keyValuePair[0]),
								'onkeypress' => 'if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;',
								'onblur' => '$.event.trigger({type: "feeOptionBlur", origin: this, totalFieldId: "#' . $cleanFieldId . '_Total", "fieldset_id": "' . $cleanFieldId . '"});',
								'class' => 'col-md-12',
								'value' => $keyValuePair[1],
								'required' => $requiedProp,
							)
						);
					}
					// lastly add the total
					$retVal = $retVal . $this->Form->input('Total', array('id' => $cleanFieldId . '_Total', 'disabled' => 'disabled', 'onclick' => 'return false;', 'class' => String::insert('col-md-:width', array('width' => $field['width'])), 'required' => $requiedProp));
					$retVal = $retVal . "</fieldset>";
					break;

				case 8:
					$retVal = $retVal . $this->Html->tag('hr');
					break;

				// 'phoneUS',       //  9 - (###) ###-####
				// 'ssn',           // 12 - ###-##-####
				// 'zip',           // 14 - #####[-####]
				case 9:
				case 12:
				case 14:
					$retVal = $retVal . $this->Form->input($field['name'], array('label' => $label, 'class' => String::insert('col-md-:width', array('width' => $field['width'])), 'id' => $fieldId, 'name' => $fieldId, 'required' => $requiedProp));
					break;

				// 'money',         // 10 - $###.##
				// 'percent',       // 11 - (0-100)%

				// 'state',         // 13 - us state

				// 'email',         // 15 - 
				case 15:
					$retVal = $retVal . $this->Form->input($field['name'], array('type' => 'email', 'label' => $label, 'id' => $fieldId, 'name' => $fieldId, 'required' => $requiedProp));
					break;

				// 'lengthoftime',  // 16 - [#+] [year|month|day]s
				// 'creditcard',    // 17 - 

				default:
					$retVal = $retVal . '***** UNRECOGNIZED FIELD TYPE [' . $field['type'] . '] for field [' . $field['merge_field_name'] . ']*****';
					break;
			}
			$retVal = $retVal . '</div>';
		}

		return $retVal;
	}
}