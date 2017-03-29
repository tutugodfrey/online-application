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
	__('sections'),
	CakeText::insert(
		'/admin/templatepages/:template_page_id/templatesections',
		array('template_page_id' => $templatePage['id'])
	)
);
?>

<div class="templateFields form panel panel-default">
	<div class="panel-heading">
		<strong>
			<?php echo CakeText::insert(__('Edit Template Field for ":template_section_name" '), array("template_section_name" => $templateSection['name'])); ?>
		</strong>
	</div>
	<div class="panel-body">
		<?php 
		echo $this->Form->create('TemplateField', array('novalidate' => true));
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
		echo $this->Form->input('default_value', array('type' => 'textarea', 'escape' => false, 'div' => 'col-md-12'));
		echo $this->Form->input('merge_field_name', array('div' => 'col-md-12'));
		echo $this->Form->input('description', array('div' => 'col-md-12'));
		echo $this->Form->input('encrypt', array('div' => 'col-md-6'));
		echo $this->Form->hidden('section_id');
		echo '</div>';
		echo $this->Form->end(array('label' => 'Submit', 'div' => false, 'class' => 'btn btn-sm btn-success')); 
		?>
	</div>
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
