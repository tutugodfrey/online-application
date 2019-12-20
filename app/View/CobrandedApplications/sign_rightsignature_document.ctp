<html>
<head>
	<!--<?//php common_styles(); ?>-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
	<!--<?php if ($error)
		//login_bar();
	?>-->
        <?php if (!$alreadySigned) { ?>
	<div class="container">
	<div class="panel panel-primary">
		<div class="panel-heading bottom">
			<h3 class="bottom">Welcome! Please sign your document.</h3>
		</div>
		<div class="panel-body">
			<div class="span-24 top">
				<h3 class="text-primary">Instructions:</h3>
				<ol>
					<li> Please review and submit signatures for all sections marked as <span class="text-danger"><strong>**Pending</strong></span> below.</li>
					<li>  <span class="text-danger"><strong>All the </strong></span><span class="text-success"><strong><u>green</u> Sign Document </strong></span><span class="text-danger"><strong> buttons next to all signer names must be clicked.</strong></span></li>
					<li> Each green button will take your through a defierent section of the document that needs your signature, initials, and/or dates. </li>
					<li> After clicking each green button, click/follow the <span class="label label-danger">Scroll <span class="glyphicon glyphicon-arrow-down"></span></span> tab on the left edge of the document; it will guide you to each required field. </li>
					<li> Sign/Initial/Date all required fields, using your mouse for the signatures (click-and-drag).  When done click "<strong><span class="text-success"><span class="glyphicon glyphicon-ok"></span></span> SUBMIT SIGNATURE</strong>". </li>
					<li> The page will then refresh automatically, <strong>repeat all steps above for all sections marked as <span class="text-danger">**Pending</span> below.</strong></li>
	                                                                
				</ol>
			</div>
            <?php } ?>
			<div class="span-24">
				<?php
				if ($error) {
					if ($alreadySigned) {
						echo '<div class="text-center bg-info" style="margin-top:100px;padding-top:100px; padding-bottom:100px">';
						echo "<h1><img src='/img/e-sign-icon.png' style='width:100px'><span class='glyphicon glyphicon-ok text-success' id='checkAllDone' style='display:none'></span></h1><h3 class='text-success'>Document is Signed!</h3>";
						echo "<div>All signers have successfully signed, your representative has been notified.<br/>(If applicable, please refer to your representative's email for any final items and/or additional signature links)<br/>Thank you!</div>";
						echo "</div>";
						echo '<script>$( "#checkAllDone" ).show( "clip", null,2000);</script>';
					} else {
						echo "<h3>Error</h3><div class=\"span-24\">";
						echo "Error:" . Xml::fromArray(message);
						echo "</div>";
					}
				} else { 
	                                if(Hash::dimensions($xml) == 5) {
	                                    $rsarray = $xml['document']['signer-links']['signer-link'];
	                                } else {
	                                    $rsarray = $xml['document']['signer-links'];
	                                }
	                                
					foreach ($rsarray as $signer) { 

						if ($signer['role'] == 'signer_A'){
	                                           $signera = $signer['name'];
	                                           $tokena = $signer['signer-token'];
	                                        }
	                                        if($signer['role'] == 'signer_B') {
	                                           $signerb = $signer['name'];
	                                           $tokenb = $signer['signer-token'];
						}
	                                        
	                                        if ($signer['role'] == 'signer_C'){
	                                           $signerc = $signer['name'];
	                                           $tokenc = $signer['signer-token'];                                            
	                                        }
	                                        
	                                        if($signer['role'] == 'signer_D') {
	                                           $signerd = $signer['name'];
	                                           $tokend = $signer['signer-token'];					
	                                        } 
	//                                        
	//					// Makes signer link redirect to RightSignature's link which handles all the odd cases (zooming, scrolling, etc...) that can make mess up Widget
	//				/*	if ($is_mobile_safari) {
	//						echo "<span class=\"span-8\"><a href=\"$rightsignature->base_url/signatures/embedded?rt=" . $this->Signer->{'signer-token'} . "\">Sign Document</a></span>";
	//					} else { // Makes signer link render in iframe in case we want to customize the look
	//						echo "<span class=\"span-8\"><a href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $this->Signer->{'signer-token'} . "')\">Sign Document</a></span>";
	//					}
	//					echo "</div>";*/
					}
						echo '<br/><div id="pendCountMsg" class="text-center center-block small" style="display:none"></div>';
                        if (!empty($signera)) {
                            if ($varSigner){
                                echo "<h3 class='text-primary'>Select Signer</h3><div class=\"span-24\">";
                                echo "<span class=\"span-5\">Signer Name: " . $signera .  " - </span>";
            					if ($is_mobile_safari) {
									echo "<span class=\"span-8\"><a href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokena . "\">Sign Installation Sheet</a></span>";
								} else { // Makes signer link render in iframe in case we want to customize the look
									echo "<span class=\"span-8\"><a href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokena . "')\">Sign Installation Sheet</a></span>";
								}
								echo "</div>";    
                            } else {
	                            echo "<h3 class='text-primary' name='pendingSignature'>Select Signer</h3><div class=\"span-24\">";
	                            echo "<span class=\"span-5\"><span class='text-danger'><strong>**Pending App section signature from:</strong></span> " . $signera .  ": </span>";
	        					if ($is_mobile_safari) {
									echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokena . "\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
								} else { // Makes signer link render in iframe in case we want to customize the look
									echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokena . "')\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
								}
								echo "</div>";
                            }
	                                }
	                                if (!empty($signerb)) {
	                                        echo "<h3 class='text-primary' name='pendingSignature'>Select Signer</h3><div class=\"span-24\">";
	                                        echo "<span class=\"span-5\"><span class='text-danger'><strong>**Pending PG section signature from:</strong></span> " . $signerb .  ": </span>";
										if ($is_mobile_safari) {
											echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokenb . "\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
										} else { // Makes signer link render in iframe in case we want to customize the look
											echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokenb . "')\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
										}
	                                        echo "</div>";
	                                }
	                                if (!empty($signerc)) {
	                                        echo "<h3 class='text-primary' name='pendingSignature'>Select Signer</h3><div class=\"span-24\">";
	                                        echo "<span class=\"span-5\"><span class='text-primary'><strong>**Pending App section signature from:</strong></span> " . $signerc .  ": </span>";
										if ($is_mobile_safari) {
											echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokenc . "\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
										} else { // Makes signer link render in iframe in case we want to customize the look
											echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokenc . "')\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
										}
						echo "</div>";
	                                }
	                                if (!empty($signerd)) {
	                                        echo "<h3 class='text-primary' name='pendingSignature'>Select Signer</h3><div class=\"span-24\">";
	                                        echo "<span class=\"span-5\"><span class='text-primary'><strong>**Pending PG section signature from: </strong></span> " . $signerd .  ": </span>";
										if ($is_mobile_safari) {
											echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokend . "\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
										} else { // Makes signer link render in iframe in case we want to customize the look
											echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokend . "')\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
										}
						echo "</div>";
	                                }


				}
	                        
				?>
				<br/>
				<div class="span-24">
					<?php if (!empty($appPdfUrl) && !$alreadySigned): ?>
					<div class="panel panel-success pull-right" id="documentToolKit" style="display:none">
						<div class="panel-heading">
							<strong>Tools<span class="glyphicon glyphicon-cog pull-right"></span></strong>
						</div>
						<ul class="list-group">
							<li class="list-group-item">
								<span class="glyphicon glyphicon-new-window">&nbsp;</span>
								<a target="_blank" href=<?php echo "'$appPdfUrl'"; ?>>View larger <strong>Read-Only</strong> Document</a>
							</li>
							<li class="list-group-item">
								<span class="glyphicon glyphicon-hand-up">&nbsp;</span>
								<a href="javascript:void(0)" onClick="scrollToBottomOrTop(false)">Go To Top of Page</a>
							</li>
							<li class="list-group-item">
								<span class="glyphicon glyphicon-hand-down">&nbsp;</span>
								<a href="javascript:void(0)" onClick="scrollToBottomOrTop(true)">Go To Bottom of Page</a>
							</li>
						</ul>
					</div>
				<?php endif;?>
					<iframe width="<?php echo $widgetWidth; ?>px" scrolling="no" height="<?php echo $widgetHeight; ?>px" frameborder="0" id="signing-widget"></iframe>
				</div>
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
			pendCount = $('[name="pendingSignature"]').length;
			if (pendCount) {
				$('#pendCountMsg').show();
				$('#pendCountMsg').html('<div class="alert alert-info"><strong class="text-danger">Pending sections to review: ' + pendCount + '</strong></div>');
			}

		});
		function change_signer(url) {
			$("#signing-widget").attr('src', url);
			$('#documentToolKit').fadeIn(2000);
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
		}
		function scrollToBottomOrTop(bottom) {
			scrollVal = 0;
			if (bottom) {
				scrollVal = $('html, body').get(0).scrollHeight;
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
