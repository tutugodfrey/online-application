<div class="panel panel-default center-block"  style="width:80%">
	<div class="panel panel-body">
	<div class="text-center">
		<h4><span class="label label-success">
		<?php echo strtoupper(h($this->request->data('ApiConfiguration.configuration_name')))?> API CONNECTION CONFIG
	</span></h4></div>
	<?php echo $this->Form->create('ApiConfiguration', [
		'inputDefaults' => [
			'div' => 'form-group col-md-12',
			'label' => ['class' => 'col-md-4 control-label'],
			'wrapInput' => 'col-md-7',
			'class' => 'form-control input-sm',
		],
		'class' => 'form-horizontal',
	]); ?>
		<?php
			echo $this->Form->hidden('id');
			echo $this->Element('/formFieldsApiConifigs')?>
	</div>
	<div class="panel-footer text-center">
			<?php
			echo $this->Form->end(['class' => 'btn btn-success center-block']); ?>
	</div>
</div>

