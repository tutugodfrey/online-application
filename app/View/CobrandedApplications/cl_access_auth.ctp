<div class='center-block' style="width: 390px;" id='loginContainer'>
	<div class='panel panel-primary panel-body' style="padding: 20px 40px 0px 40px;">

	<?php
		echo $this->Html->image(
			'AxiaMedHDnoLoop.gif',
			array(
				'alt' => __('Axia'),
				'border' => '0',
				'class' => 'center-block',
				'style' => 'width: 100%;',
			)
		);
	?>

		<div style='margin-top:-15;opacity:0' id='loginFrmContainer'>
			<hr class="row" />
			<p class='text-center'><strong>Client Applications Portal.</strong><br/> Customer sign in.<br/>Please enter your credentials.</p>
			<?php echo $this->Session->flash(); ?>
			<?php
			echo $this->Form->create('BoostCake', array(
				'inputDefaults' => array(
					'div' => 'form-group',
					'label' => array(
						'class' => 'sr-only'
					),
					'class' => 'form-control'
				),
				'class' => 'form-horizontal'
			));
			echo $this->Form->input('CobrandedApplication.user_token', array(
					'placeholder' => 'Username',
					'autofocus' => 'autofocus',
					'required' => true,
					'autocomplete' => 'off'
				));
				echo $this->Form->input('CobrandedApplication.password', array(
					'placeholder' => 'Password',
					'required' => true,
					'autocomplete' => 'off'
				)); ?>
				<div class="form-group">
					<?php echo $this->Form->submit('Sign In', array(
						'class' => 'btn btn-sm btn-success col-md-12 col-sm-12 col-lg-12',
						'id' => 'loginSubmitBtn'
					)); ?>
				</div>
			<?php
				echo $this->Form->end();
			?>
		</div>
		<div class="panel-footer row small text-small text-right">
			<?php
			echo $this->Html->link('Forgot username/password?', '/', array('class' => 'btn btn-xs text-muted'));
			?>
		</div>
	</div>
</div>
<script>

//Don't run animation when there are alert messages.
if ($('.alert').length === 0){
	$( "#loginContainer" ).animate({
		"margin-top": "75px",
		opacity: 1,
		}, 500
	);
	$( "#loginFrmContainer" ).animate({
		opacity: 1,
		"margin-top": "50px",
		}, 800
	);

} else {
	$( "#loginContainer" ).css({
		"margin-top": "75px",
		"opacity": 1,
	});
	$( "#loginFrmContainer" ).css({
		"opacity": 1,
		"margin-top": "60px"
	});
}
</script>
