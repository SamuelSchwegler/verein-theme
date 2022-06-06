// JS Script to load in header
jQuery(function () {
    // mobile
    jQuery('button.menu-toggle').on('click', function () {
        jQuery('body').toggleClass('nav-is-toggled');
    });


    // left bar
    jQuery('.hide-mobile .menu-item-has-children a').click(function (event) {
        toggleMenu(jQuery(this));
    });

    jQuery('body:not(.nav-is-toggled) #navigation-bar').click(function (event) {
        toggleMenu(jQuery(this), 'close');
    });

    jQuery('div#main').click(function (event) {
        toggleMenu(jQuery(this), 'close');
    });

    jQuery('#menu-area.menu-main-container').click(function () {
        var elem = jQuery('.current_page_item').first();
        //funktioniert
        if (elem.parent().hasClass('nav-expand-content')) {
            jQuery('.current-menu-parent').find('> span > .next').click();
        }
    });

    /**
     * Menü
     */
    jQuery('.mobile-menu .menu-item-has-children > span').append('<span class="next">></span>');

    let navExpand = [].slice.call(document.querySelectorAll('.mobile-menu .menu-item-has-children'));
    let backLink = '<li class="menu-item">\n\t<span><a class="nav-link nav-back-link" href="javascript:;">\n\t\t<span class="next"><</span>Zurück\n\t</a></span>\n</li>';

    navExpand.forEach(item => {
        if (item.querySelector('.nav-expand-content') !== null) {
            item.querySelector('.nav-expand-content').insertAdjacentHTML('afterbegin', backLink);
        }

        if (item.querySelector('.next') !== null) {
            item.querySelector('.next').parentNode.addEventListener('click', () => item.classList.add('active'))
        }

        if (item.querySelector('.nav-back-link') !== null) {
            item.querySelector('.nav-back-link').addEventListener('click', () => item.classList.remove('active'));
        }
    });

    // remove active class from all except first
    jQuery('.menu-item-home.active:not(:first)').removeClass("active");
    jQuery('.menu-item-home.current_page_item:not(:first)').removeClass("active");
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