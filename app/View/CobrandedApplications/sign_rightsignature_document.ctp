<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
        <?php if (!$alreadySigned) { ?>
	<div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="text-center">Welcome! Please sign your document.</h3>
		</div>
		<div class="panel-body">
			<div class="span-24 top">
				<h3 class="text-primary">Instructions:</h3>
				<ol>
					<li> Please review and submit signatures for all sections marked as <span class="text-danger"><strong>**Pending</strong></span> below.</li>
					<li>  <span class="text-danger"><strong>All the </strong></span><span class="text-success"><strong><u>green</u> </strong></span><span class="text-danger"><strong> buttons next to each signer name must be clicked.</strong></span></li>
					<li> On mobile devices the document will open in a new window, <strong>please return to this window when done and review any pending sections.</strong></li>
					<li> Each green button will take you through the sections of the document that need your signature, initials, and/or dates. </li>
					<li> Sign/Initial/Date all required fields, using your mouse for the signatures (click-and-drag) and click "<strong></span> Submit Signature</strong>" when done. </li>
					<li> This page may refresh automatically after each section is completed, <strong>repeat all steps above for all sections marked as <span class="text-danger">**Pending</span> below.</strong></li>
	                                                                
				</ol>
			</div>
            <?php } ?>
			<div class="span-24">
				<?php
				if ($error) {
					if ($alreadySigned) {
						echo '<div class="text-center bg-info" style="margin-top:100px;padding-top:90px; padding-bottom:100px">';
						echo "<h1><img src='/img/e-sign-icon.png' style='width:100px'><span class='glyphicon glyphicon-ok text-success' id='checkAllDone' style='display:none'></span></h1><h3 class='text-success'>Document is Signed!<br/>Thank you!</h3>";
						echo "<div>All parties have successfully signed this document, your representative has been notified.<br/>(If applicable, please refer to your representative's email for any final items and/or additional signature links)</div>";
						echo "</div>";
						echo '<script>$( "#checkAllDone" ).show( "clip", null,2000);</script>';
						echo '<script>$("body").css( "background-image", "linear-gradient(lightgrey, white, white, white, white, lightgrey)");</script>';
					} else {
						echo "<h3>Error</h3><div class=\"span-24\">";
						echo "Error: " . $apiErrorMsg;
						echo "</div>";
					}
				} else {

					foreach ($docDetails['document']['recipients'] as $signer) {
						if ($signer['sequence'] == '0'){
                           $signera = ($signer['status'] == 'pending')? $signer['name'] : null;
                           $signerA_Id = $signer['id'];
                           $signerA_URL = $signer['sign_url'];
                        }
                        if($signer['sequence'] == '1') {
                           $signerb = ($signer['status'] == 'pending')? $signer['name'] : null;
                           $signerB_Id = $signer['id'];
                           $signerB_URL = $signer['sign_url'];
						}
    	                    
                        if ($signer['sequence'] == '2'){
                           $signerc = ($signer['status'] == 'pending')? $signer['name'] : null;
                           $signerC_Id = $signer['id'];
                           $signerC_URL = $signer['sign_url'];                                            
                        }
                        
                        if($signer['sequence'] == '3') {
                           $signerd = ($signer['status'] == 'pending')? $signer['name'] : null;
                           $signerD_Id = $signer['id'];
                           $signerD_URL = $signer['sign_url'];					
                        } 
					}
						echo '<br/><div id="pendCountMsg" class="text-center center-block small" style="display:none"></div>';
                        if (!empty($signera)) {
                            if ($varSigner){
                                echo "<h3 class='text-primary'>Select Signer</h3><div class=\"span-24\">";
                                echo "<span class=\"span-5\"><span class='text-danger'><strong>**Pending App section signature from:</strong></span> Signer Name: " . $signera .  " - </span>";
            					if ($is_mobile_safari) {
									echo "<span class=\"span-8\"><a target='_blank' onclick=\"setCurSigner('$signerA_Id');\"  href=\"" . $signerA_URL . "\">Go to Sign Installation Sheet</a></span><strong>(Please return to this page when done with this section)</strong>";
								} else { // Makes signer link render in iframe in case we want to customize the look
									echo "<span class=\"span-8\"><a href=\"#\" onclick=\"change_signer('" . $signerA_URL . "'); setCurSigner('$signerA_Id');\">Sign Installation Sheet</a></span>";
								}
								echo "</div>";    
                            } else {
	                            echo "<h3 class='text-primary' name='pendingSignature'>Select Signer</h3><div class=\"span-24\">";
	                            echo "<span class=\"span-5\"><span class='text-danger'><strong>**Pending App section signature from:</strong></span> " . $signera .  ": </span>";
	        					if ($is_mobile_safari) {
									echo "<br /><span class=\"span-8\"><a class='btn btn-sm btn-success' onclick=\"setCurSigner('$signerA_Id');\" target='_blank' href=\"" . $signerA_URL . "\">Open Document to Sign _<span class='glyphicon glyphicon-pencil'></span></a></span><strong>(Please return to this page when done with this section)</strong>";
								} else { // Makes signer link render in iframe in case we want to customize the look
									echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"#\" onclick=\"change_signer('" . $signerA_URL . "'); setCurSigner('$signerA_Id');\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
								}
								echo "</div>";
                            }
                        }
	                                if (!empty($signerb)) {
	                                        echo "<h3 class='text-primary' name='pendingSignature'>Select Signer</h3><div class=\"span-24\">";
	                                        echo "<span class=\"span-5\"><span class='text-danger'><strong>**Pending PG section signature from:</strong></span> " . $signerb .  ": </span>";
										if ($is_mobile_safari) {
											echo "<br /><span class=\"span-8\"><a class='btn btn-sm btn-success' onclick=\"setCurSigner('$signerB_Id');\" target='_blank' href=\"" . $signerB_URL . "\">Open Document to Sign _<span class='glyphicon glyphicon-pencil'></span></a></span><strong>(Please return to this page when done with this section)</strong>";
										} else { // Makes signer link render in iframe in case we want to customize the look
											echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"#\" onclick=\"change_signer('" . $signerB_URL . "');setCurSigner('$signerB_Id');\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
										}
	                                        echo "</div>";
	                                }
	                                if (!empty($signerc)) {
	                                        echo "<h3 class='text-primary' name='pendingSignature'>Select Signer</h3><div class=\"span-24\">";
	                                        echo "<span class=\"span-5\"><span class='text-danger'><strong>**Pending App section signature from:</strong></span> " . $signerc .  ": </span>";
										if ($is_mobile_safari) {
											echo "<br /><span class=\"span-8\"><a class='btn btn-sm btn-success' onclick=\"setCurSigner('$signerC_Id');\"  target='_blank' href=\"" . $signerC_URL . "\">Open Document to Sign _<span class='glyphicon glyphicon-pencil'></span></a></span><strong>(Please return to this page when done with this section)</strong>";
										} else { // Makes signer link render in iframe in case we want to customize the look
											echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"#\" onclick=\"change_signer('" . $signerC_URL . "');setCurSigner('$signerC_Id');\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
										}
						echo "</div>";
	                                }
	                                if (!empty($signerd)) {
	                                        echo "<h3 class='text-primary' name='pendingSignature'>Select Signer</h3><div class=\"span-24\">";
	                                        echo "<span class=\"span-5\"><span class='text-danger'><strong>**Pending PG section signature from: </strong></span> " . $signerd .  ": </span>";
										if ($is_mobile_safari) {
											echo "<br /><span class=\"span-8\"><a class='btn btn-sm btn-success' onclick=\"setCurSigner('$signerD_Id');\" target='_blank' href=\"" . $signerD_URL . "\">Open Document to Sign _<span class='glyphicon glyphicon-pencil'></span></a></span><strong>(Please return to this page when done with this section)</strong>";
										} else { // Makes signer link render in iframe in case we want to customize the look
											echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"#\" onclick=\"change_signer('" . $signerD_URL . "');setCurSigner('$signerD_Id');\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
										}
						echo "</div>";
	                                }
				}
	                        
				?>
				<br/>
				<?php if (!$alreadySigned): ?>
				<div id="autoRefreshNotice" style="display:none">
				    <div style="max-width: 400px" class="center-block text-center">
				        <div class="panel panel-success">
				        	<div id="sectionSuccess" style="display:block">
					            <h3 class="text-success nowrap">Section completed, Thank you!</h3>
					            <strong>You will now be taken to the next step, or<br>press continue to go to next step.</strong><br><br>
				        	</div>
				        	<div id="autoRefreshError" style="display:none">
					            <h3 class="text-danger nowrap"><span class="glyphicon glyphicon-exclamation-sign"></span><br>Unexpected error occurred!</h3>
					            <strong>Sorry! Please press continue to<br/>refresh page and go to next steps.</strong><br><br>
				        	</div>
				        	<a href="javascript:void(0);" onclick="location.reload();" class="btn btn-info">
					                    Continue <span class="glyphicon glyphicon-refresh"></span></a>
					        <br><br>
				        </div>
				    </div>
				</div>
				<div id='frameContaner' style="display:none" class="row">
					<span id='leftSideNav' class="navbar navbar-default navbar-fixed-side">
						<a title="Go to top" class="btn btn-info" href="javascript:void(0)" onClick="scrollToBottomOrTop(false)">
							<span class="glyphicon glyphicon-triangle-top"></span>
						</a><br/>
						<a title="Go to Bottom" class="btn btn-info" href="javascript:void(0)" onClick="scrollToBottomOrTop(true)">
							<span class="glyphicon glyphicon-triangle-bottom"></span>
						</a>
					</span>
					<div class="row text-center" name="parentScrollBtns">
						<a class="btn btn-sm btn-info" href="javascript:void(0)" onClick="scrollToBottomOrTop(true)">
								<span class="glyphicon glyphicon-triangle-bottom">&nbsp;</span>
								Go To Bottom
						</a>
					</div>

					<div id="iframe_wrapper">
						<iframe frameborder="0" height="800" width="100%" scrolling="no" style="overflow:hidden" id="signing-widget"></iframe>
					</div>
					<div class="row text-center" name="parentScrollBtns">
						<a class="btn btn-sm btn-info" href="javascript:void(0)" onClick="scrollToBottomOrTop(false)">
								<span class="glyphicon glyphicon-triangle-top">&nbsp;</span>
								Back To Top
						</a>
					</div>
				</div>
				<?php endif;?>
			</div>
		</div>
	</div>
	</div>


