<?php
echo $this->Form->create('User', array(
		'inputDefaults' => array(
			'div' => 'form-group col-md-9 col-sm-8 col-xs-10',
			'wrapInput' => 'input-xs',
			'class' => 'form-control'
		),
		'class' => 'form-horizontal',
		'url' => array_merge(
			array(
				'action' => 'admin_index'
			),
			$this->params['pass']
		)
));
echo $this->Form->input('search', array(
		'style' => 'height: 30px; font-size: 14px;',
		'label' => false,
		'type' => 'text',
		'placeholder' => 'Search Users')
	);	
echo $this->Form->end(array('label' => 'Search', 'div' => false, 'class' => 'btn btn-sm btn-success'));
?>