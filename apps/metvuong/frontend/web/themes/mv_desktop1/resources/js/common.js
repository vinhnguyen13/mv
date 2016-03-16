$(document).ready(function() {

    $(window).on('resize', checkMobile);

    $(".rippler").rippler({
        effectClass      :  'rippler-effect',
        effectSize      :  0,      // Default size (width & height)
        addElement      :  'div',   // e.g. 'svg'(feature)
        duration        :  400
    });

    $('.hFullScreen').css({
        height: $('#hScreen').outerHeight() - 40
    });
    
    $('.page-home').css('visibility','visible');

    $('.wrap-lazy img').lazyload({
        threshold : 200
    });

    $("#menu-header").mmenu({
        navbar: {
            add: false
        },
        extensions: ["pageshadow"]
    },{
        // configuration
        classNames: {
            fixedElements: {
                fixed: "header"
            }
        }
    });

    $("#settings-user").mmenu({
        navbar: {
            add: false
        },
        extensions: ["pageshadow"],
        offCanvas: {
            position: "right"
        }
    });
    
    var API_MENU = $("#menu-header").data("mmenu");
    var API_USER = $("#settings-user").data("mmenu");

    $("#hide-menu").click(function() {
        API_MENU.close();
    });
    $("#hide-settings").click(function() {
        API_USER.close();
    });

    $(document).on('click','.link-login', function() {$('a[href=#tab-signin]').trigger('click');});
    $(document).on('click','.link-regis', function() {$('a[href=#tab-signup]').trigger('click');});

    var hWin = screen.height,
        flagScroll = false,
        elScroll = $('<a href="#" id="scroll-top"><span class="icon arrowUp"></span></a>');
    $(document).on('scroll', function () {
        var _this = $(this),
            val = _this.scrollTop();

        if ( val >= hWin ) {
            $('body').append(elScroll);
            flagScroll = true;
        }else {
            $('body').remove('#scroll-top');
            flagScroll = false;
        }

        if ( flagScroll ) {
            $('#scroll-top').fadeIn();
            return;
        }else {
            $('#scroll-top').hide();
        }
    });

    var searchForm = $('#search-form');
    $('#search').keyup(function() {
    	var self = $(this);
    	var val = self.val().trim();
    	var url = searchForm.attr('action');
    	var ss = $('.suggest-search');
    	
    	if(val.length > 2) {
    		if($.data(this, 'v') != val) {
    			$.data(this, 'v', val);
            	
    			if($.data(this, 'ajax')) {
    				$.data(this, 'ajax').abort();
    			}
    			
    			$.data(this, 'ajax', $.get(url, searchForm.serialize(), function(response){
            		var cs = $('.content-suggest ul');
            		
            		if(response.length > 0) {
            			ss.removeClass('hide');
            			
            			var html = '';
            			for(var i in response) {
            				html += '<li><a href="' + response[i].url + '">' + response[i].full_name + ' <span>(' + response[i].total + ')</span></a></li>';
                      	}
            			
            			$('.content-suggest ul').html(html);
            		} else {
            			ss.addClass('hide');
            		}
            	}));
    		}
    	} else {
    		$.data(this, 'v', '');
    		ss.addClass('hide');
    	}
    });

    $(window).resize(function () {
        $('.hFullScreen').css({
            height: $('#hScreen').outerHeight() - 40
        });
    });
});

function l(x){console.log(x);}

$.fn.radio = function (options) {

    return this.each(function() {
        var defaults = {
            done: function(){}
        },
        sc = {},
        el = $(this);

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        el.on('click', toggleCheck);

        if ( el.find('input').attr('checked') ) {
            el.addClass('active');
        }

        function toggleCheck (e) {
            e.preventDefault();
            var _this = $(this),
                nameGroup = _this.find('input').attr('name');

            $('input[name="'+nameGroup+'"]').prop("checked", false);
            $('input[name="'+nameGroup+'"]').closest('.frm-radio').removeClass('active');

            if ( _this.find('input[type=radio]').prop("checked") ) {
                _this.find('input[type=radio]').prop("checked", false);
                checkedItem(_this, false);
            }else {
                _this.parent().find('input[type=radio]').prop("checked", true);
                checkedItem(_this, true);
            }

            sc.settings.done(_this.find('input'));
        }
        function checkedItem (item, flagChecked) {
            if ( flagChecked ) {
                item.addClass('active');
            }else {
                item.removeClass('active');
            }
        }

    });
}

