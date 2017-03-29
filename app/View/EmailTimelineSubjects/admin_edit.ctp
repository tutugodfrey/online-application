<div class="emailTimelineSubjects form">
<?php echo $this->Form->create('EmailTimelineSubject'); ?>
	<fieldset>
		<legend><?php echo __('Edit Email Timeline Subject'); ?></legend>
	<?php
		if (!empty($this->request->data['EmailTimelineSubject']['id'])) {
			echo $this->Form->hidden('id');
		}
		echo $this->Form->input('subject');
	?>
	</fieldset>
<?php echo $this->Form->end(array('label' => 'Submit', 'div' => false, 'class' => 'btn btn-sm btn-success')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('EmailTimelineSubject.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('EmailTimelineSubject.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Email Timeline Subjects'), array('action' => 'index')); ?></li>
	</ul>
</div>
