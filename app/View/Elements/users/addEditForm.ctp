 <?php
echo $this->Form->create('User', array('url' => array('action' => 'edit'),
   'inputDefaults' => array(
        'div' => 'form-group col-md-12',
        'label' => array('class' => 'col-md-4 control-label'),
        'wrapInput' => 'col-md-7',
        'class' => 'form-control input-sm',
    ),
    'class' => 'form-horizontal',
    'novalidate' => true));
echo $this->Html->tag('div', null, array('class' => 'col-md-6'));
	if (!empty($this->request->data['User']['id'])) {
		echo $this->Form->hidden('id');
	}
	echo $this->Form->input('firstname');
	echo $this->Form->input('lastname');
	echo $this->Form->input('email');
	echo $this->Form->input('pwd', array('label'=> 'Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
	echo $this->Form->input('password_confirm', array('type' => 'password', 'value'=>'', 'autocomplete'=>'off'));
	echo $this->Form->input('api_enabled', array('label'=> array('text' => 'Enable API', 'class' => 'col-md-12 control-label'),'type'=>'checkbox', 'class' => null));        
	echo $this->Form->input('api_password', array('label'=> 'Api Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
	echo $this->Form->input('group_id');
	echo $this->Form->input('extension');
	echo $this->Form->input('active', array('label'=> array('class' => 'col-md-12 control-label text-success'), 'type' => 'checkbox', 'class' => null));

	echo $this->Form->input('Manager', array(
	    'before' => '<small id="helpBlock2" class="help-block text-info col-md-offset-3">Hold Ctrl key for multiple select/deselect.</small>', 
	    'label' => 'Select Manager(s)', 
	    'multiple' => true, 
	    'style' => 'min-height:300px'));
	if ($this->request->data('User.group_id') == User::MANAGER_GROUP_ID){?>
	<?php
	echo $this->Form->input('AssignedRepresentative', array(
	    'before' => '<small id="helpBlock2" class="help-block text-info col-md-offset-3">Hold Ctrl key for multiple select/deselect.</small>', 
	    'label' => 'Select Rep(s)', 
	    'multiple' => true, 
	    'style' => 'min-height:300px'));

	}
//-------------END FIRST INPUT COLUMN
echo $this->Html->tag('/div');
//-------------START SECOND INPUT COLUMN
echo $this->Html->tag('div', null, array('class' => 'col-md-6'));
	if ($this->request->data('User.api_enabled')) {
	    echo $this->Form->input('token');
	    echo $this->Html->tag('div', null, array('class' => 'form-group col-md-12'));
	    echo $this->Form->label('User.token_used', 'Token Used On', array('class' => 'col-md-4 control-label'));
	    echo $this->Form->dateTime('token_used', 'MDY', '12', array(
	        'wrapInput' => 'col-md-7',
	        'class' => 'input-sm',
	        'maxYear' => date('Y') + 2,
	        'empty' => true));
	    echo $this->Html->tag('/div');
	    echo $this->Form->input('token_uses');
	}


	echo $this->Form->input('Cobrand', array(
	    'before' => '<small id="helpBlock2" class="help-block text-info col-md-offset-3">Hold Ctrl key for multiple select/deselect.</small>', 
	    'label' => 'Select Cobrand(s)', 
	    'multiple' => true, 
	    'style' => 'min-height:300px'));

	echo $this->Form->input('Template', array(
	    'before' => '<small id="helpBlock2" class="help-block text-info col-md-offset-3">Hold Ctrl key for multiple select/deselect.</small>', 
	    'label' => 'Select Template(s)', 
	    'multiple' => true, 
	    'style' => 'min-height:300px'));
	if (isset($userTemplates)) {
	    echo $this->Form->input(
	        'User.template_id',
	        array(
	            'options' => $userTemplates,
	            'label' => 'Select Default Template',
	            'type' => 'select'
	        )
	    );
	}
echo $this->Html->tag('/div');
echo $this->Form->end(array('label' => __('Save user'), 'div' => array('class' => 'col-md-12'), 'class' => 'btn btn-sm btn-success'));
?>