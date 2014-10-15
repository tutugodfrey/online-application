<h1>Edit User</h1>
<div class="users form">
    <table cellpadding="0" cellspacing="0">
        <tr>
<?php
echo $this->Form->create('User', array('action' => 'edit', 'novalidate' => true));
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('email');
echo $this->Form->input('pwd', array('label'=> 'Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
echo $this->Form->input('password_confirm', array('type' => 'password', 'value'=>'', 'autocomplete'=>'off'));
echo $this->Form->input('firstname');
echo $this->Form->input('lastname');
echo $this->Form->input('group_id');
echo $this->Form->input('extension');
echo $this->Form->input('api_enabled', array('label'=> 'Enable API','type'=>'checkbox'));        
echo $this->Form->input('api_password', array('label'=> 'Api Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
echo $this->Form->input('active', array('type' => 'checkbox'));
echo $this->Form->input('Manager', array('label' => 'Select Manager(s)', 'multiple' => 'checkbox'));
if ($this->request->data['User']['group_id'] == User::MANAGER_GROUP_ID){?>
    <br/>Select Rep(s)<br/>
 
            
<?php
echo $this->Form->input('AssignedRepresentative',array('label' => false,'multiple' => 'checkbox'));
}?>
        
<?php
if ($this->request->data['User']['api_enabled']) {
echo $this->Form->input('token');
echo $this->Form->input('token_used');
echo $this->Form->input('token_uses');
}

echo $this->Form->input('Cobrand', array('label' => 'Select Cobrand(s)', 'multiple' => 'checkbox'));
echo "<br>";
echo $this->Form->input('Template', array('label' => 'Select Template(s)', 'multiple' => 'checkbox'));
echo "<br>";
echo $this->Form->input(
    'User.template_id',
    array(
        'options' => $userTemplates,
        'label' => 'Select Default Template',
        'type' => 'select',
        'default' => $defaultTemplateId
    )
);
echo "<br>";

echo $this->Form->end('Save User');
?>
            </tr>
    </table>
</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link('List Users', array('action' => 'index', 'admin' => true)); ?></li>
        <li><?php echo $this->Html->link('List Applications', array('controller' => 'applications', 'action' => 'index', 'admin' => true)); ?></li>
        <li><?php echo $this->Html->link('List Settings', array('controller' => 'settings', 'action' => 'index', 'admin' => true)); ?></li>
        <li><?php echo $this->Html->link('List IP Restrictions', array('controller' => 'apips', 'action' => 'index', 'admin' => true)); ?></li>
        <li><?php echo $this->Html->link('List Groups', array('controller' => 'groups', 'action' => 'index', 'admin' => true)); ?></li>
        <? echo $this->Element('users/search'); ?>
	</ul>
</div>
