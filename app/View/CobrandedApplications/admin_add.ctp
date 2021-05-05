<div class="container-fluid">
  <div class="row">

	<div class="col-md-12">
	  <!-- view page content -->
		<div class="panel panel-default">
			<div class="panel-heading"><u><strong><?php echo __('Admin Add Application')?></strong></u></div>
			<div class="panel-body">
				<?php 
					if (empty($this->request->params['pass'])) : ?>
						<div class="alert alert-info small">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							If data from this needs to sync between SalesForce and the DB, please create from SF.
						</div>
				<?php endif; ?>
				<?php
					echo $this->Form->create('CobrandedApplication', array(
							'inputDefaults' => array(
								'div' => 'form-group col-md-12',
								'label' => array('class' => 'col-md-3 control-label'),
								'wrapInput' => 'col-md-5',
								'class' => 'form-control input-sm',
							),
							'class' => 'form-horizontal',
						)); 
					echo $this->Form->input('user_id', array('id' => 'appCopyUserIdDropDown'));
					echo $this->Form->input('template_id', array('default' => $defaultTemplateId));
					$this->Form->unlockField('CobrandedApplication.client_id_to_verify');
					echo "<div class='hide' id='clientDataFields'>";
					echo $this->Form->input('client_id_to_verify', array(
						'label' => 'AxiaMed Client ID:',
						'type' => 'number',
						'maxlength' => 8,
						'required' => true,
						'oninput' => 'validateClientId(this)',
						'after' => '<div class="row small col-sm-7 col-md-7 col-lg-7" id="clIdValidationMsgs"></div>'
					));
					echo "</div>";
					$this->Form->unlockField('CobrandedApplication.client_id_global');
					echo $this->Form->hidden('client_id_global');
					$this->Form->unlockField('CobrandedApplication.client_name_global');
					echo $this->Form->hidden('client_name_global');
					echo $this->Form->hidden('uuid');
					if (isset($applicationId)) {
						echo $this->Form->hidden('applicationId');
					}
					echo $this->Html->link('Cancel', array('controller' => 'CobrandedApplications', 'action' => 'index'), array('class' => 'btn btn-sm btn-danger col-md-offset-4 col-sm-offset-4'));
					echo $this->Form->end(array('label' => __('Submit'), 'div' => false, 'class' => 'btn btn-sm btn-success', 'id' => 'appCopySubmitBtn'));
				?>
			</div>
		</div>
	</div>
  </div>
</div>
<script type="text/javascript">
	function validateClientId(inputObj) {
		inputObj.value=inputObj.value.slice(0,inputObj.maxLength);
		jQuery("#clIdValidationMsgs").html('');
		jQuery("#CobrandedApplicationClientIdGlobal").val('');
		jQuery("#CobrandedApplicationClientNameGlobal").val('');
		errMsg = '<span class="small text-danger bg-danger" id="clIdValidationError">';
		if (jQuery("#CobrandedApplicationClientIdToVerify").val().length === 8) {
			jQuery("#clIdValidationMsgs").html('<br/><div class="progress small"><div class="small progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">Validating ID from SalesForce...</div> </div>');
			jQuery.ajax({
				type: "POST",
				url: "/admin/CobrandedApplications/validate_client_id/"+ jQuery("#CobrandedApplicationClientIdToVerify").val(),
				dataType: 'html',
				success: function(result) {
					responseData = JSON.parse(result);
					if (responseData.valid === false) {
						jQuery("#clIdValidationMsgs").html(errMsg + "Invalid ID not found in SalesForce! Enter an existing AxiaMed client ID</span>");
					} else {
						msgHtml = '<br/><ul class="list-group"><li class="list-group-item list-group-item-success text-center"><img src="/img/test-pass-icon.png" class="pull-right" id="clIdValidated"> <strong>Client ID verified</strong></li>';
  						msgHtml += '<li class="list-group-item"><strong>' + responseData.Client_Name__c + '</strong></li></ul>';

						jQuery("#clIdValidationMsgs").html(msgHtml);
						jQuery("#CobrandedApplicationClientIdGlobal").val(responseData.Client_ID__c);
						jQuery("#CobrandedApplicationClientNameGlobal").val(responseData.Client_Name__c);
					}
				},
				error: function(data) {
					/*If user session expired the server will return a Unathorized status 401
				 	*Refreshing the page will redirect the user to the login page*/
					if (data.status === 401) {
					 	location.reload();
					}
					if (data.status === 500) {
						jQuery("#clIdValidationMsgs").html(errMsg + "Something went wrong validating Client ID! Try again later.</span>");
					}
					
				},
				cache: false
			});
		} else {
			jQuery("#clIdValidationMsgs").html(errMsg + "Invalid ID! Must be at least 8 digits!</span>");
		}
	}
	function requiresClientData(templateId) {
		jQuery('#appCopySubmitBtn').attr('disabled', 'disabled');
		jQuery('#appCopySubmitBtn').val('One moment...');
		jQuery.ajax({
			type: "POST",
			url: "/admin/Templates/is_client_data_required/"+templateId,
			dataType: 'html',
			success: function(result){
				jQuery('#appCopySubmitBtn').removeAttr('disabled');
				jQuery('#appCopySubmitBtn').val('Submit');
				toggleClientRequirement(result);
			},
			error: function(data) {
				/*If user session expired the server will return a Unathorized status 401
				 *Refreshing the page will redirect the user to the login page*/
				if(data.status === 401) {
				 	location.reload();
				}
			},
			cache: false
		});
	}
	function toggleClientRequirement(toggleOn) {
		if (toggleOn == 1) {
			jQuery("#clientDataFields").removeClass('hide');
			jQuery('#CobrandedApplicationClientIdToVerify').attr('required', true);

			$('#CobrandedApplicationAdminAddForm').on('submit', function(event) {
				if ($('#clIdValidated').length === 0) {
					jQuery("#clIdValidationMsgs").html('<strong class="text-danger">A valid Client ID is required to create an application with selected template!</strong>');
					event.preventDefault();
					jQuery('#appCopySubmitBtn').removeAttr('disabled');
				}
			});
		} else {
			jQuery("#clientDataFields").addClass('hide');
			$('#CobrandedApplicationAdminAddForm').off('submit');
			jQuery('#CobrandedApplicationClientIdToVerify').removeAttr('required');
		}
	}

	jQuery("#CobrandedApplicationTemplateId").change(function() {
		requiresClientData(jQuery(this).val())
	});

	jQuery("document").ready(function() {
		requiresClientData(jQuery("#CobrandedApplicationTemplateId").val());
	});


	jQuery("#appCopyUserIdDropDown").change(function(){
		userId=jQuery(this).val();
		jQuery.ajax({
			url: "/users/get_user_templates/"+userId,
			data: userId,
			success: function(result){
				jQuery("#CobrandedApplicationTemplateId").html(result);
			},
			cache: false
		});
	});
	jQuery('#appCopySubmitBtn').click(function() {
		jQuery('#CobrandedApplicationAdminAddForm').append('<input type="hidden" name="'+this.name+'" value="'+this.name+'" />');
		jQuery(this).attr('disabled', 'disabled');
		jQuery('#CobrandedApplicationAdminAddForm').submit();
	});
</script>