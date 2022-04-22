<?php
/**
 *
 * @author omota
 */
?>
<h1>API access token</h1>
<?php if (!empty($token)) { ?>
<p>Your new API user access token is: <strong><?php echo $token;
?></strong></p>
<p>Your new API password is: <strong><?php echo $password;
?></strong></p>
<?php } else {
    echo $this->Html->link('Manage API settings for this user', array('controller' => 'users', 'action' => 'edit', $id, 'admin' => true));
}
?>
