<div class="panel panel-success">
	<div class="panel-body">
		<?php
			echo "<strong>API username/token:</strong><br/><pre>" . $apiToken . '</pre>';
			if (!empty($apiPassword)) :
			?>
				<strong>API password:</strong><br/>
				<pre id='apiPwRevealDestroy'><span class='small' id='apiRevealNote'>Click this box to automatically copy password to clipboard. <br/>(Password will not be revealed)</span><span id='apiPwMask' class='text-muted' style='display:none'><?php echo '<br/>' . $apiPassword; ?></span></pre>
		<?php endif; ?>
	</div> 
</div>
<script>
$("#apiPwRevealDestroy").click(function() {
		$('#apiRevealNote').addClass('text-danger strong');
		if ($('#apiPwMask').length) {
			$('#apiPwMask').css( "display", "block");
			$('#apiRevealNote').html('<strong>NOTICE: Password copied to clipboard, paste it in a secure location.<br/>For your security password can only be copied once.</strong>');
			temp = $("<input id='tmpCopyField' style='width:5px;font-size:1px'>");
			$("#apiPwRevealDestroy").append(temp);
			$('#tmpCopyField').val($('#apiPwMask').text());
			$('#tmpCopyField').select();
			document.execCommand("copy")
			$('#tmpCopyField').remove();
			$('#apiPwMask').remove();
		} else {
			$('#apiRevealNote').html('<strong>NOTICE: Password may no be copied again!<br/>Lost API password? Click "Regenerate".</strong>');
		}
		$("#apiPwRevealDestroy").mouseleave(function() {
			$('#apiRevealNote').removeClass('text-danger');
			$('#apiRevealNote').addClass('text-muted');
		});
	});
</script>