<div class="ApiLogs panel panel-default index">
	<div class="panel-heading"><u><strong><?php echo __('ApiLogs');?></strong></u></div>
	<?php echo $this->Element('paginatorBottomNav'); ?>
	<table class="table talbe-condensed table-hover">
	<tr>
			<th><?php echo $this->Paginator->sort('User.fullname');?></th>
                        <th><?php echo $this->Paginator->sort('auth_status');?></th>
                        <th><?php echo $this->Paginator->sort('ip_address');?></th>
			<th><?php echo $this->Paginator->sort('request_type');?></th>
                        <th><?php echo $this->Paginator->sort('request_url');?></th>
                        <th><?php echo $this->Paginator->sort('request_string');?></th>
                        <th><?php echo $this->Paginator->sort('created');?></th>
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
                <td><pre title='Double-click to display full string' ondblclick="jQuery(this).toggleClass('text-truncate')" style="background-color:transparent" class="text-truncate"><?php echo print_r(unserialize($apiLog['ApiLog']['request_string']),true);?></pre>
            </td>
                <td><?php echo $apiLog['ApiLog']['created']; ?>&nbsp;</td>

	</tr>
<?php endforeach; ?>
	</table>
	
	 <?php echo $this->Element('paginatorBottomNav'); ?>
