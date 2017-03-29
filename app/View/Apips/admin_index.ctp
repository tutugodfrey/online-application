<div class="apips panel panel-default index">
	<div class="panel-heading"><u><strong><?php echo __('API IP restrictions') ?></strong></u></div>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('ip_address'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($apips as $apip): ?>
	<tr>
		<td><?php echo h($apip['Apip']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($apip['User']['fullname'], array('controller' => 'users', 'action' => 'view', $apip['User']['id'])); ?>
		</td>
		<td><?php echo h($apip['Apip']['ip_address']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $apip['Apip']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $apip['Apip']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $apip['Apip']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $apip['Apip']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<?php
	echo $this->Element('paginatorBottomNav');
	?>
</div>
<?php echo $this->Element('actionsNav'); ?>