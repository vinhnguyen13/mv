$(document).ready(function() {
	$("#menu-header").mmenu({
		navbar: {
			add: false
		}
	});

	$(document).on('click','.link-login', function() {$('a[href=#tab-signin]').trigger('click');});
	$(document).on('click','.link-regis', function() {$('a[href=#tab-signup]').trigger('click');});
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

$.fn.dropdown = function (options) {

    return this.each(function() {
        var defaults = {
            styleShow: 1, // 1. relative, 0. absolute
            txtAdd: false
        },
        sc = {},
        el = $(this),
        itemList = el.parent().find('.item-dropdown li a'),
        hiddenFillValue = el.parent().find('.value_selected');

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        el.find('.val-selected').on('click', toggleView);

        showSortLoad();
        outsiteClick();

        function showSortLoad () {
            if ( hiddenFillValue.val() != '' && hiddenFillValue.length > 0 ) {
                valGet = hiddenFillValue.val();
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
                    _this.parent().find('.item-dropdown').hide();
                }else {
                    _this.addClass('active');
                    _this.parent().find('.item-dropdown').show();
                    selectItem(_this);
                }
            }
        }

        function selectItem (item) {
            itemList.unbind('click');
            itemList.on('click', function () {
                var txt = $(this).text(),
                    dataValue = $(this).data('value');

                hiddenFillValue.val(dataValue);
                item.parent().find('.item-dropdown').hide();
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
                    el.find('.val-selected').parent().find('.item-dropdown').velocity("slideUp", { duration: 0 });
                }
            });
        }
    });
}

$.fn.toggleShowMobi = function (options) {

    return this.each(function() {
        var defaults = {
            btnEvent: '.advande-search-btn',
            itemToggle: '.advande-search'
        },
        sc = {},
        el = $(this);

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        el.find(sc.settings.btnEvent).on('click', toggleShow);

        function toggleShow (e) {
            var _this = $(this);
            if ( _this.hasClass('active') ) {
                _this.removeClass('active');
                el.find(sc.settings.itemToggle).velocity("slideUp", { duration: 350 });
            }else {
                _this.addClass('active');
                el.find(sc.settings.itemToggle).velocity("slideDown", { duration: 350 });
                outsiteClick();
            }
        }

        function outsiteClick () {
            $(document).on('click', function (e) {
                var container = el;
                if ( !container.is(e.target) && container.has(e.target).length === 0 ){
                    el.find(sc.settings.btnEvent).removeClass('active');
                    el.find(sc.settings.itemToggle).velocity("slideUp", { duration: 0 });
                }
            });
        }
    });
}

$.fn.price_dt = function (options) {

    return this.each(function() {
        var defaults = {
            inputMin: '.input-min',
            inputMax: '.input-max'
        },
        sc = {},
        el = $(this);

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        el.toggleShowMobi({
            btnEvent: '.value-selected',
            itemToggle: '.item-advande'
        });

        el.find('.min-max').keyup(function(e){
            var _this = $(this);

            el.find('.tu').show();

            l(checkVal(_this));

            if ( checkVal(_this) == 1 ) {// min rong
                el.find('.troxuong').show();
            }else if ( checkVal(_this) == 2 ) {// max rong
                el.find('.den').hide();
                el.find('.trolen').show();
            }else if ( checkVal(_this) == 3 ) {
                
            }

            if ( _this.is($(sc.settings.inputMax)) ) {
                el.find('.wrap-max').show().html(_this.val());
            }else if ( _this.is($(sc.settings.inputMin)) ) {
                el.find('.wrap-min').show().html(_this.val());
            }
        });

        function checkVal (item) {
            if ( item.val() == "" && item.is($(sc.settings.inputMin)) ) {
                return 1; // min k co gia tri
            }else if ( item.val() == "" && item.is($(sc.settings.inputMax)) ) {
                return 2; // max k co gia tri
            }else { // min hoac max co gia tri
                return 3;
            }
        }
    });

}