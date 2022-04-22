<p><br/><br/></p>
<div class="jumbotron center">
	<div style="width:50%" class="center">
		<div class="panel panel-primary panel-body">
			<?php if (stripos(Router::url('/', true), 'axiapayments')): ?>
			<img style="margin: 20px 20px 20px 20px;padding: 10px;background-color: #313637;" src="https://axia.i3merchant.com/wp-content/uploads/2019/07/AxiaLogo_ReverseColor_RGB-1024x321-1-600x188.png" width="180" alt="">
			<?php else: ?>
			<img style="margin: 20px 20px 20px 20px;"src="/img/AxiaMedHDnoLoop.gif" width="200" alt="">
			<?php endif; ?>

			<h3 class="text-info">Welcome to Axia's Online Application System</h3>
			<hr />
			<p class="text-justify"><small>If you are a client or a prospective client, we thank you for your business. You may request access to your forms and applications by using the option below. The username that was previously assigned to your account will be required.</small></p>
		    <?php
		    	echo $this->Html->link('Request Access <span class="glyphicon glyphicon-circle-arrow-right"></span>', 
			    	'#', //(anchor url since this is hadled with AJAX modal)
			    	array('escape' => false, 'data-toggle' => 'modal', 'data-target' => '#myModal_', 'class' => 'btn btn-primary btn-lg')
		    	);
		    ?>
			<p>&nbsp;</p>

			<hr />
			<p class="text-right">
				<?php 
					 if (stripos(Router::url('/', true), 'axiapayments') === false) {
						echo '<small>' . $this->Html->link('Not a client? Visit our site <span class="glyphicon glyphicon-new-window"></span>', 
					    	'https://www.axiamed.com/contact/', array('escape' => false, 'target' => '_blank', 'class' => 'small')) . '</small>';
					 }
				?>
			</p>
	</div>
	</div>
</div>

<?php echo $this->element('cobranded_applications/client_access_request'); ?>