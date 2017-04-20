<!--Load status popover css customizations-->
<link rel="stylesheet" type="text/css" href="/css/app_status_popover.css">
<div class="CobrandedApplications">
	<?php echo $this->Element('cobranded_applications/search'); ?>
	<table class="table table-condensed table-striped table-hover">
		<tr>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.id', 'ID'); ?></th>
			<th><?php echo $this->Paginator->sort('User.firstname', 'User Name'); ?></th>
			<th><?php echo $this->Paginator->sort('Cobrand.partner_name', 'Partner Name'); ?></th>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.template_id', 'Template'); ?></th>
			<th><?php echo $this->Paginator->sort('Dba.value', 'DBA'); ?></th>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.status', 'Status'); ?></th>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.modified', 'Modified'); ?></th>
			<th><?php echo 'Actions'; ?></th>
		</tr>
		<?php foreach ($cobrandedApplications as $cobrandedApplication): ?>
		<tr>
		<td><div class="btn-group"><?php
				echo $this->Html->link(' ',
					array(
						'controller' => 'cobrandedApplications',
						'action' => 'edit',
						$cobrandedApplication['CobrandedApplication']['uuid'],
						'admin' => false,
					),
					array(
						'class' => 'btn btn-primary btn-sm glyphicon glyphicon-edit',
						'title' => __('Edit ' . $cobrandedApplication['CobrandedApplication']['id'])
					)
				);
		?></div></td>
			<td>
				<?php echo $cobrandedApplication['User']['firstname'] . ' ' . $cobrandedApplication['User']['lastname']; ?>
			</td>
			<td>
				<?php echo $cobrandedApplication['Cobrand']['partner_name']; ?>
			</td>
			<td>
				<?php echo $cobrandedApplication['Template']['name']; ?>
			</td>
			<td><?php echo $cobrandedApplication['Dba']['value']; ?>&nbsp;</td>
			<td>
	<?php 
		if ($cobrandedApplication['CobrandedApplication']['status'] == CobrandedApplication::STATUS_COMPLETED
		|| $cobrandedApplication['CobrandedApplication']['status'] == CobrandedApplication::STATUS_SIGNED) {

			echo $this->Element('cobranded_applications/appStatusPopOver', array(
					'appId' => $cobrandedApplication['CobrandedApplication']['id'],
					'appStatus' => $cobrandedApplication['CobrandedApplication']['status']
				));
		} else {
			
			echo $cobrandedApplication['CobrandedApplication']['status'];
		}
	?>&nbsp;
			</td>
			<td><?php echo $this->Time->format('m/d/y h:i A', $cobrandedApplication['CobrandedApplication']['modified']); ?>&nbsp;</td>
			<td><div class="btn-group"><?php
				if (in_array($this->Session->read('Auth.User.group'), array('admin')) && $cobrandedApplication['CobrandedApplication']['status'] == CobrandedApplication::STATUS_SIGNED) {
				echo $this->Html->link(' ',
					array(
						'action' => 'export',
						$cobrandedApplication['CobrandedApplication']['id']
					),
					array(
						'class' => 'btn btn-default btn-sm glyphicon glyphicon-export',
						'title' => __('Export')
					)
				);
				}
				echo $this->Html->link(' ',
					array(
						'action' => 'add',
						$cobrandedApplication['CobrandedApplication']['id']
					),
					array(
						'class' => 'btn btn-default btn-sm glyphicon glyphicon-tags',
						'title' => __('Copy')
					)
				);
				echo $this->Html->link(' ',
					array(
						'action' => 'email_timeline',
						$cobrandedApplication['CobrandedApplication']['id']
					),
					array(
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
				echo $this->Form->button(' ',
					array(
						'type' => 'button',
						'data-toggle' => 'modal',
						'data-target' => '#myModal_' . $cobrandedApplication['CobrandedApplication']['id'],
						'class' => 'btn btn-info btn-sm glyphicon glyphicon-send',
						'title' => __('Email App For Field Completion')
					)
				);

				if ($cobrandedApplication['CobrandedApplication']['status'] === 'signed' && isset($cobrandedApplication['Merchant']['merchant_id'])) {
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
				echo $this->Html->link(' ',
					array(
						'action' => 'edit',
						$cobrandedApplication['CobrandedApplication']['id']
					),
					array(
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
	<?php echo $this->Element('paginatorBottomNav'); ?>
</div>
