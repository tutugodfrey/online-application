<p>Document was successfully sent out for Signature.</p>
<?php $admin = Router::url(array('controller' => 'admin', 'action' => 'applications'));
echo $this->Html->scriptBlock("alert('Document was successfully sent out for signature.') 
    window.location = '{$admin}'"); ?>
<?php
    if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
        echo $this->Html->link(
            'Return to Applications Admin',
            '/admin/applications/',
            array('style' => 'display: block; float: right;')
        );
    }
?>