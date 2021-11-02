
<div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
	<div class="thumbnail">
		<?php 
			echo ($workflowComplete)? '<img src="/img/green_check.png" style="position: absolute;width: 60px;right: 42%;top: 17%;opacity: 0.8;">':'';
		?>
	    <img src=<?php echo "'$thumbImg'"?> style="width: 100px;">
	    <div class="caption">
	        <div class="text-center small" style="white-space: nowrap;">
	        	<strong class="small">Status: </strong> <?php echo $statusLabel; ?>
	        </div>
	        <?php if ($isExpired) {
            	echo "<div class='text-center small'><strong class='text-muted small'>(Contact your Rep to renew access if needed.)</strong></div>";
            }
            ?>
	        <strong>
	        	<?php echo '<span class="text-primary">' . $name .'</span>'; ?>
	        </strong> 
	        <div class="small">
	            <div><strong><?php echo $cobrandName; ?> Application</strong></div>
	            <div><strong>Created: </strong> <?php echo date('m-d-Y g:ia',strtotime($cobrandedApplication['created'])); ?></div>
	            <div><strong>Last Modified: </strong> <?php echo date('m-d-Y g:ia',strtotime($cobrandedApplication['modified'])); ?></div>
	        </div>
         <?php 
            
            ?>
	        <div class="text-center">
	        	<?php
	        	if ($workflowComplete === false) {
		        	if (($isExpired === false || !empty($this->Session->read('Auth.User.id'))) && $cobrandedApplication['status'] != 'completed') {
		        		echo $this->Html->link('Open', array('controller' => 'cobranded_applications', 'action' => 'edit', $uuid), array('class' => 'btn btn-xs btn-primary', 'target' => '_blank'));
		        	}
		        	if (($allowSigning === true) || (!empty($this->Session->read('Auth.User.id')) && $cobrandedApplication['status'] == 'completed' && !empty($cobrandedApplication['rightsignature_document_guid']))) {
		        		echo $this->Html->link('Sign Document', array('controller' => 'cobranded_applications', 'action' => 'sign_rightsignature_document', '?' => array('guid' => $cobrandedApplication['rightsignature_document_guid'])), array('class' => 'btn btn-xs btn-success', 'target' => '_blank'));
		        	}
	        	} else {
	        		if ($cobrandedApplication['status'] == 'signed' && !empty($cobrandedApplication['rightsignature_document_guid'])) {
						echo $this->Html->link('<span class="glyphicon glyphicon-download-alt pull-left"></span>&nbsp;&nbsp;Download',
								'#',
								array(
									'escape' => false,
									'data-toggle' => 'modal',
									'data-target' => '#dynamicModal',
									'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/CobrandedApplications/send_pdf_to_client/" . $cobrandedApplication['id'] . "')",
									'class' => 'btn btn-xs btn-success',
									'style' => 'padding-left: 5px',
									'title' => __('Download Signed Document')
								)
							);
		        	}
	        	}
		        	if (!empty($this->Session->read('Auth.User.id'))) {
		        		echo '<div class="btn-group">
  									<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="caret"></span></button>
  									<ul class="dropdown-menu dropdown-menu-keft panel-primary" style="width: max-content;">';
  									if ($isExpired) {
										echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-refresh pull-left btn-xs btn-success"></span>&nbsp;&nbsp;Renew App For Client Access',
											array('controller' => 'cobranded_applications', 'action' => 'renew_modified_date', 'admin' => true, $uuid),
											array(
											'escape' => false,
											'class' => 'small',
											'style' => 'padding-left: 5px',
											'title' => __('Renew App For Client Access')
											)
										) .'</li>';
  									}
									if ($cobrandedApplication['status'] == CobrandedApplication::STATUS_COMPLETED) {
										echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-erase pull-left btn-xs btn-danger"></span>&nbsp;&nbsp;Edit & Make Corrections',
											'#',
											array(
											'escape' => false,
											'data-toggle' => 'modal',
											'data-target' => '#dynamicModal',
											'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/CobrandedApplications/amend_completed_document/" . $cobrandedApplication['id'] . "')",
											'class' => 'small',
											'style' => 'padding-left: 5px',
											'title' => __('Edit & Make Corrections')
											)
										) .'</li>';
									}
									echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-calendar pull-left btn-xs btn-default"></span>&nbsp;&nbsp;Timeline for Emails',
										'#',
										array(
										'escape' => false,
										'data-toggle' => 'modal',
										'data-target' => '#dynamicModal',
										'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/CobrandedApplications/email_timeline/" . $cobrandedApplication['id'] . "')",
										'class' => 'small',
										'style' => 'padding-left: 5px',
										'title' => __('Timeline for Emails')
										)
									) .'</li>';
									if (strlen($cobrandedApplication['rightsignature_document_guid']) > 30) {
										echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-tasks pull-left btn-xs btn-default"></span>&nbsp;&nbsp;View Document Audit',
											'#',
											array(
											'escape' => false,
											'data-toggle' => 'modal',
											'data-target' => '#dynamicModal',
											'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/CobrandedApplications/rs_document_audit/" . $cobrandedApplication['rightsignature_document_guid'] . "')",
											'class' => 'small',
											'style' => 'padding-left: 5px',
											'title' => __('View Document Audit')
											)
										) .'</li>';
									}
									if (!$hasCoversheet) {
										echo '<li>' .  $this->Form->postLink('<span class="glyphicon glyphicon-trash pull-left btn-xs btn-danger"></span>&nbsp;&nbsp;Delete',
											array(
												'admin' => true,
												'action' => 'delete',
												$cobrandedApplication['id']
											),
											array(
												'escape' => false,
												'class' => 'small',
												'style' => 'padding-left: 5px',
												'title' => __('Delete'),
											),
											__('Are you sure you want to delete # %s?',
											$cobrandedApplication['id'])
										) . '</li>';

									}
							echo '</ul></div>';
	        		} 
	        	?>
	        </div>
	    </div>
	</div>
</div>