// JS Script to load as last

/**
 * Accordion
 */
$(".accordion-header").click(function () {
    $(this).next('.accordion-content').slideToggle();
    $(this).parent().toggleClass("open");
});

/**
 * Search Form
 */
$(function () {
    $('.search-form-icon:not(.no-action)').click(function () {
        let search_form_outer = $(this).parent();

        if (!search_form_outer.hasClass('active')) {
            search_form_outer.addClass('active');
            $(this).html('<i class="fas fa-times"></i>');
            $('#s').focus();
        } else {
            search_form_outer.removeClass('active');
            $(this).html('<i class="fas fa-search"></i>');
        }
    });

    // left bar
    $('.hide-mobile .menu-item-has-children a').click(function (event) {
        toggleMenu($(this));
    });

    $('body:not(.nav-is-toggled) #navigation-bar').click(function (event) {
        toggleMenu($(this), 'close');
    });

    $('div#main').click(function (event) {
        toggleMenu($(this), 'close');
    });

    $('.menu-toggle').click(function () {
        var elem = $('.current_page_item').first();
        //funktioniert
        if (elem.parent().hasClass('nav-expand-content')) {
            $('.current-menu-parent').find('> span > .next').click();
        }
    });
});

/**
 * Menü
 */
$('.mobile-menu .menu-item-has-children > span').append('<span class="next">></span>');

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
$('.menu-item-home.active:not(:first)').removeClass("active");
$('.menu-item-home.current_page_item:not(:first)').removeClass("active");

function toggleMenu(element, action = 'open') {
    if (action === 'open') {
        let menu;

        if ($('.open').length !== 0) {
            menu = $('.open');
            menu.animate({left: 0});
            menu.removeClass('open');
            $('body').removeClass('sub-menu-open')
            $('.sub-menu-open').removeClass('sub-menu-open');
        }

        menu = element.parent().next('#menu-area ul:not(.open)');
        menu.animate({left: '280px'});

        setTimeout(function () {
            element.closest('.menu-item-has-children').addClass('sub-menu-open');
            menu.addClass('open');
            $('body').addClass('sub-menu-open')

        }, 1);
    } else if (action === 'close') {
        let menu = $('.open');
        menu.animate({left: 0});
        menu.removeClass('open');
        $('.sub-menu-open').removeClass('sub-menu-open');
        $('body').removeClass('sub-menu-open');
    }
}