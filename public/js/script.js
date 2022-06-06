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