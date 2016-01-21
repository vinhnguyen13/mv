var arrJSONReturn = {
    objTT: [],
    objLoai: [],
    objTTuc: []
},objEvent;

$(document).ready(function() {

    var hscreenFirst = $('.search-reals, .intro-duan').outerHeight();
    $('#scroll-rearch-reals').css({height: (hscreenFirst-60)+'px'});

    $('[data-toggle="tooltip"]').tooltip({
        html: true
    });

    $('.see-less').on('click', function() {
        if ( $(this).hasClass('active') ) {
            $(this).parent().find('.wrap-infor-attr').slideDown();
            $(this).removeClass('active');
            $(this).find('.txt-toggle').html('see less');
        }else {
            $(this).addClass('active');
            $(this).parent().find('.wrap-infor-attr').slideUp();
            $(this).find('.txt-toggle').html('see more');
        }
        return false;
    });


    var modalVerticalCenterClass = ".modal";
    function centerModals($element) {
        var $modals;
        if ($element != undefined) {
            $modals = $element;
        } else {
            $modals = $(modalVerticalCenterClass + ':visible');
        }
        $modals.each( function(i) {
            var $clone = $(this).clone().css('display', 'block').appendTo('body');
            var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 2);
            top = top > 0 ? top : 0;
            $clone.remove();
            $(this).find('.modal-content').css("margin-top", top);
        });
    }
    var $modal = $('.modal');
    $modal.on('show.bs.modal', function(e) {
        var _this = $(this),
            $root = _this.parent();

        $('body').append(_this);

        if ( $('.modal-backdrop').length > 0 ) {
            $('.modal-backdrop').trigger('click');
        }
    });

    $(window).on('resize', function () {
        //centerModals();
    });
    
    //start click scroll to top
	var btnScrollTop = $('<div id="topcontrol" title="Lên đầu trang"></div>');
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

    $('.settings').dropdown({
        linkShow: $('.sub-setting-user')
    });

    var regis_listing = {
        wrapStep: $('#regis-listing .wrap-step'),
        pagi: $('.list-steps li'),
        btnNext: $('.btn-next-step'),
        lenStep: $('.list-steps li').length,
        init: function () {
            regis_listing.pagi.find('a').on('click', regis_listing.showStep);
            regis_listing.btnNext.on('click', regis_listing.next_step);
            regis_listing.loadStep();
        },
        loadStep: function () {
            regis_listing.pagi.each(function (i) {
                if ( $(this).hasClass('active') ) {
                    regis_listing.wrapStep.eq(i).removeClass('hide-step');
                }
            });
        },
        showStep: function (e) {
            e.preventDefault();
            var _this = $(this),
                index = _this.parent().index();

            regis_listing.pagi.removeClass('active');
            _this.parent().addClass('active');

            regis_listing.ani(index);

            if ( index + 1 == regis_listing.lenStep ) {
                regis_listing.btnNext.hide();
            }else {
                regis_listing.btnNext.show();
            }
        },
        ani: function (index) {
            regis_listing.wrapStep.addClass('fadeOutUpShort hide-step');
            regis_listing.wrapStep.eq(index).removeClass('hide-step').addClass('fadeOutDownShort');
        },
        next_step: function (e) {
            e.preventDefault();
            var current = $('.list-steps li.active').index();
            if ( current + 1 == regis_listing.lenStep - 1 ) {
                $(this).hide();
            }
            regis_listing.pagi.eq(current + 1).find('a').trigger('click');
        }
    }
    regis_listing.init();

    var checkbox_ui = {
        item: $('.checkbox-ui'),
        init: function () {
            checkbox_ui.item.find('label').on('click', checkbox_ui.toggleCheck);

            checkbox_ui.item.find('em').on('click', checkbox_ui.toggleCheck);
        },
        load: function () {

        },
        toggleCheck: function (e) {
            e.preventDefault();
            var _this = $(this);
            if ( _this.parent().find('input[type=checkbox]').prop("checked") ) {
                _this.parent().find('input[type=checkbox]').prop("checked", false);
                checkbox_ui.checkedItem(_this, false);
            }else {
                _this.parent().find('input[type=checkbox]').prop("checked", true);
                checkbox_ui.checkedItem(_this, true);
            }
        },
        checkedItem: function (item, flagChecked) {
            if ( flagChecked ) {
                item.parent().find('em').removeClass('fa-square-o').addClass('fa-check-square-o');
            }else {
                item.parent().find('em').removeClass('fa-check-square-o').addClass('fa-square-o');
            }
        }
    };
    checkbox_ui.init();

    $('.btn-out-more, .btn-scroll-down a').on('click', function () {
        $('body,html').stop().animate({
            scrollTop: $(window).outerHeight() - 60
        }, 800);
        return false;
    });

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

$.fn.dropdown = function (options) {

    return this.each(function() {
        var el = $(this);

        var defaults = {
            wrap: el.find('.wrap-effect'),
            linkShow: $('.tabs-detail-item li .more-item'),
            close: ''
        },
        sc = {};

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        sc.settings.linkShow.on('click', function () {
            if ( $(this).hasClass('active') && $(this).parent().find(el).is(':visible') ) {
                sc.settings.linkShow.removeClass('active');
                $(document).trigger('click');
                return;
            }
            sc.settings.linkShow.removeClass('active');
            $(this).addClass('active');

            open($(this));

            return false;
        });

        function open (item) {
            el.addClass('hidden-effect');
            el.removeClass('active');

            item.parent().find(el).removeClass('hidden-effect');
            setTimeout(function() {
                item.parent().find(el).addClass('active');
            }, 30);

            close();
        }

        function close () {
            $(document).on('click', clickOutsideevent);
        }

        function clickOutsideevent (e) {
            var container = el.parent();
            
            if ( !container.is(e.target) && container.has(e.target).length === 0 || el.has(e.target).length > 0 ) {
                el.removeClass('active');

                setTimeout(function() {
                    el.addClass('hidden-effect');
                }, 300);
            }
        };

    });
}

/*!
 * jQuery Print Previw Plugin v1.0.1
 *
 * Copyright 2011, Tim Connell
 * Licensed under the GPL Version 2 license
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Date: Wed Jan 25 00:00:00 2012 -000
 */
 
;(function($) { 
    
    // Initialization
    $.fn.printPreview = function() {
        this.each(function() {
            $(this).bind('click', function(e) {
                e.preventDefault();
                var url = window.location.href;
                url = url.replace(/\/(tin-tuc|photo|danh-gia|infographic)\//, "/print/");
                window.open(url, "_blank")
                if (!$('#print-modal').length) {
                    $.printPreview.loadPrintPreview();
                }
            });
        });
        return this;
    };
    
    // Private functions
    var mask, size, print_modal, print_controls;
    $.printPreview = {
        loadPrintPreview: function() {
            // Declare DOM objects
            print_modal = $('<div id="print-modal"></div>');
            print_controls = $('<div id="print-modal-controls">' + 
                                    '<a href="#" class="print" title="Print page">Print page</a>' +
                                    '<a href="#" class="close" title="Close print preview">Close</a>').hide();
            var print_frame = $('<iframe id="print-modal-content" scrolling="no" border="0" frameborder="0" name="print-frame" />');

            // Raise print preview window from the dead, zooooooombies
            print_modal
                .hide()
                .append(print_controls)
                .append(print_frame)
                .appendTo('body');

            // The frame lives
            for (var i=0; i < window.frames.length; i++) {
                if (window.frames[i].name == "print-frame") {    
                    var print_frame_ref = window.frames[i].document;
                    break;
                }
            }
            print_frame_ref.open();
            print_frame_ref.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' +
                '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">' + 
                '<head><title>' + document.title + '</title></head>' +
                '<body></body>' +
                '</html>');
            print_frame_ref.close();
            
            // Grab contents and apply stylesheet
            var $iframe_head = $('head link[media*=print], head link[media=all]').clone(),
                $iframe_body = $('body > *:not(#print-modal):not(script)').clone();
            $iframe_head.each(function() {
                $(this).attr('media', 'all');
            });
            if ( false ) { //!$.browser.msie && !($.browser.version < 7)
                $('head', print_frame_ref).append($iframe_head);
                $('body', print_frame_ref).append($iframe_body);
            }
            else {
                $('body > *:not(#print-modal):not(script)').clone().each(function() {
                    $('body', print_frame_ref).append(this.outerHTML);
                });
                $('head link[media*=print], head link[media=all]').each(function() {
                    $('head', print_frame_ref).append($(this).clone().attr('media', 'all')[0].outerHTML);
                });
            }
            
            // Disable all links
            $('a', print_frame_ref).bind('click.printPreview', function(e) {
                e.preventDefault();
            });
            
            // Introduce print styles
            $('head').append('<style type="text/css">' +
                '@media print {' +
                    '/* -- Print Preview --*/' +
                    '#print-modal-mask,' +
                    '#print-modal {' +
                        'display: none !important;' +
                    '}' +
                '}' +
                '</style>'
            );

            // Load mask
            $.printPreview.loadMask();

            // Disable scrolling
            $('body').css({overflowY: 'hidden', height: '100%'});
            $('img', print_frame_ref).load(function() {
                print_frame.height($('body', print_frame.contents())[0].scrollHeight);
            });
            
            // Position modal            
            starting_position = $(window).height() + $(window).scrollTop();
            var css = {
                    top:         starting_position,
                    height:      '100%',
                    overflowY:   'auto',
                    zIndex:      10000,
                    display:     'block'
                }
            print_modal
                .css(css)
                .animate({ top: $(window).scrollTop()}, 400, 'linear', function() {
                    print_controls.fadeIn('slow').focus();
                });
            print_frame.height($('body', print_frame.contents())[0].scrollHeight);
            
            // Bind closure
            $('a', print_controls).bind('click', function(e) {
                e.preventDefault();
                if ($(this).hasClass('print')) { window.print(); }
                else { $.printPreview.distroyPrintPreview(); }
            });
        },
        
        distroyPrintPreview: function() {
            print_controls.fadeOut(100);
            print_modal.animate({ top: $(window).scrollTop() - $(window).height(), opacity: 1}, 400, 'linear', function(){
                print_modal.remove();
                $('body').css({overflowY: 'auto', height: 'auto'});
            });
            mask.fadeOut('slow', function()  {
                mask.remove();
            });             

            $(document).unbind("keydown.printPreview.mask");
            mask.unbind("click.printPreview.mask");
            $(window).unbind("resize.printPreview.mask");
        },
        
        /* -- Mask Functions --*/
        loadMask: function() {
            size = $.printPreview.sizeUpMask();
            mask = $('<div id="print-modal-mask" />').appendTo($('body'));
            mask.css({              
                position:           'absolute', 
                top:                0, 
                left:               0,
                width:              size[0],
                height:             size[1],
                display:            'none',
                opacity:            0,                          
                zIndex:             9999,
                backgroundColor:    '#000'
            });
    
            mask.css({display: 'block'}).fadeTo('400', 0.75);
            
            $(window).bind("resize..printPreview.mask", function() {
                $.printPreview.updateMaskSize();
            });
            
            mask.bind("click.printPreview.mask", function(e)  {
                $.printPreview.distroyPrintPreview();
            });
            
            $(document).bind("keydown.printPreview.mask", function(e) {
                if (e.keyCode == 27) {  $.printPreview.distroyPrintPreview(); }
            });
        },
    
        sizeUpMask: function() {
            if ( false ) { //$.browser.msie
                // if there are no scrollbars then use window.height
                var d = $(document).height(), w = $(window).height();
                return [
                    window.innerWidth ||                        // ie7+
                    document.documentElement.clientWidth ||     // ie6  
                    document.body.clientWidth,                  // ie6 quirks mode
                    d - w < 20 ? w : d
                ];
            } else { return [$(document).width(), $(document).height()]; }
        },
    
        updateMaskSize: function() {
            var size = $.printPreview.sizeUpMask();
            mask.css({width: size[0], height: size[1]});
        }
    }
})(jQuery);