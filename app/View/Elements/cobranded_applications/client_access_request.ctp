
	
		<!-- Modal -->
	<div class='modal fade' id='myModal_' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
      			<div class='modal-header'>
        			<h4 class='modal-title panel-title text-center' id='myModalLabel'>
        				<?php echo "<strong class='text-primary'>Request access to my application(s)</strong>"; ?>
        			</h4>
      			</div>
      			<div class='modal-body' id='sendEmailModalDialog'>
      				<div class="media panel-body">
      					<div id='ajaxEmailResponse' class='text-center'><!--Ajax Response will render here--></div>
      					<p>Your username (also referred to as user token) will be used to locate your account, and an email will be sent with access instructions.<br/> Contact your sales representative if you do not know your user token.</p>
						<div class="media-left">
							<img class="media-object" src="/img/mail-icon.png" style="width: 74px; height: 74px;">
						</div>
						<div class="media-body">
							<?php
								echo $this->Form->create('CobrandedApplication',
									array(
										'inputDefaults' => array(
											'div' => 'form-group',
											'wrapInput' => false,
											'class' => 'form-control'
										),
										'novalidate' => true,
										'default' => false,//prevent default submit this form is ajax only
				                    	'id' => 'clientAccessRequestForm',
				                    	'class' => 'form-inline'
									)
								);

								echo $this->Form->input(
									'client_user_token',
									array(
										'label' => 'Enter your account username:',
										'type' => 'text',
										'id' => false,
										'required' => 'required',
										'autofocus'=> 'autofocus',
										'autocomplete' => 'off'
									)
								);
							?>
							<br/><br/>
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
		$("#clientAccessRequestForm").bind("submit", function (event) {
			$('<img src=\'/img/refreshing.gif\'/>').appendTo( '#ajaxEmailResponse');
			$("#submitBtn").hide();
			$.ajax({
				type:"POST", 
				url:"\/cobranded_applications\/retrieve_with_client_token",
				async:true, 
				dataType:"html", 
				data:$("#clientAccessRequestForm").serialize(), 
				success:function (data, textStatus) {
					$("#ajaxEmailResponse").html(data);
					$("#submitBtn").show();
				}, 
			});
			return false;
		});
	});
</script>

