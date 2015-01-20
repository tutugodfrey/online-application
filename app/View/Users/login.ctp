<div  style="width: 350px; margin-left: auto; margin-right: auto">
<h3>Login</h3>
<?php
echo $this->Session->flash('auth');
//echo $this->Form->create('User');
//echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' =>'login')));
/*echo $this->Form->create('BoostCake', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'labal' => false,
		'wrapInput' => false,
		'class' => 'control-label'
	),
	'class' => 'well form-horizontal'
));
echo $this->Form->input('User.email', array('placeholder' => 'Email', 'autofocus'=>'autofocus'));
echo $this->Form->input('User.password', array('placeholder' => 'Password'));
echo $this->Form->submit('Login', array('div' => 'form-group', 'class' => 'btn btn-default'));
echo $this->Form->end();
*/
?>
<?php
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
		'autofocus' => 'autofocus'
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
