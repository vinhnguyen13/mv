var arrJSONReturn = {
    objTT: [],
    objLoai: [],
    objTTuc: []
},objEvent;

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip({
        html: true
    });
    var $modal = $('.modal');
    $modal.on('show.bs.modal', function(e) {
        var curModal;
        curModal = this;
        $modal.each(function(i) {
          if (this !== curModal) {
            $(this).modal("hide");
          }
        });

        var _this = $(this);
        if( !$('body').hasClass('modal-open') ) {
            //$('body').addClass('modal-open').css('padding-right', '17px');
        }
    });
	
    //start click scroll to top
	var btnScrollTop = $('<div id="topcontrol" title="Lên đầu trang"></div>')
	$('body').append(btnScrollTop);
	$(window).scroll(function(){
		var valScroll = $(this).scrollTop();

		if( valScroll >= 100 ) {
			$("#topcontrol").css("display", "block");
			$('#topcontrol').stop().animate({
				opacity: 1
			}, 500);
		}else {
			valScroll = 0;
			$("#topcontrol").css({
				display: 'none'
			});
			$('#topcontrol').stop().animate({
				opacity: 0
			},0);
		}
    });

    $("#topcontrol").on('click', function() {
    	$('body,html').stop().animate({
			scrollTop: 0
		}, 800);
    });
    //end click scroll to top

    //start header
    
    //end header

    //start page du-an
    $('.item-infor a').each(function() {
        var _this = $(this);
        if(_this.hasClass('active')) {
            var idShowBox = _this.attr('href');
            $(idShowBox).fadeIn();
        }
    });

    $('.list-pics-tdxd .item-pics a').on('click',function() {
        $('.item-tdxd').html('');
        $('.list-pics-tdxd .item-pics .wrap-img').removeClass('active');
        var _this = $(this),
            arrPic = _this.data('imgsrc'),
            $wrapSlide = $('<div id="slideTDXD" class="owl-carousel"></div>'),
            $thumSlide = $('<div id="slideTDXD-thum" class="owl-carousel thumnail-list"></div>');

        $('.list-pics-tdxd .item-pics a').removeClass('active');
        _this.addClass('active');
        for(var i = 0; i < arrPic.length; i++) {
            var $itemWrap = $('<div class="item bgcover img-big-duan" style="background-image:url('+arrPic[i]+')"></div>'),
                $itemThum = $('<div class="item bgcover img-big-duan" style="background-image:url('+arrPic[i]+')"></div>');
            $wrapSlide.append($itemWrap);
            $thumSlide.append($itemThum);
        }
        $('.item-tdxd').append($wrapSlide).append($thumSlide);
        runSlideDuAn('#slideTDXD','#slideTDXD-thum');

        return false;
    });

    $('.tab-type-1 > a, .tab-type-2 > a').on('click',function(e) {
        e.preventDefault();
        var _this = $(this),
            idShowBox = _this.attr('href');
        $('.item-infor > a').removeClass('active');
        _this.addClass('active');
        $('.show-infor').slideUp('fast');
        _this.parent().find('.show-infor').slideDown('fast');
        showBoxTab(idShowBox);
        return false;
    });

    $('.tab-type-3 > a').on('click',function(e) {
        e.preventDefault();
        var _this = $(this),
            idShowBox = _this.attr('href');
        $('.item-infor > a').removeClass('active');
        _this.addClass('active');
        $('.show-infor').slideUp('fast');
        _this.parent().find('.show-infor').slideDown('fast');
        if( _this.parent().find('a[data-active-first]').length > 0 ) {
            _this.parent().find('a[data-active-first]').addClass('active');
            _this.parent().find('a[data-active-first]').trigger('click');
        }
        showBoxTab(idShowBox);
        return false;
    });

    $('.video-img').on('click',function(e) {
        e.preventDefault();
        var srcVideo = $(this).attr('rel');
        $('.video-img').removeClass('active');
        $(this).addClass('active');
        $('.phim3d').attr('src',srcVideo);
        return false;
    });

    $('.tab-type-4 > a').on('click',function(e) {
        $('.map').remove();
        e.preventDefault();
        var _this = $(this),
            idShowBox = _this.attr('href');
        $('.item-infor > a').removeClass('active');
        _this.addClass('active');
        $('.show-infor').slideUp('fast');
        _this.parent().find('.show-infor').slideDown('fast');
        _this.parent().find('a[data-active-first]').addClass('active');
        if( _this.parent().find('a[data-active-first]:last-child').data('srcmb') != '') {
            var idBoxSub = _this.parent().find('a[data-active-first]:last-child').attr('href'),
                srcImg = _this.parent().find('a[data-active-first]:last-child').data('srcmb'),
                $imgAdd = $('<img class="img-alone" src="'+srcImg+'" alt="" usemap="#map" />'),
                arrCoord = _this.parent().find('a[data-active-first]:last-child').data('coord'),
                arrImg = _this.parent().find('a[data-active-first]:last-child').data('images'),
                mapRender = $('<map name="map" class="map" id="map"></map>');

            $(idBoxSub).html('');
            $('.img-alone').remove();
            $('.item-tab').hide();
            $(idBoxSub).append($imgAdd);
            for( var i = 0; i < arrCoord.length; i++ ) {
                var typeArea = '';
                if( arrCoord[i].length <= 4 ) {
                    typeArea = 'rect';
                }else {
                    typeArea = 'poly';
                }
                var areaItem = $('<area shape="'+typeArea+'" id="area'+(i+1)+'" class="group1 area" coords="'+arrCoord[i].toString()+'" href="'+arrImg[i]+'" alt="" title="">')
                mapRender.append(areaItem);
            }
            $(idBoxSub).append(mapRender);
            showBoxTab(idBoxSub);
            $(idBoxSub).closest('.item-detail').show();
        }else {
            showBoxTab(idShowBox);    
        }
        $('.img-alone').maphilight();
        $(".group1").colorbox({className:'boxShowMatBang'});
        return false;
    });

    $('.tab-type-4 a[data-srcmb]').on('click',function() {
        $('.map').remove();
        var srcImg = $(this).data('srcmb'),
            idBoxSub = $(this).attr('href'),
            $imgAdd = $('<img class="img-alone" src="'+srcImg+'" alt="" usemap="#map" />'),
            arrCoord = $(this).data('coord'),
            arrImg = $(this).data('images'),
            mapRender = $('<map name="map" class="map" id="map"></map>');

        $(idBoxSub).html('');
        $('.img-alone').remove();
        $('.item-tab').hide();
        $('.tab-type-4 a[data-srcmb]').removeClass('active');
        $(this).parent().parent().parent().find('>a').addClass('active');
        $('.tab-sub-link').removeClass('active');
        $(this).addClass('active');
        $(idBoxSub).append($imgAdd);

        for( var i = 0; i < arrCoord.length; i++ ) {
            var typeArea = '';
            if( arrCoord[i].length <= 4 ) {
                typeArea = 'rect';
            }else {
                typeArea = 'poly';
            }
            var areaItem = $('<area shape="'+typeArea+'" id="area'+(i+1)+'" class="group1 area" coords="'+arrCoord[i].toString()+'" href="'+arrImg[i]+'" alt="" title="">')
            mapRender.append(areaItem);
        }
        $(idBoxSub).append(mapRender);

        showBoxTab(idBoxSub);
        $(idBoxSub).closest('.item-detail').show(); 
        $('.img-alone').maphilight();
        $(".group1").colorbox({className:'boxShowMatBang'});
        return false;
    });

    $('.tab-sub-link').on('click',function() {
        var idShowBox = $(this).attr('href');
        $('.item-tab').hide();
        showBoxTab(idShowBox);   
        $(idShowBox).closest('.item-detail').show(); 
        $(this).closest('.show-infor').find('.active').removeClass('active');
        $(this).addClass('active');
        return false; 
    });

    function showBoxTab(idShowBox) {
        $('.item-detail').hide();
        $(idShowBox).css({
            display: 'block',
            visibility: 'hidden'
        });
        $(idShowBox).css('visibility','visible').hide().fadeIn();
    }
    //end page du-an

    //start scroll fixed header
    
    //end scroll fixed header

    //start home page search
    //animateSearch();
    //end home page search
    

    $('.infor-user-profile .inner-infor').scfix();

});


