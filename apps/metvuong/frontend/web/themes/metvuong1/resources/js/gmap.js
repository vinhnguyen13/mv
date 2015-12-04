var apiIsLoaded = false;
var queueAsynInitials = [];

function apiLoaded() {
	Gmap.prototype.geocoder = new google.maps.Geocoder;
	
	apiIsLoaded = true;
	
	for(i = 0; i < queueAsynInitials.length; i++) {
		var asynInitial = queueAsynInitials[i];
		asynInitial.callback(new Gmap(asynInitial.el, asynInitial.initialCenter));
	}
}

function asynInitial(el, initialCenter, callback) {
	if(apiIsLoaded) {
		callback(new Gmap(el, initialCenter));
	} else {
		var queueAsynInitial = {};
		queueAsynInitial.el = el;
		queueAsynInitial.initialCenter = initialCenter;
		queueAsynInitial.callback = callback;
		queueAsynInitials.push(queueAsynInitial);
	}
}

function Gmap(el, options) {
	var self = this;
	
	self.map = new google.maps.Map(el, options);
	
	self.addMarker = function(marker, setCenter) {
		var marker = marker._marker;
		marker.setMap(self.map);
		
		if(setCenter) {
			self.map.setCenter(marker.getPosition());
		}
	};
	
	self.geocode = function(address, callback) {
		self._geocode({address: address}, function(response){
			if(response.length > 0) {
				callback({lat: response[0].geometry.location.lat(), lng: response[0].geometry.location.lng()});
			} else {
				callback(false);
			}
		});
	};
	
	self._geocode = function(options, callback) {
		self.geocoder.geocode(options, function(response){
			callback(response);
		});
	};
	
	return self;
}

function InfoWindow() {
	
}

function Marker(options) {
	var self = this;
	
	self._marker = new google.maps.Marker(options);
	
	self.dragend = function(callback) {
		self._marker.addListener('dragend', function(evt) {
			callback({lat: evt.latLng.lat(), lng: evt.latLng.lng()});
		});
	};
	
	return this;
}
