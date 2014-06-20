<?php
	echo $this->Html->css('step_3', null, array('inline' => false));
	$states = array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York", 'NC'=>"North Carolina", 'ND'=>"North Dakota", 'OH'=>"Ohio", 'OK'=>"Oklahoma", 'OR'=>"Oregon", 'PA'=>"Pennsylvania", 'RI'=>"Rhode Island", 'SC'=>"South Carolina", 'SD'=>"South Dakota", 'TN'=>"Tennessee", 'TX'=>"Texas", 'UT'=>"Utah", 'VT'=>"Vermont", 'VA'=>"Virginia", 'WA'=>"Washington", 'WV'=>"West Virginia", 'WI'=>"Wisconsin", 'WY'=>"Wyoming");

	if (isset($errors) && is_array($errors)) {
		/**
		 * if statements are a work-a-round for cake returning the error message twice
		 */
		if($errors['depository_routing_number'][1]) {unset($errors['depository_routing_number'][1]);}
		if($errors['fees_routing_number'][1]) {unset($errors['fees_routing_number'][1]);}
		echo $this->Html->scriptBlock("var errors = " . json_encode($errors) . ";", array('inline' => false));

		echo $this->Html->scriptBlock("
			window.onload=function() {
				if (errors.depository_routing_number || errors.fees_routing_number) {
					var divObjs = document.getElementsByTagName('div');

					for(x=0; x < divObjs.length; x++){
						var errorMsg = document.createElement('span');

						if (errors.depository_routing_number && divObjs[x].className == 'depository_routing_number') {
							errorMsg.innerHTML = errors.depository_routing_number;
							divObjs[x].firstChild.appendChild(errorMsg);
						}
						if (errors.fees_routing_number && divObjs[x].className =='fees_routing_number') {
							errorMsg.innerHTML = errors.fees_routing_number
							divObjs[x].firstChild.appendChild(errorMsg);
						}
					}
				}
			}
		");
	}

?>
<?php if (in_array($this->request->data['Application']['status'], array('completed', 'signed'))): ?><h3 class="center">Note: This application has been marked as completed and is read-only.</h3><?php endif; ?>

<p class="steps_blocks">
	<?php
		if (in_array($this->request->data['Application']['status'], array('pending', 'completed', 'signed')) || in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
			for ($i=0; $i<6; $i++) {
				echo $this->Html->link($this->Html->image('steps_block.png', array('alt'=> __('Step ' . ($i + 1)), 'border' => '0')), '/applications/add/' . ($i + 1) . '/' . $id . '/' . $hash, array('title' => 'Step '. ($i + 1), 'escape' => false)) . ' &nbsp; ';
			}
		} else {
			for ($i=0; $i<$step; $i++) {
				echo $this->Html->link($this->Html->image('steps_block.png', array('alt'=> __('*'), 'border' => '0')), '/applications/add/' . ($i + 1) . '/' . $id . '/' . $hash, array('title' => 'Step '. ($i + 1), 'escape' => false)) . ' &nbsp; ';
			}
		}
	?>
</p>

<h4>Step 3 of 6 | ACH Bank and Trade Reference</h4>

<?php
	if (isset($errors) && is_array($errors)) {
		echo '<h4 class="error">Please correct the specified errors (labeled in red)</h4>';
	}
	
	echo $this->Form->create('Application', array('url' => '/applications/add/' . $step . '/' . $id . '/' . $hash, 'novalidate' => true));
	echo $this->Form->hidden('id', array('value' => $id));
	echo $this->Form->hidden('monthly_volume');
	echo $this->Form->hidden('average_ticket');
?>

<table>
	<tr><th>Bank Information</th></tr>
	<tr><td colspan="3">
		<?php echo $this->Html->div(
			'bank_name',
			$this->Form->label('bank_name', 'Bank Name *') .
			$this->Form->text('bank_name')
		); ?>
	</td></tr>
	<tr><td colspan="3">
		<?php echo $this->Html->div(
			'bank_contact_name',
			$this->Form->label('bank_contact_name', 'Contact Name') .
			$this->Form->text('bank_contact_name')
		); ?>
	</td></tr>
	<tr><td colspan="3">
		<?php echo $this->Html->div(
			'bank_phone',
			$this->Form->label('bank_phone', 'Phone') .
			$this->Form->text('bank_phone')
		); ?>
	</td></tr>
	<tr><td colspan="3">
		<?php echo $this->Html->div(
			'bank_address',
			$this->Form->label('bank_address', 'Address') .
			$this->Form->text('bank_address')
		); ?>
	</td></tr>
	<tr>
		<td>
			<?php echo $this->Html->div(
				'bank_city',
				$this->Form->label('bank_city', 'City') .
				$this->Form->text('bank_city')
			); ?>
		</td>
		<td>
			<?php echo $this->Html->div(
				'bank_state',
				$this->Form->label('bank_state', 'State') .
				$this->Form->select('bank_state', $states)
			); ?>
		</td>
		<td>
			<?php echo $this->Html->div(
				'bank_zip',
				$this->Form->label('bank_zip', 'Zip') .
				$this->Form->text('bank_zip')
			); ?>
		</td>
	</tr>
</table>

<p><hr /></p>

<table>
	<tr>
		<td>
			<table>
				<tr><th>Depository Account</th></tr>
				<tr><td>
					<?php echo $this->Html->div(
						'depository_routing_number',
						$this->Form->label('depository_routing_number', 'Routing Number *') .
						$this->Form->text('depository_routing_number')
					); ?>
				</td></tr>
				<tr><td>
					<?php echo $this->Html->div(
						'depository_account_number',
						$this->Form->label('depository_account_number', 'Account Number *') .
						$this->Form->text('depository_account_number')
					); ?>
				</td></tr>
			</table>
		</td>
		<td>
			<table>
				<tr><th>Fees Account</th></tr>
				<tr><td>
					<?php echo $this->Html->div(
						'fees_routing_number',
						$this->Form->label('fees_routing_number', 'Routing Number *') .
						$this->Form->text('fees_routing_number')
					); ?>
				</td></tr>
				<tr><td>
					<?php echo $this->Html->div(
						'fees_account_number',
						$this->Form->label('fees_account_number', 'Account Number *') .
						$this->Form->text('fees_account_number')
					); ?>
				</td></tr>
			</table>
		</td>
	</tr>
</table>

<p><hr /></p>
<table>
	<tr>
		<td>
			<table>
				<tr><th colspan="3">Trade Reference 1</th></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'trade1_business_name',
						$this->Form->label('trade1_business_name', 'Business Name *') .
						$this->Form->text('trade1_business_name')
					); ?>
				</td></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'trade1_contact_person',
						$this->Form->label('trade1_contact_person', 'Contact Person *') .
						$this->Form->text('trade1_contact_person')
					); ?>
				</td></tr>
				<tr>
					<td>
						<?php echo $this->Html->div(
							'trade1_phone',
							$this->Form->label('trade1_phone', 'Phone *') .
							$this->Form->text('trade1_phone')
						); ?>
					</td>
					<td>
						<?php echo $this->Html->div(
							'trade1_acct_num',
							$this->Form->label('trade1_acct_num', 'Acct # *') .
							$this->Form->text('trade1_acct_num')
						); ?>
					</td>
					<td style="width: 107px;">&nbsp;</td>
				</tr>
				<tr>
					<td>
						<?php echo $this->Html->div(
							'trade1_city',
							$this->Form->label('trade1_city', 'City *') .
							$this->Form->text('trade1_city')
						); ?>
					</td>
					<td>
						<?php echo $this->Html->div(
							'trade1_state',
							$this->Form->label('trade1_state', 'State *') .
							$this->Form->select('trade1_state', $states)
						); ?>
					</td>
					<td style="width: 107px;">&nbsp;</td>
				</tr>
			</table>
		</td>
		<td>
			<table>
				<tr><th colspan="3">Trade Reference 2</th></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'trade2_business_name',
						$this->Form->label('trade2_business_name', 'Business Name') .
						$this->Form->text('trade2_business_name')
					); ?>
				</td></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'trade2_contact_person',
						$this->Form->label('trade2_contact_person', 'Contact Person') .
						$this->Form->text('trade2_contact_person')
					); ?>
				</td></tr>
				<tr>
					<td>
						<?php echo $this->Html->div(
							'trade2_phone',
							$this->Form->label('trade2_phone', 'Phone') .
							$this->Form->text('trade2_phone')
						); ?>
					</td>
					<td>
						<?php echo $this->Html->div(
							'trade2_acct_num',
							$this->Form->label('trade2_acct_num', 'Acct #') .
							$this->Form->text('trade2_acct_num')
						); ?>
					</td>
					<td style="width: 107px;">&nbsp;</td>
				</tr>
				<tr>
					<td>
						<?php echo $this->Html->div(
							'trade2_city',
							$this->Form->label('trade2_city', 'City') .
							$this->Form->text('trade2_city')
						); ?>
					</td>
					<td>
						<?php echo $this->Html->div(
							'trade2_state',
							$this->Form->label('trade2_state', 'State') .
							$this->Form->select('trade2_state', $states)
						); ?>
					</td>
					<td style="width: 107px;">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<p><hr /></p>
<p>Fields marked with * are required.</p>

<?php
	echo $this->Form->end('Save & Continue to Next Step ->');

	if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
		echo $this->Html->link(
			'Return to Applications Admin',
			'/admin/applications/',
			array('style' => 'display: block; float: right;')
		);
	}

	echo $this->Html->scriptBlock("
		$(document).ready(function() {
			if (". (isset($errors) && is_array($errors) ? '1' : '0') .") {
				for (field in errors) {
					if ($('.' + field).length > 0) {
						if ($('.' + field + ' input').filter(':first').length) {
							$('.' + field + ' label').filter(':first').css('color', '#f00');
						}
						else if ($('.' + field + ' select').filter(':first').length) {
							$('.' + field + ' label').filter(':first').css('color', '#f00');
						}
					}
				}
			}
		});
	", array('inline' => false));
?>
