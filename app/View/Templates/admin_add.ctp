<?php $this->Html->addCrumb(__('Cobrand'), '/admin/cobrands'); ?>
<div class="container-fluid">
  <div class="row">
  	<?php
	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>
					<?php echo CakeText::insert(__('Add Template for ":cobrand_name"'), array('cobrand_name' => $cobrand['Cobrand']['partner_name'])); ?>
				</strong>
			</div>
			<div class="panel-body">
				<?php echo $this->Form->create('Template', array(
								'inputDefaults' => array(
									'div' => 'form-group col-md-12',
									'label' => array('class' => 'col-md-2 control-label'),
									'wrapInput' => 'col-md-4',
									'class' => 'form-control input-sm',
								),
								'class' => 'form-horizontal',
							)); 
						echo $this->element('Templates/templateFields');
						// the cobrand_id is injected if we cannot tell what it(cobrand_id) is from the route
						if ($this->request->params['parent_controller_id'] == null) {
							echo $this->Form->input('cobrand_id');
						}
					?>
				<?php echo $this->Form->end(array('label' => __('Submit'), 'div' => false, 'class' => 'btn btn-sm btn-success')); ?>
			</div>
		</div>
	</div>
  </div>
</div>