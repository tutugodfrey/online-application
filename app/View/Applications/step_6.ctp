<?php
	echo $this->Html->css('step_6', null, array('inline' => false));

	if (isset($errors) && is_array($errors)) {
		echo $this->Html->scriptBlock("var errors = " . json_encode($errors) . ";", array('inline' => false));
	}
?>

<?php if (in_array($this->request->data['Application']['status'], array('completed', 'signed'))): ?><h3 class="center" style="color: #4D4D4D;">Note: This application has been marked as completed and is read-only.</h3><?php endif; ?>

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

<h4>Step 6 of 6 | Merchant Referral Program</h4>

<?php
	if (isset($errors) && is_array($errors)) {
		echo '<h4 class="error">Please correct the specified errors (labeled in red)</h4>';
	}

	echo $this->Form->create('Application', array('url' => '/applications/add/' . $step . '/' . $id . '/' . $hash, 'novalidate' => true));
	echo $this->Form->hidden('id', array('value' => $id));
?>
<h4>Any successful referrals will result in $100 credit to Merchant's bank account provided. Visit our <?php echo $this->Html->link('referral program page', 'http://www.axiapayments.com/products-services/referral-program/', array('target' => '_blank')); ?> for details.</h4>

<table>
	<tr>
		<td>
			<?php echo $this->Html->div(
				'referral1_business',
				$this->Form->label('referral1_business', 'Referral Business #1') .
				$this->Form->text('referral1_business')
			); ?>
		</td>
		<td>
			<?php echo $this->Html->div(
				'referral1_owner_officer',
				$this->Form->label('referral1_owner_officer', 'Owner/Officer') .
				$this->Form->text('referral1_owner_officer')
			); ?>
		</td> 
		<td>
			<?php echo $this->Html->div(
				'referral1_phone',
				$this->Form->label('referral1_phone', 'Phone #') .
				$this->Form->text('referral1_phone')
			); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $this->Html->div(
				'referral2_business',
				$this->Form->label('referral2_business', 'Referral Business #2') .
				$this->Form->text('referral2_business')
			); ?>
		</td>
		<td>
			<?php echo $this->Html->div(
				'referral2_owner_officer',
				$this->Form->label('referral2_owner_officer', 'Owner/Officer') .
				$this->Form->text('referral2_owner_officer')
			); ?>
		</td>
		<td>
			<?php echo $this->Html->div(
				'referral2_phone',
				$this->Form->label('referral2_phone', 'Phone #') .
				$this->Form->text('referral2_phone')
			); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $this->Html->div(
				'referral3_business',
				$this->Form->label('referral3_business', 'Referral Business #3') .
				$this->Form->text('referral3_business')
			); ?>
		</td>
		<td>
			<?php echo $this->Html->div(
				'referral3_owner_officer',
				$this->Form->label('referral3_owner_officer', 'Owner/Officer') .
				$this->Form->text('referral3_owner_officer')
			); ?>
		</td>
		<td>
			<?php echo $this->Html->div(
				'referral3_phone',
				$this->Form->label('referral3_phone', 'Phone #') .
				$this->Form->text('referral3_phone')
			); ?>
		</td>
	</tr>
</table>

<p><hr /></p>
<h4>Step 6 of 6 | Validate Application</h4>
<?php if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))): ?>

<h3>Rep only</h3>

<?php
	echo $this->Html->div(
		'rep_contractor_name',
		$this->Form->label('rep_contractor_name', 'Contractor Name:', array('style' => 'display: inline;')) . ' ' .
		$this->Form->text('rep_contractor_name')
	);
?>

&nbsp;

