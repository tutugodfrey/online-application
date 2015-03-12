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

		/*if (target.attr('name') == 'BusinessType-') {
			var businessType = $("input[name='BusinessType-']:checked").parent().text();

			if (businessType == 'Retail' || businessType == 'Grocery') {
				$('#Amex\\ Discount\\ Rate').val(2.89);
				$('#Amex\\ Discount\\ Rate').trigger('change');
			}
			else if (businessType == 'Restaurant' || businessType == 'MOTO' || businessType == 'Internet') {
				$('#Amex\\ Discount\\ Rate').val(3.5);
				$('#Amex\\ Discount\\ Rate').trigger('change');
			}
		}*/

		persist(data);
	} else if(target.is(":checkbox")) {
		if (target.attr('id') == 'loc_same_as_corp' || target.attr('id') == 'fees_same_as_depository') {
			return true;
		}
		data['id'] = target.attr('data-value-id');
		data['value'] = target.is(":checked"); // not really needed...
		data['field_id'] = target.attr('id');
		persist(data);
	} else {
		if (target.attr('id') == 'CobrandedApplicationEmailList' || target.attr('id') == 'CobrandedApplicationEmailText') {
			return true;
		}

		var id = target.attr('id');
		var name = target.attr('name');

		// need to validate
		if ($validator.element(target) === true) {
			data['id'] = target.attr('data-value-id');
			data['field_id'] = target.attr('id');

			var isDateField = (target.attr('data-inputmask') === 'date' || target.attr('data-inputmask') && target.attr('data-inputmask').indexOf('\'alias\': \'mm\/dd\/yyyy\'') >= 0);
			data['value'] = target.val();
			if (isDateField) {
				var newDate = new Date(Date.parse(target.val()));
				var d = ("0" + newDate.getDate()).slice(-2);
				var m = newDate.getMonth();
				m += 1;  // JavaScript months are 0-11
				m = ("0" + m).slice(-2);
				var y = newDate.getFullYear();
				data['value'] = y+'-'+m+'-'+d;
			}

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
		if (data['field_id'] == 'EMail' ||
			data['field_id'] == 'LocEMail' ||
			data['field_id'] == 'Owner1Email' ||
			data['field_id'] == 'Owner2Email') {

				$('#CobrandedApplicationEmailList').empty();
				$('#emailListDiv').show();
				$('#emailTextDiv').hide();

				if ($('#EMail').val()) {
					$('#CobrandedApplicationEmailList').append('<option value="'+$('#EMail').val()+'">'+$('#EMail').val()+'</option>');
				}

				if ($('#LocEMail').val()) {
					$('#CobrandedApplicationEmailList').append('<option value="'+$('#LocEMail').val()+'">'+$('#LocEMail').val()+'</option>');
				}

				if ($('#Owner1Email').val()) {
					$('#CobrandedApplicationEmailList').append('<option value="'+$('#Owner1Email').val()+'">'+$('#Owner1Email').val()+'</option>');
				}

				if ($('#Owner2Email').val()) {
					$('#CobrandedApplicationEmailList').append('<option value="'+$('#Owner2Email').val()+'">'+$('#Owner2Email').val()+'</option>');
				}
		}

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

	// start from the top of the fieldset and sum all the inputs
	// except for the originatingField and the total field
	var totalFieldPattern = new RegExp('_Total');
	var percentSum = 0;
	
	$("#"+event.fieldset_id).find("input").map(function(index, input) {
		var inputObj = $(input);
		
		if (inputObj.attr("id") != originatingField.attr("id") &&
			!totalFieldPattern.test(inputObj.attr("name"))) {
				if (inputObj.val() != '') {
					percentSum += parseInt(inputObj.val());
				}
		}
	});

	var originatingFieldValue = parseInt(originatingField.val());
	if (isNaN(originatingFieldValue)) {
		originatingFieldValue = 0;
	}

	var newTotal = percentSum + originatingFieldValue;
	totalField.val(newTotal);

	if (newTotal != 100) {
		document.getElementById(totalField.attr("id")).style.backgroundColor='#FFFF00';
	}
	else {
		document.getElementById(totalField.attr("id")).style.backgroundColor='#FFFFFF';
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
		if (document.getElementById('MOTO/Internet Questionnaire') !== null) {
			document.getElementById('MOTO/Internet Questionnaire').style.display = 'block';
		}
	} else {
		if (document.getElementById('MOTO/Internet Questionnaire') !== null) {
			document.getElementById('MOTO/Internet Questionnaire').style.display = 'none';
		}
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

	if ($('#RoutingNum').val() != '' &&
		$('#AccountNum').val() != '' &&
		$('#FeesRoutingNum').val() != '' &&
		$('#FeesAccountNum').val() != '') {
			$('#fees_same_as_depository').attr('checked','checked');
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

	motoQuestionnaireCheck();

	$('#MethodofSales-CardNotPresent-Keyed').on('change', motoQuestionnaireCheck);
	$('#MethodofSales-CardNotPresent-Internet').on('change', motoQuestionnaireCheck);

	$(":input").inputmask();
});