$.fn.checkbox_ui = function (options) {

    return this.each(function() {
        var defaults = {
            done: function(){}
        },
        sc = {},
        el = $(this);

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        function toggleCheck (e) {
            e.preventDefault();
            var _this = $(this);
            if ( _this.parent().find('input[type=checkbox]').prop("checked") ) {
                _this.parent().find('input[type=checkbox]').prop("checked", false);
                checkedItem(_this, false);
            }else {
                _this.parent().find('input[type=checkbox]').prop("checked", true);
                checkedItem(_this, true);
            }
        }

        function checkedItem (item, flagChecked) {
            if ( flagChecked ) {
                item.parent().find('em').removeClass('fa-square-o').addClass('fa-check-square-o');
            }else {
                item.parent().find('em').removeClass('fa-check-square-o').addClass('fa-square-o');
            }
        }

    });
}

$.fn.dropdown = function (options) {

    return this.each(function() {
        var defaults = {
            styleShow: 1, // 1. relative, 0. absolute
            txtAdd: false,
            ajaxSubmit: function () {},
            funCallBack: function () {},
            renderAddEvent: function () {}
        },
        sc = {},
        el = $(this),
        wrapBoxEffect = $('<div class="dropdown-up-style hide"></div>'),
        itemList = el.parent().find('.item-dropdown li a');

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        el.find('.val-selected').on('click', toggleView);

        if ( sc.settings.styleShow == 0 ) {
            el.find('.item-dropdown').addClass('wrap-effect');
            wrapBoxEffect.insertBefore(el.find('.item-dropdown'));
            wrapBoxEffect.append(el.find('.item-dropdown'));
        }

        showSortLoad();
        outsiteClick();

        function showSortLoad () {
            if ( $(el.find('input[type=hidden]')).val() != '' && $(el.find('input[type=hidden]')).length > 0 ) {
                valGet = $(el.find('input[type=hidden]')).val();
                if ( $(el.find('input[type=hidden]')).is('#tinh-thanh') ) {
                    sc.settings.funCallBack($(el.find('input[type=hidden]')));
                }
                itemList.each(function () {
                    var dataGet = $(this).data('value');
                    if ( dataGet == valGet ) {
                        el.find('.val-selected .selected').text($(this).text());
                    }
                });
            }
        }

        function toggleView (e) {
            e.preventDefault();
            var _this = $(this),
                itemList = el.parent().find('.item-dropdown li a');

            if ( sc.settings.styleShow ) { // relative
                if ( _this.hasClass('active') ) {
                    _this.removeClass('active');
                    _this.parent().find('.item-dropdown').velocity("slideUp", { duration: 350 });
                }else {
                    _this.addClass('active');
                    _this.parent().find('.item-dropdown').velocity("slideDown", { duration: 350 });
                    selectItem(itemList, _this);
                }
            }else { // absolute
                if ( _this.hasClass('active') ) {
                    _this.removeClass('active');
                    el.find('.dropdown-up-style').removeClass('active');
                    setTimeout(function() {
                        el.find('.dropdown-up-style').addClass('hide');
                    },250);
                }else {
                    _this.addClass('active');
                    el.find('.dropdown-up-style').removeClass('hide');
                    setTimeout(function() {
                        el.find('.dropdown-up-style').addClass('active');
                    },50);
                    selectItem(itemList, _this);
                }
            }
        }

        function selectItem (item, itemValueClick) {
            item.unbind('click');
            item.on('click', function () {
                if ( $(this).hasClass('disable') ) return;
                var txt = $(this).text(),
                dataValue = $(this).data('value') != undefined ? $(this).data('value') : txt;

                $(el.find('input[type=hidden]')).val(dataValue);

                sc.settings.ajaxSubmit();

                if (sc.settings.styleShow) {
                    itemValueClick.parent().find('.item-dropdown').hide();
                }else {
                    el.find('.dropdown-up-style').removeClass('active');
                    setTimeout(function() {
                        el.find('.dropdown-up-style').addClass('hide');
                    },250);
                }
                
                itemValueClick.removeClass('active');
                if ( sc.settings.txtAdd ) {
                    var getTxtAdd = el.find('.val-selected').data('textAdd') == undefined ? '' : el.find('.val-selected').data('textAdd');
                    txt += ' '+getTxtAdd;
                }
                
                el.find('.val-selected .selected').text(txt);

                sc.settings.funCallBack($(this));

                return false;
            });
        }

        function outsiteClick () {
            $(document).on('click', function (e) {
                var container = el;
                if ( !container.is(e.target) && container.has(e.target).length === 0 ){
                    el.find('.val-selected').removeClass('active');
                    if ( sc.settings.styleShow ) {
                        el.find('.val-selected').parent().find('.item-dropdown').velocity("slideUp", { duration: 0 });
                    }else {
                        el.find('.dropdown-up-style').removeClass('active');
                        setTimeout(function() {
                            el.find('.dropdown-up-style').addClass('hide');
                        },250);
                    }
                }
            });
        }
    });
}

