var listingMap = {
	drawedMarkerDetail: false,
	polygons: [],
	areaMarkers: [],
	markers: {},
	zoomLevelConst: {
		CITY: 0,
		DISTRICT: 1,
		WARD: 2,
		STREET: 3
	},
	zoomLevel: [[0,11],[12,14],[15,16],[17,21]],
	dataMaps: ['cities','districts','wards','streets'],
	idMaps: ['city_id','district_id','ward_id','street_id'],
	init: function() {
		listingMap.initMap();

		listing.el.on('mouseenter', '.item-listing a', listingMap.listingMouseenter);
		listing.el.on('mouseleave', '.item-listing a', listingMap.listingMouseleave);
		
		form.formChange = function() {
			form._formChange(function(r){
				form.updateList(r.list, count(r.products));
				
				form.data.products = r.products;
				listingMap.buildMarkers();
				
				listingMap.draw();
			});
		};
	},
	listingMouseenter: function() {
		var self = $(this);
		var id = self.data('id') + '';
		
		$.data(this, 'mouseenterTimer', setTimeout(function(){
			var marker;
			
			if(listingMap.getZoomLevel(listingMap.map.getZoom()) < 3) {
				marker = listingMap.getAreaMarkerByProductId(id);
			} else {
				marker = listingMap.getMarker(id);
			}
			
			if(marker) {
				listingMap.focusMarker(marker);
			}
		}, 300));
	},
	listingMouseleave: function() {
		clearTimeout($.data(this, 'mouseenterTimer'));
		
		var id = $(this).data('id') + '';
		var marker;
		
		if(listingMap.getZoomLevel(listingMap.map.getZoom()) < 3) {
			marker = listingMap.getAreaMarkerByProductId(id);
		} else {
			marker = listingMap.getMarker(id);
		}
		
		if(marker) {
			marker.setIcon(listingMap.icon(marker.get('ids').length, 0));
			listingMap.infoWindow.setOffsetTop(40);
		}
	},
	focusMarker: function(marker) {
		marker.setZIndex(google.maps.Marker.MAX_ZINDEX++);
		marker.setIcon(listingMap.icon(marker.get('ids').length, 1));
		
		var position = marker.getPosition();
		
		var offsetX = $('.detail-listing-dt').position().left / 2;
		
		if(!listingMap.getBounds(offsetX, 0).contains(position)) {
			listingMap.setCenter(position, offsetX, 0);
		}
	},
	initMap: function() {
		m2Map.loaded();
		
		var levelValue = listingMap.getFocusLevelValue();
		
		listingMap.currentLevel = levelValue.level;
		
		var collection = listingMap.dataMaps[levelValue.level];
		var area = listingMap.getFocusArea(levelValue.id, collection);
		
		var center = area.center ? JSON.parse(area.center) : [10.783091, 106.704899];

		var options = {center: {lat: center[0], lng: center[1]}, zoom: listingMap.zoomLevel[levelValue.level][1]};
		
		listingMap.map = new google.maps.Map(document.getElementById('map'), options);
		listingMap.infoWindow = new m2Map.InfoWindow({offsetTop: 40});
		
		var areas = {};
		areas[area.id] = area;
		
		listingMap.drawArea(areas);
		
		listingMap.buildMarkers();

		listingMap.infoWindow = new m2Map.InfoWindow({offsetTop: 40});
		
		listingMap.infoWindowMore = new google.maps.InfoWindow();
		listingMap.infoWindowMore.addListener('domready', function() {
			listingMap.infoWindow.close();
			var marker = listingMap.infoWindowMore.anchor;
			google.maps.event.clearListeners(marker, 'mouseover');
			
			var infoWindowClick = google.maps.event.addListener(listingMap.infoWindowMore, 'closeclick', detachEvent);
			var infoWindowPositionChange = google.maps.event.addListener(listingMap.infoWindowMore, 'position_changed', detachEvent);
			
			var mapClick = google.maps.event.addListener(listingMap.map, 'mousedown', function(){
				listingMap.infoWindowMore.close();
				detachEvent();
			});
			
			function detachEvent() {
				marker.addListener('mouseover', listingMap.markerOver);
				google.maps.event.removeListener(infoWindowClick);
				google.maps.event.removeListener(infoWindowPositionChange);
				google.maps.event.removeListener(mapClick);
			}
		});
		
		listingMap.map.addListener('zoom_changed', function(){
			var zoomLevel = listingMap.getZoomLevel(this.getZoom());
			var levelValue = listingMap.getFocusLevelValue();
			
			if(zoomLevel >= levelValue.level) {
				if(listingMap.currentLevel != zoomLevel) {
					listingMap.draw();
				}
			}
		});
		
		listing.detail = function(id) {
			listing._detail(id);
			
			listingMap.map.addListener('mousedown', function(){
				listing.closeDetail();
				google.maps.event.clearListeners(listingMap.map, 'mousedown');
			});
		};
	},
	draw: function() {
		var zoomLevel = listingMap.getZoomLevel(listingMap.map.getZoom());
		var levelValue = listingMap.getFocusLevelValue();
		
		listingMap.currentLevel = zoomLevel;
		
		listingMap.removeArea();
		
		if(listingMap.currentLevel < 3) {
			if(listingMap.drawedMarkerDetail) {
				listingMap.removeDetailMarkers();
				listingMap.drawedMarkerDetail = false;
			}
			
			var areas = {};

			if(zoomLevel - levelValue.level == 1) {
				var compareId = listingMap.idMaps[levelValue.level];
				for(var i in form.data[listingMap.dataMaps[listingMap.currentLevel]]) {
					if(form.data[listingMap.dataMaps[listingMap.currentLevel]][i][compareId] == levelValue.id) {
						areas[i] = form.data[listingMap.dataMaps[listingMap.currentLevel]][i];
					}
				}
			} else if(zoomLevel == levelValue.level) {
				areas[levelValue.id] = form.data[listingMap.dataMaps[zoomLevel]][levelValue.id];
			} else {
				areas = form.data[listingMap.dataMaps[listingMap.currentLevel]];
			}
			
			listingMap.drawArea(areas);
		} else {
			listingMap.drawDetail();
			listingMap.drawedMarkerDetail = true;
		}
	},
	buildMarkers: function() {
		
		for(var i in listingMap.markers) {
			listingMap.markers[i].setMap(null);
		}
		
		listingMap.markers = {};
		
		for(var i in form.data.products) {
			var product = form.data.products[i];
			var index = product.lat + '-' + product.lng;
			var marker = listingMap.markers[index];
			
			if(marker) {
				var ids = marker.get('ids');
				ids.push(product.id);
				marker.set('ids', ids);
				marker.setIcon(listingMap.icon(ids.length, 0));
			} else {
				marker = new google.maps.Marker({
					position: {lat: Number(product.lat), lng: Number(product.lng)},
					map: null,
				    icon: listingMap.icon(1, 0)
				});
				
				marker.set('ids', [product.id]);
				marker.addListener('mouseover', listingMap.markerOver);
				marker.addListener('mouseout', listingMap.markerOut);
				marker.addListener('click', listingMap.markerClick);
				
				listingMap.markers[index] = marker;
			}
		}
	},
	drawDetail: function() {
		for(var i in listingMap.markers) {
			listingMap.markers[i].setMap(listingMap.map);
		}
	},
	removeDetailMarkers: function() {
		for(var i in listingMap.markers) {
			listingMap.markers[i].setMap(null);
		}
	},
	markerOver: function() {
		this.setZIndex(google.maps.Marker.MAX_ZINDEX++);
		var ids = this.get('ids');
		var id = ids[0];
		
		var product = form.data.products[id];
		var infoContent = $('<div class="info-wrap-single">' + listingMap.buildInfoContent(product) + '</div>');
		
//		if(ids.length > 1) {
//			infoContent.append('<div class="more">...</div>');
//		}
		
		infoContent.append('<div class="arrow"></div>');
		
		listingMap.infoWindow.setContent(infoContent.get(0));
		listingMap.infoWindow.open(this);
	},
	markerOut: function() {
		listingMap.infoWindow.close();
		listingMap.infoWindow.setOffsetTop(40);
	},
	markerClick: function() {
		var ids = this.get('ids');
		
		if(ids.length == 1) {
			listing.detail(ids[0]);
		} else {
			var infoContentWrap = $('<div class="info-wrap-multiple"></div>');
			for(i = 0; i < ids.length; i++) {
				var id = ids[i];
				var product = form.data.products[id];
				var infoContent = $(listingMap.buildInfoContent(product));
				listingMap.attachInfoItemClick(infoContent, id);
				infoContentWrap.append(infoContent);
			}
			
			listingMap.infoWindowMore.setContent(infoContentWrap.get(0));
			listingMap.infoWindowMore.open(listingMap.map, this);
		}	
	},
	attachInfoItemClick: function(item, id) {
		item.click(function(){
			listing.detail(id);
		});
	},
	getZoomLevel: function(zoom) {
		var i = 0;
		
		for(i; i <= listingMap.zoomLevel.length; i++) {
			if(zoom >= listingMap.zoomLevel[i][0] && zoom <= listingMap.zoomLevel[i][1]) {
				break;
			}
		}
		
		return i;
	},
	getFocusArea: function(id, collection) {
		return form.data[collection][id];
	},
	getFocusLevelValue: function() {
		var level, id;

		for(var c in listingMap.zoomLevelConst) {
			var el = $('#' + c.toLowerCase() + '_id');
			if(el.val()) {
				level = listingMap.zoomLevelConst[c];
				id = Number(el.val());
			}
		}
		
		return {level: level, id: id};
	},
	removeArea: function() {
		for(var i = 0; i < listingMap.polygons.length; i++) {
			google.maps.event.clearInstanceListeners(listingMap.polygons[i]);
			listingMap.polygons[i].setMap(null);
		}
		
		for(var i = 0; i < listingMap.areaMarkers.length; i++) {
			listingMap.areaMarkers[i].setMap(null);
		}

		listingMap.polygons = [];
		listingMap.areaMarkers = [];
		
		listingMap.infoWindow.close();
	},
	drawArea: function(areas) {
		var areaString = listingMap.idMaps[listingMap.currentLevel];

		var groups = listingMap.groupProductByArea(areaString);

		for(var i in areas) {
			var area = areas[i];
			
			if(area.geometry) {
				listingMap.drawPolygon(area);
			}
			
			if(groups[area.id] && area.center) {
				listingMap.drawAreaMarker(area, groups[area.id]);
			}
		}
	},
	drawAreaMarker: function(area, group) {

		var center = JSON.parse(area.center);
		center = {lat: center[0], lng: center[1]};
		
		var marker = new google.maps.Marker({
			position: center,
			map: listingMap.map,
		    icon: listingMap.icon(group.length, 0),
		    id: area.id,
		    ids: group
		});
		
		listingMap.areaMarkers.push(marker);
		
		marker.addListener('mouseover', function(){
			var name = area.pre ? area.pre + ' ' + area.name : area.name;
			
			listingMap.areaMarkerMouseover.call(this, name);
		});
		
		marker.addListener('mouseout', function(){
			listingMap.infoWindow.close();
			listingMap.infoWindow.setOffsetTop(40);
		});
		
		marker.addListener('click', function(){
			var zoomLevel = listingMap.getZoomLevel(listingMap.map.getZoom());
			var nextZoomLevel = zoomLevel + 1;
			listingMap.map.setZoom(listingMap.zoomLevel[nextZoomLevel][0]);
			listingMap.map.setCenter(this.getPosition());
		});
	},
	areaMarkerMouseover: function(name) {
		if(this.ids.length > 9999) {
			listingMap.infoWindow.setOffsetTop(60);
		} else if(this.ids.length > 999) {
			listingMap.infoWindow.setOffsetTop(50);
		}
		
		var infoContent = '<div class="info-wrap-single"><div style="padding: 6px 12px; font-weight: bold; font-size: 13px; white-space: nowrap">' + name + '</div><div class="arrow"></div></div>';
		listingMap.infoWindow.setContent(infoContent);
		listingMap.infoWindow.open(this);
	},
	drawPolygon: function(area) {
		if(area.geometry) {
			var color = '#00a769';
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
			
			var polygon = new google.maps.Polygon({
			    paths: triangleCoords,
			    strokeColor: color,
			    strokeOpacity: 0.8,
			    strokeWeight: 1,
			    fillColor: color,
			    fillOpacity: 0.2,
			    id: area.id
			});
			
			polygon.setMap(listingMap.map);
			listingMap.polygons.push(polygon);
			
			polygon.addListener("mouseover", listingMap.polygonMouseover);
			polygon.addListener("mouseout", listingMap.polygonMouseout);
			polygon.addListener("click", listingMap.polygonClick);
		}
	},
	groupProductByArea: function(idString) {
		var groups = {};
		
		
		for(var i in form.data.products) {
			var product = form.data.products[i];
			var areaId = product[idString];
			
			if(groups[areaId]) {
				groups[areaId].push(i);
			} else {
				groups[areaId] = [i];
			}
		}
		
		return groups;
	},
	polygonMouseover: function() {
		if(marker = listingMap.getAreaMarker(this.id)) {
			google.maps.event.trigger(marker, 'mouseover');
		}
	},
	polygonMouseout: function() {
		if(marker = listingMap.getAreaMarker(this.id)) {
			google.maps.event.trigger(marker, 'mouseout');
		}
	},
	polygonClick: function() {
		if(marker = listingMap.getAreaMarker(this.id)) {
			google.maps.event.trigger(marker, 'click');
		}
	},
	icon: function(counter, status) {
		var base = (counter > 1) ? '/site/map-image?s={status}&t=' + counter : '/images/marker-{status}.png';
		
		return base.replace('{status}', status);
	},
	getAreaMarker: function(id) {
		var marker;

		for(var i = 0; i<= listingMap.areaMarkers.length; i++) {
			if(listingMap.areaMarkers[i] && listingMap.areaMarkers[i].id == id) {
				marker = listingMap.areaMarkers[i];
				break;
			}
		}
		
		return marker;
	},
	_getMarker: function(markers, id) {
		var marker;

		for(var i in markers) {
			if(markers[i].ids.indexOf(id) !== -1) {
				marker = markers[i];
				break;
			}
		}
		
		return marker;
	},
	getMarker: function(id) {
		return listingMap._getMarker(listingMap.markers, id);
	},
	getAreaMarkerByProductId: function(id) {
		return listingMap._getMarker(listingMap.areaMarkers, id);
	},
	buildInfoContent: function(product) {
		var img = listingMap.getImageUrl(product);
		var price = formatPrice(product.price);
		var addition = listingMap.getAdditionInfo(product);
		var address = listingMap.getAddress(product);
		
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
		var city = form.data.cities[Number(product.city_id)];
		var district = form.data.districts[Number(product.district_id)];
		var address = [];
		var homeStreet = '';

		if(product.home_no) {
			homeStreet += product.home_no;
		}

		if(product.street_id) {
			homeStreet = homeStreet ? homeStreet + ' ' : '';
			homeStreet += form.data.streets[Number(product.street_id)]['pre'] + ' ' + form.data.streets[Number(product.street_id)]['name'];
		}
		
		if(homeStreet) {
			address.push(homeStreet);
		}
		
		if(product.ward_id) {
			address.push(form.data.wards[Number(product.ward_id)]['pre'] + ' ' + form.data.wards[Number(product.ward_id)]['name']);
		}
		
		address.push(district['pre'] + ' ' + district['name']);
		address.push(city['name']);
		
		return address.join(', ');
	},
	setCenter: function(latLng, offsetx, offsety) {
		var scale = Math.pow(2, listingMap.map.getZoom());
		var bounds = listingMap.map.getBounds();
		var projection = listingMap.map.getProjection();
		
		var nw = new google.maps.LatLng(bounds.getNorthEast().lat(), bounds.getSouthWest().lng());
		
		var worldCoordinateCenter = projection.fromLatLngToPoint(latLng);
		var pixelOffset = new google.maps.Point((offsetx/scale) || 0, (offsety/scale) || 0);
		
		var worldCoordinateNewCenter = new google.maps.Point(
			worldCoordinateCenter.x - pixelOffset.x,
			worldCoordinateCenter.y + pixelOffset.y
		);
		
		var center = projection.fromPointToLatLng(worldCoordinateNewCenter);
		
		listingMap.map.setCenter(center);
	},
	getBounds: function(offsetx, offsety) {
		var scale = Math.pow(2, listingMap.map.getZoom());
		var bounds = listingMap.map.getBounds();
		var projection = listingMap.map.getProjection();
		var northEast = bounds.getNorthEast();
		
		var worldCoordinateNorthEast = projection.fromLatLngToPoint(northEast);
		var pixelOffset = new google.maps.Point(((offsetx*2)/scale) || 0, ((offsety*2)/scale) || 0);
		var worldCoordinateNewNorthEast = new google.maps.Point(
			worldCoordinateNorthEast.x + pixelOffset.x,
			worldCoordinateNorthEast.y - pixelOffset.y
		);
		
		var newNorthEast = projection.fromPointToLatLng(worldCoordinateNewNorthEast);

		return new google.maps.LatLngBounds(bounds.getSouthWest(), newNorthEast);
	}
}