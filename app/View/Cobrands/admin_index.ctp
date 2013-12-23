<div class="cobrands index">
	<h2><?php echo __('Cobrands'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('partner_name'); ?></th>
			<th><?php echo $this->Paginator->sort('partner_name_short'); ?></th>
			<th><?php echo $this->Paginator->sort('logo_url'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($cobrands as $cobrand): ?>
	<tr>
		<td><?php echo h($cobrand['Cobrand']['id']); ?>&nbsp;</td>
		<td><?php echo h($cobrand['Cobrand']['partner_name']); ?>&nbsp;</td>
		<td><?php echo h($cobrand['Cobrand']['partner_name_short']); ?>&nbsp;</td>
		<td><?php
			$logo_url = $cobrand['Cobrand']['logo_url'];
			$pos = strpos($logo_url, '/');
			if ($pos === false) {
				echo h($logo_url);
			} else {
				// assume it is a url
				echo $this->Html->link($logo_url);
			}
		?>&nbsp;</td>
		<td><?php echo h($cobrand['Cobrand']['description']); ?>&nbsp;</td>
		<td><?php echo h($cobrand['Cobrand']['created']); ?>&nbsp;</td>
		<td><?php echo h($cobrand['Cobrand']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cobrand['Cobrand']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $cobrand['Cobrand']['id']), null, __('Are you sure you want to delete # %s?', $cobrand['Cobrand']['id'])); ?>
			<?php echo $this->Html->link(__('List Templates'), String::insert('/admin/cobrands/:id/templates', array('id' => $cobrand['Cobrand']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Cobrand'), array('action' => 'add')); ?></li>
	</ul>
</div>
