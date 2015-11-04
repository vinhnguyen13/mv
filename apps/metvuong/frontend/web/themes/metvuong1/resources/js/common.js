$(document).ready(function() {
	
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
        var _this = $(this),
            arrPic = _this.data('imgsrc'),
            $wrapSlide = $('<div id="slideTDXD" class="owl-carousel"></div>'),
            $thumSlide = $('<div id="slideTDXD-thum" class="owl-carousel thumnail-list"></div>');
        for(var i = 0; i < arrPic.length; i++) {
            var $itemWrap = $('<div class="item bgcover img-big-duan" style="background-image:url('+arrPic[i]+')"></div>'),
                $itemThum = $('<div class="item bgcover img-big-duan" style="background-image:url('+arrPic[i]+')"></div>');
            $wrapSlide.append($itemWrap);
            $thumSlide.append($itemThum);
        }
        $('#item-tdxd').append($wrapSlide).append($thumSlide);
        runSlideDuAn('#slideTDXD','#slideTDXD-thum');
    });
    $('.item-infor > a').on('click',function() {
        var _this = $(this);
        $('.show-infor').slideUp('fast');
        $('.item-infor').removeClass('active');
        _this.parent().addClass('active');
        if( _this.parent().find('>.show-infor').length > 0 ) {
            var hItem = _this.parent().find('>.show-infor').outerHeight();
            _this.parent().find('>.show-infor').slideDown('fast');
        }
        var idShowBox = _this.attr('href');
        $('.item-detail').hide();
        $(idShowBox).css({
            display: 'block',
            visibility: 'hidden'
        });
        $(idShowBox).css('visibility','visible').hide().fadeIn();

        return false;
    });
    //end page du-an

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
});

function l(x){console.log(x);}