<table>
	<tr>
		<th colspan="4">Schedule of Fees Part I</th>
	</tr>
	<tr>
		<td class="fees_rate_discount"><?php echo $this->Form->label('fees_rate_discount', 'Rate Discount'); ?></td>
		<td class="fees_rate_discount"><?php echo $this->Form->text('fees_rate_discount', array('style' => 'width: 50px !important;', 'onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')) . '%'; ?> &nbsp;</td>
		<td class="fees_rate_structure"><?php echo $this->Form->label('fees_rate_structure', 'Rate Structure'); ?></td>
		<?php
		$standardRate = array(
			'Interchange Pass Thru' => 'Interchange Pass Thru',
			'Downgrades At Cost' => 'Downgrades At Cost',
			'Cost Plus' => 'Cost Plus',
			'Bucketed' => 'Bucketed',
			'Bucketed (Rewards)' => 'Bucketed (Rewards)',
			'Simply Swipe It Rates' => 'Simply Swipe It Rates',
		);
		$flatRate = array('Flat Rate' => 'Flat Rate');
		if ($flatrate === true) {
			$rateStructure = array_merge($standardRate, $flatRate);
		} else {
			$rateStructure = $standardRate;
		}
		?>
		
		<td class="fees_rate_structure"><?php echo $this->Form->select(
				'fees_rate_structure',
				$rateStructure
		); ?></td>
	</tr>
</table>

<table>
	<tr class="fees_qualification_exemptions">
		<td><?php echo $this->Form->label('fees_qualification_exemptions', 'Qualification Exemptions'); ?></td>
		<?php
		$standardExemptions = array(
					'Visa/MC Interchange at Pass Thru' => 'Visa/MC Interchange at Pass Thru',
					'Non-Qualified Transactions at Additional Visa/MC Cost Based on Regulated Check Cards' => 'Non-Qualified Transactions at Additional Visa/MC Cost Based on Regulated Check Cards',
					'Non-Qualified Transactions at Additional Visa/MC Cost Based on Qualified Consumer Cards' => 'Non-Qualified Transactions at Additional Visa/MC Cost Based on Qualified Consumer Cards',
					'Non-Qualified Transactions at Additional Visa/MC Cost Based on Non-Regulated Qualified Check Cards' => 'Non-Qualified Transactions at Additional Visa/MC Cost Based on Non-Regulated Qualified Check Cards',
					'Visa/MC Cost Plus 0.05%' => 'Visa/MC Cost Plus 0.05%',
					'Visa/MC Cost Plus 0.10%' => 'Visa/MC Cost Plus 0.10%',
					'Visa/MC Cost Plus 0.15%' => 'Visa/MC Cost Plus 0.15%',
					'Visa/MC Cost Plus 0.20%' => 'Visa/MC Cost Plus 0.20%',
					'Visa/MC Cost Plus 0.25%' => 'Visa/MC Cost Plus 0.25%',
					'Visa/MC Cost Plus 0.30%' => 'Visa/MC Cost Plus 0.30%',
					'Visa/MC Cost Plus 0.35%' => 'Visa/MC Cost Plus 0.35%',
					'Visa/MC Cost Plus 0.40%' => 'Visa/MC Cost Plus 0.40%',
					'Visa/MC Cost Plus 0.45%' => 'Visa/MC Cost Plus 0.45%',
					'Visa/MC Cost Plus 0.50%' => 'Visa/MC Cost Plus 0.50%',
					'Visa/MC Cost Plus 0.55%' => 'Visa/MC Cost Plus 0.55%',
					'Visa/MC Cost Plus 0.60%' => 'Visa/MC Cost Plus 0.60%',
					'Visa/MC Cost Plus 0.65%' => 'Visa/MC Cost Plus 0.65%',
					'Visa/MC Cost Plus 0.70%' => 'Visa/MC Cost Plus 0.70%',
					'Visa/MC Cost Plus 0.75%' => 'Visa/MC Cost Plus 0.75%',
					'(SSI) RATE 2: Keyed: 0.40% Keyed Rewards: 0.75% Mid-Qual: 0.95% Bus: 1.15% Non-Qual: 1.90%' => '(SSI) RATE 2: Keyed: 0.40% Keyed Rewards: 0.75% Mid-Qual: 0.95% Bus: 1.15% Non-Qual: 1.90%',
					'RATE 2:  0.45%            RATE 3:  1.15% + $0.10             BUS 1:  1.05% + $0.10            BUS 2:  1.95% + $0.10' => 'RATE 2:  0.45%            RATE 3:  1.15% + $0.10             BUS 1:  1.05% + $0.10            BUS 2:  1.95% + $0.10',
					'RATE 2:  0.85%            RATE 3:  1.79% + $0.10             BUS 1:  1.15% + $0.10            BUS 2:  1.75% + $0.10' => 'RATE 2:  0.85%            RATE 3:  1.79% + $0.10             BUS 1:  1.15% + $0.10            BUS 2:  1.75% + $0.10',
					'REWARDS:  0.36%            MID:  0.85%             BUS 1:  1.15% + $0.10               NON:  1.79% + $0.10         ' => 'REWARDS:  0.36%            MID:  0.85%             BUS 1:  1.15% + $0.10               NON:  1.79% + $0.10         ',
				);
		$flatRateExemption = array('Flat Rate' => 'Flat Rate');
		if ($flatrate === true) {
			$feesExemptions = array_merge($standardExemptions, $flatRateExemption);
		} else {
			$feesExemptions = $standardExemptions;
		}
		?>
		<td><?php echo $this->Form->select(
				'fees_qualification_exemptions',
				$feesExemptions,
				array('style' => 'width: 575px;')
		); ?></td>
	</tr>
