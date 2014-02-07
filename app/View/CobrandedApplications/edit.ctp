

<div class='container'>
	<div class="row">
		<section id="wizard">

			<form id="commentForm" method="get" action="" class="form-horizontal">
				<div id="rootwizard">
					<ul>
						<?php
						$numberOfPages = count($templatePages);
						for ($index = 0; $index < $numberOfPages; $index ++) {
							$templatePage = $templatePages[$index];
							$pageDescription = $templatePage['description'];
							$displayIndex = 1 + $index;
							$displayText = $templatePage['name'];
						?>
							<li>
								<a href="#tab<?php echo $displayIndex ?>" data-toggle="tab">
									<span class="badge"><?php echo $displayIndex; ?></span>
									<div class="connecting-line"></div>
								</a>
							</li>
						<?php
						}
						?>
					</ul>

					<div class="tab-content">
					<?php
					for ($index = 0; $index < $numberOfPages; $index ++) {
						$displayIndex = 1 + $index;
						$templatePage = $templatePages[$index];
						$active = ($displayIndex == 1 ? ' active' : '');
						?>

						<div class="tab-pane<?php echo $active ?>" id="tab<?php echo $displayIndex ?>">
							<div class="container">
								<div class="row">
								<?php
								foreach ($templatePage['TemplateSections'] as $section) {
									?>
									<div class="col-md-<?php echo $section['width']; ?>">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><?php echo $section['name']; ?></h4>
											</div>
											<div class="panel-body">
												<div class="row">
												<?php echo $this->Element('Templates/Pages/Sections/Fields/genericField',
													array("fields" => $section['TemplateFields'], "bad_characters" => $bad_characters)); ?>
												</div>
											</div>
										</div>
									</div>
									<?php
								}
								?>
								</div>
							</div>
						</div>
						<?php
					}
					?>
					</div>
					<ul class="pager wizard">
						<li class="previous first" style="display:none;"><a href="#">First</a></li>
						<li class="previous"><a href="#">Previous</a></li>
						<li class="next last" style="display:none;"><a href="#">Last</a></li>
						<li class="next"><a href="#">Next</a></li>
					</ul>
				</div>
			</form>

		</section>
	</div>
</div>

<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="/js/jquery.bootstrap.wizard.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		var $validator = $("#commentForm").validate({
			rules: {
				emailfield: {
					required: true,
					email: true,
					minlength: 3
				},
				namefield: {
					required: true,
					minlength: 3
				},
				urlfield: {
					required: true,
					minlength: 3,
					url: true
				}
			}
		});

		$('#rootwizard').bootstrapWizard({
			'tabClass': 'nav nav-pills',
			'onNext': function(tab, navigation, index) {
				var $valid = $("#commentForm").valid();
				if(!$valid) {
					$validator.focusInvalid();
					return false;
				}
			}
		});

		$(window).resize(function() {
			var totalWidth = $("#wizard .nav-pills").css("width").replace('px', '');
			var numberOfChildren = $("#wizard .nav-pills>li").length;
			var newWidth = Math.floor(totalWidth/(numberOfChildren-1))-$("#wizard .nav-pills").css('padding').replace('px','');
			$("#wizard .nav-pills>li>a>div.connecting-line").css("width", newWidth);
		});

		$(window).trigger('resize');
	});
</script>

<style type="text/css">
	#wizard input {
		padding: 0;
	}
	#wizard .nav.nav-pills {
		padding: 13px;
	}
	#wizard .nav>li>a {
		padding: inherit;
	}
	#wizard .nav-pills>.active>a,
	.nav-pills>.active>a:hover {
		background: none;
	}
	#wizard .nav-pills>li>a,
	.nav-pills>li>a:hover {
		background: transparent;
	}
	#wizard .nav-pills>li>a>span.badge {
		padding-top: 5px;
		padding-bottom: 5px;
		-webkit-border-radius: 15px;
		-moz-border-radius: 15px;
		border-radius: 15px;
	}
	#wizard .nav-pills>.active>a>span.badge {
		color: white;
		background-color: #417092;
		-moz-box-shadow: 5px 5px 8px #888;
		-webkit-box-shadow: 5px 5px 8px #888;
		box-shadow: 5px 5px 8px #888;
	}
	#wizard .nav-pills>li>a>div.connecting-line {
		position: relative;
		top: -11px;
		left: 25px;
		border-bottom: 1px solid gray;
		z-index: -100;
		width: 8em;
	}
	#wizard .nav-pills>li:last-child>a>div.connecting-line {
		border-bottom: 0;
		width: 0;
	}
	#wizard .nav-pills>li:last-child>a {
		width: 26px;
	}
</style>
