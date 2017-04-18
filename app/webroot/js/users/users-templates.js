/* 
 * Author Oscar Mota 2017
 * 
 * @requires /js/multiselect.js Multiselect jQuery plugin
 *
 * Copyright (c) 2017 Axia Technologies
 */

 $(document).ready(function() {
	//Enable multiselect functionality
	$('#all_managers').multiselect({
		settings: {
			submitAllLeft: false
		},
		search: {
			left: '<input type="text" name="mgr_optns_search" class="form-control" placeholder="Search All Managers..." />',
			right: '<input type="text" name="mgr_optns_search" class="form-control" placeholder="Search Selected..." />',
		},
		fireSearch: function(value) {
			return value.length > 1;
		}
	});

	$('#all_assn_reps').multiselect({
		settings: {
			submitAllLeft: false
		},
		search: {
			left: '<input type="text" name="assn_reps_optns_search" class="form-control" placeholder="Search All Reps..." />',
			right: '<input type="text" name="assn_reps_optns_search" class="form-control" placeholder="Search Selected..." />',
		},
		fireSearch: function(value) {
			return value.length > 1;
		}
	});

	$('#all_cobrands').multiselect({
		settings: {
			submitAllLeft: false
		},
		search: {
			left: '<input type="text" name="all_cobrands_optns_search" class="form-control" placeholder="Search All Cobrands..." />',
			right: '<input type="text" name="all_cobrands_optns_search" class="form-control" placeholder="Search Selected..." />',
		},
		fireSearch: function(value) {
			return value.length > 1;
		}
	});

	$('#all_templates').multiselect({
		settings: {
			submitAllLeft: false,
		},
		search: {
			left: '<input type="text" name="all_templates_optns_search" class="form-control" placeholder="Search All Templates..." />',
			right: '<input type="text" name="all_templates_optns_search" class="form-control" placeholder="Search Selected..." />',
		},
		fireSearch: function(value) {
			return value.length > 1;
		}
	});
	$('#multiSelectionArea').on('click mouseenter mouseleave keypress', function() {
		if ($('#UserTemplateId option').length -1 !== $('#all_templates_to option').length) {
			//remember initial selection
			curSelVal = $("#UserTemplateId option:selected").val();
			//Get all new updated options from multiselect selected menu
			var newOptions = $('#all_templates_to option');
			
			var $el = $("#UserTemplateId");
			// remove old options except the top 
			$('#UserTemplateId option:gt(0)').remove(); 
			//Update default template dropdown
			$.each(newOptions, function(key,value) {
				$el.append($("<option></option>")
					.attr("value", value.value).text(value.label));
			});

			//Set initial value
			$('#UserTemplateId option[value=' + curSelVal + ']').attr('selected','selected');
		}
	});

	//Attach event handlers to update menus
	$('button[name=cobrands_btns]').on('click', function(){
		udpateTemplates();
	});

	//on click/keypress/ of all the cobrands-from, and the conbrands-to update templates-from
	$('#all_cobrands, #all_cobrands_to').on('dblclick keypress', function(){
		udpateTemplates();
	});

	//Set template menu options on page load
	udpateTemplates();
});

/**
 * udpateTemplates
 *
 * @return void
 */
	function udpateTemplates() {
		//Get all new cobrands from right cobrand menu 
		var cobrandsTo = $('#all_cobrands_to option');
		if (cobrandsTo.length === 0) {
			//If no cobrands remove all templates options
			$('#all_templates_to option').remove();
		}
		//Get all templates Global var
		var newOptions = allTemplates;

		var $el = $("#all_templates");

		// remove all old options
		$('#all_templates option').remove(); 
		//Iterate through all options
		$.each(newOptions, function(cobrandId, val) {
			//Iterate though selected cobrands
			$.each(cobrandsTo, function(idx, value) {
				//Find matching selected cobrands where 
				//value.value is the id of the cobrands on the right
				if(value.value == cobrandId) {
					//Iterate and append all templates that belong to selected cobrand(s)
					$.each(val, function(id, name) {
						$el.append($("<option></option>")
						.attr("value", id).text(name));
					});
				}
			});
		});

		//--Tidy up:		
		//remove from left all templates that are already in right
		$.multiselect.defaults.startUp($('#all_templates'), $('#all_templates_to'));
		//Re-sort list
		natSortSelect('all_templates');
	}