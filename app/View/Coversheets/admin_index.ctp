<div class="Coversheets">
	<h2><?php echo$this->Element('coversheets/search');?></h2>
	<table class="table table-condensed table-hover">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('cobranded_application_id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('Coversheet.status', 'CS Status');?></th>
                        <th><?php echo $this->Paginator->sort('CobrandedApplication.status', 'App Status');?></th>
			<th><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($coversheets as $coversheet):
	?>
	<tr>
		<td><div class="btn-group"><?php 
			echo $this->Html->link(' ',
				array(
					'controller' => 'coversheets',
					'action' => 'edit',
					$coversheet['Coversheet']['id'],
					'admin' => false
				),
				array(
					'class' => 'btn btn-primary btn-sm glyphicon glyphicon-edit',
					'title' => __('Edit ' . $coversheet['Coversheet']['id'])
				)
			); ?>&nbsp;
		</div>
		</td>
		<td>
			<?php echo $this->Html->link($coversheet['Dba']['value'], array('controller' => 'cobranded_applications', 'action' => 'edit', $coversheet['CobrandedApplication']['uuid'], 'admin' => false)); ?>
		</td>
		<td>
			<?php echo $coversheet['User']['firstname'] . ' ' . $coversheet['User']['lastname']; ?>
		</td>
		<td><?php echo $coversheet['Coversheet']['status']; ?>&nbsp;</td>
                <td><?php echo $coversheet['CobrandedApplication']['status']; ?>&nbsp;</td>

		<td class="actions">
                    <?php if ($this->Session->read('Auth.User.group') == 'admin'): ?>
			<?php echo $this->Html->link(__(' '), 
				array(
					'action' => 'override', 
					$coversheet['Coversheet']['id']
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
					$coversheet['Coversheet']['id']
				), 
				array(
					'class' => 'btn btn-warning btn-sm glyphicon glyphicon-trash',
					'title' => __('Delete'),
				), 
				__('Are you sure you want to delete %s Cover Sheet?', $coversheet['Dba']['value'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Element('paginatorBottomNav');
	?>
</div>
