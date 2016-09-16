$(document).ready(function(){
	$('.menu-toggle').on('click', function() {
		var menu = $('nav.main');
		menu.toggleClass('open');
	});
});

$(document).ready(function(){
	if( $(".success").css('display') == 'block'){

		$("html, body").animate({ scrollTop: $('.success').offset().top-80 }, 1000);
	}

	if( $(".error").css('display') == 'block'){

		$("html, body").animate({ scrollTop: $('.error').offset().top-80 }, 1000);
	}
});
