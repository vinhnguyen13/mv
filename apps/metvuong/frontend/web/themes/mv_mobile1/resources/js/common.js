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
        el = $(this);

        if ( el.length == 0 ) return el;

        sc.settings = $.extend({}, defaults, options);

        el.on('click', toggleView);

        function toggleView (e) {
            var _this = $(this);
            
        }
    });
}