// JS Script to load as last

/**
 * Accordion
 */
jQuery(".accordion-header").click(function () {
    jQuery(this).next('.accordion-content').slideToggle();
    jQuery(this).parent().toggleClass("open");
});

/**
 * Search Form
 */
jQuery(function () {
    jQuery('.search-form-icon:not(.no-action)').click(function () {
        let search_form_outer = jQuery(this).parent();

        if (!search_form_outer.hasClass('active')) {
            search_form_outer.addClass('active');
            jQuery(this).html('<i class="fas fa-times"></i>');
            jQuery('#s').focus();
        } else {
            search_form_outer.removeClass('active');
            jQuery(this).html('<i class="fas fa-search"></i>');
        }
    });
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