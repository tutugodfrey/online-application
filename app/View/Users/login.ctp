<div  style="width: 350px; margin-left: auto; margin-right: auto">
<?php
	echo $this->Html->image(
		'logo.png',
		array(
			'alt'=> __('Axia'),
			'border' => '0',
			'class' => 'center-block'
		)
	);
?>
<h3 class='text-center' >Login</h3>
<?php

?>
<?php
echo $this->Session->flash();
echo $this->Form->create('BoostCake', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'label' => array(
			'class' => 'col col-md-3 control-label'
		),
		'wrapInput' => 'col col-md-9',
		'class' => 'form-control'
	),
	'class' => 'well form-horizontal'
));
echo $this->Form->input('User.email', array(
		'placeholder' => 'Email',
		'autofocus' => 'autofocus',
		'required' => false
	));
	echo $this->Form->input('User.password', array(
		'placeholder' => 'Password'
	)); ?>
	<div class="form-group">
		<?php echo $this->Form->submit('Login', array(
			'div' => 'col col-md-9 col-md-offset-3',
			'class' => 'btn btn-default'
		)); ?>
	</div>
<?php echo $this->Form->end(); ?>
</div>
