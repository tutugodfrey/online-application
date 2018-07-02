<div class='center-block' style="width: 300px;" id='loginContainer'>
<?php
	echo $this->Html->image(
		'logo.png',
		array(
			'alt' => __('Axia'),
			'border' => '0',
			'class' => 'center-block',
		)
	);
?>

	<div style='margin-top:-15;opacity:0' id='loginFrmContainer'>
		<p class='text-center'><strong>Application Portal.</strong> Please Login.</p>
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
				'required' => false
			));
			echo $this->Form->input('User.password', array(
				'placeholder' => 'Password'
			)); ?>
			<div class="form-group">
				<?php echo $this->Form->submit('Login', array(
					'class' => 'btn btn-default pull-right'
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
		"margin-top": "60px",
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
