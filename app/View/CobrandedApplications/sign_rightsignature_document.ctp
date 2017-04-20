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
		<div class="span-24 bottom">
			<h2 class="bottom">Please Sign the document</h2>
		</div>
		<div class="span-24 top">
			<ol>
				<li> Select the Person to sign </li>
				<li> Click on the Scroll icon to take you to each required field. </li>
				<li> Complete all required fields and sign the document with your mouse and click 'Submit'. </li>
                                                                
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
                                        echo "<h3>Select Signer</h3><div class=\"span-24\">";
                                        echo "<span class=\"span-5\">Signer Name: " . $signera .  " - </span>";
                                            					if ($is_mobile_safari) {
						echo "<span class=\"span-8\"><a href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokena . "\">Sign Installation Sheet</a></span>";
					} else { // Makes signer link render in iframe in case we want to customize the look
						echo "<span class=\"span-8\"><a href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokena . "')\">Sign Installation Sheet</a></span>";
					}
					echo "</div>";    
                                        } else {
                                        echo "<h3>Select Signer</h3><div class=\"span-24\">";
                                        echo "<span class=\"span-5\">Name: " . $signera .  " Signature 1 - App: </span>";
                                            					if ($is_mobile_safari) {
						echo "<span class=\"span-8\"><a href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokena . "\">Sign Document</a></span>";
					} else { // Makes signer link render in iframe in case we want to customize the look
						echo "<span class=\"span-8\"><a href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokena . "')\">Sign Document</a></span>";
					}
					echo "</div>";
                                }
                                }
                                if (!empty($signerb)) {
                                        echo "<h3>Select Signer</h3><div class=\"span-24\">";
                                        echo "<span class=\"span-5\">Name: " . $signerb .  " Signature 1 - PG: </span>";
                                        					if ($is_mobile_safari) {
						echo "<span class=\"span-8\"><a href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokenb . "\">Sign Document</a></span>";
					} else { // Makes signer link render in iframe in case we want to customize the look
						echo "<span class=\"span-8\"><a href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokenb . "')\">Sign Document</a></span>";
					}
                                        echo "</div>";
                                }
                                if (!empty($signerc)) {
                                        echo "<h3>Select Signer</h3><div class=\"span-24\">";
                                        echo "<span class=\"span-5\">Name: " . $signerc .  " Signature 2: - App </span>";
                                            					if ($is_mobile_safari) {
						echo "<span class=\"span-8\"><a href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokenc . "\">Sign Document</a></span>";
					} else { // Makes signer link render in iframe in case we want to customize the look
						echo "<span class=\"span-8\"><a href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokenc . "')\">Sign Document</a></span>";
					}
					echo "</div>";
                                }
                                if (!empty($signerd)) {
                                        echo "<h3>Select Signer</h3><div class=\"span-24\">";
                                        echo "<span class=\"span-5\">Name: " . $signerd .  " Signature 2 - PG: </span>";
                                        					if ($is_mobile_safari) {
						echo "<span class=\"span-8\"><a href=\"$rightsignature->base_url/signatures/embedded?rt=" . $tokend . "\">Sign Document</a></span>";
					} else { // Makes signer link render in iframe in case we want to customize the look
						echo "<span class=\"span-8\"><a href=\"#\" onclick=\"change_signer('$rightsignature->base_url/signatures/embedded?height=$widgetHeight&rt=" . $tokend . "')\">Sign Document</a></span>";
					}
					echo "</div>";
                                }
                                        
			}
                        
			?>
			<div class="span-24">
				<iframe width="<?php echo $widgetWidth; ?>px" scrolling="no" height="<?php echo $widgetHeight; ?>px" frameborder="0" id="signing-widget"></iframe>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		function change_signer(url) {
			$("#signing-widget").attr('src', url)
			;
		}
	</script>
<?php
	echo $this->Element('cobranded_applications/return');
?>
</body>
</html>
