/*
 fileName: shop.js
 description: process Shop
 */

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
$(document).ready(function () {

});

// Search course on Course List Page
function searchUserList(url) {
    var searchType = $("#searchType :selected").val();
    var name = $('#searchName').val();
    name = name == '' ? 'ALL' : name;

    location.href = url + 'userCoListing/' + searchType + '/' + name ;
}
