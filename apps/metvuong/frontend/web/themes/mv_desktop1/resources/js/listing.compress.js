function setCookie(cname, cvalue, exdays) {if(typeof exdays === 'undefined') {document.cookie = cname + "=" + cvalue + "; path=/";} else {var d = new Date();d.setTime(d.getTime() + (exdays*24*60*60*1000));var expires = "expires="+d.toGMTString();document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";}}
function getCookie(cname) { var name = cname + "="; var ca = document.cookie.split(';');for(var i=0; i<ca.length; i++) { var c = ca[i]; while (c.charAt(0)==' ') c = c.substring(1);if (c.indexOf(name) != -1) return c.substring(name.length,c.length); } return "";}

var desktop, form, events;
var $window = $(window);
var s = {type: "#type"};

$(document).ready(function() {
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
			form.fields.filter(function () {
		        return !this.value;
		    }).prop('disabled', true);
		},
		sortSubmit: function() {
			form.el.submit();
		},
		searchFocus: function(e) {
			form.mapSearchEl.val('');
			form.showSearchList();
			form.listSearchEl.find('.hint-wrap').show();
			
			var searchHistory = getCookie('sh');
			
			if(searchHistory) {
				form.listSearchEl.find('.center').show();
				
				var listSearch = JSON.parse(searchHistory);
				var lsLength = listSearch.length;
				
				for(var i = 0; i < lsLength; i++) {
					form.listSearchUl.append('<li><a data-history="1" class="search-item" href="javascript:;" data-id="' + listSearch[i].i + '" data-type="' + listSearch[i].t + '">' + listSearch[i].v + '</a></li>');
				}
			} else {
				form.listSearchEl.find('.center').hide();
			}
		},
		searchBlur: function() {
			form.mapSearchEl.val(form.mapSearchEl.data('val'));
		},
		searchTyping: function() {
			var val = form.mapSearchEl.val().trim();
			var type = form.fields.filter(s.type).val();
			
	    	if(val.length > 1) {
	        	
				if($.data(this, 'ajax')) {
					$.data(this, 'ajax').abort();
				}
				
				$.data(this, 'ajax', $.get('/api/v1/map/get', {v: val, t: type}, function(response){
					form.listSearchUl.html('');
					if(response.length) {
						for(var i = 0; i < response.length; i++) {
		        			var area = response[i];
		        			
		        			form.listSearchUl.append('<li><a class="search-item" href="javascript:;" data-id="' + area.id + '" data-type="' + area.type + '">' + area.full_name + '</a></li>');
		        		}
						form.showSearchList();
						form.listSearchEl.find('.hint-wrap').hide();
					} else {
						form.hideSearchList_();
					}
	        	}));
	    	} else {
				form.hideSearchList_();
	    	}
		},
		searchItemClick: function() {
			var self = $(this);
			var val = self.text();
			var type = self.data('type');
			var id = self.data('id');
			
			form.mapSearchEl.data('val', val).val(val);
			
			form.autoFill.val('').filter('#' + type + '_id').val(id);
			
			// push search history
			
			if(!self.data('history')) {
				var searchHistory = getCookie('sh');
				var listSearch = searchHistory ? JSON.parse(searchHistory) : [];
				var isExist = false;
				
				for(var i = 0; i < listSearch.length; i++) {
					var sli = listSearch[i];
					
					if(sli.i == id && sli.t == type) {
						isExist = true;
						break;
					}
				}
				
				if(!isExist) {
					if(listSearch.length > 4) {
						listSearch.pop();
					}
					
					listSearch.unshift({v: val, i: id, t: type});
					
					setCookie('sh', JSON.stringify(listSearch));
				}
			}
		},
		searchListMouseEnter: function() {
			form.mapSearchEl.off('blur', form.searchBlur);
		},
		searchListMouseLeave: function() {
			form.mapSearchEl.on('blur', form.searchBlur);
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
		},
		preventEnterSubmit: function(e) {
			if(e.keyCode == 13) {
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
	
	form.mapSearchEl.on('focus', form.searchFocus).on('keyup', form.searchTyping).on('blur', form.searchBlur).on('keydown', form.preventEnterSubmit);
	form.listSearchEl.on('click', 'a', form.searchItemClick);
	form.listSearchEl.on('mouseenter', form.searchListMouseEnter).on('mouseleave', form.searchListMouseLeave);
	
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

	var typeGet = $('.select-others #type').val();
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