<div class="panel panel-primary">
	<div class="panel-heading"><u><strong><?php echo __('Request Copy of Executed Document')?></strong></u></div>
	<div class="media panel-body">
		<div class="media-left">
			<img class="media-object" src="/img/mail-icon.png" style="width: 74px; height: 74px;">
		</div>
		<div class="media-body">

			<h4 class="media-heading text-info">Request Copy of Executed Document</h4>
			<?php echo $this->Session->flash(); ?>

			<?php
				if (!empty($emails)) {
					echo $this->Form->create('CobrandedApplication', array(
						'url' => array('controller' => 'CobrandedApplications', 'action' => 'send_pdf_to_client', 'admin' => false, $id),
						'inputDefaults' => array(
							'div' => 'form-group',
							'wrapInput' => false,
							'class' => 'form-control'
						),
						'class' => 'form-inline'
					));
					echo $this->Form->hidden('id', array('value' => $id));
					echo $this->Html->tag('p', "For security, this document will be sent to one of the email addresses provided within the document itself. The email will contain further instructions to download a copy of this executed document. (Remember to check your spam/junk folders if this email does not arrive to your inbox within a few minutes)");


					if (count($emails) > 1) {
						echo $this->Html->tag('p', '<strong><span class="text-danger">*</span>Please select an email address where this document will be delivered:</strong>');
						echo $this->Form->input(
							'client_email',
							array(
								'label' => false,
								'empty' => 'Select email',
								'options' => $emails,
								'required' => true,
							)
						);
					} else {
						$clientEmailEncrypted = key($emails);
						$clientEmail = $emails[$clientEmailEncrypted];
						echo $this->Form->hidden('client_email', array('value' => $clientEmailEncrypted));
						echo '<ul class="list-group">' .
	  						 '<li class="list-group-item list-group-item-info text-center">Email will be sent to: <strong>'. $clientEmail .'</strong> </li></ul>';
					}
					echo "<hr />";
					echo $this->Html->tag('div', 
					$this->Form->button('Send', array('escape' => false, 'type' => 'submit', 'id' => 'sendEmailSubmitBtn', 'class' => 'btn btn-success'))
						, array('class' => 'text-center')
					);
					echo $this->Form->end();
				} else {
					echo "<hr />";
					echo $this->Html->tag('div', 
					$this->Form->button('Close', array('escape' => false, 'type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => 'modal'))
						, array('class' => 'text-center')
					);
				}
			?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#CobrandedApplicationSendPdfToClientForm').on('submit', function(event) {
		jQuery('#sendEmailSubmitBtn').attr('disabled', 'disabled');
		jQuery('#sendEmailSubmitBtn').hide();
		jQuery('#CobrandedApplicationSendPdfToClientForm').append('<div class="text-center text-primary small"><img src="/img/refreshing.gif" style="width:25px"><br/>Sending...</div>');
	});
</script>