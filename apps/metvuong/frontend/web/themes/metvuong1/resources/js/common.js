var arrJSONReturn = {
    objTT: [],
    objLoai: [],
    objTTuc: []
},objEvent;

$(document).ready(function() {

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
            $('body').addClass('modal-open').css('padding-right', '17px');
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

    //start home page search
    //animateSearch();
    //end home page search

    stepPost();

});

function animateSearch() {
    var hWindown = $(window).outerHeight(),
        leftLogo = $('.logo-home').position().left,
        leftInputSearch = $('.box-search-header').position().left;

    setTimeout(function() {
        $('.wrap-search-home .logo-home').addClass('ani-logo');
        $('.box-search-header').addClass('ani-search');
        setTimeout(function() {
            $('header').addClass('border-shadow');
        },250);
    },2000);
}

function l(x){console.log(x);}

function getActiveSuggert(objJson) {
    var $wrapListSuggest = $('.type-search ul');
    if( objJson.length > 0 ) {
        $wrapListSuggest.show();    
        for( var i = 0; i < objJson.length; i++ ) {
            var $itemSuggest = $('<li data-step="'+objJson[i].idItem+'" data-step-id="'+objJson[i].stepId+'" data-step-show='+objJson[i].stepShow+'><i>x</i><span>'+objJson[i].name_item+'</span></li>');
            $wrapListSuggest.append($itemSuggest);
        }
        //objEvent.resizeWidthInput();
        //objEvent.updateSuggert( 3, $wrapListSuggest.find('li').length );
    }
}

function stepPost () {
    //jQuery time
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches

    $(".next").click(function(){
        if(animating) return false;
        animating = true;
        
        current_fs = $(this).parent();
        next_fs = $(this).parent().next();
        
        //activate next step on progressbar using the index of next_fs
        $("#progressbar li").eq($(".fieldset").index(next_fs)).addClass("active");
        
        //show the next fieldset
        next_fs.show(); 
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50)+"%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'transform': 'scale('+scale+')'});
                next_fs.css({'left': left, 'opacity': opacity});
                $('body').addClass('overHidden');
            }, 
            duration: 800, 
            complete: function(){
                current_fs.hide();
                animating = false;
                $('body').removeClass('overHidden');
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    $(".previous").click(function(){
        if(animating) return false;
        animating = true;
        
        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();
        
        //de-activate current step on progressbar
        $("#progressbar li").eq($(".fieldset").index(current_fs)).removeClass("active");
        
        //show the previous fieldset
        previous_fs.show(); 
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1-now) * 50)+"%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
                $('body').addClass('overHidden');
            }, 
            duration: 800, 
            complete: function(){
                current_fs.hide();
                animating = false;
                $('body').removeClass('overHidden');
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    $(".submit").click(function(){
        return false;
    });
}