</table>

&nbsp;

<table class="fees_part_two">
	<tr>
		<th colspan="4">Schedule of Fees Part II</th>
	</tr>
	<tr>
		<td valign="top">
			<table>
				<tr>
					<th colspan="2">Start Up Fees</th>
				</tr>
				<tr class="fees_startup_application">
					<td><?php echo $this->Form->label('fees_startup_application', 'Application'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_startup_application', array('onkeyup'=>'addStartUpFees()','onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_startup_equipment">
					<td><?php echo $this->Form->label('fees_startup_equipment', 'Equipment'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_startup_equipment', array('onkeyup'=>'addStartUpFees()','onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_startup_expedite">
					<td><?php echo $this->Form->label('fees_startup_expedite', 'Expedite'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_startup_expedite', array('onkeyup'=>'addStartUpFees()','onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_startup_reprogramming">
					<td><?php echo $this->Form->label('fees_startup_reprogramming', 'Reprogramming'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_startup_reprogramming', array('onkeyup'=>'addStartUpFees()','onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_startup_training">
					<td><?php echo $this->Form->label('fees_startup_training', 'Training'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_startup_training', array('onkeyup'=>'addStartUpFees()','onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_startup_wireless_activation">
					<td><?php echo $this->Form->label('fees_startup_wireless_activation', 'Wireless Activation &nbsp; &nbsp; &nbsp; '); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_startup_wireless_activation', array('onkeyup'=>'addStartUpFees()','onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_startup_tax">
					<td><?php echo $this->Form->label('fees_startup_tax', 'Tax (CA only)'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_startup_tax', array('value'=>' (TBD)', 'disabled'=>'true')); ?></td>
				</tr>
				<tr class="fees_startup_total">
					<td><?php echo $this->Form->label('fees_startup_total', 'Total (not including CA Tax)'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_startup_total', array('onkeyup'=>'addStartUpFees()')); ?></td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<table>
				<tr>
					<th colspan="2">Authorization Fees</th>
				</tr>
				<tr class="fees_auth_transaction">
					<td><?php echo $this->Form->label('fees_auth_transaction', 'Visa/MC/JCB/DISC & Batch'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_auth_transaction', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_auth_amex">
					<td><?php echo $this->Form->label('fees_auth_amex', 'American Express'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_auth_amex', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_auth_aru_voice">
					<td><?php echo $this->Form->label('fees_auth_aru_voice', 'ARU & Voice Authorization'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_auth_aru_voice', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_auth_wireless">
					<td><?php echo $this->Form->label('fees_auth_wireless', 'Wireless'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_auth_wireless', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<table>
				<tr>
					<th colspan="2">Monthly Fees</th>
				</tr>
				<tr class="fees_monthly_statement">
					<td><?php echo $this->Form->label('fees_monthly_statement', 'Statement'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_monthly_statement',array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_monthly_minimum">
					<td><?php echo $this->Form->label('fees_monthly_minimum', 'Monthly Minimum'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_monthly_minimum', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_monthly_debit_access">
					<td><?php echo $this->Form->label('fees_monthly_debit_access', 'Debit Access'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_monthly_debit_access', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_monthly_ebt">
					<td><?php echo $this->Form->label('fees_monthly_ebt', 'EBT Access'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_monthly_ebt', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_monthly_gateway_access">
					<td><?php echo $this->Form->label('fees_monthly_gateway_access', 'Gateway Access'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_monthly_gateway_access', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_monthly_wireless_access">
					<td><?php echo $this->Form->label('fees_monthly_wireless_access', 'Wireless Access'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_monthly_wireless_access', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<table>
				<tr>
					<th colspan="2">Miscellaneous Fees</th>
				</tr>
				<tr class="fees_misc_annual_file">
					<td><?php echo $this->Form->label('fees_misc_annual_file', 'Annual File Fee'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_misc_annual_file', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_misc_chargeback">
					<td><?php echo $this->Form->label('fees_misc_chargeback', 'Chargeback'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_misc_chargeback', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table>
				<tr>
					<th colspan="2">PIN Debit Fees</th>
				</tr>
				<tr class="fees_pin_debit_auth">
					<td><?php echo $this->Form->label('fees_pin_debit_auth', 'PIN Debit Authorization'); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_pin_debit_auth', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_pin_debit_discount">
					<td><?php echo $this->Form->label('fees_pin_debit_discount', 'PIN Debit Discount'); ?></td>
					<td><?php echo $this->Form->text('fees_pin_debit_discount', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')) . '%'; ?></td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<table>
				<tr>
					<th colspan="2">EBT Fees</th>
				</tr>
				<tr class="fees_ebt_auth">
					<td><?php echo $this->Form->label('fees_ebt_auth', 'EBT Authorization&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; '); ?></td>
					<td><?php echo '$' . $this->Form->text('fees_ebt_auth', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')); ?></td>
				</tr>
				<tr class="fees_ebt_discount">
					<td><?php echo $this->Form->label('fees_ebt_discount', 'EBT Discount'); ?></td>
					<td><?php echo $this->Form->text('fees_ebt_discount', array('onblur'=>'this.value=(!isNaN(Number(this.value))) ? Number(this.value).toFixed(2) : "0.00" ;')) . '%'; ?></td>
				</tr>
			</table>
		</td>
		<td></td>
		<td></td>
	</tr>
</table>

<p><?php echo $this->Html->div(
	'rep_discount_paid radio',
	'<span style="font-size: 13px;">Discount Paid</span> ' .
	$this->Form->radio('rep_discount_paid', array('monthly' => 'Monthly', 'daily' => 'Daily'), array('legend' => false))
); ?></p>

<p><?php echo $this->Html->div(
	'rep_amex_discount_rate',
	$this->Form->label('rep_amex_discount_rate', 'Amex Discount Rate:', array('style' => 'display: inline;')) . ' ' .
	$this->Form->text('rep_amex_discount_rate', array('style' => 'width: 50px !important;'))
); ?></p>

<p><hr /></p>

<h4>Site Inspection Information</h4>

<?php
	echo $this->Html->div(
		'rep_business_legitimate radio2',
		'<span>Does business appear legitimate?</span> ' .
		$this->Form->radio('rep_business_legitimate', array('yes' => 'Yes ', 'no' => 'No'), array('legend' => false))
	);
	echo $this->Html->div(
		'rep_photo_included radio2',
		'<span>Is site photo included with this application?</span> ' .
		$this->Form->radio('rep_photo_included', array('yes' => 'Yes ', 'no' => 'No'), array('legend' => false))
	);
	echo $this->Html->div(
		'rep_inventory_sufficient radio2',
		'<span>Is inventory sufficient for Business Type?</span> ' .
		$this->Form->radio('rep_inventory_sufficient', array('yes' => 'Yes ', 'no' => 'No'), array('legend' => false))
	);
	echo $this->Html->div(
		'rep_goods_delivered radio2',
		'<span>Are goods and services delivered at time of sale?</span> ' .
		$this->Form->radio('rep_goods_delivered', array('yes' => 'Yes ', 'no' => 'No'), array('legend' => false))
	);
	echo $this->Html->div(
		'rep_bus_open_operating radio2',
		'<span>Is business open and operating?</span> ' .
		$this->Form->radio('rep_bus_open_operating', array('yes' => 'Yes ', 'no' => 'No'), array('legend' => false))
	);
	echo $this->Html->div(
		'rep_visa_mc_decals_visible radio2',
		'<span>Are Visa and MasterCard decals visible?</span> ' .
		$this->Form->radio('rep_visa_mc_decals_visible', array('yes' => 'Yes ', 'no' => 'No'), array('legend' => false))
	);
	echo $this->Html->div(
		'rep_mail_tel_activity radio2',
		'<span>Any mail/telephone order sales activity?</span> ' .
		$this->Form->radio('rep_mail_tel_activity', array('yes' => 'Yes ', 'no' => 'No'), array('legend' => false))
	);
	?>
<p>
	<?php  echo $this->Html->div(
		'site_survey_signature',
		$this->Form->label('site_survey_signature', 'Please type name to confirm if you visted the site: ', array('style' => 'display: inline;')) . ' ' .
		$this->Form->text('site_survey_signature', array('style' => 'width: 50px;'))
	);
?>
</p>
<p><hr /></p>

<?php endif; ?>

<?php
	if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) echo $this->Form->end('Save Application');
	else {
		switch ($this->request->data['Application']['status']) {
			//case 'pending':
			case 'completed':
			   echo $this->Form->end('Save Application');
				break;
			default:
	   $repNotify = Router::url(array('controller' => 'applications', 'action' => 'rep_notify', $this->request->data['Application']['id']));
		/*
				echo $this->Html->scriptBlock("            
			function repNotify() {
				window.location = '{$repNotify}';
			}
		");
				 echo "<input type=\"button\" onclick=\"repNotify();\" value=\"Submit for Review\">";
		 
		 */
				echo $this->Form->end('Submit for Review');
		}
	}
	if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager')) && ($this->request->data['Application']['status'] == 'saved' || $this->request->data['Application']['status'] == 'validate' )) {
		//echo "\t\t\t" . $this->Html->link(__('Email App For Field Completion'), array('controller' => 'applications', 'action' => 'complete_fields', 'admin' => false, $this->request->data['Application']['id']), array('type' => 'button') , __('Send for completion to: ') . $this->request->data['Application']['owner1_email']) . "\n";
		$url = Router::url(array('controller' => 'applications', 'action' => 'complete_fields', 'admin' => false, $this->request->data['Application']['id']));
		echo $this->Html->scriptBlock("
			function fieldCompletion() {
			if (confirm('Send for completion to: " . $this->request->data['Application']['owner1_email'] . "'))
				{load_sequence();
				window.location = '{$url}';}
				}"
				);
		
	}
	if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
		$url = Router::url(array('controller' => 'applications', 'action' => 'send_document', $this->request->data['Application']['id'], False));
		//echo $url;
		//echo $this->request->data['Application']['rs_document_guid'];
		echo $this->Html->scriptBlock("
			function load_sequence() {
				document.getElementById('loadIMG').style.display='block';
				document.getElementById('loading_box').style.display='block';
				document.getElementById('msg_faders').style.display='block';
				}
			function submit_for_signature() {
				
				if (". (isset($errors) && is_array($errors) ? '1' : '0') .") {
					alert('The application must be saved with all required fields completed before submitting for signature.');
					return null;
				}
				else if (" . ($this->request->data['Application']['status'] == 'signed' ? '1' : '0') . ") {
					answer = confirm('This application has aleady been signed. Do you really want to resend?');
					if (!answer) return null
				}
				else if (" . ($this->request->data['Application']['rs_document_guid'] ? '1' : '0') . ") {
					answer = confirm('This application has aleady been sent for signature. Do you really want to send it again?');
					if (!answer) return null
				}
				window.location = '{$url}';
			}
		");
		 
	   // $signNowURL = Router::url(array('controller' => 'applications', 'action' => 'in_person', $this->request->data['Application']['id']));
		$signNowURL = Router::url(array('controller' => 'applications', 'action' => 'send_document', $this->request->data['Application']['id'], True));
		
		//echo $signNowURL; 
		echo $this->Html->scriptBlock("
			function signDocNow() {
			   
			   if (". (isset($errors) && is_array($errors) ? '1' : '0') .") {
					alert('The application must be saved with all required fields completed before submitting for signature.');
					return null;
				}
				else if (" . ($this->request->data['Application']['status'] == 'signed' ? '1' : '0') . ") {
					answer = confirm('This application has aleady been signed.');
					return null;
				}
				
				/*else if (" . ($this->request->data['Application']['rs_document_guid'] ? '1' : '0') . ") {
					answer = confirm('This application has aleady been sent for signature. Do you really want to send it again?');
					if (!answer) return null
				}*/

				window.location = '{$signNowURL}';

			}
			 
			/*This function calculates the total Application Start up fees and is designed to work 
			* even if more start up fee input fields are added onto the page as long as the string in the
			* id attribue is consistent.
			* patt1: A RegEx pattern used to locate the startup fees input element based its id attribute    
			*/
		   function addStartUpFees(){
			var patt1=/Startup/gi; 
			var total = 0.00;
			var inputElements = document.getElementsByTagName('input')
				   for(x=0; x < inputElements.length; x++){
					   if(inputElements[x].id.match(patt1) && inputElements[x].id !='ApplicationFeesStartupTotal' && inputElements[x].id !='ApplicationFeesStartupTax'){                       
							//If not a number set current element's value to zero
							if (isNaN(Number(inputElements[x].value)))
							inputElements[x].value = 0;
							if (!isNaN(inputElements[x].value))
							var numVal = Number(inputElements[x].value);                            
							total = total + numVal;
						}                
					}
			document.getElementById('ApplicationFeesStartupTotal').value = total.toFixed(2);
		  }     
				
		");
		echo "<br/>Please Save Application before submitting this application for Signature <br/><br/>";
		echo "<input type=\"button\" onclick=\"fieldCompletion();\" value=\"Email For Field Completion\">" . "<br/>";
		echo "<input type=\"button\" onclick=\"submit_for_signature();\" value=\"Submit for Signature\">" . "<br/>";
		echo "<input type=\"button\" onclick=\"signDocNow();\" value=\"View and Sign Now\">";
		echo $this->Html->link(
			'Return to Applications Admin',
			'/admin/applications/',
			array('style' => 'display: block; float: right;')
		);
	}

	echo $this->Html->scriptBlock("
		$(document).ready(function() {
			if (". (is_array($errors) ? '1' : '0') .") {
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
<div id="msg_faders" >&nbsp;</div>
<div id="loading_box" style=" text-align: center;" >
	<h2>Generating Application</h2>
	<p style="color:black ">Please wait while the application is generated and emailed.</p>
	<img src="/img/refreshing.gif" id="loadIMG" />
</div>