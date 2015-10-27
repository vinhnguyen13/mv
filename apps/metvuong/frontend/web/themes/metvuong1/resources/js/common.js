$(document).ready(function() {
	
	/**
	* Push right instantiation and action.
	*/
	var pushRight = new Menu({
		wrapper: '.o-wrapper',
		type: 'push-right',
		menuOpenerClass: '.c-button',
		maskId: '#c-mask'
	});

	var pushRightBtn = document.querySelector('#slide-menu-right');

	pushRightBtn.addEventListener('click', function(e) {
		e.preventDefault;
		pushRight.open();
	});

	var btnScrollTop = $('<div id="topcontrol" title="Lên đầu trang"><img src="images/scrolltop.png" style="width:40px; height:40px"></div>')
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

    $('.search-select a').on('click', function() {
    	var _this = $(this);
    	$('.search-select').removeClass('active');
    	_this.parent().addClass('active');
    	_this.hasClass('type-text') ? ($('#search-kind>.dropdown').hide(), $('.box-search-header').addClass('resize-width')) : ($('#search-kind>.dropdown').show(),$('.box-search-header').removeClass('resize-width'));
    	
    	return false;
    });

    $('#search-kind .dropdown-menu a').on('click', function() {
    	var _this = $(this),
    		valueID = _this.data('valueId'),
    		txt = _this.data('valueText');
    	$('#search-kind .txt-selected').html(txt);
    });
	
});

function l(x){console.log(x);}