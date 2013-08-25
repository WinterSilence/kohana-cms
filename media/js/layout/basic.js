// Add class when you hover over an element
$.fn.ehover = function (add_class) {
	if ( ! add_class) {
		add_class = 'active';
	}
	$(this).hover(function () {
		$(this).toggleClass(add_class);
	});
	return this;
}

// Element positioned in the center
$.fn.center = function () {
	$(this).css('position', 'absolute');
	$(this).css('top', (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()  + 'px');
	$(this).css('left', (($(window).width() - $(this).outerWidth())  / 2) + $(window).scrollLeft() + 'px');
	return this;
}

$(document).ready(function () {
	
	//var h = $('body').height() - $('.g-row > header').parent().outerHeight(true) - $('.g-row > footer').parent().outerHeight(true) - $('div#content').outerHeight(true);
	//if (h > $('body div#content').height()) {
		//$('div#content').height($('div#content').height() + h);
	//}
	
	// Sidebar scroller
	var sidebar = $('#sidebar');
	var sidebar_top = sidebar.offset().top;
	$(window).scroll(function () {
		if ($(window).scrollTop() > sidebar_top) {
			sidebar.css({position: 'fixed', 'top': '10px'});
		} else {
			sidebar.css({position: 'static', 'top': '0'});
		}
	});
	
});