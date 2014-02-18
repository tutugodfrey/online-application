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

var handlePercentOptionBlur = function(event) {
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

	// look for percent group and find the first input and fire a blur event to update the total field
	$('#wizard fieldset.percent').each(function(index, item) {
		$(item).find('input:first').trigger('blur');
	});
});