/*
 fileName: shop.js
 description: process Shop
 */

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
$(document).ready(function () {

});

// Search course on Course List Page
function searchShop(url) {

    var name = $('#searchName').val();
    var status = $('#searchStatus :selected').val();
    name = name == '' ? 'all' : name;

    var provinceText = $('#provinceName').html();
    var cityText = $('#cityName').html();
    var districtText = $('#districtName').html();
    var address = provinceText + "_" + cityText + "_" + districtText;

    // location.href = url + 'shopListing/' + name + '/' + status;

    $.ajax({
        type: 'post',
        url: url + 'shop/shop_listing',
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

// Search course on Course List Page
function searchQR(url) {

    var searchType = $('#searchType').val();
    var name = $('#searchName').val();
    var status = $('#searchStatus :selected').val();
    name = name == '' ? 'all' : name;

//    location.href = url + 'qrListing/' + name + '/' + status;

    $.ajax({
        type: 'post',
        url: url + 'qrmanage/qr_listing',
        dataType: 'json',
        data: {name: name, status: status, searchType: searchType},
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

//check if string is number string
function isNumeric(value) {
    return /^\d+$/.test(value);
}

//check if account is valid
function invalidAccount(account) {
    if (account.length != 11) return true;
    if (!isNumeric(account)) return true;
    return false;
}

// Add and update Shop
function processShop(url, id) {

    var name = $("#shopname").val();
    var rate = (parseFloat($("#shoprate").val())) / 100;
    var account = $("#shopid").val();
    var password = $("#shoppassword").val();
    var type = $('#shoptype :selected').val();

    var provinceText = $('#provinceName').html();
    var cityText = $('#cityName').html();
    var districtText = $('#districtName').html();
    if (districtText == '' || cityText == '' || provinceText == '') {
        window.alert("请填写所有信息!.");
        return;
    }
    if ($("#shoprate").val() == '' || rate <= 0 || type == '0') {
        window.alert("请填写所有信息!.");
        return;
    }
    var address = provinceText + "," +
        cityText + "," + districtText;
    if (name.length > 10) {
        $('#custom-error-shopname').show();
        return;
    } else $('#custom-error-shopname').hide();
    if (account.length > 11) {
        $('#custom-error-shopid').show();
        return;
    } else $('#custom-error-shopid').hide();

    if (invalidAccount(account)) {
        $('#custom-error-shopid').show();
        return;
    }

    var shopInfo = '';
    var reqUrl = '';
    if (parseInt(id) != 0) {
        shopInfo = {
            id: parseInt(id),
            name: name,
            phonenumber: account,
            password: password,
            address_1: address,
            address_2: address,
            type: type,
            discount_rate: rate,
            status: 0
        };
        reqUrl = url + "api/Shops/saveAccount/" + id;
    }
    else {
        shopInfo = {
            name: name,
            phonenumber: account,
            password: password,
            address_1: address,
            address_2: address,
            type: type,
            discount_rate: rate,
            status: 0
        };
        reqUrl = url + "api/Shops/saveAccount";
    }

    $.post(reqUrl, shopInfo, function (result) {
        console.log(result);
        window.alert(result['message']);
        if (result['status'] == true) location.href = url + 'shop';
    });

    return;
}

// This part is action processing for Course
// delete and deploy Course
function deleteShopConfirm(id) {

    $('#custom-confirm-delete-view').show();
    $('#current-areaid').val(id);
}

function deleteShop(url, type) {

    $('#custom-confirm-delete-view').hide();
    if (type == 1) {  // if ok button clicked
        $.post(url + "api/Shops/remove/" + $('#current-areaid').val(), function (result) {
            console.log(result);
            location.href = url + 'shop';
        });
    }
}

function deployShopConfirm(id) {

    $('#custom-confirm-deploy-view').show();
    $('#deployMessage').html("是否要禁用此商家?");
    $('#current-areaid').val(id);
    $('#current-areastatus').val(1);
}

function undeployShopConfirm(id) {

    $('#custom-confirm-deploy-view').show();
    $('#deployMessage').html("是否要取消禁用此商家?");
    $('#current-areaid').val(id);
    $('#current-areastatus').val(0);
}

function deployShop(url, type) {

    $('#custom-confirm-deploy-view').hide();
    if (type == 1) {  // if ok button clicked

        var touristArea = {
            id: $('#current-areaid').val(),
            status: $('#current-areastatus').val()
        };

        $.post(url + "api/Shops/save/" + touristArea['id'], touristArea, function (result) {
            location.href = url + 'shop';
        });
    }
}

function showGenerateQR(id) {

    $('#custom-generate-auth-view').show();
    $('#current-areaid').val(id);
    $('#current-codetype').val('qr');
}

function showGenerateAuth(id) {

    $('#custom-generate-auth-view').show();
    $('#current-areaid').val(id);
    $('#current-codetype').val('auth');
}

function changeAuthType() {
    var type = $('#auth-select :selected').val();
    if (type == 2) { // 1-course, 2-area
        $('#custom-auth-area-view').show();
        $('#custom-auth-course-view').hide();
        $('#current-type').val(2);
    } else if (type == 1) { //1-course, 2-area
        $('#custom-auth-area-view').hide();
        $('#custom-auth-course-view').show();
        $('#current-type').val(1);
    } else {
        $('#custom-auth-area-view').hide();
        $('#custom-auth-course-view').hide();
        $('#current-type').val(0);
    }
}

function generateAuth(url, confirm) {

    var type = $('#current-type').val();
    var codeType = $('#current-codetype').val();

    var target = '0';
    if (type == 2) { // 1-course, 2-area
        target = $('#auth-select-area :selected').val();
    }
    if (type == 1) { // 1- course, 2-area
        target = $('#auth-select-course :selected').val();
    }
    if (target != '0') {

        $('#current-targetid').val(target);
        if (codeType == 'qr') {
            generateAuthFinal(url, confirm);
        }
        else {
            $('#custom-generate-auth-count-view').show();
            $('#custom-generate-auth-view').hide();
        }
    }
}

function showQR(url_suffix) {
    $('#custom-generate-qr-view').show();
    console.log('http://www.ayoubc.com/tour' + encodeURI(url_suffix));
    $('#qr-view').qrcode({text: 'http://www.ayoubc.com/tour/index.php' + url_suffix});
}

function generateAuthFinal(url, confirm) {
    var authCount = parseInt($('#auth-count').val());
    var codeType = $('#current-codetype').val();
    var target = $('#current-targetid').val();
    var type = $('#current-type').val();
    var shopid = $('#current-areaid').val();
    var targetname;

    console.log('code ' + codeType + 'tar ' + target + ' type ' + type + ' shop ' + shopid + ' count ' + authCount);

    if (codeType == 'qr') {

        var data = 'http://www.ayoubc.com/tour/index.php?shopid=' + shopid + '&type=' + type + '&targetid=' + target;
        var authInfo = {
            shopid: shopid,
            type: type,
            targetid: target,
            data: data
        };
        console.log(url);
        $.post(url + "api/Shops/generateQR", authInfo, function (result) {
            console.log(result);
            $('#custom-generate-qr-view').show();
            $('#custom-generate-auth-view').hide();

            $('#qr-view').qrcode({text: data});

            //location.href = url + 'shop';
        });

    } else {
        if (authCount > 0) {
            if (codeType == 'auth') {

                var authInfo = {
                    shopid: shopid,
                    status: 0,  // 0-usual,  1- unusual
                    type: type,
                    targetid: target,
                    codecount: authCount
                };

                $.post(url + "api/Shops/generateAuth", authInfo, function (result) {
                    console.log(result);
                    location.href = url + 'shop';
                });
            }
        }
    }
}

//return previos page
function cancel(url) {
    location.href = url + 'shop';
}

//return previos page
function cancelQR(url) {
    location.href = url + 'qrmanage';
}

function findShopInList(url) {
    var strKey = $('#course-search').val();
    $.post(url + "api/Shops/find/" + strKey, function (result) {

        console.log(result);
        $("#courseList").empty();
        var areaList = result;
        for (var i = 0; i < areaList.length; i++) {
            var area = areaList[i];
            $("#courseList").append("<li class='custom-areaitem' id='areaitem-" + area['id'] + "' onclick='selectCourse(" + area['id'] + ");'>" +
                "<div id='areatitle-" + area['id'] + "'>" + area['name'] + "</div></li>");
        }

    });
}

/* End of file shop.js */
/* Location: ./assets/js/shop.js */