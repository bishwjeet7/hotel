$(document).ready(function(){
	var urlPath = window.location.pathname,
    urlPathArray = urlPath.split('.'),
    tabId = urlPathArray[0].split('/').pop();
	$('#rooms, #accomodation, #reservation, #items, #user').removeClass('active');	
	$('#'+tabId).addClass('active');
	$('div[id^="expand"]').click(function(){
		$(this).next().show();
	})		
});