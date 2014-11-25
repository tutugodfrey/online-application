<div class="users form">
    <?php echo $this->Form->create('User');?>
    <fieldset>
        <legend>Add User</legend>
        <?php
        echo $this->Form->input('firstname', array('type' => 'text', 'required' => true));
        echo $this->Form->input('lastname', array('type' => 'text', 'required' => true));
        echo $this->Form->input('email');
        //echo $this->Form->input('password');
        echo $this->Form->input('pwd', array('label'=> 'Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
        echo $this->Form->input('password_confirm', array('label'=> 'Password Confirmation','type' => 'password', 'value'=>'', 'autocomplete'=>'off'));
        echo $this->Form->input('api', array('label'=> 'Enable API','type'=>'checkbox'));        
        echo $this->Form->input('api_password', array('label'=> 'Api Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
        echo $this->Form->input('group_id');
        echo $this->Form->input('Manager', array('label' => 'Select Manager(s)', 'multiple' => 'checkbox'));

        echo "<br>";
        echo $this->Form->input('Cobrand', array('label' => 'Select Cobrand(s)', 'multiple' => 'checkbox'));
        echo "<br>";
        echo $this->Form->input('Template', array('label' => 'Select Template(s)', 'multiple' => 'checkbox'));
        echo "<br>";
        echo $this->Form->input(
            'User.template_id',
            array(
                'options' => $templates,
                'label' => 'Select Default Template',
                'type' => 'select'
            )
        );
        echo "<br>";

        ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>
</div>