<div class="panel panel-default center-block"  style="width:80%">
	<div class="panel panel-body">
	<div class="text-center">
		<h4><span class="label label-success">
		NEW API INTEGRATION CONFIGURATION
	</span></h4>
	</div>
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
	echo $this->Form->input('configuration_name', ['label' => "Config name",
		'before' => '<span class="pull-right text-danger">Must include the name of the external system that we have been integrated with. If this is for testing, type "test" at the end i.e.: salesforce test.</span>',
	 'placeholder' => 'Must be unique.']);
	echo $this->Element('/formFieldsApiConifigs');?>
	</div>
	<div class="panel-footer text-center">
		<?php
		echo $this->Form->end(['class' => 'btn btn-success center-block']); ?>
	</div>
</div>