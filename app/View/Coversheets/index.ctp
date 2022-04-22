<div class="Coversheets index">
	<h2><?php echo __('Coversheets');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('cobranded_application_id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('status');?></th>
			<!--<th><?php echo $this->Paginator->sort('hash');?></th>
			<th><?php echo $this->Paginator->sort('setup_existing_merchant');?></th>
			<th><?php echo $this->Paginator->sort('setup_banking');?></th>
			<th><?php echo $this->Paginator->sort('setup_statements');?></th>
			<th><?php echo $this->Paginator->sort('setup_drivers_license');?></th>
			<th><?php echo $this->Paginator->sort('setup_new_merchant');?></th>
			<th><?php echo $this->Paginator->sort('setup_business_license');?></th>
			<th><?php echo $this->Paginator->sort('setup_other');?></th>
			<th><?php echo $this->Paginator->sort('setup_field_other');?></th>
			<th><?php echo $this->Paginator->sort('setup_tier3');?></th>
			<th><?php echo $this->Paginator->sort('setup_tier4');?></th>
			<th><?php echo $this->Paginator->sort('setup_tier5_financials');?></th>
			<th><?php echo $this->Paginator->sort('setup_tier5_processing_statements');?></th>
			<th><?php echo $this->Paginator->sort('setup_tier5_bank_statements');?></th>
			<th><?php echo $this->Paginator->sort('setup_equipment_terminal');?></th>
			<th><?php echo $this->Paginator->sort('setup_equipment_gateway');?></th>
			<th><?php echo $this->Paginator->sort('setup_install_rep');?></th>
			<th><?php echo $this->Paginator->sort('setup_install_axia');?></th>
			<th><?php echo $this->Paginator->sort('setup_install_pos');?></th>
			<th><?php echo $this->Paginator->sort('setup_starterkit_rep');?></th>
			<th><?php echo $this->Paginator->sort('setup_starterkit_axia');?></th>
			<th><?php echo $this->Paginator->sort('setup_equipment_payment_ach');?></th>
			<th><?php echo $this->Paginator->sort('setup_equipment_payment_lease');?></th>
			<th><?php echo $this->Paginator->sort('setup_lease_price');?></th>
			<th><?php echo $this->Paginator->sort('setup_lease_months');?></th>
			<th><?php echo $this->Paginator->sort('setup_existing_amex');?></th>
			<th><?php echo $this->Paginator->sort('setup_debit_volume');?></th>
			<th><?php echo $this->Paginator->sort('setup_item_count');?></th>
			<th><?php echo $this->Paginator->sort('setup_referrer');?></th>
			<th><?php echo $this->Paginator->sort('setup_referrer_gp');?></th>
			<th><?php echo $this->Paginator->sort('setup_referrer_bp');?></th>
			<th><?php echo $this->Paginator->sort('setup_referrer_pct');?></th>
			<th><?php echo $this->Paginator->sort('setup_reseller');?></th>
			<th><?php echo $this->Paginator->sort('setup_reseller_gp');?></th>
			<th><?php echo $this->Paginator->sort('setup_reseller_bp');?></th>
			<th><?php echo $this->Paginator->sort('setup_reseller_pct');?></th>
			<th><?php echo $this->Paginator->sort('setup_notes');?></th>
			<th><?php echo $this->Paginator->sort('cp_encrypted_sn');?></th>
			<th><?php echo $this->Paginator->sort('cp_pinpad_ra_attached');?></th>
			<th><?php echo $this->Paginator->sort('cp_giftcards');?></th>
			<th><?php echo $this->Paginator->sort('cp_check_guarantee');?></th>
			<th><?php echo $this->Paginator->sort('cp_check_guarantee_info');?></th>
			<th><?php echo $this->Paginator->sort('cp_pos');?></th>
			<th><?php echo $this->Paginator->sort('cp_pos_contact');?></th>
			<th><?php echo $this->Paginator->sort('micros_ip');?></th>
			<th><?php echo $this->Paginator->sort('micros_dial');?></th>
			<th><?php echo $this->Paginator->sort('micros_bill_statement');?></th>
			<th><?php echo $this->Paginator->sort('micros_bill_merchant');?></th>
			<th><?php echo $this->Paginator->sort('micros_bill_rep');?></th>
			<th><?php echo $this->Paginator->sort('gateway_option_1');?></th>
			<th><?php echo $this->Paginator->sort('gateway_option_2');?></th>
			<th><?php echo $this->Paginator->sort('gateway_package_silver');?></th>
			<th><?php echo $this->Paginator->sort('gateway_package_gold');?></th>
			<th><?php echo $this->Paginator->sort('gateway_package_gold_cust_db');?></th>
			<th><?php echo $this->Paginator->sort('gateway_package_gold_fraud');?></th>
			<th><?php echo $this->Paginator->sort('gateway_package_platinum');?></th>
			<th><?php echo $this->Paginator->sort('gateway_epay');?></th>
			<th><?php echo $this->Paginator->sort('gateway_bill_statement');?></th>
			<th><?php echo $this->Paginator->sort('gateway_bill_merchant');?></th>
			<th><?php echo $this->Paginator->sort('gateway_bill_rep');?></th>
			<th><?php echo $this->Paginator->sort('moto_online_chd');?></th>
			<th><?php echo $this->Paginator->sort('moto_developer');?></th>
			<th><?php echo $this->Paginator->sort('moto_company');?></th>
			<th><?php echo $this->Paginator->sort('moto_gateway');?></th>
			<th><?php echo $this->Paginator->sort('moto_contact');?></th>
			<th><?php echo $this->Paginator->sort('moto_phone');?></th>
			<th><?php echo $this->Paginator->sort('moto_email');?></th>-->
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($Coversheets as $Coversheet):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $this->Html->link($Coversheet['Coversheet']['id'], array('controller' => 'onlineapp_coversheets', 'action' => 'view', $Coversheet['Coversheet']['id'])); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($Coversheet['CobrandedApplication']['DBA'], array('controller' => 'cobranded_applications', 'action' => 'add', 1, $Coversheet['CobrandedApplication']['id'], $Coversheet['CobrandedApplication']['hash'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($Coversheet['User']['firstname'] . ' ' . $Coversheet['User']['lastname'], array('controller' => 'users', 'action' => 'view', 'admin' => true, $Coversheet['User']['id'])); ?>
		</td>
		<td><?php echo $Coversheet['Coversheet']['status']; ?>&nbsp;</td>
		<!--<td><?php echo $Coversheet['Coversheet']['hash']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_existing_merchant']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_banking']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_statements']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_drivers_license']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_new_merchant']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_business_license']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_other']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_field_other']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_tier3']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_tier4']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_tier5_financials']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_tier5_processing_statements']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_tier5_bank_statements']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_equipment_terminal']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_equipment_gateway']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_install_rep']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_install_axia']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_install_pos']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_starterkit_rep']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_starterkit_axia']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_equipment_payment_ach']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_equipment_payment_lease']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_lease_price']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_lease_months']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_existing_amex']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_debit_volume']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_item_count']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_referrer']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_referrer_gp']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_referrer_bp']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_referrer_pct']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_reseller']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_reseller_gp']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_reseller_bp']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_reseller_pct']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['setup_notes']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['cp_encrypted_sn']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['cp_pinpad_ra_attached']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['cp_giftcards']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['cp_check_guarantee']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['cp_check_guarantee_info']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['cp_pos']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['cp_pos_contact']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['micros_ip']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['micros_dial']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['micros_bill_statement']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['micros_bill_merchant']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['micros_bill_rep']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['gateway_option_1']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['gateway_option_2']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['gateway_package_silver']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['gateway_package_gold']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['gateway_package_gold_cust_db']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['gateway_package_gold_fraud']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['gateway_package_platinum']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['gateway_epay']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['gateway_bill_statement']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['gateway_bill_merchant']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['gateway_bill_rep']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['moto_online_chd']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['moto_developer']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['moto_company']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['moto_gateway']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['moto_contact']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['moto_phone']; ?>&nbsp;</td>
		<td><?php echo $Coversheet['Coversheet']['moto_email']; ?>&nbsp;</td>-->
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $Coversheet['Coversheet']['id'])); ?><br />
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $Coversheet['Coversheet']['id'])); ?><br />
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $Coversheet['Coversheet']['id']), null, __('Are you sure you want to delete %s Cover Sheet?', $Coversheet['CobrandedApplication']['DBA']/*['Coversheet']['id']*/)); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous'), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next') . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
			<ul>
				<li><?php echo $this->Html->link(__('New  Coversheet'), array('action' => 'add')); ?></li>
				<li><?php echo $this->Html->link(__('List  Applications'), array('controller' => 'cobranded_applications', 'action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('New  Application'), array('controller' => 'cobranded_applications', 'action' => 'add')); ?> </li>
				<li><?php echo $this->Html->link(__('List  Users'), array('controller' => 'onlineapp_users', 'action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('New  User'), array('controller' => 'onlineapp_users', 'action' => 'add')); ?> </li>
			</ul>
		</div>
	</div>
</div>