$.fn.toggleShowMobi = function (options) {

    return this.each(function() {
        var defaults = {
            //styleShow: 1, // 1 relative and 0 absolute
            styleEffect: 'slide', // slideDownUp -> absolute, slideDown and slideUp = slide -> relative
            btnEvent: '.advande-search-btn',
            itemToggle: '.advande-search'
        },
        sc = {},
        el = $(this),
        wrapBoxEffect = $('<div class="dropdown-up-style hide"></div>');

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        if ( sc.settings.styleEffect == 'slideDownUp' ) {
            el.find(sc.settings.itemToggle).addClass('wrap-effect');
            wrapBoxEffect.insertBefore(el.find(sc.settings.itemToggle));
            wrapBoxEffect.append(el.find(sc.settings.itemToggle));
        }

        el.find(sc.settings.btnEvent).on('click', toggleShow);

        function toggleShow (e) {
            var _this = $(this);

            if ( _this.hasClass('active') && _this.data('flag') ) {
                _this.data('flag', false);
            }

            if ( _this.hasClass('active') ) {
                _this.removeClass('active');
                if ( sc.settings.styleEffect == 'slide' ) {
                    el.find(sc.settings.itemToggle).velocity("slideUp", { duration: 350 });
                }else if ( sc.settings.styleEffect == 'slideDownUp' ) {
                    el.find('.dropdown-up-style').removeClass('active');
                    setTimeout(function() {
                        el.find('.dropdown-up-style').addClass('hide');
                    },250);
                }
            }else {
                _this.addClass('active');
                if ( sc.settings.styleEffect == 'slide' ) {
                    el.find(sc.settings.itemToggle).velocity("slideDown", { duration: 350 });
                }else if ( sc.settings.styleEffect == 'slideDownUp' ) {
                    el.find('.dropdown-up-style').removeClass('hide');
                    setTimeout(function() {
                        el.find('.dropdown-up-style').addClass('active');
                    },50);
                }
                outsiteClick();
            }
        }

        function outsiteClick () {
            $(document).on('click', function (e) {
                var container = el;
                if ( !container.is(e.target) && container.has(e.target).length === 0 ){
                    el.find(sc.settings.btnEvent).removeClass('active');
                    if ( sc.settings.styleEffect == 'slide' ) {
                        el.find(sc.settings.itemToggle).velocity("slideUp", { duration: 0 });
                        el.find(sc.settings.btnEvent).data('flag',true);
                    }else if ( sc.settings.styleEffect == 'slideDownUp' ) {
                        el.find('.dropdown-up-style').removeClass('active');
                        setTimeout(function() {
                            el.find('.dropdown-up-style').addClass('hide');
                        },250);
                    }
                }
            });
        }
    });
}

