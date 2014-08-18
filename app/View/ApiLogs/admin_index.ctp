<div class="ApiLogs index">
	<h2><?php echo __('ApiLogs');?></h2>
	<?php echo $this->Element('paginatorBottomNav'); ?>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('User.fullname');?></th>
                        <th><?php echo $this->Paginator->sort('auth_status');?></th>
                        <th><?php echo $this->Paginator->sort('ip_address');?></th>
			<th><?php echo $this->Paginator->sort('request_type');?></th>
                        <th><?php echo $this->Paginator->sort('request_url');?></th>
                        <th><?php echo $this->Paginator->sort('request_string');?></th>
                        <th><?php echo $this->Paginator->sort('created');?></th>

			<!--<th class="actions"><?php echo __('Actions');?></th>-->
	</tr>
	<?php
	$i = 0;
	foreach ($apiLogs as $apiLog):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $apiLog['User']['fullname']; ?>&nbsp;</td>
                <td><?php echo $apiLog['ApiLog']['auth_status']; ?>&nbsp;</td>
                <td><?php echo $apiLog['ApiLog']['ip_address']; ?>&nbsp;</td>
                <td><?php echo $apiLog['ApiLog']['request_type']; ?>&nbsp;</td>
                <td><?php echo $apiLog['ApiLog']['request_url']; ?>&nbsp;</td>
                <td><?php print("<pre style=\"background-color:transparent;\">".print_r(unserialize($apiLog['ApiLog']['request_string']),true)."</pre>");?>&nbsp;</td>
                <td><?php echo $apiLog['ApiLog']['created']; ?>&nbsp;</td>

	</tr>
<?php endforeach; ?>
	</table>
	
	 <?php echo $this->Element('paginatorBottomNav'); ?>
