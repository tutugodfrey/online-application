<h2>Login</h2>
<?php
echo $this->Session->flash('auth');
echo $this->Form->create('User');
//echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' =>'login')));
echo $this->Form->input('User.email', array('autofocus'=>'autofocus'));
echo $this->Form->input('User.password');
echo $this->Form->end('Login');
?>
