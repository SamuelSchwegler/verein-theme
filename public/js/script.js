// JS Script to load in header
jQuery(function(){
	// mobile
	jQuery('button.menu-toggle').on('click', function(){
		jQuery('body').toggleClass('nav-is-toggled');
	});


	// left bar
	jQuery('.hide-mobile .menu-item-has-children a').click(function (event) {
		console.log('open');
		toggleMenu(jQuery(this));
	});

	jQuery('body:not(.nav-is-toggled) #navigation-bar').click(function (event) {
		console.log('close');
		toggleMenu(jQuery(this), 'close');
	});

	jQuery('div#main').click(function (event) {
		toggleMenu(jQuery(this), 'close');
	});

	jQuery('#menu-area.menu-main-container').click(function () {
		console.log('bla bla');
		var elem = jQuery('.current_page_item').first();
		//funktioniert
		if (elem.parent().hasClass('nav-expand-content')) {
			jQuery('.current-menu-parent').find('> span > .next').click();
		}
	});
});

function toggleMenu(element, action = 'open') {
	if (action === 'open') {
		let menu;

		if (jQuery('.open').length !== 0) {
			menu = jQuery('.open');
			menu.animate({left: 0});
			menu.removeClass('open');
			jQuery('body').removeClass('sub-menu-open')
			jQuery('.sub-menu-open').removeClass('sub-menu-open');
		}

		menu = element.parent().next('#menu-area ul:not(.open)');
		menu.animate({left: '280px'});

		setTimeout(function () {
			element.closest('.menu-item-has-children').addClass('sub-menu-open');
			menu.addClass('open');
			jQuery('body').addClass('sub-menu-open')

		}, 1);
	} else if (action === 'close') {
		let menu = jQuery('.open');
		menu.animate({left: 0});
		menu.removeClass('open');
		jQuery('.sub-menu-open').removeClass('sub-menu-open');
		jQuery('body').removeClass('sub-menu-open');
	}
}