<?php if(empty($this->Session->read('Auth.User.id'))):?>
	<div class='modal fade' id='dynamicModal' tabindex='-1' role='dialog' aria-labelledby='dynamicModalLabel' aria-hidden='true'>
		<div class='modal-dialog'>
			<div style='margin-top:200px'>
				<div class='modal-body panel panel-danger' id='dynamicModalBody'>
					
				</div>
			</div>
		</div>
	</div>
<?php endif;?>
	<script type="text/javascript">
		$(document).ready(function () {
			scrollToBottomOrTop(false);
			window.rsDocumentId = <?php echo "'{$docDetails['document']['id']}'"?>;
			pendCount = $('[name="pendingSignature"]').length;
			if (pendCount) {
				$('#pendCountMsg').show();
				$('#pendCountMsg').html('<div class="alert alert-info"><strong class="text-danger">Pending sections to review: ' + pendCount + '</strong></div>');
			}
		});
		function startPolling() {
		  if (window.stopPolling == false) {
		  	setTimeout(function(){
		  		isSignerDoneSigning();
		    }, 2000);
		  }
		}
		function setCurSigner(signerId) {
			window.curSignerId = signerId;
		    window.stopPolling = true;
		    setTimeout(function(){
		    	window.stopPolling = false;
		  		startPolling();
		    }, 6000);
		}
		function isSignerDoneSigning () {
			$.ajax({
				type: "POST",
				url: '/CobrandedApplications/signerHasSigned/' + window.rsDocumentId +'/'+ window.curSignerId,
				dataType: 'JSON',
				success: function(data) {
					if (data.signerHasSigned == false) {
						startPolling();
					} else {
						window.stopPolling = true;
						$('#frameContaner').hide();
						$('#autoRefreshNotice').show();
						location.reload();
					}
					
				},
				error: function(data) {
					console.log(data.error);
					window.stopPolling = true;
					$('#frameContaner').hide();
					$('#sectionSuccess').hide();
					$('#autoRefreshNotice').show();
					$('#autoRefreshError').show();
				}
			});
		}
		function change_signer(url) {
			$("#signing-widget").attr('src', url);
			$('#autoRefreshNotice').hide();
			$('#frameContaner').fadeIn(2000);
			window.addEventListener('beforeunload', function (e) {
				pendCount = $('[name="pendingSignature"]').length;
				loadBarHtml = '<div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"></div> </div>';
				feedbackMsg = 'Loading please wait..';
				if (pendCount >=2) {
					pendCount = pendCount -1;
					feedbackMsg = feedbackMsg + '<br/>There are more pending section(s).';
				}
				$('#dynamicModal').modal();

				$('#dynamicModalBody').html('<strong class="text-info text-center"><h3>' + feedbackMsg + '<h3></strong>' + loadBarHtml);
				// the absence of a returnValue property on the event will guarantee the browser unload happens
				delete e['returnValue'];
			});
			scrollToBottomOrTop(true);
			$('#leftSideNav').parent().css('height');
			$('#leftSideNav').css({
			    'top': parseInt($('#iframe_wrapper').css('height'))/2
			});
		}
		function scrollToBottomOrTop(bottom) {
			scrollVal = 0;
			if (bottom) {
				scrollVal = $('html, body').get(0).scrollHeight + 50 - parseInt($('#iframe_wrapper').css('height'));
			}

			$("html, body").animate({ 
                scrollTop: scrollVal 
            }, 1500);
		}
	</script>
<?php
	echo $this->Element('cobranded_applications/return');
?>
</body>
</html>
