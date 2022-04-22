<!--Load status popover css customizations-->
<link rel="stylesheet" type="text/css" href="/css/app_status_popover.css">
<div class="Coversheets">
<?php
echo$this->Element('coversheets/search');
echo $this->Element('resultLimitModifier');
?>
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
			<?php echo h($coversheet['User']['firstname'] . ' ' . $coversheet['User']['lastname']); ?>
		</td>
		<td><?php echo h($coversheet['Coversheet']['status']); ?>&nbsp;</td>
        <td>
        	<?php 
        	if ($coversheet['CobrandedApplication']['status'] == CobrandedApplication::STATUS_SIGNED) {
				echo $this->Html->tag('span', '<strong>' . h($coversheet['CobrandedApplication']['status']). '</strong>', array('class' => 'text-success'));
			} else {
				echo h($coversheet['CobrandedApplication']['status']);
			}
			?>
        </td>

		<td class="actions">
        <?php if ($this->Session->read('Auth.User.group') == 'admin') {
				echo $this->Form->button(' ',
					array(
						'type' => 'button',
						'data-toggle' => 'modal',
						'data-target' => '#dynamicModal',
						'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/Coversheets/override/" . $coversheet['Coversheet']['id'] . "')",
						'class' => 'btn btn-danger btn-sm glyphicon glyphicon-cog',
						'title' => __('Override')
					)
				);
			}
		?>
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
	echo $this->Element('resultLimitModifier');
	echo $this->Element('paginatorBottomNav');
	?>
</div>
