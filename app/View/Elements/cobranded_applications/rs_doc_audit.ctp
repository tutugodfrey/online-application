<div class="panel <?php echo ($docStatus == 'executed')?'panel-success': 'panel-default';?>">
	<div class="panel-heading">
		<u><strong><?php echo __('Document Audit')?></strong></u>
		<span class="nowrap pull-right <?php echo ($docStatus == 'executed')?'success text-success': 'active text-muted'; ?>" >
			<?php echo 'Status: ' . $docStatus; ?>
			<?php  echo ($docStatus == 'executed')? ' <img src="/img/test-pass-icon.png">':''; ?>

		</span>
	</div>
	<table class="table table-striped table-hover">
		<tr>
			<th>Name</th>
			<th>Action</th>
			<th>Date/Time</th>
		</tr>
		<?php 
		foreach ($auditTrail as $audit):
			if ($audit['keyword'] != 'executed'):
			?>
			<tr>
				<td>
					<?php
					echo substr($audit['payload'], 0, stripos ($audit['payload'], '('));
					?> 
				</td>
				<td>
					<?php echo $audit['keyword'] . ' document'; ?> 
				</td>
				<td>
					<?php echo date('m/d/Y h:i:s a', strtotime($audit['timestamp'])); ?> 
				</td>
			</tr>
		<?php endif; ?>
		<?php endforeach; ?>
	</table>
</div>