function l(x){console.log(x);}

function scrollFixed() {
    var $colRight = $('.contact-wrapper'),
        hColRight = $colRight.outerHeight(),
        wColRight = $colRight.outerWidth(),
        $colLeft = $('.dt-left-col'),
        hColLeft = $colLeft.outerHeight(),
        $header = $('.gallery-detail'),
        hItemTop = parseInt($('#detail-listing .modal-dialog').css('margin-top')) + $('.gallery-detail').outerHeight() + 20,
        hItemBottom = parseInt($('#detail-listing .modal-dialog').css('margin-top')) + $('#detail-listing .wrap-modal').outerHeight() - Math.max(hColRight, hColLeft),
        posiTopColRight = $colRight.offset().top;

    $('#detail-listing').scroll(function (e) {
        e.preventDefault();
        var valScroll = $(this).scrollTop(),
            valPosiItem;
        if( valScroll >= posiTopColRight ) {
            valPosiItem = valScroll - posiTopColRight;
            $colRight.addClass('fixed-scroll');
            $colRight.css({
                position: 'absolute',
                width: wColRight+'px',
                'margin-top': valPosiItem+'px'
            });
        }else {
            $colRight.removeClass('fixed-scroll');
            $colRight.css({
                position: 'static',
                width: 'auto',
                'margin-top': 0
            });
        }
    });
}

