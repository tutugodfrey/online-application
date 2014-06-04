<div class="Coversheets index">
	<h2><?php echo __('Coversheets');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('cobranded_application_id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('Coversheet.status', 'CS Status');?></th>
                        <th><?php echo $this->Paginator->sort('CobrandedApplication.status', 'App Status');?></th>

			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($Coversheets as $Coversheet):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $this->Html->link($Coversheet['Coversheet']['id'], array('controller' => 'coversheets', 'action' => 'edit', $Coversheet['Coversheet']['id'], 'admin' => false)); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($Coversheet['CobrandedApplication']['DBA'], array('controller' => 'cobranded_applications', 'action' => 'add', 1, $Coversheet['CobrandedApplication']['id'], $Coversheet['CobrandedApplication']['hash'], 'admin' => false)); ?>
		</td>
		<td>
			<?php echo $this->Html->link($Coversheet['User']['firstname'] . ' ' . $Coversheet['User']['lastname'], array('controller' => 'users', 'action' => 'view', 'admin' => true, $Coversheet['User']['id'])); ?>
		</td>
		<td><?php echo $Coversheet['Coversheet']['status']; ?>&nbsp;</td>
                <td><?php echo $Coversheet['CobrandedApplication']['status']; ?>&nbsp;</td>

		<td class="actions">
                    <?php if ($this->Session->read('Auth.User.group') == 'admin'): ?>
			<?php echo $this->Html->link(__('Override'), array('action' => 'override', $Coversheet['Coversheet']['id'])); ?>
                    <?php endif; ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $Coversheet['Coversheet']['id'], 'admin' => false)); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $Coversheet['Coversheet']['id']), null, __('Are you sure you want to delete %s Cover Sheet?', $Coversheet['CobrandedApplication']['DBA']/*['Coversheet']['id']*/)); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous'), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next') . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List  Applications'), array('controller' => 'cobranded_applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New  Application'), array('controller' => 'cobranded_applications', 'action' => 'add', 'admin' => false)); ?> </li>
                <li><?php echo $this->Html->link(__('List  Coversheets'), array('controller' => 'coversheets', 'action' => 'index')); ?> </li>
<!--                <li><?php echo $this->Form->create(); ?>
                <?php if (in_array($this->Session->read('Auth.User.group'), array('admin'))) { ?>
                <?php echo $this->Form->input('Select User',array('options' => array($users), 'default' => $this->Session->read('coversheet.user_id'), 'empty' => 'Show All', 'style' => 'width: 75%;')); ?>
                <?php } ?>
                
                    <?php echo $this->Form->input('Coversheet Status', array('options' => array(
                        'saved'=>'saved',
                        'validated'=>'validated',
                        'sent'=>'sent'
                        ), 'default' => $this->Session->read('coversheet.status'),'empty' => 'Show All', 'style' => 'width: 75%;')); ?>
                
                <?php echo $this->Form->end('submit');?></li>-->
                <?php echo $this->Element('coversheets/search'); ?>
	</ul>
</div>