$.fn.price_dt = function (options) {

    var prices = {"thue": {"-1": "Giá bất kỳ","2000000" : "2 triệu","4000000" : "4 triệu","6000000" : "6 triệu","8000000" : "8 triệu","10000000": "10 triệu","12000000": "12 triệu","14000000": "14 triệu","16000000": "16 triệu","18000000": "18 triệu","20000000": "20 triệu",},"mua": {"-1": "Giá bất kỳ","2000000000" : "2 tỷ","4000000000" : "4 tỷ","6000000000" : "6 tỷ","8000000000" : "8 tỷ","10000000000": "10 tỷ","12000000000": "12 tỷ","14000000000": "14 tỷ","16000000000": "16 tỷ","18000000000": "18 tỷ","20000000000": "20 tỷ"}};
    var dt = {"-1": "Bất kỳ", "10": "10m2", "20": "20m2", "30": "30m2", "40": "40m2", "50": "50m2", "60": "60m2", "70": "70m2", "80": "80m2", "90": "90m2", "100": "100m2"};
    
    return this.each(function() {
        var defaults = {
            inputMin: '.input-min',
            inputMax: '.input-max',
            hinhthuc: 'mua', // co 2 hinh thuc, (thue, cho thue) và (bán,)
            numRenderMax: 11
        },
        sc = {},
        el = $(this);

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        el.toggleShowMobi({
            styleEffect: 'slideDownUp',
            btnEvent: '.val-selected',
            itemToggle: '.wrap-min-max'
        });

        if ( el.data('itemMinmax') == 'prices' ) {
            for ( var i in prices[sc.settings.hinhthuc] ) {
                if ( parseInt(i) < 0 ) {
                    continue;
                }
                var item = $('<li data-number="'+i+'"><a class="option">'+prices[sc.settings.hinhthuc][i]+'</a></li>');
                el.find('.wrap-minmax').append(item);
            }
        }else if ( el.data('itemMinmax') == 'area' ) {
            for ( var i in dt ) {
                if ( parseInt(i) < 0 ) {
                    continue;
                }
                var item = $('<li data-number="'+i+'"><a class="option">'+dt[i]+'</a></li>');
                el.find('.wrap-minmax').append(item);
            }
        }

        el.find('ul[data-wrap-minmax=min-val]').show();

        el.find('.filter-minmax li a').on('click', seletedVal);

        el.find('.min-max').on('click', clickInput);

        function seletedVal (e) {
            var _this = $(this),
                numberVal = _this.parent().data('number'),
                txt = _this.text(),
                minmax = _this.closest('.wrap-minmax').data('wrapMinmax'),
                valMin = '', valMax = '';

            el.find('.'+minmax).html(txt);
            el.find('.'+minmax).data('value',numberVal);

            if ( minmax == 'min-val' ) {
                toggleMinMax(minmax);
                renderMax(numberVal);
                if ( el.find('.max-val').data('value') != '' ) {
                    el.find('.max-val').html('');
                    el.find('.max-val').data('value','');
                }
                
                valMin = numberVal;

            }else {// hidden khi chon xong gia tri max
                el.find('.val-selected').trigger('click');
                valMax = numberVal;
            }

            if ( el.data('itemMinmax') == 'prices' ) {
                minmax == 'min-val' ? $('#priceMin').val(valMin) : $('#priceMax').val(valMax);
            }else if ( el.data('itemMinmax') == 'area' ) {
                minmax == 'min-val' ? $('#dtMin').val(valMin) : $('#dtMax').val(valMax);
            }

            renderTxtShow(minmax, txt);
            
            return false;
        }

        function clickInput (e) {
            var _this = $(this);
            if ( _this.hasClass('min-val') ) {
                toggleMinMax('max-val');
            }else {
                toggleMinMax('min-val');
            }
        }

        function renderMax (valMin) {
            var itemMax = el.find('ul[data-wrap-minmax=max-val]'), valMax = 0, valFirst, unitDT = '';
            itemMax.html('');
            valMax = valMin;
            if ( el.data('itemMinmax') == 'area' ) {
                unitDT = 'm2';
            }
            for ( var i = 0; i < sc.settings.numRenderMax; i++ ) {
                valMax += priceUnit(valMin);
                var item = $('<li data-number="'+valMax+'"><a class="option">'+formatPrice(''+valMax+'')+unitDT+'</a></li>');
                item.find('a').on('click', seletedVal);
                itemMax.append(item);
            }
        }

        function toggleMinMax (item) {
            el.find('.min-max').removeClass('active');
            el.find('.wrap-minmax').hide();
            if ( item == 'min-val' ) {
                el.find('ul[data-wrap-minmax=max-val]').show();
                el.find('.max-val').addClass('active');
            }else if ( item == 'max-val' ) {
                el.find('ul[data-wrap-minmax=min-val]').show();
                el.find('.min-val').addClass('active');
            }
        }

        function checkValMinMax () {
            if ( el.find('.min-val').data('value') == "" && el.find('.max-val').data('value') == "" ) {
                return 1;
            }else if ( el.find('.min-val').data('value') == "" ) {
                return 2;
            }else if ( el.find('.max-val').data('value') == "" ) {
                return 3;
            }
        }

        function renderTxtShow (minmax, txt) {
            var wrapTxt = el.find('.val-selected');

            if ( minmax == 'min-val' ) {
                wrapTxt.find('.wrap-min').html(txt);
            }else {
                wrapTxt.find('.wrap-max').html(txt);
            }

            wrapTxt.find('.tu').show();

            if ( checkValMinMax() == 1 ) {// min, max rong
                wrapTxt.find('.wrap-min, .trolen, .den, .wrap-max, .troxuong').hide();
            }else if ( checkValMinMax() == 2 ) {// min rong
                wrapTxt.find('.den, .wrap-min, .trolen').hide();
                wrapTxt.find('.troxuong, .wrap-max').show();
            }else if ( checkValMinMax() == 3 ) {// max rong
                wrapTxt.find('.troxuong, .den, .wrap-max').hide();
                wrapTxt.find('.trolen, .wrap-min').show();
            }else {
                wrapTxt.find('.trolen, .troxuong').hide();
                wrapTxt.find('.den, .wrap-min, .wrap-max').show();
            }
        }

        function priceUnit (num) {
            num = Math.abs(num);
            if (num >= 1000000000) {
                return 2000000000;
            } else if (num >= 1000000) {
                return 2000000;
            } else  if (num >= 1000) {
                return 2000;
            } else {
                return 10;
            }
        }

    });
}

