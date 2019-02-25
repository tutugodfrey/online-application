<div class="panel panel-success">
	<div class="panel-heading text-center"><strong><span class="glyphicon glyphicon-lock pull-left"></span>API Access Information</strong></div>
	<div class="panel-body">
		<?php
			echo "<div id='apiInfoContent'>" . $this->element('Ajax/api_info_content') . '</div>';
			?>
			<strong>Lost API password?</strong>
			<div class="panel panel-success">
				<div class="panel-body text-center">
					<?php
						echo $this->Html->link(
							'Regenerate API password',
							'#',
							array(
								'class' => 'btn btn-success btn-sm',
								'onClick' => "if(confirm('A new API password will be generated for your account, continue?')){ renderContentAJAX('', '', '', 'apiInfoContent', '/Users/reset_api_info/" . $this->Session->read('Auth.User.id') . "/1')};return false;")
						);
					?>
				</div> 
			</div>
	</div> 
</div>
