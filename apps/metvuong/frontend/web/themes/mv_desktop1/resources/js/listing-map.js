var listingMap = {
	zoomLevelConst: {
		CITY: 0,
		DISTRICT: 1,
		WARD: 2,
		STREET: 3
	},
	zoomLevel: [[0,11],[12,14],[15,16],[17,21]],
	dataMaps: ['cities','districts','wards','streets'],
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
//		
//		var focusArea = listingMap.getFocusArea();
//		var dataCollection = listingMap.dataMap[focusArea.level];
//		var areas = form.data[]
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
	}
}