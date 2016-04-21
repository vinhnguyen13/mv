function loaded() {
	$(document).ready(function(){
		var selectedCityId;
		var selectedCity;
		var districts = [];
		var geocoder = new google.maps.Geocoder();
		var log = $('#log');
		var cityEl = $('#city');
		var saveResult = {0: [], 1: [], 2: []};
		
		for(id in dataCities) {
			var city = dataCities[id];
			cityEl.append('<option value="' + id + '">' + city.name + '</option>');
		}
		
		cityEl.change(function(){
			selectedCityId = cityEl.val();
			selectedCity = dataCities[selectedCityId];

			var ds = selectedCity['districts'];
			
			for(districtId in ds) {
				var d = ds[districtId];
				
				var district = {center: d.center, id: districtId, name: d.pre + ' ' + d.name, wards: []};
				
				var ws = d['wards'];
				for(wardId in ws) {
					var w = ws[wardId];
					
					ward = {center: w.center, id: wardId, name: w.pre + ' ' + w.name};
					
					district['wards'].push(ward);
				}
				
				districts.push(district);
			}
		});
		
		$('#geo').click(function(){
			saveResult = {0: [], 1: [], 2: []};
			log.empty();
			
			if(!selectedCity) {
				alert('Chưa chọn tỉnh/thành');
			} else {
				geocode(0, selectedCityId, selectedCity.name, function() {
					nextDistrict(0);
				});
			}
		});
		
		$('#save').click(function(){
			var csrfName = $('#csrf').attr('name');
			var csrfToken = $('#csrf').val();
			
			var sendata = {data: saveResult};
			
			sendata[csrfName] = csrfToken;
			sendata['token'] = '2UWa0KRnCQAON9Js6kae';
			
			$.post($(this).data('url'), sendata, function(){
				alert('SAVE OK');
			});
		});
		
		function nextDistrict(index) {
			if(typeof districts[index] === 'undefined') {
				log.append('<div>END</div>');
				$('#save').prop('disabled', false);
				
				console.log(JSON.stringify(saveResult));
			} else {
				var district = districts[index];
				var wards = district['wards'];
				
				if(district.center) {
					nextWard(0, wards, index);
				} else {
					geocode(1, district.id, district.name, function(){
						nextWard(0, wards, index);
					});
				}
			}
		}
		
		function nextWard(index, wards, districtIndex) {
			if(typeof wards[index] === 'undefined') {
				nextDistrict(++districtIndex)
			} else {
				var ward = wards[index];
				
				if(ward.center) {
					nextWard(++index, wards, districtIndex);
				} else {
					geocode(2, ward.id, ward.name, function(){
						nextWard(++index, wards, districtIndex);
					});
				}
			}
		}
		
		function geocode(level, id, name, fn) {
			var logItem = $('<div style="padding: 2px; margin-left: ' + level * 20 + 'px">' + name + ': <span class="status">...</span></div>');
			
			log.append(logItem);
			
			geocoder.geocode({address: name}, function(results, status){
				var span = logItem.find('.status').html('');
				
				if(status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						span.append('<span style="color: blue">' + status + '</span>');
						
						var latLng = results[0].geometry.location.toUrlValue();
						
						span.append(' - <span>' + latLng + '</span>');
						
						saveResult[level].push({id: id, latLng: latLng});
					} else {
						span.append('<span style="color: yellow">NO RESULT</span>');
					}
				} else {
					span.append('<span style="color: red">' + status + '</span>');
				}
				
				setTimeout(function(){
					fn();
				}, 1000);
			});
		}
	});
}