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
echo $this->Html->tag('div', __('Reset My Password'),
	[
		'class' => 'bg-primary text-center text-muted'
	]
);
echo $this->Form->create('User', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'label' => false,
		'wrapInput' => 'col col-md-12',
		'class' => 'form-control'
	),
	'class' => 'well form-horizontal'
));
echo $this->Form->input('email', array(
	'wrapInput' => 'col col-md-12 input-group',
	'beforeInput' => '<span class ="input-group-addon glyphicon glyphicon-envelope"></span>',
	'placeholder' => 'email',
	'autofocus' => 'autofocus'
));

echo $this->Html->tag('div', $this->Html->link('Cancel', array('action' => 'login'), array(
	'class' => 'btn btn-danger',
	)) . $this->Form->submit('Send', array(
	'div' => 'pull-right',
	'class' => 'btn btn-primary ',
	)),
	array('class' => 'form-group')
);
echo $this->Form->end();
echo $this->Session->flash();
?>
</div>
