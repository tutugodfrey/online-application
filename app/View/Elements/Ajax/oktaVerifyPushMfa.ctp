<hr/>
<?php
echo $this->Html->image(
		'okta-logo.png',
		array(
			'alt' => __('Axia'),
			'border' => '0',
			'class' => 'center-block',
		)
	);
?>
<br/>
<p class='text-center'><strong>Okta Verify <?php echo "{$oktaMfaMeta['factors']['deviceName']}"?></strong></p>
<div class="alert alert-info text-center"><strong>Push notification sent!</strong></div>
<div class="alert alert-warning" style="display:none" id="alertDelayedVerification"><span class="glyphicon glyphicon-warning-sign pull-left" style="font-size:20pt; margin-bottom: 15%;margin-right: 10px;"></span><span class="small">Haven't received a push notification yet? Try opening the Okta app on your mobile device <?php echo "{$oktaMfaMeta['factors']['deviceName']}"?></span></div>
<div class="alert alert-danger" id="alertOktaMfaError" style="display:none">
    <span class="glyphicon glyphicon-exclamation-sign pull-left" style="font-size:20pt; margin-bottom: 15%;margin-right: 10px;"></span>
    <span class="small" id="mfaErrorMsg"></span>
</div>
	<div style="display: none;" id="oktaTOTPformWrapper">
		<?php 
			echo $this->Form->create('OktaTotp', array(
				'inputDefaults' => array(
					'div' => false,
					'wrapInput' => false,
					'label' => false,
					'class' => 'form-control',
				),
				'class' => 'form-horizontal'
			));

			echo $this->Form->input('User.okta_totp', array(
					'autofocus' => 'autofocus',
					'required' => true,
					'wrapInput' => 'form-group col-sm-10 col-md-10 col-lg-10 input-sm',
			));

			echo $this->Form->submit('Verify', array(
				'div' => 'input-sm col-md-3 col-sm-3 col-lg-3',
				'class' => 'btn btn-default',
				'id' => 'totpVerifyBtn'
			));

			echo $this->Form->end();
		?>
	</div>
<div class="form-group col-md-12 col-sm-12 col-lg-12 text-right">
	<span class="text-center center-block"><a onClick="$('#oktaTOTPformWrapper').show();$(this).hide();" href="#" class="small text-muted ">(Or enter code)</a><a href="/users/login" class="row small text-danger pull-right">Cancel</a></span>
</div>
<span id='responseResult'></span>
<script type="text/javascript">

$("#OktaTotpLoginForm").bind("submit", function (event) {
	$('#totpVerifyBtn').prop('disabled', 'disabled');
	$('#totpVerifyBtn').val('Verifying...');
	verifyOktaTotpFactor();
	return false;
});

$('#loginFrmContainer').hide();
window.pollCount = 0;
window.stopPolling = false;
function stopMfaChallengePolling() {
	window.stopPolling = true;
}
function pollMfaChallenge() {
  	setTimeout(function() {
  		window.pollCount += 1;
  		if (window.pollCount == 5) {
  			$('#alertDelayedVerification').show();
  		}
  		if (window.pollCount == 60) {
  			window.location = '/Users/login';
  		}
  		if (window.stopPolling == false) {
  			verifyOktaPushFactor();
  		}
    }, 4000);
}

function verifyOktaPushFactor () {
	$.ajax({
		async: true, 
		type: "POST", 
		url: "/Users/verify_okta_mfa/<?php echo $oktaMfaMeta['User']['id'].'/'.$oktaMfaMeta['stateToken'].'/'.$oktaMfaMeta['factors']['pushFactorId']; ?>",
		dataType: "html",
		success: function (data) {
			responseData = JSON.parse(data);
			if (responseData.okta_verify_status == 'SUCCESS' || responseData.okta_verify_status == 'ERROR') {
				window.location = responseData.redirect_url;
			} else if (responseData.okta_verify_status == 'WAITING') {
				pollMfaChallenge();
			}
		},
	});
}
function verifyOktaTotpFactor () {
	stopMfaChallengePolling();
	$("#alertOktaMfaError").hide();
	$("#mfaErrorMsg").html(' ');//clear any previews message html
	$.ajax({
		async: true, 
		type: "POST", 
		url: "/Users/verify_okta_mfa/<?php echo $oktaMfaMeta['User']['id'].'/'.$oktaMfaMeta['stateToken'].'/'.$oktaMfaMeta['factors']['totpFactorId']; ?>",
		dataType: "html",
		data: $("#OktaTotpLoginForm").serialize(),
		success: function (data, textStatus) {
		    responseData = JSON.parse(data);
			$('#totpVerifyBtn').removeProp('disabled', 'disabled');
			$('#totpVerifyBtn').val('Verify');
			$("#UserOktaTotp").parent().removeClass('has-error');
	    	if (responseData.hasOwnProperty('error_msg')) {
	    		$("#alertOktaMfaError").show();
				$("#mfaErrorMsg").html(responseData.error_msg);
				$("#UserOktaTotp").parent().addClass('has-error');
	    	}
			if (responseData.hasOwnProperty('redirect_url')) {
				window.location = responseData.redirect_url;
			}
		}
	});
}
verifyOktaPushFactor();
</script>