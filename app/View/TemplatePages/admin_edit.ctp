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
<div class="container-fluid">
  <div class="row">
  	<?php
  	$elVars = array(
  			'navLinks' => array(
  				'Template Pages Index' => $list_url,
  				'Add Template Page' => "$list_url/add"
			)
  		);
	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="panel panel-default">
		<div class="panel-heading">
			<strong>
			<?php echo CakeText::insert(__('Edit Template Page for ":template_name" '), array("template_name" => $template['name'])); ?>
		</strong>
		</div>
			<div class="panel-body">
		<?php   echo $this->Form->create('TemplatePage', array(
				'inputDefaults' => array(
						'div' => 'form-group col-md-12',
						'label' => array('class' => 'col-md-2 control-label'),
						'wrapInput' => 'col-md-4',
						'class' => 'form-control input-sm',
					),
					'class' => 'form-horizontal',
			)); 
				echo $this->Form->input('id');
				echo $this->Form->input('name', array('disabled' => $nameDisabled));
				if ($nameDisabled) {
					echo $this->Form->hidden('name');
				}
				echo $this->Form->input('rep_only', array('label'=> array('class' => 'col-md-9 control-label'), 'type' => 'checkbox', 'class' => null));
				echo $this->Form->input('order', array('min' => 0, 'max' => $maxOrderValue, 'disabled' => $orderDisabled));
				if ($orderDisabled) {
					echo $this->Form->hidden('order');
				}
				echo $this->Form->input('description');
				echo $this->Form->hidden('template_id');
				echo $this->Form->end(array('label' => __('Submit'), 'div' => false, 'class' => 'btn btn-sm btn-success')); ?>
			</div>
		</div>
	</div>
  </div>
</div>