var form, desktop, listing;


//}).on('mouseenter', '.item-listing a', function(e){
//	var id = $(this).data('id');
//	
//	$.data(this, 'mouseenterTimer', setTimeout(function() {
//		if(map.currentState == 'detail') {
//			var marker = map.getMarker(id);
//			marker.setZIndex(google.maps.Marker.MAX_ZINDEX++);
//			marker.setIcon(map.icon(marker.get('ids').length, 1));
//			
//			var position = marker.getPosition();
//			
//			if(!map.gmap.getBounds().contains(position)) {
//				map.gmap.setCenter(position);
//			}
//		} else {
//			var marker = map.getMarkerGroup(id);
//			
//			if(marker) {
//				marker.setZIndex(google.maps.Marker.MAX_ZINDEX++);
//				marker.setIcon(map.icon(marker.get('ids').length, 1));
//				
//				var position = marker.getPosition();
//				
//				if(!map.gmap.getBounds().contains(position)) {
//					map.gmap.setCenter(position);
//				}
//			}
//		}
//    }, 300));
//}).on('mouseleave', '.item-listing a', function(e){
//	clearTimeout($.data(this, 'mouseenterTimer'));
//	if(map.currentState == 'detail') {
//		var marker = map.getMarker($(this).data('id'));
//		marker.setIcon(map.icon(marker.get('ids').length, 0));
//	} else {
//		var marker = map.getMarkerGroup($(this).data('id'));
//		
//		if(marker) {
//			marker.setIcon(map.icon(marker.get('ids').length, 0));
//		}
//	}
//});



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

