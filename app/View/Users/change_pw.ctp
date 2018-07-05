<div style="width: 350px; margin-left: auto; margin-right: auto">
<?php
echo $this->Html->image(
	'logo.png',
	array(
		'alt' => __('Axia'),
		'border' => '0',
		'class' => 'center-block',
		'style' => 'margin-top:25%;margin-bottom:5%;width:inherit;',
	)
);
$actionStr = ($renewingPw)? 'Update' : 'Reset';
echo $this->Html->tag('div', __("$actionStr Password"),
	[
		'class' => 'bg-primary text-center text-muted'
	]
);
echo $this->Form->create('User', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'label' => false,
		'wrapInput' => false,
		'class' => 'form-control'
	),
	'class' => 'well form-horizontal'
));

if ($renewingPw) {
	echo $this->Form->input('cur_password', array('type' => 'password','placeholder' => 'Enter current password'));
}

echo $this->Form->hidden('id');
echo $this->Form->input('pwd', array('type' => 'password', 'placeholder' => 'Enter new password'));
echo $this->Form->input('repeat_password', array('type' => 'password', 'placeholder' => 'Confirm new password'));

echo $this->Html->tag('div', $this->Html->link('Cancel', array('action' => 'login'), array(
	'class' => 'btn btn-danger',
	)) . $this->Form->submit($actionStr, array(
	'div' => 'pull-right',
	'class' => 'btn btn-primary',
	)),
	array('class' => 'form-group ')
);
echo $this->Form->end();
echo $this->Session->flash(); 
?>
</div>
