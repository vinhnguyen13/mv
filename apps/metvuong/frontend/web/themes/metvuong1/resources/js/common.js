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
    	$textSearch = $('#search-kind .form-group input');

    var $wrapListSuggest = $('.type-search ul'),
        countStep = 1,
        countCurrent = 0,
        lenghtStep = $('.search-wrap').length,
        lenghtSuggest = 0;

    if( $('.search-select.active a').hasClass('no-suggest') ) {
        $textSearch.addClass('no-suggest');
    }

    $('.search-select a').on('click', function() {
    	var _this = $(this),
    		txtPlaceholder = _this.data('placeholder');

        objEvent.reset();

        if( _this.data('stepFix') ) {
            countStep = parseInt(_this.data('stepFix').split('-')[1]);
        }else {
            countStep = 1;
        }
    	
    	$('.search-select').removeClass('active');
    	$textSearch.attr('placeholder', txtPlaceholder);
    	_this.parent().addClass('active');
    	
        return false;
    });

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
            $('.search-item > ul a').on('click', function(e) {
                e.preventDefault();
                var _this = $(this),
                    txt = _this.text(),
                    $itemSuggest = $('<li data-step="'+countStep+'"><i>x</i><span></span></li>');

                if( !_this.closest('.search-wrap').hasClass('edit-suggest') ) {
                    if( _this.closest('.search-wrap').data('stepBox') == 'fixed' ) {
                        $wrapListSuggest.html('');
                    }
                    $itemSuggest.find('span').text(txt);
                    $wrapListSuggest.append($itemSuggest);
                }else {
                    countStepUpadte = parseInt($('.edit-suggest').attr('id').split('-')[1]);
                    $('.type-search li[data-step="'+countStepUpadte+'"] span').text(txt);
                }
                
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
                
                countStep = boxId;
                for( var i = lenghtSuggest; i >= 0 ; i-- ) {
                    if( i > boxId ) {
                        $('.type-search li').eq(i-1).remove();
                    }
                }
                objEvent.resizeWidthInput();
                objEvent.open(countStep, 1);
                //objEvent.checkCounter();
            });
        },
        checkCounter: function() {
            lenghtSuggest = $('.type-search ul li').length;
            if( lenghtStep === lenghtSuggest ) {
                return;
            }else if( countStep < lenghtStep ){
                countStep += 1;
            }else {
                return;
            }
        },
        reset: function() {
            countStep = 1;
            lenghtSuggest = 0;
            $('.type-search ul').hide().find('li').remove();
            objEvent.resizeWidthInput();
        }
    };

    $(document).on('keyup focus','.type-search input:not(".no-suggest")', function(e) {
        e.preventDefault();
        var _this = $(this);
        if( countCurrent > 0 ) {
            countStep = countCurrent;
        }
        if( countStep <= $('.search-wrap').length && lenghtSuggest != lenghtStep ) {
            objEvent.open(countStep);
        }

        if( _this.val() != '' ) {
            setTimeout(function() {//timeout so voi thoi gian effect show suggest
                $('.active').find('.suggest-search-text').slideDown('fast');
                setTimeout(function() {//timeout demo loading bang ajax
                    $('.active .suggest-search-text .loading-suggest').hide();
                    $('.active .suggest-search-text ul').show();
                },500);
            },35);
        }else {
            if( $('#step-'+countStep+' .search-item > ul').length <= 0) {
                $('#step-'+countStep).addClass('hidden-effect');
                setTimeout(function() {
                    $('#step-'+countStep).removeClass('active');
                }, 30);
            }
            $('.suggest-search-text').hide();
        }
    });

    
    objEvent.btnclose();
    objEvent.selectItem();
    objEvent.removeSuggest();
    objEvent.reOpenBySuggest();

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

        _this.closest('.wrap-img').addClass('active');
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

    $('.item-infor > a').on('click',function() {
        var _this = $(this),
            idShowBox = _this.attr('href'),
            $parentLink = _this.parent(),
            tempItem;
        
        $('.item-infor > a').removeClass('active');
        $('a[data-active-first]').removeClass('active');
        $parentLink.find('>a').addClass('active');

        if( $parentLink.hasClass('has-sub') ) {
            tempItem = $parentLink;
            $('.has-sub .show-infor').slideUp('fast');
            $parentLink.find('>.show-infor').slideDown('fast');
            
            //tab phim 3D
            if( $(idShowBox).find('.phim3d').length ) {
                var srcVideo = $parentLink.find('a[data-active-first]').attr('rel');
                $(idShowBox).find('.phim3d').attr('src', srcVideo);

                $parentLink.find('.video-item a').on('click',function() {
                    var srcVideo = $(this).attr('rel');
                    $parentLink.find('.video-item a').removeClass('active');
                    $(this).addClass('active');
                    $(idShowBox).find('.phim3d').attr('src', srcVideo);
                    return false;
                });
            }
            //end tab phim 3D

            $parentLink.find('a[data-active-first]:last-child').trigger('click');
            $parentLink.find('a[data-active-first]').addClass('active');
        }

        if( !$(idShowBox).hasClass('item-tab') ) {
            $('.item-detail').hide();
        }
        $(idShowBox).css({
            display: 'block',
            visibility: 'hidden'
        });
        $(idShowBox).css('visibility','visible').hide().fadeIn();
        
        return false;
    });

    /*$('.item-infor a[data-srcmb]').on('click', function() {
        var _this = $(this);
        if( _this.data('srcmb')) {
            $('.img-alone').remove();
            $('.item-tab').hide();
            var srcImg = _this.data('srcmb'),
                $imgAdd = $('<img class="img-alone" src="'+srcImg+'" alt="" />'),
                idBox = _this.attr('href');
            $(idBox).append($imgAdd).fadeIn();
        }

        return false;
    });*/
    //end page du-an

    //start scroll fixed header
    var secondaryNav = $('.cd-secondary-nav'),
        secondaryNavTopPosition = secondaryNav.length > 0 ? secondaryNav.offset().top : 0,
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

//start run slide home page
/*function runSlideUpDown() {
    insertBeforeAnimate($('.slideshow li:last'), $('.slideshow li:first'));
    $( ".slideshow li" ).each(function( index ) {
        if(index < 5){
            $( this ).show();
        }else{
            $( this ).hide();
        }
    });
}
function insertBeforeAnimate($itemLast, $itemFirst) {
    $itemLast.css({
        opacity: 0,
        display: 'none'
    });
    $itemLast.insertBefore($itemFirst);
    $itemLast.slideDown('fast').animate({
        opacity: 1
    },500);
}*/
//end run slide home page