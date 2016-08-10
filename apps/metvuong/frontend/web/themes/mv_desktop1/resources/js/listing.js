var desktop, form, events;
var $window = $(window);
var s = {type: "#type"};
var im = (window.navigator.userAgent.search('Mobi') !== -1) ? 1 : 0;

$(document).ready(function() {
	var compare = {
		countCompare: 0,
		numCheck: 3,
		setNumItem: $('.tool-compare button'),
		numGet: $('.tool-compare .num-show'),
		saveGetItem: $('.getCompare'),
		saveArr: [],
		init: function () {
			$(document).on('click', '.flag-compare-set', function (e) {
				e.preventDefault();
				compare.add($(this));
			});
			$(document).on('click', '.flag-compare-remove', function (e) {
				e.preventDefault();
				compare.remove($(this));
			});
		},
		add: function (item) {
			compare.countCompare += 1;
			if ( compare.countCompare > 0 && compare.countCompare <= compare.numCheck ) {
				item.removeClass('flag-compare-set').addClass('flag-compare-remove');
				item.find('.txt-change').text('Đã thêm so sánh');
				item.find('.icon-balance-scale').attr('class','icon-close-icon');
				compare.numGet.text('('+compare.countCompare+')');
				compare.effectShow();
				compare.checkVal(item, 1);
			}else {
				alert("Bạn đã chọn đủ 3 tin đăng");
				compare.countCompare = compare.numCheck;
			}
		},
		remove: function (item) {
			item.removeClass('flag-compare-remove').addClass('flag-compare-set');
			item.find('.txt-change').text('So Sánh');
			item.find('.icon-close-icon').attr('class','icon-balance-scale');
			compare.countCompare -= 1;
			if ( compare.countCompare == 0 ) {
				compare.numGet.text('');
			}else {
				compare.numGet.text('('+compare.countCompare+')');	
			}
			
			compare.effectShow();
			compare.checkVal(item, 0);
		},
		checkVal: function (item, flag) {
			var idItem = item.data('value');
			if ( flag ) {
				compare.saveArr.push(idItem);
			}else {
				for ( var i = 0; i < compare.saveArr.length; i++ ) {
					if ( idItem == compare.saveArr[i] ) {
						compare.saveArr.splice(i, 1);
					}
				}
			}
			
			var valSet = '['+compare.saveArr.toString()+']';
			compare.saveGetItem.val(valSet);
		},
		effectShow: function () {
			compare.setNumItem.addClass('get-show-num');
			setTimeout(function(){compare.setNumItem.removeClass('get-show-num')},300);
		}
	};

	compare.init();
	/*
	 * Event
	 */
	events = {
		mobileEvents: [],
		desktopEvents: [],
		attachMobileEvent: function() {
			events._attachEvent.apply(events.mobileEvents, arguments);
		},
		attachDesktopEvent: function() {
			events._attachEvent.apply(events.desktopEvents, arguments);
		},
		_attachEvent: function() {
			events.switchEvent('on', arguments);
			
			this.push(arguments);
		},
		detachMobileEvents: function() {
			events._detachEvents(events.mobileEvents);
			events._attachEvents(events.desktopEvents);
		},
		detachDesktopEvents: function() {
			events._detachEvents(events.desktopEvents);
			events._attachEvents(events.mobileEvents);
		},
		_detachEvents: function(eventsCollection) {
			for(var i in eventsCollection) {
				events.switchEvent('off', eventsCollection[i]);
			}
		},
		_attachEvents: function(eventsCollection) {
			for(var i in eventsCollection) {
				events.switchEvent('on', eventsCollection[i]);
			}
		},
		switchEvent: function(status, args) {
			var argsArray = [];
			
			for(var i in args) {
				argsArray.push(args[i]);
			}
			
			var el = argsArray.splice(0, 1);
			
			el = el[0];
			
			el[status].apply(el, argsArray);
		}
	};
	
	
	/*
	 * Form
	 */
	form = {
		el: $('#search-form'),
		listSearchEl: $('#search-list'),
		mapSearchEl: $('#map-search'),
		autoFill: $('#map-search-wrap').find('.auto-fill'),
		fields: null,
		filterFields: function(e) {
			e.preventDefault();
			
			form.fields.filter(function () {
		        return !this.value;
		    }).prop('disabled', true);
			
			window.location = form.mappingUrl(form.el.serialize()) + '?tf=' + tf_ip;
		},
		mappingUrl: function(serialize) {
			var serialize = decodeURIComponent(serialize);
			var paramValues = serialize.split('&');
			var convertPV = [];
			
			var paramValuesFiltered = [];
			var hasMin = false;
			
			for(var i = 0; i < paramValues.length; i++) {
				var paramValue = paramValues[i].split('=');
				var param = paramValue[0];
				var value = paramValue[1];
				
				var min = '_min';
				var max = '_max';
				
				if(param.lastIndexOf(min) == param.length - min.length) {
					paramValuesFiltered.push(param.replace(min, '') + '=' + value);
				} else if(param.lastIndexOf(max) == param.length - max.length) {
					var lastIndex = paramValuesFiltered.length - 1;
					var lastParamValue = paramValuesFiltered[lastIndex].split('=');
					var fakeParam = param.replace(max, '');
					if(lastParamValue[0] == fakeParam) {
						paramValuesFiltered[lastIndex] = paramValuesFiltered[lastIndex] + '-' + value;
					} else {
						paramValuesFiltered.push(fakeParam + '=-' + value);
					}
				} else {
					paramValuesFiltered.push(paramValues[i]);
				}
			}

			for(var i = 0; i < paramValuesFiltered.length; i++) {
				paramValue = paramValuesFiltered[i].split('=');
			
				if(paramValue[0] == 'category_id') {
					convertPV.push(catsSlug[paramValue[1]]);
				} else {
					if(fieldsMapping[paramValue[0]]) {
						var value = (paramValue[0] == 'order_by') ? sortMapping[paramValue[1]] : paramValue[1];
						convertPV.push(fieldsMapping[paramValue[0]] + '_' + value);
					}
				}
			}

			return actionId + '/' + convertPV.join('/');
		},
		sortSubmit: function() {
			form.el.submit();
		},
		searchFocus: function(e) {
			form.mapSearchEl.val('');
			form.showSearchList();
			form.listSearchEl.find('.hint-wrap').show();
			
			var searchHistory = getCookie('sh1');
			
			if(searchHistory) {
				form.listSearchEl.find('.center').show();
				
				var listSearch = JSON.parse(searchHistory);
				var lsLength = listSearch.length;
				
				form.listSearchUl.html('');
				
				for(var i = 0; i < lsLength; i++) {
					form.listSearchUl.append('<li><a class="search-item" href="javascript:;" data-slug="' + listSearch[i].s + '" data-id="' + listSearch[i].i + '" data-type="' + listSearch[i].t + '">' + decodeURIComponent(listSearch[i].v) + '</a></li>');
				}
			} else {
				form.listSearchEl.find('.center').hide();
			}
		},
		searchTyping: function(e) {
			if(e.keyCode == 13) {
				var currentActive = form.listSearchUl.find('.active');
				
				if(currentActive) {
					form.mapSearchEl.blur();
					currentActive.find('a').trigger('click');
					
					return;
				}
			}
			
			if(e.keyCode == 38 || e.keyCode == 40) {
				var storeText = form.listSearchUl.data('text');
				
				if(typeof storeText != 'string') {
					form.listSearchUl.data('text', form.mapSearchEl.val());
				}
				
				var items = form.listSearchUl.children();
				var direction = (e.keyCode == 40) ? 1 : -1;
				var currentActive = form.listSearchUl.find('.active');
				var currentActiveIndex = items.index(currentActive);
				var nextIndex = currentActiveIndex + direction;
				var totalItems = items.length;
				
				if(nextIndex == totalItems) {
					nextIndex = -1;
				} else if(nextIndex == -2) {
					nextIndex = totalItems - 1;
				}
				
				if(nextIndex == -1) {
					if(typeof storeText == 'string') {
						form.mapSearchEl.val(storeText);
					}
				} else {
					var nextActiveItem = items.eq(nextIndex).addClass('active');
					
					form.mapSearchEl.val(nextActiveItem.text());
				}
				
				currentActive.removeClass('active');
				
				return;
			}
			
			form.listSearchUl.data('text', false);

			var val = form.mapSearchEl.val().trim();
			var type = form.fields.filter(s.type).val();
			
	    	if(val != '') {
	        	
				if($.data(this, 'ajax')) {
					$.data(this, 'ajax').abort();
				}
				
				$.data(this, 'ajax', $.get('/api/v1/map/get', {v: val, t: type}, function(response){
					form.listSearchUl.html('');
					if(response.length) {
						for(var i = 0; i < response.length; i++) {
		        			var area = response[i];
		        			
		        			form.listSearchUl.append('<li><a class="search-item" href="javascript:;" data-slug="' + area.slug + '" data-id="' + area.id + '" data-type="' + area.type + '">' + area.full_name + '</a></li>');
		        		}
						form.showSearchList();
						form.listSearchEl.find('.hint-wrap').hide();
					} else {
						// form.hideSearchList_();
					}
	        	}));
	    	} else {
	    		form.listSearchUl.html('');
				form.searchFocus();
	    	}
		},
		searchItemMouseEnter: function() {
			var currentActive = form.listSearchUl.find('.active');
			
			currentActive.removeClass('active');
			$(this).parent().addClass('active');
		},
		searchItemMouseLeave: function() {
			$(this).parent().removeClass('active');
		},
		searchItemClick: function() {
			var self = $(this);
			var val = self.text();
			var type = self.data('type');
			var id = self.data('id');
			var slug = self.data('slug');
			
			form.mapSearchEl.data('val', val).val(val);
			
			form.autoFill.val('').filter('#' + type + '_id').val(id);
			
			// Replace actionId
			$segments = actionId.split('/');
			actionId = '/' + $segments[1] + '/' + slug;
			
			// push search history
			
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
				
				listSearch.unshift({v: encodeURIComponent(val), i: id, t: type, s: slug});
			}
			
			setCookie('sh1', JSON.stringify(listSearch));
		},
		showSearchList: function() {
			if(form.listSearchEl.hasClass('hide')) {
				form.listSearchEl.removeClass('hide');
				$(document).on('click', form.hideSearchList);
			}
		},
		hideSearchList: function(e) {
			var target = $(e.target);
			
			if(target.hasClass('search-item') || target.closest('#map-search-wrap').length == 0) {
				form.hideSearchList_();
			}
		},
		hideSearchList_: function() {
			form.listSearchEl.addClass('hide');
			form.listSearchUl.html('');
			$(document).off('click', form.hideSearchList);
			form.mapSearchEl.val(form.mapSearchEl.data('val'));
		},
		preventEnterSubmit: function(e) {
			if(e.keyCode == 13) {
				var childs = form.listSearchUl.children();
				
				if(childs.length) {
					childs.eq(0).find('.search-item').trigger('click');
				}
				
				e.preventDefault();
				return false;
			}
		}
	};
	
	form.fields = form.el.find('select:not(.exclude), input:not(.exclude)');
	form.fields.on('change', getShowNumFrm);

	events.attachMobileEvent(form.el, 'submit', form.filterFields);
	events.attachMobileEvent(form.fields.filter('#order_by'), 'change', form.sortSubmit);
	
	form.listSearchUl = form.listSearchEl.find('ul');
	
	form.mapSearchEl.on('focus', form.searchFocus).on('keyup', form.searchTyping).on('keydown', form.preventEnterSubmit);
	form.listSearchEl.on('click', 'a', form.searchItemClick).on('mouseenter', 'a', form.searchItemMouseEnter).on('mouseleave', 'a', form.searchItemMouseLeave);
	
	/*
	 * Desktop
	 */
	desktop = {
		isLoadedResources: false,
		countLoadedResource: 0,
		checkToEnable: function() {
			if(desktop.isDesktop() && !desktop.isEnabled) {
				desktop.enable();
			} else if(!desktop.isDesktop() && desktop.isEnabled) {
				desktop.disable();
			}
		},
		isDesktop: function() {
			return $('.m-header').css('display') == 'none';
		},
		enable: function() {
			desktop.isEnabled = true;

			events.detachMobileEvents();
			
			if(!desktop.isLoadedResources) {
				desktop.isLoadedResources = true;
				desktop.loadResources();
			}
		},
		disable: function() {
			desktop.isEnabled = false;

			events.detachDesktopEvents();
		},
		loadResources: function() {
			var head = document.getElementsByTagName('head')[0];

			for(var i in resources) {
				var script = document.createElement('script');
				script.src = resources[i];
				head.appendChild(script);
			}
		},
		loadedResource: function() {
			desktop.countLoadedResource++;
			
			if(desktop.countLoadedResource == resources.length) {
				m2Map.initMap();
			}
		}
	};
	
	desktop.checkToEnable();
	
	$window.on('resize', desktop.checkToEnable);
	
	/*
	 * Tracking
	 */
	var payload = form.fields.filter(function () {
        return !!this.value;
    }).serialize();
	
	var data = {
		location: form.mapSearchEl.val(),
		payload: payload,
		tf: tf,
		referer: referer
	};

	tracking(data);
	
	
	/*
	 * Migrate code
	 */
	toogleScroll();
	getShowNumFrm();
	
	$window.on('resize', toogleScroll);
	
	$('.advande-search').toggleShowMobi({
        btnEvent: '.btn-submit',
        itemToggle: '.toggle-search'
    });
	
	$('.dropdown-common').dropdown({
		txtAdd: true,
		styleShow: 0
	});
	
	$('#type').on('change', function () {
		$('.select-price .val-selected div span').hide();
			
		$('.select-price .box-dropdown').price_dt({
			rebuild: true
		});

		if ( $(this).val() == 1 ) {
			$('.select-price .box-dropdown').price_dt({
				hinhthuc: 'mua'
			});
		}else if ( $(this).val() == 2 ) {
			$('.select-price .box-dropdown').price_dt({
				hinhthuc: 'thue'
			});
		}else {
			$('.select-price .box-dropdown').price_dt();
		}
	});

	var typeGet = $('#type').val();
	if ( typeGet == 1 ) {
		$('.select-price .box-dropdown').price_dt({
			hinhthuc: 'mua'
		});
	}else if ( typeGet == 2 ) {
		$('.select-price .box-dropdown').price_dt({
			hinhthuc: 'thue'
		});
	}else {
		$('.select-price .box-dropdown').price_dt();
	}
	
	$('.select-dt .box-dropdown').price_dt();
});