/*

var mapEl;
var searchForm;
var submitButton;
var listingList;

var map = {
	data: null,
	gmap: null,
	status: 0,
	currentState: null,
	focusState: null,
	infoWindow: null,
	polygons: [],
	markers: {},
	groupMarkers: [],
	gmapV2Loaded: function() {
		map.status += 1;
		map.allLoaded();
	},
	apiLoaded: function() {
		map.status += 1;
		map.allLoaded();
	},
	dataLoaded: function() {
		map.status += 1;
		map.allLoaded();
	},
	allLoaded: function() {
		if(map.status == 3) {
			m2Map.loaded();
			map.initMap();

			for(var i in map.data.products) {
				var product = map.data.products[i];
				var index = product.lat + '-' + product.lng;
				var marker = map.markers[index];
				
				if(marker) {
					var ids = marker.get('ids');
					ids.push(product.id);
					marker.set('ids', ids);
					marker.setIcon(map.icon(ids.length, 0));
				} else {
					marker = new google.maps.Marker({
						position: {lat: Number(product.lat), lng: Number(product.lng)},
						map: null,
					    icon: map.icon(1, 0)
					});
					
					marker.set('ids', [product.id]);
					marker.addListener('mouseover', map.markerOver);
					marker.addListener('mouseout', map.markerOut);
					marker.addListener('click', map.markerClick);
					
					map.markers[index] = marker;
				}
			}
		}
	},
	markerOver: function() {

		this.setZIndex(google.maps.Marker.MAX_ZINDEX++);
		var ids = this.get('ids');
		var id = ids[0];
		
		var product = map.data.products[id];
		var infoContent = $('<div class="info-wrap-single">' + map.buildInfoContent(product) + '</div>');
		
//		if(ids.length > 1) {
//			infoContent.append('<div class="more">...</div>');
//		}
		
		infoContent.append('<div class="arrow"></div>');
		
		map.infoWindow.setContent(infoContent.get(0));
		map.infoWindow.open(this);
	},
	markerOut: function() {
		map.infoWindow.close();
	},
	markerClick: function() {
		var ids = this.get('ids');
		
		if(ids.length == 1) {
			map.detail(ids[0]);
		} else {
			var infoContentWrap = $('<div class="info-wrap-multiple"></div>');
			for(i = 0; i < ids.length; i++) {
				var id = ids[i];
				var product = map.data.products[id];
				var infoContent = $(map.buildInfoContent(product));
				map.attachInfoItemClick(infoContent, id);
				infoContentWrap.append(infoContent);
			}
			
			map.infoWindowMore.setContent(infoContentWrap.get(0));
			map.infoWindowMore.open(map.gmap, this);
		}
	},
	attachInfoItemClick: function(item, id) {
		item.click(function(){
			map.detail(id);
		});
	},
	detail: function(id) {
		var wWrapList = $('.detail-listing-dt').outerWidth();
		var detailListing = $('.detail-listing');
		var wrapDetailListing = $('.detail-listing-dt');
		
		wrapDetailListing.loading({full: false});
		
		$('.detail-listing-dt').css({
			left: -wWrapList +'px'
		});
		
		$.get('/ad/detail', {id: id}, function(r){
			
			detailListing.find('.container').html($(r).find('#wrapper').find('.detail-listing .container').html());

			var swiper = new Swiper('.swiper-container', {
				pagination: '.swiper-pagination',
				paginationClickable: true,
		        spaceBetween: 0
		    });

			wrapDetailListing.loading({done: true});
		});
	},
	closeDetail: function() {
		$('.detail-listing-dt').css({
			left: '0px'
		});
	},
	initMap: function() {
		map.currentState = map.focusState = map.getCurrentState(initialZoom);
		
		if(map.currentState == 'ward') {
			var area = map.data.wards[areaId];
		} else if(map.currentState == 'district') {
			var area = map.data.districts[areaId];
		} else {
			var area = map.data.city;
		}
		
		var center = JSON.parse(area.center);
		
		var options = {center: {lat: center[0], lng: center[1]}, zoom: initialZoom};
		map.gmap = new google.maps.Map(document.getElementById('map'), options);
		
		map.infoWindow = new m2Map.InfoWindow({offsetTop: 40});

		map.infoWindowMore = new google.maps.InfoWindow();
		map.infoWindowMore.addListener('domready', function() {
			map.infoWindow.close();
			var marker = map.infoWindowMore.anchor;
			google.maps.event.clearListeners(marker, 'mouseover');
			
			var infoWindowClick = google.maps.event.addListener(map.infoWindowMore, 'closeclick', detachEvent);
			var infoWindowPositionChange = google.maps.event.addListener(map.infoWindowMore, 'position_changed', detachEvent);
			
			var mapClick = google.maps.event.addListener(map.gmap, 'mousedown', function(){
				map.infoWindowMore.close();
				detachEvent();
			});
			
			function detachEvent() {
				marker.addListener('mouseover', map.markerOver);
				google.maps.event.removeListener(infoWindowClick);
				google.maps.event.removeListener(infoWindowPositionChange);
				google.maps.event.removeListener(mapClick);
			}
		});
		
		map.drawPolygon(area);
		
		var counters = map.getCounterByArea(map.currentState);
		
		if(area.center && typeof counters[area.id] !== 'undefined') {
			map.drawPolygonMarker(area, counters[area.id]);
		}
		
		map.gmap.addListener('zoom_changed', function(){
			var state = map.getCurrentState(map.gmap.getZoom());
			
			if(map.currentState != state) {
				map.currentState = state;
				
				if(map.currentState == 'detail') {
					for(var i in map.markers) {
						map.markers[i].setMap(map.gmap);
					}
					
					map.removePolygons();
					map.removeGroupMarkers();
				} else {
					map.removeDetailMarkers();
					if(map.currentState == 'ward') {
						
						map.removePolygons();
						map.removeGroupMarkers();
						
						var counters = map.getCounterByArea('ward');
						
						if(map.focusState == 'ward') {
							var area = map.data.wards[areaId];
							map.drawPolygon(area);
							if(area.center && typeof counters[areaId] !== 'undefined') {
								map.drawPolygonMarker(area, counters[areaId]);
							}
						} else {
							var wards = {};
							
							if(map.focusState == 'district') {
								for(var i in map.data.wards) {
									if(map.data.wards[i]['district_id'] == areaId) {
										wards[i] = map.data.wards[i];
									}
								}
							} else {
								wards = map.data.wards;
							}

							for(var i in wards) {
								var area = map.data.wards[i];
								if(area.geometry) {
									map.drawPolygon(area);
								}
								if(area.center && typeof counters[i] !== 'undefined') {
									map.drawPolygonMarker(area, counters[i]);
								}
							}
						}
					} else if(map.currentState == 'district' && map.focusState != 'ward') {
						
						map.removePolygons();
						map.removeGroupMarkers();
						
						var counters = map.getCounterByArea('district');
						
						if(map.focusState == 'district') {
							var area = map.data.districts[areaId];
							map.drawPolygon(area);
							if(area.center && typeof counters[areaId] !== 'undefined') {
								map.drawPolygonMarker(area, counters[areaId]);
							}
						} else {
							for(var i in map.data.districts) {
								var area = map.data.districts[i];
								if(area.geometry) {
									map.drawPolygon(area);
								}
								if(area.center && typeof counters[i] !== 'undefined') {
									map.drawPolygonMarker(area, counters[i]);
								}
							}
						}
						
						
					} else {
						if(map.focusState != 'ward' && map.focusState != 'district') {
							
							map.removePolygons();
							map.removeGroupMarkers();
							
							var counters = map.getCounterByArea('city');
							map.drawPolygon(map.data.city);
							
							if(map.data.city.center && typeof counters[map.data.city.id] !== 'undefined') {
								map.drawPolygonMarker(map.data.city, counters[map.data.city.id]);
							}
						}
					}
				}
			}
		});
	},
	getCounterByArea: function(area) {
		var counters = {};
		
		for(var i in map.data.products) {
			var product = map.data.products[i];
			var areaId = product[area + '_id'];
			
			if(counters[areaId]) {
				counters[areaId]['total'] = counters[areaId]['total'] + 1;
			} else {
				counters[areaId] = {
					total: 1,
					ids: []
				};
			}
			
			counters[areaId]['ids'].push(i);
		}
		
		return counters;
	},
	getCurrentState: function(zoom) {
		for(var i in zoomLevel) {
			if(zoom >= zoomLevel[i].min && zoom <= zoomLevel[i].max) {
				return i;
			}
		}
	},
	drawPolygonMarker: function(area, counters) {
		var center = JSON.parse(area.center);
		center = {lat: center[0], lng: center[1]};
		
		var marker = new google.maps.Marker({
			position: center,
			map: map.gmap,
		    icon: map.icon(counters['total'], 0)
		});
		
		marker.set('ids', counters['ids']);
		
		var name = area.pre ? area.pre + ' ' + area.name : area.name;
		
		marker.addListener('mouseover', function(){
			if(counters['total'] > 9999) {
				map.infoWindow.setOffsetTop(60);
			} else if(counters['total'] > 999) {
				map.infoWindow.setOffsetTop(50);
			}
			
			var infoContent = '<div class="info-wrap-single"><div style="padding: 6px 12px; font-weight: bold; font-size: 13px; white-space: nowrap">' + name + '</div><div class="arrow"></div></div>';
			map.infoWindow.setContent(infoContent);
			map.infoWindow.open(this);
		});
		
		marker.addListener('mouseout', function(){
			map.infoWindow.close();
			map.infoWindow.setOffsetTop(40);
		});

		marker.addListener('click', function(){
			
			var state = map.getCurrentState(map.gmap.getZoom());

			if(state == 'ward') {
				map.gmap.setZoom(zoomLevel.detail.min);
			} else if(state == 'district') {
				map.gmap.setZoom(zoomLevel.ward.min);
			} else {
				map.gmap.setZoom(zoomLevel.district.min);
			}
			
			map.gmap.setCenter(center);
		});
		
		map.groupMarkers.push(marker);
	},
	drawPolygon: function(area) {
		var color = '#00a769';
		var geometries = JSON.parse(area.geometry);
		
		for(var i = 0; i < geometries.length; i++) {
			var triangleCoords = [];
			var geometry = geometries[i];
			
			for(var j = 0; j < geometry.length; j++) {
				triangleCoords.push(new google.maps.LatLng(geometry[j][0], geometry[j][1]));
			}
			var polygon = new google.maps.Polygon({
			    paths: triangleCoords,
			    strokeColor: color,
			    strokeOpacity: 0.8,
			    strokeWeight: 1,
			    fillColor: color,
			    fillOpacity: 0.2,
			    clickable: false
			});

			polygon.setMap(map.gmap);
			map.polygons.push(polygon);
		}
	},
	removePolygons: function() {
		for(var i = 0; i < map.polygons.length; i++) {
			var polygon = map.polygons[i];
			polygon.setMap(null);
		}
		
		map.polygons = [];
	},
	removeGroupMarkers: function() {
		for(var i = 0; i < map.groupMarkers.length; i++) {
			var marker = map.groupMarkers[i];
			marker.setMap(null);
		}
		
		map.groupMarkers = [];
	},
	removeDetailMarkers: function() {
		for(var i in map.markers) {
			map.markers[i].setMap(null);
		}
	},
	icon: function(counter, status) {
		var base = (counter > 1) ? '/site/map-image?s={status}&t=' + counter : '/images/marker-{status}.png';
		
		return base.replace('{status}', status);
	},
	buildInfoContent: function(product) {
		var img = map.getImageUrl(product);
		var price = formatPrice(product.price);
		var addition = map.getAdditionInfo(product);
		var address = map.getAddress(product);
		
		var infoContent = '<div class="infoContent">' + 
								'<div class="img-show"><div><img src="'+img+'"></div></div>' +
								'<div class="address">' + address + '</div>' +
								'<div class="price">' + price + '</div>' +
								'<div class="addition">' + addition + '</div></div>' +
							'</div>';
		return infoContent;
	},
	getImageUrl: function(product) {
		if(product.image_file_name) {
			if(product.image_folder) {
				return '/store/' + product.image_folder + '/480x360/' + product.image_file_name;
			} else {
				return product.image_file_name.replace('745x510', '350x280');
			}
		} else {
			return '/themes/metvuong2/resources/images/default-ads.jpg';
		}
	},
	getAdditionInfo: function(product) {
		var addition = [];
		
		addition.push(product.area + 'm<sup>2</sup>');
		
		if(product.floor_no && product.floor_no != '0') {
			addition.push(product.floor_no + ' ' + lajax.t('storey'));
		}
		if(product.room_no && product.room_no != '0') {
			addition.push(product.room_no + ' ' + lajax.t('beds'));
		}
		if(product.toilet_no && product.toilet_no != '0') {
			addition.push(product.toilet_no + ' ' + lajax.t('baths'));
		}

		return addition.join(' • ');
	},
	getAddress: function(product) {
		var city = dataCities[product.city_id];
		var district = city['districts'][product.district_id];
		var address = [];
		var homeStreet = '';

		if(product.home_no) {
			homeStreet += product.home_no;
		}

		if(product.street_id) {
			homeStreet = homeStreet ? homeStreet + ' ' : '';
			homeStreet += district['streets'][product.street_id]['pre'] + ' ' + district['streets'][product.street_id]['name'];
		}
		
		if(homeStreet) {
			address.push(homeStreet);
		}
		
		if(product.ward_id) {
			address.push(district['wards'][product.ward_id]['pre'] + ' ' + district['wards'][product.ward_id]['name']);
		}
		
		address.push(district['pre'] + ' ' + district['name']);
		address.push(city['name']);
		
		return address.join(', ');
	},
	getMarker: function(id) {
		for(var i in map.markers) {
			var marker = map.markers[i];
			var ids = marker.get('ids');

			if(ids.indexOf('' + id) != -1) {
				return marker;
			}
		}
	},
	getMarkerGroup: function(id) {
		for(var i in map.groupMarkers) {
			var marker = map.groupMarkers[i];
			var ids = marker.get('ids');

			if(ids.indexOf('' + id) != -1) {
				return marker;
			}
		}
		return false;
	}
}

$(document).ready(function(){
	searchForm = $('#search-form');
	submitButton = $('.btn-submit');
	listingList = $('#listing-list ul');
	mapEl = $('#map');
//	var map = {
//		gmap: null,
//		focus: 1,
//		focusLevel: {
//			CITY: 0,
//			DISTRICT: 1,
//			WARD: 2,
//			DETAIL: 3
//		},
//		zoom: [[0,11], [12,13], [14,15], [16,21]],
//		initMap: function() {
//			m2Map.initial(function(gmap) {
//				var options = {center: {lat: 10.783068, lng: 106.704825}, zoom: 15};
//				map.gmap = new google.maps.Map(document.getElementById('map'), options);
//			});
//		}
//	};
	
	
	
	function getListingLocation(callback) {
		$.get(searchForm.attr('action'), searchForm.serialize(), function(r){
			callback(r);
		});
	}
	
	var desktop = {
		isInit: false,
		isEnabled: false,
		enable: function() {
			this.isEnabled = true;
			
			if(!this.isInit) {
				this.isInit = true;
				
				mapEl.loading({full: false});
				
				getListingLocation(function(result){
					map.data = result;
					map.dataLoaded();
				});
				
				var head = document.getElementsByTagName('head')[0];
				
				var apiScript = document.createElement('script');
				apiScript.src = srcApi;
				head.appendChild(apiScript);
				
				var gmapV2Script = document.createElement('script');
				gmapV2Script.src = srcGmapV2;
				gmapV2Script.onload = map.gmapV2Loaded;
				head.appendChild(gmapV2Script);
			}
			
			listingList.on('click', '.item-listing a', function(e){
				e.preventDefault();
				
				map.detail($(this).data('id'));
			}).on('mouseenter', '.item-listing a', function(e){
				var id = $(this).data('id');
				
				$.data(this, 'mouseenterTimer', setTimeout(function() {
					if(map.currentState == 'detail') {
						var marker = map.getMarker(id);
						marker.setZIndex(google.maps.Marker.MAX_ZINDEX++);
						marker.setIcon(map.icon(marker.get('ids').length, 1));
						
						var position = marker.getPosition();
						
						if(!map.gmap.getBounds().contains(position)) {
							map.gmap.setCenter(position);
						}
					} else {
						var marker = map.getMarkerGroup(id);
						
						if(marker) {
							marker.setZIndex(google.maps.Marker.MAX_ZINDEX++);
							marker.setIcon(map.icon(marker.get('ids').length, 1));
							
							var position = marker.getPosition();
							
							if(!map.gmap.getBounds().contains(position)) {
								map.gmap.setCenter(position);
							}
						}
					}
			    }, 300));
			}).on('mouseleave', '.item-listing a', function(e){
				clearTimeout($.data(this, 'mouseenterTimer'));
				if(map.currentState == 'detail') {
					var marker = map.getMarker($(this).data('id'));
					marker.setIcon(map.icon(marker.get('ids').length, 0));
				} else {
					var marker = map.getMarkerGroup($(this).data('id'));
					
					if(marker) {
						marker.setIcon(map.icon(marker.get('ids').length, 0));
					}
				}
			});
			
			$('.btn-close').on('click', function(e){
				e.preventDefault();
				
				map.closeDetail();
			});
		},
		disable: function() {
			this.isEnabled = false;
			
			listingList.off('click', '.item-listing a').off('mouseenter', '.item-listing a').off('mouseleave', '.item-listing a');
			$('.btn-close').off('click');
		}
	};


	
	//$('.tinh-thanh').html('');
	$('.loai-bds').html('');
//	for ( var i in dataCities) {
//		var item = $('<li><a href="#" data-value="'+i+'" data-order="1" class="disable">'+dataCities[i].name+'</a></li>');
//		if ( i == "1" ) {
//			item.find('a').removeClass('disable');
//		}
//		$('.tinh-thanh').append(item);
//	}
	for ( var i in dataCategories) {
		var item = $('<li><a href="#" data-value="'+i+'" data-order="3">'+dataCategories[i].name+'</a></li>');
		$('.loai-bds').append(item);
	}
	
	var duAn = $('.du-an');
	
	$('#city_id').change(function(){
		$.get('/ad/list-district', {cityId: $(this).val()}, function(districts){
			appendDropdown($('#district_id'), districts);
			
			appendDropdown($('#ward_id'), []);
			appendDropdown($('#street_id'), []);
			duAn.html('');
		});
	});
	
	$('#district_id').change(function(){
		$.get('/ad/list-swp', {districtId: $(this).val()}, function(response){
			appendDropdown($('#ward_id'), response.wards);
			appendDropdown($('#street_id'), response.streets);
			
			for(projectId in response.projects) {
				var item = $('<li><a href="#" data-value="'+projectId+'">'+response.projects[projectId]+'</a></li>');
				duAn.append(item);
			}
		});
	});

	$('.dropdown-common').dropdown({
		txtAdd: true,
		styleShow: 0,
		funCallBack: function (item) {
			if(item.closest('ul').hasClass('loai-bds')) {
				if(item.data('value') == '6') {
					$('#du-an-select').removeClass('hide');
				} else {
					$('#du-an-select').addClass('hide');
				}
			}
			
			
//			var selectedCityList = $('<li data-value="'+item.data('value')+'" data-order="'+item.data('order')+'">'+item.text()+'<span class="icon arrow-left arrow-small"></span></li>');
//
//			if ( item.closest('.tinh-thanh').length > 0 || item.is('input[type=hidden]') ) {
//				var idTT = item.data('value') == undefined ? item.val() : item.data('value');
//
//				$('.quan-huyen').html('');
//				var txtDefault = $('.quan-huyen').closest('.box-dropdown').find('.val-selected').data('placeholder');
//				$('.quan-huyen').closest('.box-dropdown').find('.val-selected').text(txtDefault);
//
//				for ( var i in dataCities) {
//					if ( i == idTT ) {
//						for ( var j in dataCities[i].districts ) {
//							var item = $('<li><a href="#" data-value="'+j+'" data-order="2">'+dataCities[i].districts[j].name+'</a></li>');
//							$('.quan-huyen').append(item);
//						}
//						break;
//					}
//				}
//			}
		}
	});
	
	function appendDropdown(el, items) {
		el.find("option:not(:first-child)").remove();
		for(var i in items) {
			el.append('<option value="' + i + '">' + items[i] + '</option>');
		}
	}
	
	function loadProjects() {
		var districtId = $('#quan-huyen').val();
		var cityId = $('#tinh-thanh').val();
		
		var projects = dataCities[cityId]['districts'][districtId]['projects'];
		
		for(projectId in projects) {
			var item = $('<li><a href="#" data-value="'+projectId+'">'+projects[projectId].name+'</a></li>');
			$('.du-an').append(item);
		}
	}

	$('.select-others #type').on('change', function () {

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

	$('.advande-search').toggleShowMobi({
        btnEvent: '.btn-submit',
        itemToggle: '.toggle-search'
    });
//	var page = 1;
//	var listingListId = '#listing-list';
//	var listingListWrap = '#listing-list > ul';
//	var itemLoadingId = '#item-loading';
//	
//	if(!$(itemLoadingId).hasClass('hide')) {
//		$(window).on('scroll', next);
//	}
//	
//	function next() {
//		var self = $(this);
//		
//		if(self.scrollTop() >= (document.body.scrollHeight - 260 - self.height())) {
//			$(window).off('scroll', next);
//			
//			var inputPage = $('<input type="hidden" name="page" value="' + (++page) + '" />');
//			searchForm.append(inputPage);
//			
//			$.get(searchForm.attr('action'), searchForm.serialize(), function(r){
//				r = $(r);
//				
//				inputPage.remove();
//				
//				var items = r.find('.item-listing');
//				items.each(function() {
//					var item = $('<li></li>');
//					item.append($(this));
//					$(listingListWrap).append(item);
//				});
//				
//				var countTo = $('#count-to');
//				countTo.text(Number(countTo.text()) + items.length);
//				
//				if(r.find(itemLoadingId).hasClass('hide')) {
//					$(itemLoadingId).addClass('hide');
//				} else {
//					$(window).on('scroll', next);
//				}
//
//				if ( checkMobile() ) {
//					$('.wrap-lazy img').lazyload({
//						threshold : 200
//					});
//				}else {
//					$('.wrap-lazy img').lazyload({
//						threshold : 200,
//						container: $("#listing-list")
//					});
//				}
//				
//
//				$(".rippler").rippler({
//			        effectClass      :  'rippler-effect',
//			        effectSize      :  0,      // Default size (width & height)
//			        addElement      :  'div',   // e.g. 'svg'(feature)
//			        duration        :  400
//			    });
//			});
//		}
//	}
	
	$('.dropdown-select').dropdown({
		ajaxSubmit: function () {
			$('button.btn-submit').data('flag',false);
			searchForm.submit();
		}
	});
	
	searchForm.submit(function(e){
		searchForm.find('input, select').each(function(){
			var self = $(this);
			
			if(self.val() === "") {
				self.prop('disabled', true);
			}
		});

		return true;
//		e.preventDefault();
//
//		var btnSubmit = $(this).find('button.btn-submit');
//
//		if ( btnSubmit.data('flag') ) {
//			btnSubmit.data('flag', false);
//			return;
//		}else {
//			btnSubmit.data('flag', true);
//		}
//
//		$(window).off('scroll', next);
//		
//		$(itemLoadingId).removeClass('hide');
//		
//		var id = '#content-holder';
//		var contentHolder = $(id).html('');
//		
//		page = 1;
//		
//		$.get(searchForm.attr('action'), searchForm.serialize(), function(r){
//			r = $(r);
//			
//			contentHolder.html(r.find(id).html());
//			
//			if(r.find(itemLoadingId).hasClass('hide')) {
//				$(itemLoadingId).addClass('hide');
//			} else {
//				$(window).on('scroll', next);
//			}
//			
//			if ( checkMobile() ) {
//				$('.wrap-lazy img').lazyload({
//					threshold : 200
//				});
//			}else {
//				$('.wrap-lazy img').lazyload({
//					threshold : 200,
//					container: $("#listing-list")
//				});
//			}
//		});
	});
});
*/