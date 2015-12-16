var apiIsLoaded = false;
var queueAsynInitials = [];
var mapCounter = 1;
var markerCounter = 1;

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
	var id = mapCounter;
	var self = this;
	var markers = {};
	var map = new google.maps.Map(el, options);
	
	self.addMarker = function(marker, setCenter) {
		if(marker.getMap()) {
			self.removeMarker(marker);
		}
		
		marker.setMap(self);
		
		if(setCenter) {
			map.setCenter(marker.getPosition());
		}
		
		var markerId = marker.getId();
		
		markers[markerId] = marker;
		
		return markerId;
	};
	
	self.removeAllMarker = function() {
		for(markerId in markers) {
			var marker = self.getMarker(markerId);
			marker.setMap(null);
		}
		markers = {};
	}
	
	self.removeMarker = function(marker) {
		delete markers[marker.getId()];
		marker.setMap(null);
	}
	
	self.getMarker = function(markerId) {
		return markers[markerId];
	};
	
	self.setCenter = function(latLng) {
		map.setCenter(latLng);
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
	
	self.getId = function() {
		return id;
	}
	
	self.getOriginal = function() {
		return map;
	};
	
	self.getBounds = function() {
		return map.getBounds();
	}
	
	mapCounter++;
	
	return self;
}

function InfoWindow(options) {
	var self = this;
	var infoWindow = new google.maps.InfoWindow(options);
	
	self.open = function(marker) {
		infoWindow.open(marker.getMap(), marker.getOriginal());
	}
	
	self.setContent = function(content) {
		infoWindow.setContent(content);
	}
}

function Marker(options) {
	var id = markerCounter;
	var self = this;
	var marker = new google.maps.Marker(options);
	var map;
	
	self.dragend = function(callback) {
		marker.addListener('dragend', function(evt) {
			callback({lat: evt.latLng.lat(), lng: evt.latLng.lng()});
		});
	};
	
	self.click = function(callback) {
		marker.addListener('click', function(evt) {
			callback({lat: evt.latLng.lat(), lng: evt.latLng.lng()});
		});
	}
	
	self.getPosition = function() {
		return marker.getPosition();
	};
	
	self.setMap = function(m) {
		map = m;
		
		if(m) {
			marker.setMap(m.getOriginal());
		} else {
			marker.setMap(null);
		}
	};
	
	self.addInfoWindow = function(infoWindow) {
		infoWindow.open(self);
	};
	
	self.getMap = function() {
		return marker.getMap();
	};
	
	self.getOriginal = function() {
		return marker;
	};
	
	self.getId = function() {
		return id;
	}
	
	self.setIcon = function(icon) {
		marker.setIcon(icon);
	}
	
	self.setZIndex = function(index) {
		marker.setZIndex(index);
	}
	
	markerCounter++;
	
	return this;
}
