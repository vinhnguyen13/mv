(function () {

	$('.list-filter > li > a').on('click', function (e) {
		e.preventDefault();
		var _this = $(this),
			$root = _this.parent();

		if( $root.find('.filter-common').is(':visible') ) {
			$('.filter-common').hide();
			$('.list-filters-result li > a').removeClass('active');
		}else {
			$('.filter-common').hide();
			$('.list-filters-result li > a').removeClass('active');
			_this.addClass('active');
			$root.find('.filter-common').show();
		}
	});

	$('.dualboxes input').on('focus', function (e) {
		e.preventDefault();
		var _this = $(this),
			name = _this.attr('name');

		_this.closest('.filter-common').find('.minmax-options').addClass('hide');
		$('.minmax-options[data-toggle-filter="'+name+'"]').removeClass('hide');
		
	});

	$('.minmax-options .dropdown-options li a').on('click', function (e) {
		e.preventDefault();
		var _this = $(this),
			$root = _this.parent(),
			dataNumber = $root.data('number'),
			dataUnit = $root.data('unit');

		
	});

	var clickOutsideevent = function (e) {
		var container = $(".list-filters-result .outsideevent");
        if ( !container.is(e.target) && container.has(e.target).length === 0 ){
        	$('.filter-common').hide();
        	$('.list-filters-result li > a').removeClass('active');
        }
	};

	$(document).on('click',clickOutsideevent);

})();