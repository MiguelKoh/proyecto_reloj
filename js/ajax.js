// JavaScript Document

function ajaxFunction() {
var xmlHttp;
try {
// Firefox, Opera 8.0+, Safari
xmlHttp=new XMLHttpRequest();
return xmlHttp;
} catch (e) {
// Internet Explorer
try {
xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
return xmlHttp;
} catch (e) {
try {
xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
return xmlHttp;
} catch (e) {
alert("Tu navegador no soporta AJAX!");
return false;
}}}
}

function Enviar(_pagina,capa) {
var
ajax;
ajax = ajaxFunction();

ajax.open("POST", _pagina, true);

ajax.setRequestHeader("Content-Type",
"application/x-www-form-urlencoded");
ajax.onreadystatechange = function()
{

if (ajax.readyState == 4)
{
document.getElementById(capa).innerHTML =
ajax.responseText;

}}
ajax.send(null);
}

$(document).ready(function () {
 
    //Set the height of the block
    $('#menu .block').height($('#menu li').height());
 
    //go to the default selected item
    topval = $('#menu .selected').position()['top'];
    $('#menu .block').stop().animate({top: topval}, {easing: '', duration:500});
 
    $('#menu li').hover(
         
        function() {
             
            //get the top position
            topval = $(this).position()['top'];
             
            //animate the block
            //you can add easing to it
            $('#menu .block').stop().animate({top: topval}, {easing: '', duration:500});
             
            //add the hover effect to menu item
            $(this).addClass('hover'); 
        },
         
        function() {       
            //remove the hover effect
            $(this).removeClass('hover');  
        }
    );
 
});

