<?php
echo $this->Html->image(
	'logo.png',
	array(
		'alt' => __('Axia'),
		'border' => '0',
		'class' => 'center-block',
		'style' => 'margin-top:5%;margin-bottom:5%',
	)
);
?>
<div class="jumbotron">
	<div class="col col-md-offset-1">
		<h3><?php echo $name; ?></h3>
		<p class="text-muted">
			<?php
			printf(__('The requested address %s was not found on this server.'), "<strong>'{$url}'</strong>");
			?>
		</p>
	</div>
</div>