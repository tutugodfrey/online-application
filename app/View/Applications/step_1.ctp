<?php
	echo $this->Html->css('step_1', null, array('inline' => false));

	$states = array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York", 'NC'=>"North Carolina", 'ND'=>"North Dakota", 'OH'=>"Ohio", 'OK'=>"Oklahoma", 'OR'=>"Oregon", 'PA'=>"Pennsylvania", 'RI'=>"Rhode Island", 'SC'=>"South Carolina", 'SD'=>"South Dakota", 'TN'=>"Tennessee", 'TX'=>"Texas", 'UT'=>"Utah", 'VT'=>"Vermont", 'VA'=>"Virginia", 'WA'=>"Washington", 'WV'=>"West Virginia", 'WI'=>"Wisconsin", 'WY'=>"Wyoming");

	/*
	TODO: UNDECIDED ABOUT ERROR HANDLING FOR NOW
	if (is_array($errors)) {
		$camelized_errors = array();
		foreach ($errors as $k => $v) {
			$camelized_errors[Inflector::camelize($k)] = $v;
		}
		echo $this->Html->scriptBlock("var errors = " . json_encode($camelized_errors) . ";", array('inline' => false));
	}*/

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

<h4>Step 1 of 6 | General Information</h4>

<?php
	if (isset($errors) && is_array($errors)) {
		echo '<h4 class="error">Please correct the specified errors (labeled in red)</h4>';
	}
	
	echo $this->Form->create('Application', array('url' => '/applications/add/' . $step . '/' . ($id ? $id : '0') . '/' . $hash, 'novalidate' => true));
	echo $this->Form->hidden('id', array('value' => $id));
?>


<?php /* echo $this->Html->div(
	'ownership_type radio',
	'<div>' . $this->Form->radio('ownership_type', array('Corporation', 'Sole Prop', 'LLC', 'Partnership', 'Non Profit/Tax Exempt (fed form 501C)', 'Other'), array('legend' => false)) . '</div>'
); */ ?>

<table class="radios ownership_type">
	<tr><th colspan="3">OWNERSHIP TYPE *</th></tr>
	<tr>
		<td>
			<input type="radio" name="data[Application][ownership_type]" id="ApplicationOwnershipType0" value="corporation" <?php echo ($this->request->data['Application']['ownership_type'] == 'corporation' ? 'checked="checked"' : ''); ?>>
			<label for="ApplicationOwnershipType0">Corporation</label>
		</td><td>
			<input type="radio" name="data[Application][ownership_type]" id="ApplicationOwnershipType1" value="sole prop" <?php echo ($this->request->data['Application']['ownership_type'] == 'sole prop' ? 'checked="checked"' : ''); ?>>
			<label for="ApplicationOwnershipType1">Sole Prop</label>
		</td><td>
			<input type="radio" name="data[Application][ownership_type]" id="ApplicationOwnershipType2" value="llc" <?php echo ($this->request->data['Application']['ownership_type'] == 'llc' ? 'checked="checked"' : ''); ?>>
			<label for="ApplicationOwnershipType2">LLC</label>
		</td>
	</tr>
	<tr>
		<td>
			<input type="radio" name="data[Application][ownership_type]" id="ApplicationOwnershipType3" value="partnership" <?php echo ($this->request->data['Application']['ownership_type'] == 'partnership' ? 'checked="checked"' : ''); ?>>
			<label for="ApplicationOwnershipType3">Partnership</label>
		</td><td>
			<input type="radio" name="data[Application][ownership_type]" id="ApplicationOwnershipType4" value="non profit" <?php echo ($this->request->data['Application']['ownership_type'] == 'non profit' ? 'checked="checked"' : ''); ?>>
			<label for="ApplicationOwnershipType4">Non Profit/Tax Exempt (fed form 501C)</label>
		</td><td>
			<input type="radio" name="data[Application][ownership_type]" id="ApplicationOwnershipType5" value="other" <?php echo ($this->request->data['Application']['ownership_type'] == 'other' ? 'checked="checked"' : ''); ?>>
			<label for="ApplicationOwnershipType5">Other</label>
		</td>
	</tr>
</table>

<p><hr /></p>

