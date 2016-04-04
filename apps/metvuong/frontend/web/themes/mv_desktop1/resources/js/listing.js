var desktop, form, events;

$(document).ready(function(){
	codeMigrate();
	
	form = {
		el: $('#search-form'),
		fields: {},
		init: function() {
			form.el.find('select, input').each(function(){
				var self = $(this);
				
				form.fields[camel(self.attr('name'))] = self;
			});
			
			events.attachMobileEvent(form.el, 'submit', form.filterFields);
			events.attachMobileEvent(form.fields.orderBy, 'change', form.sortSubmit);
			
			form.commonEvents();
		},
		filterFields: function(e) {
			for(var i in form.fields) {
				if(!form.fields[i].val()) {
					form.fields[i].prop('disabled', true);
				}
			}
		},
		sortSubmit: function() {
			form.el.submit();
		},
		commonEvents: function(){
			form.fields.categoryId.on('change', function(){
				var projectEl = $('#select2-' + form.fields.projectBuildingId.attr('id') + '-container').closest('.select2-container');
				
				if($(this).val() == CATEGORY_CHCK) {
					projectEl.removeClass('hide');
				} else {
					projectEl.addClass('hide');
				}
			});
			
			form.fields.cityId.on('change', function(){
				form.resetLocation();
				
				$.get('/ad/list-district', {cityId: $(this).val()}, function(districts){
					form.appendDropdown(form.fields.districtId, districts);
				});
			});
			
			form.fields.districtId.change(function(){
				form.resetLocation();
				
				$.get('/ad/list-swp', {districtId: $(this).val()}, function(response){
					form.appendDropdown(form.fields.wardId, response.wards);
					form.appendDropdown(form.fields.streetId, response.streets);
					form.appendDropdown(form.fields.projectBuildingId, response.projects);
				});
			});
		},
		appendDropdown: function(el, items) {
			el.find("option:not(:first-child)").remove();
			for(var i in items) {
				el.append('<option value="' + items[i]['id'] + '">' + items[i]['name'] + '</option>');
			}
			
			$('#select2-' + el.attr('id') + '-container').text(el.find("option:first-child").text());
		},
		resetLocation: function() {
			form.appendDropdown(form.fields.wardId, []);
			form.appendDropdown(form.fields.streetId, []);
			form.appendDropdown(form.fields.projectBuildingId, []);
		}
	};
	
	desktop = {
		init: function() {
			desktop.checkToEnable();
			
			$(window).on('resize', desktop.checkToEnable);
		},
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
				script.onload = desktop.loadedResource;
				head.appendChild(script);
			}
		},
		loadedResource: function() {
			desktop.countLoadedResource = desktop.countLoadedResource ? desktop.countLoadedResource + 1 : 1;
			
			if(desktop.countLoadedResource == resources.length) {
				if(typeof listing !== 'undefined') {
					listing.loadedAllResources();
				}
			}
		}
	};
	
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

	form.init();
	desktop.init();
	
	$('.style-common').each(function(){
		var self = $(this);
		
		$(this).select2({
			width: '100%',
			dropdownParent: self.parent()
		});
		
		if(self.hasClass('hide')) {
			$('#select2-' + self.attr('id') + '-container').closest('.select2-container').addClass('hide');
		}
	});
	$('.select-duan .select2').each(function(){
		var self = $(this);
		
		$(this).select2({
			width: '100%',
			dropdownCssClass: 'select2-duan',
			dropdownParent: self.parent()
		});
		
		if(self.hasClass('hide')) {
			$('#select2-' + self.attr('id') + '-container').closest('.select2-container').addClass('hide');
		}
	});
});

function codeMigrate() {
	toogleScroll();
	
	$(window).on('resize', function () {
		toogleScroll();
	});
	
	function toogleScroll() {
		var hFrm = $('#search-form').outerHeight(),
		hWrap = $('.wrap-listing-item .inner-wrap').outerHeight();
		$('.wrap-listing').css('height',(hWrap-hFrm)+'px');
	}
	
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
}

function count(obj) {
	var j = 0;
	for(var i in obj) {
		j++;
	}
	return j;
}