function tracking(data) {
	data.im = im;
	data._csrf = yii.getCsrfToken();
	
	$.ajax({
		method: "POST",
		url: '/listing/tracking',
		data: data
	});
}

function toogleScroll() {
	var hFrm = $('#search-form').outerHeight(),
	hWrap = $('.wrap-listing-item .inner-wrap').outerHeight();
	$('.wrap-listing').css('height',(hWrap-hFrm)+'px');
}

function getShowNumFrm(e) {
	if (!e) {
		$('.show-num-frm').each(function () {
			var _this = $(this), count = 0;
			_this.find('.val-selected .selected').find('span').remove();
			_this.find(':input').not('input[type=hidden]').each(function () {
				if ( $(this).val() != '' ) {
					count += 1;
				}
			});
			if ( count > 0 )
				_this.find('.val-selected .selected').append('<span style="display: inline-block;padding-left:5px;">('+count+')</span>');
		});
		return;
	}
	
	el = $(this);
	
	if ( el.closest('.show-num-frm').length > 0 ) {
		var count = 0;

		el.closest('.show-num-frm').find(':input').not('input[type=hidden]').each(function () {
			if ( $(this).val() != '' ) {
				count += 1;
				el.closest('.show-num-frm').find('.val-selected .selected').find('span').remove();
				el.closest('.show-num-frm').find('.val-selected .selected').append('<span style="display: inline-block;padding-left:5px;">('+count+')</span>');
			}
			if ( count == 0 ) {
				el.closest('.show-num-frm').find('.val-selected .selected').find('span').remove();
			}
		});
	}
}