<div class="cobrands form">
<?php echo $this->Form->create('Cobrand'); ?>
	<fieldset>
		<legend><?php echo __('Edit Cobrand'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('partner_name');
		echo $this->Form->input('partner_name_short');
		echo $this->Form->input('description');
	?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>	
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?></li>
	</ul>
</div>
