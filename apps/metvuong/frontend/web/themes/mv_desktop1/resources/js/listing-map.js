var listing = {
	listEl: $('#listing-list > ul'),
	loadingListEl: $('#loading-list'),
	countFromEl: $('#count-from'),
	countToEl: $('#count-to'),
	countTotalEl: $('#count-total'),
	projectInfoEl: $('#project-info'),
	contentHolderEl: $('#content-holder'),
	areaEl: $('.area-select'),
	detailListing: $('.detail-listing-dt'),
	markers: {},
	polygons: {},
	areaMarkers: {},
	color: '#00a769',
	zoomLevel: {
		city: {min: 0, max: 11},
		district: {min: 12, max: 14},
		ward: {min: 15, max: 16},
		detail: {min: 17, max: 21}
	},
	initialZoom: {city: 11, district: 14, ward: 16, street: 15},
	orderArea: {city: 0, district: 1, ward: 2, detail: 3},
	init: function() {
		
		listing.limit = Number(listing.countToEl.text()) - Number(listing.countFromEl.text()) + 1;
		
		events.attachDesktopEvent(listing.listEl, 'click', '.item-listing > a', listing.detailEvent);
		events.attachDesktopEvent(listing.listEl, 'mouseenter', '.item-listing > a', listing.mouseenterEvent);
		events.attachDesktopEvent(listing.listEl, 'mouseleave', '.item-listing > a', listing.mouseleaveEvent);
		events.attachDesktopEvent($('.wrap-listing'), 'scroll', listing.more);
		events.attachDesktopEvent(form.fields.projectBuildingId, 'change', listing.loadProject);
		events.attachDesktopEvent(form.fields.categoryId, 'change', listing.resetProjectField);
		events.attachDesktopEvent(form.fields.districtId, 'change', listing.resetProjectField);
		events.attachDesktopEvent($('.btn-close'), 'click', listing.closeDetail);
		
		for(var i in form.fields) {
			events.attachDesktopEvent(form.fields[i], 'change', listing.fieldChange);
		}

		listing.getListingMarker(function(r){
			listing.firstTimeDraw();
		});
		
		if(location.hash) {
			listing._detail(location.hash.replace('#', ''));
		}
	},
	mouseenterEvent: function() {
		var id = $(this).data('id');
		
		$.data(this, 'mouseenterTimer', setTimeout(function(){
			var marker = listing.getFocusMarker(id);
			
			if(marker) {
				listing.focusMarker(marker);
				
				var offsetX = listing.detailListing.position().left / 2;
				var position = marker.getPosition();
				
				if(!listing.getBounds(offsetX, 0).contains(position)) {
					listing.setCenter(position, offsetX, 0);
				}
			}
		}, 300));
	},
	focusMarker: function(marker) {
		marker.setIcon(listing.icon(marker.get('ids').length, 1));
		marker.setZIndex(google.maps.Marker.MAX_ZINDEX++);
	},
	mouseleaveEvent: function() {
		clearTimeout($.data(this, 'mouseenterTimer'));
		
		var marker = listing.getFocusMarker($(this).data('id'));
		
		if(marker) {
			marker.setIcon(listing.icon(marker.get('ids').length, 0));
		}
	},
	getFocusMarker: function(id) {
		var marker;
		
		if(listing.currentDrawLevel == 'detail') {
			marker = listing.getMarkerDetail(id + '');
		} else {
			marker = listing.getMarkerArea(id + '');
		}
		
		return marker;
	},
	getMarkerDetail: function(id) {
		for(var i in listing.markers) {
			if(listing.markers[i].get('ids').indexOf(id) !== -1) {
				return listing.markers[i];
			}
		}
	},
	getMarkerArea: function(id) {
		for(var i in listing.areaMarkers) {
			if(listing.areaMarkers[i].get('ids').indexOf(id) !== -1) {
				return listing.areaMarkers[i];
			}
		}
	},
	loadedAllResources: function() {
		if(desktop.countLoadedResource == resources.length) {
			listing._loadedAllResources();
		}
	},
	_loadedAllResources: function() {
		m2Map.loaded();

		listing.getArea(function(r){
			listing.initMap();
		});
	},
	initMap: function() {
		listing._initMap();
		
		listing.firstTimeDraw();
		
		listing.map.addListener('zoom_changed', function(){
			var focusArea = listing.focusArea();
			var zoomLevel = listing.getZoomLevel(listing.map.getZoom());
			
			var drawLevel = listing.getDrawLevel(focusArea.collection, zoomLevel);
			
			if(drawLevel != listing.currentDrawLevel) {
				listing.currentDrawLevel = listing.reDraw();
			}
		});
	},
	_initMap: function() {
		var mapEl = $('#map');
		var focusArea = listing.focusArea();
		var area = listing.area[focusArea.collection][focusArea.id];
		
		var center = area.center ? listing.parseCenter(area.center) : {lat: 10.783091, lng: 106.704899};
		
		var options = {center: center, zoom: listing.initialZoom[focusArea.collection]};
		
		listing.map = new google.maps.Map(mapEl.get(0), options);
		
		listing.detail = function(id) {
			listing._detail(id);
			
			var temp = function(){
				listing._closeDetail();
				
				mapEl.off('mousedown', temp);
			};
			
			mapEl.on('mousedown', temp);
		};
		
		listing.infoWindow = new m2Map.InfoWindow({offsetTop: 40});
		
		listing.infoWindowMore = new google.maps.InfoWindow();
		listing.infoWindowMore.addListener('domready', function() {
			listing.infoWindow.close();
			var marker = listing.infoWindowMore.anchor;
			google.maps.event.clearListeners(marker, 'mouseover');
			
			var infoWindowClick = google.maps.event.addListener(listing.infoWindowMore, 'closeclick', detachEvent);
			var infoWindowPositionChange = google.maps.event.addListener(listing.infoWindowMore, 'position_changed', detachEvent);
			
			var mapClick = google.maps.event.addListener(listing.map, 'mousedown', function(){
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
	},
	markerOver: function() {
		this.setZIndex(google.maps.Marker.MAX_ZINDEX++);
		var ids = this.get('ids');
		var id = ids[0];
		
		var product = listing.products[id];
		
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
		listing.infoWindow.setOffsetTop(40);
	},
	markerClick: function() {
		var ids = this.get('ids');
		
		if(ids.length == 1) {
			listing.detail(ids[0]);
		} else {
			var infoContentWrap = $('<div class="info-wrap-multiple"></div>');
			for(i = 0; i < ids.length; i++) {
				var id = ids[i];
				var product = listing.products[id];
				var infoContent = $(listing.buildInfoContent(product));
				listing.attachInfoItemClick(infoContent, id);
				infoContentWrap.append(infoContent);
			}
			
			listing.infoWindowMore.setContent(infoContentWrap.get(0));
			listing.infoWindowMore.open(listing.map, this);
		}	
	},
	attachInfoItemClick: function(item, id) {
		item.click(function(){
			listing.detail(id);
		});
	},
	firstTimeDraw: function() {
		if(typeof listing.products !== 'undefined' && typeof listing.area != 'undefined') {
			listing.currentDrawLevel = listing.draw();
		}
	},
	draw: function() {
		var focusArea = listing.focusArea();

		var drawLevel = 'detail';
		
		if(focusArea.collection == 'street') {
			listing.drawDetail();
			if(!listing.polylineStreet) {
				listing.drawStreet(listing.area['street'][focusArea.id]);
			}
		} else {
			var zoomLevel = listing.getZoomLevel(listing.map.getZoom());

			drawLevel = listing.getDrawLevel(focusArea.collection, zoomLevel);
			
			if(drawLevel == 'detail') {
				listing.drawDetail();
			} else {
				var area = listing.getDrawArea(focusArea.collection, zoomLevel);
				
				listing['draw' + cfl(drawLevel)].call(null, area);
			}
		}
		
		return drawLevel;
	},
	reDraw: function() {
		if(listing.currentDrawLevel != 'detail') {
			listing.removeAllArea();
		} else {
			listing.hideMarkers();
		}
		
		return listing.draw();
	},
	getDrawLevel: function(focusLevel, zoomLevel) {
		var focusOrderArea = listing.orderArea[focusLevel];
		var zoomOrderArea = listing.orderArea[zoomLevel];
		
		return (focusOrderArea > zoomOrderArea) ? focusLevel : zoomLevel;
	},
	getDrawArea: function(focusLevel, zoomLevel) {
		var focusArea = listing.focusArea();
		
		var focusOrderArea = listing.orderArea[focusLevel];
		var zoomOrderArea = listing.orderArea[zoomLevel];
		
		var area = {};
		
		if(focusOrderArea >= zoomOrderArea) {
			area[focusArea.id] = listing.area[focusArea.collection][focusArea.id];
		} else {
			if(focusArea.collection == 'city') {
				area = listing.area[zoomLevel];
			} else if(focusArea.collection == 'district') {
				for(var i in listing.area['ward']) {
					var ward = listing.area['ward'][i];
					if(ward['district_id'] == focusArea.id) {
						area[i] = ward;
					}
				}
			}
		}
		
		return area;
	},
	drawStreet: function(street) {
		if(street.geometry) {
			var geometry = JSON.parse(street.geometry);
			var path = [];
			
			for(var i in geometry) {
				path.push({lat: geometry[i][0], lng: geometry[i][1]});
			}
			
			listing.polylineStreet = listing.polyline(path);
			listing.polylineStreet.setMap(listing.map);
		}
	},
	drawWard: function(ward) {
		listing.drawArea(ward, 'ward');
	},
	drawDistrict: function(district) {
		listing.drawArea(district, 'district');
	},
	drawCity: function(city) {
		listing.drawArea(city, 'city');
	},
	drawDetail: function() {
		for(var i in listing.markers) {
			listing.markers[i].setMap(listing.map);
		}
	},
	drawArea: function(area, collection) {
		var group = listing.countGroup(collection);

		for(var i in area) {
			if(area[i].geometry) {
				listing.drawAreaPolygon(area[i]);
			}
			if(area[i].center && group[i]) {
				listing.drawAreaMarker(area[i], group[i]);
			}
		}
	},
	removeAllArea: function() {
		listing.removeAreaMarkers();
		listing.removeAreaPolygons();
	},
	removeAreaPolygons: function() {
		for(var i in listing.polygons) {
			listing.polygons[i].setMap(null);
		}
		
		listing.polygons = {};
	},
	removeAreaMarkers: function() {
		for(var i in listing.areaMarkers) {
			listing.areaMarkers[i].setMap(null);
		}
		
		listing.areaMarkers = {};
	},
	countGroup: function(collection) {
		var groups = {};
		var idString = collection + '_id';
		
		for(var i in listing.products) {
			var product = listing.products[i];
			var areaId = product[idString];
			
			if(groups[areaId]) {
				groups[areaId].push(product.id);
			} else {
				groups[areaId] = [product.id];
			}
		}

		return groups;
	},
	drawAreaPolygon: function(area) {
		var geometries = JSON.parse(area.geometry);
		var triangleCoords = [];
		
		for(var i = 0; i < geometries.length; i++) {
			var latLngs = [];
			var geometry = geometries[i];
			
			for(var j = 0; j < geometry.length; j++) {
				latLngs.push(new google.maps.LatLng(geometry[j][0], geometry[j][1]));
			}
			
			triangleCoords.push(latLngs);
		}
		
		var polygon = listing.polygon(triangleCoords);
		
		polygon.setMap(listing.map);
		polygon.set('id', area.id);
		
		polygon.addListener("mouseover", listing.polygonMouseover);
		polygon.addListener("mouseout", listing.polygonMouseout);
		polygon.addListener("click", listing.polygonClick);
		
		listing.polygons[area.id] = polygon;
	},
	polygonMouseover: function() {
		if(marker = listing.getAreaMarkerRef(this.id)) {
			google.maps.event.trigger(marker, 'mouseover');
		}
	},
	polygonMouseout: function() {
		if(marker = listing.getAreaMarkerRef(this.id)) {
			google.maps.event.trigger(marker, 'mouseout');
		}
	},
	polygonClick: function() {
		if(marker = listing.getAreaMarkerRef(this.id)) {
			google.maps.event.trigger(marker, 'click');
			google.maps.event.trigger(marker, 'mouseout');
		}
	},
	getAreaMarkerRef: function(id) {
		var marker;

		for(var i in listing.areaMarkers) {
			if(i == id) {
				marker = listing.areaMarkers[i];
				break;
			}
		}
		
		return marker;
	},
	drawAreaMarker: function(area, ids) {
		var marker = listing.marker(listing.parseCenter(area.center), listing.map);
		marker.setIcon(listing.icon(ids.length, 0));
		marker.set('ids', ids);
		
		marker.addListener('mouseover', function() {
			var name = area.pre ? area.pre + ' ' + area.name : area.name;
			listing.areaMarkerOver.call(this, name);
		});
		
		marker.addListener('mouseout', listing.areaMarkerOut);
		marker.addListener('click', listing.areaMarkerClick);
		
		listing.areaMarkers[area.id] = marker;
	},
	areaMarkerOver: function(name) {
		var infoContent = '<div class="info-wrap-single"><div style="padding: 6px 12px; font-weight: bold; font-size: 13px; white-space: nowrap">' + name + '</div><div class="arrow"></div></div>';
		
		listing.infoWindow.setContent(infoContent);
		listing.infoWindow.open(this);
		listing.fixOffsetTopInfoWindow(this);
	},
	areaMarkerOut: function() {
		listing.infoWindow.close();
	},
	areaMarkerClick: function() {
		var focusArea = listing.focusArea();
		var zoomLevel = listing.getZoomLevel(listing.map.getZoom());
		var drawLevel = listing.getDrawLevel(focusArea.collection, zoomLevel);
		var flag = false;
		
		for(var i in listing.zoomLevel) {
			if(flag) {
				listing.map.setZoom(listing.zoomLevel[i].min);
				listing.setCenter(this.getPosition(), listing.detailListing.position().left / 2, 0);
				
				break;
			}
			if(i == drawLevel) {
				flag = true;
			}
		}
	},
	fixOffsetTopInfoWindow: function(marker) {
		if(marker.ids.length > 9999) {
			listing.infoWindow.setOffsetTop(60);
		} else if(marker.ids.length > 999) {
			listing.infoWindow.setOffsetTop(50);
		} else {
			listing.infoWindow.setOffsetTop(40);
		}
	},
	focusArea: function() {
		var obj = {};
		
		listing.areaEl.each(function(){
			var self = $(this);
			if(self.val()) {
				obj.id = Number(self.val());
				obj.collection = self.attr('id').replace('_id', '');
			}
		});
		
		return obj;
	},
	getZoomLevel: function(zoom) {
		for(var i in listing.zoomLevel) {
			if(listing.zoomLevel[i]['min'] <= zoom && listing.zoomLevel[i]['max'] >= zoom) {
				return i;
				break;
			}
		}
	},
	detailEvent: function(e) {
		e.preventDefault();
		listing.detail($(this).data('id'));
	},
	detail: function(id) {
		listing._detail(id);
	},
	_detail: function(id) {
		clearTimeout(listing.closeTimeout);
		
		var wWrapList = listing.detailListing.outerWidth();
		var detailListing = $('.detail-listing');
		var wrapDetailListing = listing.detailListing;
		
		wrapDetailListing.loading({full: false});
		
		listing.detailListing.css({
			left: -wWrapList +'px'
		});
		
		$.get('/ad/detail', {id: id}, function(r){
			
			detailListing.find('.container').html($(r).find('#detail-wrap').html());

			var swiper = new Swiper('.swiper-container', {
				pagination: '.swiper-pagination',
				paginationClickable: true,
		        spaceBetween: 0
		    });

			wrapDetailListing.loading({done: true});
			
			location.hash = id;
		});
	},
	closeDetail: function(e) {
		e.preventDefault();
		listing._closeDetail();
	},
	_closeDetail: function() {
		listing.closeTimeout = setTimeout(function(){
			listing.detailListing.css({
				left: '0px'
			});
		}, 100);
	},
	more: function() {
		var self = $(this);
		
		if(self.scrollTop() >= (self.get(0).scrollHeight - 200 - self.outerHeight())) {
			if(listing.loadingListEl.hasClass('hide')) {
				var offset = Number(listing.countToEl.text());
				
				if(offset < Number(listing.countTotalEl.text())) {
					var page = offset/listing.limit + 1;
					var hiddenFields = listing.hiddenFieldsToForm({page: page});
					
					listing.getListing(function(items, total) {
						listing.listEl.append(items);
						listing.countToEl.text(offset + items.length);
					});
					
					hiddenFields.remove();
				}
			}
    	}
	},
	getArea: function(fn) {
		listing._getArea(function(r){
			listing.area = r;
			
			fn(r);
		});
	},
	_getArea: function(fn) {
		if(listing.loadAreaXHR) {
			listing.loadAreaXHR.abort();
		}
		
		listing.loadAreaXHR = $.ajax(getGeometryUrl, {
			data: {cityId: form.fields.cityId.val(), v: getGeometryVersion},
			dataType: 'json',
			cache: true,
			success: function(r) {
				fn(r);
			}
		});
	},
	getListing: function(fn) {
		listing.loadingListEl.removeClass('hide');
		
		listing._getListing(function(items, total) {
			listing.loadingListEl.addClass('hide');
			
			fn(items, total);
		});
	},
	_getListing: function(fn) {
		if(listing.loadListingXHR) {
			listing.loadListingXHR.abort();
		}
		
		listing.loadListingXHR = $.get(form.el.attr('action'), form.el.serialize(), function(r) {
			fn($(r.items).filter('li'), r.total);
		});
	},
	hiddenFieldsToForm: function(fields) {
		var hiddenFields = listing.hiddenFields(fields);
		
		form.el.append(hiddenFields);
		
		return hiddenFields;
	},
	hiddenFields: function(fields) {
		var hiddenFields = $('<div></div>');
		
		for(var i in fields) {
			hiddenFields.append('<input type="hidden" name="' + i + '" value="' + fields[i] + '" />');
		}
		
		return hiddenFields;
	},
	fieldChange: function() {
		var field = $(this);
		
		listing.updateListing();
		
		if(this.id != 'order_by') {
			listing.removeMarkers();

			if(listing.polylineStreet) {
				listing.polylineStreet.setMap(null);
				listing.polylineStreet = null;
			}
			
			listing.getListingMarker(function(r){
				listing.currentDrawLevel = listing.reDraw();
			});
		}
	},
	updateListing: function() {

		listing.contentHolderEl.addClass('hide');
		
		listing.getListing(function(items, total){
			listing.contentHolderEl.removeClass('hide');
			
			listing.listEl.html(items);
			listing.countToEl.text(items.length);
			listing.countTotalEl.text(total);
			
			var noResultEl = $('#no-result');
			var hasResultEl = $('#has-result');
			if(total) {
				noResultEl.addClass('hide');
				hasResultEl.removeClass('hide');
			} else {
				noResultEl.removeClass('hide');
				hasResultEl.addClass('hide');
			}
		});
	},
	getListingMarker: function(fn) {
		listing._getListingMarker(function(r){
			listing.products = r;
			listing.markers = {};
			
			for(var i in listing.products) {
				var product = listing.products[i];
				var index = product.lat + '-' + product.lng;
				var marker = listing.markers[index];
				
				if(marker) {
					var ids = marker.get('ids');
					ids.push(product.id);
					marker.set('ids', ids);
					marker.setIcon(listing.icon(ids.length, 0));
				} else {
					marker = listing.marker({lat: Number(product.lat), lng: Number(product.lng)}, null);
					
					marker.set('ids', [product.id]);
					marker.setIcon(listing.icon(1, 0));

					marker.addListener('mouseover', listing.markerOver);
					marker.addListener('mouseout', listing.markerOut);
					marker.addListener('click', listing.markerClick);
					
					listing.markers[index] = marker;
				}
			}

			fn(r);
		});
	},
	_getListingMarker: function(fn) {
		if(listing.loadListingMarkerXHR) {
			listing.loadListingMarkerXHR.abort();
		}
		
		listing.loadListingMarkerXHR = $.get(getMarkersUrl, form.el.serialize(), function(r) {
			fn(r);
		});
	},
	removeMarkers: function() {
		listing.hideMarkers();
		listing.markers = {};
	},
	hideMarkers: function() {
		for(var i in listing.markers) {
			listing.markers[i].setMap(null);
		}
	},
	loadProject: function() {
		listing.projectInfoEl.html('');
		
		var id = form.fields.projectBuildingId.val();
		
		if(id) {
			$.get(loadProjectUrl, {id: id}, function(r) {
				listing.projectInfoEl.html(r);
			});
		}
	},
	resetProjectField: function() {
		form.fields.projectBuildingId.val('');
		$('#select2-' + form.fields.projectBuildingId.attr('id') + '-container').text(form.fields.projectBuildingId.find("option:first-child").text());
		
		listing.projectInfoEl.html('');
	},
	marker: function(position, map) {
		return new google.maps.Marker({
			position: position,
			map: map
		})
	},
	polygon: function(triangleCoords) {
		return new google.maps.Polygon({
		    paths: triangleCoords,
		    strokeColor: listing.color,
		    strokeOpacity: 0.8,
		    strokeWeight: 1,
		    fillColor: listing.color,
		    fillOpacity: 0.2
		});
	},
	polyline: function(path) {
		return new google.maps.Polyline({
			path: path,
			geodesic: true,
			strokeColor: listing.color,
			strokeOpacity: 0.5,
			strokeWeight: 6
		});
	},
	icon: function(counter, status) {
		var base = (counter > 1) ? '/site/map-image?s={status}&t=' + counter : '/images/marker-{status}.png';
		
		return base.replace('{status}', status);
	},
	parseCenter: function(center) {
		var c = JSON.parse(center);
		
		return {lat: c[0], lng: c[1]};
	},
	setCenter: function(latLng, offsetx, offsety) {
		var scale = Math.pow(2, listing.map.getZoom());
		var bounds = listing.map.getBounds();
		var projection = listing.map.getProjection();
		
		var nw = new google.maps.LatLng(bounds.getNorthEast().lat(), bounds.getSouthWest().lng());
		
		var worldCoordinateCenter = projection.fromLatLngToPoint(latLng);
		var pixelOffset = new google.maps.Point((offsetx/scale) || 0, (offsety/scale) || 0);
		
		var worldCoordinateNewCenter = new google.maps.Point(
			worldCoordinateCenter.x - pixelOffset.x,
			worldCoordinateCenter.y + pixelOffset.y
		);
		
		var center = projection.fromPointToLatLng(worldCoordinateNewCenter);
		
		listing.map.setCenter(center);
	},
	getBounds: function(offsetx, offsety) {
		var scale = Math.pow(2, listing.map.getZoom());
		var bounds = listing.map.getBounds();
		var projection = listing.map.getProjection();
		var northEast = bounds.getNorthEast();
		
		var worldCoordinateNorthEast = projection.fromLatLngToPoint(northEast);
		var pixelOffset = new google.maps.Point(((offsetx*2)/scale) || 0, ((offsety*2)/scale) || 0);
		var worldCoordinateNewNorthEast = new google.maps.Point(
			worldCoordinateNorthEast.x + pixelOffset.x,
			worldCoordinateNorthEast.y - pixelOffset.y
		);
		
		var newNorthEast = projection.fromPointToLatLng(worldCoordinateNewNorthEast);

		return new google.maps.LatLngBounds(bounds.getSouthWest(), newNorthEast);
	},
	buildInfoContent: function(product) {
		var img = listing.getImageUrl(product);
		var price = formatPrice(product.price);
		var addition = listing.getAdditionInfo(product);
		var address = listing.getAddress(product);
		
		var infoContent = '<div class="infoContent">' + 
								'<div class="img-show"><div><img src="'+img+'"></div></div>' +
								'<div class="address">' + address + '</div>' +
								'<div class="price">' + price + '</div>' +
								'<div class="addition">' + addition + '</div></div>' +
							'</div>';
		return infoContent;
	},
	getImageUrl: function(product) {
		if(product.file_name) {
			if(product.image_folder) {
				return '/store/' + product.image_folder + '/480x360/' + product.file_name;
			} else {
				return product.file_name.replace('745x510', '350x280');
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
		var city = listing.area['city'][Number(product.city_id)];
		var district = listing.area['district'][Number(product.district_id)];
		var address = [];
		var homeStreet = '';

		if(product.home_no) {
			homeStreet += product.home_no;
		}

		if(product.street_id) {
			homeStreet = homeStreet ? homeStreet + ' ' : '';
			homeStreet += listing.area['street'][Number(product.street_id)]['pre'] + ' ' + listing.area['street'][Number(product.street_id)]['name'];
		}
		
		if(homeStreet) {
			address.push(homeStreet);
		}
		
		if(product.ward_id) {
			address.push(listing.area['ward'][Number(product.ward_id)]['pre'] + ' ' + listing.area['ward'][Number(product.ward_id)]['name']);
		}
		
		address.push(district['pre'] + ' ' + district['name']);
		address.push(city['name']);
		
		return address.join(', ');
	}
}

listing.init();
listing.loadedAllResources();

function cfl(s) {
    return s.charAt(0).toUpperCase() + s.slice(1);
}