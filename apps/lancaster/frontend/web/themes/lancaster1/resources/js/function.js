$(document).ready(function() {
    $(".btn_hide, .btn_show").click(function() {
        if ($(this).attr('class') == 'btn_hide') {
            $(".viewbanner").slideToggle("slow");
            $(".viewshow").slideToggle("slow");
        } else {
            $(".viewshow").slideToggle("slow");
            $(".viewbanner").slideToggle("slow");
        }
    });

    $(".forximg").first().addClass('big');

    $(".mainblockitem").first().addClass('mainblockbig');
    
    var scroll_start = 0;
    var startchange = $('#startchange');
    var offset = startchange.offset();
    if (startchange.length) {
        $(document).scroll(function() {
            scroll_start = $(this).scrollTop();
            if (scroll_start > offset.top) {
                $(".navbar-default").css('background-color', '#ffffff');
            } else {
                $('.navbar-default').css('background-color', 'transparent');
            }
        });
    }
});

/*-----------end demo------------------------*/

/*-------------demo1--------------------------*/
(function($) {

    $.fn.parallax = function(options) {

        var windowHeight = $(window).height();

        // Establish default settings
        var settings = $.extend({
            speed: 0.15
        }, options);

        // Iterate over each object in collection
        return this.each(function() {

            // Save a reference to the element
            var $this = $(this);

            // Set up Scroll Handler
            $(document).scroll(function() {

                var scrollTop = $(window).scrollTop();
                var offset = $this.offset().top;
                var height = $this.outerHeight();

                // Check if above or below viewport
                if (offset + height <= scrollTop || offset >= scrollTop + windowHeight) {
                    return;
                }

                var yBgPosition = Math.round((offset - scrollTop) * settings.speed);

                // Apply the Y Background Position to Set the Parallax Effect
                $this.css('background-position', 'center ' + yBgPosition + 'px');

            });
        });
    }
}(jQuery));

$('.bg-1,.bg-3').parallax({
    speed: 0.15
});

$('.bg-2').parallax({
    speed: 0.25
});


