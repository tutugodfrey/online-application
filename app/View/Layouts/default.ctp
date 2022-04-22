<!DOCTYPE html>
<html>
	<head>

		<?php echo $this->Html->charset(); ?>

		<title>
				<?php echo __('Axia - '); ?>
				<?php echo $title_for_layout; ?>
		</title>
		<link href="/favicon.ico" type="image/x-icon" rel="icon" >
		<link href="/favicon.ico" type="image/x-icon" rel="shortcut icon" >

		<link rel="stylesheet" type="text/css" href="/jquery-ui-1.13.1/jquery-ui.css"></link>
		<script src="/js/jquery-2.0.3.min.js"></script>
		
		<script src="/jquery-ui-1.13.1/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/js/onlineAppControls.js"></script>
		<script type="text/javascript" src="/js/uiContols.js"></script>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="/js/bootstrap.min.js"></script>

		<?php
				echo $this->Html->css('sessionMsg');
				echo $this->Html->css('bootstrapBased');
				if (array_key_exists('admin', $this->request->params) &&
						$this->request->params['admin'] === true) {
						?>
						<style type="text/css">
								div#content div.index table {
										font-size: 11px;
								}
						</style>
						<?php
				}
				else echo $this->Html->css('master.css');
		?>

		<?php
		echo $scripts_for_layout;
		if ($this->Session->read('Auth.User.id')!=''): ?>
			<script type='text/javascript'>
				var sessWorker;
				var c=0;
				var t;
				var timer_is_on=0;
				var remainingMins;

				function sessionWorkerStart(){
					//Stop any already running session workers
					sessionWorkerStop();
					//Start web worker
					sessWorker = new Worker("/js/sessionWorker.js");
					sessWorker.onmessage = function(event) {
						remainingMins = event.data;
						checkExpiringSession()
					}
				}

				function sessionWorkerStop(){
					if (sessWorker !== undefined){
						sessWorker.terminate();
						sessWorker = undefined;
					}
				}

				function sessionCount(){
					//set timeout at 15 minutes
					remainingMins = Math.floor((900 - c) / 60);
					checkExpiringSession();
					c=c+1;
					t=setTimeout("sessionCount()",990);//count down every 990 ms instead of 1000ms to compensate for potential client processor lag
				}
				function checkExpiringSession(){
					document.getElementById('sessCountDn').innerHTML= remainingMins + ' minutes';
					/*If half the total session timeout is reached*/
					if (remainingMins <= 5){
						document.getElementById('sessCountDn').innerHTML= '<b>' + remainingMins + ((remainingMins > 1) ? ' minutes.</b>' : ' minute.</b>');
						document.getElementById('msg_fader').style.display = 'block';
						document.getElementById('session_box').style.display = 'block';
					 }
					 if (remainingMins <= 0){
					 	sessionWorkerStop();
						this.t=null; //stops the t timeout Global Variable
						window.location='/users/logout';
						return;
					}
				}

				function sessionTimerInit(){
					//Internet Explorer 9 and earlier versions do not support Web Workers.
					//Check if browser supports workers
					if (typeof(Worker) !== "undefined") {
						sessionWorkerStart();
					} else {
						if (!timer_is_on){
							 timer_is_on=1;
							sessionCount();
						}
					}
				}

				function resetTimer(){
					if(remainingMins < 10){
							jQuery.ajax({
								type: 'POST',
								url: '/Pages/refreshSession',
								dataType: 'html',
								success: function(data) {
									if (typeof(Worker) == "undefined"){
										window.clearTimeout(t);
										c = 0;
										timer_is_on=0;
									}
									sessionTimerInit();
									hideExpirationNotice();
								}
							});
					}
				}

				function hideExpirationNotice(){
					var fader = document.getElementById('msg_fader');
					var session_box = document.getElementById('session_box');
					fader.style.display = 'none';
					session_box.style.display = 'none';
				}
				window.onload=function() {
						jQuery('body').on('click', resetTimer).on('keypress', resetTimer);
						sessionTimerInit();
				}
				</script>
		<?php endif; ?>
</head>
<body>
	<div id='countTST'></div>
	 <!-- Session notification Dialog Box -->
	 <?php if (Hash::get($this->request->params, 'api', false) === false ): ?>
	 <div id="msg_fader">&nbsp;</div>
	<div id="session_box" class="col-md-12">
		<div class="panel panel-danger">
			<div class="panel-heading text-center"><strong>Inactive session expiring!</strong></div>
			<div class="panel-body text-center">
				<h1 class="text-info"><span class="glyphicon glyphicon-time"></span></h1>
				 <span class="text-info" id="sessCountDn">&nbsp;</span>
				 <br/><span style="font-size: 10pt"><strong>Click or hit any key continue.</strong></span>
			</div> 
		</div>
	</div>
