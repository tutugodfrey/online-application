<div id="progress-bar-top" class="progress" style="width: 120%;position: fixed;max-height: 15px;top: 0px;left: -10px;z-index: 999999;display:none;">
	<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
	</div>
</div>
<div class='container'>
	<div class="row">
		<section id="wizard">
			<form id="onlineapp" method="get" action="">
				<div id="rootwizard">
					<?php
					$numberOfPages = count($templatePages);
					if ($numberOfPages > 1) {
					?>
					<ul>
						<?php
						
						for ($index = 0; $index < $numberOfPages; $index ++) {
							$templatePage = $templatePages[$index];
							$pageDescription = $templatePage['description'];
							if (strlen($pageDescription) == 0) {
								$pageDescription = $templatePage['name'];
							}
							$displayIndex = 1 + $index;
							$displayText = $templatePage['name'];
						?>
							<li title="<?php echo $pageDescription ?>">
								<a href="#tab<?php echo $displayIndex ?>" data-toggle="tab">
									<span class="badge"><?php echo $displayIndex; ?></span>
									<div class="connecting-line"></div>
								</a>
							</li>
						<?php
						}
						?>
					</ul>

					<?php echo $this->Element('Templates/Pages/wizardPager') ?>
					<?php	
					} else {
						$templatePage = $templatePages[0];
						echo '<br />';
					}
					?>

					<div class="tab-content">

					<?php echo $this->Element('Templates/Pages/templatePage', array('numberOfPages' => $numberOfPages, 'templatePage' => $templatePage, 'requireRequiredFields' => $requireRequiredFields)) ?>

					</div>
					<div class="text-right text-muted small"><strong>(Fields marked with * are required).</strong><br></div>

					<?php

						if ($methodName == 'create_rightsignature_document') {
							$validationErrorsArray = $this->Session->read('validationErrorsArray');

							echo "<script>";
							echo "var methodName = 'create_rightsignature_document';";
						
							echo "var allLabels = document.getElementsByTagName('label');";

							$repOnlyField = false;

							foreach ($validationErrorsArray as $arr) {
								$arr['mergeFieldName'] = preg_replace('/\//', '\/', $arr['mergeFieldName']);

								echo "
									var pattern = /^".$arr['mergeFieldName']."$/;
									for (l in allLabels) {
										if (pattern.test(allLabels[l]['htmlFor'])) {
											allLabels[l].style.backgroundColor = '#FFFF00';
										}
									}
								";

								if (preg_match('/Combined Ownership Needs to Exceed/i', $arr['msg'])) {
									echo "document.getElementById('OWNER\\ /\\ OFFICER\\ (2)').innerHTML = '".$arr['msg']."' + document.getElementById('OWNER\\ /\\ OFFICER\\ (2)').innerHTML";
								}

								if (!empty($arr['rep_only']) && $arr['rep_only']) {
									$repOnlyField = true;
								}
							}

							if (!in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
								if (is_array($validationErrorsArray) && count($validationErrorsArray) > 0) {
									if ($repOnlyField == true) {
										echo "alert('Application must be completed by the sales representative.');";
									}
								}
							}

							echo "</script>";
						}
						
						echo $this->Element('cobranded_applications/return');

					?>
					
					<div id="actionButtons" align="right">
						<?php
							if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
								echo '<button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#myModal_'.$this->request->data['CobrandedApplication']['id'].'">Email For Field Completion</button><br/>';
								echo '<button type="button" id="submitForSigningBtn" onclick="submit_for_signature();" class="btn btn-sm btn-default">Submit for Signature</button><br/>';

								$submitForSigUrl = Router::url(array(
									'controller' => 'cobranded_applications',
									'action' => 'create_rightsignature_document',
									$this->request->data['CobrandedApplication']['id'],
									False
								));

								echo $this->Html->scriptBlock("
									function submit_for_signature() {
				
										if (". (isset($errors) && is_array($errors) ? '1' : '0') .") {
											alert('The application must be saved with all required fields completed before submitting for signature.');
											return null;
										}
										else if (" . ($this->request->data['CobrandedApplication']['status'] == 'signed' ? '1' : '0') . ") {
											answer = confirm('This application has already been signed. Do you really want to resend?');
											if (!answer) {
												return null;
											}
											else {
												$('#submitForSigningBtn').prop('disabled', true);
											}
										}
										else if (" . ($this->request->data['CobrandedApplication']['rightsignature_document_guid'] ? '1' : '0') . ") {
											answer = confirm('Send a reminder email to all signers, that they need to sign the application?');
											if (!answer) {
												return null;
											}
											else {
												$('#submitForSigningBtn').prop('disabled', true);
											}
										}
										$('#progress-bar-top').show();
										setTimeout(function(){
											$('#progress-bar-top').hide();
											window.location = '".$submitForSigUrl."';
											}, 1000);
									}
								");
							}

							if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager')) || Hash::get($valuesMap, 'AllowMerchantToSignApplication') == 'true') {
								echo '<button type="button" onclick="signDocNow();" class="btn btn-success" id="ViewSignNowBtn">View and Sign Now <img src="/img/signature-icon32px.png"></button>';

								$signNowUrl = Router::url(array(
									'controller' => 'cobranded_applications',
									'action' => 'create_rightsignature_document',
									$this->request->data['CobrandedApplication']['id'],
									True
								));
		
								echo $this->Html->scriptBlock("
									function signDocNow() {
			   							if (". (isset($errors) && is_array($errors) ? '1' : '0') .") {
											alert('The application must be saved with all required fields completed before submitting for signature.');
											return null;
										}
										else if (" . ($this->request->data['CobrandedApplication']['status'] == 'signed' ? '1' : '0') . ") {
											answer = confirm('This application has aleady been signed.');
											return null;
										}

										$('#ViewSignNowBtn').prop('disabled', true);

										$('#progress-bar-top').show();
										setTimeout(function(){
											$('#progress-bar-top').hide();
											window.location = '".$signNowUrl."';
											}, 1000);
									}
			 					");
							}

							if (!in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager')) && Hash::get($valuesMap, 'AllowMerchantToSignApplication') != 'true') {
								echo '<button type="button" id="submitForReviewBtn" onclick="submitForReview();" class="btn btn-default" >Submit for Review</button>';

								$submitForReviewUrl = Router::url(array(
									'controller' => 'cobranded_applications',
									'action' => 'submit_for_review',
									$this->request->data['CobrandedApplication']['id'],
									True
								));
		
								echo $this->Html->scriptBlock("
									function submitForReview() {
			   							if (". (isset($errors) && is_array($errors) ? '1' : '0') .") {
											alert('The application must be saved with all required fields completed before submitting for review.');
											return null;
										}
										else if (" . ($this->request->data['CobrandedApplication']['status'] == 'signed' ? '1' : '0') . ") {
											answer = confirm('This application has already been signed.');
											return null;
										}

										$('#submitForReviewBtn').prop('disabled', true);

										window.location = '".$submitForReviewUrl."';
									}
			 					");
							}
						?>
					</div>

					<?php
						if ($numberOfPages > 1) {
							echo $this->Element('Templates/Pages/wizardPager');
						}
					?>

					<?php
						echo $this->Html->scriptBlock("
							function copyCorpToLocFields() {
								if ($('#loc_same_as_corp').prop('checked')) {
									$('#DBA').val($('#CorpName').val());
									$('#Address').val($('#CorpAddress').val());
									$('#City').val($('#CorpCity').val());
									$('#State').val($('#CorpState').val());
									$('#Zip').val($('#CorpZip').val());
									$('#PhoneNum').val($('#CorpPhone').val());
									$('#FaxNum').val($('#CorpFax').val());
									$('#Contact').val($('#CorpContact').val());
									$('#LocTitle').val($('#Title').val());
									$('#LocEMail').val($('#EMail').val());

									$('#DBA').trigger('change');
									$('#Address').trigger('change');
									$('#City').trigger('change');
									$('#State').trigger('change');
									$('#Zip').trigger('change');
									$('#PhoneNum').trigger('change');
									$('#FaxNum').trigger('change');
									$('#Contact').trigger('change');
									$('#LocTitle').trigger('change');
									$('#LocEMail').trigger('change');
								}
								else {
									$('#DBA').val('');
									$('#Address').val('');
									$('#City').val('');
									$('#State').val('');
									$('#Zip').val('');
									$('#PhoneNum').val('');
									$('#FaxNum').val('');
									$('#Contact').val('');
									$('#LocTitle').val('');
									$('#LocEMail').val('');

									$('#DBA').trigger('change');
									$('#Address').trigger('change');
									$('#City').trigger('change');
									$('#State').trigger('change');
									$('#Zip').trigger('change');
									$('#PhoneNum').trigger('change');
									$('#FaxNum').trigger('change');
									$('#Contact').trigger('change');
									$('#LocTitle').trigger('change');
									$('#LocEMail').trigger('change');
								}
							}

							function copyDepositoryToFeesFields() {
								if ($('#fees_same_as_depository').prop('checked')) {
									$('#FeesRoutingNum').val($('#RoutingNum').val());
									$('#FeesAccountNum').val($('#AccountNum').val());
									
									$('#FeesRoutingNum').trigger('change');
									$('#FeesAccountNum').trigger('change');
								}
								else {
									$('#FeesRoutingNum').val('');
									$('#FeesAccountNum').val('');
									
									$('#FeesRoutingNum').trigger('change');
									$('#FeesAccountNum').trigger('change');
								}
							}
						");
					?>
				</div>
			</form>
		</section>
	</div>
</div>
<?php 
if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
	$cAppVals = Hash::get($this->request->data, 'CobrandedApplicationValues');
	$custEmails['Owner1Email'] = Hash::get(Hash::extract($cAppVals, '{n}[name=Owner1Email].value'), '0');
	$custEmails['Owner2Email'] = Hash::get(Hash::extract($cAppVals, '{n}[name=Owner2Email].value'), '0');
	$custEmails['EMail'] = Hash::get(Hash::extract($cAppVals, '{n}[name=EMail].value'), '0');
	$custEmails['LocEMail'] = Hash::get(Hash::extract($cAppVals, '{n}[name=LocEMail].value'), '0');

	echo $this->element('cobranded_applications/email_select_modal',
		array(
			'cobranded_application_id' => $this->request->data['CobrandedApplication']['id'],
			'valuesMap' => $custEmails
		)
	);
}
?>

<script type="text/javascript" src="/js/jquery-validate.1.11.11.js"></script>
<script type="text/javascript" src="/js/jquery-validate-additional-methods.js"></script>
<script type="text/javascript" src="/js/jquery.inputmask/dist/jquery.inputmask.bundle.js"></script>
<script type="text/javascript" src="/js/jquery.bootstrap.wizard.js"></script>
<script type="text/javascript" src="/js/cobrandedApplication.js"></script>

<link rel="stylesheet" type="text/css" href="/css/cobrandedApplication.css">
