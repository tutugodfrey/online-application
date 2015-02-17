<?php echo $this->Html->css('app_status', null, array('inline' => false)); ?>
<?php echo $this->Html->script('tipsy'); ?>

<div id="dashboard_object_details">
 <div id="object_details_meat">   
	 <h3 style="text-align:center;"> <?php echo 'Document Status: ' . ucfirst($data['document']['state']); ?>
		<?php echo (ucfirst($data['document']['state'] == 'expired')) ? ' ' . $this->Html->link('Extend Application 7 days', 'app_status/' . $id . '/' . $guid):''; ?></h3>
     <h4 style="text-align:center;"><?php echo 'Document Created: ' . date("m/d/Y g:i A T", strtotime($data['document']['created_at']));?></h4>
     <h4 style="text-align:center;"><?php echo 'Document Expires: ' . date("m/d/Y g:i A T", strtotime($data['document']['expires_on']));?></h4>
    <table style="margin-left: auto; margin-right: auto;" class="basic-details" cellspacing="0">
      <thead>
        <tr>
          <td>Status</td>
          <td>Party</td>
          <td style="text-align:center;">Sent</td>
          <td style="text-align:center;">Viewed</td>
          <td style="text-align:center;">Signed</td>
        </tr>
      </thead>
      <tbody>

<?php

foreach ($recipients as $recipient) {
	if ($recipient['must_sign'] == 'true') {
		echo '<tr>';
		echo '<td class="padded" style="width:30px;">';
		echo ($recipient['state'] == 'signed') ? '<span class="party-icon signer-signed">&nbsp;</span>' : '<span class="party-icon signer-notsigned">&nbsp;</span>';
		echo '</td>';
		echo '<td class="padded party-name-email">';
		echo '<strong>' . $recipient['name'] . '</strong>';
		echo ($recipient['role_id'] == 'signer_A' || $recipient['role_id'] == 'signer_C') ? '<span>' . $app . '</span>' : '<span>' . $pg . '</span>';
		echo '</td>';
		echo '<td class="padded checkmark-area">';
		echo '&#10003;';
		echo '</td>';
		echo '<td class="padded checkmark-area">';
		echo ($recipient['viewed_at'] != '') ? '<span><a original-title="' . $recipient['viewed_at'] . '" class="has-tipsy" href="#" onclick="; return false;" style="color:#999;">&#10003;</a></span>' : '<span></span>';
		echo '</td>';
		echo '<td class="padded checkmark-area">';
		echo ($recipient['completed_at'] != '') ? '<span><a original-title="' . $recipient['completed_at'] . '" class="has-tipsy" href="#" onclick="; return false;" style="color:#58B200">&#10003;</a></span>' : '<span></span>';
		echo '</td>';
		echo '</tr>';
	}
}
?>
      
      </tbody>
    </table>
     </div>
    </div>
<?php 
	echo $this->Element('cobranded_applications/return');
?>
<script type='text/javascript'>
$(function() {
$('a[class*="has-tipsy"]').tipsy({gravity: 'w'});
});
</script>
