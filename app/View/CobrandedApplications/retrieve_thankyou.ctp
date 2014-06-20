<?php
    if (!in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
?>
<h4>Retrieve Existing Axia Applications</h4>

Thank you. Please check your email for a link to your applications.
<br />
<?php }
    if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
?>
<h4>Application sent for field completion.</h4>
<p>The Application for <?php echo $dba ?> was sent to <?php echo $fullname ?> at the following email address: <?php echo $email ?></p>
<?php
        echo $this->Html->link(
            'Return to Applications Admin',
            '/admin/cobranded_applications/',
            array('style' => 'display: block; float: right;')
        );
    }
?>