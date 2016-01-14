var m2Map = {
	isLoaded: false,
	queues: [],
	InfoWindow: null,
	extend: function (a, b) {
		for(var key in b)
	        if(b.hasOwnProperty(key))
	            a[key] = b[key];
	    return a;
	},
	loaded: function() {
		this.customInfoWindow();
		
		this.isLoaded = true;
		
		for(i = 0; i < this.queues.length; i++) {
			var queue = this.queues[i];
			queue.callback(new google.maps.Map(queue.el, queue.options));
		}
	},
	initial: function(el, options, callback) {
		if(this.isLoaded) {
			callback(new google.maps.Map(el, options));
		} else {
			var queue = {
				el: el,
				options: options,
				callback: callback
			};
			this.queues.push(queue);
		}
	},
	customInfoWindow: function() {
		m2Map.InfoWindow = function(opts) {
			google.maps.OverlayView.call(this);
			
			this.identifyMarkerString = 'CustomInfoWindowID';
			this.identifyMarkerCounter = 0;
			
			var defaultOptions = {
				content: document.createElement('div'),
				offsetTop: 28
			};
			
			opts = opts || {};
			
			this.options = m2Map.extend(defaultOptions, opts);
			this.contentHolder = this.options.content;

			this.viewHolder = document.createElement('div');
			this.viewHolder.style.border = '0px none';
			this.viewHolder.style.position = 'absolute';
			
			this.isMoved = false;
			this.dragstart = null;
		};
		
		m2Map.InfoWindow.prototype = new google.maps.OverlayView();
		
		m2Map.InfoWindow.prototype.addListener = function(e, fn) {
			return google.maps.event.addDomListener(this.viewHolder, e, fn);
		};
		
		m2Map.InfoWindow.prototype.getDiv = function() {
			return this.viewHolder;
		};
		
		m2Map.InfoWindow.prototype.onAdd = function() {
			var self = this;
			var map = this.getMap();
			this.dragstart = map.addListener('dragstart', function() {
				self.isMoved = true;
				var tempEvent = self.addListener('click', function() {
					self.isMoved = false;
					google.maps.event.removeListener(tempEvent);
				});
			});
			
			var panes = this.getPanes();

			panes.floatPane.appendChild(this.viewHolder);
			
			this.setContent(this.contentHolder);
		};
		
		m2Map.InfoWindow.prototype.onRemove = function() {
			this.viewHolder.parentNode.removeChild(this.viewHolder);
			this.isMoved = false;
			google.maps.event.removeListener(this.dragstart);
		};
		
		m2Map.InfoWindow.prototype.draw = function() {
			if(this.position) {
				var position = this.getProjection().fromLatLngToDivPixel(this.position);
				this.viewHolder.style.left = (position.x - (this.viewHolder.clientWidth / 2)) + 'px';
				this.viewHolder.style.top = (position.y - this.viewHolder.clientHeight - this.options.offsetTop) + 'px';
			}
		};
		
		m2Map.InfoWindow.prototype.open = function(marker) {
			if(marker.get(this.identifyMapString) != this.identifyMarkerCounter) {
				this.position = marker.getPosition();
				marker.set(this.identifyMapString, ++this.identifyMarkerCounter);
				this.setMap(marker.getMap());
			}
		};
		
		m2Map.InfoWindow.prototype.close = function() {
			this.identifyMarkerCounter++;
			this.setMap(null);
		};
		
		m2Map.InfoWindow.prototype.setContent = function(content) {
			this.contentHolder = content;
			
			if(this.viewHolder.parentNode) {
				if(typeof content == 'string') {
					this.viewHolder.innerHTML = content;
				} else {
					this.viewHolder.innerHTML = '';
					this.viewHolder.appendChild(content);
				}
			}
		};
	}
};