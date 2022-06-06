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