$.fn.slideSection = function (options) {

    var countEl = 0;

    return this.each(function() {
        var defaults = {
            active: 0,
            pagi: '.step-link',
            navi: true,
            validateFrm: function () {},
            funCallBack: function () {}
        },
        sc = {},
        el = $(this),
        nextBack = $('.fixed-prev-next'),
        btnNext = nextBack.find('#next-screen'),
        btnBack = nextBack.find('#back-screen'),
        current = 0,
        lenItem = el.find('.section').length;

        countEl += 1;

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        btnNext.on('click', next);
        btnBack.on('click', back);
        $('[data-next-section=true]').on('click', next);
        $('[data-prev-section=true]').on('click', back);

        if ( countEl == 1 && sc.settings.navi ) {
            el.append(nextBack);
        }
        
        //el.find('.section').eq(sc.settings.active).removeClass('hide');
        el.find('.section').eq(sc.settings.active).velocity("fadeIn", { duration: 500 });

        hWrapSection(sc.settings.active);

        current = sc.settings.active;

        if ( current == 0 ) {
            btnBack.addClass('disable');
        }

        function next (e) {
            e.preventDefault();

            if ( $(this).hasClass('disable') ) return;

            if ( !sc.settings.validateFrm(current+1) ) return;

            el.find('.section').eq(current).find('.val-selected').removeClass('error');

            //el.find('.section').removeClass('back-in back-out');

            if ( current+1 < lenItem ) {
                var oldItem = current;

                /*el.find('.section').eq(oldItem).addClass('out').removeClass('in');

                setTimeout(function () {
                    el.find('.section').eq(oldItem).addClass('hide');
                },350);*/

                el.find('.section').hide();

                current = current+1;

                sc.settings.funCallBack(current);

                pagi(current);

                //el.find('.section').eq(current).addClass('in').removeClass('hide');

                el.find('.section').eq(current).velocity("fadeIn", { duration: 500 });

                hWrapSection(current);

                if ( current+1 == lenItem ) {
                    btnNext.addClass('disable');
                }

                btnBack.removeClass('disable');
            }
        }

        function back (e) {
            e.preventDefault();
            if ( $(this).hasClass('disable') ) return;

            //el.find('.section').removeClass('in out');

            var oldItem = current;

            el.find('.section').hide();

            /*el.find('.section').eq(oldItem).removeClass('back-in').addClass('back-out');

            setTimeout(function () {
                el.find('.section').eq(oldItem).addClass('hide');
            },350);*/

            current = current - 1;

            sc.settings.funCallBack(current);

            pagi(current);

            //el.find('.section').eq(current).addClass('back-in').removeClass('out hide');

            el.find('.section').eq(current).velocity("fadeIn", { duration: 500 });

            hWrapSection(current);
            
            if ( current == 0 ) {
                btnNext.removeClass('disable');
                btnBack.addClass('disable');
            }else {
                btnNext.removeClass('disable');
            }
        }

        function hWrapSection (current) {
            /*var hActive = el.find('.section').eq(current).outerHeight();
            el.css({
                height: hActive+120+'px'
            });*/
        }

        function pagi (current) {
            if ( current == 0 ) return;
            $(sc.settings.pagi).find('li').removeClass('active');
            $(sc.settings.pagi).find('li').eq(current-1).addClass('active');
        }

    });
}

