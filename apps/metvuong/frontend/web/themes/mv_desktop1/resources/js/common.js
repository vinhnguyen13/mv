$(window).on('load', function () {
    $('.popup-common').appendTo('body');
});

$(document).ready(function() {

    var getNumKey = parseInt($('.list-redire .num-gold a').data('keys'));
    $('.list-redire .num-gold .notifi').html(getNumKey);
    
    $(document).on('click', '.option-chat-box .val-selected', function (e) {
        $(this).closest('.box-dropdown').toggleClass('show-hide');
    });

    hideElOutSite('.option-chat-box .box-dropdown', function () {
        $('.option-chat-box .box-dropdown').removeClass('show-hide');
    });
    
    $(document).on('click', '.box-chat-footer .close-box', function (e) {
        e.preventDefault();
        $(this).closest('.item-box-chat').remove();
        if ( $('.item-box-chat').length == 0 ) {
            $('.wrap-items-chat').removeClass('toggle-show-hide');
        }
    });
    $(document).on('click', '.box-chat-footer .title-top', function () {
        $(this).closest('.item-box-chat').toggleClass('toggle-show-hide');
        $('body').toggleClass('body-chat');
    });

    if ( !checkMobile() ) {
        $('.collapse').on('show.bs.collapse hide.bs.collapse', function(e) {
            e.preventDefault();
        });
        $('[data-toggle="collapse"]').on('click', function(e) {
            e.preventDefault();
            $($(this).data('target')).toggleClass('in');
        });
    }

    $('.tabs-scroll li a').on('click', function (e) {
        e.preventDefault();
        var _this = $(this),
            idTab = _this.attr('href'),
            getOffset = $(idTab).offset().top - 75;

        $('.tabs-scroll li a').removeClass('active');
        _this.addClass('active');

        $('html, body').stop().animate({
            scrollTop: getOffset
        }, '500', 'swing');
    });
    function onScroll(){
        var scrollPos = $(document).scrollTop();
        $('.tabs-scroll li a').each(function () {
            var currLink = $(this);
            var refElement = $(currLink.attr("href"));
            try {
                if (refElement.position().top <= scrollPos && refElement.position().top + refElement.outerHeight() > scrollPos) {
                    $('.tabs-scroll li a').removeClass("active");
                    currLink.addClass("active");
                }
            }catch (err) {
                l(err);
            }
        });
    }

    $(document).on('show.bs.modal', '.modal', function () {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    $(document).on('hidden.bs.modal', '.modal', function () {
        $('.modal:visible').length && $(document.body).addClass('modal-open');
    });

    $('.tooltip-show').tooltip();

    $(".rippler").rippler({
        effectClass      :  'rippler-effect',
        effectSize      :  0,      // Default size (width & height)
        addElement      :  'div',   // e.g. 'svg'(feature)
        duration        :  400
    });

    if ( checkMobile() ) {
        $('.hFullScreen').css({
            height: $('#hScreen').outerHeight() - 40
        });
    }else {
        $('.hFullScreen').css({
            height: $('#hScreen').outerHeight() - 40 - 130
        });
    }
    
    $('.page-home').css('visibility','visible');

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

        onScroll();

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
    }).trigger('scroll');

    var searchForm = $('#search-form');
    $('#search').keyup(function() {
    	var self = $(this);
    	var val = self.val().trim();
    	var url = searchForm.attr('action');
    	var ss = $('.suggest-search');
    	
    	if(val) {
    		if($.data(this, 'v') != val) {
    			$.data(this, 'v', val);
            	
    			if($.data(this, 'ajax')) {
    				$.data(this, 'ajax').abort();
    			}
    			
    			$.data(this, 'ajax', $.get(url, searchForm.serialize(), function(response){
            		var cs = $('.content-suggest ul');
            		
            		if(response.address) {
            			ss.removeClass('hide');
            			$('.content-suggest ul').html('<li><a href="' + response.url + '">' + response.address + '</a>(' + lajax.t('ID') + ': MV' + val.replace('MV', '').replace('mv', '') + ')</li>');
            		} else if(response.length > 0) {
            			ss.removeClass('hide');
            			
            			var html = '';
            			
            			for(var i in response) {
            				html += '<li data-type="' + response[i].type + '" data-id="' + response[i].id + '"><span>' + response[i].full_name + '</span> <a href="' + response[i].url_sale + '?qs=1">' + lajax.t('Sale') + ' (' + response[i].total_sell + ')</a><a href="' + response[i].url_rent + '">' + lajax.t('Rent') + ' (' + response[i].total_rent + ')</a></li>';
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
    }).focus();
    $('.content-suggest').on('click', 'a', function(e){
    	var parentLi = $(this).closest('li');
    	var id = parentLi.data('id');
    	var val = parentLi.find('span').text();
    	var type = parentLi.data('type');
    	var searchHistory = getCookie('sh1');
		var listSearch = searchHistory ? JSON.parse(searchHistory) : [];
		var isExist = false;
		
		for(var i = 0; i < listSearch.length; i++) {
			var sli = listSearch[i];
			
			if(sli.i == id && sli.t == type) {
				isExist = true;
				break;
			}
		}
		
		if(isExist) {
			move(listSearch, i, 0);
		} else {
			if(listSearch.length > 4) {
				listSearch.pop();
			}
			
			var slug = $(this).attr('href').split('/')[2];
			
			listSearch.unshift({v: encodeURIComponent(val), i: id, t: type, s: slug});
		}
		
		setCookie('sh1', JSON.stringify(listSearch));
    });

    $('.suggest-search .content-suggest .btn-close').on('click', function (e) {
        e.preventDefault();
        $('.suggest-search').addClass('hide');
    });

    $(window).on('resize', function () {
        var hWindow = $(this).outerHeight(),
            hHeader = $('header').outerHeight();
        
        $('.chat-list, .chat-live').css({
            height: hWindow - hHeader - 65 +'px'
        });
    }).trigger('resize');
});

function hideElOutSite (el, callBackItem) {
    $(document).on('click', function (e) {
        var container = $(el);
        if ( !container.is(e.target) && container.has(e.target).length === 0 ) {
            callBackItem();
        }
    });
}

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
            $('input[name="'+nameGroup+'"]').closest('.radio-ui').removeClass('active');

            if ( _this.find('input[type=radio]').prop("checked") ) {
                _this.find('input[type=radio]').prop("checked", false);
                checkedItem(_this, false);
            }else {
                _this.find('input[type=radio]').prop("checked", true);
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

        if ( el.find('input[type=checkbox]').attr('checked') ) {
            el.addClass('active');
        }

        if ( el.find('input[type=checkbox]').attr('disabled') ) {
            el.addClass('disabled-rc');
        }

        el.on('click', toggleCheck);

        function toggleCheck (e) {
            e.preventDefault();
            var _this = $(this);
            if ( _this.hasClass('disabled-rc') ) return;
            if ( _this.find('input[type=checkbox]').prop("checked") ) {
                _this.find('input[type=checkbox]').prop("checked", false);
                checkedItem(_this, false);
            }else {
                _this.find('input[type=checkbox]').prop("checked", true);
                checkedItem(_this, true);
            }
            _this.find('input[type=checkbox]').trigger('change');

            sc.settings.done(_this.find('input[type=checkbox]')); // CALLBACK
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

$.fn.dropdown = function (options) {

    return this.each(function() {
        var defaults = {
            styleShow: 1, // 1. relative, 0. absolute
            txtAdd: false,
            selectedValue: true,
            ajaxSubmit: function () {},
            funCallBack: function () {},
            renderAddEvent: function () {},
            closeCallBack: function () {}
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
        
        function showSortLoad () {
            if ( $(el.find('input[type=hidden]')).val() != '' && $(el.find('input[type=hidden]')).length > 0 ) {
                var valGet = $(el.find('input[type=hidden]')).val();
                if ( $(el.find('input[type=hidden]')).is('#tinh-thanh') ) {
                    sc.settings.funCallBack($(el.find('input[type=hidden]')));
                }
                itemList.each(function () {
                    var dataGet = $(this).data('value');
                    if ( dataGet == valGet ) {
                        var txt = el.find('.val-selected').data('textAdd') != undefined ? $(this).text() +' '+ el.find('.val-selected').data('textAdd') : $(this).text();
                        el.find('.val-selected .selected').text(txt);
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
                //$('.box-dropdown').find('.val-selected').removeClass('active');
                $('.box-dropdown').find('.dropdown-up-style').removeClass('active');
                $('.box-dropdown').find('.dropdown-up-style').addClass('hide');
                sc.settings.closeCallBack();
                
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
            sc.settings.ajaxSubmit();
            outsiteClick(el);
        }

        function selectItem (item, itemValueClick) {
            if (!sc.settings.selectedValue) return;
            item.unbind('click');
            item.on('click', function () {
                if ( $(this).hasClass('disable') ) return;
                var txt = $(this).text(),
                dataValue = $(this).data('value') != undefined ? $(this).data('value') : txt;

                if ( $(el.find('input[type=hidden]')).val() == dataValue ) {
                   // l('no change');
                }else {
                    $(el.find('input[type=hidden]')).val(dataValue);
                    $(el.find('input[type=hidden]')).trigger('change');
                }
                
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

        function outsiteClick (el) {
            //$(document).unbind('click');
            $(document).on('click', function (e) {
                var container = el;

                if ( !container.is(e.target) && container.has(e.target).length === 0 ) {
                    el.find('.val-selected').removeClass('active');
                    if ( sc.settings.styleShow ) {
                        el.find('.val-selected').parent().find('.item-dropdown').velocity("slideUp", { duration: 0 });
                    }else {
                        el.find('.dropdown-up-style').removeClass('active');
                        setTimeout(function() {
                            el.find('.dropdown-up-style').addClass('hide');
                            sc.settings.closeCallBack();
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
            itemToggle: '.advande-search',
            destroy: false
        },
        sc = {},
        el = $(this),
        wrapBoxEffect = $('<div class="dropdown-up-style hide"></div>');

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        if ( sc.settings.destroy ) {
            var domFirst = el.find('.item-dropdown').removeClass('wrap-effect');
            el.append(domFirst);
            el.find('.dropdown-up-style').remove();
            //return;
        }

        if ( sc.settings.styleEffect == 'slideDownUp' ) {
            el.find(sc.settings.itemToggle).addClass('wrap-effect');
            wrapBoxEffect.insertBefore(el.find(sc.settings.itemToggle));
            wrapBoxEffect.append(el.find(sc.settings.itemToggle));
        }

        el.find(sc.settings.btnEvent).unbind('click');
        el.find(sc.settings.btnEvent).on('click', toggleShow);

        function toggleShow (e) {

            if ( !checkMobile() && e.target.nodeName == "BUTTON" ) { return; }

            var _this = $(this);

            if ( _this.hasClass('active') ) {
                _this.removeClass('active');
                if ( sc.settings.styleEffect == 'slide' ) {
                    //el.find(sc.settings.itemToggle).velocity("slideUp", { duration: 350 });
                }else if ( sc.settings.styleEffect == 'slideDownUp' ) {
                    el.find('.dropdown-up-style').removeClass('active');
                    setTimeout(function() {
                        el.find('.dropdown-up-style').addClass('hide');
                    },250);
                }
            }else {
                e.preventDefault();
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
                        //el.find(sc.settings.btnEvent).data('flag',true);
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

    var prices = {"thue": {"-1": "Giá Bất kỳ","0": 0,"2000000" : "2 triệu","4000000" : "4 triệu","6000000" : "6 triệu","8000000" : "8 triệu","10000000": "10 triệu","12000000": "12 triệu","14000000": "14 triệu","16000000": "16 triệu","18000000": "18 triệu","20000000": "20 triệu",},"mua": {"-1": "Giá bất kỳ","0": 0,"2000000000" : "2 tỷ","4000000000" : "4 tỷ","6000000000" : "6 tỷ","8000000000" : "8 tỷ","10000000000": "10 tỷ","12000000000": "12 tỷ","14000000000": "14 tỷ","16000000000": "16 tỷ","18000000000": "18 tỷ","20000000000": "20 tỷ"}};
    var dt = {"-1": "DT Bất kỳ","0": 0, "10": "10m2", "20": "20m2", "30": "30m2", "40": "40m2", "50": "50m2", "60": "60m2", "70": "70m2", "80": "80m2", "90": "90m2", "100": "100m2", "200": "200m2", "300": "300m2", "400": "400m2", "500": "500m2" };
    
    return this.each(function() {
        var defaults = {
            inputMin: '.input-min',
            inputMax: '.input-max',
            hinhthuc: '',// mua or thue
            numRenderMax: 11,
            rebuild: false 
        },
        sc = {},
        el = $(this);

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        if ( sc.settings.rebuild ) {
            el.toggleShowMobi({
                destroy: true
            });
            el.find('.wrap-minmax').html('');
            var txtMin = el.find('.box-input .min-val').data('text'),
                txtMax = el.find('.box-input .max-val').data('text');
            el.find('.box-input .min-val').text(txtMin);
            el.find('.box-input .max-val').text(txtMax);
            toggleMinMax ('max-val');
            return;
        }else {
            el.toggleShowMobi({
                styleEffect: 'slideDownUp',
                btnEvent: '.val-selected',
                itemToggle: '.wrap-min-max'
            });
        }

        Object.size = function(obj) {
            var size = 0, key;
            for (key in obj) {
                if (obj.hasOwnProperty(key)) size++;
            }
            return size;
        };

        if ( el.data('itemMinmax') == 'prices' ) {
            var size = Object.size(prices[sc.settings.hinhthuc]), temp = 0;
            for ( var i in prices[sc.settings.hinhthuc] ) {
                temp += 1;
                if ( parseInt(i) < 0 ) {
                    continue;
                }
                var item = $('<li data-number="'+i+'"><a class="option">'+formatPrice(i)+'</a></li>'),
                    itemClone = item.clone();

                item.find('a').on('click', seletedVal);

                el.find('.wrap-minmax[data-wrap-minmax=min-val]').append(item);
            }

            //set value when reload page
            var valHisMax = el.find('#priceMax').val(),
                valHisMin = el.find('#priceMin').val();

            if ( valHisMin != '' || valHisMax != '' ) {
                el.find('.min-val').html(formatPrice(valHisMin));
                el.find('.min-val').data('value',valHisMin);
                renderTxtShow ('min-val', formatPrice(valHisMin));

                el.find('.max-val').html(formatPrice(valHisMax));
                el.find('.max-val').data('value',valHisMax);
                renderTxtShow ('max-val', formatPrice(valHisMax));
            }

            if ( valHisMin != '' ) {
                renderMax(parseInt(valHisMin));
                renderTxtShow('min-val');
            }else {
                temp = 0;
                for ( var i in prices[sc.settings.hinhthuc] ) {
                    temp += 1;
                    if ( parseInt(i) < 0 ) {
                        continue;
                    }
                    var item = $('<li data-number="'+i+'"><a class="option">'+formatPrice(i)+'</a></li>');

                    if ( parseInt(formatPrice(i)) > 0 ) {
                        item.find('a').on('click', seletedVal);
                        el.find('.wrap-minmax[data-wrap-minmax=max-val]').append(item);
                    }

                    if ( temp == size ) {
                        item = $('<li data-number="-1"><a class="option">Giá Bất Kỳ</a></li>');
                        item.find('a').on('click', seletedVal);
                        el.find('.wrap-minmax[data-wrap-minmax=max-val]').append(item);
                    }
                }
            }

        }else if ( el.data('itemMinmax') == 'area' ) {
            var size = Object.size(dt), temp = 0;
            for ( var i in dt ) {
                temp += 1;
                if ( parseInt(i) < 0 ) {
                    continue;
                }
                var item = $('<li data-number="'+i+'"><a class="option">'+dt[i]+'</a></li>'),
                    itemClone = item.clone();

                item.find('a').on('click', seletedVal);
                el.find('.wrap-minmax[data-wrap-minmax=min-val]').append(item);
            }

            //set value when reload page
            var valHisMax = el.find('#dtMax').val(),
                valHisMin = el.find('#dtMin').val();

            if ( valHisMin != '' ) {
                renderMax(parseInt(valHisMin));
                //renderTxtShow('min-val');
                el.find('.min-val').html(valHisMin+'m2');
                el.find('.min-val').data('value',valHisMin);
                renderTxtShow ('min-val', valHisMin+'m2');

                if ( valHisMax != '' ) {
                    el.find('.max-val').html(valHisMax+'m2');
                    el.find('.max-val').data('value',valHisMax);
                    renderTxtShow ('max-val', valHisMax+'m2');
                }
            }else if ( valHisMax != '' ) {
                el.find('.max-val').html(valHisMax+'m2');
                el.find('.max-val').data('value',valHisMax);
                renderTxtShow ('max-val', valHisMax+'m2');
            }else {
                temp = 0;
                for ( var i in dt ) {
                    temp += 1;
                    if ( parseInt(i) < 0 ) {
                        continue;
                    }
                    
                    var item = $('<li data-number="'+i+'"><a class="option">'+dt[i]+'</a></li>');

                    if ( parseInt(i) > 0 ) {
                        item.find('a').on('click', seletedVal);
                        el.find('.wrap-minmax[data-wrap-minmax=max-val]').append(item);
                    }

                    if ( temp+1 == size ) {
                        item = $('<li data-number="-1"><a class="option">DT Bất Kỳ</a></li>');
                        item.find('a').on('click', seletedVal);
                        el.find('.wrap-minmax[data-wrap-minmax=max-val]').append(item);
                    }
                }
            }
        }

        el.find('ul[data-wrap-minmax=min-val]').show();

        el.find('.min-max').on('click', clickInput);

        //el.find('.filter-minmax li a').on('click', seletedVal);

        function seletedVal (e) {
            var _this = $(this),
                numberVal = _this.parent().data('number'),
                txt = _this.text(),
                minmax = _this.closest('.wrap-minmax').data('wrapMinmax'),
                valMin = '', valMax = '';

            if ( numberVal > 0 ) {
                el.find('.'+minmax).html(txt);
                el.find('.'+minmax).data('value',numberVal);
            }

            if ( numberVal <= 0 ) {
                el.find('.'+minmax).data('value','');
            }

            if ( minmax == 'min-val' ) {
                toggleMinMax(minmax);

                renderMax(numberVal);

                if ( el.find('.max-val').data('value') > 0 ) {
                    var txtDefalut = el.find('.max-val').data('text');
                    el.find('.max-val').html(txtDefalut);
                    el.find('.max-val').data('value','');
                }
            }else {// hidden khi chon xong gia tri max
                el.find('.val-selected').trigger('click');
            }

            valMin = el.find('.min-val').data('value');
            valMax = el.find('.max-val').data('value');

            if ( el.data('itemMinmax') == 'prices' ) {
                if ( valMax < 0 ) {
                    valMax = '';
                }
                $('#priceMin').val(valMin);
                $('#priceMax').val(valMax);

                if ( valMin > 0 ) {
                    $('#priceMin').trigger('change');
                }else if ( valMax > 0 ) {
                    $('#priceMax').trigger('change');
                }else {
                    $('#priceMin, #priceMax').trigger('change');
                }
            }else if ( el.data('itemMinmax') == 'area' ) {
                if ( valMax < 0 ) {
                    valMax = '';
                }
                $('#dtMin').val(valMin);
                $('#dtMax').val(valMax);
                if ( valMin > 0 ) {
                    $('#dtMin').trigger('change');
                }else if ( valMax > 0 ) {
                    $('#dtMax').trigger('change');
                }else {
                    $('#dtMin, #dtMax').trigger('change');
                }
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

            if ( el.data('itemMinmax') == 'area' ) {
                unitDT = 'm2';
                if ( valMin == 0 ) {
                    valMin = 10;
                    valMax = valMin;
                }else {
                    valMax = valMin + 10;
                }
            }else {

                if ( sc.settings.hinhthuc == 'thue' && valMin == 0 ) {
                    valMin = 2000000;
                }else if ( sc.settings.hinhthuc == 'mua' && valMin == 0 ) {
                    valMin = 2000000000;
                }

                if ( sc.settings.hinhthuc == 'thue') {
                    valMax = valMin + 2000000;
                }else if ( sc.settings.hinhthuc == 'mua' ) {
                    valMax = valMin + 2000000000;
                }
            }

            for ( var i = 0; i < sc.settings.numRenderMax; i++ ) {
                var item = $('<li data-number="'+valMax+'"><a class="option">'+formatPrice(''+valMax+'')+unitDT+'</a></li>');
                
                if ( sc.settings.hinhthuc == 'thue') {
                    valMax += 2000000;
                }else if ( sc.settings.hinhthuc == 'mua' ) {
                    valMax += 2000000000;
                }

                if ( el.data('itemMinmax') == 'area' ) {
                    valMax += valMin;
                }

                itemMax.append(item);

                if ( i+1 == sc.settings.numRenderMax ) {
                    if ( el.data('itemMinmax') == 'area' ) {
                        item = $('<li data-number="-1"><a class="option">DT Bất Kỳ</a></li>');
                        itemMax.append(item);
                    }else if ( el.data('itemMinmax') == 'prices' ) {
                        item = $('<li data-number="-1"><a class="option">Giá Bất Kỳ</a></li>');
                        itemMax.append(item);
                    }
                }
            }
            itemMax.find('li a').on('click', seletedVal);
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
            if ( el.find('.min-val').data('value') == 0 && el.find('.max-val').data('value') == 0 ) {
                return 0;
            }else if ( el.find('.min-val').data('value') == 0 && el.find('.max-val').data('value') > 0 ) {
                return 1;
            }else if ( el.find('.min-val').data('value') > 0 && el.find('.max-val').data('value') == 0 ) {
                return 2;
            }else if ( el.find('.min-val').data('value') > 0 && el.find('.max-val').data('value') > 0 ) {
                return 3;
            }else {
                return 4;
            }
        }

        function renderTxtShow (minmax, txt) {
            var wrapTxt = el.find('.val-selected');

            if ( minmax == 'min-val' && txt != '' ) {
                wrapTxt.find('.wrap-min').html(txt);
            }else if ( minmax == 'max-val' && txt != '' ) {
                wrapTxt.find('.wrap-max').html(txt);
            }

            wrapTxt.find('.txt-holder-minmax').hide();

            if ( checkValMinMax() == 0 ) {
                wrapTxt.find('.txt-holder-minmax').show();
                wrapTxt.find('.wrap-max,.wrap-min,.trolen,.troxuong, .den').hide();
                if ( minmax == 'min-val' ) {
                    var txtDefalut = el.find('.min-val').data('text');
                    el.find('.min-val').html(txtDefalut);
                    el.find('.min-val').data('value','');
                }else {
                    var txtDefalut = el.find('.max-val').data('text');
                    el.find('.max-val').html(txtDefalut);
                    el.find('.max-val').data('value','');
                }
            }else if ( checkValMinMax() == 1 ) {
                wrapTxt.find('.wrap-max,.troxuong').show();
                wrapTxt.find('.den').hide();
                wrapTxt.find('.wrap-min').hide();
                if ( minmax == 'max-val' ) {
                    var txtDefalut = el.find('.min-val').data('text');
                    el.find('.min-val').html(txtDefalut);
                    el.find('.min-val').data('value','');
                }
            }else if ( checkValMinMax() == 2 ) {
                wrapTxt.find('.wrap-min,.trolen').show();
                wrapTxt.find('.wrap-max,.troxuong, .den').hide();
                if ( minmax == 'min-val' ) {
                    var txtDefalut = el.find('.max-val').data('text');
                    el.find('.max-val').html(txtDefalut);
                    el.find('.max-val').data('value','');
                }
            }else if ( checkValMinMax() == 3 ) {
                wrapTxt.find('.wrap-max,.wrap-min,.den').show();
                wrapTxt.find('.trolen,.troxuong').hide();
            }else {
                wrapTxt.find('.troxuong').hide();
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
        
        el.find('.section').eq(sc.settings.active).velocity("fadeIn", { duration: 500 });

        current = sc.settings.active;

        sc.settings.funCallBack(current);
        pagi(current);

        if ( current == 0 ) {
            btnBack.addClass('disable');
        }else if ( current+1 == lenItem ) {
            btnNext.addClass('disable');
        }

        function next (e) {
            e.preventDefault();

            if ( $(this).hasClass('disable') ) return;

            if ( !sc.settings.validateFrm(current+1) ) return;

            el.find('.section').eq(current).find('.val-selected').removeClass('error');

            if ( current+1 < lenItem ) {
                var oldItem = current;

                el.find('.section').hide();

                current = current+1;

                sc.settings.funCallBack(current);

                pagi(current);

                el.find('.section').eq(current).velocity("fadeIn", { duration: 500 });

                if ( current+1 == lenItem ) {
                    btnNext.addClass('disable');
                }

                btnBack.removeClass('disable');
            }
        }

        function back (e) {
            e.preventDefault();
            if ( $(this).hasClass('disable') ) return;

            var oldItem = current;

            el.find('.section').hide();

            current = current - 1;

            sc.settings.funCallBack(current);

            pagi(current);

            el.find('.section').eq(current).velocity("fadeIn", { duration: 500 });
            
            if ( current == 0 ) {
                btnNext.removeClass('disable');
                btnBack.addClass('disable');
            }else {
                btnNext.removeClass('disable');
            }
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
            el.parent().css('position','');
            return;
        }

        if ( sc.settings.full ) {
            $loading.css({
                position: 'fixed',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                'z-index': 999999,
                background: 'rgba(0, 0, 0, 0.20)'
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
                background: 'rgba(0, 0, 0, 0.20)'
            });

            el.css('position','relative');
            
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
                    popupItem.css({
                        'overflow-y': 'auto'
                    });
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
            //$(sc.settings.closeBtn).unbind('click');
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

    if ( wWindow <= 985 ) { ///Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
        return true;
    }
    return false;
}

var timeOutHide;
$.fn.alertBox = function (options) {

    return this.each(function() {
        var defaults = {
            txt: '',
            duration: 3000,
            position: 'bottom-left' // bottom-left, center
        },
        sc = {},
        el = $(this), wrapAlert, txtShow;

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        if ( el.data('alert') != undefined ) {
            txtShow = el.data('alert');
        }else if ( sc.settings.txt != '' ) {
            txtShow = sc.settings.txt;
        }

        //<div class="alert-item"><a class="btn-close-alert" href="#"><span class="icon-mv fs-12"><span class="icon-close-icon"></span></span></a><div class="wrap-alert">'+txtShow+'</div></div>
        wrapAlert = '<div class="alert-item hide"><a class="btn-close-alert" href="#"><span class="icon-mv fs-12"><span class="icon-close-icon"></span></span></a><div class="wrap-alert">'+txtShow+'</div></div>';

        $('#alert-noti').prepend($(wrapAlert));

        var aBox = $('.alert-item'),
            wB = aBox.outerWidth(),
            hB = aBox.outerHeight(),
            wWin = $(window).outerWidth(),
            hWin = $(window).outerHeight();

        $('.alert-item').removeClass('hide');
        setTimeout(function() {
            $('.alert-item').addClass('active')
        }, 300);

        clearTimeout(timeOutHide);

        $('#alert-noti').hover(function () {
            clearTimeout(timeOutHide);
        }, function () {
            timeOutHide = setTimeout(function () {
                        $('.alert-item').fadeOut('slow', function() {
                            $(this).remove();
                            $('#alert-noti').html('');
                        });
                    },sc.settings.duration);
        });

        timeOutHide = setTimeout(function () {
                        $('.alert-item').fadeOut('slow', function() {
                            $(this).remove();
                            $('#alert-noti').html('');
                        });
                    },sc.settings.duration);

        $('.alert-item .btn-close-alert').on('click', function (e) {
            e.preventDefault();
            clearTimeout(timeOutHide);
            $(this).parent().remove();
        });
    });
}

var originalLeave = $.fn.popover.Constructor.prototype.leave;
$.fn.popover.Constructor.prototype.leave = function(obj){
    var self = obj instanceof this.constructor ? obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type),
        container, timeout;

    originalLeave.call(this, obj);

    if(obj.currentTarget) {
        container = $(obj.currentTarget).siblings('.popover')
        timeout = self.timeout;
        container.one('mouseenter', function(){
            //We entered the actual popover – call off the dogs
            clearTimeout(timeout);
            //Let's monitor popover content instead
            container.one('mouseleave', function(){
                $.fn.popover.Constructor.prototype.leave.call(self, self);
            });
        });
    }
};


function convertKey ( numKey ) {

    if ( numKey >= 1000 && numKey < 1000000 ) {
        var val = numKey / 1000,
            getNum = val.toString().split('.'),
            numConverted = getNum[0]+'K+';

        return numConverted;
    }

    return numKey;
}