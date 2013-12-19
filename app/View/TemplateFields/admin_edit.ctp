<?php
$this->Html->addCrumb(__('Cobrands'), '/admin/cobrands');
$this->Html->addCrumb(
	__('Templates'),
	String::insert(
		'/admin/cobrands/:cobrand_id/templates',
		array('cobrand_id' => $cobrand['id'])
	)
);
$this->Html->addCrumb(
	__('Pages'),
	String::insert(
		'/admin/templates/:template_id/templatepages',
		array('template_id' => $template['id'])
	)
);
$this->Html->addCrumb(
	__('Sections'),
	String::insert(
		'/admin/templatepages/:template_page_id/templatesections',
		array('template_page_id' => $templatePage['id'])
	)
);
?>

<div class="templateFields form">
<?php echo $this->Form->create('TemplateField'); ?>
	<fieldset>
		<legend><?php echo String::insert(__('Edit Template Field for ":template_section_name" '), array("template_section_name" => $templateSection['name'])); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('type');
		echo $this->Form->input('required');
		echo $this->Form->input('source');
		echo $this->Form->input('default_value');
		echo $this->Form->input('merge_field_name');
		echo $this->Form->input('order');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), $list_url); ?></li>
	</ul>
</div>
