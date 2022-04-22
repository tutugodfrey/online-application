<?php
for ($index = 0; $index < $numberOfPages; $index ++) {
	$displayIndex = 1 + $index;
	$templatePage = $templatePages[$index];
	$active = ($displayIndex == 1 ? ' active' : '');
	?>
	<div class="tab-pane<?php echo $active ?>" id="tab<?php echo $displayIndex ?>">
		<div class="container">
			<div class="row">
				<?php echo $this->Element('Templates/Pages/Sections/templateSection', array('templatePage' => $templatePage, 'requireRequiredFields' => $requireRequiredFields)) ?>
			</div>
		</div>
	</div>
	<?php
}
?>