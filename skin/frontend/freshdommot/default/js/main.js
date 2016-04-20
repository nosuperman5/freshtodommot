(function($) {
    "use strict";
    $(document).ready(function() {
        (function(){
            if($(window).width() > 768) {
                $(document).on('mouseover', '#nav > li > a', function(){
                    var $this = $(this),
                        $departmentsDropdownWrapNav = $this.closest('.departments__dropdown__wrap-nav'),
                        $dropdown = $this.find('+ ul'),
                        $positionTop = $this.position().top,
                        $wrapNavHeight = $departmentsDropdownWrapNav.outerHeight(),
                        $dropdownHeight = $dropdown.outerHeight(),
                        $summarySize = $positionTop + $dropdownHeight,
                        $positionDropdown = $positionTop - ($summarySize - $wrapNavHeight);

                    if($summarySize > $wrapNavHeight) {
                        $dropdown.css('top', $positionDropdown);
                    } else {
                        $dropdown.css('top', $positionTop);
                    };
                });
            };
        })();

        $(document).on('click', '[data-toggle-shadow]', function() {
            $('[data-toggle-dropdown]').removeClass("open");
            $('body').removeClass("open-dropdown");
        });

        $(document).on('click', '[data-toggle-btn]', function(){
            var $this = $(this),
                dataToggleContainer = $this.closest('[data-toggle-container]'),
                dataToggleDropdown = dataToggleContainer.find('[data-toggle-dropdown]'),
                $body = $('body');

            dataToggleDropdown.toggleClass("open");
            $body.toggleClass("open-dropdown");

            return false;
        });

        //$(document).on('click', 'a[href^="#"]', function() {
        //    var e = $(this).attr("href");
        //
        //    $("html, body").animate({
        //        "scrollTop": $(e).offset().top
        //    }, 500);
        //
        //    return false;
        //});
    });
})(jQuery);
