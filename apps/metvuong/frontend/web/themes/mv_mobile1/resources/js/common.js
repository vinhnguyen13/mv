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
            selectValue: false
        },
        sc = {},
        el = $(this),
        itemList = el.parent().find('.item-dropdown li a'),
        hiddenFillValue = el.parent().find('#value_selected');

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        el.find('.val-selected').on('click', toggleView);

        showSortLoad();

        function showSortLoad () {
            if ( hiddenFillValue.val() != '' ) {
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

            }
        }

        function selectItem (item) {
            itemList.on('click', function () {
                var txt = $(this).text(),
                    dataValue = $(this).data('value');

                hiddenFillValue.val(dataValue);
                item.parent().find('.item-dropdown').hide();
                item.removeClass('active');
                el.find('.val-selected .selected').text(txt);
            });
        }
    });
}