<div class="cobrandedApplications">
<!--	<h2><?php //echo __('Applications'); ?></h2>
	<div class="btn-group"> 
	<?php echo __('Create Application') . $this->BoostCakeHtml->link(' ',array('action' => 'add'),
		array(
			'class' => 'btn btn-default glyphicon glyphicon-plus',
			'title' => __('Create Application')
		)
		
	); ?>
	</div>
-->	<?php echo $this->Element('cobranded_applications/search'); ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.id', 'ID'); ?></th>
			<th><?php echo $this->Paginator->sort('User.firstname', 'User Name'); ?></th>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.template_id', 'Template'); ?></th>
			<th><?php echo $this->Paginator->sort('Dba.value', 'DBA'); ?></th>
			<th><?php echo $this->Paginator->sort('CorpName.value', 'Corp Name'); ?></th>
			<th><?php echo $this->Paginator->sort('CorpContact.value', 'Corp Contact'); ?></th>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.status', 'Status'); ?></th>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.modified', 'Modified'); ?></th>
			<th><?php echo 'Actions'; ?></th>
		</tr>
		<?php foreach ($cobrandedApplications as $cobrandedApplication): ?>
		<tr>
		<td><div class="btn-group"><?php
				echo $this->BoostCakeHtml->link(' ', 
					array(
						'controller' => 'cobrandedApplications',
						'action' => 'edit',
						$cobrandedApplication['CobrandedApplication']['uuid'],
						'admin' => false,
					),
					array(
						'class' => 'btn btn-default glyphicon glyphicon-edit',
						'title' => __('Edit ' . $cobrandedApplication['CobrandedApplication']['id'])
					)
				);
		?></div></td>
			<td>
				<?php echo $this->Html->link($cobrandedApplication['User']['firstname'] . ' ' . $cobrandedApplication['User']['lastname'], array('controller' => 'users', 'action' => 'view', $cobrandedApplication['User']['id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->link($cobrandedApplication['Template']['name'], String::insert('/admin/templates/:id/templatepages', array('id' => $cobrandedApplication['Template']['id']))); ?>
			</td>
			<td><?php echo $this->Html->link($cobrandedApplication['Dba']['value'], '/cobranded_applications/edit/'.$cobrandedApplication['CobrandedApplication']['uuid']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($cobrandedApplication['CorpName']['value'], '/cobranded_applications/edit/'.$cobrandedApplication['CobrandedApplication']['uuid']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($cobrandedApplication['CorpContact']['value'], '/cobranded_applications/edit/'.$cobrandedApplication['CobrandedApplication']['uuid']); ?>&nbsp;</td>
			<td>
				<?php if($cobrandedApplication['CobrandedApplication']['status'] == 'completed' || $cobrandedApplication['CobrandedApplication']['status'] == 'signed') {
                    echo $this->Html->link($cobrandedApplication['CobrandedApplication']['status'], array('controller' => 'cobrandedApplications', 'action' => 'app_status', $cobrandedApplication['CobrandedApplication']['id'], 'admin' => true)) . "\n\t\t</td>\n";
                         } else {
							echo $cobrandedApplication['CobrandedApplication']['status'];
                                }
				?>&nbsp;
			</td>
			<td><?php echo h($cobrandedApplication['CobrandedApplication']['modified']); ?>&nbsp;</td>
			<td><div class="btn-group"><?php
				echo $this->BoostCakeHtml->link(' ', 
					array(
						'action' => 'export', 
						$cobrandedApplication['CobrandedApplication']['id']
					),
					array(
						'class' => 'btn btn-default btn-xs glyphicon glyphicon-export',
						'title' => __('Export')
					)
				); 
				echo $this->BoostCakeHtml->link(' ', 
					array(
						'action' => 'copy',
						$cobrandedApplication['CobrandedApplication']['id']
					),
					array(
						'class' => 'btn btn-default glyphicon glyphicon-tags',
						'title' => __('Copy')
					)
				);
				echo $this->BoostCakeHtml->link(' ', 
					array(
						'action' => 'email_timeline', 
						$cobrandedApplication['CobrandedApplication']['id']
					),
					array(
						'class' => 'btn btn-default glyphicon glyphicon-calendar',
						'title' => __('Timeline for Emails')
					)
				);
				echo $this->BoostCakeHtml->link(' ', 
					array(
						'action' => 'complete_fields', 
						$cobrandedApplication['CobrandedApplication']['id']
					),
					array(
						'class' => 'btn btn-default glyphicon glyphicon-send', 
						'title' => __('Email App For Field Completion')
					)
				);
				echo $this->BoostCakeHtml->link(' ', 
					array(
						'action' => 'install_sheet_var', 
						'admin' => 'false', 
						$cobrandedApplication['CobrandedApplication']['id']
					),
					array(
						'class' => 'btn btn-default glyphicon glyphicon-file', 
						'title' => __('Install Sheet')
					)
				);
				if (isset($cobrandedApplication['Coversheet']['id'])) {
					echo $this->BoostCakeHtml->link(' ',
						array(
							'Controller' => 'Coversheets',
							'action' => 'edit',
							$cobrandedApplication['Coversheet']['id']
						),
						array(
							'class' => 'btn btn-default glyphicon glyphicon-book',
							'title' => __('Cover Sheet')
						)
					); 
				} else {
					echo $this->BoostCakeHtml->link(' ', 
						array(
							'controller' => 'Coversheets', 
							'action' => 'add', 
							$cobrandedApplication['CobrandedApplication']['id'], 
							$cobrandedApplication['User']['id'], 
							'admin' => false
						),
						array(
							'class' => 'btn btn-default glyphicon glyphicon-book',
							'title' => __('Cover Sheet')
						)
					); 
				}
				echo $this->BoostCakeHtml->link(' ',
					array(
						'action' => 'edit',
						$cobrandedApplication['CobrandedApplication']['id']
					),
					array(
						'class' => 'btn btn-default glyphicon glyphicon-cog',
						'title' => __('Override')
					)
				);
				echo $this->Form->postLink(' ', 
					array(
						'action' => 'delete', 
						$cobrandedApplication['CobrandedApplication']['id']
					), 
					array(
						'class' => 'btn btn-default glyphicon glyphicon-trash',
						'title' => __('Delete'),
						'style' => 'margin-left:-2px'	
					), 
					__('Are you sure you want to delete # %s?', $cobrandedApplication['CobrandedApplication']['id'])				);
?>
		</div>
		</td>
		</tr>
<!--		<tr>
			<td class="actions" colspan="8">
				<?php echo $this->Html->link(__('Export'), array('action' => 'export', $cobrandedApplication['CobrandedApplication']['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cobrandedApplication['CobrandedApplication']['uuid'], 'admin' => false)); ?>
				<?php echo $this->Html->link(__('Copy'), array('action' => 'copy', $cobrandedApplication['CobrandedApplication']['id'])); ?>
				<?php echo $this->Html->link(__('Timeline For Emails'), array('action' => 'email_timeline', $cobrandedApplication['CobrandedApplication']['id'])); ?>
				<?php echo $this->Html->link(__('Email App For Field Completion'), '/cobranded_applications/complete_fields/'.$cobrandedApplication['CobrandedApplication']['id']); ?>
				<?php echo $this->Html->link(__('Install Sheet'), array('action' => 'install_sheet_var', 'admin' => false, $cobrandedApplication['CobrandedApplication']['id'])); ?>
				<?php 
					if (isset($cobrandedApplication['Coversheet']['id'])) {
						echo $this->Html->link(__('Cover Sheet'), '/coversheets/edit/'.$cobrandedApplication['Coversheet']['id']); 
					} else {
						echo $this->Html->link(__('Cover Sheet'), array('controller' => 'Coversheets', 'action' => 'add', $cobrandedApplication['CobrandedApplication']['id'], $cobrandedApplication['User']['id'], 'admin' => false)); 
					}
				?>
				<?php echo $this->Html->link(__('Override'), array('action' => 'edit', $cobrandedApplication['CobrandedApplication']['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $cobrandedApplication['CobrandedApplication']['id']), null, __('Are you sure you want to delete # %s?', $cobrandedApplication['CobrandedApplication']['id'])); ?>
			</td>
		</tr>
-->		<?php endforeach; ?>
	</table>
	<?php echo $this->Element('paginatorBottomNav'); ?>
</div>

<!--<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Application'), array('action' => 'add')); ?></li>
		</li>
               <? echo $this->Element('cobranded_applications/search'); ?>
                
                <?php
                        echo $this->Form->end();
                ?>
	</ul>
</div>-->
