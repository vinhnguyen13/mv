var arrJSONReturn = {
    objTT: [],
    objLoai: [],
    objTTuc: []
};
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
    
    $.getJSON(url_tt, function(result){
        arrJSONReturn.objTT = result;
        for( var i = 0; i < result.length; i++ ) {
            var $itemTinhThanh = $('<li data-id-tt='+result[i].id+' data-active='+result[i].status_active+'><a href="#valTT">'+result[i].tinh_thanh+'</a></li>');
            $('.list-tinh-thanh').append($itemTinhThanh);
        }
    });

    $.getJSON(url_loaibds, function(result){
        arrJSONReturn.objLoai = result;
        for( var i = 0; i < result.length; i++ ) {
            var $item = $('<li data-id-loai='+result[i].id+' data-active='+result[i].status_active+'><a href="#valLoai">'+result[i].ten_loai+'</a></li>');
            $('.list-loai-bds').append($item);   
        }
    });

    $.getJSON(url_ttuc, function(result){
        arrJSONReturn.objTTuc = result;
        for( var i = 0; i < result.length; i++ ) {
            var $item = $('<li data-id-ttuc='+result[i].id+' data-active='+result[i].status_active+'><a href="#valTTuc">'+result[i].ten_tt+'</a></li>');
            $('.list-loai-tt').append($item);   
        }
    });

    var txtResetDropdown = 'Loại...',
    	flagEnd = false,
    	$textSearch = $('#search-kind .form-group input');

    var $wrapListSuggest = $('.type-search ul'),
        countStep = 1,
        countCurrent = 0,
        lenghtStep = $('.search-wrap').length,
        lenghtSuggest = 0,
        flagOpenSugget = false;

    if( $('.search-select.active a').hasClass('no-suggest') ) {
        $textSearch.addClass('no-suggest');
    }

    var txtPlaceholder = $('.search-select.active a').data('placeholder');
    $textSearch.attr('placeholder', txtPlaceholder);

    $('.search-select a').on('click', function() {
    	var _this = $(this),
            txtPlaceholder = _this.data('placeholder');

        objEvent.reset();
        $textSearch.val('');

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

            if( countStep !=4 && !flagEnd ) $textSearch.blur();
        },
        close: function() {
            $('.search-wrap').removeClass('edit-suggest');
            $('.search-wrap').removeClass('active');
            setTimeout(function() {
                $('.search-wrap').addClass('hidden-effect');
            }, 300);
            $('.type-search li').removeClass('active');
            if( countStep < lenghtStep ) {
                flagOpenSugget = false;
                //objEvent.checkCounter();
            }
        },
        btnclose: function() {
            $('.btn-close-search').on('click',function(e) {
                e.preventDefault();

                $('.type-search li').removeClass('active');

                $(this).closest('.search-wrap').removeClass('active');
                objEvent.close();

                return false;
            });
        },
        selectItem: function() {
            $(document).on('click', '.search-item > ul a', function(e) {
                e.preventDefault();
                var _this = $(this),
                    txt = _this.text(),
                    $itemSuggest = $('<li data-step="'+countStep+'" data-step-show='+_this.attr('href')+'><i>x</i><span></span></li>'),
                    itemId = '';

                if( _this.parent().data('idTt') != undefined ) {
                    $('#valTT').val(_this.parent().data('idTt'));
                    itemId = _this.parent().data('idTt');
                    for( var i = 0; i < arrJSONReturn.objTT.length; i++ ) {
                        if( arrJSONReturn.objTT[i].id == _this.parent().data('idTt') ) {
                            $('.list-quan-huyen').html('');
                            for( var j = 0; j < arrJSONReturn.objTT[i].quan_huyen.length; j++ ) {
                                var $item = $('<li data-id-qh='+arrJSONReturn.objTT[i].quan_huyen[j].id+'><a href="#valQh">'+arrJSONReturn.objTT[i].quan_huyen[j].ten_quan_huyen+'</a></li>');
                                $('.list-quan-huyen').append($item);
                            }
                        }
                    }
                }

                if( _this.parent().data('idQh') != undefined ) {
                    $('#valQh').val(_this.parent().data('idQh'));
                    itemId = _this.parent().data('idQh');
                }else if( _this.parent().data('idLoai') != undefined ) {
                    $('#valLoai').val(_this.parent().data('idLoai'));
                    itemId = _this.parent().data('idLoai');
                }else if( _this.parent().data('idTtuc') != undefined ) {
                    $('#valTTuc').val(_this.parent().data('idTtuc'));
                    itemId = _this.parent().data('idTtuc');
                }

                $itemSuggest.attr('data-item-id',itemId);

                if( !_this.closest('.search-wrap').hasClass('edit-suggest') && !flagOpenSugget ) {
                    if( _this.closest('.search-wrap').data('stepBox') == 'fixed' ) {
                        $wrapListSuggest.html('');
                    }
                    $itemSuggest.find('span').text(txt);
                    $wrapListSuggest.append($itemSuggest);

                    objEvent.checkCounter();
                    var txtStep = countStep == 5 ? 'Tìm kiếm...' : $('#step-'+countStep).data('txtStep');

                    $('.type-search input').attr('placeholder', txtStep);

                }else {
                    countStepUpadte = parseInt($('.edit-suggest').attr('id').split('-')[1]);
                    $('.type-search li[data-step="'+countStepUpadte+'"] span').text(txt);
                    flagOpenSugget = false;
                }
                
                objEvent.close();
                
                $wrapListSuggest.show();

                objEvent.resizeWidthInput();

                return false;
            });
        },
        removeSuggest: function() {
            $(document).on('click', '.type-search li i',function(e) {
                e.preventDefault();
                var _this = $(this),
                    $parentList = _this.parent(),
                    getStep = $parentList.index(),
                    count = 0;

                for( var i = lenghtSuggest-1; i >= getStep ; i-- ) {
                    $($('.type-search li').eq(i).data('stepShow')).val('');
                    $('.type-search li').eq(i).remove();
                }

                objEvent.close();
                countStep = countStep === 5 ? 5 : getStep;
                objEvent.resizeWidthInput();
                objEvent.checkCounter();
                $textSearch.val('');
                if( !flagOpenSugget ) {
                    var txtStep = $('#step-'+countStep).data('txtStep');
                    $('.type-search input').attr('placeholder', txtStep);
                }
                if( flagEnd && countStep == 5 ) flagEnd = false;
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
                $('.type-search li').removeClass('active');
                _this.parent().addClass('active');
                
                $textSearch.val('');
                for( var i = lenghtSuggest; i >= 0 ; i-- ) {
                    if( i > boxId ) {
                        $('.type-search li').eq(i-1).remove();
                    }
                }
                objEvent.resizeWidthInput();
                objEvent.open(boxId, 1);
                if( !flagOpenSugget && boxId < 5 ) {
                    var txtStep = $('#step-'+(boxId+1)).data('txtStep');
                    $('.type-search input').attr('placeholder', txtStep);
                    countStep = boxId + 1;
                }
                flagOpenSugget = true;
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
                flagEnd = true;
                return;
            }
        },
        reset: function() {
            countStep = 1;
            lenghtSuggest = 0;
            $('.type-search ul').hide().find('li').remove();
            objEvent.resizeWidthInput();
            flagEnd = false;
            $('.getValSuggest').val('');
        }
    };

    $(document).on('click keyup','.type-search input:not(".no-suggest")', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var _this = $(this);
        if( countCurrent > 0 ) {
            countStep = countCurrent;
        }
        if( countStep <= $('.search-wrap').length && lenghtSuggest != lenghtStep && !flagEnd && !flagOpenSugget ) {
            objEvent.open(countStep);
        }

        if( flagOpenSugget ) {
            $textSearch.blur();
        }

        if( _this.val() != '' ) {
            var iTem = '.active';
            stepSuggestText = 4;
            objEvent.open(stepSuggestText);
            setTimeout(function() {//timeout so voi thoi gian effect show suggest
                $(iTem).find('.suggest-search-text').slideDown('fast');
                setTimeout(function() {//timeout demo loading bang ajax
                    $(iTem+' .suggest-search-text .loading-suggest').hide();
                    $(iTem+' .suggest-search-text ul').show();
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

    //start click outside
    $(document).on('click', function(e) {
        var container = $(".outsideevent");
        if (!container.is(e.target) && container.has(e.target).length === 0){
            $('.btn-close-search').trigger('click');
        }
    });
    //end click outside

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
        /*if( valShow > 0 ) {
            secondaryNav.addClass('is-fixed');
            setTimeout(function() {
                secondaryNav.addClass('animate-children');
            }, 50);
        } else {
            secondaryNav.removeClass('is-fixed');
            setTimeout(function() {
                secondaryNav.removeClass('animate-children');
            }, 50);
        }*/
    });
    //end scroll fixed header

    //start home page search
    //animateSearch();
    //end home page search


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