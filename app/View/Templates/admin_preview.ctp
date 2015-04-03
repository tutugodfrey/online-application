<br/><br/><br/>

<div class="container">

	<div class="row">
		<?php
		echo $this->Html->tag(
			'h1',
			String::insert(
				"Preview of ':template_name' Template for ':partner_name'",
				array(
					'template_name' => $template['Template']['name'],
					'partner_name' => $template['Cobrand']['partner_name']
				)
			)
		);
		?>
	</div>

	<div class="row">
		<div class="container">
		<?php

		$brand_logo = $this->Html->image($template['Cobrand']['brand_logo_url'], array('class' => 'pull-right'));
		$cobrand_logo = $this->Html->image($template['Cobrand']['cobrand_logo_url']);

		if (strlen($template['Cobrand']['cobrand_logo_url']) == 0) {
			// no logo specified... use brand logo on the left
			echo String::insert(
				'<div class="row">' .
					'<div class="col-md-12">:brand_logo</div>' .
				'</div>',
				array(
					'brand_logo' => $brand_logo
				)
			);
		} elseif ($template['Template']['include_brand_logo'] == true) {
			// only one way to display this combination
			echo String::insert(
				'<div class="row">' .
					'<div class="col-md-6">:cobrand_logo</div>' .
					'<div class="col-md-6">:brand_logo</div>' .
				'</div>',
				array(
					'cobrand_logo' => $cobrand_logo,
					'brand_logo' => $brand_logo
				)
			);
		} else {
			// position the logo left,center or right
			$logo_position = $template['Template']['logo_position'];
			if ($logo_position < 3) {
				echo String::insert(
					'<div class="row">' .
						'<div class="col-md-12 text-:position">:cobrand_logo</div>' .
					'</div>',
					array(
						'cobrand_logo' => $cobrand_logo,
						'position' => $logoPositionTypes[$logo_position]
					)
				);
			}
		}
		?>
		</div>
	</div>

	<br/>

	<div class="accordion">
		<div class="panel-group" id="page_accordion">
		<?php
			$is_admin = $this->Session->read('Auth.User.id') > 0;
			foreach ($template['TemplatePages'] as $page):
				if ($is_admin || $page['rep_only'] !== true) {
					$page_id = str_replace($bad_characters, '', $page['name']);
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#page_accordion" href="#<?php echo $page_id ?>"
						<?php 
						if ($page['rep_only'] == true) {
							echo 'title="only the rep will see this"';
						}
						?>>
							<?php echo $page['name'] ?>
						</a>
					</h4>
				</div>
				<div class="panel-body panel-collapse collapse" id="<?php echo $page_id ?>">
					<div class="row">
						<div class="accordion">
							<div class="panel-group" id="section_accordion">
								<div class="row">
									<?php
									$form_html = $this->Form->create($page['name']);
									echo preg_replace('/(id="[^"]*)"/', '\1" class="onlineapp_preview_page"', $form_html);
									foreach ($page['TemplateSections'] as $section):
										if ($is_admin || $section['rep_only'] !== true) {
											$section_id = str_replace($bad_characters, '', $section['name']);
									?>
										<div class="col-md-<?php echo $section['width']; ?>">
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4 class="panel-title">
														<a data-toggle="collapse" data-parent="#section_accordion" href="#<?php echo $section_id ?>" <?php 
															if ($section['rep_only'] == true) {
																echo 'title="only the rep will see this"';
															}
														?>>
															<?php echo $section['name']; ?>
														</a>
													</h4>
												</div>
												<div class="panel-body panel-collapse collapse" id="<?php echo $section_id ?>">
													<div class="row">
													<?php echo $this->Element('Templates/Pages/Sections/Fields/genericField',
														array("fields" => $section['TemplateFields'], "bad_characters" => $bad_characters)); ?>
													</div>
												</div>
											</div>
										</div>
									<?php
										}
									endforeach;
									?><!-- end sections -->
									<?php echo $this->Form->end(array('label' => 'Update', 'class' => 'hidden')); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
			}
		endforeach; ?><!-- end pages -->
		</div>
	</div>

	<style type="text/css">
		/* TODO: move this into a css file */
		#page_accordion div.panel-body {
			padding: 2px 15px;
		}
		.panel-group .panel+.panel {
			margin-top: 2;
		}
	</style>

	<script type="text/javascript">
		/* TODO: move this into a javascript file */
		$(document).on("ready", function() {
			// remove the cake css
			// TODO: use a different template that does not use the cake.generic.css file
			$.map(
				$('head link'),
				function(link, index) {
					if ($(link).attr('href') == '/css/cake.generic.css') {
						$(link).remove();
					}
				}
			);

			$(document).on("percentOptionBlur", handlePercentOptionBlur);
			$(document).on("feeOptionBlur", handleFeeOptionBlur);

			function handlePercentOptionBlur(event) {
				var totalField = $(event.totalFieldId);
				var startingTotalValue = parseInt(totalField.val());
				var originatingField = $(event.origin);
				if (totalField.val() == "") {
					// stuff the new value value
					totalField.val(originatingField.val());
				} else {
					var newTotal = parseInt(startingTotalValue) + parseInt(originatingField.val());
					if (newTotal <= 100) {
						totalField.val(newTotal);
					} else {
						// start from the top of the fieldset and all sum the inputs
						// except for the originatingField
						var percentSum = 0;
						$("#"+event.fieldset_id).find("input").map(function(index, input) {
							var inputObj = $(input);
							if (!inputObj.is(':disabled') &&
									inputObj.attr("id") != originatingField.attr("id")) {
								if (inputObj.val() != '') {
									percentSum += parseInt(inputObj.val());
								}
							}
						});

						var newTotal = percentSum + parseInt(originatingField.val());
						if (newTotal <= 100) {
							// set it
							parseInt(originatingField.val());
							totalField.val(newTotal);
						} else {
							var maxOriginatingValue = 100 - percentSum;
							originatingField.val(maxOriginatingValue < 0 ? 0 : maxOriginatingValue);
							totalField.val(100);
						}
					}
				}
			}

			function handleFeeOptionBlur(event) {
				var totalField = $(event.totalFieldId);
				// sum the
				var feesSum = 0;
				$("#"+event.fieldset_id).find("input").map(function(index, input) {
					var inputObj = $(input);
					if (!inputObj.is(':disabled')) {
						feesSum += parseFloat(inputObj.val());
					}
				});
				totalField.val(feesSum.toFixed(2));
			}

			// this is overkill because the :first-child psuedo class is not working as expected
			$(".fees").find("input:first-child").map(function(index, input) {
				$(input).trigger('blur');
			});
		});
	</script>
</div>
