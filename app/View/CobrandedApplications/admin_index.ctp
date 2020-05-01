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
			<td><div class="btn-group"><?php
				if (in_array($this->Session->read('Auth.User.group'), array('admin')) && $cobrandedApplication['CobrandedApplication']['status'] == CobrandedApplication::STATUS_SIGNED) {
					echo $this->Form->button(' ',
						array(
							'type' => 'button',
							'data-toggle' => 'modal',
							'data-target' => '#dynamicModal',
							'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/CobrandedApplications/export/" . $cobrandedApplication['CobrandedApplication']['id'] . "')",
							'class' => 'btn btn-default btn-sm glyphicon glyphicon-export',
							'title' => __('Export')
						)
					);
				}

				if ($cobrandedApplication['CobrandedApplication']['status'] == CobrandedApplication::STATUS_SIGNED && 
					($cobrandedApplication['Template']['email_app_pdf'] === true || in_array($this->Session->read('Auth.User.group'), array('admin')))) {
					echo $this->Html->link($this->Html->image('pdf-format.png', array('style' => 'margin:-1px -4px -4px -4px;vertical-align:top')),
						array(
							'action' => 'open_app_pdf',
							$cobrandedApplication['CobrandedApplication']['id'],
							'admin' => true
						),
						array(
							'target' => '_blank',
							'escape' => false,
							'class' => 'btn btn-default btn-sm glyphicon',
							'title' => __('Open ' . $cobrandedApplication['Template']['name'] . ' PDF')
						)
					);
				}
				if (strlen($cobrandedApplication['CobrandedApplication']['rightsignature_document_guid']) > 30) {
					echo $this->Form->button(' ',
						array(
							'type' => 'button',
							'data-toggle' => 'modal',
							'data-target' => '#dynamicModal',
							'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/CobrandedApplications/rs_document_audit/" . $cobrandedApplication['CobrandedApplication']['rightsignature_document_guid'] . "')",
							'class' => 'btn btn-default btn-sm glyphicon glyphicon-tasks',
							'title' => __('View Document Audit')
						)
					);
				}

				if (!$appOutOfSync){
					echo $this->Form->button(' ',
						array(
							'type' => 'button',
							'data-toggle' => 'modal',
							'data-target' => '#dynamicModal',
							'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/CobrandedApplications/add/" . $cobrandedApplication['CobrandedApplication']['id'] . "')",
							'class' => 'btn btn-default btn-sm glyphicon glyphicon-duplicate',
							'title' => __('Create Copy')
						)
					);
				}
				echo $this->Form->button(' ',
					array(
						'type' => 'button',
						'data-toggle' => 'modal',
						'data-target' => '#dynamicModal',
						'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/CobrandedApplications/email_timeline/" . $cobrandedApplication['CobrandedApplication']['id'] . "')",
						'class' => 'btn btn-default btn-sm glyphicon glyphicon-calendar',
						'title' => __('Timeline for Emails')
					)
				);

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
				if (!$appOutOfSync){
					echo $this->Form->button(' ',
						array(
							'type' => 'button',
							'data-toggle' => 'modal',
							'data-target' => '#myModal_' . $cobrandedApplication['CobrandedApplication']['id'],
							'class' => 'btn btn-info btn-sm glyphicon glyphicon-send',
							'title' => __('Email App For Field Completion')
						)
					);
				}

				if ($cobrandedApplication['CobrandedApplication']['status'] === 'signed' && isset($cobrandedApplication['Merchant']['id'])) {
					echo $this->Html->link(' ',
						array(
							'action' => 'install_sheet_var',
							$cobrandedApplication['CobrandedApplication']['id']
						),
						array(
							'class' => 'btn btn-default btn-sm glyphicon glyphicon-file',
							'title' => __('Install Sheet')
						)
					);
				}
				if ($cobrandedApplication['Template']['requires_coversheet']) {
					if (isset($cobrandedApplication['Coversheet']['id'])) {
							echo $this->Html->link(' ',
								array(
									'controller' => 'Coversheets',
									'action' => 'edit',
									$cobrandedApplication['Coversheet']['id'],
									'admin' => false
								),
								array(
									'class' => 'btn btn-success btn-sm glyphicon glyphicon-book',
									'title' => __('Edit Cover Sheet')
								)
							);
						} else {
							echo $this->Html->link(' ',
								array(
									'controller' => 'Coversheets',
									'action' => 'add',
									$cobrandedApplication['CobrandedApplication']['id'],
									$cobrandedApplication['User']['id'],
									'admin' => false
								),
								array(
								'class' => 'btn btn-success btn-sm glyphicon glyphicon-book',
									'title' => __('Create Cover Sheet')
								)
							);
						}
					}
				if (in_array($this->Session->read('Auth.User.group'), array('admin'))) {
					echo $this->Form->button(' ',
						array(
							'type' => 'button',
							'data-toggle' => 'modal',
							'data-target' => '#dynamicModal',
							'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/CobrandedApplications/edit/" . $cobrandedApplication['CobrandedApplication']['id'] . "')",
							'class' => 'btn btn-danger btn-sm glyphicon glyphicon-cog',
							'title' => __('Override')
						)
					);

				}
				if (!isset($cobrandedApplication['Coversheet']['id'])) {
					echo $this->Form->postLink(' ',
						array(
							'action' => 'delete',
							$cobrandedApplication['CobrandedApplication']['id']
						),
						array(
							'class' => 'btn btn-warning btn-sm glyphicon glyphicon-trash',
							'title' => __('Delete'),
						),
						__('Are you sure you want to delete # %s?',
						$cobrandedApplication['CobrandedApplication']['id'])
					);

				}
	?>
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