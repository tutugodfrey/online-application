<div class="container-fluid">
  <div class="row">
	<?php
		$navItems = array('List Groups' => '/admin/settings/index');
		echo $this->Element('actionsNav', array('navLinks' => $navItems));
	?>
	<div class="col-sm-9 col-lg-10">
	<!-- view page content -->
			<div class="groups panel panel-default form">
				<div class="panel-heading"><u><strong><?php echo __('Edit Group');?></strong></u></div>
				   <?php 
				   echo $this->Form->create('Setting',
						array(
							   'inputDefaults' => array(
								'div' => 'form-group col-md-12',
								'label' => array('class' => 'col-md-2 control-label'),
								'wrapInput' => 'col-md-4',
								'class' => 'form-control input-sm',
							),
							'class' => 'form-horizontal',
							'class' => 'form-horizontal'
						)
					);

					echo $this->Form->input('key');
					echo $this->Form->input('value');
					echo $this->Form->input('description');
					echo $this->Form->end(array('label' => __('Submit'), 'class' => 'btn btn-success')); 
					?>
			</div>
		</div>
	</div>
</div>