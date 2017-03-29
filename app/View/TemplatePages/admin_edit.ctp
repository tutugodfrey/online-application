<?php
$this->Html->addCrumb(__('Cobrands'), '/admin/cobrands');
$this->Html->addCrumb(
	__('Templates'),
	CakeText::insert(
		'/admin/cobrands/:cobrand_id/templates',
		array('cobrand_id' => $cobrand['id'])
	)
);
?>

<div class="templatePages form">
<?php echo $this->Form->create('TemplatePage'); ?>
	<fieldset>
		<legend><?php echo CakeText::insert(__('Edit Template Page for ":template_name"'), array("template_name" => $template['name'])); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name', array('disabled' => $nameDisabled));
		if ($nameDisabled) {
			echo $this->Form->hidden('name');
		}
		echo $this->Form->input('rep_only');
		echo $this->Form->input('order', array('min' => 0, 'max' => $maxOrderValue, 'disabled' => $orderDisabled));
		if ($orderDisabled) {
			echo $this->Form->hidden('order');
		}
		echo $this->Form->input('description');
		echo $this->Form->hidden('template_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
			<ul>
				<li>
					<?php echo $this->Html->link(__('Cancel'), $list_url); ?>
				</li>
			</ul>
		</div>
	</div>
</div>
