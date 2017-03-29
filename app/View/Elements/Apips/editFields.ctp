<div class="apips panel panel-default form">
	<div class="panel-heading"><u><strong><?php echo __('Add/Edit API IP restriction');?></strong></u></div>
<?php 
	echo $this->Form->create('Apip');
	if (!empty($this->request->data)) {
		echo $this->Form->hidden('id');
	}
	echo $this->Form->input('user_id');
	echo $this->Form->input('ip_address');
	echo $this->Form->end(array('label' => 'Submit', 'div' => false, 'class' => 'btn btn-sm btn-success')); ?>
</div>