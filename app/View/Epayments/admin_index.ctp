<div class="panel panel-default">
	<div class="panel-heading"><u><strong><?php echo __('Epayments') ?></strong></u></div>
	<table class="table table-condensed table-striped table-hover">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('pin'); ?></th>
			<th><?php echo $this->Paginator->sort('application_id'); ?></th>
			<th><?php echo $this->Paginator->sort('merchant_id'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('onlineapp_application_id'); ?></th>
			<th><?php echo $this->Paginator->sort('date_boarded'); ?></th>
			<th><?php echo $this->Paginator->sort('date_retrieved'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($epayments as $epayment): ?>
	<tr>
		<td><?php echo h($epayment['Epayment']['id']); ?>&nbsp;</td>
		<td><?php echo h($epayment['Epayment']['pin']); ?>&nbsp;</td>
		<td><?php echo h($epayment['Epayment']['application_id']); ?>&nbsp;</td>
		<td><?php echo h($epayment['Epayment']['merchant_id']); ?>&nbsp;</td>
		<td>
			<?php echo $epayment['User']['fullname']; ?>
		</td>
		<td><?php echo h($epayment['Epayment']['onlineapp_application_id']); ?>&nbsp;</td>
		<td><?php echo h($epayment['Epayment']['date_boarded']); ?>&nbsp;</td>
		<td><?php echo h($epayment['Epayment']['date_retrieved']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Form->postLink($this->Html->tag('span', '&nbsp', array('class' => 'glyphicon glyphicon-remove text-danger')),
				array('action' => 'delete', $epayment['Epayment']['id']), array('escape' => false,'confirm' => __('Are you sure you want to delete # %s?', $epayment['Epayment']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
<?php echo $this->Element('paginatorBottomNav'); ?>
</div>