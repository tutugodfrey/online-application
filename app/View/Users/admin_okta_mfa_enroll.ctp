<div class='center-block' style="width:390px;margin-top:100px;">
	<div class='panel panel-default panel-body' style="padding: 20px 40px 0px 40px;">
		<?php
			echo $this->Html->image(
				'okta-logo.png',
				[
					'alt' => __('Axia'),
					'border' => '0',
					'class' => 'center-block',
				]
			);
		?>
		<hr/>
		<div id="notificationMsgs"></div>
		<div style='margin-top:-15;'>
			<p class='text-center'><strong>Setup Okta Verify</strong></p>
			<div class='well-lg center-block text-center text-info small' id="loadSpinner" style="display: none;">
				<span><img src="/img/refreshing.gif"><br>Contacting Okta...</span>
			</div>
			<div class='well-sm text-info small' style="display: none;" id="QrCodeContainer">
				<div class="text-center"><strong>Scan QR code:</strong></div>
				<div>Launch Okta Verify application on your mobile device, select Add an account, select account type and scan QR code.</div>
				<div class="panel panel-info panel-body text-center"><img src="/img/logo.png" id="oktaQrCodeImg" style="width: 200px;"></div>
			</div>
		</div>
	</div>
</div>

<script type='text/javascript'>
	$(document).ready(function(){
		$("#loadSpinner").show();
		$.ajax({
			async: true, 
			type: "POST", 
			url: "/admin/Users/okta_mfa_enroll/0",
			dataType: "html",
			success: function (data) {
				$("#loadSpinner").hide();
				$("#notificationMsgs").html(' ');//clear
				responseData = JSON.parse(data);
				if (responseData.hasOwnProperty('error')) {
					$("#notificationMsgs").html('<div class="alert alert-danger">' + responseData.error +'</div>');
				} else {
					window.pollActivationUrl = responseData.pollActivationUrl;
					pollFactorActivation()
					$("#oktaQrCodeImg").prop('src', responseData.qrcode);
					$("#QrCodeContainer").show();
				}
			}
		});
	});
	function pollFactorActivation() {
		$.ajax({
			async: true, 
			type: "POST", 
			url: "/admin/Users/okta_mfa_enroll/1",
			dataType: "html",
			data: {poll_activation_url: window.pollActivationUrl},
			success: function (data) {
				$("#notificationMsgs").html(' ');//clear
				responseData = JSON.parse(data);
				if (responseData.hasOwnProperty('error')) {
					$("#notificationMsgs").html('<div class="alert alert-danger">' + responseData.error +'</div>');
				} else {
					if (responseData.hasOwnProperty('factorResult') && responseData.factorResult == 'WAITING') {
						setTimeout(function() {
					  		pollFactorActivation();
					    }, 3000);
					}
					if (responseData.hasOwnProperty('status') && responseData.status == 'ACTIVE') {
						$("#oktaQrCodeImg").prop('src', '/img/green_check.png');
						window.location = '/admin/CobrandedApplications/index';
						$("#notificationMsgs").html('<div class="alert alert-success">Okta MFA has been activated! Thank you!</div>');
					}

				}
			}
		});
	}
</script>