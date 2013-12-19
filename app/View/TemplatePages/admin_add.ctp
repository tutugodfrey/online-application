<?php
$this->Html->addCrumb(__('Cobrands'), '/admin/cobrands');
$this->Html->addCrumb(
	__('Templates'),
	String::insert(
		'/admin/cobrands/:cobrand_id/templates',
		array('cobrand_id' => $cobrand['id'])
	)
);
?>

<div class="templatePages form">
<?php echo $this->Form->create('TemplatePage'); ?>
	<fieldset>
		<legend><?php echo String::insert(__('Add Template Page for ":template_name" '), array("template_name" => $template['name'])); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), $list_url); ?> </li>
	</ul>
</div>
