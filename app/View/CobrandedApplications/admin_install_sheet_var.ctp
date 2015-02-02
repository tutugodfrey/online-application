<div id="install">
<?php
	echo $this->Html->script('prototype');
	echo $this->Html->script('scriptaculous/src/scriptaculous.js?load=effects');
	if ($data['Merchant']['merchant_id'] == "") {
		echo '<h4>This Application Has not been boarded into the Database!<br/>Please contact <a href="mailto:support@axiapayments.com?subject= Onlineapp ' . $data['CobrandedApplication']['id'] . ' not boarded">support@axiapayments.com</a></h4>';
	} else {
		$contractorId = '';
		$dba = '';
		$corpContact = '';
		$phoneNumber = '';
		$faxNumber = '';
		$address = '';
		$city = '';
		$state = '';
		$zip = '';
		$applications = '';

		foreach ($data['CobrandedApplicationValues'] as $val) {
			if ($val['name'] == 'ContractorID') {
				$contractorId = $val['value'];
			}
			if ($val['name'] == 'DBA') {
				$dba = $val['value'];
			}
			if ($val['name'] == 'CorpContact') {
				$corpContact = $val['value'];
			}
			if ($val['name'] == 'PhoneNum') {
				$phoneNumber = $val['value'];
			}
			if ($val['name'] == 'FaxNum') {
				$faxNumber = $val['value'];
			}
			if ($val['name'] == 'Address') {
				$address = $val['value'];
			}
			if ($val['name'] == 'City') {
				$city = $val['value'];
			}
			if ($val['name'] == 'State') {
				$state = $val['value'];
			}
			if ($val['name'] == 'Zip') {
				$zip = $val['value'];
			}
			if (preg_match("/@/", $val['value'])) {
				$applications .= $val['value'] . ',';
			}
		}

		echo '<h4>Merchant Install Sheet - VAR</h4>';
		echo '<p>Contact Info</p>';
		if (is_array($errors)) {
			echo $this->Html->scriptBlock("var errors = " . json_encode($errors) . ";", array('inline' => false));
		}
		echo $this->Form->create('CobrandedApplication', array('url' => '/cobranded_applications/install_sheet_var/' . $data['CobrandedApplication']['id']));
		echo '<table width="800">';
		echo '<tr>';
		echo '<td><strong>Rep:</strong> ' . $contractorId . '</td>';
		echo '<td><strong>Merchant:</strong> ' . $dba . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td><strong>Phone:</strong> ' . '877.875.6114 x' . $data['User']['extension'] . '</td>';
		echo '<td><strong>Contact:</strong> ' . $corpContact . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td><strong>Fax:</strong> ' . '877.875.5135' . '</td>';
		echo '<td><strong>Phone/Fax:</strong> ' . $phoneNumber . ' / ' . $faxNumber . '</td>';
		echo '</tr>';
		echo '</table>';

		foreach ($data['Merchant']['EquipmentProgramming'] as $programming) {
			echo 'Terminal Type: ' . $programming['terminal_type'];
		}
		echo '<p>Installation Information</p>';
		echo '<strong>Address: </strong>' . $address . '<br/>';
		echo '<strong>City: </strong>' . $city . '<br/>';
		echo '<strong>State: </strong>' . $state . '<br/>';
		echo '<strong>Zip: </strong>' . $zip . '<br/>';
		echo '<strong>Merchant ID: </strong>' . $data['Merchant']['merchant_id'] . '<br/><br/>';

		$applications = explode(",", $applications, -1);
		$applications = array_unique($applications);

		foreach ($applications as $key => $value) {
			$appArray[$value] = $value;
		}

		if ($data['CobrandedApplication']['rightsignature_install_status'] == 'sent') {
			$divString = 'show.hide';
			echo '<strong>This Installation sheet was already sent for Signing.</strong><br/>';
			echo $this->Html->link('Click to Send Again.', '', array('update' => $divString, 'loading' => 'Effect.Appear(\'' . $divString . '\')', )) . '<br/>';
			echo $this->Html->div($divString, array('style' => 'display:none'));
		}
		if ($data['CobrandedApplication']['rightsignature_install_status'] == 'signed') {
			$divString = 'show.hide';
			echo '<strong>This Installation sheet has already been signed.</strong><br/>';
			echo $this->Html->link('Click to Send Again.', '', array('update' => $divString, 'loading' => 'Effect.Appear(\'' . $divString . '\')', )) . '<br/>';
			echo $this->Html->div($divString, array('style' => 'display:none'));
		}

		echo 'Please select or enter an email address for the Install Sheet Recipient <br/>';
		echo '<table><tr class="select_email_address">';
		echo '<td>' . $this->Html->div('select_email_address',
			$this->Form->label(
				'select_email_address',
				'Select Email Address:') . '</td><td>' .
				$this->Form->select(
					'select_email_address',
					$appArray
				) . '</td></tr>'
			);

		echo '<tr class="enter_email_address"><td>' .
			$this->Html->div('enter_email_address',
			$this->Form->label(
				'enter_email_address',
				'Enter Email Address: '
			) . '</td><td>' .
			$this->Form->text('enter_email_address') . '</td>');
		echo '</tr></table>';
		echo $this->Form->end('Submit');
		echo $this->Html->div();
	}
?>
<?php
	echo $this->Element('cobranded_applications/return');
	echo $this->Html->scriptBlock("
	$(document).ready(function() {
		if (" . (is_array($errors) ? '1' : '0') . ") {
			for (field in errors) {
				if ($('.' + field).length > 0) {
					if ($('.' + field + ' input').filter(':first').length) {
						$('.' + field + ' label').filter(':first').css('color', '#f00');
					} else if ($('.' + field + ' select').filter(':first').length) {
						$('.' + field + ' label').filter(':first').css('color', '#f00');
					}
				}
			}
		}
	});
	", array('inline' => false));
?>
</div>
