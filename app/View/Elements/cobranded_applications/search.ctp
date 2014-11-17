<?php
	echo $this->Form->create('CobrandedApplication',
		array(
			'url' => array_merge(
				array(
					'action' => 'index'),
					$this->params['pass']
				),
			'inputDefaults' => array(
				'div' => 'form-group',
				'label' => false,
				'wrapInput' => false,
				'class' => 'form-control'
			),
                        'class' => 'well form-inline',
			'novalidate' => true,
		)
	);

	echo $this->Html->link('New Application', 
		array(
			'controller' => 'cobrandedApplications',
			'action' => 'add',
		),
       		array(
			'class' => 'btn btn-primary pull-right', 
			'title' => 'New Application'
		)
	);
	echo $this->Form->input('search', array('placeholder' => 'Search'));
                        
	echo $this->Form->input('user_id',array('options' => array($users), 'default' => $user_id, 'empty' => 'Show All')); 

	echo $this->Form->input('status', 
		array(
			'options' => array(
        			'saved'=>'saved',
        			'validate'=>'validate',
        			'completed'=>'completed',
        			'pending'=>'pending',
        			'signed'=>'signed'
			),
			'empty' => 'Show All'
		)
	); 
	echo $this->Form->button($this->Html->tag('span','', 
		array('class' => 'glyphicon glyphicon-search')
		),
		array(
			'div' => 'form-group', 
			'class' => 'btn btn-success', 
			'name' => 'search', 
			'type' => 'submit'
		)
	);
	echo $this->Form->button($this->Html->tag(
		'span', '',
			array('class' => 'glyphicon glyphicon-random')
		),
		array(
			'div' => 'form-group', 
			'class' => 'btn btn-success', 
			'name' => 'reset',
			'type' => 'submit'
		)
	);
	echo $this->Form->end();
?>

