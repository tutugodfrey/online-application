<div class="groups panel panel-default form">
	<div class="panel-heading"><u><strong><?php echo __('Add/Edit Group');?></strong></u></div>
<?php 
echo $this->Form->create('Group',
	array(
		'inputDefaults' => array(
			'div' => 'form-group',
			'class' => 'form-control'
		),
		'class' => 'form-inline'
	)
);
if (!empty($this->request->data['Group']['id'])) {
		echo $this->Form->hidden('id');
	}
echo $this->Form->input('name');
echo $this->Form->end(array('label' => __('Submit'), 'class' => 'btn btn-success')); 
?>
</div>