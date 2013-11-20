<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link('List Users', array('action' => 'index', 'admin' => true)); ?></li>
                <li><?php echo $this->Html->link('List Applications', array('controller' => 'applications', 'action' => 'index', 'admin' => true)); ?></li>
                <li><?php echo $this->Html->link('List Settings', array('controller' => 'settings', 'action' => 'index', 'admin' => true)); ?></li>
                <li><?php echo $this->Html->link('List IP Restrictions', array('controller' => 'apips', 'action' => 'index', 'admin' => true)); ?></li>
                <li><?php echo $this->Html->link('List Groups', array('controller' => 'groups', 'action' => 'index', 'admin' => true)); ?></li>
	</ul>
</div>
<div class="users index">
	<h2><?php echo __('Bulk Users Edit');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('firstname');?></th>
			<th><?php echo $this->Paginator->sort('lastname');?></th>
			<th><?php echo $this->Paginator->sort('email');?></th>
			<th><?php echo $this->Paginator->sort('group_id');?></th>
                        <th><?php echo $this->Paginator->sort('active');?></th>
	</tr>
	<?php
	$i = 0;
        echo $this->Form->create('User', array('action' => 'bulk_edit'));
        //debug($users);
	foreach ($users as $user):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<?php echo $this->Form->input('User.'.$user['User']['id'].'.id', array('type' => 'hidden', 'default'=> $user['User']['id'])); ?>
                <td> <?php echo $this->Form->input('User.' . $user['User']['id'] .'.firstname', array('default'=> trim($user['User']['firstname']))); ?>&nbsp;</td>
		<td>
			<?php echo $this->Form->input('User.' . $user['User']['id'] .'.lastname', array('default'=> trim($user['User']['lastname']))); ?>
		</td>
		<td>
			<?php echo $this->Form->input('User.' . $user['User']['id'] .'.email', array('default'=> $user['User']['email'])); ?>
		</td>
		<td><?php echo $this->Form->input('User.' . $user['User']['id'] .'.group_id', array('options' => $groups,'default'=> $user['User']['group_id'])); ?>&nbsp;</td>
                <td><?php echo $this->Form->input('User.' . $user['User']['id'] .'.active', array('type' => 'checkbox','checked'=> $user['User']['active'])); ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
        echo $this->Form->submit('Submit');
        echo $this->Form->end();
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

