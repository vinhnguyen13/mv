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
    	$dropdown.removeClass('dropdown-menu')
    	$(getId).addClass('dropdown-menu');
    	_this.parent().addClass('active');
    	_this.hasClass('type-text') ? ($('#search-kind>.dropdown').hide(), $('.box-search-header').addClass('resize-width')) : ($('#search-kind>.dropdown').show(),$('.box-search-header').removeClass('resize-width'));

		return false;
    });


    $('#search-kind ul a').on('click', function(){
    	var _this = $(this),
    		valueID = _this.data('valueId'),
    		txt = _this.data('valueText');
    	$txtDropdownSelected.html(txt);
        $('#search-kind .dropdown').removeClass('open');
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