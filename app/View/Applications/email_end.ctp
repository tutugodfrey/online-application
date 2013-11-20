<h4>Axia | Finished</h4>

An email has been sent to the following recipient(s) containing a link for signing the Application:<br /><br />
<? echo "Signer 1 Name: " . $owner1name . "<br />" . "Signer 1 Email: " . $owner1email; ?> <br /><br />
<? if ($owner2name != '') echo "Signer 2 Name: " . $owner2name . "<br /> " . "Signer 2 Email: " . $owner2email; ?>
<br />
<?
    if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
        echo $this->Html->link(
            'Return to Applications Admin',
            '/admin/applications/',
            array('style' => 'display: block; float: right;')
        );
    }
    ?>