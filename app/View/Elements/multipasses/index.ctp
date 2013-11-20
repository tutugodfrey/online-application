<div class="Multipasses index">
    <h2><?php echo __('Multipasses');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('merchant_id');?></th>
			<th><?php echo $this->Paginator->sort('device_number');?></th>
			<th><?php echo $this->Paginator->sort('username');?></th>
                        <th><?php echo $this->Paginator->sort('pass', 'Temp Password');?></th>
                        <th><?php echo $this->Paginator->sort('in_use');?></th>
                        <th><?php echo $this->Paginator->sort('Application.dba_business_name', 'DBA');?></th>
                        <th><?php echo $this->Paginator->sort('created');?></th>
                        <th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($multipasses as $multipass):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $multipass['Multipass']['merchant_id']; ?>&nbsp;</td>
                <td><?php echo $multipass['Multipass']['device_number']; ?>&nbsp;</td>
                <td><?php echo $multipass['Multipass']['username']; ?>&nbsp;</td>
                <td><?php echo $multipass['Multipass']['pass']; ?>&nbsp;</td>
                <td><?php echo ($multipass['Multipass']['in_use'] === false) ? 'NO' : 'YES' ; ?>&nbsp;</td>
		<td><?php echo $this->Html->link($multipass['Application']['dba_business_name'], array('controller' => 'applications', 'action' => 'add', 1, $multipass['Application']['id'], $multipass['Application']['hash'], 'admin' => false)); ?></td>
		<td><?php echo $multipass['Multipass']['created']; ?>&nbsp;</td>
                <td><?php echo $multipass['Multipass']['modified']; ?>&nbsp;</td>

		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $multipass['Multipass']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $multipass['Multipass']['id']), null, __('Are you sure you want to delete %s Cover Sheet?', $multipass['Application']['dba_business_name']/*['Multipass']['id']*/)); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>