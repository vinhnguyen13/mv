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
		
		$('.detail-listing-dt').css({
			left: -wWrapList +'px'
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
				counters[areaId] = counters[areaId] + 1;
			} else {
				counters[areaId] = 1;
			}
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
	drawPolygonMarker: function(area, number) {
		var center = JSON.parse(area.center);
		center = {lat: center[0], lng: center[1]};
		
		var marker = new google.maps.Marker({
			position: center,
			map: map.gmap,
		    icon: map.icon(number, 0)
		});
		
		var name = area.pre ? area.pre + ' ' + area.name : area.name;
		
		marker.addListener('mouseover', function(){
			if(number > 999) {
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
		var color = '#FF0000';
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
			    strokeOpacity: 0.5,
			    strokeWeight: 2,
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
	}
}

$(document).ready(function(){
	searchForm = $('#search-form');
	submitButton = $('.btn-submit');
	listingList = $('#listing-list ul');
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
					}
			    }, 300));
			}).on('mouseleave', '.item-listing a', function(e){
				clearTimeout($.data(this, 'mouseenterTimer'));
				if(map.currentState == 'detail') {
					var marker = map.getMarker($(this).data('id'));
					marker.setIcon(map.icon(marker.get('ids').length, 0));
				}
			});
			
			$('.btn-close').on('click', function(e){
				e.preventDefault();
			});
		},
		disable: function() {
			this.isEnabled = false;
			
			listingList.off('click', '.item-listing a').off('mouseenter', '.item-listing a').off('mouseleave', '.item-listing a');
			$('.btn-close').off('click');
		}
	};

	$(window).resize(function(){
		if($('.m-header').css('display') == 'none') {
			if(!desktop.isEnabled) {
				desktop.enable();
			}
		} else {
			if(desktop.isEnabled) {
				desktop.disable();
			}
		}
	}).trigger('resize');
	
	//$('.tinh-thanh').html('');
	$('.loai-bds').html('');
	/*for ( var i in dataCities) {
		var item = $('<li><a href="#" data-value="'+i+'" data-order="1" class="disable">'+dataCities[i].name+'</a></li>');
		if ( i == "1" ) {
			item.find('a').removeClass('disable');
		}
		$('.tinh-thanh').append(item);
	}*/
	for ( var i in dataCategories) {
		var item = $('<li><a href="#" data-value="'+i+'" data-order="3">'+dataCategories[i].name+'</a></li>');
		$('.loai-bds').append(item);
	}

	$('.dropdown-common').dropdown({
		txtAdd: true,
		styleShow: 0,
		funCallBack: function (item) {
			if(item.closest('ul').hasClass('loai-bds')) {
				if(item.data('value') == '6') {
					$('#du-an-select').removeClass('hide');
					loadProjects();
				} else {
					$('#du-an-select').addClass('hide');
				}
			} else if(item.closest('ul').hasClass('quan-huyen')) {
				if($('#loai-bds').val() == '6') {
					loadProjects();
				}
				
				$.get('/ad/list-swp', {districtId: $('#quan-huyen').val()}, function(response){
					appendDropdown($('#adproductsearch-ward_id'), response.wards);
					appendDropdown($('#adproductsearch-street_id'), response.streets);
				});
			}
			
			
			/*var selectedCityList = $('<li data-value="'+item.data('value')+'" data-order="'+item.data('order')+'">'+item.text()+'<span class="icon arrow-left arrow-small"></span></li>');

			if ( item.closest('.tinh-thanh').length > 0 || item.is('input[type=hidden]') ) {
				var idTT = item.data('value') == undefined ? item.val() : item.data('value');

				$('.quan-huyen').html('');
				var txtDefault = $('.quan-huyen').closest('.box-dropdown').find('.val-selected').data('placeholder');
				$('.quan-huyen').closest('.box-dropdown').find('.val-selected').text(txtDefault);

				for ( var i in dataCities) {
					if ( i == idTT ) {
						for ( var j in dataCities[i].districts ) {
							var item = $('<li><a href="#" data-value="'+j+'" data-order="2">'+dataCities[i].districts[j].name+'</a></li>');
							$('.quan-huyen').append(item);
						}
						break;
					}
				}
			}*/
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