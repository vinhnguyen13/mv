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

    $('.menu-detail-duan li').on('click',function() {
        //$('.collapse').collapse();
        if($(this).hasClass('submenu')) {
            $(this).toggleClass('active');
        }
    });

    var secondaryNav = $('.cd-secondary-nav'),
        secondaryNavTopPosition = secondaryNav.offset().top,
        contentSections = $('.cd-section'),
        hFirstNav = secondaryNav.outerHeight(),
        valShow;

    $(window).on('scroll', function(){
        valShow = $(window).scrollTop() - hFirstNav
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