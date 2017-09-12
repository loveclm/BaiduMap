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

$(document).ready(function () {

    var status = $("#success_message").val();
    console.log(status);
    if (status != undefined) {
        window.alert("添加人员成功.");
        location.href = baseURL + "userListing";
    }

});


function addSystem_User() {
    var email = $('#email').val();
    var fname = $('#fname').val();
    var password = $('#password').val();
    var cpassword = $('#cpassword').val();
    var mobile = $('#mobile').val();
    var role = $('#role :selected').val();

    $.ajax({
        type: 'POST',
        url: url + 'systemmanage/addNewUser',
        dataType: 'json',
        data: {
            email: email,
            fname: fname,
            password: password,
            cpassword: cpassword,
            mobile: mobile,
            role: role
        },
        success: function (data, textStatus, jqXHR) {
            if (JSON.parse(data['status']))
                window.alert("添加人员成功.");
            location.href = baseURL + "userListing";
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}
