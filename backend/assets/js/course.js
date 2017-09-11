/*
 fileName: course.js
 description: process Tourist Course
 */

var currentSelectedArea = 0; // current selected area in tourist areas list
var currentSelectedCourseItem = null; // current selected area in area list of course

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
$(document).ready(function () {

});

// select area from Area List
function selectCourse(id) {

    $(".custom-areaitem").removeClass("selected-area");
    $(".custom-courseitem").removeClass("selected-courseitem");
    $('#areaitem-' + id).addClass('selected-area');
    currentSelectedArea = id;
}

// add selected area to course
function addAreaToCourse() {

    var areaName = $('#areatitle-' + currentSelectedArea).text();
    $("#courseItems").append("<li class='custom-courseitem' onclick='selectedCourseItem(this)' " +
        "data-id='" + currentSelectedArea + "'><div>" + areaName + "</div></li>");
    var price = 0;
    price = parseFloat($('#courseprice').val() == '' ? '0' : $('#courseprice').val());
    price += parseFloat($('#areaprice-' + currentSelectedArea).html());
    $('#courseprice').val(price);
}

//Select area in Course
function selectedCourseItem(obj) {

    $(".custom-courseitem").removeClass("selected-courseitem");
    $(".custom-areaitem").removeClass("selected-area");
    $(obj).addClass('selected-courseitem');
    currentSelectedCourseItem = obj;
}

//Remove area from course
function removeAreaFromCourse() {

    $(currentSelectedCourseItem).remove();
    var price = parseFloat($('#courseprice').val());
    price -= parseFloat($('#areaprice-' + $(currentSelectedCourseItem).attr('data-id')).html());
    $('#courseprice').val(price);
}

//Get area list on Course Add and Edit Page
function getAreas() {

    var ret = [];
    var list = $('#courseItems');
    var areaList = list.children();
    var price=0;
    for (var i = 0; i < areaList.length; i++) {

        var areaId = $(areaList[i]).attr('data-id');
        var areaTitle = $('#areatitle-' + areaId).text();
        price += parseFloat($('#areaprice-'+areaId).text());
        ret.push({id: areaId, price: price, name: areaTitle});
    }
    $("#courseprice").val(price);
    return JSON.stringify(ret);
}

// Search course on Course List Page
function searchCourse(url) {

    var name = $('#searchName').val();
    var status = $('#searchStatus :selected').val();
    name = name == '' ? 'all' : name;

    //location.href = encodeURI(url + 'courseListing/' + name + '/' + status);

    $.ajax({
        type: 'post',
        url: url + 'area/course_listing',
        dataType: 'json',
        data: {name: name, status: status},
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

// Add and update Tourist Course
function processCourse(url, id) {

    var area = $("#coursename").val();
    var rate = (parseFloat($("#courserate").val())) / 100;
    var price=0;
    var point_list ='';
    if (area == '') {
        window.alert("请输入线路名称");
        return;
    }
    if (area.length > 10) {
        $('#custom-error-coursename').show();
        return;
    } else $('#custom-error-coursename').hide();
    var touristArea = '';
    var reqUrl = '';
    var info = {
        overay: 'all_course_image.png',
    };
    if (parseInt(id) != 0) {
        point_list=getAreas();
        price = $("#courseprice").val();
        touristArea = {
            id: parseInt(id),
            name: area,
            discount_rate: rate,
            price: price,
            address: '',
            status: 0, // 0-usual, 1-unusual,
            type: 1, // 1-course, 2-area
            info: JSON.stringify(info),
            point_list: point_list
        };
        reqUrl = url + "api/Areas/save/" + id;
    }
    else {
        point_list=getAreas();
        price = $("#courseprice").val();
        touristArea = {
            name: area,
            discount_rate: rate,
            price: price,
            address: '',
            status: 0, // 0-usual,  1-unusual
            type: 1, // 1-course,  2-area
            info: JSON.stringify(info),
            point_list: point_list
        };
        reqUrl = url + "api/Areas/save";
    }

    $.post(reqUrl, touristArea, function (result) {
        console.log(result);
        location.href = url + 'course';
    });

    return;
}

// This part is action processing for Course
// delete and deploy Course
function deleteAreaConfirm(id) {

    $('#custom-confirm-delete-view').show();
    $('#current-areaid').val(id);
}

function deleteArea(url, type) {

    $('#custom-confirm-delete-view').hide();
    if (type == 1) { // if ok button clicked

        $.post(url + "api/Areas/remove/" + $('#current-areaid').val(), function (result) {
            location.href = url + 'course';
        });
    }
}

function deployAreaConfirm(id) {

    $('#custom-confirm-deploy-view').show();
    $('#deployMessage').html("是否要上架此线路?");
    $('#current-areaid').val(id);
    $('#current-areastatus').val(1);
}

function undeployAreaConfirm(id) {

    $('#custom-confirm-deploy-view').show();
    $('#deployMessage').html("是否要下架此线路?");
    $('#current-areaid').val(id);
    $('#current-areastatus').val(0);
}

function deployArea(url, type) {

    $('#custom-confirm-deploy-view').hide();
    if (type == 1) {

        var touristArea = {
            id: $('#current-areaid').val(),
            status: $('#current-areastatus').val()
        };

        $.post(url + "api/Areas/changeCourseStatus/" + touristArea['id'], touristArea, function (result) {
            if (result['status'] == false)
                window.alert(result['message']);
            else
                location.href = url + 'course';
        });
    }
}

//return previos page
function cancel(url) {
    location.href = url + 'course';
}

//
function findAreaInList(url) {
    var strKey = $('#course-search').val();
    $.ajax({
        type: 'post',
        url: url + 'api/Areas/find',
        dataType: 'json',
        data: {key: strKey},
        success: function (res) {
            if (res != undefined) {

                $("#courseList").empty();
                var areaList = res;
                for (var i = 0; i < areaList.length; i++) {
                    var area = areaList[i];
                    $("#courseList").append("<li class='custom-areaitem' " +
                        "id='areaitem-" + area['id'] + "' onclick='selectCourse(" + area['id'] + ");'>" +
                        "<div id='areatitle-" + area['id'] + "'>" + area['name'] + "</div></li>");
                }

            } else {
                // alert('search failed!');
                console.log(res);
            }
        }
    });


    //
    //$.post(url + "api/Areas/find/" + strKey, function (result) {
    //    $("#courseList").empty();
    //    var areaList = result;
    //    for (var i = 0; i < areaList.length; i++) {
    //        var area = areaList[i];
    //        $("#courseList").append("<li class='custom-areaitem' " +
    //            "id='areaitem-" + area['id'] + "' onclick='selectCourse(" + area['id'] + ");'>" +
    //            "<div id='areatitle-" + area['id'] + "'>" + area['name'] + "</div></li>");
    //    }
    //
    //});
}