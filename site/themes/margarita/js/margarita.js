$(document).ready(function(){
	$('.menu-toggle').on('click', function() {
		var menu = $('nav.main');
		menu.toggleClass('open');
	});
});
