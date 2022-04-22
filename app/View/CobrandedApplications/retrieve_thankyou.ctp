<?php 
	if (in_array($this->Session->read('Auth.User.group'), array(USER::ADMIN, USER::REP, USER::MANAGER))) {
	?>
	<h4>Application sent for field completion.</h4>
	<p>The Application for 
	<?php echo $dba ?> 
	was sent to 
	<?php echo $fullname ?> 
	at the following email address: 
	<?php echo $email ?>
	</p>
	<?php
	echo $this->Element('cobranded_applications/return');
	} else {
	?>

	<h4>Retrieve Existing Axia Applications</h4>

	Thank you. Please check your email for a link to your applications.
	<br />
	<?php
	}
?>
