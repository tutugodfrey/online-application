<div class="container-fluid">
  <div class="row">
	
	<div class="col-md-5 col-md-offset-3">
	  <!-- view page content -->
		<div class="panel panel-default">
			<div class="panel-heading"><u><strong><?php echo __('Admin Edit Application')?></strong></u></div>
			<div class="panel-body">
			<?php 
				echo $this->Form->create('CobrandedApplication', array(
							'inputDefaults' => array(
								'div' => 'form-group col-md-12',
								'label' => array('class' => 'col-md-4 control-label'),
								'wrapInput' => 'col-md-5',
								'class' => 'form-control input-sm',
							),
							'class' => 'form-horizontal',
						));
				echo $this->Form->input('id');
				echo $this->Form->input('uuid', array('type' => 'hidden'));
				echo $this->Form->input('template_id', array('type' => 'hidden'));
				echo $this->Form->input('user_id');
				echo $this->Form->input('status',
							array(
								'options' => array(
										'saved'=>'saved',
										'validate'=>'validate',
										'completed'=>'completed',
										'pending'=>'pending',
										'signed'=>'signed'
								),
								'empty' => 'Show All'
							)
						);
				echo $this->Form->input('rightsignature_document_guid');
				echo $this->Form->input('rightsignature_install_document_guid');
				echo $this->Html->link('Cancel', array('controller' => 'CobrandedApplications', 'action' => 'index'), array('class' => 'btn btn-sm btn-danger col-md-offset-4 col-sm-offset-4'));
				echo $this->Form->end(array('label' => __('Submit'), 'div' => false, 'class' => 'btn btn-sm btn-success'));
			?>
			</div>
		</div>
	</div>
  </div>
</div>