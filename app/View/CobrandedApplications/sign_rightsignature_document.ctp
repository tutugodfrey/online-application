<html>
<head>
	<!--<?//php common_styles(); ?>-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
</head>
<body>
	<!--<?php if ($error)
		//login_bar();
	?>-->
        <? if (!$alreadySigned) { ?>
	<div class="container">
	<div class="panel panel-primary">
		<div class="panel-heading bottom">
			<h3 class="bottom">Welcome! Please sign your document.</h3>
		</div>
		<div class="panel-body">
			<div class="span-24 top">
				<h3 class="text-primary">Instructions:</h3>
				<ol>
					<li> Select the Person to sign </li>
					<li> Click on the <span class="label label-danger">Scroll <span class="glyphicon glyphicon-arrow-down"></span></span> icon to guide you to each required field. </li>
					<li> Complete all required fields and digitally sign the document with your mouse. When done click "<strong><span class="text-success"><span class="glyphicon glyphicon-ok"></span></span> SUBMIT SIGNATURE</strong>". </li>
	                                                                
				</ol>
			</div>
            <?php } ?>
			<div class="span-24">
				<?php
				if ($error) {
					if ($alreadySigned) {
						echo "<h3>Document is Signed</h3><div class=\"span-24\">";
						echo "All Signers have Successfully Signed. <br/>";
						echo "</div>";
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
	                            echo "<h3 class='text-primary'>Select Signer</h3><div class=\"span-24\">";
	                            echo "<span class=\"span-5\"><span class='text-primary'><strong>Name</strong></span>: " . $signera .  " Signature 1 - App: </span>";
	        					if ($is_mobile_safari) {
									echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokena . "\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
								} else { // Makes signer link render in iframe in case we want to customize the look
									echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokena . "')\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
								}
								echo "</div>";
                            }
	                                }
	                                if (!empty($signerb)) {
	                                        echo "<h3 class='text-primary'>Select Signer</h3><div class=\"span-24\">";
	                                        echo "<span class=\"span-5\"><span class='text-primary'><strong>Name</strong></span>: " . $signerb .  " Signature 1 - PG: </span>";
	                                        					if ($is_mobile_safari) {
							echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokenb . "\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
						} else { // Makes signer link render in iframe in case we want to customize the look
							echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokenb . "')\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
						}
	                                        echo "</div>";
	                                }
	                                if (!empty($signerc)) {
	                                        echo "<h3 class='text-primary'>Select Signer</h3><div class=\"span-24\">";
	                                        echo "<span class=\"span-5\"><span class='text-primary'><strong>Name</strong></span>: " . $signerc .  " Signature 2: - App </span>";
	                                            					if ($is_mobile_safari) {
							echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokenc . "\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
						} else { // Makes signer link render in iframe in case we want to customize the look
							echo "<span class=\"span-8\"><a class='btn btn-sm btn-success' href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokenc . "')\">Sign Document _<span class='glyphicon glyphicon-pencil'></span></a></span>";
						}
						echo "</div>";
	                                }
	                                if (!empty($signerd)) {
	                                        echo "<h3 class='text-primary'>Select Signer</h3><div class=\"span-24\">";
	                                        echo "<span class=\"span-5\"><span class='text-primary'><strong>Name</strong></span>: " . $signerd .  " Signature 2 - PG: </span>";
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
						</ul>
					</div>
				<?php endif;?>
					<iframe width="<?php echo $widgetWidth; ?>px" scrolling="no" height="<?php echo $widgetHeight; ?>px" frameborder="0" id="signing-widget"></iframe>
				</div>
			</div>
		</div>
	</div>
	</div>
	
	<script type="text/javascript">
		function change_signer(url) {
			$("#signing-widget").attr('src', url);
			$('#documentToolKit').fadeIn(2000);
		}
	</script>
<?php
	echo $this->Element('cobranded_applications/return');
?>
</body>
</html>
