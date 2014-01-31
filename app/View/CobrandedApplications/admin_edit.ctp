<div class="cobrandedApplications form">
<?php echo $this->Form->create('CobrandedApplication'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Application'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('template_id');
		echo $this->Form->input('uuid');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li>
			<?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?>
		</li>
	</ul>
</div>
