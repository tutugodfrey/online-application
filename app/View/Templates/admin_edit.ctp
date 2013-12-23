<?php $this->Html->addCrumb(__('Cobrand'), '/admin/cobrands'); ?>

<div class="templates form">
<?php echo $this->Form->create('Template'); ?>
	<fieldset>
		<legend><?php echo String::insert(__('Edit Template for ":cobrand_name"'), array('cobrand_name' => $cobrand['Cobrand']['partner_name'])); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input(
			'logo_position',
			array(
				'options' => $logo_position_types,
				'empty' => __('(choose one)')
			)
		);
		echo $this->Form->input('include_axia_logo');
		echo $this->Form->input('description');
		echo $this->Form->hidden('cobrand_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), String::insert('/admin/cobrands/:cobrand_id/templates', array('cobrand_id' => $cobrand['Cobrand']['id']))); ?></li>
	</ul>
</div>
