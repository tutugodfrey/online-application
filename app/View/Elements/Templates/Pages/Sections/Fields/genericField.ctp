<?php foreach ($fields as $field):
	// suppress fields with a source of 0 == API
	if ($field['source'] != 0) {
		$label = ($field['required'] == true ? $field['name'] . '*' : $field['name']);
		echo String::insert('<div class="col-md-:width">', array('width' => $field['width']));
		switch ($field['type']) {
			case 0: // text
				echo $this->Form->input($field['name'], array('label' => $label, 'class' => 'col-md-12'));
				break;

			case 1: // date
				echo $this->Form->input($field['name'], array('type' => 'date', 'label' => $label));
				break;

			case 2: // time
				echo $this->Form->input($field['name'], array('type' => 'time', 'label' => $label));
				break;

			case 3: // checkboxes
				echo $this->Html->div('checkbox',
					$this->Form->checkbox($field['name']) . $this->Form->label($field['name'])
				);
				break;

			case 4: // radios
				$radio_options_string = $field['default_value'];
				$radio_options = array();
				foreach (split(',', $radio_options_string) as $key_value_pair_str) {
					$key_value_pair = split('::', $key_value_pair_str);
					$radio_options[$key_value_pair[1]] = $key_value_pair[0];
				}
				$options = array('options' => $radio_options, 'empty' => __('(choose one)'), 'label' => $label);
				echo $this->Form->input($field['name'], $options);
				break;

			case 5: // percent
				$field_id = str_replace($bad_characters, '', $field['name']);
				echo "<fieldset id='" . $field_id ."' class='percent'>";
				echo "<legend>" . $field['name'] . "</legend>";
				$percent_options_string = $field['default_value'];
				foreach (split(',', $percent_options_string) as $key_value_pair_str) {
					$key_value_pair = split('::', $key_value_pair_str);
					echo $this->Form->input(
						str_replace($bad_characters, '', $key_value_pair[0]),
						array(
							'type' => 'number',
							'id' => $field_id . "_" . str_replace($bad_characters, '', $key_value_pair[0]),
							'onkeypress' => 'if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;',
							'onblur' => '$.event.trigger({type: "percentOptionBlur", origin: this, totalFieldId: "#' . $field_id . '_Total", "fieldset_id": "' . $field_id . '"});',
							'min' => 0,
							'max' => 100,
							'class' => 'col-md-12'
						)
					);
				}
				// lastly add the total
				echo $this->Form->input('Total', array('id' => $field_id . '_Total', 'disabled' => 'disabled', 'onclick' => 'return false;', 'class' => 'col-md-12'));
				echo "</fieldset>";
				break;

			case 6: // label
				echo $this->Html->tag('h4', $field['name'], array());
				break;

			case 7: // fees
				$field_id = str_replace($bad_characters, '', $field['name']);
				echo "<fieldset id='" . $field_id ."' class='fees'>";
				echo "<legend>" . $field['name'] . "</legend>";
				$percent_options_string = $field['default_value'];
				foreach (split(',', $percent_options_string) as $key_value_pair_str) {
					$key_value_pair = split('::', $key_value_pair_str);
					echo $this->Form->input(
						$key_value_pair[0].' $',
						array(
							'type' => 'text',
							'id' => $field_id . "_" . str_replace($bad_characters, '', $key_value_pair[0]),
							'onkeypress' => 'if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;',
							'onblur' => '$.event.trigger({type: "feeOptionBlur", origin: this, totalFieldId: "#' . $field_id . '_Total", "fieldset_id": "' . $field_id . '"});',
							'class' => 'col-md-12',
							'value' => $key_value_pair[1],
						)
					);
				}
				// lastly add the total
				echo $this->Form->input('Total', array('id' => $field_id . '_Total', 'disabled' => 'disabled', 'onclick' => 'return false;', 'class' => 'col-md-12'));
				echo "</fieldset>";
				break;

			case 8:
				echo $this->Html->tag('hr');
				break;

			default:
				echo '***** UNRECOGNIZED FIELD TYPE [' . $field['type'] . '] for field [' . $field['merge_field_name'] . ']*****';
				break;
		}
		echo '</div>';
	}
	?>
<?php endforeach; ?><!-- end fields -->