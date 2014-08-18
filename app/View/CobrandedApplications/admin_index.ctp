<div class="cobrandedApplications index">
	<h2><?php echo __('Applications'); ?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.id', 'ID'); ?></th>
			<th><?php echo $this->Paginator->sort('User.firstname'); ?></th>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.template_id', 'Template'); ?></th>
			<th><?php echo $this->Paginator->sort('Dba.value', 'DBA'); ?></th>
			<th><?php echo $this->Paginator->sort('CorpName.value', 'Corp Name'); ?></th>
			<th><?php echo $this->Paginator->sort('CorpContact.value', 'Corp Contact'); ?></th>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.status', 'Status'); ?></th>
			<th><?php echo $this->Paginator->sort('CobrandedApplication.modified', 'Modified'); ?></th>
		</tr>
		<?php foreach ($cobrandedApplications as $cobrandedApplication): ?>
		<tr>
			<td><?php echo h($cobrandedApplication['CobrandedApplication']['id']); ?>&nbsp;</td>
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
		</tr>
		<tr>
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
		<?php endforeach; ?>
	</table>
	<?php echo $this->Element('paginatorBottomNav'); ?>
</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Application'), array('action' => 'add')); ?></li>
		</li>
               <? echo $this->Element('cobranded_applications/search'); ?>
                
                <?php
                        echo $this->Form->end();
                ?>
	</ul>
</div>
