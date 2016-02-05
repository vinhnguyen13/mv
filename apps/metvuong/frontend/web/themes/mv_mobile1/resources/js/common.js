$(document).ready(function() {
    $("#menu-header").mmenu({
        navbar: {
            add: false
        }
    });

    $("#settings-user").mmenu({
        navbar: {
            add: false
        },
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

    $('#search').keyup(function() {
      var self = $(this);
      var val = self.val();
      var ss = $('.suggest-search');
      
      if(val.length > 3 && val != self.data('val')) {
       self.data('val', val);
       
       var url = self.data('url');
       
       $.post(url, {v: val}, function(response){
        var cs = $('.content-suggest ul');
        if(response.length > 0) {
         ss.removeClass('hide');
         cs.html('');
         for(var i in response) {
          cs.append('<li><a href="#">' + response[i].full_name + ' <span>(' + response[i].total + ')</span></a></li>');
         }
        } else {
         ss.addClass('hide');
        }
       });
      } else {
       ss.addClass('hide');
      }
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
            el.find('.radio-ui').addClass('active');
        }

        function toggleCheck (e) {
            e.preventDefault();
            var _this = $(this),
                nameGroup = _this.find('input').attr('name');

            $('input[name='+nameGroup+']').prop("checked", false);
            $('input[name='+nameGroup+']').parent().removeClass('active');

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
                item.find('.radio-ui').addClass('active');
            }else {
                item.find('.radio-ui').removeClass('active');
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
            hiddenFillValue: ''
        },
        sc = {},
        el = $(this),
        itemList = el.parent().find('.item-dropdown li a'),
        wrapBoxEffect = $('<div class="dropdown-up-style hide"></div>');

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
            if ( $(sc.settings.hiddenFillValue).val() != '' && $(sc.settings.hiddenFillValue).length > 0 ) {
                valGet = $(sc.settings.hiddenFillValue).val();
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
            var _this = $(this);
            if ( sc.settings.styleShow ) { // relative
                if ( _this.hasClass('active') ) {
                    _this.removeClass('active');
                    _this.parent().find('.item-dropdown').velocity("slideUp", { duration: 350 });
                }else {
                    _this.addClass('active');
                    _this.parent().find('.item-dropdown').velocity("slideDown", { duration: 350 });
                    selectItem(_this);
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
                    selectItem(_this);
                }
            }
        }

        function selectItem (item) {
            itemList.unbind('click');
            itemList.on('click', function () {
                var txt = $(this).text(),
                    dataValue = $(this).data('value') != undefined ? $(this).data('value') : txt;

                $(sc.settings.hiddenFillValue).val(dataValue);

                if (sc.settings.styleShow) {
                    item.parent().find('.item-dropdown').hide();
                }else {
                    el.find('.dropdown-up-style').removeClass('active');
                    setTimeout(function() {
                        el.find('.dropdown-up-style').addClass('hide');
                    },250);
                }
                
                item.removeClass('active');
                if ( sc.settings.txtAdd ) {
                    var getTxtAdd = el.find('.val-selected').data('textAdd');
                    txt += ' '+getTxtAdd;
                }
                el.find('.val-selected .selected').addClass('selected_val');
                el.find('.val-selected .selected').text(txt);
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

    var prices = {"thue": {"-1": "Giá bất kỳ","2000000" : "2 triệu","4000000" : "4 triệu","6000000" : "6 triệu","8000000" : "8 triệu","10000000": "10 triệu","12000000": "12 triệu","14000000": "14 triệu","16000000": "16 triệu","18000000": "18 triệu","20000000": "20 triệu",},"mua": {"0" : "0+","-1": "Giá bất kỳ","2000000000" : "2 tỷ","4000000000" : "4 tỷ","6000000000" : "6 tỷ","8000000000" : "8 tỷ","10000000000": "10 tỷ","12000000000": "12 tỷ","14000000000": "14 tỷ","16000000000": "16 tỷ","18000000000": "18 tỷ","20000000000": "20 tỷ"}};
    var dt = {"-1": "Bất kỳ", "10": "10m2", "20": "20m2", "30": "30m2", "40": "40m2", "50": "50m2", "60": "60m2", "70": "70m2", "80": "80m2", "90": "90m2", "100": "100m2"};
    
    return this.each(function() {
        var defaults = {
            inputMin: '.input-min',
            inputMax: '.input-max',
            hinhthuc: 'thue', // co 2 hinh thuc, (thue, cho thue) và (bán,)
            numRenderMax: 11
        },
        sc = {},
        el = $(this);

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        el.toggleShowMobi({
            styleEffect: 'slideDownUp',
            btnEvent: '.value-selected',
            itemToggle: '.item-advande'
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

            el.find('.'+minmax).val(txt);

            if ( minmax == 'min-val' ) {
                toggleMinMax(minmax);
                renderMax(numberVal);
                el.find('.max-val').val('');
                valMin = numberVal;

            }else {// hidden khi chon xong gia tri max
                el.find('.value-selected').trigger('click');
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
            if ( el.find('.min-val').val() == "" && el.find('.max-val').val() == "" ) {
                return 1;
            }else if ( el.find('.min-val').val() == "" ) {
                return 2;
            }else if ( el.find('.max-val').val() == "" ) {
                return 3;
            }
        }

        function renderTxtShow (minmax, txt) {
            var wrapTxt = el.find('.value-selected');

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
            validateFrm: function () {}
        },
        sc = {},
        el = $(this),
        nextBack = $('<div class="fixed-prev-next"><a href="#" id="back-screen"><span class="icon"></span>Back</a><a href="#" id="next-screen" class=""><span class="icon"></span>Next</a></div>'),
        btnNext = nextBack.find('#next-screen'),
        btnBack = nextBack.find('#back-screen'),
        current = 0,
        lenItem = el.find('.section').length;

        countEl += 1;

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        btnNext.on('click', next);
        btnBack.on('click', back);

        if ( countEl == 1 ) {
            $('#container').append(nextBack);
        }
        
        el.find('.section').eq(sc.settings.active).removeClass('hide');

        current = sc.settings.active;

        if ( current == 0 ) {
            btnBack.addClass('disable');
        }

        function next () {
            if ( $(this).hasClass('disable') ) return;
            if ( !sc.settings.validateFrm() ) return;

            el.find('.section').removeClass('back-in back-out');
            if ( current+1 < lenItem ) {
                var oldItem = current;
                el.find('.section').eq(oldItem).addClass('out').removeClass('in');
                setTimeout(function () {
                    el.find('.section').eq(oldItem).addClass('hide');
                },350);
                current = current+1;

                pagi(current);

                el.find('.section').eq(current).addClass('in').removeClass('hide');

                if ( current+1 == lenItem ) {
                    btnNext.addClass('disable');
                }

                btnBack.removeClass('disable');
            }

            return false;
        }

        function back () {
            if ( $(this).hasClass('disable') ) return;
            el.find('.section').removeClass('in out');
            var oldItem = current;
            el.find('.section').eq(oldItem).removeClass('back-in').addClass('back-out');
            setTimeout(function () {
                el.find('.section').eq(oldItem).addClass('hide');
            },350);

            current = current - 1;
            
            pagi(current);

            el.find('.section').eq(current).addClass('back-in').removeClass('out hide');
            
            if ( current == 0 ) {
                //nextBack.addClass('hide');
                btnBack.addClass('disable');
            }else {
                btnNext.removeClass('disable');
            }

            return false;
        }

        function pagi (current) {
            if ( current == 0 ) return;
            $(sc.settings.pagi).find('li a').removeClass('active');
            $(sc.settings.pagi).find('li').eq(current-1).find('a').addClass('active');
        }

    });
}