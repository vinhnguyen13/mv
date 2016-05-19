$(document).ready(function(){
	$('select').change(function(){
		$.get(url, {city: $(this).val()}, function(r){
			var city = r.city;
			var districts = r.districts;
			var pathsEncoded = city.geometry ? encodeGeometry(city.geometry) : '';
			
			updateGeometry('city', city.id, pathsEncoded, function(r){
				console.log('-Update: ' + city.name);
				console.log('--------------------------------------------------------------------------------------------------------');
				updateDistricts(districts, 0, function(){
					console.log('--------------------------------------------------------------------------------------------------------');
					console.log('-Update All District finished');
				});
			});
		});
	});
	
	function updateDistricts(districts, key, fn) {
		if(key < districts.length) {
			var district = districts[key];
			var pathsEncoded = district.geometry ? encodeGeometry(district.geometry) : '';
			key++;
			
			updateGeometry('district', district.id, pathsEncoded, function(r){
				console.log('--------Update: ' + district.pre + ' ' + district.name);
				updateWards(r.wards, 0, function(){

					console.log('--------Update All Wards of ' + district.pre + ' ' + district.name + ' finished');
					console.log('--------------------------------------------------------------------------------------------------------');
					updateDistricts(districts, key, fn);
				});
			});
		} else {
			fn();
		}
	}
	
	function updateWards(wards, key, fn) {
		if(key < wards.length) {
			var ward = wards[key];
			var pathsEncoded = ward.geometry ? encodeGeometry(ward.geometry) : '';
			key++;
			
			updateGeometry('ward', ward.id, pathsEncoded, function(r){
				console.log('----------------Update: ' + ward.pre + ' ' + ward.name);
				updateWards(wards, key, fn);
			});
		} else {
			fn();
		}
	}
	
	function encodeGeometry(geometry) {
		if(geometry) {
			var geo = JSON.parse(geometry);
			var paths = [];
			
			for(var i = 0; i < geo.length; i++) {
				var path = [];

				for(var j = 0; j < geo[i].length; j++) {
					path[j] = new google.maps.LatLng(geo[i][j][0], geo[i][j][1]);
				}

				paths[i] = google.maps.geometry.encoding.encodePath(path);
			}
			return JSON.stringify(paths);
		} else {
			return '';
		}
	}
	
	function updateGeometry(table, id, pathsEncoded, fn) {
		$.post(url, {table: table, id: id, paths: pathsEncoded}, function(r){
			fn(r);
		});
	}
});