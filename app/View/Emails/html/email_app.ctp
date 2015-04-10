Dear <?php echo $ownerName; ?>,<br /><br />

Please click on the following link to complete your Axia Merchant Application.<br /><br />

<?php echo $this->Html->link($url, $url);  ?><br /><br />

We appreciate your business and look forward to satisfying your payment processing needs.<br /><br />

Thank you,<br /><br />

Axia Merchant Services<br />
<?php echo "<a href=\"mailto:app@axiapayments.com?Subject=" . $merchant . "%20Online%20Application\">app@axiapayments.com</a>" ?><br />

877.875.6114<br />

<?php
	$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : exec("hostname");
	echo $this->Html->image("https://".$hostname.$brandLogo, array('alt'=> __('Axia'), 'border' => '0', 'url' => 'http://www.axiapayments.com'));
?>



