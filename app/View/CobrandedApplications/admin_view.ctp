<div class="cobrandedApplications view">
	<h2><?php  echo __('Application'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($cobrandedApplication['CobrandedApplication']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cobrandedApplication['User']['fullname'], array('controller' => 'users', 'action' => 'view', $cobrandedApplication['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('template'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cobrandedApplication['Template']['name'], CakeText::insert('/admin/templates/:id/templatepages', array('id' => $cobrandedApplication['Template']['id']))); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Uuid'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cobrandedApplication['CobrandedApplication']['uuid'], '/cobranded_applications/edit/'.$cobrandedApplication['CobrandedApplication']['uuid']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($cobrandedApplication['CobrandedApplication']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($cobrandedApplication['CobrandedApplication']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
	<ul>
		<li><?php echo $this->Html->link(__('Edit Application'), array('action' => 'edit', $cobrandedApplication['CobrandedApplication']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Application'), array('action' => 'delete', $cobrandedApplication['CobrandedApplication']['id']), null, __('Are you sure you want to delete # %s?', $cobrandedApplication['CobrandedApplication']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Applications'), array('action' => 'index')); ?> </li>
	</ul>
			</div>
	</div>
</div>

