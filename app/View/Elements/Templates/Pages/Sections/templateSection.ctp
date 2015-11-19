<?php
foreach ($templatePage['TemplateSections'] as $section) {
	?>
	
	<div class="col-md-<?php echo $section['width']; ?>" id="<?php echo $section['name']; ?>">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<?php
						$applicationStatus = $this->Session->read('applicationStatus');

						echo $section['name'];
						if (preg_match('/location information/i', $section['name'])) {
							$fieldOptions = array();
							$fieldOptions = Hash::insert($fieldOptions, 'name', 'loc_same_as_corp');
							$fieldOptions = Hash::insert($fieldOptions, 'hiddenField', 'false');
							$fieldOptions = Hash::insert($fieldOptions, 'onchange', 'copyCorpToLocFields()');

							if ($applicationStatus == CobrandedApplication::STATUS_SIGNED ||
								$applicationStatus == CobrandedApplication::STATUS_COMPLETED) {
									$fieldOptions = Hash::insert($fieldOptions, 'disabled', 'true');
							}

							echo "<tr><th> " . $this->Form->checkbox('loc_same_as_corp', $fieldOptions) . "<font size='-1'>Same As Corporate Information</font></th></tr>";
						}
						if (preg_match('/fees account/i', $section['name'])) {
							$fieldOptions = array();
							$fieldOptions = Hash::insert($fieldOptions, 'name', 'fees_same_as_depository');
							$fieldOptions = Hash::insert($fieldOptions, 'hiddenField', 'false');
							$fieldOptions = Hash::insert($fieldOptions, 'onchange', 'copyDepositoryToFeesFields()');

							if ($applicationStatus == CobrandedApplication::STATUS_SIGNED ||
								$applicationStatus == CobrandedApplication::STATUS_COMPLETED) {
									$fieldOptions = Hash::insert($fieldOptions, 'disabled', 'true');
							}

							echo "<tr><th> " . $this->Form->checkbox('fees_same_as_depository', $fieldOptions) . "<font size='-1'>Same As Depository Information</font></th></tr>";
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
