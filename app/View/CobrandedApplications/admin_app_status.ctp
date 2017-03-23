<div>
 <div>
	<?php
	$contextStCssClass = 'text-info';
	if (ucfirst($data['document']['state'] == 'expired')) {
		$extensionLink =  $this->Html->tag('div',
				$this->Html->link('Extend Application 7 days', 'app_status/' . $id . '/' . $guid),
				array('class' => 'text-center'));
		$contextStCssClass = 'text-danger';
	} elseif (ucfirst($data['document']['state'] == 'pending')) {
		$contextStCssClass = 'text-warning';
	}
	echo $this->Html->tag('div', 
		$this->Html->tag('strong', 'Status: ') . $this->Html->tag('span', ucfirst($data['document']['state']), array("class" => $contextStCssClass)),
		array('class' => 'text-center'));

	if (isset($extensionLink)) {
		echo $extensionLink =  $this->Html->tag('div',
			$this->Html->link('Extend Application 7 days', 'app_status/' . $id . '/' . $guid),
			array('class' => 'text-center'));

	}
	?>
	<table class="table table-condensed table-hover" >
	  <thead>
		<tr>
		  <th>Party</th>
		  <th style="text-align:center;">Sent</th>
		  <th style="text-align:center;">Viewed</th>
		  <th style="text-align:center;">Signed</th>
		</tr>
	  </thead>
	  <tbody>
<?php
foreach ($recipients as $recipient) {
	if ($recipient['must_sign'] == 'true') {
		echo '<tr>';
		echo '<td class="">';
		echo '<strong>' . $recipient['name'] . '</strong>';
		echo ($recipient['role_id'] == 'signer_A' || $recipient['role_id'] == 'signer_C') ? '<br><small>' . $app . '</small>' : '<br><small>' . $pg . '</small>';
		echo '</td>';
		echo '<td class="text-center">';
		echo '&#10003;';
		echo '</td>';
		echo '<td class="text-center">';
		echo ($recipient['viewed_at'] != '') ? '<span><a data-toggle="tooltip" data-placement="bottom" data-original-title="' . $recipient['viewed_at'] . '" style="color:#999;">&#10003;</a></span>' : '<span></span>';
		echo '</td>';
		echo '<td class="text-center">';
		echo ($recipient['completed_at'] != '') ? '<span><a data-toggle="tooltip" data-placement="bottom" data-original-title="' . $recipient['completed_at'] . '" href="#" onclick="; return false;" style="color:#58B200">&#10003;</a></span>' : '<span></span>';
		echo '</td>';
		echo '</tr>';
	}
}
?>
	  </tbody>
	</table>
<?php
	echo $this->Html->tag('small', 
			$this->Html->tag('strong', 'Created: ') . $this->Html->tag('span', date("m/d/Y g:i A T", strtotime($data['document']['created_at'])), array("class" => 'text-success')),
			array('class' => 'center-block text-center'));
	echo $this->Html->tag('small', 
			$this->Html->tag('strong', 'Expiration: ') . $this->Html->tag('span', date("m/d/Y g:i A T", strtotime($data['document']['expires_on'])), array("class" => 'text-muted')),
			array('class' => 'center-block text-center'));
?>
	 </div>
	</div>

<script type='text/javascript'>
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();   
});
</script>
