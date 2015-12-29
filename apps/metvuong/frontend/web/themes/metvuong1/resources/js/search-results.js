(function () {
	var prices = {"thue": {"0" : "0","-1": "Giá bất kỳ","2000000" : "2 triệu","4000000" : "4 triệu","6000000" : "6 triệu","8000000" : "8 triệu","10000000": "10 triệu","12000000": "12 triệu","14000000": "14 triệu","16000000": "16 triệu","18000000": "18 triệu","20000000": "20 triệu",},"muaban": {"0" : "0","-1": "Giá bất kỳ","2000000000" : "2 tỷ","4000000000" : "4 tỷ","6000000000" : "6 tỷ","8000000000" : "8 tỷ","10000000000": "10 tỷ","12000000000": "12 tỷ","14000000000": "14 tỷ","16000000000": "16 tỷ","18000000000": "18 tỷ","20000000000": "20 tỷ"}};
	var dt = { "0": "0", "-1": "Bất kỳ", "10": "10 m<sup>2</sup>", "20": "20 m<sup>2</sup>", "30": "30 m<sup>2</sup>", "40": "40 m<sup>2</sup>", "50": "50 m<sup>2</sup>", "60": "60 m<sup>2</sup>", "70": "70 m<sup>2</sup>", "80": "80 m<sup>2</sup>", "90": "90 m<sup>2</sup>", "100": "100 m<sup>2</sup>"};
	var minmax = {
		wrap: $('.filter-pane'),
		wrapVal: $('.filter-common'),
		inputVal: $('.dualboxes input[type=text]'),
		tabs: $('.list-filter > li > a'),
		itemVal: $('.minmax .dropdown-options li a'),
		wrapMinMaxVal: $('.minmax-options'),
		idHinhThuc: '',
		txtAnyPrice: '',
		tabsClick: '',
		numShow: 11,
		priceAdd: '',
		priceGetFirst: {},
		init: function () {
			minmax.clickTab();
			minmax.render();

			//reset filter
			$('#reset-filter').on('click', minmax.reset);

			//submit
			$('#submit-filter').on('click', function (e) {
				e.preventDefault();
				
			});
		},
		open: function (item) {
			var $root = item.parent();

			//minmax.wrapVal.hide();

			minmax.wrapVal.addClass('hidden-effect');
			minmax.wrapVal.removeClass('active');

			if ( item.hasClass('active') ) {
        		minmax.tabs.removeClass('active');
        	}else {
        		minmax.tabs.removeClass('active');

        		$root.find('.filter-common').removeClass('hidden-effect');
				setTimeout(function() {
	                $root.find('.filter-common').addClass('active');
	            }, 30);
        	
        		//$root.find('.filter-common').show();
        		item.addClass('active');
        	}

        	minmax.focusInput();
        	minmax.close(item);
		},
		close: function (item) {
			$(document).unbind('click');
			$(document).on('click', function (e) {
				e.preventDefault();
				var container = $(".list-filters-result .outsideevent");
				if ( !container.is(e.target) && container.has(e.target).length === 0 ){
		        	minmax.wrapVal.addClass('hidden-effect');
					minmax.tabs.removeClass('active');
		        }
		        if ( minmax.tabsClick.length > 0 ) {

		        }
			});
		},
		clickTab: function () {
			minmax.tabs.on('click', function (e) {
				e.preventDefault();
				var _this = $(this);

				minmax.tabsClick = _this;

				minmax.open(_this);

				dropdownSelect.init(_this);

				return;
			});
		},
		clickVal: function (valItem) {
			minmax.itemVal.unbind('click');
			valItem.on('click', function (e) {
				e.preventDefault();

				var _this = $(this),
					$wrapVal = _this.parent(),
					$root = _this.closest('.filter-common'),
					attrInput = _this.closest('.minmax').data('toggleFilter'),
					dataNumber = $wrapVal.data('number'),
					inputVal = $root.find('.commaFormat'),
					txtVal = '',
					valNumMin = '',
					valNumMax = '',
					txtSymbol = minmax.tabsClick.data('symbolUnit') === undefined ? '' : minmax.tabsClick.data('symbolUnit');

				_this.closest('.minmax').find('li a').removeClass('active');

				_this.addClass('active');

				var valShowMaxInput = dataNumber != '' ? minmax.priceUnit(dataNumber) : '';

				if ( dataNumber === 0 ) {
					minmax.tabsClick.parent().find('.commaFormat[id="'+attrInput+'"]').val('');
					minmax.toggleMinMax(attrInput, minmax.tabsClick);
					minmax.tabsClick.parent().find('.commaFormat[id="max-val"]').trigger('click');
				}

				minmax.tabsClick.parent().find('.commaFormat[id="'+attrInput+'"]').val(valShowMaxInput);
				minmax.tabsClick.parent().find('.commaFormat[id="'+attrInput+'"]').data('valNum',dataNumber);
				
				minmax.toggleMinMax(attrInput, minmax.tabsClick);
				
				var valMin = minmax.tabsClick.parent().find('.commaFormat[id="min-val"]').val(),
					valMax = minmax.tabsClick.parent().find('.commaFormat[id="max-val"]').val();

				valNumMin = minmax.tabsClick.parent().find('.commaFormat[id="min-val"]').data('valNum');
				valNumMax = minmax.tabsClick.parent().find('.commaFormat[id="max-val"]').data('valNum');

				if ( valMin != '' && attrInput == 'min-val' ) {
					minmax.renderMaxVal(valNumMin);
				}

				if ( valMin != '' ) {
					txtVal += valMin + txtSymbol;
				}

				if ( valMax != '' ) {
					if ( valMin != '' ) {
						txtVal += ' - ';
					}
					txtVal += valMax + txtSymbol;
				}

				if ( _this.parent().hasClass('anyVal') ) {
					valMax = '';
					txtVal = valMin;
				}

				minmax.tabsClick.find('.txt-show').html(txtVal).show();

				minmax.tabsClick.find('em').remove();
				if ( valMin != '' && valMax === '' ) {
					//up-to
					minmax.tabsClick.append('<em class="fa fa-long-arrow-up"></em>');
				}else if ( valMin === '' && valMax != '' ) {
					//down-to
					minmax.tabsClick.append('<em class="fa fa-long-arrow-down"></em>');
				}

				minmax.valSubmit(valNumMin, valNumMax, true);

				return;

			});
		},
		toggleMinMax: function (attrInput, item) {
			if ( attrInput === 'max-val' && !item.is('input') ) {
				$(document).trigger('click');
				item.parents('li').find('.minmax-options').removeClass('hide');
				item.parents('li').find('.minmax-options[data-toggle-filter="max-val"]').addClass('hide');
			}else if ( attrInput === 'min-val' && !item.is('input') ) {
				minmax.tabsClick.parent().find('.commaFormat[id="max-val"]').data('valNum','');
				minmax.tabsClick.parent().find('.filter-common #max-val').val('');
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
					attrInput = _this.attr('id'),
					minVal = minmax.tabsClick.parent().find('.filter-common .dualboxes input[id=min-val]').val(),
					maxVal = minmax.tabsClick.parent().find('.filter-common .dualboxes input[id=max-val]').val();

				minmax.toggleMinMax(attrInput, _this);

				if ( (minVal == 0 || minVal == '') && (minmax.tabsClick.parent().find('.filter-common').data('filter') == 'price-min-max') ) {
					minmax.renderMaxFirst(minmax.priceGetFirst);
				}else if ( (minVal == 0 || minVal == '') && (minmax.tabsClick.parent().find('.filter-common').data('filter') == 'dt-min-max') ) {
					minmax.renderMaxFirst(dt);
				}
			});
		},
		getValCookie: function (name) {
            var value = "; " + document.cookie,
                parts = value.split("; "+name+"="),
                valCookie;

            if (parts.length == 2) 
                valCookie = parts.pop().split(";").shift();

            return valCookie;
        },
        cookieSearch: function () {
            var valCookie = minmax.getValCookie('valSearch'),
                objItemGet;

            try {
                objItemGet = JSON.parse(valCookie);
            }catch (e) {
                objItemGet = [];
            }

            if ( objItemGet.length > 0 ) {
                flagCookie = true;
            }

            for ( var i = 0; i < objItemGet.length; i++ ) {
                if ( objItemGet[i].itemData == 'mua-thue' || objItemGet[i].itemData == 'ban-thue' ) {
                	var arrType = {};
	                for ( var j in prices ) { // prices theo hinh thuc mua-ban-thue
	                    if ( objItemGet[i].idItem == 1 && j == 'muaban' ) { // hinh thuc mua-ban
	                        arrType = prices[j];
	                    }else if ( objItemGet[i].idItem == 2 && j == 'thue' ) { // hinh thuc cho thue - muon thue
	                        arrType = prices[j];
	                    }
	                }
	                for ( var j in arrType ) {
	                    var item;
	                    if ( parseInt(j) < 0 ) {
	                        txtAnyPrice = arrType[j];
	                        continue;
	                    }
	                    item = $('<li data-number="'+j+'" data-unit=""><a class="option">'+arrType[j]+'</a></li>');
	                    $('#min-price-options ul').append(item);
	                    minmax.clickVal(item.find('a'));
	                }

	                minmax.priceGetFirst = arrType;

	                break;
                }
            }
        },
		render: function () {
			// render gia theo hinh thuc vd: mua-thue or ban-thue
			minmax.cookieSearch();

			// render dien tich
			for ( var j in dt ) {
                var item;
                if ( parseInt(j) < 0 ) {
                    txtAnyPrice = dt[j];
                    continue;
                }
                item = $('<li data-number="'+j+'" data-unit=""><a class="option">'+dt[j]+'</a></li>');
                $('#min-dt-options ul').append(item);
                minmax.clickVal(item.find('a'));
            }
		},
		renderMaxFirst: function (GetFirst) {
			minmax.tabsClick.parent().find('.minmax[data-toggle-filter=max-val] ul').html('');
			for ( var j in GetFirst ) {
                var item;
                if ( parseInt(j) == 0 ) {
                	continue;
                }
                if ( parseInt(j) < 0 ) {
                    txtAnyPrice = GetFirst[j];
                    continue;
                }
                item = $('<li data-number="'+j+'" data-unit=""><a class="option">'+GetFirst[j]+'</a></li>');
                minmax.tabsClick.parent().find('.minmax[data-toggle-filter=max-val] ul').append(item);
                minmax.clickVal(item.find('a'));
            }
            var item = $('<li data-number="" data-unit=""><a class="option">'+txtAnyPrice+'</a></li>');
            minmax.tabsClick.parent().find('.minmax[data-toggle-filter=max-val] ul').append(item);
            minmax.clickVal(item.find('a'));
		},
		renderMaxVal: function (valMin) {
			//render price max
			minmax.tabsClick.parent().find('.minmax[data-toggle-filter=max-val] ul').html('');
            var priceFirst = parseInt(valMin),
            	symUnit = '';
            if ( minmax.tabsClick.parent().find('.filter-common').data('filter') == 'dt-min-max' ) {
            	symUnit = ' m<sup>2</sup>';
            }
            for ( var i = 0; i < minmax.numShow; i++ ) {
                var costStr = minmax.priceUnit(valMin) + symUnit;
                if ( valMin != priceFirst ) {
                    var item = $('<li data-number="'+valMin+'"><a class="option">'+costStr+'</a></li>');
                    minmax.tabsClick.parent().find('.minmax[data-toggle-filter=max-val] ul').append(item);
                    minmax.clickVal(item.find('a'));
                }
                valMin += minmax.priceAdd;
            }
            var item = $('<li class="anyVal" data-number=""><a class="option">'+txtAnyPrice+'</a></li>');
            minmax.tabsClick.parent().find('.minmax[data-toggle-filter=max-val] ul').append(item);
            minmax.clickVal(item.find('a'));
		},
        priceUnit: function (num) {
            isNegative = false
            if (num < 0) {
                isNegative = true
            }
            num = Math.abs(num);
            if (num >= 1000000000) {
                formattedNumber = (num / 1000000000).toFixed(0).replace(/\.0$/, '') + ' tỷ';
                minmax.priceAdd = 2000000000;
            } else if (num >= 1000000) {
                formattedNumber =  (num / 1000000).toFixed(0).replace(/\.0$/, '') + ' triệu';
                minmax.priceAdd = 2000000;
            } else  if (num >= 1000) {
                formattedNumber =  (num / 1000).toFixed(0).replace(/\.0$/, '') + ' nghìn';
                minmax.priceAdd = 2000;
            } else {
                formattedNumber = num;
                minmax.priceAdd = 2;
            }
            if ( minmax.tabsClick.parent().find('.filter-common').data('filter') == 'dt-min-max' ) {
            	minmax.priceAdd = 10;
            }   
            if(isNegative) { formattedNumber = '-' + formattedNumber }
            return formattedNumber;
        },
        reset: function () {
        	$('.hidden_filter').val('');
        	minmax.tabs.removeClass('active');
        	minmax.tabs.find('em').remove();
        	$('.dropdown-options li a').removeClass('active');
        	minmax.inputVal.val('');
        	$('.txt-tab').show();
        	$('.txt-show').hide().html('');
        	$(document).trigger('click');
        	$('.minmax-options').addClass('hide');
        	$('.minmax-options[data-toggle-filter=min-val]').removeClass('hide');
        },
        valSubmit: function (valNumMin, valNumMax, minmaxFlag) {

        	if ( minmax.tabsClick.parent().find('.filter-common').data('filter') == 'price-min-max' && minmaxFlag ) {
				$('#price-min-filter').val(valNumMin);
				$('#price-max-filter').val(valNumMax);
			}else if ( minmax.tabsClick.parent().find('.filter-common').data('filter') == 'dt-min-max' && minmaxFlag ) {
				$('#dt-min-filter').val(valNumMin);
				$('#dt-max-filter').val(valNumMax);
			}

			

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

				minmax.tabsClick.parent().find('li a').removeClass('active');
				_this.addClass('active');
				item.find('.txt-show').html(txt);
				item.find('.txt-show').append('<em class="fa fa-long-arrow-up"></em>').show();

				if ( minmax.tabsClick.parent().find('.filter-common').data('filter') == 'phong-ngu' ) {
					$('#bed-filter').val(txt);
				}else if ( minmax.tabsClick.parent().find('.filter-common').data('filter') == 'phong-tam' ) {
					$('#bath-filter').val(txt);
				}
				
				$(document).trigger('click');
			});
		}
	};

	minmax.init();
})();