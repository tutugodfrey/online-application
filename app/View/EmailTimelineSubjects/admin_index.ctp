<div class="emailTimelineSubjects panel panel-default index">
	<div class="panel-heading"><u><strong><?php echo __('Email Timeline Subjects') ?></strong></u></div>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('subject'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($emailTimelineSubjects as $emailTimelineSubject): ?>
	<tr>
		<td><?php echo h($emailTimelineSubject['EmailTimelineSubject']['id']); ?>&nbsp;</td>
		<td><?php echo h($emailTimelineSubject['EmailTimelineSubject']['subject']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $emailTimelineSubject['EmailTimelineSubject']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $emailTimelineSubject['EmailTimelineSubject']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $emailTimelineSubject['EmailTimelineSubject']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<?php echo $this->Element('paginatorBottomNav'); ?>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		<div class="panel-body">
			<ul>
				<li><?php echo $this->Html->link(__('New Email Timeline Subject'), array('action' => 'edit')); ?></li>
			</ul>
		</div>
	</div>
</div>
