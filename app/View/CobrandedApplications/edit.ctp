
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
							if (strlen($pageDescription) == 0) {
								$pageDescription = $templatePage['name'];
							}
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
					<div>Fields marked with * are required.</div>
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
	var quickAdd = function(e) {
		e.preventDefault();
		var target = $(e.target);
		var data = {};

		// handle radio buttons and checkboxes don't need to be validated
		if (target.is(":radio")) {
			data['id'] = target.attr('data-value-id');
			data['value'] = target.is(":checked"); // not really needed...
			data['template_field_id'] = target.attr('data-field-id');
			persist(data);
		} else if(target.is(":checkbox")) {
			data['id'] = target.attr('data-value-id');
			data['value'] = target.is(":checked"); // not really needed...
			persist(data);
		} else {
			// need to validate the
			if ($validator.element(target) === true) {
				data['id'] = target.attr('data-value-id');
				data['value'] = target.val();

				persist(data);
			}
		}
	};

	var persist = function(data) {
		$.ajax({
			method: 'post',
			url: document.location.pathname.replace('/edit/', '/quickAdd/'),
			data: data,
			context: document.body
		}).done(function(response) {
			// noop
		}).error(function() {
			alert('failed to update application value');
		});
	};

	var onWindowResize = function() {
		var totalWidth = $("#wizard .nav-pills").css("width").replace('px', '');
		var numberOfChildren = $("#wizard .nav-pills>li").length;
		var padding = $("#wizard .nav-pills").css('padding-left').replace('px','');
		var newWidth = Math.floor(totalWidth/(numberOfChildren-1))-padding;
		$("#wizard .nav-pills>li>a>div.connecting-line").css("width", newWidth);
	};

	function handlePercentOptionBlur(event) {
		var totalField = $(event.totalFieldId);
		var originatingField = $(event.origin);

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

		setTimeout(function() {$(window).trigger('resize')}, 10);

		$(document).on("percentOptionBlur", handlePercentOptionBlur);

		var validationRules = {};
		$("#onlineapp input[data-vtype]").map(function(index, input) {
			var currentInput = $(input);
			if (typeof(validationRules[currentInput.attr('id')]) == 'undefined') {
				var rule = new Object();
				rule[currentInput.attr('data-vtype')] = true;
				validationRules[currentInput.attr('id')] = rule;
			} else {
				alert('Already added a rule for ['+input['id']+']');
			}
		});

		$validator = $("#onlineapp").validate({ rules: validationRules });

		$('#rootwizard').bootstrapWizard({
			'tabClass': 'nav nav-pills',
			'onNext': onTabChange
		});

		// set up the onBlur handler for all of the appliction input fields
		$('#wizard input').on('change', quickAdd);
		$('#wizard select').on('change', quickAdd);
		$('#wizard textarea').on('change', quickAdd);
	});
</script>

<style type="text/css">
	#wizard input {
		padding: 0;
		height: 30px;
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
	#wizard input[type="checkbox"],  #wizard input[type="radio"] {
		height: 18px;
	}
	#wizard input[type="radio"] {
		float: left;
		margin-top: 0;
		margin-right: 5px;
	}
	#wizard .input-group-addon {
		padding: 8px 22px 6px 10px;
	}
	#wizard select {
		margin-top: 5px;
		margin-bottom: 10px;
	}
	#wizard textarea {
		width: 100%;
	}
</style>
