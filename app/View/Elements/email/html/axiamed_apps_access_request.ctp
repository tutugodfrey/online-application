<div style="margin-bottom: 20px;background-color: #f5f5f5;border: 1px solid #ddd;padding: 35px;">
	<div style="margin-bottom: 20px;background-color: #fff;border: 1px solid;border-color: #bce8f1;">
	    <div style="color: #31708f;background-color: #d9edf7;border-color: #bce8f1;border-bottom: 1px solid;text-align: center;">
	    	<img style="margin: 20px 20px 20px 20px;"src="https://app.axiatech.com/img/AxiaMed_Logo.png" width="150" alt="">
	    </div>
		<div style="margin: 50px 50px 50px 50px;">
	        <p>Esteemed and valued client:</p>
	        <p>You recently requested access to your AxiaMed applications. Please visit the link below to view your applications:</p>

			<p><strong><u>For your security, save this information in a safe location, and do not share it with anyone since your applications may contain confidential information. <br/>
				In addition please note that access to this expires on <?php echo date_create($clientAccessCredentials['clientAccessExpiration'])->format('M jS Y');?>.</u></strong></p>
			<p>
				<?php echo "<a href='$appAccesslink'><strong>$appAccesslink</strong></a>"; ?><br/>
				Username/User token: <?php echo $clientAccessCredentials['clientAccesToken'];?><br/>
				Password: <?php echo $clientAccessCredentials['clientPassword'];?><br/>
			</p>
			<p>Thank you!<br/>
			This is an automated message please do not reply to this email, if you have any questions contact your sales representative or use the contact information at the bottom if this email. 
			</p>
		</div>
		<div style="padding: 10px 15px;background-color: #f5f5f5;border-top: 1px solid #ddd;">
			<span style="font-size:8pt;color:gray">The materials in this email are private and may contain privileged and confidential information, potentially including Protected Healthcare Information protected by federal and state privacy laws. If you are not the intended recipient, be advised that any unauthorized use, disclosure, copying, distribution, or the taking of any action in reliance on the contents of this information is strictly prohibited. If you have received this email in error, please immediately notify the sender via e-mail, telephone or return mail and destroy all copies of the original message.</span>
			<div style="font-size:10pt; text-align: center;">
				<strong>Sales Inquiries:</strong><br/>
				(855) 376-2942 x 1<br/>
				info@axiamed.com<br/><br/>

				<strong>Technical Support:</strong><br/>
				(855) 376-2942 x 2<br/>
				
			</div>
		</div>
	</div>
</div>