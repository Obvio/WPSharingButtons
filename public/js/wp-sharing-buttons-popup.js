(function ($) {
    'use strict';

    $(window).load(function () {

        /*
        * This function intercepts the click on the sharing buttons container and finds which button was clicked,
        * it then gets all the data attribute values from the button and launched  popup with the relevant attributes.
        * */

        $('.wp-sharing-buttons').on('click', 'a', function (event) {
            event.preventDefault();

            var url = $(event.currentTarget).data('url');
            var paramUrl = $(event.currentTarget).data('u');
            var paramText = $(event.currentTarget).data('t');
            var windowName = $(event.currentTarget).data('name');
            var width = $(event.currentTarget).data('width');
            var height = $(event.currentTarget).data('height');
            var left = (window.innerWidth - width) * .5;
            var top = (window.innerHeight - height) * .5;

            url += '?';

            if(paramUrl !== ''){
                url += paramUrl + '=' + encodeURIComponent(window.location.href) + '&';
            }
            
            if(paramText !== ''){
                url += paramText + '=' + encodeURIComponent(document.title);
            }
            
            window.open(url, windowName,
                'left=' + left + ',top=' + top + ',width=' + width + ',height=' + height + ',toolbar=0,status=0');
        });
    });

})(jQuery);
