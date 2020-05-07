 <html>
  <head>
   
  </head>
  <body>
	 Dear Underwriters,<br/><br/>

	All parties have signed a new sales agreement and is ready for your review.<br/>
	Business Name (DBA): <strong><?php echo $merchant; ?><br/></strong>
	<?php echo (!empty($repName))? "Rep Name: <strong>$repName</strong>":''; ?><br/>
	<?php 
	echo "$description<br/><br/>";
	echo "<strong><u>For security, this document can only be downloaded once and this link will expire after document is downloaded:</u></strong><br/>" ;
	echo "<div style='margin: 0 auto; text-align: center;'><a href='$appPdfUrl'><img width='100' height='100' src='https://app.axiatech.com/img/pdf-axiamed-dl.png'><br/><strong>Download</strong></a></div><br/>";

	?>

	Thank you,<br/>

	AxiaMed Online Application System<br/><br/>

	<span style="font-size:8pt;color:gray">The materials in this email are private and may contain privileged and confidential information, potentially including Protected Healthcare Information protected by federal and state privacy laws. If you are not the intended recipient, be advised that any unauthorized use, disclosure, copying, distribution, or the taking of any action in reliance on the contents of this information is strictly prohibited. If you have received this email in error, please immediately notify the sender via e-mail, telephone or return mail and destroy all copies of the original message.</span>
   
  </body>
</html>
