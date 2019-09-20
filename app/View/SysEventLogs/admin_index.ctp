<div class="panel panel-default">
	<div class="panel-heading"><u><strong><?php echo __('System Events Log');?></strong></u></div>
	<?php echo $this->Element('paginatorBottomNav'); ?>
	<table class="table table-condensed table-striped table-hover">
	<tr>
			<th><?php echo $this->Paginator->sort('User.fullname');?></th>
			<th><?php echo $this->Paginator->sort('EventType.description', 'Description');?></th>
            <th><?php echo 'IP Address'; ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Date Time');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($sysEventLogs as $logEntry):?>
	<tr>
		<td><?php echo $logEntry['User']['fullname']; ?>&nbsp;</td>
		<td><?php echo $logEntry['EventType']['description']; ?>&nbsp;</td>
		<td><?php echo $logEntry['SysEventLog']['client_ip']; ?>&nbsp;</td>
		<td><?php echo $this->Time->format($logEntry['SysEventLog']['created'], '%a %b %e, %Y %I:%M:%S %p'); ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</table>
	
	 <?php echo $this->Element('paginatorBottomNav'); ?>
