<div class="ApiLogs index">
	<h2><?php echo __('ApiLogs');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('User.fullname');?></th>
                        <th><?php echo $this->Paginator->sort('auth_status');?></th>
			<!--<th><?php echo $this->Paginator->sort('user_token');?></th>-->
                        <th><?php echo $this->Paginator->sort('ip_address');?></th>
			<th><?php echo $this->Paginator->sort('request_type');?></th>
                        <th><?php echo $this->Paginator->sort('request_url');?></th>
                        <th><?php echo $this->Paginator->sort('request_string');?></th>
                        <th><?php echo $this->Paginator->sort('created');?></th>

			<!--<th class="actions"><?php echo __('Actions');?></th>-->
	</tr>
	<?php
	$i = 0;
	foreach ($apiLogs as $apiLog):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $apiLog['User']['fullname']; ?>&nbsp;</td>
                <!--<td><?php echo $apiLog['ApiLog']['user_token']; ?>&nbsp;</td>-->
                <td><?php echo $apiLog['ApiLog']['auth_status']; ?>&nbsp;</td>
                <td><?php echo $apiLog['ApiLog']['ip_address']; ?>&nbsp;</td>
                <td><?php echo $apiLog['ApiLog']['request_type']; ?>&nbsp;</td>
                <td><?php echo $apiLog['ApiLog']['request_url']; ?>&nbsp;</td>
                <td><?php print("<pre style=\"background-color:transparent;\">".print_r(unserialize($apiLog['ApiLog']['request_string']),true)."</pre>");?>&nbsp;</td>
                <td><?php echo $apiLog['ApiLog']['created']; ?>&nbsp;</td>

<!--		<td class="actions">
                    <?php if ($this->Session->read('Auth.User.group') == 'admin'): ?>
			<?php echo $this->Html->link(__('Override'), array('action' => 'override', $Coversheet['Coversheet']['id'])); ?>
                    <?php endif; ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $Coversheet['Coversheet']['id'], 'admin' => false)); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $Coversheet['Coversheet']['id']), null, __('Are you sure you want to delete %s Cover Sheet?', $Coversheet['Application']['dba_business_name']/*['Coversheet']['id']*/)); ?>
		</td>-->
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
	));
	?>	</p>
