<div class='modal fade' id='oktaEnrollModal' tabindex='-1' role='dialog' aria-labelledby='oktaEnrollModalLabel' aria-hidden='true'>
	<div class='modal-dialog'>
		<div class='modal-content' style="width:470px">
			<div class='modal-body' id='oktaEnrollModalBody'>
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
					<div style='margin-top:-15;'>
						<p class='text-center'><strong>Setup multifactor authentication.</strong></p>
						<p class='text-center'>Let's setup multifactor authentication (MFA) on your account to add an additional layer of security.</p>
						<p><strong><u>Setup Requirements:</u></strong></p>
						<div class="pull-left"><img src='/img/green_orb.gif'></div><div class="col-sm-11 col-md-11">From your mobile device download the Okta verify app then click the button below to begin setup. After setup, a push notification will be sent to the mobile app installed on your phone when you sing into this site to verify that it is really you signing in.</div>
						
						<div class="col-sm-12 col-md-12 col-lg-12"><br/></div>
						<div>
							<?php
							echo $this->Html->link('Configure Factor', ['controller' => 'Users', 'action' => 'okta_mfa_enroll', 'admin' => true], ['class' => 'btn btn-primary col-sm-12 col-md-12 col-lg-12']);
							?>
						</div>
						<div class="col-sm-12 col-md-12 col-lg-12"><br/></div>
					</div>
				</div>
					<a class="pull-right text-muted small" data-dismiss='modal' href="#">Skip for now</a>
			</div>
		</div>
	</div>
</div>