function camel(str) {
	return str.replace(/_([a-z])/g, function (g) { return g[1].toUpperCase(); });
}
/*
var form, desktop, listing;

$(document).ready(function(){
	if ( !checkMobile() ) {
		$(window).on('resize', function () {
			var hFrm = $('#search-form').outerHeight(),
				hWrap = $('.wrap-listing-item .inner-wrap').outerHeight();
			$('.wrap-listing').css('height',(hWrap-hFrm)+'px');
		}).trigger('resize');
	}
	
	desktop = {
		isLoadResources: false,
		init: function() {
			desktop.checkToEnable();
			
			$(window).on('resize', desktop.checkToEnable);
		},
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

			if(!desktop.isLoadResources) {
				desktop.loadResources();
			}
			
			desktop.desktopEvent();

			desktop.countFrom = $('#count-from');
			desktop.countToEl = $('#count-to');
			desktop.countTotal = $('#count-total');
			desktop.loadingList = $('#loading-list');
			
			desktop.limit = Number(desktop.countToEl.text()) - Number(desktop.countFrom.text()) + 1;
		},
		disable: function() {
			desktop.isEnabled = false;

			desktop.mobileEvent();
		},
		loadResources: function() {
			desktop.loadingResource = count(resources) + 1;
			
			desktop.isLoadResources = true;
			
			// get form data use for map
			form.getData(function(r){
				form.data = r;
				
				desktop.loadedResource();
			});
			
			// lazy load javascript src
			var head = document.getElementsByTagName('head')[0];

			for(var i = 0; i < resources.length; i++) {
				var script = document.createElement('script');
				script.src = resources[i];
				script.onload = desktop.loadedResource;
				
				head.appendChild(script);
			}
		},
		loadedResource: function() {
			desktop.loadingResource--;

			if(!desktop.loadingResource) {
				listingMap.init();
			}
		},
		desktopEvent: function() {
			listing.desktopEvent();
			form.desktopEvent();
		},
		mobileEvent: function() {
			listing.mobileEvent();
			form.mobileEvent();
		}
	};
	
	listing = {
		el: $('#listing-list'),
		init: function() {
			// Load default attach event for mobile here
		},
		desktopEvent: function() {
			listing.wrapListingEl = $('.wrap-listing');
			
			listing.el.on('click', '.item-listing a', listing.detailEvent);
			listing.wrapListingEl.on('scroll', listing.moreEvent);
			
			$('.btn-close').on('click', function(e){
				e.preventDefault();
				
				listing.closeDetail();
			});
		},
		mobileEvent: function() {
			listing.el.off('click', '.item-listing a', listing.detailEvent);
			listing.wrapListingEl.off('scroll', listing.moreEvent);
		},
		detailEvent: function(e) {
			e.preventDefault();
			listing.detail(Number($(this).data('id')));
		},
		detail: function(id) {
			listing._detail(id);
		},
		_detail: function(id) {
			var wWrapList = $('.detail-listing-dt').outerWidth();
			var detailListing = $('.detail-listing');
			var wrapDetailListing = $('.detail-listing-dt');
			
			wrapDetailListing.loading({full: false});
			
			$('.detail-listing-dt').css({
				left: -wWrapList +'px'
			});
			
			$.get('/ad/detail', {id: id}, function(r){
				
				detailListing.find('.container').html($(r).find('#wrapper').find('.detail-listing .col-left').html());

				var swiper = new Swiper('.detail-listing-dt .swiper-container', {
					pagination: '.swiper-pagination',
					paginationClickable: true,
			        spaceBetween: 0,
			        nextButton: '.swiper-button-next',
			        prevButton: '.swiper-button-prev'
			    });

				wrapDetailListing.loading({done: true});
			});
		},
		closeDetail: function() {
			$('.detail-listing-dt').css({
				left: '0px'
			});
		},
		moreEvent: function(e) {
			var self = $(this);
			
			if(self.scrollTop() >= (self.get(0).scrollHeight - 200 - self.outerHeight())) {
	    		listing.more();
	    	}
		},
		more: function() {
			if(desktop.loadingList.hasClass('hide')) {
				
				var offset = Number(desktop.countToEl.text());
				
				if(offset < Number(desktop.countTotal.text())) {
					desktop.loadingList.removeClass('hide');
					
					var hiddenFieldWrap = $('<div></div>');
					var page = offset/desktop.limit + 1;
					
					hiddenFieldWrap.html('<input type="hidden" name="page" value="' + page + '" />');
					
					form.el.append(hiddenFieldWrap);
					
					form.getData(function(r) {
						listing.el.find('> ul').append(r);
						desktop.countToEl.text(offset + $(r).filter('li').length);
						
						desktop.loadingList.addClass('hide');
					});
					
					hiddenFieldWrap.remove();
				}
			}
		}
	};
	
	form = {
		el: $('#search-form'),
		init: function() {

			form.getShowNumFrm();

			codeMigrate();
			
			form.attachEvent();
			
			// Load default attach event for mobile here
			form.orderByEl = $('#order_by');
			
			form.el.on('submit', form.filterFields);
			form.orderByEl.on('change', form.sortSubmit);
		},
		attachEvent: function() {
			form.cityEl = $('#city_id');
			form.districtEl = $('#district_id');
			form.wardEl = $('#ward_id');
			form.streetEl = $('#street_id');
			form.projectEl = $('#project_building_id');
			
			form.cityEl.change(function(){
				$.get('/ad/list-district', {cityId: $(this).val()}, function(districts){
					form.appendDropdown(form.districtEl, districts);
					
					form.appendDropdown(form.wardEl, []);
					form.appendDropdown(form.streetEl, []);
					form.appendDropdown(form.projectEl, []);
				});
			});
			
			form.districtEl.change(function(){
				$.get('/ad/list-swp', {districtId: $(this).val()}, function(response){
					form.appendDropdown(form.wardEl, response.wards);
					form.appendDropdown(form.streetEl, response.streets);
					form.appendDropdown(form.projectEl, response.projects);
					if ( response.wards.length == 0 ) {
						form.getShowNumFrm();
					}
				});
			});
		},
		appendDropdown: function(el, items) {
			el.find("option:not(:first-child)").remove();
			for(var i in items) {
				el.append('<option value="' + i + '">' + items[i] + '</option>');
			}
			
			if(!items.length) {
				$('#select2-' + el.attr('id') + '-container').text(el.find("option:first-child").text());
			}
		},
		filterFields: function(e) {
			form.el.find('select, input').filter(function(){
				return !this.value;
			}).prop('disabled', true);
		},
		sortSubmit: function() {
			form.el.submit();
		},
		desktopEvent: function() {
			form.el.off('submit', form.filterFields);
			form.orderByEl.off('change', form.sortSubmit);
			
			form.el.find('input, select').each(function(){
				$(this).on('change', form.fieldChange);
			});
		},
		mobileEvent: function() {
			form.el.on('submit', form.filterFields);
			form.orderByEl.on('change', form.sortSubmit);
			
			form.el.find('input, select').each(function(){
				$(this).off('change', form.fieldChange);
			});
		},
		fieldChange: function(e) {
			console.log('field change');
			form.getShowNumFrm($(e.target));
			form.formChange();
		},
		formChange: function() {
			form._formChange(function(r){
				form.updateList(r.list, count(r.products));
			});
		},
		_formChange: function(fn) {
			listing.el.find('> ul').html('');
			desktop.loadingList.removeClass('hide');
			
			var hiddenFieldWrap = $('<div></div>');
			
			hiddenFieldWrap.html('<input type="hidden" name="update" value="1" />');
			
			form.el.append(hiddenFieldWrap);
			
			form.getData(function(r) {
				fn(r);

				desktop.loadingList.addClass('hide');
			});
			
			hiddenFieldWrap.remove();
		},
		updateList: function(list, total) {
			desktop.countFrom.text(1);
			desktop.countTotal.text(total);
			desktop.countToEl.text($(list).filter('li').length);
			
			listing.el.find('> ul').html(list);
		},
		getShowNumFrm: function (el) {

			if ( el == undefined ) {
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
		},
		getData: function(callback) {
			$.get(form.el.attr('action'), form.el.serialize(), function(r){
				callback(r);
			});
		}
	};
	
	form.init();
	desktop.init();
	listing.init();
	
	$('.style-common').each(function(){
		var self = $(this);
		
		$(this).select2({
			width: '100%',
			dropdownParent: self.parent()
		});
		
		if(self.hasClass('hide')) {
			$('#select2-' + self.attr('id') + '-container').closest('.select2-container').addClass('hide');
		}
	});
	$('.select-duan .select2').each(function(){
		var self = $(this);
		
		$(this).select2({
			width: '100%',
			dropdownCssClass: 'select2-duan',
			dropdownParent: self.parent()
		});
		
		if(self.hasClass('hide')) {
			$('#select2-' + self.attr('id') + '-container').closest('.select2-container').addClass('hide');
		}
	});
});

function codeMigrate() {
	$('.advande-search').toggleShowMobi({
        btnEvent: '.btn-submit',
        itemToggle: '.toggle-search'
    });
	
	$('.dropdown-common').dropdown({
		txtAdd: true,
		styleShow: 0,
		funCallBack: function (item) {
			if(item.closest('ul').hasClass('loai-bds')) {
				if(item.data('value') == CATEGORY_CHCK) {
					$('#select2-' + form.projectEl.attr('id') + '-container').closest('.select2-container').removeClass('hide');
				} else {
					$('#select2-' + form.projectEl.attr('id') + '-container').closest('.select2-container').addClass('hide');
				}
			}
		}
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
}

function count(obj) {
	var j = 0;
	for(var i in obj) {
		j++;
	}
	return j;
}

*/