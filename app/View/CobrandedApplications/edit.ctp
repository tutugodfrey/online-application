
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
					<div>Fields marked with * are required.<br><br></div>

					<?php

						if ($methodName == 'create_rightsignature_document') {
							$validationErrorsArray = $this->Session->read('validationErrorsArray');

							echo "<script>";
							echo "var methodName = 'create_rightsignature_document';";
						
							echo "var allLabels = document.getElementsByTagName('label');";

							$repOnlyField = false;

							foreach ($validationErrorsArray as $arr) {
								echo "
									var pattern = /^".$arr['mergeFieldName']."/;
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
						
						if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
							echo $this->Html->link('Return to Applications Admin', array('controller' => 'cobranded_applications', 'action' => 'index', 'admin' => 'true')) . "<br/>"; 
						}

					?>
					
					<div id="actionButtons" align="right">
						<?php
							if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
								echo "<input type='button' onclick='fieldCompletion();' value='Email For Field Completion'>"."<br/>";
								echo "<input type='button' onclick='submit_for_signature();' value='Submit for Signature'>"."<br/>";

								$completeFieldsUrl = Router::url(array(
									'controller' => 'cobranded_applications',
									'action' => 'complete_fields',
									$this->request->data['CobrandedApplication']['id'],
									False
								));

								echo $this->Html->scriptBlock(
									"function fieldCompletion() {
										if (confirm('Send for completion to: ' + document.getElementById('Owner1Email').value)) {
											window.location = '".$completeFieldsUrl."';
										}
									}"
								);

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
											answer = confirm('This application has aleady been signed. Do you really want to resend?');
											if (!answer) return null
										}
										else if (" . ($this->request->data['CobrandedApplication']['rightsignature_document_guid'] ? '1' : '0') . ") {
											answer = confirm('This application has aleady been sent for signature. Do you really want to send it again?');
											if (!answer) return null
										}
										window.location = '".$submitForSigUrl."';
									}
								");
							}

							if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager')) || $values_map['AllowMerchantToSignApplication'] == 'true') {
								echo "<input type='button' onclick='signDocNow();' value='View and Sign Now'>";

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
										window.location = '".$signNowUrl."';
									}
			 					");
							}

							if (!in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager')) && $values_map['AllowMerchantToSignApplication'] != 'true') {
								echo "<input type='button' onclick='submitForReview();' value='Submit for Review'>";

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

<script type="text/javascript" src="/js/jquery-validate.1.11.11.js"></script>
<script type="text/javascript" src="/js/jquery-validate-additional-methods.js"></script>
<script type="text/javascript" src="/js/jquery.inputmask/dist/jquery.inputmask.bundle.js"></script>
<script type="text/javascript" src="/js/jquery.bootstrap.wizard.js"></script>
<script type="text/javascript" src="/js/cobrandedApplication.js"></script>

<link rel="stylesheet" type="text/css" href="/css/cobrandedApplication.css">
