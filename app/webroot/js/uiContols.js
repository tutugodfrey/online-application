/* 
 * Developed by Oscar Mota 2012
 * 
 * Copyright Axia Med
 */


/************************************************************/

    /*Global vars used by the animated div functions*/
var timerlen = 1;
var slideAniLen = 500;
var timerID = new Array();
var startTime = new Array();
var obj = new Array();
var endHeight = new Array();
var moving = new Array();
var dir = new Array();
/************************************************************/

/*Disable enter key from all online app form inputs except textarea input where enter is needed for new lines*/
jQuery("document").ready(function(){
    if (jQuery("form#onlineapp").length !== 0) {
        jQuery(document).on("keypress", ":input:not(textarea)", function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
            }
        });
    }
});

/************************************************************
*This funtion searches for action menus currently displayed and hides them
*then displays only the one that the user clicks on by calling the functions
*that animate slides.
**********************************************/
function showAndHideCurrentMenu(activatedObj){
var divElements = document.getElementsByTagName("div")
var patt1=/actionsMenuSlider/gi; 
              
        slidedown(activatedObj);
        for(x=0;x<divElements.length;x++){            
            if(divElements[x].id.match(patt1) && divElements[x].id != activatedObj)
                slideup(divElements[x].id)                   
            }
}
function slidedown(objname){
        if(moving[objname])
				return;

        if(document.getElementById(objname).style.display != "none")
                return; // cannot slide down something that is already visible

        moving[objname] = true;
        dir[objname] = "down";
        startslide(objname);
}

function slideup(objname){
        if(moving[objname])
		        return;

        if(document.getElementById(objname).style.display == "none")
                return; // cannot slide up something that is already hidden

        moving[objname] = true;
        dir[objname] = "up";
        startslide(objname);		
}

function startslide(objname){
        obj[objname] = document.getElementById(objname);

        endHeight[objname] = parseInt(obj[objname].style.height);
        startTime[objname] = (new Date()).getTime();

        if(dir[objname] == "down"){
                obj[objname].style.height = "1px";
        }

        obj[objname].style.display = "block";

        timerID[objname] = setInterval('slidetick(\'' + objname + '\');',timerlen);
}

function slidetick(objname){
        var elapsed = (new Date()).getTime() - startTime[objname];

        if (elapsed > slideAniLen)
                endSlide(objname)
        else {
                var d =Math.round(elapsed / slideAniLen * endHeight[objname]);
                if(dir[objname] == "up")
                        d = endHeight[objname] - d;

                obj[objname].style.height = d + "px";
        }

        return;
}

function endSlide(objname){
        clearInterval(timerID[objname]);

        if(dir[objname] == "up")
                obj[objname].style.display = "none";

        obj[objname].style.height = endHeight[objname] + "px";

        delete(moving[objname]);
        delete(timerID[objname]);
        delete(startTime[objname]);
        delete(endHeight[objname]);
        delete(obj[objname]);
        delete(dir[objname]);

        return;
}

/**
 * Performs a natural sort of dropdown menu elements in ascending order
 *  
 * @method natSortSelect
 * @param ddListId string id of drop down element
 * @return void
 */
    function natSortSelect(ddListId) {
        $("#" + ddListId).html($('#' + ddListId +' option').sort(function(x, y) {
            return $(x).text() < $(y).text() ? -1 : 1;
        }));
    }

/*
 * Autocomplete functionality - requires JQuery UI library
 * Can be enabled onto any text field by simply passing its id attribute
 * 
 * Implements a function callback to allow Ajax functionality 
 * 
 * @param string inputObj, DOM object id to pass as the jquery selector for the . Ej: "#objId"
 * @param string requestUri, a URI to perform ajax request for suggestions data to populate suggestions list.
 *                          URI FORMAT: '/ControllerName/action'
 * request = user input
 * response = Returns JSON encoded array of matching suggestions depending on user input
 * Response is passed as the source which requires an array or a JSON encoded object acting as associative array.
 * 
*/
function enableAutocomplete(inputObj, requestUri) {
    jQuery(inputObj).autocomplete({
        source: function(request, response) {
            jQuery.ajax({
                type: "POST",
                url: requestUri + "/" + request.term,
                dataType: 'html',
                success: function(data) {
                    response(JSON.parse(data));
                },
                error: function(data) {
                    //pass an empty array to close the menu if it was initially opened
                    response([]);
                }
            });/*end ajax*/
        }
    });
}

/**
 * toggleShowPwField
 * Toggle mask unmask contents of password fields
 * 
 * @param string objId the id of the password input field
 * @return void
 */

function toggleShowPwField(objId) {
    var x = document.getElementById(objId);
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
}