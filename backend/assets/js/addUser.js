/**
 * File : addUser.js
 * 
 * This file contain the validation of add user form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Kishor Mali
 */

//return previos page
function cancel(url) {
	if (url == '')
		window.history.back();
	else
		location.href = url + 'userListing';
}

$(document).ready(function(){

	var status = $("#success_alert").val();
	console.log(status);
	if(status!=undefined) {
		window.alert("添加人员成功.");
		location.href=baseURL+"userListing";
	}

});
