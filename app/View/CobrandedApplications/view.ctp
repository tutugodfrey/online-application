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
		<dt><?php echo __('Uuid'); ?></dt>
		<dd>
			<?php echo h($cobrandedApplication['CobrandedApplication']['uuid']); ?>
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
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php
			echo $this->Html->link(
				__('Edit Application'),
				array(
					'action' => 'edit',
					$cobrandedApplication['CobrandedApplication']['uuid']
				)
			);
			?>
		</li>
	</ul>
</div>
