<div class="container-fluid">
  <div class="row">
  	<?php
  	$elVars = array(
		'navLinks' => array(
			'Add New Email Timeline Subject' => Router::url(array('action' => 'edit'))
		)
	);
	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="panel panel-default">
			<div class="panel-heading"><u><strong><?php echo __('Email Timeline Subjects')?></strong></u></div>
				<table class="table table-condensed table-striped table-hover">
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
							<?php echo $this->Html->link($this->Html->tag('span', '&nbsp', array('class' => 'glyphicon glyphicon-pencil')),
								array('action' => 'edit', $emailTimelineSubject['EmailTimelineSubject']['id']),
								array('escape' => false)); ?>
							<?php echo $this->Form->postLink($this->Html->tag('span', '&nbsp', array('class' => 'glyphicon glyphicon-remove text-danger')),
								array('action' => 'delete', $emailTimelineSubject['EmailTimelineSubject']['id']),
								array('escape' => false, 'confirm' => __('Are you sure you want to delete # %s?', $emailTimelineSubject['EmailTimelineSubject']['id']))); ?>
						</td>
					</tr>
				<?php endforeach; ?>
					</tbody>
					</table>
					<?php echo $this->Element('paginatorBottomNav'); ?>
		</div>
	</div>
  </div>
</div>