$.fn.loading = function (options) {

    return this.each(function() {
        var defaults = {
            inside: false,
            full: true,
            done: false
        },
        sc = {},
        el = $(this),
        $loading = $('<div class="loading-proccess"><span></span></div>');

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        if ( sc.settings.done ) {
            el.find('.loading-proccess').fadeOut(300, function () {
                $(this).remove();
            });
            return;
        }

        if ( sc.settings.full ) {
            $loading.css({
                position: 'fixed',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                'z-index': 999,
                background: 'rgba(0, 0, 0, 0.51)'
            });
            el.append($loading);
            return;
        }else {
            $loading.css({
                position: 'absolute',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                'z-index': 999,
            });

            el.append($loading);

            return;
        }
    });
}

$.fn.popupMobi = function (options) {

    return this.each(function() {
        var defaults = {
            styleShow: "full", // "full" or "center"
            duration: 200,
            effectShow: "slideDownUp", // show/hide or slideDownUp
            btnClickShow: "",
            closeBtn: "",
            funCallBack: function () {}
        },
        sc = {},
        el = $(this);

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        $(sc.settings.btnClickShow).on('click', function (e) {
            e.preventDefault();
            var _this = $(this),
                popupItem = _this.attr('href');

            showPopup(_this, $(popupItem));
        });

        $(sc.settings.closeBtn).on('click', function (e) {
            e.preventDefault();
            hide(el);
        });

        $('body').append(el);

        if ( el.find('.title-popup').length == 0 ) {
            el.addClass('no-title');
        }

        if ( sc.settings.styleShow == "center" ) {
            el.addClass('center-popup');
        }else if ( sc.settings.styleShow == "full" ) {
            el.addClass('full-style');
        }

        if ( $(sc.settings.btnClickShow).length == 0 || sc.settings.btnClickShow == "" ) {
            showPopup("", el);
        }

        function showPopup (itemClick, popupItem) {
            $('body').addClass('popup-mobi');
            popupItem.removeClass('hide-popup');

            sc.settings.funCallBack(itemClick, popupItem);

            if ( sc.settings.effectShow == "slideDownUp" ) {
                if ( sc.settings.styleShow == "full" ) {
                    popupItem.css('top','100%');
                    setTimeout(function() {
                        popupItem.css({
                            "top": "0",
                            "transition": "top "+sc.settings.duration+"ms ease",
                            "-webkit-transition": "top "+sc.settings.duration+"ms ease",
                            "-moz-transition": "top "+sc.settings.duration+"ms ease",
                            "-ms-transition": "top "+sc.settings.duration+"ms ease"
                        });
                    },150);
                    return;
                }else {
                    setTimeout(function() {
                        popupItem.find('.wrap-popup').css({
                            "bottom": "50%",
                            "transition": "bottom "+sc.settings.duration+"ms ease",
                            "-webkit-transition": "bottom "+sc.settings.duration+"ms ease",
                            "-moz-transition": "bottom "+sc.settings.duration+"ms ease",
                            "-ms-transition": "bottom "+sc.settings.duration+"ms ease"
                        });
                    },150);
                }
            }else if ( sc.settings.effectShow == "show-hide" ) {

            }
        }

        function hide (popupItem) {
            $(sc.settings.closeBtn).unbind('click');
            if ( sc.settings.effectShow == "slideDownUp" ) {
                if ( sc.settings.styleShow == "full" ) {
                    var hPopup = popupItem.outerHeight();
                    popupItem.css({
                        "top": hPopup+"px"
                    });
                }else {
                    var hPopup = popupItem.find('.wrap-popup').outerHeight();
                    popupItem.find('.wrap-popup').css({
                        "bottom": "-"+hPopup+"px"
                    });
                }
                
                setTimeout(function() {
                    popupItem.addClass('hide-popup');
                },sc.settings.duration + 20);
            }else {
                popupItem.addClass('hide-popup');
            }
            
            $('body').removeClass('popup-mobi');
        }
    });
}

function checkMobile () {
    var wWindow = $(window).outerWidth();

    if ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || wWindow <= 985 ) {
        return true;
    }
    return false;
}