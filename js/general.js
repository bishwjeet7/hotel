$(document).ready(function(){
	var urlPath = window.location.pathname,
    urlPathArray = urlPath.split('.'),
    tabId = urlPathArray[0].split('/').pop();
	$('#index, #booking').removeClass('active');	
	$('#'+tabId).addClass('active');			
});

function showPopupAndPrint() {
	alert("Room has been booked!");
	window.print();
  }
  