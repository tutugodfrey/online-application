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
<div class="container-fluid">
  <div class="row">
  	<?php
  	$elVars = array(
  	'navLinks' => array(
				'List Template Fields' => $list_url,
		)
	);
	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>
				<?php echo  CakeText::insert(__('Add Template Field for ":template_section_name" '), array("template_section_name" => $templateSection['name'])); ?>
				</strong>
			</div>
			<div class="panel-body">
			<?php 
			echo $this->Form->create('TemplateField', array(
					'novalidate' => true,
					'inputDefaults' => array(
						'div' => 'form-group col-md-12',
						'label' => array('class' => 'col-md-2 control-label'),
						'wrapInput' => 'col-md-4',
						'class' => 'form-control input-sm',
					),
					'class' => 'form-horizontal',
				));
			echo $this->Form->input('name');
			echo $this->Form->input('width', array('options' => array_combine(range(1,12),range(1,12))));
			echo $this->Form->input('type', array('options' => $fieldTypes));
			echo $this->Form->input('source', array('options' => $sourceTypes));
			echo $this->Form->input('rep_only', array('label'=> array('class' => 'col-md-offset-7') ,'type' => 'checkbox', 'class' => null));
			echo $this->Form->input('required', array('label'=> array('class' => 'col-md-offset-7') ,'type' => 'checkbox', 'class' => null));
			echo $this->Form->input('default_value');
			echo $this->Form->input('merge_field_name');
			echo $this->Form->input('description');
			echo $this->Form->input('encrypt', array('label'=> array('class' => 'col-md-offset-7') ,'type' => 'checkbox', 'class' => null));
			echo $this->Form->hidden('section_id', array('value' => $templateSection['id']));
			echo $this->Form->end(array('label' => __('Submit'), 'div' => 'form-group col-md-12', 'class' => 'btn btn-sm btn-success')); ?>
			</div>
		</div>
	</div>
  </div>
</div>