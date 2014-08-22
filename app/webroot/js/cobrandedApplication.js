var quickAdd = function(e) {
	e.preventDefault();
	var target = $(e.target);
	var data = {};

	// handle radio buttons and checkboxes don't need to be validated
	if (target.is(":radio")) {
		data['id'] = target.attr('data-value-id');
		data['value'] = target.is(":checked"); // not really needed...
		data['template_field_id'] = target.attr('data-field-id');
		data['field_id'] = target.attr('id');
		persist(data);
	} else if(target.is(":checkbox")) {
		data['id'] = target.attr('data-value-id');
		data['value'] = target.is(":checked"); // not really needed...
		data['field_id'] = target.attr('id');
	} else {
		// need to validate the
		if ($validator.element(target) === true) {
			data['id'] = target.attr('data-value-id');
			data['value'] = target.val();
			data['field_id'] = target.attr('id');
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
		document.getElementById(data['field_id']).removeAttribute("style");
	}).error(function() {
		alert('failed to update application value');
	});
};

var onWindowResize = function() {
	if ($("#wizard .nav-pills").length == 0) {
		setTimeout(onWindowResize, 500);
	} else {
		var totalWidth = $("#wizard .nav-pills").css("width").replace('px', '');
		var numberOfChildren = $("#wizard .nav-pills>li").length;
		var padding = $("#wizard .nav-pills").css('padding-left').replace('px','');
		var newWidth = Math.floor(totalWidth/(numberOfChildren-.96))-padding-Math.floor(numberOfChildren/2);	
		if (newWidth >= 917) {
			newWidth = 915;
		}	
		$("#wizard .nav-pills>li>a>div.connecting-line").css("width", newWidth);
	}
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

var motoQuestionnaireCheck = function(){
	// show MOTO/Internet Questionnaire if the following 2 values
	// combined are greater than or equal to 30
	var methodOfSalesCardNotPresentKeyed = parseInt($('#MethodofSales-CardNotPresent-Keyed').val());
	var methodOfSalesCardNotPresentInternet = parseInt($('#MethodofSales-CardNotPresent-Internet').val());

	if (methodOfSalesCardNotPresentKeyed + methodOfSalesCardNotPresentInternet >= 30) {
		document.getElementById('MOTO/Internet Questionnaire').style.display = 'block';
	} else {
		document.getElementById('MOTO/Internet Questionnaire').style.display = 'none';
	}


};
$(document).ready(function() {
	$(window).resize(onWindowResize);

	setTimeout(function() {$(window).trigger('resize')}, 10);

	if ($('#CorpName').val() != '' &&
		$('#CorpAddress').val() != '' &&
		$('#CorpCity').val() != '' &&
		$('#CorpState').val() != '' &&
		$('#CorpZip').val() != '' &&
		$('#CorpPhone').val() != '' &&
		$('#CorpFax').val() != '' &&
		$('#CorpContact').val() != '' &&
		$('#Title').val() != '' &&
		$('#DBA').val() != '' &&
		$('#Address').val() != '' &&
		$('#City').val() != '' &&
		$('#State').val() != '' &&
		$('#Zip').val() != '' &&
		$('#PhoneNum').val() != '' &&
		$('#FaxNum').val() != '' &&
		$('#Contact').val() != '' &&
		$('#LocTitle').val() != '') {
			$('#loc_same_as_corp').attr('checked','checked');
	}

	$(document).on("percentOptionBlur", handlePercentOptionBlur);

	var validationRules = {};
	$("#onlineapp input[data-vtype]").map(function(index, input) {
		var currentInput = $(input);
		if (typeof(validationRules[currentInput.attr('id')]) == 'undefined') {
			var rule = new Object();
			rule[currentInput.attr('data-vtype')] = true;
			validationRules[currentInput.attr('id')] = rule;
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

	$('div.tab-pane.active').on('click', function(e) {
		var event = $(e).get(0);
		if (event.altKey == true && event.shiftKey == true) {
			// toggle the hidden property for all the $('.api_field')(s)
			$('.api-field').toggle();
		}
	});
	$('#MethodofSales-CardNotPresent-Keyed').on('change', motoQuestionnaireCheck);
	$('#MethodofSales-CardNotPresent-Internet').on('change', motoQuestionnaireCheck);
});
