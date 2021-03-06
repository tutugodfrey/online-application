<h4>Retrieve Existing Axia Applications</h4>

<p>Please specify the email address entered on your applications to retrieve access:</p>

<?php
	echo isset($error) ? $this->Html->div('error', $error) : '';
	echo $this->Form->create(array('controller' => 'cobranded_applications', 'action' => 'retrieve'));
	echo $this->Html->div(
		'emailText',
		$this->Form->text('emailText', array('type' => 'email'))
	);
	echo $this->Form->end('Submit');
	echo $this->Element('cobranded_applications/return');
?>
