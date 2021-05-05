<div class="panel panel-info">
	<div class="panel-heading"><span class="glyphicon glyphicon-info-sign pull-left" style="font-size: 15pt"></span><strong>&nbsp;&nbsp; <?php echo __('More Information about ') . $app['Dba']['value']; ?></strong></div>
	<table class="table table-condensed table-hover">
		<tr>
			<th>Client ID/Name:</th>
			<td style="width: 400px;">
				<?php 
				if (!empty($app['CobrandedApplication']['client_id_global'])) {
						if ($clientIdValid) {
							echo $app['CobrandedApplication']['client_id_global'] ." - ". $app['CobrandedApplication']['client_name_global'];
						} else {
						?>
							<span id="clIdValidationMsgs" class="small"><strong class="text-danger">The client ID assigned to this app no longer exists in SalesForce, please update.</strong></span>
							<div class="form-inline" id="clientEditLinkContainer">
								<span class="glyphicon glyphicon-exclamation-sign text-danger"> </span><i><span class='text-muted'><?php echo $app['CobrandedApplication']['client_id_global'] ." - ". $app['CobrandedApplication']['client_name_global']; ?></span></i>&nbsp;&nbsp;&nbsp;<a class="btn btn-xs btn-success" href="#" onClick="jQuery('#clientEditLinkContainer').hide();jQuery('#clientEditFieldContainer').show();"><span class="glyphicon glyphicon-pencil"></span></a>
							</div>
							<div class="form-inline" id="clientEditFieldContainer" style="display: none">
								<input type="hidden" id="CobrandedApplication_id" value="<?php echo $app['CobrandedApplication']['id']?>">
								<input class="form-control input-sm" maxlength="8" type="number" id="CobrandedApplicationClientIdToVerify" required="required" value="<?php echo $app['CobrandedApplication']['client_id_global']?>">
								<a class="btn btn-sm btn-success" href="#" id="saveClIdBtn"><span class="glyphicon glyphicon-floppy-disk"></span></a>
							</div>
						<?php
						}
					
				} else {
					echo '<span class="text-muted text-center">N/A</span>';
				}
				?>
				
			</td>
		</tr>
		<tr>
			<th>Corp Contact Name</th>
			<td><?php echo (!empty($app['CorpContact']['value']))? $app['CorpContact']['value'] : $app['Owner1Name']['value']; ?></td>
		</tr>
		<tr>
			<th>Corp Phone #</th>
			<td><?php echo $app['CorpPhone']['value']; ?></td>
		</tr>
		<tr>
			<th>Location Phone #</th>
			<td><?php echo $app['PhoneNum']['value']; ?></td>
		</tr>
		<tr>
			<th>Email</th>
			<td><?php echo $app['EMail']['value']; ?></td>
		</tr>

	</table>
</div>
<script type='text/javascript'>
	jQuery("#saveClIdBtn").click(function() {
		updateClientId();
		
	});
	function updateClientId() {
		if (jQuery("#CobrandedApplicationClientIdToVerify").val().length === 8) {
			jQuery("#clIdValidationMsgs").html('<div class="progress small"><div class="small progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">Updating...</div> </div>');
			jQuery.ajax({
				type: "POST",
				url: "/admin/CobrandedApplications/update_client_id/"+ jQuery("#CobrandedApplication_id").val() +"/"+ jQuery("#CobrandedApplicationClientIdToVerify").val(),
				dataType: 'html',
				success: function(result) {
					responseData = JSON.parse(result);
					if (responseData.valid === false) {
						jQuery("#clIdValidationMsgs").html("<strong class='text-danger'>Invalid ID not found in SalesForce! Enter an existing client ID</strong>");
					} else {
						jQuery('#clientEditFieldContainer').hide();
						msgHtml = '<strong class="text-success"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;&nbsp;Client Updated!</strong><p>' + responseData.Client_ID__c +' - '+responseData.Client_Name__c + '</p>';

						jQuery("#clIdValidationMsgs").html(msgHtml);
						
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
			jQuery("#clIdValidationMsgs").html("<strong class='text-danger'>Invalid ID! Client IDs must be 8 digits.</strong>");
		}
	}
</script>