//var gmap = null, response = null, listResult, infoWindow, savedTab = false, closeInfowindow, dragging = false, infoGroupWindow;
//var page = 1;
//var limit = 20;
//var ZOOM_CHANGE_LIMIT = 13;
//
//var CITY_ZOOM_LEVEL = 11;
//var DISTRICT_ZOOM_LEVEL = 12;
//var WARD_ZOOM_LEVEL = 13;

$(document).ready(function(){
	listing.init();
//	listResult = $('.list-results');
//	var mapOptions = {
//		center: {lat: 10.803164, lng: 106.631439},
//	    zoom: 14
//	};
//	asynInitial(document.getElementById('map'), mapOptions, function(gmapInstance){
//		gmap = gmapInstance;
//		start();
//	});
//	
//	search(function(r){
//		response = r;
//		start();
//	});
//	
//	$('#submit-filter').click(function(){
//		search(function(r){
//			response = r;
//			loadListing();
//		});
//	});
//	
//	$('#reset-filter').click(function(){
//		search(function(r){
//			response = r;
//			loadListing();
//		});
//	});
//	
//	$('.tab-content').scroll(function() {
//		var self = $(this);
//	    clearTimeout($.data(this, 'scrollTimer'));
//	    $.data(this, 'scrollTimer', setTimeout(function() {
//	    	if(self.scrollTop() >= (self.get(0).scrollHeight - 100 - self.outerHeight())) {
//	    	       loadPage();
//	    	}
//	    }, 250));
//	});
});