<?php endif; ?>

		<!-- End Session notification Dialog Box -->
		<!--  span id="loginBtn" class="btn">Need to login?</span -->
		<div id="container">
				<div id="header">
					<?php
					$additionalMarginTop = 0;
					if (!is_null($this->Session->read('Client.client_user_token'))) : ?>
							<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
								<span class="navbar-text navbar-right btn-group" style="margin-right:20px">
									<?php
									if ($this->request->params['action'] != 'index') {
										echo $this->Html->link(__('Go to Dashboard ') .' <span class="glyphicon glyphicon-th-list"></span>',
											array(
												'controller' => 'cobrandedApplications',
												'action' => 'index',
												$this->Session->read('Client.client_dashboard_id'),
												'admin' => false,
											),
											array(
												'escape' => false,
												'class' => 'btn btn-primary'
											)
										);
									}
									echo $this->Html->link(__('Sign out ') .' <span class="glyphicon glyphicon-log-out"></span>',
										array(
											'controller' => 'cobrandedApplications',
											'action' => 'cl_logout',
											'admin' => false,
										),
										array(
											'escape' => false,
											'class' => 'btn btn-default'
										)
									);
								?>
							</span>
						</nav>
					<?php 
					$additionalMarginTop = 45;
					endif; ?>
						<?php
						
						if ($this->Session->check('Auth.User.id')) :
							echo $this->Element('Ajax/dynamicModal');
						 ?>

								<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
									<span class="navbar-text">
									<?php echo $this->Html->getCrumbs(' > ', array('text' => _('Axia Admin Home'), 'url' => '/admin/')); ?>
									</span>
									<span class="navbar-text navbar-right btn-group" style="margin-right:20px">
										<?php
										echo $this->Html->link(__('Applications'),
											array(
												'controller' => 'cobrandedApplications',
												'action' => 'index',
												'admin' => true,
											),
											array(
												'class' => 'btn btn-default'
											)
										);
										echo $this->Html->link(__('Coversheets'),
											array(
												'controller' => 'coversheets',
												'action' => 'index',
												'admin' => true,
											),
											array(
												'class' => 'btn btn-default'
											)
										);
										echo $this->element('users/navbarDropDown');
									?>
								</span>
							</nav>
						<?php 
							$additionalMarginTop = 45;
						endif; ?>

								<div style="margin-top: <?php echo 25 + $additionalMarginTop; ?>px;">
								<?php
									if (!empty($brand_logo_url) || !empty($cobrand_logo_url)) {

										$brand_logo = $this->Html->image($brand_logo_url, array('class' => 'pull-right', "style" => "max-height:100px"));
										$cobrand_logo = $this->Html->image($cobrand_logo_url, array('height' => '50px'));

										if (strlen($cobrand_logo_url) == 0) {
											// no logo specified... use brand logo on the left
											echo CakeText::insert(
												'<div class="row">' .
												'<div class="col-md-12">:brand_logo</div>' .
												'</div>',
												array(
													'brand_logo' => $brand_logo
												)
											);
										} elseif ($include_brand_logo == true) {
											// only one way to display this combination
											if ($cobrand_logo_position < 3) {
												echo CakeText::insert(
													'<div class="row">' .
													'<div class="col-md-6">:cobrand_logo</div>' .
													'<div class="col-md-6">:brand_logo</div>' .
													'</div>',
													array(
														'cobrand_logo' => $cobrand_logo,
														'brand_logo' => $brand_logo
													)
												);
											}
											else {
												echo CakeText::insert(
													'<div class="row">' .
													'<div class="col-md-6">:brand_logo</div>' .
													'</div>',
													array(
														'brand_logo' => $brand_logo
													)
												);
											}
										} else {
											// position the logo left,center or right
											$logo_position = $cobrand_logo_position;
											if ($logo_position < 3) {
												echo CakeText::insert(
													'<div class="row">' .
													'<div class="col-md-12 text-:position">:cobrand_logo</div>' .
													'</div>',
													array(
														'cobrand_logo' => $cobrand_logo,
														'position' => $logoPositionTypes[$logo_position]
													)
												);
											}
										}
									}
								?>
							</div>
				</div>
				<div class="content">
						<?php echo $this->Session->flash(); ?>
						<?php echo $this->Session->flash('auth'); ?>
						<?php echo $this->fetch('content'); ?>
				</div>
				<div id="footer">
					
						<?php
							if ($this->request['action'] === 'cl_access_auth') {
								echo $this->Element('publicFooter');
							}
						?>
				</div>
		</div>
	<?php if ($this->Session->consume('Auth.User.Okta.needs_mfa_enrollment')): ?>
	<?php 
			echo $this->Element('users/oktaMfaEnrollPrompt');
	?>
		<script type='text/javascript'>
			$(document).ready(function(){
				$("#oktaEnrollModal").modal();
			});
		</script>
	<?php endif; ?>  
</body>
</html>
