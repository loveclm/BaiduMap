/*
 fileName: area.js
 description: process Tourist Area
 */

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
$(document).ready(function () {

});

function deleteAreaConfirm_jingqu(id) {
    $('#custom-confirm-delete-view').show();
    $('#current-areaid').val(id);
}

function deleteArea_jingqu(url, type) {

    $('#custom-confirm-delete-view').hide();
    if (type == 1) {//if ok button clicked
        $.post(url + "api/Areas/remove/" + $('#current-areaid').val(), function (result) {
            if (result['status'] == false)
                window.alert(result['message']);
            else
                location.href = url + 'area';
        });
    }
}

function deployAreaConfirm_jingqu(id) {

    $('#custom-confirm-deploy-view').show();
    $('#deployMessage').html("是否要上架此景区?");
    $('#current-areaid').val(id);
    $('#current-areastatus').val(1);
}

function undeployAreaConfirm_jingqu(id) {

    $('#custom-confirm-deploy-view').show();
    $('#deployMessage').html("是否要下架此景区?");
    $('#current-areaid').val(id);
    $('#current-areastatus').val(0);
}

function deployArea_jingqu(url, type) {

    $('#custom-confirm-deploy-view').hide();
    if (type == 1) { // if ok button clicked

        var touristArea = {
            id: $('#current-areaid').val(),
            status: $('#current-areastatus').val()
        };

        $.post(url + "api/Areas/changeStatus/" + touristArea['id'], touristArea, function (result) {
            console.log(result);
            if (result['status'] == false)
                window.alert(result['message']);
            else
                location.href = url + 'area';
        });
    }
}

function searchArea_jingqu(url) {

    var name = $('#searchName').val();
    var status = $('#searchStatus :selected').val();
    name = name == '' ? 'all' : name;
    var provinceText = $('#provinceName').html();
    var cityText = $('#cityName').html();
    var districtText = $('#districtName').html();
    var address = provinceText + "_" + cityText + "_" + districtText;
//    location.href = url + 'area/listing/' + name + '/' + JSON.stringify(address) + '/' + status;

    $.ajax({
        type: 'post',
        url: url + 'area/custom_listing',
        dataType: 'json',
        data: {name: name, address: address, status: status},
        success: function (res) {
            if (res.status == 'success') {

                $('#content_tbl').html(res.data);

            } else {
                alert('search failed!');
                console.log(res.data);
            }
        }
    });
}

function test_api111() {
    var posi = [116.404845, 39.898345];
    var id = '1200000018';
    var phone = '12345678901';
    $.ajax({
        type: 'POST',
        //url: 'http://www.ayoubc.com/backend/api/Areas/getMyAreaInfos',
        url: 'http://192.168.2.18/backend/api/Areas/setAreaBuyOrder',
        dataType: 'json',
        username: 'admin',
        password: '1234',
        data: {
            'pos': posi,
            'id': id,
            'phone': phone
        },
        success: function (data, textStatus, jqXHR) {
            console.log(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}

function test_api() {
    var posi = [116.404845, 39.898345];
    var id = '1200000023';
    var phone = '24562456245';
    var cost = '00505000007';
    var cost = '6';
    var type = '3';
    var shopid = '6';
    $.ajax({
        type: 'POST',
        //url: 'http://www.ayoubc.com/backend/api/Areas/getMyOrderInfos',
//        url: 'http://192.168.2.18/backend/api/Areas/setAreaBuyOrder',
        url: 'http://www.ayoubc.com/backend/api/Areas/setPayOrder',
        dataType: 'json',
        username: 'admin',
        password: '1234',
        data: {
            'id': id,
            'phone': phone,
            'cost': cost,
            'type': type,
            'shop': shopid,
            'pos':posi
        },
        success: function (data, textStatus, jqXHR) {
            console.log(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}

function test_api33() {
    var posi = [116.404845, 39.898345];
    var id = '11';
    var phone = '24562456245';
    var cost = '00402700012';
    var type = '4';
    var code = '123skla8kso98alk29lkngb23ioemv56';
    $.ajax({
        type: 'GET',
//        url: 'http://116.196.83.125/test/example/jsapi.php',
        url: 'http://www.ayoubc.com/test/example/jsapi.php',
        dataType: 'json',
        username: 'admin',
        password: '1234',
        data: {
            'id': '3'
        },
        success: function (data, textStatus, jqXHR) {
            console.log(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}

