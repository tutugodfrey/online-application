<?php
foreach ($templatePage['TemplateSections'] as $section) {
	?>
	
	<div class="col-md-<?php echo $section['width']; ?>" id="<?php echo $section['name']; ?>">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<?php
						echo $section['name']; 
						if ($section['name'] == 'LOCATION INFORMATION') {
							$fieldOptions = array();
							$fieldOptions = Hash::insert($fieldOptions, 'name', 'loc_same_as_corp');
							$fieldOptions = Hash::insert($fieldOptions, 'hiddenField', 'false');
							$fieldOptions = Hash::insert($fieldOptions, 'onchange', 'copyCorpToLocFields()');
							echo "<tr><th> ".$this->Form->checkbox('loc_same_as_corp', $fieldOptions)."<font size='-1'>Same As Corporate Information</font></th></tr>";
						}
					?>
				</h4>
			</div>
			<div class="panel-body">
				<div class="row">
				<?php
				echo $this->Element('Templates/Pages/Sections/Fields/genericField',
					array("fields" => $section['TemplateFields'], "bad_characters" => $bad_characters, 'requireRequiredFields' => $requireRequiredFields));
				?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>