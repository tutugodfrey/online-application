<div class="cobrandedApplications index">
	<h2><?php echo __('Applications'); ?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('template_id'); ?></th>
			<th><?php echo $this->Paginator->sort('uuid'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php foreach ($cobrandedApplications as $cobrandedApplication): ?>
		<tr>
			<td><?php echo h($cobrandedApplication['CobrandedApplication']['id']); ?>&nbsp;</td>
			<td>
				<?php echo $this->Html->link($cobrandedApplication['User']['fullname'], array('controller' => 'users', 'action' => 'view', $cobrandedApplication['User']['id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->link($cobrandedApplication['Template']['name'], String::insert('/admin/templates/:id/templatepages', array('id' => $cobrandedApplication['Template']['id']))); ?>
			</td>
			<td><?php echo $this->Html->link($cobrandedApplication['CobrandedApplication']['uuid'], '/cobranded_applications/edit/'.$cobrandedApplication['CobrandedApplication']['uuid']); ?>&nbsp;</td>
			<td><?php echo h($cobrandedApplication['CobrandedApplication']['created']); ?>&nbsp;</td>
			<td><?php echo h($cobrandedApplication['CobrandedApplication']['modified']); ?>&nbsp;</td>
			<td class="actions">
				<?php echo $this->Html->link(__('Export'), array('action' => 'export', $cobrandedApplication['CobrandedApplication']['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cobrandedApplication['CobrandedApplication']['id'])); ?>
				<?php echo $this->Html->link(__('Copy'), array('action' => 'copy', $cobrandedApplication['CobrandedApplication']['id'])); ?>
				<?php echo $this->Html->link(__('Timeline For Emails'), array('action' => 'email_timeline', $cobrandedApplication['CobrandedApplication']['id'])); ?>
				<?php echo $this->Html->link(__('Email App For Field Completion'), '/cobranded_applications/complete_fields/'.$cobrandedApplication['CobrandedApplication']['id']); ?>
				<?php echo $this->Html->link(__('Install Sheet'), array('action' => 'install_sheet_var', 'admin' => false, $cobrandedApplication['CobrandedApplication']['id'])); ?>
				<?php echo $this->Html->link(__('Cover Sheet'), '/coversheets/edit/'.$cobrandedApplication['Coversheet']['id']); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $cobrandedApplication['CobrandedApplication']['id']), null, __('Are you sure you want to delete # %s?', $cobrandedApplication['CobrandedApplication']['id'])); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php echo $this->Element('paginatorBottomNav'); ?>
</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Application'), array('action' => 'add')); ?></li>
	</ul>
</div>
