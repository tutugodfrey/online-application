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
	__('sections'),
	String::insert(
		'/admin/templatepages/:template_page_id/templatesections',
		array('template_page_id' => $templatePage['id'])
	)
);
?>

<div class="templateFields form container">
<?php echo $this->Form->create('TemplateField'); ?>
	<fieldset>
		<legend><?php echo String::insert(__('Edit Template Field for ":template_section_name" '), array("template_section_name" => $templateSection['name'])); ?></legend>
	<?php
		echo '<div class="row">';
		echo $this->Form->input('name', array('div' => 'col-md-12'));
		echo '</div>';
		echo '<div class="row">';
		echo $this->Form->input('order', array('min' => 0, 'div' => 'col-md-3'));
		echo $this->Form->input('width', array('min' => 1, 'max' => 12, 'div' => 'col-md-3'));
		echo $this->Form->input('type', array('options' => $fieldTypes, 'div' => 'col-md-2'));
		echo $this->Form->input('source', array('options' => $sourceTypes, 'div' => 'col-md-2'));
		echo '</div>';
		echo '<div class="row">';
		echo $this->Form->input('rep_only', array('div' => 'col-md-6'));
		echo $this->Form->input('required', array('div' => 'col-md-6'));
		echo '</div>';
		echo '<div class="row">';
		echo $this->Form->input('default_value', array('div' => 'col-md-12'));
		echo $this->Form->input('merge_field_name', array('div' => 'col-md-12'));
		echo $this->Form->input('description', array('div' => 'col-md-12'));
		echo $this->Form->hidden('section_id');
		echo '</div>';
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
