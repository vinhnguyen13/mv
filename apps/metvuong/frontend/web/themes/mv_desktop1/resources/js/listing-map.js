var listingMap = {
	polygons: [],
	areaMarkers: [],
	markers: [],
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
		
		listingMap.map.addListener('zoom_changed', function(){
			var zoomLevel = listingMap.getZoomLevel(this.getZoom());
			var levelValue = listingMap.getFocusLevelValue();
			
			if(zoomLevel >= levelValue.level) {
				if(listingMap.currentLevel != zoomLevel) {
					listingMap.currentLevel = zoomLevel;
					
					listingMap.removeArea();
					
					var areas = form.data[listingMap.dataMaps[listingMap.currentLevel]];
					
					listingMap.drawArea(areas);
				}
			}
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
			
			if(groups[area.id]) {
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
	}
}