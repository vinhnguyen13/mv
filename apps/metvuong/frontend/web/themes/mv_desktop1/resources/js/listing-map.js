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
		
		var collection = listingMap.dataMaps[levelValue.level];
		var area = listingMap.getFocusArea(levelValue.id, collection);
		
		var center = area.center ? JSON.parse(area.center) : [10.783091, 106.704899];

		var options = {center: {lat: center[0], lng: center[1]}, zoom: listingMap.zoomLevel[levelValue.level][1]};
		
		listingMap.map = new google.maps.Map(document.getElementById('map'), options);
		
		listingMap.drawArea([area]);
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
	drawArea: function(areas) {
		var levelValue = listingMap.getFocusLevelValue();
		var areaString = listingMap.idMaps[levelValue.level];
		var groups = listingMap.groupProductByArea(areaString);

		for(var i = 0; i < areas.length; i++) {
			var area = areas[i];
			
			listingMap.drawPolygon(area);
			listingMap.drawAreaMarker(area, groups[area.id]);
		}
	},
	drawAreaMarker: function(area, group) {
		console.log(area);
		console.log(group);
		var center = JSON.parse(area.center);
		center = {lat: center[0], lng: center[1]};
		
		var marker = new google.maps.Marker({
			position: center,
			map: listingMap.map,
		    icon: listingMap.icon(group.length, 0),
		    ids: group
		});
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
			google.maps.event.addListener(polygon, "mouseover", listingMap.polygonMouseover); 
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
		console.log(this.id);
	},
	icon: function(counter, status) {
		var base = (counter > 1) ? '/site/map-image?s={status}&t=' + counter : '/images/marker-{status}.png';
		
		return base.replace('{status}', status);
	}
}