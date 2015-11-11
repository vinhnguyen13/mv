$(document).ready(function() {
	
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
    var txtResetDropdown = 'Loại...',
    	flagReset = false,
    	$textSearch = $('#search-kind .form-group input'),
    	$dropdown = $('#search-kind .dropdown ul'),
    	$txtDropdownSelected = $('#search-kind .txt-selected');

    $('.search-select a').on('click', function() {
    	var _this = $(this),
    		txtPlaceholder = _this.data('placeholder'),
    		getId = _this.attr('rel');
    	
    	$txtDropdownSelected.html(txtResetDropdown);

    	$('.search-select').removeClass('active');
    	$textSearch.attr('placeholder', txtPlaceholder);
    	_this.parent().addClass('active');
    	
        return false;
    });

    var $wrapListSuggest = $('.type-search ul'),
        countStep = 1,
        countCurrent = 0,
        lenghtStep = $('.search-wrap').length,
        lenghtSuggest = 0;

    var objEvent = {
        open: function(countStep, flagOpen) {//1. edit, 0. open normal
            $('.search-wrap').addClass('hidden-effect');

            flagOpen ? $('#step-'+countStep).addClass('edit-suggest') : $('#step-'+countStep).removeClass('edit-suggest');

            $('#step-'+countStep).removeClass('hidden-effect');
            setTimeout(function() {
                $('#step-'+countStep).addClass('active');
            }, 30);
        },
        close: function() {
            $('.search-wrap').removeClass('edit-suggest');
            $('.search-wrap').removeClass('active');
            setTimeout(function() {
                $('.search-wrap').addClass('hidden-effect');
            }, 300);
        },
        btnclose: function() {
            $('.btn-close-search').on('click',function(e) {
                e.preventDefault();

                $(this).closest('.search-wrap').removeClass('active');
                objEvent.close();

                return false;
            });
        },
        selectItem: function() {
            $('.search-item ul a').on('click', function(e) {
                e.preventDefault();
                var _this = $(this),
                    txt = _this.text(),
                    $itemSuggest = $('<li data-step="'+countStep+'"><i>x</i><span></span></li>');

                if( !_this.closest('.search-wrap').hasClass('edit-suggest') ) {
                    $itemSuggest.find('span').text(txt);
                    $wrapListSuggest.append($itemSuggest);
                }else {
                    countStep = parseInt($('.edit-suggest').attr('id').split('-')[1]);
                    $('.type-search li[data-step="'+countStep+'"] span').text(txt);
                }
                
                
                $('#step-'+countStep).removeClass('active');
                objEvent.close();
                
                $wrapListSuggest.show();

                objEvent.resizeWidthInput();

                objEvent.checkCounter();

                return false;
            });
        },
        removeSuggest: function() {
            $(document).on('click', '.type-search li i',function(e) {
                e.preventDefault();
                var _this = $(this),
                    $parentList = _this.parent(),
                    getStep = $parentList.data('step'),
                    count = 0;
                for( var i = lenghtSuggest; i >= 0 ; i-- ) {
                    if( i >= getStep ) {
                        $('.type-search li').eq(i-1).remove();
                    }
                }
                objEvent.close();
                countStep = getStep - 1;
                objEvent.resizeWidthInput();
                objEvent.checkCounter();
            });
            
        },
        resizeWidthInput: function() {
            var wInput = $('.type-search').outerWidth() - $wrapListSuggest.outerWidth();
            $('.type-search input').css('width',wInput+'px');
        },
        reOpenBySuggest: function() {
            $(document).on('click', '.type-search li span',function(e) {
                e.preventDefault();
                var _this = $(this),
                    boxId = _this.parent().data('step');
                
                objEvent.open(boxId, 1);
            });
        },
        checkCounter: function() {
            lenghtSuggest = $('.type-search ul li').length;

            if( lenghtStep === lenghtSuggest ) {
                return;
            }else if( countStep <= lenghtStep ){
                countStep += 1;
            }else {
                return;
            }
        }
    };
    $('.type-search input').on('click', function(e) {
        e.preventDefault();
        var _this = $(this);
        if( countStep <= $('.search-wrap').length && lenghtSuggest != lenghtStep ) {
            objEvent.open(countStep);
        }
    });

    objEvent.btnclose();
    objEvent.selectItem();
    objEvent.removeSuggest();
    objEvent.reOpenBySuggest();

    $('#search-kind ul a').on('click', function(){
    	var _this = $(this),
    		valueID = _this.data('valueId'),
    		txt = _this.data('valueText');
    	$txtDropdownSelected.html(txt);
        $('#search-kind .dropdown').removeClass('open');
        $('#search-kind').attr('action', _this.attr('href'));
        return false;
    });
    //end header

    //start page du-an
    $('.item-infor a').each(function() {
        var _this = $(this);
        if(_this.parent().hasClass('active')) {
            var idShowBox = _this.attr('href');
            $(idShowBox).fadeIn();
        }
    });

    $('.list-pics-tdxd .item-pics a').on('click',function() {
        $('#item-tdxd').html('');
        $('.list-pics-tdxd .item-pics .wrap-img').removeClass('active');
        var _this = $(this),
            arrPic = _this.data('imgsrc'),
            $wrapSlide = $('<div id="slideTDXD" class="owl-carousel"></div>'),
            $thumSlide = $('<div id="slideTDXD-thum" class="owl-carousel thumnail-list"></div>');

        _this.closest('.wrap-img').addClass('active');
        for(var i = 0; i < arrPic.length; i++) {
            var $itemWrap = $('<div class="item bgcover img-big-duan" style="background-image:url('+arrPic[i]+')"></div>'),
                $itemThum = $('<div class="item bgcover img-big-duan" style="background-image:url('+arrPic[i]+')"></div>');
            $wrapSlide.append($itemWrap);
            $thumSlide.append($itemThum);
        }
        $('#item-tdxd').append($wrapSlide).append($thumSlide);
        runSlideDuAn('#slideTDXD','#slideTDXD-thum');

        return false;
    });

    $('.item-infor > a').on('click',function() {
        player.pause();
        var _this = $(this);

        if( _this.parents('.show-infor').length > 0 ) {
            _this.parents('.show-infor').find('.item-infor').removeClass('active');
            _this.parent().addClass('active');
            if( _this.parent().find('.show-infor').length > 0 ) {
                l(1);
            }

        }else {
            $('.item-infor').removeClass('active');
            _this.parent().addClass('active');
            $('.item-infor .show-infor').slideUp('fast');
            if( _this.parent().find('.show-infor').length > 0 ) {
                _this.parent().find('.show-infor').slideDown('fast');
            }
        }
        
        if( _this.parent().hasClass('group-menu') ) {
            _this.parent().find('.item-infor').eq(0).addClass('active');
            if( _this.parent().find('.item-infor').eq(0).find('.show-infor').length > 0 ) {
                _this.parent().find('.item-infor').eq(0).find('.show-infor').find('.item-infor').eq(0).addClass('active');
            }
        }

        //event show khu dan cu & khu thuong mai
        if( _this.data('srcmb')) {
            $('#item-kch .img-alone, #item-ktm .img-alone').remove();
            $('.item-tab').hide();
            var srcImg = _this.data('srcmb'),
                $imgAdd = $('<img class="img-alone" src="'+srcImg+'" alt="" />'),
                hrefLink = _this.attr('href');
            hrefLink === '#item-kch' ? $('#item-kch').append($imgAdd) : $('#item-kch .img-alone').remove();
            hrefLink === '#item-ktm' ? $('#item-ktm').append($imgAdd) : $('#item-ktm .img-alone').remove();
        }
        if( _this.data('tabsub')) {
            $('#item-kch .img-alone, #item-ktm .img-alone').remove();
            var srcImg = _this.data('tabsub'),
                hrefLink = _this.attr('href');
            $('.item-tab').hide();
            hrefLink === '#item-kch' ? $('#item-kch '+srcImg).show() : $('#item-kch '+srcImg).hide();
            hrefLink === '#item-ktm' ? $('#item-ktm '+srcImg).show() : $('#item-ktm '+srcImg).hide();
        }
        //End event show khu dan cu & khu thuong mai

        //show box left detail
        var idShowBox = _this.attr('href');
        if( idShowBox === '#item-tdxd' ) {
            $('.list-pics-tdxd .item-pics a').eq(0).trigger('click');
        }
        $('.item-detail').hide();
        $(idShowBox).css({
            display: 'block',
            visibility: 'hidden'
        });
        $(idShowBox).css('visibility','visible').hide().fadeIn();
        //End show box left detail

        return false;
    });
    //end page du-an

    //start scroll fixed header
    var secondaryNav = $('.cd-secondary-nav'),
        secondaryNavTopPosition = secondaryNav.offset().top,
        contentSections = $('.cd-section'),
        hFirstNav = secondaryNav.outerHeight(),
        valShow;

    $(window).on('scroll', function(){
        valShow = $(window).scrollTop() - hFirstNav/2
        if( valShow > 0 ) {
            secondaryNav.addClass('is-fixed');
            setTimeout(function() {
                secondaryNav.addClass('animate-children');
            }, 50);
        } else {
            secondaryNav.removeClass('is-fixed');
            setTimeout(function() {
                secondaryNav.removeClass('animate-children');
            }, 50);
        }
    });
    //end scroll fixed header
});

function l(x){console.log(x);}