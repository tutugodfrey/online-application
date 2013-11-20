<h4>Retrieve Existing Axia Applications</h4>

<p>Please specify the email address entered on your applications to retrieve access:</p>
<?php
    if ($error) echo $this->Html->div('error', $error);
    echo $this->Form->create('Application', array('controller' => 'applications', 'action' => 'retrieve'));
    echo $this->Html->div(
        'email',
        $this->Form->text('email', array('type' => 'email'))
    );
    echo $this->Form->end('Submit');
?>
<?php
    if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {

        echo $this->Html->link(
            'Return to Applications Admin',
            '/admin/applications/',
            array('style' => 'display: block; float: right;')
        );
    }
?>