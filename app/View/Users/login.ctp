<div  style="width: 350px; margin-left: auto; margin-right: auto; opacity:0;" id='loginContainer'>
<?php
	echo $this->Html->image(
		'logo.png',
		array(
			'alt'=> __('Axia'),
			'border' => '0',
			'class' => 'center-block',
		)
	);
?>
<h3 class='text-center text-primary' >Welcome</h3>
	<div style='margin-top:-15;opacity:0' id='loginFrmContainer'>
		<?php
		echo $this->Form->create('BoostCake', array(
			'inputDefaults' => array(
				'div' => 'form-group',
				'label' => array(
					'class' => 'col col-md-3 control-label'
				),
				'wrapInput' => 'col col-md-9',
				'class' => 'form-control'
			),
			'class' => 'well form-horizontal'
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
					'div' => 'col col-md-9 col-md-offset-3',
					'class' => 'btn btn-default'
				)); ?>
			</div>
		<?php 
			echo $this->Form->end(); 
			echo $this->Session->flash();
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