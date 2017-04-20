<?php

$stsPopoverId = "app_status_$appId";
echo $this->Html->link(__($appStatus),
		'#', 
		array(
			'id' => $stsPopoverId, 
			'onClick' => "getAppStatus('$appId')",
			'data-toggle' => 'popover',
			'data-trigger' => 'focus'
		)
	);
?>
<script>
	setElementPopOver('<?php echo $stsPopoverId; ?>');
</script>