<table cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table>
				<tr><th colspan="3">CORPORATE INFORMATION</th></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'legal_business_name',
						$this->Form->label('legal_business_name', 'Legal Business Name *') .
						$this->Form->text('legal_business_name')
					); ?>
				</td></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'mailing_address',
						$this->Form->label('mailing_address', 'Mailing Address *') .
						$this->Form->text('mailing_address')
					); ?>
				</td></tr>
				<tr>
					<td><?php echo $this->Html->div(
						'mailing_city',
						$this->Form->label('mailing_city', 'City *') .
						$this->Form->text('mailing_city')
					); ?></td>
					<td><?php echo $this->Html->div(
						'mailing_state',
						$this->Form->label('mailing_state', 'State *') .
						//$this->Form->select('mailing_state', $states, null)
							$this->Form->select('mailing_state', $states)
					); ?></td>
					<td><?php echo $this->Html->div(
						'mailing_zip',
						$this->Form->label('mailing_zip', 'Zip *') .
						$this->Form->text('mailing_zip')
					); ?></td>
				</tr>
				<tr>
					<td><?php echo $this->Html->div(
						'mailing_phone',
						$this->Form->label('mailing_phone', 'Phone *') .
						$this->Form->text('mailing_phone')
					); ?></td>
					<td><?php echo $this->Html->div(
						'mailing_fax',
						$this->Form->label('mailing_fax', 'Fax *') .
						$this->Form->text('mailing_fax')
					); ?></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="2"><?php echo $this->Html->div(
						'corp_contact_name',
						$this->Form->label('corp_contact_name', 'Corp Contact Name *') .
						$this->Form->text('corp_contact_name')
					); ?></td>
					<td><?php echo $this->Html->div(
						'corp_contact_name_title',
						$this->Form->label('corp_contact_name_title', 'Title *') .
						$this->Form->text('corp_contact_name_title')
					); ?></td>
				</tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'corporate_email',
						$this->Form->label('corporate_email', 'Email *') .
						$this->Form->text('corporate_email', array('type' => 'email'))
					); ?>
				</td></tr>
			</table>
		</td>
		<td>
			<table style="width: 353px;">
				<tr><th colspan="3">LOCATION INFORMATION <span class="loc_same_as_corp"><?php echo $this->Form->checkbox('loc_same_as_corp'); ?><label for="ApplicationLocSameAsCorp">Same As Corporate Information</label><span></th></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'dba_business_name',
						$this->Form->label('dba_business_name', 'Business Name (DBA) *') .
						$this->Form->text('dba_business_name')
					); ?>
				</td></tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'location_address',
						$this->Form->label('location_address', 'Location Address *') .
						$this->Form->text('location_address')
					); ?>
				</td></tr>
				<tr>
					<td><?php echo $this->Html->div(
						'location_city',
						$this->Form->label('location_city', 'City *') .
						$this->Form->text('location_city')
					); ?></td>
					<td><?php echo $this->Html->div(
						'location_state',
						$this->Form->label('location_state', 'State *') .
						//$this->Form->select('location_state', $states, null)
							$this->Form->select('location_state', $states)
					); ?></td>
					<td><?php echo $this->Html->div(
						'location_zip',
						$this->Form->label('location_zip', 'Zip *') .
						$this->Form->text('location_zip')
					); ?></td>
				</tr>
				<tr>
					<td><?php echo $this->Html->div(
						'location_phone',
						$this->Form->label('location_phone', 'Phone *') .
						$this->Form->text('location_phone')
					); ?></td>
					<td><?php echo $this->Html->div(
						'location_fax',
						$this->Form->label('location_fax', 'Fax *') .
						$this->Form->text('location_fax')
					); ?></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="2"><?php echo $this->Html->div(
						'loc_contact_name',
						$this->Form->label('loc_contact_name', 'Location Contact Name *') .
						$this->Form->text('loc_contact_name')
					); ?></td>
					<td><?php echo $this->Html->div(
						'loc_contact_name_title',
						$this->Form->label('loc_contact_name_title', 'Title *') .
						$this->Form->text('loc_contact_name_title')
					); ?></td>
					<td></td>
				</tr>
				<tr><td colspan="3">
					<?php echo $this->Html->div(
						'location_email',
						$this->Form->label('location_email', 'Email *') .
						$this->Form->text('location_email', array('type' => 'email'))
					); ?>
				</td></tr>
			</table>
		</td>
	</tr>
</table>

