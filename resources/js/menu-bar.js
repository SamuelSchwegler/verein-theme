
$('.menu-item-has-children > a').click(function(event) {
	toggleMenu($(this));
});

$('#navigation-bar').click(function(event) {
	toggleMenu($(this), 'close');
});

function toggleMenu(element, action = 'open') {
	if(action === 'open') {
		let menu;

		if($('.open').length !== 0) {
			menu = $('.open');
			menu.animate({left: 0});
			menu.removeClass('open');
			$('.sub-menu-open').removeClass('sub-menu-open');
		}

		menu = element.parent().find('.menu:not(.open)');
		menu.animate({left: '280px'});

		setTimeout(function() {
			element.closest('.menu-item-has-children').addClass('sub-menu-open');
			menu.addClass('open');
		}, 1);
	} else if(action === 'close') {
		let menu = $('.open');
		menu.animate({left: 0});
		menu.removeClass('open');
		$('.sub-menu-open').removeClass('sub-menu-open');
	}
}