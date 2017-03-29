<?php
$this->Html->addCrumb(__('Cobrands'), '/admin/cobrands');
$this->Html->addCrumb(
	__('Templates'),
	CakeText::insert(
		'/admin/cobrands/:cobrand_id/templates',
		array('cobrand_id' => $cobrand['id'])
	)
);
$this->Html->addCrumb(
	__('Pages'),
	CakeText::insert(
		'/admin/templates/:template_id/templatepages',
		array('template_id' => $template['id'])
	)
);
$this->Html->addCrumb(
	__('Sections'),
	CakeText::insert(
		'/admin/templatepages/:template_page_id/templatesections',
		array('template_page_id' => $templatePage['id'])
	)
);
?>

<div class="templateFields form">
<?php echo $this->Form->create('TemplateField'); ?>
	<fieldset>
		<legend>
		<?php echo CakeText::insert(__('Add Template Field for ":template_section_name" '), array("template_section_name" => $templateSection['name'])); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('width', array('min' => 1, 'max' => 12));
		echo $this->Form->input('rep_only');
		echo $this->Form->input(
			'type',
			array(
				'options' => $fieldTypes,
				'empty' => __('(choose one)')
			)
		);
		echo $this->Form->input('required');
		echo $this->Form->input(
			'source',
			array(
				'options' => $sourceTypes,
				'empty' => __('(choose one)')
			)
		);
		echo $this->Form->input('default_value');
		echo $this->Form->input('merge_field_name');
		echo $this->Form->input('description');
		echo $this->Form->input('encrypt');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
			<ul>
				<li><?php echo $this->Html->link(__('Cancel'), $list_url); ?></li>
			</ul>
			</div>
	</div>
</div>
