<div class="cobrands index">
	<h2><?php echo __('Cobrands'); ?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('partner_name'); ?></th>
			<th><?php echo $this->Paginator->sort('partner_name_short'); ?></th>
			<th><?php echo $this->Paginator->sort('logo_url'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th><?php echo $this->Paginator->sort('response_url_type'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php foreach ($cobrands as $cobrand): ?>
		<tr class="<?php echo $cobrand['Cobrand']['partner_name_short'] ?>">
			<td><?php echo h($cobrand['Cobrand']['id']); ?></td>
			<td><?php echo h($cobrand['Cobrand']['partner_name']); ?></td>
			<td><?php echo h($cobrand['Cobrand']['partner_name_short']); ?></td>
			<td><?php
				$logo_url = $cobrand['Cobrand']['logo_url'];
				$pos = strpos($logo_url, '/');
				if ($pos === false) {
					echo h($logo_url);
				} else {
					// assume it is a url
					echo $this->Html->link($logo_url);
				}
			?></td>
			<td><?php echo h($cobrand['Cobrand']['description']); ?></td>
			<td><?php echo h($cobrand['Cobrand']['response_url_type']); ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cobrand['Cobrand']['id'])); ?>
				<?php echo $this->Html->link(
					__('Delete'), 
					array(
						'action' => 'delete', 
						$cobrand['Cobrand']['id']
					),
						 array(),
						"Delete " . $cobrand['Cobrand']['partner_name'] . " Cobrand and associated Templates?"
				); ?>
				<?php echo $this->Html->link(__('List Templates'), String::insert('/admin/cobrands/:id/templates', array('id' => $cobrand['Cobrand']['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	<?php echo $this->Element('paginatorBottomNav'); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Cobrand'), array('action' => 'add')); ?></li>
	</ul>
</div>