<table>
	<tr><td colspan="3">
		<?php echo $this->Html->div(
			'federal_taxid',
			$this->Form->label('federal_taxid', 'Federal Tax ID *') .
			$this->Form->text('federal_taxid', array('maxlength'=> '10', 'onblur' => 'this.value=this.value.replace(/-/g,"")'))
		); ?>
	</td></tr>
	<tr><td colspan="3">
		<?php echo $this->Html->div(
			'website',
			$this->Form->label('website', 'Website *') .
			$this->Form->text('website')
		); ?>
	</td></tr>
	<tr><td colspan="3">
		<?php echo $this->Html->div(
			'customer_svc_phone',
			$this->Form->label('customer_svc_phone', 'Customer Service Phone *') .
			$this->Form->text('customer_svc_phone')
		); ?>
	</td></tr>
	<tr>
		<td colspan="3">
			<table cellpadding="0" cellspacing="0">
				<tr><td>
					<?php echo $this->Html->div(
						'bus_open_date',
						$this->Form->label('bus_open_date', 'Business Open Date *') .
						$this->Form->text('bus_open_date')
					); ?>
				</td>
				<td style="padding-left: 9px;">
					<?php echo $this->Html->div(
						'length_current_ownership',
						$this->Form->label('length_current_ownership', 'Length of Current Ownership *') .
						$this->Form->text('length_current_ownership')
					); ?>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" valign="top">
			<?php echo $this->Html->div(
					'existing_axia_merchant radio2',
					$this->Form->label('existing_axia_merchant', 'Existing Axia Merchant? *') .
					$this->Form->radio('existing_axia_merchant', array('yes' => 'Yes', 'no' => 'No'), array('legend' => false))
			); ?>
		</td>
		<td style="padding-left: 10px;">
			<?php echo $this->Html->div(
				'current_mid_number',
				$this->Form->label('current_mid_number', 'Current MID #') .
				$this->Form->text('current_mid_number')
			); ?>
		</td>
	</tr>
	<tr><td colspan="3">
		<?php echo $this->Html->div(
			'general_comments',
			$this->Form->label('general_comments', 'General Comments') .
			$this->Form->textarea('general_comments')
		); ?>
	</td></tr>
</table>

<?php
	// LOCATION TYPE
/*    echo '<h4>LOCATION TYPE</h4>';
	echo $this->Html->div(
		'location_type radio',
		$this->Form->radio('location_type', array('Retail Store', 'Industrial', 'Trade', 'Office', 'Residence', 'Other'), array('legend' => false))
	); */
?>
<p><hr /></p>

<table class="radios location_type">
	<tr><th colspan="3">LOCATION TYPE *</th></tr>
	<tr>
		<td>
			<input type="radio" name="data[Application][location_type]" id="ApplicationLocationType0" value="retail store" <?php echo ($this->request->data['Application']['location_type'] == 'retail store' ? 'checked="checked"' : ''); ?>>
			<label for="ApplicationLocationType0">Retail Store</label>
		</td><td>
			<input type="radio" name="data[Application][location_type]" id="ApplicationLocationType1" value="industrial" <?php echo ($this->request->data['Application']['location_type'] == 'industrial' ? 'checked="checked"' : ''); ?>>
			<label for="ApplicationLocationType1">Industrial</label>
		</td><td>
			<input type="radio" name="data[Application][location_type]" id="ApplicationLocationType2" value="trade"  <?php echo ($this->request->data['Application']['location_type'] == 'trade' ? 'checked="checked"' : ''); ?>>
			<label for="ApplicationLocationType2">Trade</label>
		</td>
	</tr>
	<tr>
		<td>
			<input type="radio" name="data[Application][location_type]" id="ApplicationLocationType3" value="office"  <?php echo ($this->request->data['Application']['location_type'] == 'office' ? 'checked="checked"' : ''); ?>>
			<label for="ApplicationLocationType3">Office</label>
		</td><td>
			<input type="radio" name="data[Application][location_type]" id="ApplicationLocationType4" value="residence" <?php echo ($this->request->data['Application']['location_type'] == 'residence' ? 'checked="checked"' : ''); ?>>
			<label for="ApplicationLocationType4">Residence</label>
		</td><td>
			<input type="radio" name="data[Application][location_type]" id="ApplicationLocationType5" value="other" <?php echo ($this->request->data['Application']['location_type'] == 'other' ? 'checked="checked"' : ''); ?>>
			<label for="ApplicationLocationType5">Other</label>
			<?php //echo $this->request->data['Application']['location_type']; ?>
			<?php echo $this->Form->text('location_type_other'); ?>            
		</td>
	</tr>
</table>

<?php
	// MERCHANT
	echo '<br />&nbsp;'; 
	echo '<h4 style="margin-bottom: 5px;">MERCHANT</h4>';
	echo $this->Html->div(
		'merchant_status radio',
		$this->Form->radio('merchant_status', array('owns'=> 'Owns', 'leases' => 'Leases'), array('legend' => false))
	); 

