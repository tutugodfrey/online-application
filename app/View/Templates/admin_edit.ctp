<?php $this->Html->addCrumb(__('Cobrand'), '/admin/cobrands'); ?>

<div class="templates form">
<?php echo $this->Form->create('Template', array(
		'inputDefaults' => array(
			'wrapInput' => false,
		),
		'class' => 'form-inline'
	)); ?>
	<fieldset>
		<legend><?php echo CakeText::insert(__('Edit Template for ":cobrand_name"'), array('cobrand_name' => $cobrand['Cobrand']['partner_name'])); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->element('Templates/templateFields');
		echo $this->Form->hidden('cobrand_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
			<ul>
				<li><?php echo $this->Html->link(__('Cancel'), CakeText::insert('/admin/cobrands/:cobrand_id/templates', array('cobrand_id' => $cobrand['Cobrand']['id']))); ?></li>
			</ul>
		</div>
	</div>
</div>