$.fn.scfix = function (options) {

    return this.each(function() {
        var defaults = {
            elTopH: ['.home-page'],
            elBottomH: [],
            elCol: $('.quanlytinraoban'),
            headerFix: true,
            headerFixEl: $('header'),
            ajaxLoad: false
        },
        sc = {},
        valScroll = 0,
        posiTopEl = 0,
        hTop = 0,
        hBottom = 0,
        el = $(this),
        wElFirst = el.outerWidth(),
        hColOther = 0;

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        posiTopEl = el.offset().top;

        hColOther = sc.settings.elCol.outerHeight();

        if ( sc.settings.ajaxLoad ) {
            hColOther = sc.settings.elCol.outerHeight();
        }

        for ( var i = 0; i < sc.settings.elTopH.length; i++ ) {
            var $item = $(sc.settings.elTopH[i]);
            hTop += $item.outerHeight();
        }
        for ( var i = 0; i < sc.settings.elBottomH.length; i++ ) {
            var $item = $(sc.settings.elBottomH[i]);
            hBottom += $item.outerHeight();
        }

        $(window).on('scroll', function (e) {
            e.preventDefault();
            valScroll = $(this).scrollTop();
            
            if ( sc.settings.headerFix ) {
                if ( sc.settings.headerFixEl.hasClass('is-fixed') ) {
                    hTop = sc.settings.headerFixEl.outerHeight();
                }
            }

            if ( valScroll >= posiTopEl ) {
                el.addClass('fixElScroll');
                el.css({
                    'transform': 'translateY('+(hTop+20)+'px)',
                    '-webkit-transform': 'translateY('+(hTop+20)+'px)',
                    '-moz-transform': 'translateY('+(hTop+20)+'px)',
                    '-ms-transform': 'translateY('+(hTop+20)+'px)',
                    width: wElFirst+'px'
                });
            }else {
                el.removeClass('fixElScroll');
                el.css({
                    'transform': 'translateY(0)',
                    width: 'auto'
                });
            }
        });
    });

}