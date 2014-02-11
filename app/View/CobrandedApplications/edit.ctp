
<div class='container'>
	<div class="row">
		<section id="wizard">
			<form id="onlineapp" method="get" action="">
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
							<li title="<?php echo $pageDescription ?>">
								<a href="#tab<?php echo $displayIndex ?>" data-toggle="tab">
									<span class="badge"><?php echo $displayIndex; ?></span>
									<div class="connecting-line"></div>
								</a>
							</li>
						<?php
						}
						?>
					</ul>

					<?php echo $this->Element('Templates/Pages/wizardPager') ?>

					<div class="tab-content">

					<?php echo $this->Element('Templates/Pages/templatePage', array('numberOfPages' => $numberOfPages, 'templatePage' => $templatePage, 'requireRequiredFields' => $requireRequiredFields)) ?>

					</div>

					<?php echo $this->Element('Templates/Pages/wizardPager') ?>
				</div>
			</form>
		</section>
	</div>
</div>

<script type="text/javascript" src="/js/jquery-validate.1.11.11.js"></script>
<script type="text/javascript" src="/js/jquery-validate-additional-methods.js"></script>
<script type="text/javascript" src="/js/jquery.bootstrap.wizard.js"></script>

<script type="text/javascript">
	var onWindowResize = function() {
		var totalWidth = $("#wizard .nav-pills").css("width").replace('px', '');
		var numberOfChildren = $("#wizard .nav-pills>li").length;
		var newWidth = Math.floor(totalWidth/(numberOfChildren-1))-$("#wizard .nav-pills").css('padding').replace('px','');
		$("#wizard .nav-pills>li>a>div.connecting-line").css("width", newWidth);
	};

	var onTabChange = function(tab, navigation, index) {
		var $valid = $("#onlineapp").valid();
		if(!$valid) {
			$validator.focusInvalid();
			return false;
		}
	};

	var $validator;

	$(document).ready(function() {
		$(window).resize(onWindowResize);

		setTimeout(function() {$(window).trigger('resize')}, 500);

		$validator = $("#onlineapp").validate({
			rules: {
				// no quoting necessary
				'CorpPhone': {
					phoneUS: true
				}
			}
		});

		$('#rootwizard').bootstrapWizard({
			'tabClass': 'nav nav-pills',
			'onNext': onTabChange
		});
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
