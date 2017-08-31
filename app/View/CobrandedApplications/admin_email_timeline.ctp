<div class="panel panel-primary">
	<div class="text-center panel-heading"><u><strong><?php echo __('Email Timelines:');?></strong></u><br/><?php echo h($dba);?></div>
	<table class="table talbe-condensed table-hover">
	 <tr>
		 <th>Date Sent</th>
		 <th>Recipient(s)</th>
		 <th>Email Type</th>
	 </tr>
	<?php foreach($applications as $application): ?>
	 <?php foreach($application['EmailTimeline'] as $timeline): ?>
	<tr>
		<td><?php echo date('F j, Y g:i A',strtotime($timeline['date'])) ; ?></td>
		<td><?php echo (!empty($timeline['recipient'])) ? $timeline['recipient'] : 'No Address';?></td>
		<td><?php echo $timeline['EmailTimelineSubject']['subject'] ; ?></td>
	</tr>
	<?php endforeach; ?>
	<?php endforeach; ?>
	</table>
</div>