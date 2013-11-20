Dear <?php echo $ownerName; ?>,<br /><br />

In order for your account to become active on your POS/Gateway we must receive a signed Confirmation Sheet<br /><br />
 
Please click on the following link to complete the confirmation.<br /><br />

<?php echo $this->Html->link($url, $url);  ?><br /><br />

Thank you,<br /><br />

Axia Merchant Services<br />
<?php echo "<a href=\"mailto:app@axiapayments.com?Subject=" . $merchant . "%20Online%20Application\">app@axiapayments.com</a>" ?><br />

877.875.6114<br />

<?php echo $this->Html->image("https://" . $_SERVER['SERVER_NAME'] . '/img/axia_logo.png', array('alt'=> __('Axia'), 'border' => '0', 'url' => 'http://www.axiapayments.com')); ?>



