var map;
var marker;
var initialCenter;

function initMap() {
	initialCenter = {lat: Number($('#buildingproject-bplat').val()), lng: Number($('#buildingproject-bplng').val())};
	
	map = new google.maps.Map(document.getElementById('map'), {
		center: initialCenter,
	    zoom: 16
	});
	
	buildMaker();
	buildGeoCodeButton();
}

function buildMaker() {
	marker = new google.maps.Marker({
		draggable: true,
	    animation: google.maps.Animation.DROP,
	    map: map,
	    position: initialCenter
	});
	
	var infowindow = new google.maps.InfoWindow({content: '<div style="font-size: 12px;">Di chuyển con trỏ đến đúng vị trí mong muốn</div>'});
	infowindow.open(map, marker);
	
	marker.addListener('dragend', function(evt) {
		$('#buildingproject-bplat').val(evt.latLng.lat());
		$('#buildingproject-bplng').val(evt.latLng.lng());
	});
	marker.addListener('mousedown', function() {
		infowindow.close();
	});
}

function buildGeoCodeButton() {
	var geoCodeButton = document.createElement('DIV');
	geoCodeButton.style.color = 'rgb(25,25,25)';
	geoCodeButton.style.backgroundColor = '#FFF';
	geoCodeButton.style.fontFamily = 'Roboto,Arial,sans-serif';
	geoCodeButton.style.padding = '12px';
	geoCodeButton.style.paddingRight = '12px';
	geoCodeButton.style.paddingTop = '6px';
	geoCodeButton.style.paddingBottom = '6px';
	geoCodeButton.style.fontWeight = 'bold';
	geoCodeButton.style.cursor = 'pointer';
	geoCodeButton.style.boxShadow = '1px 1px 2px rgba(0, 0, 0, 0.2)';
	geoCodeButton.draggable = 'false';
	geoCodeButton.appendChild(document.createTextNode('Định vị theo địa chỉ đã nhập'));
	
	geoCodeButton.onclick = function() {
		var address = $('#buildingproject-bplocation').val();
		
		if(address != '') {
			var geocoder = new google.maps.Geocoder;
			geocoder.geocode({
				address: $('#buildingproject-bplocation').val(),
			}, function (response) {
				var latLng = initialCenter;
				
				if(response.length > 0) {
					response = response[0];
					var latLng = {lat: response.geometry.location.lat(), lng: response.geometry.location.lng()};
				}
				
				marker.setPosition(latLng);
				map.setCenter(latLng);
		    });
		}
	};

	map.controls[google.maps.ControlPosition.TOP_CENTER].push(geoCodeButton);
}