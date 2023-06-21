(function($) {

    "use strict";

    function switch_tabs(obj) {

        if (obj.attr('class') != "active") { // fixed in version 1.1.2

            obj.parent().parent().find('.bkb-tab-content').slideUp("slow");

            obj.parent().find('li').removeClass("active");

            var id = obj.find("a", 0).attr("rel");

            $('#' + id).slideDown("slow");

            obj.addClass("active");

        }

    }

    $('.bkb-tabs li').click(function() {

        if ($(this).find(".bkb-link").attr("class") != "bkb-link") {
            switch_tabs($(this));
        }

    });
    
})(jQuery);