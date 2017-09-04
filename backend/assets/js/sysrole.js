/*
 fileName: area.js
 description: process Tourist Area
 */

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
$(document).ready(function () {

});


function confirmDelete(id) {
    $('#roleId').html(id);
    $('#custom-confirm-delete-view').show();
}

function showRoleEdit(roleId) {
    $('#roleId').html(roleId);
    var i = 0;
    var permission = document.getElementById('permission' + roleId).innerText;
    if (permission != '') {
        permission = JSON.parse(permission);
        for (i = 1; i < 10; i++) {
            document.getElementById('manage' + i + '0').checked = (permission['p_' + i + '0']=='1');
        }
        document.getElementById('manage61').checked = (permission['p_61']=='1');
        document.getElementById('manage62').checked = (permission['p_62']=='1');
    } else{
        for (i = 1; i < 10; i++) {
            document.getElementById('manage' + i + '0').checked = false;
        }
        document.getElementById('manage61').checked = false;
        document.getElementById('manage62').checked = false;

    }

    $('#custom-generate-auth-view').show();
}

function updateRole(url, role) {
    var roleId = $('#roleId').html();
    var i = 0;
    var permission = new Object();
    for (i = 1; i < 10; i++) {
        permission['p_' + i + '0'] = document.getElementById('manage' + i + '0').checked == true ? '1' : '0';
    }
    permission['p_61'] = document.getElementById('manage61').checked == true ? '1' : '0';
    permission['p_62'] = document.getElementById('manage62').checked == true ? '1' : '0';
    $.ajax({
        type: 'POST',
        url: url + 'systemmanage/updateRole',
        dataType: 'json',
        data: {
            'roleId': roleId,
            'permission': JSON.stringify(permission)
        },
        success: function (data, textStatus, jqXHR) {
            console.log(data);
            if (JSON.parse(data['status'])) {
                if(role == roleId) location.href = url+'logout';
                else location.reload();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}
function addRole(url) {
    var roleName = $('#rolename').val();
    if (roleName == '') {
        $('#alertmsg').html("不超过10个字符；名称不允许相同.");
        $('#alertmsg').show();
        return;
    } else {
        $('#alertmsg').html("");
        $('#alertmsg').hide();
    }
    $.ajax({
        type: 'POST',
        url: url + 'systemmanage/addRole',
        dataType: 'json',
        data: {
            'roleName': roleName
        },
        success: function (data, textStatus, jqXHR) {
            if (JSON.parse(data['status'])) {
                location.reload();
            } else {
                $('#alertmsg').html("不超过10个字符；名称不允许相同.");
                $('#alertmsg').show();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}
function deleteRole(url) {
    var roleId = $('#roleId').html();
    location.href = url + 'systemmanage/deleteRole/' + roleId;
}
