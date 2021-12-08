$('.menu-item-has-children a').click(function (event) {
    toggleMenu($(this));
});

$('#navigation-bar').click(function (event) {
    toggleMenu($(this), 'close');
});

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

        console.log(element);
        menu = element.parent().next('#menu-area ul:not(.open)');
        console.log(menu);
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
        $('body').removeClass('sub-menu-open')
    }
}