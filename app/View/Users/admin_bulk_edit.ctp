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
			<th><?php echo 'Cobrands';?></th>
			<th><?php echo 'Templates';?></th>
			<th><?php echo $this->Paginator->sort('template_id', 'Default Template');?></th>
                        <th><?php echo $this->Paginator->sort('active');?></th>
	</tr>
	<?php
	$i = 0;
        echo $this->Form->create('User', array('action' => 'bulk_edit'));
	foreach ($users as $user):
			$usersCobrands = Hash::extract($user, 'Cobrand.{n}.id');
			$usersTemplates = Hash::extract($user, 'Template.{n}.id');
			//debug($usersCobrands);
	?>
		<?php echo $this->Form->input($i . '.User.id', array('type' => 'hidden', 'default'=> $user['User']['id'])); ?>
                <td> <?php echo $this->Form->input($i . '.User.firstname', array('default'=> trim($user['User']['firstname']))); ?>&nbsp;</td>
		<td>
			<?php echo $this->Form->input($i . '.User.lastname', array('default'=> trim($user['User']['lastname']))); ?>
		</td>
		<td>
			<?php echo $this->Form->input($i. '.User.email', array('default'=> $user['User']['email'])); ?>
		</td>
		<td><?php echo $this->Form->input($i . '.User.group_id', array('options' => $groups,'default'=> $user['User']['group_id'])); ?>&nbsp;</td>
		<td><?php echo $this->Form->input($i . '.Cobrand.Cobrand', array(
			'label' => 'Select Cobrand(s)',
			'multiple' => 'checkbox',
			'value' => $usersCobrands
		));
		?></td>
		<td><?php 
		echo $this->Form->input($i . '.Template.Template', array(
			'label' => 'Select Template(s)',
			'multiple' => 'checkbox',
			'default' => $usersTemplates
		));
		?></td>
		<td><?php echo $this->Form->radio($i . '.User.template_id', 
			$templates,
			array(
				'value' => $user['User']['template_id'],
				'legend' => false
			
		)); ?></td>
                <td><?php echo $this->Form->input($i . '.User.active', array('type' => 'checkbox','checked'=> $user['User']['active'])); ?>&nbsp;</td>
	<?php $i++; ?>
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

