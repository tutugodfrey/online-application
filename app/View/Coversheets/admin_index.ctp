<div class="Coversheets">
	<h2><?php echo$this->Element('coversheets/search');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('cobranded_application_id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('Coversheet.status', 'CS Status');?></th>
                        <th><?php echo $this->Paginator->sort('CobrandedApplication.status', 'App Status');?></th>
			<th><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($Coversheets as $Coversheet):
	?>
	<tr>
		<td><div class="btn-group"><?php 
			echo $this->Html->link(' ',
				array(
					'controller' => 'coversheets',
					'action' => 'edit',
					$Coversheet['Coversheet']['id'],
					'admin' => false
				),
				array(
					'class' => 'btn btn-primary btn-sm glyphicon glyphicon-edit',
					'title' => __('Edit ' . $Coversheet['Coversheet']['id'])
				)
			); ?>&nbsp;
		</div>
		</td>
		<td>
			<?php echo $this->Html->link($Coversheet['Dba']['value'], array('controller' => 'cobranded_applications', 'action' => 'edit', $Coversheet['CobrandedApplication']['uuid'], 'admin' => false)); ?>
		</td>
		<td>
			<?php echo $Coversheet['User']['firstname'] . ' ' . $Coversheet['User']['lastname']; ?>
		</td>
		<td><?php echo $Coversheet['Coversheet']['status']; ?>&nbsp;</td>
                <td><?php echo $Coversheet['CobrandedApplication']['status']; ?>&nbsp;</td>

		<td class="actions">
                    <?php if ($this->Session->read('Auth.User.group') == 'admin'): ?>
			<?php echo $this->Html->link(__(' '), 
				array(
					'action' => 'override', 
					$Coversheet['Coversheet']['id']
				),
				array(
					'class' => 'btn btn-danger btn-sm glyphicon glyphicon-cog',
                                        'title' => __('Override')
				)   
			); ?>
                    <?php endif; ?>
			<?php echo $this->Form->postLink(__(' '), 
				array(
					'action' => 'delete',
					$Coversheet['Coversheet']['id']
				), 
				array(
					'class' => 'btn btn-warning btn-sm glyphicon glyphicon-trash',
					'title' => __('Delete'),
				), 
				__('Are you sure you want to delete %s Cover Sheet?', $Coversheet['Dba']['value'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Element('paginatorBottomNav');
	?>
</div>
