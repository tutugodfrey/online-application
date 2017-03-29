<div class="cobrands panel panel-default index">
	<div class="panel-heading"><u><strong>Cobrands</strong></u></div>
	<table class="table talbe-condensed table-hover">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('partner_name'); ?></th>
			<th><?php echo $this->Paginator->sort('partner_name_short'); ?></th>
			<th><?php echo $this->Paginator->sort('cobrand_logo_url'); ?></th>
			<th><?php echo $this->Paginator->sort('brand_logo_url'); ?></th>
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
				$cobrand_logo_url = $cobrand['Cobrand']['cobrand_logo_url'];
				$pos = strpos($cobrand_logo_url, '/');
				if ($pos === false) {
					echo h($cobrand_logo_url);
				} else {
					// assume it is a url
					echo $this->Html->link($cobrand_logo_url);
				}
			?></td>
			<td><?php
				$brand_logo_url = $cobrand['Cobrand']['brand_logo_url'];
				$pos = strpos($brand_logo_url, '/');
				if ($pos === false) {
					echo h($brand_logo_url);
				} else {
					// assume it is a url
					echo $this->Html->link($brand_logo_url);
				}
			?></td>
			<td><?php echo h($cobrand['Cobrand']['description']); ?></td>
			<td><?php echo h($responseUrlTypes[$cobrand['Cobrand']['response_url_type']]); ?></td>
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
				<?php echo $this->Html->link(__('List Templates'), CakeText::insert('/admin/cobrands/:id/templates', array('id' => $cobrand['Cobrand']['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	<?php echo $this->Element('paginatorBottomNav'); ?>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		<div class="panel-body">
			<ul>
				<li><?php echo $this->Html->link(__('New Cobrand'), array('action' => 'add')); ?></li>
			</ul>
		</div>
	</div>
</div>
