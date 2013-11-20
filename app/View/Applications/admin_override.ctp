<div class="onlineappApplications form">
<?php echo $this->Form->create('Application');?>
	<fieldset>
		<legend><?php echo __('Admin Override Online Application'); ?></legend>
	<?php
                echo 'Rep Name: '. $users[$applications['Application']['user_id']] . '<br/>';
                debug($applications['Application']['rs_document_guid']);
                echo 'Merchant: ' . $applications['Application']['dba_business_name'];
		echo $this->Form->input('id');
		//echo $this->Form->input('onlineapp_application_id', array('options' => array($Applications), 'default' => $this->request->data['Coversheet']['onlineapp_application_id']));
		echo $this->Form->input('user_id', array('label' => 'User Name','options' => array($users),'default' => $applications['Application']['user_id']));
		//echo $this->Form->input('status', array('options' => array('saved' => 'Saved', 'validated' => 'Validated', 'sent' => 'Sent'), 'default' => $this->request->data['Coversheet']['status']));
                echo $this->Form->input('status', array('label' => 'Application Status','options' => array(
                        'saved'=>'saved',
                        'validate'=>'validate',
                        'completed'=>'completed',
                        'pending'=>'pending',
                        'signed'=>'signed'
                    ), 'default' => $applications['Application']['status']));
                echo $this->Form->input('rs_document_guid', array('label' => 'RightSignature Document GUID'));
                echo $this->Form->input('var_status', array('label' => 'Install Sheet (Var) Status'));
                echo $this->Form->input('install_var_rs_document_guid', array('label' => 'Install Sheet (Var) RightSignature Document GUID'));
                /*
		echo $this->Form->input('hash');
		echo $this->Form->input('setup_existing_merchant');
		//echo $this->Form->input('setup_banking');
                echo "Attached: " . $this->Form->input('setup_banking', array('label' => "Banking (no starter checks)"));
		echo $this->Form->input('setup_statements');
		//echo $this->Form->input('setup_drivers_license');
                echo $this->Form->input('setup_drivers_license', array('label' => "Owner's Driver's License"));
		echo $this->Form->input('setup_new_merchant');
		echo $this->Form->input('setup_business_license');
		echo $this->Form->input('setup_other');
		echo $this->Form->input('setup_field_other');
		echo $this->Form->input('setup_tier3');
		echo $this->Form->input('setup_tier4');
		echo $this->Form->input('setup_tier5_financials');
		echo $this->Form->input('setup_tier5_processing_statements');
		echo $this->Form->input('setup_tier5_bank_statements');
		echo $this->Form->input('setup_equipment_terminal');
		echo $this->Form->input('setup_equipment_gateway');
		echo $this->Form->input('setup_install_rep');
		echo $this->Form->input('setup_install_axia');
		echo $this->Form->input('setup_install_pos');
		echo $this->Form->input('setup_starterkit_rep');
		echo $this->Form->input('setup_starterkit_axia');
		echo $this->Form->input('setup_equipment_payment_ach');
		echo $this->Form->input('setup_equipment_payment_lease');
		echo $this->Form->input('setup_lease_price');
		echo $this->Form->input('setup_lease_months');
		echo $this->Form->input('setup_existing_amex');
		echo $this->Form->input('setup_debit_volume');
		echo $this->Form->input('setup_item_count');
		echo $this->Form->input('setup_referrer');
		echo $this->Form->input('setup_referrer_gp');
		echo $this->Form->input('setup_referrer_bp');
		echo $this->Form->input('setup_referrer_pct');
		echo $this->Form->input('setup_reseller');
		echo $this->Form->input('setup_reseller_gp');
		echo $this->Form->input('setup_reseller_bp');
		echo $this->Form->input('setup_reseller_pct');
		echo $this->Form->input('setup_notes');
		echo $this->Form->input('cp_encrypted_sn');
		echo $this->Form->input('cp_pinpad_ra_attached');
		echo $this->Form->input('cp_giftcards');
		echo $this->Form->input('cp_check_guarantee');
		echo $this->Form->input('cp_check_guarantee_info');
		echo $this->Form->input('cp_pos');
		echo $this->Form->input('cp_pos_contact');
		echo $this->Form->input('micros_ip');
		echo $this->Form->input('micros_dial');
		echo $this->Form->input('micros_bill_statement');
		echo $this->Form->input('micros_bill_merchant');
		echo $this->Form->input('micros_bill_rep');
		echo $this->Form->input('gateway_option_1');
		echo $this->Form->input('gateway_option_2');
		echo $this->Form->input('gateway_package_silver');
		echo $this->Form->input('gateway_package_gold');
		echo $this->Form->input('gateway_package_gold_cust_db');
		echo $this->Form->input('gateway_package_gold_fraud');
		echo $this->Form->input('gateway_package_platinum');
		echo $this->Form->input('gateway_epay');
		echo $this->Form->input('gateway_bill_statement');
		echo $this->Form->input('gateway_bill_merchant');
		echo $this->Form->input('gateway_bill_rep');
		echo $this->Form->input('moto_online_chd');
		echo $this->Form->input('moto_developer');
		echo $this->Form->input('moto_company');
		echo $this->Form->input('moto_gateway');
		echo $this->Form->input('moto_contact');
		echo $this->Form->input('moto_phone');
		echo $this->Form->input('moto_email');*/
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Application.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Application.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List %s', __('Coversheets')), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List %s', __('Applications')), array('controller' => 'applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'applications', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
