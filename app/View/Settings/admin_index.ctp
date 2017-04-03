<div class="container-fluid">
  <div class="row">
	<div class="col-sm-9 col-lg-12">
	  <!-- view page content -->
		<div class="users panel panel-default">
		<div class="panel-heading"><u><strong><?php echo __('Settings') ?></strong></u></div>
		<table class="table table-condensed table-striped table-hover">
			<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('key'); ?></th>
				<th><?php echo $this->Paginator->sort('value'); ?></th>
				<th><?php echo $this->Paginator->sort('description'); ?></th>
				<th class="actions"><?php echo __('Actions'); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($settings as $setting): ?>
			<tr>
				<td><?php echo h($setting['Setting']['key']); ?>&nbsp;</td>
				<td><?php echo h($setting['Setting']['value']); ?>&nbsp;</td>
				<td><?php echo h($setting['Setting']['description']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $setting['Setting']['key'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
			</tbody>
		</table>

		<?php echo $this->Element('paginatorBottomNav'); ?>

		</div>
	</div>
  </div>
</div>