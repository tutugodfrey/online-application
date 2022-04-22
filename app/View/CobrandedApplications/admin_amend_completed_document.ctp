<div class="container-fluid">
  <div class="row">
	
	<div class="col-md-12">
	  <!-- view page content -->
		<div class="panel panel-warning">
			<div class="panel-heading text-center">
				<span class="glyphicon glyphicon-warning-sign text-danger pull-left" style="font-size:19pt;vertical-align: middle;"></span>
				<u><strong><?php echo __('Confirm Request to Amend Existing App & Agreement')?></strong></u></div>
			<div class="panel-body">
				<?php

				$commonText = 'Any existing signature links sent to clients will no longer work. After updating the application, follow the usual workflow for signing documents to generate the updated version of its document.';
				$rsDocIsExpired = (strtolower($rsDocData['document']['state']) === 'expired');
				$auditTrail = ($rsDocIsExpired == false)? $rsDocData['document']['audits'] : [];
				$alreadySignedList = '';
				foreach ($auditTrail as $audit) {
					if ($audit['keyword'] == 'signed') {
						$alreadySignedList .= '<div class="list-group-item panel-danger"><strong>' . substr($audit['payload'], 0, stripos ($audit['payload'], '(')) .'</strong> on ' . date('m/d/Y h:i:s a', strtotime($audit['timestamp'])) . '</div>';
					}
				}

				if ($rsDocIsExpired == true) {
					echo $this->Html->tag('div', "This application's document has <strong>expired</strong> and is no longer accessible. However, this action will delete the expired document and allow you to edit this application. You can then follow the usual workflow for signing documents to generate a renewed document.");
				} elseif (empty($alreadySignedList)) {
					echo $this->Html->tag('div', "<strong>Warning!</strong> This application's document has been generated and it is waiting to be signed. This action will allow editing and making corrections to this application but <strong>the existing document will be lost</strong>! $commonText");
				} else {
					echo $this->Html->tag('div', "<strong>Warning!</strong> This application's document has been generated and has already been partially signed by:
						<div class='panel panel-body'>$alreadySignedList</div> This action will allow editing and making corrections to this application but <strong>the existing document will be lost and the parties listed above will have to re-sign a new document</strong>. $commonText");
				}
				?>
			<div class=text-center>
				<br/>
				<p><strong>
					<?php echo ($rsDocIsExpired)? 'Delete the existing expired document?' : 'Are you sure you wish to delete the existing document and make corrections to the data in this app?'; ?>
				</strong></p>
				<a class="text-center col-sm-6 btn-sm btn-danger" data-dismiss="modal" href="#"><strong>Cancel</strong></a>
				<?php
				echo $this->Form->postLink('Yes',
					array(
						'admin' => true,
						'action' => 'amend_completed_document',
						$appData['CobrandedApplication']['id'],
						$rsDocIsExpired,
					),
					array(
						'escape' => false,
						'class' => 'col-sm-6 btn-sm btn-primary',
						'title' => __('Continue'),
					));
				?>
			</div>
			</div>
		</div>
	</div>
  </div>
</div>