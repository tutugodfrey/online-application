<div class="container-fluid">
  <div class="row">
  	
	<div class="col-md-5 col-md-offset-3">
	  <!-- view page content -->
		<div class="panel panel-default">
			<div class="panel-heading"><u><strong><?php echo __('Admin Edit Onlineapp Coversheet')?></strong></u></div>
			<div class="panel-body">
				<?php 
					echo $this->Form->create('Coversheet', array(
							'inputDefaults' => array(
								'div' => 'form-group col-md-12',
								'label' => array('class' => 'col-md-3 control-label'),
								'wrapInput' => 'col-md-5',
								'class' => 'form-control input-sm',
							),
							'class' => 'form-horizontal',
						));
					echo $this->Html->tag('div', '<strong>Rep Name:</strong> '. $data['User']['fullname'], array('class' => 'col-md-offset-3'));
	                echo $this->Html->tag('div', '<strong>Merchant:</strong> ' . Hash::get($data, 'CobrandedApplication.DBA'), array('class' => 'col-md-offset-3'));
					echo $this->Form->hidden('id');
					echo $this->Form->input('cobranded_application_id', array('label' => 'Online App Application ID #','options' => array($Applications), 'default' => $data['Coversheet']['cobranded_application_id']));
					echo $this->Form->input('user_id', array('label' => 'User Name','options' => array($Users),'default' => $data['Coversheet']['user_id']));
					echo $this->Form->input('status', array('label' => 'Coversheet Status','options' => array('saved' => 'Saved', 'validated' => 'Validated', 'sent' => 'Sent'), 'default' => $data['Coversheet']['status']));
					echo $this->Html->link('Cancel', array('action' => 'index'), array('class' => 'btn btn-sm btn-danger col-md-offset-4 col-sm-offset-4'));
					echo $this->Form->end(array('label' => __('Submit'), 'div' => false, 'class' => 'btn btn-sm btn-success'));
				?>
			</div>
		</div>
	</div>
  </div>
</div>