
<div class="panel panel-default center-block"  style="width:60%">
 <table class="table table-hover table-bordered">
		<tr class="text-center">
			<th colspan=4 class="text-center"><?php 
			echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> Add New Config',['action' => 'add', 'admin' => true],['escape' => false, "class" => "btn btn-info btn-sm"]);
			 ?></th>
			
		</tr>
		<tr class="bg-info text-center">
			<th class="text-center"><?php echo 'Integrated System Name'; ?></th>
			<th class="text-center"><?php echo 'Authentication Type'; ?></th>
			<th class="text-center"><?php echo 'Access token Issued on'; ?></th>
			<th class="text-center"><?php echo 'Lifetime in seconds'; ?></th>
			<th></th>
		</tr>
		<?php
		foreach ($apiConfigs as $apiConfig): ?>
		<tr class="strong">
			<td class="text-center"><?php echo h($apiConfig['ApiConfiguration']['configuration_name']); ?>&nbsp;</td>
			<td class="text-center"><span class="glyphicon glyphicon-lock"></span> <?php echo h($apiConfig['ApiConfiguration']['auth_type']); ?>&nbsp;</td>
			<td class="text-center"><?php 
			$timestamp = $apiConfig['ApiConfiguration']['issued_at'];
			$issDateTime = null;
			try {
				if (!empty($timestamp) && is_numeric($timestamp)) {
					$issDateTime = date("M d, Y h:i:s A", substr($timestamp,  0, 10));
				} elseif(!empty($timestamp)) {
					$issDateTime = DateTime::createFromFormat('M d, Y h:i:s A', $timestamp);
				}
			} catch (Exception $e) {}

			echo (!empty($issDateTime))? $issDateTime : 'N/A';

			?>&nbsp;</td>
			<td class="text-center">
				<?php echo (!empty($apiConfig['ApiConfiguration']['access_token_lifetime_seconds']))? $apiConfig['ApiConfiguration']['access_token_lifetime_seconds'] . ' secs' : '--'; ?>
			</td>
			<td><?php
				echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>',['admin' => true, 'action' => 'edit', $apiConfig['ApiConfiguration']['id']],['escape' => false, "class" => "btn btn-default btn-sm"]);
			?></td>
		</tr>
	<?php endforeach; ?>
	</table>	
  <div class="panel-footer text-center">
  	<?php echo $this->Element('paginatorBottomNav')?>
  </div>
</div>