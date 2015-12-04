var apiIsLoaded = false;
var queueAsynInitials = [];

function apiLoaded() {
	apiIsLoaded = true;
	console.log(queueAsynInitials);
	for(i = 0; i < queueAsynInitials.length; i++) {
		console.log('call from queue');
		var asynInitial = queueAsynInitials[i];
		asynInitial.callback(new gmap(asynInitial.el, asynInitial.initialCenter));
	}
}

function asynInitial(el, initialCenter, callback) {
	if(apiIsLoaded) {
		console.log('call direct');
		var gmapInstance = new gmap(el, initialCenter);
		callback(gmapInstance);
	} else {
		var queueAsynInitial = {};
		queueAsynInitial.el = el;
		queueAsynInitial.initialCenter = initialCenter;
		queueAsynInitial.callback = callback;
		queueAsynInitials.push(queueAsynInitial);
	}
}

function gmap(el, initialCenter) {
	this.map = new google.maps.Map(el, {
		center: initialCenter,
	    zoom: 16
	});
	
	return this;
}