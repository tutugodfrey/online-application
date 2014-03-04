<?php
	echo $this->Html->css('step_5', null, array('inline' => false));
	$states = array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York", 'NC'=>"North Carolina", 'ND'=>"North Dakota", 'OH'=>"Ohio", 'OK'=>"Oklahoma", 'OR'=>"Oregon", 'PA'=>"Pennsylvania", 'RI'=>"Rhode Island", 'SC'=>"South Carolina", 'SD'=>"South Dakota", 'TN'=>"Tennessee", 'TX'=>"Texas", 'UT'=>"Utah", 'VT'=>"Vermont", 'VA'=>"Virginia", 'WA'=>"Washington", 'WV'=>"West Virginia", 'WI'=>"Wisconsin", 'WY'=>"Wyoming");

	if (isset($errors) && is_array($errors)) {
		echo $this->Html->scriptBlock("var errors = " . json_encode($errors) . ";", array('inline' => false));
	}
?>

<?php if (in_array($this->request->data['Application']['status'], array('completed', 'signed'))): ?><h3 class="center">Note: This application has been marked as completed and is read-only.</h3><?php endif; ?>

<p class="steps_blocks">
	<?php
		if (in_array($this->request->data['Application']['status'], array('pending', 'completed', 'signed')) || in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
			for ($i=0; $i<6; $i++) {
				echo $this->Html->link($this->Html->image('steps_block.png', array('alt'=> __('Step ' . ($i + 1)), 'border' => '0')), '/applications/add/' . ($i + 1) . '/' . $id . '/' . $hash, array('title' => 'Step '. ($i + 1), 'escape' => false)) . ' &nbsp; ';
			}
		}
		else {
			for ($i=0; $i<$step; $i++) {
				echo $this->Html->link($this->Html->image('steps_block.png', array('alt'=> __('*'), 'border' => '0')), '/applications/add/' . ($i + 1) . '/' . $id . '/' . $hash, array('title' => 'Step '. ($i + 1), 'escape' => false)) . ' &nbsp; ';
			}
		}
	?>
</p>

<h4>Step 5 of 6 | Ownership Information</h4>

<?php
	if (isset($errors) && is_array($errors)) {
		echo '<h4 class="error">Please correct the specified errors (labeled in red)</h4>';
	}
	
	echo $this->Form->create('Application', array('url' => '/applications/add/' . $step . '/' . $id . '/' . $hash, 'novalidate' => true));
	echo $this->Form->hidden('id', array('value' => $id));
?>

<table cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table>
				<tr><th colspan="3" style="white-space: nowrap;">
					<?php echo $this->Html->div(
						'owner1_percentage',
						$this->Form->label('owner1_percentage', 'OWNER / OFFICER (1) Percentage Ownership * ', array('style' => 'display: inline;')) .
						$this->Form->text('owner1_percentage') . ' %'
					); ?>
				</th></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'owner1_fullname',
						$this->Form->label('owner1_fullname', 'Full Name *') .
						$this->Form->text('owner1_fullname')
					); ?>
				</td></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'owner1_title',
						$this->Form->label('owner1_title', 'Title *') .
						$this->Form->text('owner1_title')
					); ?>
				</td></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'owner1_address',
						$this->Form->label('owner1_address', 'Address *') .
						$this->Form->text('owner1_address')
					); ?>
				</td></tr>
				<tr>
					<td>
						<?php echo $this->Html->div(
							'owner1_city',
							$this->Form->label('owner1_city', 'City *') .
							$this->Form->text('owner1_city')
						); ?>
					</td>
					<td>
						<?php echo $this->Html->div(
							'owner1_state',
							$this->Form->label('owner1_state', 'State *') .
							$this->Form->select('owner1_state', $states)
						); ?>
					</td>
					<td>
						<?php echo $this->Html->div(
							'owner1_zip',
							$this->Form->label('owner1_zip', 'Zip *') .
							$this->Form->text('owner1_zip')
						); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $this->Html->div(
							'owner1_phone',
							$this->Form->label('owner1_phone', 'Phone *') .
							$this->Form->text('owner1_phone')
						); ?>
					</td>
					<td>
						<?php echo $this->Html->div(
							'owner1_fax',
							$this->Form->label('owner1_fax', 'Fax') .
							$this->Form->text('owner1_fax')
						); ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'owner1_email',
						$this->Form->label('owner1_email', 'Email *') .
						$this->Form->text('owner1_email')
					); ?>
				</td></tr>
				<tr>
					<td colspan="2">
						<?php echo $this->Html->div(
							'owner1_ssn',
							$this->Form->label('owner1_ssn', 'SSN *') .
							$this->Form->text('owner1_ssn', array('maxlength'=> '11', 'onblur' => 'this.value=this.value.replace(/-/g,"")'))
						); ?>
					</td>
					<td>
						<?php echo $this->Html->div(
							'owner1_dob',
							$this->Form->label('owner1_dob', 'Date of Birth *') .
							$this->Form->text('owner1_dob')
						); ?>
					</td>
				</tr>
			</table>
		</td>
		<td>
			<table>
				<tr><th colspan="3" style="white-space: nowrap;">
					<?php echo $this->Html->div(
						'owner2_percentage',
						$this->Form->label('owner2_percentage', 'OWNER / OFFICER (2) Percentage Ownership ', array('style' => 'display: inline;')) .
						$this->Form->text('owner2_percentage') . ' %'
					); ?>
				</th></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'owner2_fullname',
						$this->Form->label('owner2_fullname', 'Full Name') .
						$this->Form->text('owner2_fullname')
					); ?>
				</td></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'owner2_title',
						$this->Form->label('owner2_title', 'Title') .
						$this->Form->text('owner2_title')
					); ?>
				</td></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'owner2_address',
						$this->Form->label('owner2_address', 'Address') .
						$this->Form->text('owner2_address')
					); ?>
				</td></tr>
				<tr>
					<td>
						<?php echo $this->Html->div(
							'owner2_city',
							$this->Form->label('owner2_city', 'City') .
							$this->Form->text('owner2_city')
						); ?>
					</td>
					<td>
						<?php echo $this->Html->div(
							'owner2_state',
							$this->Form->label('owner2_state', 'State') .
							$this->Form->select('owner2_state', $states)
						); ?>
					</td>
					<td>
						<?php echo $this->Html->div(
							'owner2_zip',
							$this->Form->label('owner2_zip', 'Zip') .
							$this->Form->text('owner2_zip')
						); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $this->Html->div(
							'owner2_phone',
							$this->Form->label('owner2_phone', 'Phone') .
							$this->Form->text('owner2_phone')
						); ?>
					</td>
					<td>
						<?php echo $this->Html->div(
							'owner2_fax',
							$this->Form->label('owner2_fax', 'Fax') .
							$this->Form->text('owner2_fax')
						); ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'owner2_email',
						$this->Form->label('owner2_email', 'Email') .
						$this->Form->text('owner2_email')
					); ?>
				</td></tr>
				<tr>
					<td colspan="2">
						<?php echo $this->Html->div(
							'owner2_ssn',
							$this->Form->label('owner2_ssn', 'SSN') .
							$this->Form->text('owner2_ssn', array('maxlength'=> '11', 'onblur' => 'this.value=this.value.replace(/-/g,"")'))
						); ?>
					</td>
					<td>
						<?php echo $this->Html->div(
							'owner2_dob',
							$this->Form->label('owner2_dob', 'Date of Birth') .
							$this->Form->text('owner2_dob')
						); ?>
					</td>
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
