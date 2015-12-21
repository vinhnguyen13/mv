(function () {
	
	var minmax = {
		wrap: $('.filter-pane'),
		wrapVal: $('.filter-common'),
		inputVal: $('.dualboxes input[type=text]'),
		tabs: $('.list-filter > li > a'),
		itemVal: $('.minmax .dropdown-options li a'),
		wrapMinMaxVal: $('.minmax-options'),
		init: function () {
			minmax.clickTab();
		},
		open: function (item) {
			var $root = item.parent();

			minmax.wrapVal.hide();

			if ( item.hasClass('active') ) {
        		minmax.tabs.removeClass('active');
        	}else {
        		minmax.tabs.removeClass('active');
        		$root.find('.filter-common').show();
        		item.addClass('active');
        	}

        	minmax.focusInput();
        	minmax.clickVal(item);
        	minmax.close(item);

		},
		close: function (item) {
			$(document).on('click', function (e) {
				//e.preventDefault();
				var container = $(".list-filters-result .outsideevent");
		        if ( !container.is(e.target) && container.has(e.target).length === 0 ){
		        	minmax.wrapVal.hide();
		        	minmax.tabs.removeClass('active');
		        }
		        //return;
			});
		},
		clickTab: function () {
			minmax.tabs.on('click', function (e) {
				e.preventDefault();
				var _this = $(this);

				minmax.open(_this);

				dropdownSelect.init(_this);

				return;
			});
		},
		clickVal: function (item) {
			minmax.itemVal.unbind('click');
			minmax.itemVal.on('click', function (e) {
				e.preventDefault();

				var _this = $(this),
					$wrapVal = _this.parent(),
					$root = _this.closest('.filter-common'),
					attrInput = _this.closest('.minmax').data('toggleFilter'),
					dataNumber = $wrapVal.data('number'),
					dataUnit = $wrapVal.data('unit') === undefined ? '' : $wrapVal.data('unit'),
					inputVal = $root.find('.commaFormat'),
					txtVal = '',
					txtSymbol = item.data('symbolUnit') === undefined ? '' : item.data('symbolUnit');

				_this.closest('.minmax').find(minmax.itemVal).removeClass('active');

				_this.addClass('active');

				if ( dataNumber === 0 ) {
					item.parent().find('.commaFormat[id="'+attrInput+'"]').val('');
					minmax.toggleMinMax(attrInput, item);
					//return;
				}

				item.parent().find('.commaFormat[id="'+attrInput+'"]').val(dataNumber+' '+dataUnit);
				item.parent().find('.commaFormat[id="'+attrInput+'"]').data('valNum', dataNumber);

				var valMin = item.parent().find('.commaFormat[id="min-val"]').val(),
					valNumMin = item.parent().find('.commaFormat[id="min-val"]').data('valNum'),
					valMax = item.parent().find('.commaFormat[id="max-val"]').val(),
					valNumMax = item.parent().find('.commaFormat[id="max-val"]').data('valNum');

				if ( valMin.trim() != '' ) {
					txtVal += valMin + txtSymbol;
				}

				if ( valMax.trim() != '' ) {
					if ( valMin.trim() != '' ) {
						txtVal += ' - ';
					}
					txtVal += valMax + txtSymbol;
				}

				item.html(txtVal);

				if ( _this.parent().hasClass('anyVal') ) {
					valMax = '';
				}

				if ( valMin.trim() != '' && valMax === '' ) {
					//up-to
					item.append('<em class="fa fa-long-arrow-up"></em>');
				}else if ( valMin === '' && valMax != '' ) {
					//down-to
					item.prepend('<em class="fa fa-long-arrow-down"></em>');
				}else {
					item.removeClass('up-to down-to');
				}

				minmax.toggleMinMax(attrInput, item);

				var valHidden = item.parent().find('.commaFormat[id="'+attrInput+'"]').data('priceshortcut');

				if ( $root.data('filter') == 'price-min-max' ) {
					$('#price-min-filter').val(valNumMin);
					$('#price-max-filter').val(valNumMax);
				}else if ( $root.data('filter') == 'dt-min-max' ) {
					$('#dt-min-filter').val(valNumMin);
					$('#dt-max-filter').val(valNumMax);
				}

				return;

			});
		},
		toggleMinMax: function (attrInput, item) {
			if ( attrInput === 'max-val' && !item.is('input') ) {
				item.parents('li').find('.minmax-options').removeClass('hide');
				item.parents('li').find('.minmax-options[data-toggle-filter="max-val"]').addClass('hide');
				$(document).trigger('click');
			}else if ( attrInput === 'min-val' && !item.is('input') ) {
				item.parents('li').find('.minmax-options').addClass('hide');
				item.parents('li').find('.minmax-options[data-toggle-filter="max-val"]').removeClass('hide');
			}else {
				item.parents('li').find('.minmax-options').addClass('hide');
				item.parents('li').find('.minmax-options[data-toggle-filter="'+attrInput+'"]').removeClass('hide');

			}
		},
		focusInput: function () {
			minmax.inputVal.on('click', function (e) {
				e.preventDefault();
				var _this = $(this),
					attrInput = _this.attr('id');

				minmax.toggleMinMax(attrInput, _this);
			});
		}
	};

	var dropdownSelect = {
		itemVal: $('.filter-dropdown li a'),
		tabs: $('.list-filter > li > a'),
		init: function (item) {
			dropdownSelect.itemVal.unbind('click');
			dropdownSelect.itemVal.on('click', function (e) {
				e.preventDefault();
				var _this = $(this),
					txt = _this.html();

				item.find('span').html(txt);
				item.find('span').append('<em class="fa fa-long-arrow-up"></em>');
				if ( _this.closest('.filter-common').data('filter') == 'phong-ngu' ) {
					$('#bed-filter').val(txt);
				}else if ( _this.closest('.filter-common').data('filter') == 'phong-tam' ) {
					$('#bath-filter').val(txt);
				}
				
				minmax.close();
				$(document).trigger('click');
			});
		}
	}

	minmax.init();
	
})();