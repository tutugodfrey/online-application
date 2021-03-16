<!--Load status popover css customizations-->
<link rel="stylesheet" type="text/css" href="/css/app_status_popover.css">
<div class="CobrandedApplications">
<?php echo $this->Element('cobranded_applications/search');
	  echo $this->Element('resultLimitModifier');
?>

	<table class="table table-condensed table-striped table-hover">
		<tr>
			<th><!--spacer--></th>
			<th><?php echo $this->Paginator->sort('User.firstname', 'User Name'); ?></th>
			<th><?php echo $this->Paginator->sort('Cobrand.partner_name', 'Partner Name'); ?></th>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.template_id', 'Template'); ?></th>
			<th><?php echo $this->Paginator->sort('Dba.value', 'DBA'); ?></th>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.status', 'Status'); ?></th>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.modified', 'Modified'); ?></th>
			<?php if (in_array($this->Session->read('Auth.User.group'), array('admin'))): ?>
				<th><?php echo $this->Paginator->sort('CobrandedApplication.exported_date', 'Exported'); ?></th>
			<?php endif; ?>
			<th><?php echo 'Actions'; ?></th>
		</tr>
		<?php foreach ($cobrandedApplications as $cobrandedApplication): 
			$appOutOfSync = !empty($cobrandedApplication['CobrandedApplication']['data_to_sync']);
			//set contextual highlight class for out-of-sync applications
			echo ($appOutOfSync)? "<tr class='warning'>" : "<tr>"; 
		?>
		<td><div class="btn-group"><?php
				$btnSettings = array(
					'controller' => 'cobrandedApplications',
					'admin' => false,
				);
				
				if ($appOutOfSync === false) {
					$btnSettings['action'] = 'edit';
					$btnSettings[] = $cobrandedApplication['CobrandedApplication']['uuid'];
					$btnAttributes = array(
							'class' => 'btn btn-primary btn-sm glyphicon glyphicon-edit',
							'title' => __('Edit ' . $cobrandedApplication['CobrandedApplication']['id'])
						);

				} else {
					$btnSettings['action'] = 'syncApplication';
					$btnSettings[] = $cobrandedApplication['CobrandedApplication']['id'];
					$btnSettings[] = $cobrandedApplication['CobrandedApplication']['template_id'];
					$btnAttributes = array(
							'class' => 'btn btn-danger btn-sm glyphicon glyphicon-refresh',
							'title' => __('Sync Application ' . $cobrandedApplication['CobrandedApplication']['id'] . ' with Template.')
						);

				}

				echo $this->Html->link(' ', $btnSettings, $btnAttributes);
		?></div></td>
			<td>
				<?php echo h($cobrandedApplication['User']['firstname'] . ' ' . $cobrandedApplication['User']['lastname']); ?>
			</td>
			<td>
				<?php echo h($cobrandedApplication['Cobrand']['partner_name']); ?>
			</td>
			<td>
				<?php echo h($cobrandedApplication['Template']['name']); ?>
			</td>
			<td><?php echo h($cobrandedApplication['Dba']['value']); ?>&nbsp;</td>
			<td>
	<?php 
		if ($cobrandedApplication['CobrandedApplication']['status'] == CobrandedApplication::STATUS_COMPLETED
		|| $cobrandedApplication['CobrandedApplication']['status'] == CobrandedApplication::STATUS_SIGNED) {

			$isSigned = ($cobrandedApplication['CobrandedApplication']['status'] == CobrandedApplication::STATUS_SIGNED);
			echo ($isSigned)? "<span class='text-success'><strong>":"";
			echo h($cobrandedApplication['CobrandedApplication']['status']);
			echo ($isSigned)? "</span></strong>":"";
		} elseif($appOutOfSync) {
			echo "<span class='text-warning' data-toggle='tooltip' data-placement='left' title='' alt='' data-original-title=\"App and Template are out-of-sync due to changes made to Template. Click Sync button if necessary.\";><strong>out-of-sync</strong></span>";
		} else {
			echo h($cobrandedApplication['CobrandedApplication']['status']);
		}	
	?>&nbsp;
			</td>
			<td><?php echo $this->Time->format('m/d/y h:i A', $cobrandedApplication['CobrandedApplication']['modified']); ?>&nbsp;</td>
			<?php if (in_array($this->Session->read('Auth.User.group'), array('admin'))): ?>
			<td><?php
					if (!empty($cobrandedApplication['CobrandedApplication']['api_exported_date'])) {
						echo $this->Html->tag('span', $this->Html->tag('span',null,array('class' => 'glyphicon glyphicon-ok')) . $this->Html->tag('/span') . ' API',
							array(
								'class' => 'label label-success',
								'data-toggle' => 'tooltip',
								'data-placement' => 'top',
								'data-original-title' => "Exported directly to Axia Database on: " . $this->Time->format($cobrandedApplication['CobrandedApplication']['api_exported_date'], '%b %e, %Y %H:%M %p')
							));
						echo '&nbsp;';
					}
					if (!empty($cobrandedApplication['CobrandedApplication']['csv_exported_date'])) {
						echo $this->Html->tag('span', $this->Html->tag('span',null, array('class' => 'glyphicon glyphicon-ok')) . $this->Html->tag('/span') . ' CSV',
							array(
								'class' => 'label label-info',
								'data-toggle' => 'tooltip',
								'data-placement' => 'top',
								'data-original-title' => 'Exported as CSV file on: ' . $this->Time->format($cobrandedApplication['CobrandedApplication']['csv_exported_date'], '%b %e, %Y %H:%M %p')
							));
					}
			 ?></td>
			<?php endif; ?>
			<td>
				<?php 
					//Element for 'Email app for field completion GUI':
					$valuesMap = array();
					$valuesMap['Owner1Email'] = $cobrandedApplication['Owner1Email']['value'];
					$valuesMap['Owner2Email'] = $cobrandedApplication['Owner2Email']['value'];
					$valuesMap['EMail'] = $cobrandedApplication['EMail']['value'];
					$valuesMap['LocEMail'] = $cobrandedApplication['LocEMail']['value'];
					echo $this->element('cobranded_applications/email_select_modal',
						array(
							'cobranded_application_id' => $cobrandedApplication['CobrandedApplication']['id'],
							'valuesMap' => $valuesMap
						)
					);
				?>
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right panel-primary" style="width: max-content;">
						<?php
							if (in_array($this->Session->read('Auth.User.group'), array('admin')) && $cobrandedApplication['CobrandedApplication']['status'] == CobrandedApplication::STATUS_SIGNED) {
								echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-export pull-left btn-xs btn-default"></span>&nbsp;&nbsp;Export Data',
										'#',
										array(
										'escape' => false,
										'data-toggle' => 'modal',
										'data-target' => '#dynamicModal',
										'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/CobrandedApplications/export/" . $cobrandedApplication['CobrandedApplication']['id'] . "')",
										'class' => 'small',
										'style' => 'padding-left: 5px',
										'title' => __('Export Data')
										)
									) .'</li>';
							}

							if ($cobrandedApplication['CobrandedApplication']['status'] == CobrandedApplication::STATUS_SIGNED && 
								($cobrandedApplication['Template']['email_app_pdf'] === true || in_array($this->Session->read('Auth.User.group'), array('admin')))) {
								echo '<li>' . $this->Html->link($this->Html->image('pdf-format.png', array('style' => 'vertical-align:top;height: 20px; padding: 1px 2px 1px 2px;', 'class' => 'pull-left btn-xs btn-default')) . '&nbsp;&nbsp;Download PDF',
									array(
										'action' => 'open_app_pdf',
										$cobrandedApplication['CobrandedApplication']['id'],
										'admin' => true
									),
									array(
										'escape' => false,
										'style' => 'padding-left: 5px',
										'class' => 'small',
										'title' => __('Download PDF')
									)
								).'</li>';
							}
							if (strlen($cobrandedApplication['CobrandedApplication']['rightsignature_document_guid']) > 30) {
								echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-tasks pull-left btn-xs btn-default"></span>&nbsp;&nbsp;View Document Audit',
										'#',
										array(
										'escape' => false,
										'data-toggle' => 'modal',
										'data-target' => '#dynamicModal',
										'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/CobrandedApplications/rs_document_audit/" . $cobrandedApplication['CobrandedApplication']['rightsignature_document_guid'] . "')",
										'class' => 'small',
										'style' => 'padding-left: 5px',
										'title' => __('View Document Audit')
										)
									) .'</li>';
							}
							if ($cobrandedApplication['CobrandedApplication']['status'] == CobrandedApplication::STATUS_COMPLETED) {
								echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-erase pull-left btn-xs btn-danger"></span>&nbsp;&nbsp;Edit & Make Corrections',
									'#',
									array(
									'escape' => false,
									'data-toggle' => 'modal',
									'data-target' => '#dynamicModal',
									'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/CobrandedApplications/amend_completed_document/" . $cobrandedApplication['CobrandedApplication']['id'] . "')",
									'class' => 'small',
									'style' => 'padding-left: 5px',
									'title' => __('Edit & Make Corrections')
									)
								) .'</li>';
							}
							if (!$appOutOfSync) {
								echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-duplicate pull-left btn-xs btn-default"></span>&nbsp;&nbsp;Create Copy',
									'#',
									array(
									'escape' => false,
									'data-toggle' => 'modal',
									'data-target' => '#dynamicModal',
									'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/CobrandedApplications/add/" . $cobrandedApplication['CobrandedApplication']['id'] . "')",
									'class' => 'small',
									'style' => 'padding-left: 5px',
									'title' => __('Create Copy')
									)
								) .'</li>';
							}
							echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-calendar pull-left btn-xs btn-default"></span>&nbsp;&nbsp;Timeline for Emails',
								'#',
								array(
								'escape' => false,
								'data-toggle' => 'modal',
								'data-target' => '#dynamicModal',
								'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/CobrandedApplications/email_timeline/" . $cobrandedApplication['CobrandedApplication']['id'] . "')",
								'class' => 'small',
								'style' => 'padding-left: 5px',
								'title' => __('Timeline for Emails')
								)
							) .'</li>';
							
							if (!$appOutOfSync) {
								echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-send pull-left btn-xs btn-info"></span>&nbsp;&nbsp;Email App For Field Completion',
									'#',
									array(
									'escape' => false,
									'data-toggle' => 'modal',
									'data-target' => '#myModal_' . $cobrandedApplication['CobrandedApplication']['id'],
									'class' => 'small',
									'style' => 'padding-left: 5px',
									'title' => __('Email App For Field Completion')
									)
								) .'</li>';
							}
							if ($cobrandedApplication['CobrandedApplication']['status'] === 'signed' && isset($cobrandedApplication['Merchant']['id'])) {
								echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-file pull-left btn-xs btn-default"></span>&nbsp;&nbsp;Install Sheet',
									array(
										'action' => 'install_sheet_var',
										$cobrandedApplication['CobrandedApplication']['id']
									),
									array(
									'escape' => false,
									'class' => 'small',
									'style' => 'padding-left: 5px',
									'title' => __('Install Sheet')
									)
								) .'</li>';
							}
							if ($cobrandedApplication['Template']['requires_coversheet']) {
								if (isset($cobrandedApplication['Coversheet']['id'])) {
									$linkTitle = 'Edit Cover Sheet';
									$actionUrl = array(
										'controller' => 'Coversheets',
										'action' => 'edit',
										$cobrandedApplication['Coversheet']['id'],
										'admin' => false
									);
								} else {
									$linkTitle = 'Create Cover Sheet';
									$actionUrl = array(
										'controller' => 'Coversheets',
										'action' => 'add',
										$cobrandedApplication['CobrandedApplication']['id'],
										$cobrandedApplication['User']['id'],
										'admin' => false
									);
								}
								echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-book pull-left btn-xs btn-success"></span>&nbsp;&nbsp;' . $linkTitle,
									$actionUrl,
									array(
										'escape' => false,
										'class' => 'small',
										'style' => 'padding-left: 5px',
										'title' => __($linkTitle)
									)
								).'</li>';
							}
							if (in_array($this->Session->read('Auth.User.group'), array('admin'))) {
								echo '<li>' . $this->Html->link('<span class="glyphicon glyphicon-cog pull-left btn-xs btn-danger"></span>&nbsp;&nbsp;Override',
									'#',
									array(
									'escape' => false,
									'data-toggle' => 'modal',
									'data-target' => '#dynamicModal',
									'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/CobrandedApplications/edit/" . $cobrandedApplication['CobrandedApplication']['id'] . "')",
									'class' => 'small',
									'style' => 'padding-left: 5px',
									'title' => __('Override')
									)
								) .'</li>';

							}
							if (!isset($cobrandedApplication['Coversheet']['id'])) {
								echo '<li>' .  $this->Form->postLink('<span class="glyphicon glyphicon-trash pull-left btn-xs btn-danger"></span>&nbsp;&nbsp;Delete',
									array(
										'action' => 'delete',
										$cobrandedApplication['CobrandedApplication']['id']
									),
									array(
										'escape' => false,
										'class' => 'small',
										'style' => 'padding-left: 5px',
										'title' => __('Delete'),
									),
									__('Are you sure you want to delete # %s?',
									$cobrandedApplication['CobrandedApplication']['id'])
								) . '</li>';

							}
						?>
						
					</ul>
				</div>
		</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php
		echo $this->Element('resultLimitModifier');
		echo $this->Element('paginatorBottomNav');
	?>
</div>

<script type='text/javascript'>
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip(); 
});
</script>