jQuery(function () {
    //fake next, prev
    jQuery('.rslides').parent().css('position', 'relative');
    for(var i = 1; i <= 11; i++ ) {
        jQuery('#slider' + i).responsiveSlides({
            manualControls: '#slider' + i + '-pager',
            maxwidth: 900,
            auto: false,
            nav: true,
            pause: true,
            speed: 500, // Integer: Speed of the transition, in milliseconds
            timeout: 4000,
            prevText: '<i class="icon-chevron-left"></i>', // String: Text for the "previous" button
            nextText: '<i class="icon-chevron-right"></i>',
        });        
    }
});

function showRequest(formData, jqForm, options) {
    var queryString = jQuery.param(formData);
    return true;
}
function showResponse(responseText, statusText) {
}
jQuery.fn.clearForm = function () {
    return this.each(function () {
        var type = this.type, tag = this.tagName.toLowerCase();
        if (tag == 'form')
            return jQuery(':input', this).clearForm();
        if (type == 'text' || type == 'password' || tag == 'textarea')
            this.value = '';
        else if (type == 'checkbox' || type == 'radio')
            this.checked = false;
        else if (tag == 'select')
            this.selectedIndex = -1;
    });
};

//smooth scroll on page
jQuery(function () {
    jQuery('#more a, .nav a, .nav li a, .brand, #footer li a').bind('click', function (event) {
        var jQueryanchor = jQuery(this);

        jQuery('[data-spy="scroll"]').each(function () {
            var jQueryspy = jQuery(this).scrollspy('refresh')
        });

        jQuery('html, body').stop().animate({
            scrollTop: jQuery(jQueryanchor.attr('href')).offset().top - 61
        }, 1500, 'easeInOutExpo');

        event.preventDefault();
    });
});

//collapse menu on click on mobile and tablet devices
jQuery('.nav a').click(function () {
    jQuery(".nav-collapse").collapse("hide")
});
