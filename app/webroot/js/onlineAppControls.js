/* 
 * Author Oscar Mota 2017
 * 
 */

function renderContentAJAX(controller, action, id, containerID, actionPath) {
  /*    This function asynchronously renders Elements stored in /View/Elements/AjaxElements.
   *    The cakephp action that responds to this ajax request must be defined in its corresponding Controller.
   *    Data returned by action must be the html,javascript,etc. that was defined in the AjaxElement.
   *    
   *    Requered args: 
   *     *  /controller/action or actionPath. The controller and action to make the request to.
   *     *  containerID = DOM id of element to dump returned HTML content
   *     
   *    actionPath = must be any valid path if the action path is deeper than 2 levels (/controller/action)
   *    id = an id relevant to requested action (optional)
   *               
   **/
   //Insert Loading sequence
   $('#'+containerID).html('<img class="center-block" src="/img/refreshing.gif"/>');
	var reqURL;
	if (actionPath) {
			reqURL = actionPath;
	} else {
			reqURL= "/"+controller+"/"+action;
	} 
	if (id) {
		reqURL += "/" + id;
	}
	  AJAXrequest = $.ajax({
			type: "POST",
			url: reqURL,
		   // data: "data['MerchantNote'][note_type]=''",
			dataType: 'html',
			success: function(data) { 
				
				$('#'+containerID).html(data);
			},
			error: function(data) {
				/*If user session expired the server will return a Unathorized status 401
				 *Refreshing the page will redirect the user to the login page thus preventing it from inserting into the DOM*/
					if(data.status === 401) {
						$('#'+containerID).html('<div class="error-message">Error 401: Unathorized.</div>');
					 	location.reload();
					}
					$('#'+containerID).html('<div class="error-message">Server Request Error: Sorry try again later.</div>');
				  }
		});
}

function getTemplateForm() {
	$('#slectorFrmErrCont').html('');
	if ($("#TemplateBuilderBaseCobrand option:selected").index() > 0 && $("#TemplateBuilderBaseTemplate option:selected").index() > 0) {
		$('#tmpltBldrContainer').html('<img class="center-block" src="/img/refreshing.gif"/>');
		renderContentAJAX('TemplateBuilder', 'add_template', $("#TemplateBuilderBaseTemplate").val(), 'tmpltBldrContainer');
	} else {
		$('#slectorFrmErrCont').html('<div class="text-danger">Please select a cobrand and a template.</div>');
	}
}

/**
 * setElementPopOver
 * Creates a popover (with no title attribute) and associates it with its trigger object using its id.
 * The popover container is given a unique id by concatenating the string 'popOvrContentFor' + associatedObjId.
 * The contents can be later filled out using that id from any other method.
 * 
 * @param string associatedObjId the id of the object that will trigger the popover.
 * @return void
 */
function setElementPopOver(associatedObjId) {
	popOverContentId = 'popOvrContentFor' + associatedObjId;
	$('#' + associatedObjId).popover({
		//title: "Loading", 
		content: "<span id='" + popOverContentId + "'><span>", 
		html: true, 
		placement: "bottom"
	});
}

/**
 * getAppStatus
 * Intermediary stage method that performs an call to retrieve an Application Status via AJAX.
 * The Result is later populated in a popover element container.
 * 
 * @param string appStatusId the id of the application.
 * @return void
 */
function getAppStatus(appStatusId) {
	//begin request
	renderContentAJAX('', '', '', 'popOvrContentFor' + 'app_status_' + appStatusId, '/admin/CobrandedApplications/app_status/' + appStatusId);
}