?>

<br />

<table>
	<tr>
		<td><?php echo $this->Html->div(
			'landlord_name',
			$this->Form->label('landlord_name', 'Landlord Name') .
			$this->Form->text('landlord_name')
		); ?></td>
		<td><?php echo $this->Html->div(
			'landlord_phone',
			$this->Form->label('landlord_phone', 'Landlord Phone') .
			$this->Form->text('landlord_phone')
		); ?></td>
		
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
		$('#ApplicationLocSameAsCorp').change(function () {
			if (this.checked) {
				$('#ApplicationDbaBusinessName').val($('#ApplicationLegalBusinessName').val());
				$('#ApplicationLocationAddress').val($('#ApplicationMailingAddress').val());
				$('#ApplicationLocationCity').val($('#ApplicationMailingCity').val());
				$('#ApplicationLocationState').val($('#ApplicationMailingState').val());
				$('#ApplicationLocationZip').val($('#ApplicationMailingZip').val());
				$('#ApplicationLocationPhone').val($('#ApplicationMailingPhone').val());
				$('#ApplicationLocationFax').val($('#ApplicationMailingFax').val());
				$('#ApplicationLocContactName').val($('#ApplicationCorpContactName').val());
				$('#ApplicationLocContactNameTitle').val($('#ApplicationCorpContactNameTitle').val());
				$('#ApplicationLocationEmail').val($('#ApplicationCorporateEmail').val());
				// disable them all
				/* $('#ApplicationDbaBusinessName').prop('readonly', true);
				$('#ApplicationLocationAddress').prop('readonly', true);
				$('#ApplicationLocationCity').prop('readonly', true);
				$('#ApplicationLocationState').prop('readonly', true);
				$('#ApplicationLocationZip').prop('readonly', true);
				$('#ApplicationLocationPhone').prop('readonly', true);
				$('#ApplicationLocationFax').prop('readonly', true);
				$('#ApplicationLocContactName').prop('readonly', true);
				$('#ApplicationLocContactNameTitle').prop('readonly', true);
				$('#ApplicationLocationEmail').prop('readonly', true); */
				return;
			}

			//do stuff if the checkbox isn't checked
			/* $('#ApplicationDbaBusinessName').prop('readonly', false);
			$('#ApplicationLocationAddress').prop('readonly', false);
			$('#ApplicationLocationCity').prop('readonly', false);
			$('#ApplicationLocationState').prop('readonly', false);
			$('#ApplicationLocationZip').prop('readonly', false);
			$('#ApplicationLocationPhone').prop('readonly', false);
			$('#ApplicationLocationFax').prop('readonly', false);
			$('#ApplicationLocContactName').prop('readonly', false);
			$('#ApplicationLocContactNameTitle').prop('readonly', false);
			$('#ApplicationLocationEmail').prop('readonly', false); */
		});
	");

	echo $this->Html->scriptBlock("
		$(document).ready(function() {
			if ($('#ApplicationLocSameAsCorp').prop('checked')) {
				// disable them all
				/* $('#ApplicationDbaBusinessName').prop('readonly', true);
				$('#ApplicationLocationAddress').prop('readonly', true);
				$('#ApplicationLocationCity').prop('readonly', true);
				$('#ApplicationLocationState').prop('readonly', true);
				$('#ApplicationLocationZip').prop('readonly', true);
				$('#ApplicationLocationPhone').prop('readonly', true);
				$('#ApplicationLocationFax').prop('readonly', true);
				$('#ApplicationLocContactName').prop('readonly', true);
				$('#ApplicationLocContactNameTitle').prop('readonly', true);
				$('#ApplicationLocationEmail').prop('readonly', true); */
			}
			
			if (". (isset($errors) && is_array($errors) ? '1' : '0') .") {
				for (field in errors) {
					if ($('.' + field).length > 0) {
						if (field == 'ownership_type') {
							$('.' + field + ' th').filter(':first').css('color', '#f00');
						}
						else if (field == 'location_type') {
							$('.' + field + ' th').filter(':first').css('color', '#f00');
						}
						else if ($('.' + field + ' input').filter(':first').length) {
							$('.' + field + ' label').filter(':first').css('color', '#f00');
						}
						else if ($('.' + field + ' select').filter(':first').length) {
							$('.' + field + ' label').filter(':first').css('color', '#f00');
						}
//                        $('.' + field + ' input').filter(':first').css('background-color', '#ffc0cb');
					}
				}
			}
		});
	", array('inline' => false));
?>
