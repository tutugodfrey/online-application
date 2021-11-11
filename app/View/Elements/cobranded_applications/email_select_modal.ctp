<?php
	$emailList = array();
	if (!empty($valuesMap)) {
		$emailList = array_combine(Hash::extract($valuesMap, "{s}"), Hash::extract($valuesMap, "{s}"));
		$emailList = Hash::filter($emailList);
	}
	$modalHeaderTxt = "Request access to my application(s)";
	if (!empty($this->Session->read('Auth.User.id'))) {
		$modalHeaderTxt = "Email to client for field Completion";
	}
	?>
	
		<!-- Modal -->
	<div class='modal fade' id='myModal_<?php echo $cobranded_application_id; ?>' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
      			<div class='modal-header'>
        			<h4 class='modal-title panel-title text-center' id='myModalLabel'>
        				<?php echo "<strong class='text-primary'>$modalHeaderTxt</strong>"; ?>
        			</h4>
      			</div>
      			<div class='modal-body' id='sendEmailModalDialog_<?php echo $cobranded_application_id; ?>'>
      				<div class="media panel-body">
						<div class="media-left">
							<img class="media-object" src="/img/mail-icon.png" style="width: 74px; height: 74px;">
						</div>
						<div class="media-body">
							<?php
				      			$thisFormId = 'emailToComplFrm_' . $cobranded_application_id;
								echo $this->Form->create('CobrandedApplication',
									array(
										'inputDefaults' => array(
											'div' => 'form-group',
											'wrapInput' => false,
											'class' => 'form-control'
										),
										'novalidate' => true,
										'default' => false,//prevent default submit this form is ajax only
				                    	'id' => $thisFormId,
				                    	'class' => 'form-inline'
									)
								);

								echo $this->Form->input('id',
									array(
										'type' => 'hidden',
										'default' => $cobranded_application_id,
										'id' => false
									)
								);

								$emailListDiv;
								$emailTextDiv;

								if (count($emailList) > 0) {
									$emailListDiv = array('id' => 'emailListDiv', 'style' => 'display:inline-block;');
									$emailTextDiv = array('id' => 'emailTextDiv', 'style' => 'display:none;');
								} else {
									$emailListDiv = array('id' => 'emailListDiv', 'style' => 'display:none;');
									$emailTextDiv = array('id' => 'emailTextDiv', 'style' => 'display:inline-block;');
								}

								echo $this->Form->input(
									'emailList',
									array(
										'options' => $emailList,
										'label' => 'Select Email Address',
										'type' => 'select',
										'div' => $emailListDiv,
										'id' => false
									)
								);

								echo $this->Form->input(
									'emailText',
									array(
										'label' => 'Please enter an email address:',
										'type' => 'text',
										'div' => $emailTextDiv,
										'id' => false,
										'required' => true
									)
								);
							?>
							<br/><br/><div id='ajaxEmailResponse<?php echo $cobranded_application_id; ?>' class='text-center'><!--Ajax Response will render here--></div>
						</div>
					</div>	
					<div class='modal-footer'>
				    	<button class='btn btn-default btn-sm' data-dismiss='modal'>Close</button>
						<?php echo $this->Form->end(array('label' =>'Send', 'div' => false, 'class' => 'btn btn-default btn-sm', 'id' => 'submitBtn')); ?>
					</div>
			</div>
		</div>
	</div>
</div>
	
<script type="text/javascript">
	$(document).ready(function () {
		var emailFrmId = '<?php echo $thisFormId; ?>';		
		$("#" + emailFrmId).bind("submit", function (event) {
			var cobrandedAppId = '<?php echo $cobranded_application_id; ?>';
			$('<img src=\'/img/refreshing.gif\'/>').appendTo( '#ajaxEmailResponse' + cobrandedAppId);
			$("#submitBtn").hide();
			$.ajax({
				type:"POST", 
				url:"\/cobranded_applications\/retrieve",
				async:true, 
				dataType:"html", 
				data:$("#" + emailFrmId).serialize(), 
				success:function (data, textStatus) {
					$("#ajaxEmailResponse" + cobrandedAppId).html(data);
					$("#submitBtn").show();
				}, 
			});
			return false;
		});
	});
</script>