var listing = {
	CITY_ZOOM_LEVEL: 12, DISTRICT_ZOOM_LEVEL: 13, WARD_ZOOM_LEVEL: 14,
	status: 0, form: null, listEl: null, detailWrapEl: null, detailEl: null, tabContentEl: null, gmap: null, products: [],
	resultItemEl: null, noResultEl: null, listingLoading: null, filterFieldsEl: null,
	markers: {}, polygons: [], groupMarkers: [], infoWindow: null, InfoWindowMore: null, closeTimeout: null, offsetCenterX: 0, offsetCenterY: 0,
	currentPage: 1, limit: 20,
	state: {DRAW_DETAIL: 0, DRAW_WARD: 1, DRAW_DISTRICT: 2, DRAW_CITY: 3, DRAW_SAVED: 4},
	init: function() {
		listing.form = $('#map-search-form');
		listing.listEl = $('.list-results');
		listing.detailWrapEl = $('#detail-wrap');
		listing.detailEl = $('#detail-listing');
		listing.tabContentEl = $('.tab-content');
		listing.resultItemEl = $('.result-items');
		listing.noResultEl = $('#no-result');
		listing.listingLoading = $('#listing-loading');
		listing.filterFieldsEl = $('.list-filters-result');
		
		listing.initMap(listing.waitInitFilter);
		listing.initFilter(listing.waitInitMap);
		
		listing.attachDOMEvent();
		listing.calDetailSize();
	},
	attachDOMEvent: function() {
		$(window).resize(function(){
			listing.calDetailSize();
		});
		
		listing.tabContentEl.scroll(function() {
			var self = $(this);
			clearTimeout($.data(this, 'scrollTimer'));
		    $.data(this, 'scrollTimer', setTimeout(function() {
		    	if(self.scrollTop() >= (self.get(0).scrollHeight - 100 - self.outerHeight())) {
		    		listing.next();
		    	}
		    }, 250));
		});
		
		listing.listEl.on('click', '.save-item', function(e){
			e.stopPropagation();
			
			var id = $(this).data('id');
			
			listing.save($(this), function(stt, id) {
				var saveItem = listing.detailEl.find('.save-item');
				
				if(id == saveItem.data('id')) {
					if(stt) {
						saveItem.addClass('active');
					} else {
						saveItem.removeClass('active');
					}
				}
			});
		});
		
		listing.detailEl.on('click', '.save-item', function(e) {
			e.preventDefault();
			
			listing.save($(this), function(stt, id) {
				listing.listEl.find('.save-item').each(function(){
					var self = $(this);
					
					if(id == self.data('id')) {
						if(stt) {
							self.addClass('active');
						} else {
							self.removeClass('active');
						}
						
						return false;
					}
				});
			});
		});
		
		listing.listEl.on('click', '> li', function(){
			listing.detail($(this).data('id'));
		});
		
		listing.listEl.on('mouseenter', '> li', function(){
			var id = $(this).data('id') + '';
			
			$.data(this, 'mouseenterTimer', setTimeout(function() {
				if(listing.gmap.getZoom() > listing.WARD_ZOOM_LEVEL || listing.currentState == listing.state.DRAW_SAVED) {
					var marker = listing.getMarker(id);
					var ids = marker.get('ids');
					marker.setIcon(listing.icon(ids.length, 1));
					marker.setZIndex(google.maps.Marker.MAX_ZINDEX++);
					listing.setCenter(marker.getPosition(), listing.offsetCenterX, listing.offsetCenterY);
				}
		    }, 300));
		}).on('mouseleave', '> li', function(){
			clearTimeout($.data(this, 'mouseenterTimer'));
			var id = $(this).data('id') + '';
			var marker = listing.getMarker(id);
			var ids = marker.get('ids');
			marker.setIcon(listing.icon(ids.length, 0));
		});
		
		$('#order-by-tab').find('.order-button').click(function(){
			
			var self = $(this);
			
			if(!self.parent().hasClass('active')) {
				$('#order-by').val(self.data('order'));
				
				if(listing.currentState == listing.state.DRAW_SAVED) {
					listing.search(function(){
						listing.filterFieldsEl.removeClass('hide');
					});
				} else {
					if(self.data('order') == 'price') {
						listing.sort(self.data('order'), true);
					} else {
						listing.sort(self.data('order'));
					}
				}
			}
		});
		
		$('.saved-listing').click(function(e){
			e.preventDefault();
			
			var self = $(this);
			
			if(!self.parent().hasClass('active')) {
				listing.listEl.empty();
				listing.listingLoading.show();
				listing.noResultEl.hide();
				
				$.get(self.data('href'), {}, function(products) {
					listing.filterFieldsEl.addClass('hide');
					listing.listingLoading.hide();
					listing.products = products;
					
					if(listing.products.length) {
						listing._list();
					} else {
						listing.noResultEl.text('Chưa có tòa nhà nào được lưu.').show();
					}
					
					listing.addMarkers();
					listing.drawMarkerDetail();
					
					listing.currentState = listing.state.DRAW_SAVED;
				});
			}
		});
		
		$('.full-map a').click(function(){
			listing.resultItemEl.addClass('h');
			listing.calOffsetX();
		});
		
		$(document).on('click', '.show-listing', function(){
			listing.resultItemEl.removeClass('h');
			listing.calOffsetX();
		});
		
		listing.detailWrapEl.on('click', '.btn-close-detail', function(){
			listing.closeDetail(function(){
				listing.detailEl.empty();
			});
		});
		
		lightbox.option({
			'resizeDuration': 300,
			'fadeDuration': 400
		});
	},
	save: function(el, fn) {
		if(isGuest) {
			$('#frmLogin').modal('show');
		} else {
			if(!el.hasClass('saving')) {
				el.addClass('saving');
				
				var id = el.data('id');
				var url = el.data('url');
				var stt = el.hasClass('active') ? 0 : 1;
				
				$.ajax({
					type: 'post',
					url: url,
					data: {id: id, stt: stt},
					success: function() {
						el.removeClass('saving');
						el.toggleClass('active');
						
						listing.pushToProductSaved(Number(id), stt);
						
						fn(stt, id);
					}
				});
			}
		}
	},
	pushToProductSaved: function(id, stt) {
		if(stt) {
			productSaved.push(id);
		} else {
			var index = productSaved.indexOf(id);
			if (index != -1) {
				productSaved.splice(index, 1);
			}
		}
	},
	calDetailSize: function() {
		listing.calOffsetX();
		
		var width = $('#map').width() - listing.resultItemEl.width();

		width = (width > 820) ? 820 : width;
		var css = {width: width, height: listing.resultItemEl.height()};
		
		if(listing.detailWrapEl.hasClass('show')) {
			css.left = '-' + width + 'px';
		} else {
			css.left = '0x';
		}
		
		listing.detailWrapEl.css(css);
	},
	calOffsetX: function() {
		var offsetWidth = 0;
		
		if(listing.detailWrapEl.hasClass('show')) {
			offsetWidth += listing.detailWrapEl.width();
		}
		
		if(!listing.resultItemEl.hasClass('h')) {
			offsetWidth += listing.resultItemEl.width();
		}
		
		listing.offsetCenterX = - (offsetWidth / 2);
		listing.offsetCenterY = - (listing.filterFieldsEl.height() / 2);
	},
	waitInitMap: function(products) {
		listing.products = products;
		
		listing.list();
		
		listing.status++;
		listing.start();
	},
	waitInitFilter: function(gmap) {
		listing.gmap = gmap;
		
		var boundsChanged = listing.gmap.addListener('bounds_changed', function(){
			listing.setCenter(listing.gmap.getCenter(), listing.offsetCenterX, listing.offsetCenterY);
			google.maps.event.removeListener(boundsChanged);
		});
		
		listing.status++;
		listing.start();
	},
	start: function() {
		if(this.status == 2) {
			listing.addMarkers();
			listing.drawOnZoom();
		}
	},
	addMarkers: function() {
		for(var i in listing.markers) {
			var marker = listing.markers[i];
			marker.setMap(null);
		}
		
		listing.markers = {};
		
		for(i = 0; i < listing.products.length; i++) {
			var product = listing.products[i];
			var index = product.lat + '-' + product.lng;
			var marker = listing.markers[index];
			
			if(marker) {
				var ids = marker.get('ids');
				ids.push(product.id);
				marker.set('ids', ids);
				marker.setIcon(listing.icon(ids.length, 0));
			} else {
				marker = new google.maps.Marker({
					position: {lat: Number(product.lat), lng: Number(product.lng)},
					map: null,
				    icon: listing.icon(1, 0)
				});
				
				marker.set('ids', [product.id]);
				marker.addListener('mouseover', listing.markerOver);
				marker.addListener('mouseout', listing.markerOut);
				marker.addListener('click', listing.markerClick);
				
				listing.markers[index] = marker;
			}
		}
	},
	icon: function(counter, status) {
		var base = (counter > 1) ? '/site/map-image?s={status}&t=' + counter : '/images/marker-{status}.png';
		
		return base.replace('{status}', status);
	},
	markerOver: function() {
		this.setZIndex(google.maps.Marker.MAX_ZINDEX++);
		var ids = this.get('ids');
		var id = ids[0];
		
		var product = listing.getProduct(id);
		var infoContent = $('<div class="info-wrap-single">' + listing.buildInfoContent(product) + '</div>');
		
//		if(ids.length > 1) {
//			infoContent.append('<div class="more">...</div>');
//		}
		
		infoContent.append('<div class="arrow"></div>');
		
		listing.infoWindow.setContent(infoContent.get(0));
		listing.infoWindow.open(this);
	},
	markerOut: function() {
		listing.infoWindow.close();
	},
	markerClick: function() {
		var ids = this.get('ids');
		
		if(ids.length == 1) {
			listing.detail(ids[0]);
		} else {
			var infoContentWrap = $('<div class="info-wrap-multiple"></div>');
			for(i = 0; i < ids.length; i++) {
				var id = ids[i];
				var product = listing.getProduct(id);
				var infoContent = $(listing.buildInfoContent(product));
				listing.attachInfoItemClick(infoContent, id);
				infoContentWrap.append(infoContent);
			}
			
			listing.infoWindowMore.setContent(infoContentWrap.get(0));
			listing.infoWindowMore.open(listing.gmap, this);
		}
	},
	attachInfoItemClick: function(item, id) {
		item.click(function(){
			listing.detail(id);
		});
	},
	buildInfoContent: function(product) {
		var img = listing.getImageUrl(product.file_name, 'thumb');
		var price = formatPrice(product.price);
		var addition = listing.getAdditionInfo(product);
		var address = listing.getAddress(product);
		
		var infoContent = '<div class="infoContent">' + 
								'<div class="img-wrap bgcover" style="background-image:url('+img+')"></div><div>' +
								'<div class="address">' + address + '</div>' +
								'<div class="price">' + price + '</div>' +
								'<div class="addition">' + addition + '</div></div>' +
							'</div>';
		return infoContent;
	},
	drawOnZoom: function() {
		var zoom = listing.gmap.getZoom();

		if(zoom > listing.WARD_ZOOM_LEVEL) {
			listing.drawMarkerDetail();
		} else if(zoom > listing.DISTRICT_ZOOM_LEVEL) {
			// listing.drawMarkerWard();
			listing.drawMarkerDistrict();
		} else if(zoom > listing.CITY_ZOOM_LEVEL) {
			listing.drawMarkerDistrict();
		} else {
			// listing.drawMarkerCity();
			listing.drawMarkerDistrict();
		}
	},
	initMap: function(fn) {
		m2Map.initial(function(gmap) {
			var center = districtId ? listing.parseLatLng(districts[districtId].center) : listing.parseLatLng(city.center);
			var options = {center: center, zoom: initialZoom};
			var gmap = new google.maps.Map(document.getElementById('map'), options);
			
			listing.infoWindow = new m2Map.InfoWindow({offsetTop: 40});
			listing.infoWindowMore = new google.maps.InfoWindow();
			listing.infoWindowMore.addListener('domready', function() {
				listing.infoWindow.close();
				var marker = listing.infoWindowMore.anchor;
				google.maps.event.clearListeners(marker, 'mouseover');
				
				var infoWindowClick = google.maps.event.addListener(listing.infoWindowMore, 'closeclick', detachEvent);
				var infoWindowPositionChange = google.maps.event.addListener(listing.infoWindowMore, 'position_changed', detachEvent);
				
				var mapClick = google.maps.event.addListener(gmap, 'mousedown', function(){
					listing.infoWindowMore.close();
					detachEvent();
				});
				
				function detachEvent() {
					marker.addListener('mouseover', listing.markerOver);
					google.maps.event.removeListener(infoWindowClick);
					google.maps.event.removeListener(infoWindowPositionChange);
					google.maps.event.removeListener(mapClick);
				}
			});
			
			gmap.addListener('zoom_changed', function(){
				if(listing.currentState != listing.state.DRAW_SAVED) {
					var zoom = listing.gmap.getZoom();

					if(zoom > listing.WARD_ZOOM_LEVEL) {
						var zoomState = listing.state.DRAW_DETAIL;
					} else if(zoom > listing.DISTRICT_ZOOM_LEVEL) {
						var zoomState = listing.state.DRAW_DISTRICT;
					} else if(zoom > listing.CITY_ZOOM_LEVEL) {
						var zoomState = listing.state.DRAW_DISTRICT;
					} else {
						var zoomState = listing.state.DRAW_CITY;
					}
					
					if(zoomState != listing.currentState) {
						listing.drawOnZoom();
					}
				}
			});
			
			gmap.addListener('mousedown', function(){
				listing.closeDetail();
			});
			
			fn(gmap);
		});
	},
	initFilter: function(fn) {
		listing.filter(fn);
	},
	filter: function(fn) {
		listing.listingLoading.show();
		
		listing.noResultEl.hide();
		
		$.post(listing.form.attr('action'), listing.form.serialize(), function(products){
			listing.listingLoading.hide();
			fn(products);
		});
	},
	closeDetail: function(fn) {
		listing.detailWrapEl.removeClass('show').css('left', '0px');
		listing.calOffsetX();
		listing.closeTimeout = setTimeout(fn, 300);
	},
	detail: function(id) {
		clearTimeout(listing.closeTimeout);
		
		var mapLoading = $('#map-loading');
		
		mapLoading.show();
		
		listing.detailWrapEl.addClass('show').css('left', '-' + listing.detailWrapEl.width() + 'px');
		listing.calOffsetX();
		
		$.get('/ad/detail', {id: id}, function(responseHtml){
			listing.detailEl.html($(responseHtml).html());
			
			if(productSaved.indexOf(Number(id)) != -1) {
				listing.detailEl.find('.save-item ').addClass('active');
			}
			
			var imgs = listing.detailEl.find('img');
			
			listing.imgLoaded(imgs, function(){
				mapLoading.hide();
				if(listing.detailEl.find('.wrap-img-detail').length > 0) {
					listing.detailEl.find('.bxslider').bxSlider({
						moveSlides: 1,
						startSlide: 0,
						startSlide: 0,
						onSliderLoad: function() {
							this.infiniteLoop = false;
							this.hideControlOnEnd = true;
						}
					});
				}
				
				listing.detailEl.find('.gallery-detail').css('visibility', 'visible');
			});

			$('.tabs-detail-item li .sub-more').dropdown({
				linkShow: $('.tabs-detail-item a.more-item')
			});
		});
	},
	search: function(fn) {
		listing.listEl.empty();
		
		listing.filter(function(products) {
			listing.products = products;
			
			listing.list();

			listing.addMarkers();
			listing.drawOnZoom();
			
			if(fn) {
				fn();
			}
		});
	},
	sort: function(orderBy, asc) {
		var n = asc ? -1 : 1;
		var p = asc ? 1 : -1;
		
		listing.products.sort(function(a, b) {
			var an = Number(a[orderBy]);
			var bn = Number(b[orderBy]);
			
			if (an < bn)
				return n;
			else if (an > bn)
				return p;
			else 
				return 0;
		});
		
		listing.tabContentEl.scrollTop(0);
		listing.list();
	},
	list: function() {
		if(listing.products.length) {
			listing._list();
		} else {
			listing.noResultEl.text('Chưa có tòa nhà nào được đăng như tìm kiếm của bạn.').show();
		}
	},
	_list: function() {
		listing.currentPage = 1;
		listing.listEl.empty();
		
		var products = listing.products.slice(0, listing.limit);
		listing.appendList(products);
	},
	next: function() {
		var offset = (++listing.currentPage - 1) * listing.limit;
		var products = listing.products.slice(offset, offset + listing.limit);
		listing.appendList(products);
	},
	appendList: function(products) {
		var els = '';
		for(i = 0; i < products.length; i++) {
			var product = products[i];
			var saved = '';
			
			if(productSaved.indexOf(Number(product.id)) != -1) {
				saved = ' active';
			}
			
			els += '<li data-id="' + product.id +'">' +
							'<div class="bgcover wrap-img pull-left" style="background-image:url(' + listing.getImageUrl(product.file_name, 'thumb') + ')"><a href="#" class=""></a></div>' +
				            '<div class="infor-result">' +
				                '<p class="item-title">' + listing.getAddress(product) + '</p>' +
				                '<p class="type-result">' + dataCategories[product.category_id]['name'] + ' ' + types[product.type] + '</p>' +
				                '<p class="rice-result">' + formatPrice(product.price) + '</p>' +
				                '<p class="beds-baths-sqft">' + listing.getAdditionInfo(product) + '</p>' +
				                '<p class="date-post-rent">' + product.previous_time + '</p>' +
				                '<div class="icon-item-listing"><a data-id="' + product.id + '" class="save-item' + saved + '" title="Lưu" data-url="/ad/favorite"><em class="fa fa-heart-o"></em></a></div>' +
				            '</div>' +
				       '</li>';
		}
		listing.listEl.append(els);
	},
	drawMarkerDetail: function() {
		listing.removePolygons();
		listing.removeGroupMarkers();
		
		for(var i in listing.markers) {
			var marker = listing.markers[i];
			marker.setMap(listing.gmap);
		}
		
		listing.currentState = listing.state.DRAW_DETAIL;
	},
	drawMarkerWard: function() {
		var area = {};
		
		area = wards;
		
		listing.drawGroupMarker(area, 'ward_id');
		
		listing.currentState = listing.state.DRAW_WARD;
	},
	drawMarkerDistrict: function() {
		var area = {};
		
		if(districtId) {
			area[districtId] = districts[districtId];
		} else {
			area = districts;
		}
		
		listing.drawGroupMarker(area, 'district_id');
		
		listing.currentState = listing.state.DRAW_DISTRICT;
	},
	drawMarkerCity: function() {
		listing.currentState = listing.state.DRAW_CITY;
	},
	drawGroupMarker: function(area, countBy) {
		listing.removeMarkerDetail();
		listing.removePolygons();
		listing.removeGroupMarkers();
		
		var counters = {};
		
		for(var i = 0; i < listing.products.length; i++) {
			var product = listing.products[i];
			var areaId = product[countBy];
			
			if(counters[areaId]) {
				counters[areaId] = counters[areaId] + 1;
			} else {
				counters[areaId] = 1;
			}
		}
		
		for(var index in area) {
			
			var ar = area[index];
			
			if(ar.geometry) {
				var polygon = listing.drawPolygon(JSON.parse(ar.geometry), ar.color);
				polygon.setMap(listing.gmap);
				listing.polygons.push(polygon);
			}
			
			if(ar.center && counters[index]) {
				var latLng = ar.center.split(',');
				latLng = new google.maps.LatLng(Number(latLng[0]), Number(latLng[1]));
				
				var marker = new google.maps.Marker({
					position: latLng,
					map: listing.gmap,
				    icon: listing.icon(counters[index], 0)
				});
				
				marker.set('name', ar.name);
				
				marker.addListener('mouseover', listing.groupMakerMouseOver);
				marker.addListener('mouseout', listing.groupMakerMouseOut);
				marker.addListener('click', listing.groupMakerClick);
				
				listing.groupMarkers.push(marker);
			}
		}
	},
	groupMakerMouseOver: function() {
		var infoContent = '<div class="info-wrap-single"><div style="padding: 6px 12px; font-weight: bold; font-size: 13px; white-space: nowrap">' + this.name + '</div><div class="arrow"></div></div>';
		listing.infoWindow.setContent(infoContent);
		listing.infoWindow.open(this);
	},
	groupMakerMouseOut: function() {
		listing.infoWindow.close();
	},
	groupMakerClick: function() {
		listing.gmap.setZoom(listing.WARD_ZOOM_LEVEL + 1);
		listing.setCenter(this.getPosition(), listing.offsetCenterX, listing.offsetCenterY);
	},
	removePolygons: function() {
		for(i = 0; i < listing.polygons.length; i++) {
			var polygon = listing.polygons[i];
			polygon.setMap(null);
		}
		
		listing.polygons = [];
	},
	removeGroupMarkers: function() {
		for(i = 0; i < listing.groupMarkers.length; i++) {
			var groupMarker = listing.groupMarkers[i];
			groupMarker.setMap(null);
		}
		
		listing.groupMarker = [];
	},
	removeMarkerDetail: function() {
		for(var i in listing.markers) {
			var marker = listing.markers[i];
			marker.setMap(null);
		}
	},
	drawPolygon: function(geometry, color) {
		if(!color) {
			color = '#FF0000';
		}
		var triangleCoords = [];
		
		for(index in geometry) {
			var latLng = geometry[index].split(',');
			latLng = new google.maps.LatLng(Number(latLng[0]), Number(latLng[1]));
			triangleCoords.push(latLng);
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

		return polygon;
	},
	getImageUrl: function(filename, size) {
		if(filename) {
			if(filename.indexOf('http://') == 0) {
				var defaultSize = '745x510';
	    		
	    		if(size == 'thumb') {
	    			var s = '350x280';
	    		} else {
	    			var s = defaultSize;
	    		}
	    		
	    		return filename.replace(defaultSize, s);
			} else {
				var pathInfo = filename.split('.');
				return '/store/ad/' + pathInfo[0] + '.' + size + '.' + pathInfo[1];
			}
		} else {
			return '/themes/metvuong2/resources/images/default-ads.jpg';
		}
	},
	getAdditionInfo: function(product) {
		var addition = [];
		
		addition.push(product.area + 'm<sup>2</sup>');
		
		if(product.floor_no && product.floor_no != '0') {
			addition.push(product.floor_no + ' tầng');
		}
		if(product.room_no && product.room_no != '0') {
			addition.push(product.room_no + ' phòng ngủ');
		}
		if(product.toilet_no && product.toilet_no != '0') {
			addition.push(product.toilet_no + ' phòng tắm');
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
	getProduct: function(id) {
		var i;
		
		for(i = 0; i < listing.products.length; i++) {
			var product = listing.products[i];
			
			if(product.id == id) {
				return product;
			}
		}
	},
	getMarker: function(id) {
		for(var i in listing.markers) {
			var marker = listing.markers[i];
			var ids = marker.get('ids');
			
			if(ids.indexOf(id) != -1) {
				return marker;
			}
		}
	},
	imgLoaded: function(imgs, fn) {
		var imgsLength = imgs.length;
		var totalLoad = 0;
		
		imgs.load(function(){
			totalLoad++;
			
			if(totalLoad == imgs.length) {
				fn();
			}
		});
	},
	setCenter: function(latLng, offsetx, offsety) {
		var scale = Math.pow(2, listing.gmap.getZoom());
		var bounds = listing.gmap.getBounds();
		var projection = listing.gmap.getProjection();
		
		var nw = new google.maps.LatLng(bounds.getNorthEast().lat(), bounds.getSouthWest().lng());
		
		var worldCoordinateCenter = projection.fromLatLngToPoint(latLng);
		var pixelOffset = new google.maps.Point((offsetx/scale) || 0, (offsety/scale) || 0);
		
		var worldCoordinateNewCenter = new google.maps.Point(
			worldCoordinateCenter.x - pixelOffset.x,
			worldCoordinateCenter.y + pixelOffset.y
		);
		
		var center = projection.fromPointToLatLng(worldCoordinateNewCenter);
		
		listing.gmap.setCenter(center);
	},
	getBounds: function() {
		var scale = Math.pow(2, listing.gmap.getZoom());
		var bounds = listing.gmap.getBounds();
		var projection = listing.gmap.getProjection();
		var northEast = bounds.getNorthEast();
		
		var worldCoordinateNorthEast = projection.fromLatLngToPoint(northEast);
		var pixelOffset = new google.maps.Point(((listing.offsetCenterX*2)/scale) || 0, ((listing.offsetCenterY*2)/scale) || 0);
		var worldCoordinateNewNorthEast = new google.maps.Point(
			worldCoordinateNorthEast.x + pixelOffset.x,
			worldCoordinateNorthEast.y - pixelOffset.y
		);
		
		var newNorthEast = projection.fromPointToLatLng(worldCoordinateNewNorthEast);

		return new google.maps.LatLngBounds(bounds.getSouthWest(), newNorthEast);
	},
	parseLatLng: function(latLngString) {
		var latLng = latLngString.split(',');
		return new google.maps.LatLng(Number(latLng[0]), Number(latLng[1]));
	}
};

function search(callback) {
	page = 1;
	
	var searchForm = $('#map-search-form');
	
	listResult.empty();
	$('#listing-loading').show();
	$('.pagination').remove();
	$('#no-result').hide().text('Chưa có tòa nhà nào được đăng như tìm kiếm của bạn.');
	
	$.get(searchForm.attr('action'), searchForm.serialize(), function(r) {
		$('#listing-loading').hide();
		if(callback) {
			callback(r);
		}
	});
}

function start() {
	if(response && gmap) {
		gmap.click(function(){
			$('#detail-wrap').css({
				left: '0px'
			});

			setTimeout(function(){
				$('#detail-listing').html('');
			}, 300);
		});
		
		gmap.dragstart(function(){
			dragging = true;
		});
		gmap.dragend(function(){
			dragging = false;
		});
		
		var bermudaTriangles = [];
		gmap.zoomChanged(function(){
			if(gmap.getOriginal().getZoom() <= ZOOM_CHANGE_LIMIT) {
				if(gmap.status == 1) {
					gmap.hideAllMarker();

					if($.isEmptyObject(gmap.getGroupMakers())) {
						var markers = gmap.getMarkers();
						for(index in markers) {
							var marker = markers[index];
							var district = districts[marker.data.districtId];
							district.counter = Number(district.counter) + Number(marker.counter);
						}

						for(index in districts) {
							var district = districts[index];
							if(district.counter > 0) {
								var lngLat = JSON.parse(district.geometry);
								
								if(lngLat) {
									var triangleCoords = [];
									var bounds = new google.maps.LatLngBounds();
									for(index in lngLat) {
										var ll = lngLat[index].split(',');
										var a = new google.maps.LatLng(Number(ll[0]), Number(ll[1]));
										triangleCoords.push(a);
										bounds.extend(a);
									}
									var bermudaTriangle = new google.maps.Polygon({
									    paths: triangleCoords,
									    strokeColor: '#FF0000',
									    strokeOpacity: 0.5,
									    strokeWeight: 2,
									    fillColor: '#FF0000',
									    fillOpacity: 0.2
									  });
									bermudaTriangle.setMap(gmap.getOriginal());
									bermudaTriangles.push(bermudaTriangle);
									
									var firstLatLng = new google.maps.LatLng(bounds.getCenter().lat(), bounds.getCenter().lng());
									var option = {
										draggable: false,
									    position: firstLatLng,
									    icon: '/images/marker.png'
									};
									if(district.counter > 1) {
										option.icon = '/site/map-image?s=0&t=' + district.counter;
									}
									var markerGroup = new Marker(option);
									gmap.addMarkerGroup(markerGroup);
									addEvent(markerGroup, district);
								}
							}
						}
					} else {
						var markers = gmap.getGroupMakers();
						for(index in markers) {
							var marker = markers[index];
							marker.setMap(gmap);
						}
						
						for(index in bermudaTriangles) {
							bermudaTriangles[index].setMap(gmap.getOriginal());
						}
					}
				}
			} else {
				if(gmap.status == 0) {
					gmap.showAllMarker();
					
					for(index in bermudaTriangles) {
						bermudaTriangles[index].setMap(null);
					}
				}
			}
		});

		infoWindow = new InfoWindow({disableAutoPan: true});
		infoGroupWindow = new InfoWindow({disableAutoPan: true});
		
		$('#detail-wrap').on('click', '.btn-close-detail', function(){
			$('#detail-wrap').css({
				left: '0px'
			});
			setTimeout(function(){
				$('#detail-listing').html('');
			}, 300);
		});

		var timer = 0;

		$('#moi-nhat').on('click', '.icon-hear', function(e){
			e.preventDefault();
			var self = $(this);
			
			addToFavorite(self);
			
			if($('#detail-listing').data('id') == self.data('id')) {
				$('#detail-listing').find('.icon-hear').attr('class', self.attr('class'));
			}
			
//			if(savedTab && !self.hasClass('active')) {
//				var li = self.closest('li');
//				gmap.removeMarker(gmap.getMarker(li.data('id')));
//				li.remove();
//			}
		});
		
		function addToFavorite(_this) {
			if(isGuest) {
				$('#frmLogin').modal('show');
			} else {
				var _id = _this.attr('data-id');
				var callUrl = _this.attr('data-url');
				_this.toggleClass('active');
				var _stt = (_this.hasClass('active')) ? 1 : 0;
				clearTimeout(timer);
				timer = setTimeout(function() {
					$.ajax({
						type: "post",
						dataType: 'json',
						url: callUrl,
						data: {id: _id, stt: _stt},
						success: function(data) {
							if(_stt) {
								productSaved.push(Number(_id));
							} else {
								var index = productSaved.indexOf(Number(_id));
								if (index > -1) {
									productSaved.splice(index, 1);
								}
							}
						}
					});
				}, 1000);
			}
		}
		
		function addEvent(marker, district) {
			marker.mouseover(function(latLng) {
				var el = $('<div style="font-weight: bolder; padding-left: 8px;">' + district.name + '</div>');
				infoWindow.setContent(el.get(0));
				infoWindow.open(marker);
				
				var gmStyle = el.closest('.gm-style-iw');
				gmStyle.css({
					overflow: 'visible'
				});
				el.parent().css({
				    marginLeft: '-5px',
			    	marginTop: '-8px',
			    	marginBottom: '-10px',
			        marginRight: '-20px',
			        overflow: 'visible'
				});
				gmStyle.next().hide();
			});
			marker.mouseout(function(latLng) {
				infoWindow.close();
			});
		}

		$(document).on('click', '.rating .rate input', function(e){
			var _this = $(this);
			var _core = _this.val();
			var _url = _this.closest('.rating').attr('data-url');
			var _id = $('.detail-post').attr('data-id');
			console.log(_url);
			if(_core != 0 && _url){
				clearTimeout(timer);
				timer = setTimeout(function() {
					$.ajax({
						type: "post",
						dataType: 'json',
						url: _url,
						data: {id: _id, core: parseInt(_core)},
						success: function(data) {
							if(data.parameters.data){
								_this.closest('.rating').find(":radio[value="+data.parameters.data+"]").prop('checked',true);
								console.log(data.parameters.data);
							}
						}
					});
				}, 1000);
			}
		});

        // share-item click event
        $(document).on('click', '.share-item-1', function() {
            var _this = $(this);
            $('#share_form_1 input[type=text]').val(null);
            $('#share_form_1 textarea').val(null);
            var _address = $('#share_form_1 ._address');
            var _detailUrl = $('#share_form_1 ._detailUrl');
            var _image = $('#share_form_1 ._image');
            if(_address != null && _detailUrl != null){
                _address.val(_this.attr("data-address"));
                _detailUrl.val(_this.attr("data-url"));
                _image.val(_this.attr("data-img"));
            }
        });

        $(document).on('click', '#share_form_1 button.send_mail', function(){
            var recipient_email = $('#share_form_1 .recipient_email').val();
            var your_email = $('#share_form_1 .your_email').val();
            if(recipient_email != null && your_email != null) {
                $('#box-share-1').modal('hide');
                clearTimeout(timer);
                timer = setTimeout(function () {
                    $.ajax({
                        type: "post",
                        dataType: 'json',
                        url: $('#share_form_1').attr('action'),
                        data: $('#share_form_1').serializeArray(),
                        success: function (data) {
                            if(data.status == 200){

                            }
                            else {
                                var strMessage = '';
                                $.each(data.parameters, function(idx, val){
                                    var element = 'share_form_1_'+idx;
                                    strMessage += "\n" + val;
                                });
                                alert(strMessage+"\nTry again");
                            }
                        },
                        error: function () {
                            var strMessage = '';
                            $.each(data.parameters, function(idx, val){
                                var element = 'change-pass-form-'+idx;
                                strMessage += "\n" + val;
                            });
                            alert(strMessage);
                        }
                    });
                }, 1000);
            }
            return false;
        });

        // share product link to facebook
        $(document).on('click', '#share_form_1 a.fb-icon', function() {
            var detailUrl = $('#share_form_1 ._detailUrl').val();
            if(detailUrl == null || detailUrl == '' )
                detailUrl = $('#share_form_1 ._domain').val();

            FB.ui({
                method: 'share',
                href: detailUrl
            }, function(response){});
        });

        $(document).on('click', '#share_form_1 a.twe-icon', function() {
            location.href='skype:nhuttranm?chat';
        });


		$(document).on('click', '.report_modal', function(e){
			e.preventDefault();
			var _this = $(this);
			var _user_id = _this.attr('data-uid');
			if(_user_id == 0) {
				$('#frmLogin').modal('show');
				//$('#report-listing').modal('hide');
			} else {
				var _url = _this.attr('data-url');
				console.log(_url);
				clearTimeout(timer);
				timer = setTimeout(function () {
					$.ajax({
						type: "post",
						dataType: 'json',
						url: _url,
						data: {user_id: _user_id},
						success: function (data) {
							if(data.statusCode == 200){

							} else if(data.statusCode == 404){
								alert(data.parameters.msg);
							}
						}
					});
				}, 1000);
			}
		});

		$(document).on('click', '#frm-report .send_report', function(e){
			e.preventDefault();
			var _this = $(this);
			var _user_id = $('#frm-report #uid').val();
			if(_user_id != 0) {
				clearTimeout(timer);
				timer = setTimeout(function () {
					$.ajax({
						type: "post",
						dataType: 'json',
						url: $('#frm-report').attr('action'),
						data: $('#frm-report').serializeArray(),
						success: function (data) {
							$('#report-listing').modal('hide');
							if(data == 200){
								alert("Your message is sent.\nThank you.");
							}
							else if(data == 13){
								alert("You reported with us");
							}
							else {
								alert("Please, try again!");
							}
						},
						error: function () {
							$('#report-listing').modal('hide');
						}
					});
				}, 500);
			}
			return false;
		});

		
		var hoverTimeout;
		listResult.on('mouseenter', '> li', function() {
			var self = $(this);
			
			hoverTimeout = setTimeout(function(){
				if(self.hasClass('onmap'))
					return;
				var marker = gmap.getMarker(self.attr('class'));

				if(marker) {
					if(marker.counter == 1) {
						marker.setIcon('/images/marker-hover.png');
					} else {
						marker.setIcon('/site/map-image?s=1&t=' + marker.counter);
					}
					
					marker.setZIndex(google.maps.Marker.MAX_ZINDEX++);
					if(!gmap.getBounds().contains(marker.getPosition())) {
						gmap.setCenter(marker.getPosition());
					}
				}
			}, 200);
		}).on('mouseleave', '> li', function() {
			clearTimeout(hoverTimeout);
			
			var self = $(this);
			if(self.hasClass('onmap'))
				return;
			var marker = gmap.getMarker(self.attr('class'));
			if(marker.counter == 1) {
				marker.setIcon('/images/marker.png');
			} else {
				marker.setIcon('/site/map-image?s=0&t=' + marker.counter);
			}
		});
		
		listResult.on('click', '> li', function(e){
			if(!$(e.target).hasClass('icon-hear') && $(e.target).closest('.icon-hear').length == 0) {
				var self = $(this);
				
				// infoWindow.close();
				
				$('#map-loading').show();

				var width = $('.wrap-map-result').width();
				width = (width > 820) ? 820 : width;
				
				$('#detail-wrap').css({
					width: width,
					left: '-' + width + 'px',
					height: $('.result-items').height()
				});
				
				var id = $(this).data('detail');
				
				$.get('/ad/detail', {id: id}, function(response){
					
					
					var res = $(response);
					
					var index = productSaved.indexOf(Number(id));
					if (index > -1) {
						res.find('.icon-hear').addClass('active');
					}
					
					$('#detail-listing').data('id', id).html(res.html());
					
					$('#detail-listing').find('.icon-hear').click(function(e){
						e.preventDefault();
						addToFavorite($(this));
						
						self.find('.icon-hear').attr('class', $(this).attr('class'));
					});

//					if(!self.data('clone')) {
//						var marker = gmap.getMarker(self.data('id'));
//						if(marker) {
//							var position = marker.getPosition();
//							gmap.setCenter({lat: position.lat(), lng: position.lng()});
//						}
//					}

					//$(document).trigger('gallery-load', [{data: '1'}, 'something']);

					$('.gallery-detail').imagesLoaded()
				 	.always( function( instance ) {
				 		
				 		$('#map-loading').hide();
						if($('#detail-listing .bxslider').find('.wrap-img-detail').length > 0) {
							
							$('#detail-listing .bxslider').bxSlider({
								moveSlides: 1,
				                startSlide: 0,
				                startSlide: 0,
				                onSliderLoad: function() {
				                    this.infiniteLoop = false;
				                    this.hideControlOnEnd = true;
				                }
				            });
							lightbox.option({
					            'resizeDuration': 300,
					            'fadeDuration': 400
					        });
							$('.gallery-detail').css('visibility', 'visible');
						}
				 	});
					
					/*$('.tabs-detail-item li .sub-more').dropdown({
						linkShow: $('.tabs-detail-item a.more-item')
					});*/
					
				});
			}
		});
		
		$('#order-by-tab a').click(function(e){
			
			$('.btn-close-detail').trigger('click');
			
			if($(this).data('href')) {
				savedTab = true;
				listResult.empty();
				$('#listing-loading').show();
				$('.pagination').remove();
				$('#no-result').hide().text('Chưa có tòa nhà nào được lưu.');
				$('#map-search-form').css('visibility', 'hidden');
				$.get($(this).data('href'), {}, function(r) {
					$('#listing-loading').hide();
					page = 1;
					response = r;
					loadListing();
				});
			} else {
				savedTab = false;
				$('#order-by').val($(this).data('order'));
				$('#map-search-form').css('visibility', 'visible');
				search(function(r){
					response = r;
					loadListing();
				});
			}
		});
		
		loadListing();
	}
}

function loadListing() {
	var list = '';
	gmap.removeAllMarker();
	if(response.productResponse.length > 0) {
		for(index in response.productResponse) {
			var product = response.productResponse[index];
			list += makeMarker(product);
		}
		
		$('#count-listing').text(response.total);
		listResult.append(list);
		
		var marker = gmap.getMarker(listResult.find('> li:last-child').attr('class'));
		gmap.setCenter(marker.getPosition());
		
		loadPage();
		
		$('.pagination').remove();
	} else {
		$('#no-result').show();
	}
}

function makeMarker(product) {
	var city = dataCities[product.city_id];
	var district = city['districts'][product.district_id];
	var address = '';
	
	if(product.home_no) {
		address += product.home_no + ', ';
	}
	
	if(product.street_id) {
		var street = district['streets'][product.street_id];
		address = address + street.pre + ' ' + street.name + ', ';
	}
	
	if(product.ward_id) {
		var ward = district['wards'][product.ward_id];
		address = address + ward.pre + ' ' + ward.name + ', ';
	}
	
	if(address == '') {
		address = district.pre + ' ' + district.name + ', ' + city.name;
	} else {
		address = address + ' ' + district.pre + ' ' + district.name + ', ' + city.name;
	}
	
	var markerId = latLngToClass(product.lat, product.lng);
	var marker;
	
	if(marker = gmap.getMarker(markerId)) {
		marker.setIcon('/site/map-image?s=0&t=' + ++marker.counter);
	} else {
		marker = new Marker({
			draggable: false,
		    position: {lat: Number(product.lat), lng: Number(product.lng)},
		    icon: '/images/marker.png'
		}, latLngToClass(product.lat, product.lng));
		
		marker.mouseover(function(latLng) {
			showinfo(latLng, marker);
		});
		
		marker.mouseup(function(latLng) {
			showinfo(latLng, marker);
		});
		
		marker.mouseout(function(latLng){
			if(dragging)
				return;
			
			closeInfowindow = setTimeout(function(){
				infoWindow.close();
			}, 400);
		});
		
		marker.mousedown(function(){
			infoWindow.close();
		});
		
		gmap.addMarker(marker);
		
		marker.data.districtId = product.district_id;
	}
	
	var type = (product.type == 1) ? 'BÁN' : 'CHO THUÊ';
	var category = dataCategories[product.category_id]['name'].toUpperCase();
	var price = (product.type == 1) ? product.price : product.price + '/tháng';
	
	var toiletNo = '';
	if(product['toilet_no'] && product['toilet_no'] != 0) {
		toiletNo = '• ' + product['toilet_no'] + ' Phòng tắm ';
	}
	
	var roomNo = '';
	if(product['room_no'] && product['room_no'] != 0) {
		roomNo = '• ' + product['room_no'] + ' phòng ngủ ';
	}
	
	var floorNo = '';
	if(product['floor_no'] && product['floor_no'] != 0) {
		floorNo = '• ' + product['floor_no'] + ' tầng ';
	}

	var className = 'icon-hear';
	
	for(i = 0; i < productSaved.length; i++) {
		if(product.id == productSaved[i]) {
			className += ' active';
			break;
		}
	}
	
	var li = '<li style="display: none;" data-detail="' + product.id +'" class="' + markerId + '">' +
                '<div class="bgcover wrap-img pull-left" style="background-image:url('+product.image_url+')"><a href="#" class=""></a></div>' +
                '<div class="infor-result">' +
                    '<p class="item-title">' + address + '</p>' +
                    '<p class="type-result">' + category + ' ' + type + '</p>' +
                    '<p class="rice-result">' + price + '</p>' +
                    '<p class="beds-baths-sqft">' + product['area'] + 'm<sup>2</sup> ' + floorNo + roomNo + toiletNo + '</p>' +
                    '<p class="date-post-rent">' + product.previous_time + '</p>' +
                    '<div class="icon-item-listing"><a title="Lưu" class="' + className + '" data-id="' + product.id + '" data-url="/ad/favorite"><em class="fa fa-heart-o"></em></a></div>' +
                '</div>' +
            '</li>';
	
	return li;
}

function loadPage() {
	var offset = (page - 1) * limit;
	
	$('.list-results').children().slice(offset, offset+limit).show();
	
	page++;
}

function latLngToClass(lat, lng) {
	return 'p' + lat.replace('.', '_') + '-' + lng.replace('.', '_');
}

function showinfo(latLng, marker){
	if(dragging)
		return;
	clearTimeout(closeInfowindow);
	var id = marker.getId();
	var listEl = $('#moi-nhat').clone(true).attr('id', 'moi-nhat-onmap').addClass('moi-nhat-onmap');
	
	listEl.hover(function(){
		clearTimeout(closeInfowindow);
	}, function(){
		closeInfowindow = setTimeout(function(){
			infoWindow.close();
		}, 400);
	});
	
	listEl.find('.' + id).addClass('onmap').show();
	listEl.find('li').not(listEl.find('.' + id)).remove();

	listEl.width(168);
	listEl.find('.list-results').width(168 + 51);
	
	infoWindow.setContent(listEl.get(0));
	infoWindow.open(marker);
	
	marker.setZIndex(google.maps.Marker.MAX_ZINDEX++);
	
	var gmStyle = listEl.closest('.gm-style-iw');
	gmStyle.css({
		overflow: 'visible'
	});
	listEl.parent().css({
	    marginLeft: '-5px',
    	marginTop: '-8px',
    	marginBottom: '-10px',
        marginRight: '-20px',
        overflow: 'visible'
	});
	gmStyle.next().hide();
}