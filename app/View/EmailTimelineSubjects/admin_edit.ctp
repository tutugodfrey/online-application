<div class="container-fluid">
  <div class="row">
  	<?php
  	$elVars = array(
		'navLinks' => array(
			'List Email Timeline Subjects' => Router::url(array('action' => 'index')),
			'Add Email Timeline Subject' => Router::url(array('action' => 'edit'))
		)
	);
	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="panel panel-default">
		<div class="panel-heading"><u><strong><?php echo __('Add/Edit Email Timeline Subject')?></strong></u></div>
			<div class="panel-body">
				<?php 
				echo $this->Form->create('EmailTimelineSubject',array(
						'inputDefaults' => array(
							'div' => 'form-group col-md-3',
							'label' => array('class' => 'control-label'),
							'wrapInput' => 'col-md-11',
							'class' => 'form-control input-sm',
						),
						'class' => 'form-horizontal',
					));
				if (!empty($this->request->data['EmailTimelineSubject']['id'])) {
					echo $this->Form->hidden('id');
				}
				echo $this->Form->input('subject');
				echo $this->Form->end(array('label' => 'Submit', 'div' => 'form-group col-md-12', 'class' => 'btn btn-sm btn-success')); ?>
			</div>
		</div>
	</div>
  </div>
</div>