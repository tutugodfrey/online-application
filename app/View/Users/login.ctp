<div class='center-block' style="width: 390px;" id='loginContainer'>
	<div class='panel panel-default panel-body' style="padding: 20px 40px 0px 40px;">

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
			<p class='text-center'><strong>Application Portal.</strong> Company personnel sign in.</p>
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
			echo $this->Form->input('User.email', array(
					'placeholder' => 'Email',
					'autofocus' => 'autofocus',
					'required' => false,
					'autocomplete' => 'off'
				));
				echo $this->Form->input('User.password', array(
					'placeholder' => 'Password',
					'autocomplete' => 'off'
				)); ?>
				<div class="form-group">
					<?php echo $this->Form->submit('Sign In', array(
						'class' => 'btn btn-sm btn-primary col-md-12 col-sm-12 col-lg-12',
						'id' => 'loginSubmitBtn'
					)); ?>
				</div>
				<div class="form-group small pull-right">
					<?php
					echo $this->Html->link('Forgot Password', array('action' => 'request_pw_reset'), array('class' => 'btn btn-xs text-muted'));
					echo $this->Html->link('Renew Password', array('action' => 'request_pw_reset', 1), array('class' => 'btn btn-xs text-muted'));
					?>
				</div>
			<?php
				echo $this->Form->end();
			?>
		</div>
		<div id="nextAuthStep"></div>
	</div>
</div>
<script>
$("#BoostCakeLoginForm").bind("submit", function (event) {
	$('#loginSubmitBtn').prop('disabled', 'disabled');
	$('#loginSubmitBtn').val('Validating...');
	$.ajax({
		async: true, 
		type: "POST", 
		url: "/Users/login",
		dataType: "html", 
		data: $("#BoostCakeLoginForm").serialize(), 
		success: function (data, textStatus) {
			$("#nextAuthStep").html(data);
		},
		error: function (data, textStatus) {
			$("#nextAuthStep").html(data);
		}
	});
	return false;
});
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
