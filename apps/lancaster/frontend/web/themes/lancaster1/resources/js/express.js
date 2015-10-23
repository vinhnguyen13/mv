$(function(){
    Express.init();
});


var Express = {
    init: function () {
        $("#myCarousel").css({backgroundColor: '#234959'});
    },
    HomeToogleInfomation: function () {
        $(".viewbanner").show();
        $(".viewshow").hide();
        $(".btn_hide, .btn_show").click(function() {
            if ($(this).attr('class') == 'btn_hide') {
                $(".viewbanner").slideToggle("slow");
                $(".viewshow").slideToggle("slow");
            } else {
                $(".viewshow").slideToggle("slow");
                $(".viewbanner").slideToggle("slow");
            }
        });
    },
    cropResize: function () {
        var height = $(window).height() - $('.navbar').height();
        $(".imgcontent").css({height: height, width: $(window).width()})
        $('.item .imgcontent img').each(function(i, item) {
            var img_height = $(item).height();
            var div_height = $(item).parent().height();
            $(item).attr('style', '');
            if(img_height<div_height){
                //INCREASE HEIGHT OF IMAGE TO MATCH CONTAINER
                $(item).css({'width': 'auto', 'height': div_height });
                //GET THE NEW WIDTH AFTER RESIZE
                var img_width = $(item).width();
                //GET THE PARENT WIDTH
                var div_width = $(item).parent().width();
                //GET THE NEW HORIZONTAL MARGIN
                var newMargin = (div_width-img_width)/2+'px';
                console.log(div_width);
                //SET THE NEW HORIZONTAL MARGIN (EXCESS IMAGE WIDTH IS CROPPED)
                //$(item).css({'margin-left': newMargin });
            }else{
                //CENTER IT VERTICALLY (EXCESS IMAGE HEIGHT IS CROPPED)
                var newMargin = (div_height-img_height)/2+'px';
                $(item).css({'margin-top': newMargin});
            }
        });
    }
}