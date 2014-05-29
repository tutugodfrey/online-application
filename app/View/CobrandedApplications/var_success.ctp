<?php echo $this->Session->flash(); ?>
<?php
    if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
        echo $this->Html->link(
            'Return to Applications Admin',
            '/admin/cobranded_applications/',
            array('style' => 'display: block; float: right;')
        );
    }
?>
