<h4>Retrieve Existing Axia Applications</h4>

<p>Please specify the name of the aplication signer:</p>
<?php
	if ($error) {
		echo $this->Html->div('error', $error);
	}
	echo $this->Form->create('CobrandedApplication', array('controller' => 'cobranded_applications', 'action' => 'retrieve'));
	echo $this->Form->input('id', array('type' => 'hidden', 'value' => $this->passedArgs['0']));
	echo $this->Html->div(
		'email',
		$this->Form->text('email', array('type' => 'email'))
	);
	echo $this->Form->end('Submit');
	echo $this->Element('cobranded_applications/return');
?>
