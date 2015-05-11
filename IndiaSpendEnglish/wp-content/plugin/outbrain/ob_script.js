// Outbrain 2013
// Wordpress js support file
//version 8.0.0.0

var current;

//containers variables
var right_arrow_class = "option_board_right";
var down_arrow_class = "option_board_down";

//  claiming script variables
var ajaxUrl = null;

var claimModeElements = ['block_claim', true, 'block_additional_setting', true, 'block_custom_settings', true, 'block_settings', false, 'block_pages', true, 'block_submit', true];
var noClaimModeElements = ['block_claim', true, 'block_additional_setting', true, 'block_additional_instruction', true, 'block_settings', false, 'block_pages', true, 'block_submit', true];
var claimModeElements_hide = ['block_loader', true, 'claim_key', true];
var noClaimModeElements_hide = ['block_loader', true, 'claim_title', true];

var isPageLoadMode;//to verify that the return data triggers the page load display

function outbrain_$(id) {
  return(document.getElementById(id));
}


function outbrainReset() {
  $('#reset').val("true");
  $('#outbrain_form').submit();

}

function outbrainKeySave() {
  $('#keySave').val("true");
  $('#outbrain_form').submit();

}

//-------------------------------------------------------------------------------------------------------------------------------------
//  containers function
//-------------------------------------------------------------------------------------------------------------------------------------


//change the current state of the container
//forceState - make it change to a specific state - open - to open container //close to close
function toggleState(element, forceState) {
  var container = outbrain_$(element.id + "_inner"); //get inner div
  if(!container){ return; }

  if(forceState === 'open') {
    container.style.display = "";
    element.className = down_arrow_class;
  }
  else if(forceState === 'close') {
    container.style.display = "none";
    element.className = right_arrow_class;
  }
  else if(element.className === right_arrow_class) {
    container.style.display = "";
    element.className = down_arrow_class;
  }
  else {
    container.style.display = "none";
    element.className = right_arrow_class;
  }
}

/**
 * take the faster and validate it
 * @param element
 */
function toggleStateValidate(element) {
  if(!element){
    return;
  }

  var parentElement = element.parentNode; //get the <li>
  if(!parentElement){
    return;
  }

  if(parentElement.id <= 0){  //validations
    return;
  }

  toggleState(parentElement, "else..");
}

//-------------------------------------------------------------------------------------------------------------------------------------
//  claim function
//-------------------------------------------------------------------------------------------------------------------------------------
function saveClaimStatusResponseIntoDB(status, statusString) {
  try {
    // do ajax to insert code
    jQuery.ajax({
      type:"POST",
      url:ajaxUrl,
      data:'saveClaimStatus=true&status=' + status + '&statusString=' + statusString,
      success:function() {

      },
      error:function() {

      }
    });
  } catch(ex) {

  }
  return true;
}

function outbrain_elementsShowHide(arr, isShow) {
  for(var t = 0; t < arr.length; t += 2) {
    var currentElement = document.getElementById(arr[t]);
    if(currentElement !== null || typeof currentElement !== 'undefined') {
      try {
        currentElement.style.display = ((isShow) ? "" : "none");
        var currentContainerState = (arr[t + 1]) ? "open" : "close";

        if(isShow){ toggleState(currentElement, currentContainerState); }
      }
      catch(ex) {
        //alert('show Elements '+ex+':'+ currentElement +'('+ arr[t] +')')
      }
    }
  }
}


function outbrain_claimMode() {
  outbrain_elementsShowHide(claimModeElements_hide, false);//arr of elements and isShow
  outbrain_elementsShowHide(claimModeElements, true);
}

function outbrain_noClaimMode() {
  outbrain_elementsShowHide(noClaimModeElements_hide, false);
  outbrain_elementsShowHide(noClaimModeElements, true);
}

/**
 * callback for the claim request. (~not removable~).
 * @param status numeric number representing the status of the the claim process
 * @param statusString verbose the numeric value into meaningful status.
 */
function returnedClaimData(status, statusString) {
  var element = outbrain_$('after_claiming');
  element.innerHTML = statusString;//fill the div let other decide about visibility
  if(isPageLoadMode && (status === 10 || status === 12)) {
    outbrain_claimMode();  // this blog is claimed show the appropriate
  }
  else if(isPageLoadMode) {
    outbrain_noClaimMode();
  }
  else {//button pressed

    //  after-claiming text (write the response and display it)
    element.style.display = "block";
    toggleLoadingDisplay(false);//dont show loading

    //  save response - not so important
    saveClaimStatusResponseIntoDB(status, statusString);

  }
  isPageLoadMode = false;//return to claiming mode
}

function doClaim(key) {
  var keyScriptElementId = "outbrainClaimBlog";
  var element = outbrain_$(keyScriptElementId);
  if(element) {
    element.parentNode.removeChild(element);
  }

  var ob = document.createElement("script");
  ob.type = "text/javascript";
  ob.async = true;
  ob.src = "http" + ("https:" === document.location.protocol ? "s" : "") + "://odb.outbrain.com/blogutils/Claim.action?type=meta&cbk=returnedClaimData&_=" + Math.floor(100 + Math.random() * 100) + "&key=" + encodeURIComponent(key);
  var h = document.getElementsByTagName("script")[0];
  h.parentNode.insertBefore(ob, h);
}


function toggleLoadingDisplay(showMode) {
  //  hide loading image
  var loadingImage = outbrain_$("claimLoadingImage");
  if(showMode === false) {
    loadingImage.style.display = "none";
  }
  else {
    loadingImage.style.display = "inline";
  }
}

function outbrain_isUserClaim(key) {
  isPageLoadMode = true;
  doClaim(key);
}


