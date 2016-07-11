/*
 * Custom Marker
 */
function ci(src) {
	var image = document.createElement("img");
	image.src = src;
	
	return image;
}

function measureText(text, font) {
	var span = document.createElement("span");
	span.style.font = font;
	span.style.visibility = 'hidden';
	span.style.position = 'fixed';
	span.style.zIndex = -1;
	span.appendChild(document.createTextNode(text));
	document.body.appendChild(span);
	
	var width = span.getBoundingClientRect().width;
	
	document.body.removeChild(span);
	
	return width;
}

function createMarkerIcon(image, label, options) {
	var canvas = document.createElement("canvas");
	var context = canvas.getContext("2d");
	
	canvas.width = image.width;
	canvas.height = image.height;
	
	context.drawImage(image, 0, 0, image.width, image.height);
	context.font = options.font;
	context.fillStyle = options.color;
	context.fillText(label, options.left, options.top);
	
	return canvas.toDataURL();
}

var mi = {
	PADDING: 8,
	MIN_WIDTH: 24,
	IMG: null,
	IMG_HOVER: null,
	COLOR: 'black',
	COLOR_HOVER: 'white',
	FONT: '12px Arial',
	ARROW_HEIGHT: 0,
	TEXT_HEIGHT: 0,
	MAX_WIDTH: 0,
	MAX_HEIGHT: 0,
	create: function(label, hover) {
		var image = hover ? mi.IMG_HOVER : mi.IMG;
		var color = hover ? mi.COLOR_HOVER : mi.COLOR;
		var options = {color: color, font: mi.FONT};
		var measureWidth = Math.round(measureText(label, options.font));
				
		var scaleWidth = measureWidth + (mi.PADDING * 2);
		
		if(scaleWidth <= mi.MIN_WIDTH) {
			scaleWidth = mi.MIN_WIDTH;
			options.left = (scaleWidth - measureWidth) / 2;
		} else {
			options.left = mi.PADDING;
		}
		
		var scaleHeight = Math.round((scaleWidth * image.height) / image.width);

		var arrowHeight = scaleHeight * mi.ARROW_HEIGHT;
		options.top = ((scaleHeight - arrowHeight) / 2) + mi.TEXT_HEIGHT;
		
		image.width = scaleWidth;
		image.height = scaleHeight;
		
		if(mi.MAX_WIDTH < scaleWidth) {
			mi.MAX_WIDTH = scaleWidth;
			mi.MAX_HEIGHT = scaleHeight;
		}

		return {icon: createMarkerIcon(image, label, options), width: scaleWidth, height: scaleHeight};
	}
}

mi.IMG = ci("data:image/png;base64,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");
mi.IMG_HOVER = ci("data:image/png;base64,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");
mi.ARROW_HEIGHT = 4 / 24;
mi.TEXT_HEIGHT = 9 / 2;


/*
 * Area
 */
function decodeGeometry(encodeGeometry) {
	encodeGeometry = JSON.parse(encodeGeometry);
	
	var count = encodeGeometry.length;
	var paths = [];
	
	for(var i = 0; i < count; i++) {
		paths.push(google.maps.geometry.encoding.decodePath(encodeGeometry[i]));
	}
	
	return paths;
}

function Area(attrs, type) {
	this.attrs = attrs;
	this.type = type;
}

Area.prototype.getPaths = function() {
	var attrs = this.attrs;
	var paths;
	
	if(attrs.geometry) {
		paths = decodeGeometry(attrs.geometry);
	}

	return paths;
};

Area.prototype.getBounds = function() {
	var paths = this.getPaths();
	var bounds;
	
	if(paths) {
		bounds = new google.maps.LatLngBounds();
		
		var pathsLength = paths.length;
		
		for(var i = 0; i < pathsLength; i++) {
			var path = paths[i];
			var pathLength = path.length;
			
			for(var j = 0; j < pathLength; j++) {
				bounds.extend(path[j]);
			}
		}
		
	}

	return bounds;
};

Area.prototype.getCenter = function() {
	var attrs = this.attrs;
	var center;
	
	if(attrs.center) {
		centerP = JSON.parse(attrs.center);
		center = new google.maps.LatLng(centerP[0], centerP[1]);
	} else {
		var bounds = this.getBounds();

		if(bounds) {
			center = bounds.getCenter();
		}
	}
	
	return center;
};

Area.prototype.getName = function() {
	var attrs = this.attrs;
	
	return attrs.pre ? attrs.pre + ' ' + attrs.name : attrs.name;
};

Area.prototype.draw = function(map) {
	var paths = this.getPaths();
	var self = this;
	
	if(this.type == 'street') {
		if(paths) {
			this.poly = new google.maps.Polyline({
				map: map,
			    path: paths[0],
			    strokeColor: m2Map.polygonColor,
			    strokeOpacity: 0.8,
			    strokeWeight: 6,
				geodesic: true
			});
		}
	} else {
		if(paths) {
			this.poly = new google.maps.Polygon({
				map: map,
			    paths: paths,
			    strokeColor: m2Map.polygonColor,
			    strokeOpacity: 0.8,
			    strokeWeight: 1,
			    fillColor: m2Map.polygonColor,
			    fillOpacity: 0.2
			});

			this.poly.addListener("mouseover", function() {
				if(self.marker) {
					self.marker.set('clickable', false);
				}
				self.mouseover();
			});
			this.poly.addListener("mouseout", function() {
				if(self.marker) {
					self.marker.set('clickable', true);
				}
				self.mouseout();
			});

			this.poly.addListener("click", function() {
				self.nextZoomLevel();
			});
		}
		
		var center = this.getCenter();

		if(center && this.attrs.total > 0) {
			this.marker = new google.maps.Marker({
				map: map,
				position: center
			});
			
			m2Map.setIcon(this.marker, this.attrs.total, 0);

			this.marker.addListener("mouseover", function() {self.mouseover();});
			this.marker.addListener("mouseout", function() {self.mouseout();});
			this.marker.addListener("click", function() {
				self.nextZoomLevel();
			});
		}
	}
};

Area.prototype.remove = function() {
	if(this.poly) {
		this.poly.setMap(null);
	}
	if(this.marker) {
		this.marker.setMap(null);
	}
};

Area.prototype.mouseover = function() {
	m2Map.infoBoxHover.setContent('<div class="info-wrap-single"><div style="padding: 6px 12px; font-weight: bold; font-size: 13px; white-space: nowrap">' + this.getName() + '</div><div class="info-arrow"></div></div>');
	
	if(this.marker) {
		m2Map.infoBoxHover.open(this.marker);
	} else {
		m2Map.infoBoxHover.open(m2Map.map, this.getCenter());
	}
};
Area.prototype.mouseout = function() {
	m2Map.infoBoxHover.close();
};

Area.prototype.nextZoomLevel = function() {
	var zoom = m2Map.map.getZoom();
	var nextZoom = zoom + 1;
	var initZoom = form.af.filter(s.iz).val();
	var currentFocus = form.getFocusLocation();
	var currentfocusAreaType = m2Map.getZoomAreaLevel(zoom, initZoom, currentFocus.type);
	var focusAreaType = currentFocus.type;
	
	while(m2Map.getZoomAreaLevel(nextZoom, initZoom, focusAreaType) == currentfocusAreaType && nextZoom < m2Map.detailZoomLevel) {
		nextZoom++;
	}

	m2Map.map.setCenter(this.getCenter());
	m2Map.map.setZoom(nextZoom);
};


/*
 * Products
 */
function Product(attrs) {
	this.attrs = attrs;
}

Product.prototype.getMarkerKey = function() {
	return this.attrs.lat + '-' + this.attrs.lng;
};

Product.prototype.getPosition = function() {
	return new google.maps.LatLng(this.attrs.lat, this.attrs.lng);
};

Product.prototype.getImage = function() {
	if(this.attrs.f) {
		if(this.attrs.d) {
			return '/store/' + this.attrs.d + '/240x180/' + this.attrs.f;
		} else {
			return this.attrs.f.replace('745x510', '350x280');
		}
	} else {
		return '/themes/metvuong2/resources/images/default-ads.jpg';
	}
};

Product.prototype.getPrice = function() {
	return formatPrice(this.attrs.price) + ' ' + lajax.t('VND');
};

Product.prototype.getAddress = function() {
	return this.attrs.a;
};

Product.prototype.getAdditionInfo = function() {
	var addition = [];
	
	if(this.attrs.area) {
		addition.push('<span class="icon-mv"><span class="icon-page-1-copy"></span></span>' + this.attrs.area + 'm<sup>2</sup>');
	}
	
	if(this.attrs.room_no && this.attrs.room_no != '0') {
		addition.push('<span class="icon-mv"><span class="icon-bed-search"></span></span>' + this.attrs.room_no);
	}
	if(this.attrs.toilet_no && this.attrs.toilet_no != '0') {
		addition.push('<span class="icon-mv"><span class="icon-icon-bathroom"></span></span>' + this.attrs.toilet_no);
	}

	return addition.join('<i class="s"></i>');
};

Product.prototype.buildInfoContent = function() {
	var img = this.getImage();
	var price = this.getPrice();
	var address = this.getAddress();
	var addition = this.getAdditionInfo();
	
	return '<div data-id="' + this.attrs.id + '" class="clearfix item">' + 
				'<div class="img-show left"><div><img src="'+img+'"></div></div>' +
				'<div class="right"><div class="rest-inside">' +
					'<div class="address">' + address + '</div>' +
					'<div class="price">' + price + '</div>' +
					'<div class="addition">' + addition + '</div>' +
				'</div></div>' +
			'</div>';
};


/*
 * M2Map
 */
s.rect = '#rect';
s.ra = '#ra';
s.rm = '#rm';
s.rl = '#rl';
s.raK = '#ra_k';
s.iz = '#iz';
s.z = '#z';
s.c = '#c';
s.page = '#page';
s.did = '#did';

var contentHolder = $('#content-holder');
var detailListingWrap = $('.detail-listing-dt');

var m2Map = {
	infoDetailWidth: 174,
	infoDetailHeight: 98,
	loadingTimeout: null,
	loadingList: $('#loading-list'),
	progressBar: $('#progress-bar'),
	mapEl: $('#map'),
	polygonColor: '#00a769',
	map: null,
	markers: {},
	areas: {},
	areasLevel: {city: 3, district: 2, ward: 1},
	deteilZoomLevelDefault: 16,
	detailZoomLevel: 16,
	infoBoxHover: null,
	infoBoxDetailHover: null,
	infoBoxDetailClick: null,
	boundsChangedEvent: null,
	zoomChangedEvent: null,
	closeDetailListener: null,
	currentDrawState: null,
	markerIconCached: {},
	shape: {coords: [0, 0, 24, 28], type: 'rect'},
	wrapListing: $('.wrap-listing'),
	initMap: function() {
		History.Adapter.bind(window, 'statechange', m2Map.stateChange);
		
		initInfoBox();
		
		m2Map.infoBoxHover = new ib({disableAutoPan: true, position: 'top'});
		m2Map.infoBoxDetailHover = new ib({disableAutoPan: true});
		m2Map.infoBoxDetailClick = new ib({disableAutoPan: true, onDraw: m2Map.ibDrawed});
		m2Map.infoBoxDetailClick.set('markers', []);
		
		var initZoom = form.afZoom.val();
		var initCenter = form.afCenter.val();
		
		m2Map.mapOptions = {
			center: {lat: 10.783091, lng: 106.704899},
			zoom: 18,
			clickableIcons: false,
			zoomControl: false,
			streetViewControl: false,
			mapTypeControl: true,
			mapTypeControlOptions: { style: google.maps.MapTypeControlStyle.DROPDOWN_MENU }
		};
		
		if(initZoom && initCenter) {
			m2Map.initMapRe(initZoom, initCenter);
			m2Map.hasMapInstance();
		} else {
			m2Map.initMapFresh(m2Map.hasMapInstance);
		}
	},
	hasMapInstance: function() {
		var did = form.af.filter(s.did).val();
		
		if(did) {
			m2Map.detail(did);
		}
		
		// m2Map.addDrawControl();
	},
	stateChange: function() {
		
	},
	pushState: function(serialize) {
		if(!serialize) {
			serialize = form.serialize();
		}

		serialize = decodeURIComponent(serialize);
		
		var paramValues = serialize.split('&');
		var convertPV = [];
		
		for(var i in paramValues) {
			paramValue = paramValues[i].split('=');
			
			if(fieldsMapping[paramValue[0]]) {
				convertPV.push(fieldsMapping[paramValue[0]] + '_' + paramValue[1]);
			}
		}

		History.pushState({}, document.title, actionId + '/' + convertPV.join('/'));
	},
	initMapRe: function(initZoom, initCenter) {
		m2Map.mapOptions.center = m2Map.urlValueToLatLng(initCenter);
		m2Map.mapOptions.zoom = Number(initZoom);
		
		var map = m2Map.map = new google.maps.Map(m2Map.mapEl.get(0), m2Map.mapOptions);
		
		m2Map.initMapReFirstLoad();
		
		google.maps.event.addListenerOnce(m2Map.map, 'idle', m2Map.InitMapReIdle);
		
		m2Map.detectHasChange();
	},
	initMapReFirstLoad: function() {
		var focusLocation = form.getFocusLocation();
		
		if(focusLocation.type == 'project_building') {
			m2Map.currentDrawState = 'project_building';
			m2Map.changeLocation(m2Map.drawBuildingProject);
		} else if(focusLocation.type == 'street') {
			m2Map.currentDrawState = focusLocation.type;
			form.af.filter(s.rl).val('');
			form.af.filter(s.ra).val(focusLocation.type);
			form.af.filter(s.raK).val('id');
			
			m2Map.ajaxRequest = m2Map.get(function(r) {
				m2Map.drawStreet(r);
				
				if(r.ra && r.ra.length) {
					m2Map.drawArea(new Area(r.ra[0], 'street'));
				}
			});
		} else {
			var initZoom = Number(form.af.filter(s.iz).val());
			var zoom = m2Map.mapOptions.zoom;
			var areasLevel = {ward: 0, district: 1, city: 2};
			
			if(initZoom + areasLevel[focusLocation.type] >= m2Map.deteilZoomLevelDefault) {
				if(focusLocation.type == 'city') {
					m2Map.detailZoomLevel = initZoom + 3;
				} else if(focusLocation.type == 'district') {
					m2Map.detailZoomLevel = initZoom + 2;
				} else if(focusLocation.type == 'ward') {
					m2Map.detailZoomLevel = initZoom + 1;
				}
			} else {
				m2Map.detailZoomLevel = m2Map.deteilZoomLevelDefault;
			}

			if(zoom < m2Map.detailZoomLevel) {
				var currentRa = m2Map.getZoomAreaLevel(zoom, initZoom, focusLocation.type);
				
				m2Map.currentDrawState = currentRa;
				
				form.af.filter(s.ra).val(currentRa);
				
				var key = (focusLocation.type == currentRa) ? 'id' : focusLocation.type + '_id';
				form.af.filter(s.raK).val(key);
				
				var rect = form.fields.filter(s.rect).prop('disabled', true);
				
				m2Map.ajaxRequest = m2Map.get(function(r) {
					if(r.ra) {
						m2Map.drawAreas(r.ra, currentRa);
					}
				});
				
				rect.prop('disabled', false);
			} else {
				m2Map.currentDrawState = 'detail';
				m2Map.loadDetail('');
			}
		}
	},
	InitMapReIdle: function() {
		form.afRect.val(m2Map.getBounds(28, 12, 0, 12).toUrlValue());
		
		m2Map.boundsChangedEvent = m2Map.map.addListener('bounds_changed', m2Map.boundsChanged);

		m2Map.zoomChangedEvent = m2Map.map.addListener('zoom_changed', m2Map.zoomChanged);
	},
	initMapFresh: function(fn) {
		m2Map.changeLocation(function(r){
			m2Map.drawMap(r);
			fn();
		});
	},
	drawMap: function(r) {
		var map = m2Map.map = new google.maps.Map(m2Map.mapEl.get(0), m2Map.mapOptions);
		
		m2Map.drawLocation(r);
		m2Map.detectHasChange();
		
		google.maps.event.addListenerOnce(m2Map.map, 'bounds_changed', m2Map.setInitLocationProps);
	},
	detectHasChange: function() {
		var map = m2Map.map;
		var z, c;

		google.maps.event.addListenerOnce(m2Map.map, 'bounds_changed', function(){
			var zoom = map.getZoom();
			
			z = zoom;
			c = map.getCenter().toString();
		});
		
		google.maps.event.addListenerOnce(m2Map.map, 'idle', function(){
			var zoom = map.getZoom();
			
			if(zoom != z || map.getCenter().toString() != c) {
				var focusLocation = form.getFocusLocation();
				
				if(focusLocation.type == 'project_building') {
					m2Map.currentDrawState == 'project_building';
				} else if(focusLocation.type == 'street') {
					m2Map.currentDrawState == 'street';
				} else {
					if(zoom < m2Map.detailZoomLevel) {
						m2Map.zoomChanged();
						m2Map.removeAllDetail();
					} else {
						m2Map.currentDrawState = 'detail';
						m2Map.removeAreas();
						
						if(m2Map.ajaxRequest) {
							m2Map.ajaxRequest.abort();
							m2Map.ajaxRequest = null;
						}
						
						m2Map.infoBoxHover.close();
						
						form.af.filter(s.page).val('');
						form.af.filter(s.ra).val('');
						form.af.filter(s.rm).val(1);
						form.af.filter(s.rl).val(1);
						
						m2Map.ajaxRequest = m2Map.get(function(r){
							m2Map.removeAreas();
							m2Map.drawDetailCallBack(r);
						});
					}
				}
				
				form.afRect.val(m2Map.getBounds(28, 12, 0, 12).toUrlValue());
				form.afZoom.val(m2Map.map.getZoom());
				form.afCenter.val(m2Map.getCenter().toUrlValue());
				
				m2Map.pushState();
			}
		});
	},
	setInitLocationProps: function() {
		var zoom = m2Map.map.getZoom();
		var focusLocation = form.getFocusLocation();
		
		m2Map.currentDrawState = focusLocation.type;
		
		form.af.filter(s.iz).val(zoom);
		
		var areasLevel = {ward: 0, district: 1, city: 2};
		
		if(zoom + areasLevel[focusLocation.type] >= m2Map.deteilZoomLevelDefault) {
			if(focusLocation.type == 'city') {
				m2Map.detailZoomLevel = zoom + 3;
			} else if(focusLocation.type == 'district') {
				m2Map.detailZoomLevel = zoom + 2;
			} else if(focusLocation.type == 'ward') {
				m2Map.detailZoomLevel = zoom + 1;
			}
		} else {
			m2Map.detailZoomLevel = m2Map.deteilZoomLevelDefault;
		}
	},
	boundsChanged: function() {
		var map = m2Map.map;
		
		clearTimeout(map.get('bounds_changed_timeout'));
		
		map.set('bounds_changed_timeout', setTimeout(function(){
			form.afRect.val(m2Map.getBounds(28, 12, 0, 12).toUrlValue());
			form.afZoom.val(m2Map.map.getZoom());
			form.afCenter.val(m2Map.getCenter().toUrlValue());
			
			if(m2Map.currentDrawState == 'detail') {
				m2Map.loadDetail(1);
			} else if(m2Map.currentDrawState == 'street') {
				m2Map.loadDetail(1);
			}
			
			m2Map.pushState();
			
		}, 100));
	},
	zoomChanged: function() {
		var zoom = m2Map.map.getZoom();
		
		if(m2Map.currentDrawState == 'project_building' || m2Map.currentDrawState == 'street') {
			return false;
		}
		
		if(zoom < m2Map.detailZoomLevel) {
			
			form.af.filter(s.rl).val('');
			
			if(m2Map.currentDrawState == 'detail') {
				m2Map.infoBoxDetailHover.close();
				m2Map.removeAllDetail();
				
				form.af.filter(s.rm).val('');
				form.af.filter(s.page).val('');
				form.af.filter(s.rl).val(1);
			}
			
			var currentFocus = form.getFocusLocation();
			var currentRa = m2Map.getZoomAreaLevel(m2Map.map.getZoom(), form.af.filter(s.iz).val(), currentFocus.type);
			
			if(m2Map.currentDrawState != currentRa) {

				m2Map.infoBoxHover.close();
				
				if(m2Map.ajaxRequest) {
					m2Map.ajaxRequest.abort();
					m2Map.ajaxRequest = null;
				}
				
				m2Map.infoBoxHover.close();
				
				m2Map.currentDrawState = currentRa;
				
				form.af.filter(s.ra).val(currentRa);
				
				var key = (currentFocus.type == currentRa) ? 'id' : currentFocus.type + '_id';
				form.af.filter(s.raK).val(key);
				
				var rect = form.fields.filter(s.rect).prop('disabled', true);
				
				m2Map.ajaxRequest = m2Map.get(function(r) {
					if(r.ra) {
						m2Map.drawAreas(r.ra, currentRa);
					}
					
					if(r.rl) {
						m2Map.drawList(r.rl);
					}
				});
				
				rect.prop('disabled', false);
			}
		} else {
			m2Map.infoBoxHover.close();
			
			m2Map.currentDrawState = 'detail';
			m2Map.removeAreas();
		}
	},
	loadDetail: function(rl) {
		if(m2Map.ajaxRequest) {
			m2Map.ajaxRequest.abort();
			m2Map.ajaxRequest = null;
		}
		
		m2Map.infoBoxHover.close();
		
		form.af.filter(s.page).val('');
		form.af.filter(s.ra).val('');
		form.af.filter(s.rm).val(1);
		form.af.filter(s.rl).val(rl);
		
		m2Map.ajaxRequest = m2Map.get(m2Map.drawDetailCallBack);
	},
	drawLocation: function(r) {
		var focusLocation = form.getFocusLocation();
		
		if(r.rm) {
			if(focusLocation.type == 'street') {
				m2Map.drawStreet(r);
			} else {
				m2Map.drawBuildingProject(r, true);
			}
		}
		
		if(r.ra) {
			if(r.ra.length) {
				var area = new Area(r.ra[0], focusLocation.type);
				m2Map.drawAndFitArea(area);
				
				if(focusLocation.type == 'street') {
					var bounds = area.getBounds();
					var center = area.getCenter();
					
					if(!bounds && !center) {
						if(r.rm && r.rm.length) {
							m2Map.setCenter({lat: Number(r.rm[0].lat), lng: Number(r.rm[0].lng)});
						}
					}
				}
			}
		}

		google.maps.event.addListenerOnce(m2Map.map, 'idle', m2Map.drawLocationCallback);
	},
	drawBuildingProject: function(r, setCenter) {

		m2Map.removeAllDetail();
		m2Map.removeAreas();
		
		var projectInfo = $('.infor-duan-suggest');
		
		if(projectInfo.length && projectInfo.data('lat') && projectInfo.data('lng')) {
			var lngLat = {lat: projectInfo.data('lat'), lng: projectInfo.data('lng')};
		} else if(r.rm.length) {
			var lngLat = {lat: Number(r.rm[0].lat), lng: Number(r.rm[0].lng)};
		}

		if(lngLat) {
			if(setCenter === true) {
				m2Map.setCenter(lngLat);
				m2Map.map.setZoom(m2Map.deteilZoomLevelDefault);
			}
			
			var marker = new google.maps.Marker({
				map: m2Map.map,
				position: lngLat
			});
			
			var buildingProjects = [];
			
			for(var i in r.rm) {
				buildingProjects.push(new Product(r.rm[i]));
			}
			
			marker.addListener('click', m2Map.markerClick);
			marker.addListener('mouseover', m2Map.markerMouseOver);
			marker.addListener('mouseout', m2Map.markerMouseOut);
			marker.set('products', buildingProjects);
			m2Map.setIcon(marker, buildingProjects.length, 0);
			
			m2Map.markers[lngLat] = marker;
		}
	},
	drawLocationCallback: function() {
		if(m2Map.boundsChangedEvent == null) {
			m2Map.boundsChangedEvent = m2Map.map.addListener('bounds_changed', m2Map.boundsChanged);
		}
		
		if(m2Map.zoomChangedEvent == null) {
			m2Map.zoomChangedEvent = m2Map.map.addListener('zoom_changed', m2Map.zoomChanged);
		}
	},
	drawAndFitArea: function(area) {
		var bounds = area.getBounds();

		if(bounds) {
			m2Map.fitBounds(area.getBounds());
		} else {
			var center = area.getCenter();
			
			if(center) {
				m2Map.setCenter(center);
			}
		}
		
		m2Map.drawArea(area);
	},
	changeLocation: function(fn) {
		var focusLocation = form.getFocusLocation();
		
		if(focusLocation.type == 'project_building') {
			form.af.filter(s.rm).val(1);
		} else {
			form.af.filter(s.ra).val(focusLocation.type);
			form.af.filter(s.raK).val('id');
			
			if(focusLocation.type == 'street') {
				form.af.filter(s.rm).val(1);
			}
		}
		
		var rect = form.fields.filter(s.rect);
		
		if(focusLocation.type == 'project_building') {
			rect.prop('disabled', true);
		}
		
		m2Map.get(fn);
		
		if(focusLocation.type == 'project_building') {
			rect.prop('disabled', false);
		}
	},
	drawStreet: function(r) {
		if(r.rm) {
			m2Map.removeAllDetail();
			m2Map.drawDetail(r.rm);
		}
	},
	removeAreas: function() {
		var areas = m2Map.areas;
		var length = areas.length;
		
		for(var i in areas) {
			areas[i].remove();
		}
		
		m2Map.areas = {};
	},
	drawAreas: function(arrAreaAttrs, type) {
		m2Map.removeAreas();
		
		var length = arrAreaAttrs.length;
		
		for(var i = 0; i < length; i++) {
			var area = new Area(arrAreaAttrs[i], type);
			m2Map.drawArea(area);
		}
	},
	drawArea: function(area) {
		area.draw(m2Map.map);
		
		m2Map.areas[area.attrs.id] = area;
	},
	drawDetailCallBack: function(r) {
		if(r.rm) {
			m2Map.removeAllDetail();
			m2Map.drawDetail(r.rm);
		}
		if(r.rl) {
			m2Map.drawList(r.rl);
		}
	},
	drawDetail: function(products) {
		var map = m2Map.map;
		var markers = m2Map.markers;
		var length = products.length;
		
		for(var i = 0; i < length; i++) {
			var product = new Product(products[i]);

			var key = product.getMarkerKey();
			var marker = markers[key];
			
			if(marker) {
				var markerProducts = marker.get('products');
				markerProducts.push(product);
				
				marker.set('products', markerProducts);
				m2Map.setIcon(marker, markerProducts.length, 0);
			} else {
				marker = new google.maps.Marker({
					map: map,
					position: product.getPosition()
				});
				
				marker.addListener('click', m2Map.markerClick);
				marker.addListener('mouseover', m2Map.markerMouseOver);
				marker.addListener('mouseout', m2Map.markerMouseOut);
				marker.set('products', [product]);
				m2Map.setIcon(marker, 1, 0);
				
				markers[key] = marker;
			}
		}
	},
	markerMouseOver: function(){
		var products = this.get('products');
		
		m2Map.setIcon(this, products.length, 1);
		this.setZIndex(google.maps.Marker.MAX_ZINDEX++);
		
		if(!m2Map.isAttachingClickInfo(this)) {
			var product = products[0];
			
			var padding = m2Map.getMarkerPadding(this);
			
			if(padding.left < m2Map.infoDetailWidth) {
				var offsetLeft = (padding.left < m2Map.infoDetailWidth) ? m2Map.infoDetailWidth - padding.left + 6 : 0;
			} else {
				var offsetLeft = (padding.right < m2Map.infoDetailWidth) ? padding.right - m2Map.infoDetailWidth - 6 : 0;
			}
			
			if(padding.top - this.getShape().coords[3] < m2Map.infoDetailHeight + 11) {
				m2Map.infoBoxDetailHover.setPosition('bottom');
			} else {
				m2Map.infoBoxDetailHover.setPosition('top');
			}
			
			m2Map.infoBoxDetailHover.opts.offsetLeft = offsetLeft;

			var content = '<div class="info-wrap-detail info-wrap-single">' + product.buildInfoContent()
							+ '<div class="info-arrow" style="margin-left: ' + -Math.round(offsetLeft + 5) + 'px"></div></div>';
			
			m2Map.infoBoxDetailHover.setContent(content);
			m2Map.infoBoxDetailHover.open(this);
		}
	},
	isAttachingClickInfo: function(marker) {
		if(m2Map.infoBoxDetailClick.anchor) {
			return m2Map.infoBoxDetailClick.anchor.getPosition().equals(marker.getPosition());
		} else {
			return false;
		}		
	},
	markerMouseOut: function() {
		m2Map.infoBoxDetailHover.close();
		
		var products = this.get('products');
		
		m2Map.setIcon(this, products.length, 0);
	},
	markerClick: function() {
		var products = this.get('products');

		if(products.length == 1) {
			var product = products[0];
			m2Map.showDetail(product.attrs.id);
		} else {
			if(!m2Map.isAttachingClickInfo(this)) {
				m2Map.infoBoxDetailHover.close();
				
				var padding = m2Map.getMarkerPadding(this);
				var position = (padding.top > padding.bottom) ? 'top' : 'bottom';
				var height = Math.round(padding[position]) - 12;
				
				if(position == 'top') {
					height = height - this.getShape().coords[3];
				}
				
				if(products.length * m2Map.infoDetailHeight < height) {
					height = products.length * m2Map.infoDetailHeight;
				}

				if(padding.left < m2Map.infoDetailWidth) {
					var offsetLeft = (padding.left < m2Map.infoDetailWidth) ? m2Map.infoDetailWidth - padding.left + 6 : 0;
				} else {
					var offsetLeft = (padding.right < m2Map.infoDetailWidth) ? padding.right - m2Map.infoDetailWidth - 6 : 0;
				}
				
				var content = '<div class="info-wrap-detail info-wrap-single"><div class="scroll" style="height: ' + height + 'px; overflow: hidden;">';
				
				for(var i in products) {
					content += products[i].buildInfoContent();
				}
				
				content += '</div><div class="info-arrow" style="margin-left: ' + -Math.round(offsetLeft + 5) + 'px"></div></div>';
				
				m2Map.infoBoxDetailClick.opts.offsetLeft = offsetLeft;

				m2Map.infoBoxDetailClick.setContent(content);
				m2Map.infoBoxDetailClick.setPosition(position);
				m2Map.infoBoxDetailClick.open(this);
			}
		}
	},
	ibDrawed: function() {
		var self = this;
		var holder = $(this.viewHolder);
		var scroll = holder.find('.scroll');
		var infoWrap = holder.find('.info-wrap-detail');
		var close = holder.find('.close-detail');
		
		scroll.slimScroll({
	        height: scroll.height() + 'px',
	        allowPageScroll: false,
	        borderRadius: 0
	    });
		
		infoWrap.mouseenter(function(){
			m2Map.map.set('scrollwheel', false);

			var markers = self.get('markers');
			var ml = markers.length;
			for(var i = 0; i < ml; i++) {
				markers[i].set('clickable', false);
			}
		});
		
		infoWrap.mouseleave(function(e){
			m2Map.map.set('scrollwheel', true);

			var markers = self.get('markers');
			var ml = markers.length;
			for(var i = 0; i < ml; i++) {
				markers[i].set('clickable', true);
			}
		});
		
		scroll.find('.item').click(function(e){
			m2Map.showDetail($(this).data('id'));
			e.stopPropagation();
		});
		
		var tempEventBound = google.maps.event.addListenerOnce(m2Map.map, 'bounds_changed', function(){
			detachE();
		});

		var tempEventClick = google.maps.event.addListenerOnce(m2Map.map, 'click', function(){
			detachE();
		});
		
		function detachE() {
			self.close();
			m2Map.map.set('scrollwheel', true);
			google.maps.event.removeListener(tempEventBound);
			google.maps.event.removeListener(tempEventClick);
		}
		
		var bounds = self.getBounds();
		
		var mapBounds = m2Map.map.getBounds();
		var sw = bounds.getSouthWest();
		var ne = bounds.getNorthEast();
		var scaleOffset = m2Map.getScaleOffset(mapBounds);
		
		var southLat = sw.lat() + (-mi.MAX_HEIGHT * scaleOffset.y);
		var westLng = sw.lng() + (-(mi.MAX_WIDTH/2) * scaleOffset.x);
		var northLat = ne.lat();
		var eastLng = ne.lng() + ((mi.MAX_WIDTH/2) * scaleOffset.x);
		
		var newBounds = new google.maps.LatLngBounds(new google.maps.LatLng(southLat, westLng), new google.maps.LatLng(northLat, eastLng));
		var markers = [];
		
		for(var i in m2Map.markers) {
			var marker = m2Map.markers[i];

			if(newBounds.contains(marker.getPosition())) {
				markers.push(marker);
			}
		}
		
		self.set('markers', markers);
	},
	removeAllDetail: function() {
		var markers = m2Map.markers;
		
		for(var i in markers) {
			markers[i].setMap(null);
		}
		
		m2Map.markers = {};
	},
	drawList: function(list) {
		contentHolder.html(list);
	},
	getBounds: function(marginTop, marginRight, marginBottom, marginLeft) {
		var mapBounds = m2Map.map.getBounds();
		var sw = mapBounds.getSouthWest();
		var ne = mapBounds.getNorthEast();
		var scaleOffset = m2Map.getScaleOffset(mapBounds);
		
		var southLat = sw.lat() + (marginBottom * scaleOffset.y);
		var westLng = sw.lng() + (marginLeft * scaleOffset.x);
		var northLat = ne.lat() - (marginTop * scaleOffset.y);
		var eastLng = ne.lng() - (marginRight * scaleOffset.x);
		
		return new google.maps.LatLngBounds(new google.maps.LatLng(southLat, westLng), new google.maps.LatLng(northLat, eastLng));
	},
	getScaleOffset: function(mapBounds) {
		var mapDiv = m2Map.map.getDiv();
		var mapWidth = mapDiv.offsetWidth;
		var mapHeight = mapDiv.offsetHeight;
		var boundsSpan = mapBounds.toSpan();
		var latSpan = boundsSpan.lat();
		var longSpan = boundsSpan.lng();
		var degPixelX = longSpan / mapWidth;
		var degPixelY = latSpan / mapHeight;
		
		return {x: degPixelX, y: degPixelY};
	},
	getMarkerPadding: function(marker) {
		var mapBounds = m2Map.map.getBounds();
		var scaleOffset = m2Map.getScaleOffset(mapBounds);
		
		var padding = {
			top: (mapBounds.getNorthEast().lat() - marker.getPosition().lat()) / scaleOffset.y,
			bottom: (marker.getPosition().lat() - mapBounds.getSouthWest().lat()) / scaleOffset.y,
			left: (marker.getPosition().lng() - mapBounds.getSouthWest().lng()) / scaleOffset.x,
			right: (mapBounds.getNorthEast().lng() - marker.getPosition().lng()) / scaleOffset.x
		};
		return padding;
	},
	getCenter: function() {
		return m2Map.map.getCenter();
	},
	setCenter: function(center) {
		m2Map.map.setCenter(center);
	},
	fitBounds: function(bounds) {
		m2Map.map.fitBounds(bounds);
	},
	setIcon: function(marker, count, hover) {
		if(count == 1) {
			var icon = '/images/marker-' + hover + '.png';
			marker.setShape(m2Map.shape);
		} else {
			var cachedKey = count + '-' + hover;
			var iconCached = m2Map.markerIconCached[cachedKey];
			
			if(iconCached) {
				var i = iconCached;
			} else {
				var i = mi.create(count, hover);
				m2Map.markerIconCached[cachedKey] = i;
			}
			
			var icon = i.icon;
			var shape = {coords: [0, 0, i.width, i.height], type: 'rect'};
			
			marker.setShape(shape);
		}
		marker.setIcon(icon);
	},
	get: function(fn, serialize) {
		if(!serialize) {
			serialize = form.serialize();
		}
		
		var loadingList = form.af.filter(s.rl).val();
		
		var markerField = form.af.filter(s.rm);
		var loadingMap = form.af.filter(s.ra).val() || (markerField.val() && !markerField.prop('disabled'));
		
		if(loadingMap) {
			m2Map.loading(10);
		}
		
		if(loadingList) {
			m2Map.loadingList.show();
		}
		
		return $.ajax({
			url: '/map/get',
			data: serialize,
			success: fn,
			complete: function() {
				if(loadingList) {
					m2Map.loadingList.hide();
					m2Map.wrapListing.scrollTop(0);
				}
				if(loadingMap) {
					m2Map.loaded();
				}
			}
		});
	},
	urlValueToLatLng: function(str) {
		var sa = str.split(',');
		
		return new google.maps.LatLng(sa[0], sa[1]);
	},
	getZoomAreaLevel: function(currentZoom, initZoom, focusAreaType) {
		initZoom = Number(initZoom);
		var currentFocusLevel = m2Map.areasLevel[focusAreaType];
		
		var max = m2Map.detailZoomLevel - initZoom;
		var current = m2Map.detailZoomLevel - currentZoom;
		
		if(current > max) {
			return focusAreaType;
		}
		
		var wardLevel = Math.ceil(max / currentFocusLevel);
		var districtLevel = Math.ceil((max - wardLevel) / (currentFocusLevel - 1)) + wardLevel;
		
		if(current <= wardLevel) {
			return 'ward';
		} else if (current <= districtLevel) {
			return 'district';
		} else {
			return 'city';
		}
	},
	getFocusMarker: function(id) {
		var markers = m2Map.markers;
		
		for(var i in markers) {
			var marker = markers[i];
			var products = marker.get('products');
			var pl = products.length;

			for(var j = 0; j < pl; j++) {
				if(products[j].attrs.id == id) {
					return marker;
				}
			}
		}
	},
	focusMarker: function(marker) {
		m2Map.setIcon(marker, marker.get('products').length, 1);
		marker.setZIndex(google.maps.Marker.MAX_ZINDEX++);
	},
	showDetail: function(id) {
		m2Map.detail(id);
		
		form.af.filter(s.did).val(id);
		
		m2Map.pushState();
	},
	detail: function(id) {
		var wWrapList = $('.wrap-listing-item .inner-wrap').outerWidth();
		var detailListing = $('.detail-listing');
		
		detailListingWrap.loading({full: false}).addClass('show-detail');
		
		/*detailListingWrap.css({
			right: wWrapList +'px'
		});*/
		
		google.maps.event.removeListener(m2Map.closeDetailListener);
		m2Map.closeDetailListener = m2Map.map.addListener('click', m2Map.closeDetail);
		
		$.get('/listing/detail', {id: id}, function(r){
			var temp = $(r).find('#detail-wrap');
			
			temp.find('.popup-common').each(function () {
				var _this = $(this),
					idThis = _this.attr('id');
				$('body').find('#'+idThis).remove();
			});
			
			detailListing.find('.container').html($(r).find('#detail-wrap').html());
			detailListing.find('.popup-common').appendTo('body');
			
			var swiper = new Swiper('.swiper-container', {
				pagination: '.swiper-pagination',
				paginationClickable: true,
		        spaceBetween: 0
		    });

			detailListingWrap.loading({done: true});
			
			$('.inner-detail-listing').scrollTop(0);

			$('.btn-extra').attr('href', detailListing.find('.btn-copy').data('clipboard-text'));
		});
	},
	closeDetail: function(e) {
		if(e.preventDefault) {
			e.preventDefault();
		}
		
		var wWrapList = $('.wrap-listing-item .inner-wrap').outerWidth();
		
		/*detailListingWrap.css({
			right: -wWrapList + 'px'
		});*/

		detailListingWrap.removeClass('show-detail');
		
		form.af.filter(s.did).val('');
		m2Map.pushState();
	},
	loading: function(n) {
		m2Map.progressBar.show()
		m2Map.loading_(n);
	},
	loading_: function(n) {
		m2Map.progressBar.width(n + '%');
		if(n < 90) {
			m2Map.loadingTimeout = setTimeout(function(){
				m2Map.loading_(n + 10);
			}, n * 10);
		}
	},
	loaded: function() {
		clearTimeout(m2Map.loadingTimeout);
		
		m2Map.progressBar.width('100%');
		
		m2Map.loadingTimeout = setTimeout(function(){
			m2Map.progressBar.hide().width('0%');
		}, 150);
	},
	addDrawControl: function() {
		var div = document.createElement('div');
		div.className = 'draw-wrap';
		div.index = 1;
		
		var drawButton = document.createElement('a');
		drawButton.className = 'button draw-button';
		drawButton.innerHTML = '<span class="icon-mv"><span class="icon-edit-copy-4"></span></span>Vẽ khoanh vùng';

		var removeButton = document.createElement('a');
		removeButton.className = 'button remove-button';
		removeButton.innerHTML = '<span class="icon-mv"><span class="icon-close-icon"></span></span>Xóa khoanh vùng';
		
		div.appendChild(drawButton);
		div.appendChild(removeButton);
//		
//		drawButton.addEventListener('click', function() {
//			div.className = 'draw-wrap draw-wrap-drawing';
//			
//			for(var i in listing.polygons) {
//				listing.polygons[i].set('clickable', false);
//			}
//			
//			listing.map.set('draggable', false);
//			listing.map.set('draggableCursor', 'crosshair');
//			
//			var down = google.maps.event.addDomListener(listing.map.getDiv(), 'mousedown', function(e) {
//				listing.drawPoly = new google.maps.Polyline({
//					map: listing.map,
//					clickable: false,
//			        strokeWeight: 1.5,
//			        strokeColor: '#00a769'
//				});
//				
//				var move = google.maps.event.addListener(listing.map, 'mousemove', function(e) {
//					listing.drawPoly.getPath().push(e.latLng);
//				});
//				
//				google.maps.event.addListenerOnce(listing.map, 'mouseup', function(e) {
//					google.maps.event.removeListener(move);
//					var path = listing.drawPoly.getPath();
//					listing.drawPoly.setMap(null);
//
//					var worldCoords = [ new google.maps.LatLng(-85.1054596961173, -180),
//					                    new google.maps.LatLng(85.1054596961173, -180),
//									    new google.maps.LatLng(85.1054596961173, 180),
//									    new google.maps.LatLng(-85.1054596961173, 180),
//									    new google.maps.LatLng(-85.1054596961173, 0)];
//					
//					var focusArea = listing.focusArea();
//					var w = listing.parseGeometry(listing.area[focusArea.collection][focusArea.id].geometry);
//
//					for(var i in listing.polygons) {
//						listing.polygons[i].setMap(null);
//					}
//					
//					listing.drawPoly = new google.maps.Polygon({
//						map: listing.map,
//						paths: [w[0], path],
//						strokeColor: listing.color,
//				        strokeOpacity: 0.8,
//				        strokeWeight: 1,
//				        fillColor: listing.color,
//				        fillOpacity: 0.2
//					});
//					
//					listing.drawPolyWorld = new google.maps.Polygon({
//						map: listing.map,
//						paths: [worldCoords, path],
//						strokeColor: listing.color,
//				        strokeOpacity: 0.8,
//				        strokeWeight: 1,
//				        fillColor: listing.color,
//				        fillOpacity: 0.2
//					});
//					
//					polyDetect = new google.maps.Polygon({path: path});
//					
//					google.maps.event.removeListener(down);
//					
//					// console.log(google.maps.geometry.poly.containsLocation(new google.maps.LatLng(-34, 151), polyDetect));
//					
//					listing.map.set('draggable', true);
//					listing.map.set('draggableCursor', 'url(http://maps.google.com/mapfiles/openhand.cur), move');
//				});
//			});
//		});
//
//		removeButton.addEventListener('click', function() {
//			div.className = 'draw-wrap';
//			
//			if(listing.drawPoly) {
//				listing.drawPoly.setMap(null);
//				listing.drawPolyWorld.setMap(null);
//				listing.drawPoly = null;
//				listing.drawPolyWorld = null;
//			}
//			
//			for(var i in listing.polygons) {
//				listing.polygons[i].set('clickable', true);
//				listing.polygons[i].setMap(listing.map);
//			}
//			
//			listing.map.set('draggable', true);
//			listing.map.set('draggableCursor', 'url(http://maps.google.com/mapfiles/openhand.cur), move');
//		});
//		
		m2Map.map.controls[google.maps.ControlPosition.TOP_LEFT].push(div);
	}
};

/*
 * Form
 */
form.af = $('#af-wrap').children();
form.afRect = form.af.filter(s.rect);
form.afZoom = form.af.filter(s.z);
form.afCenter = form.af.filter(s.c);
form.projectInfoEl = $('#project-info');

form.formChange = function(e) {
	
	var t = $(e.target);
	
	form.af.filter(s.rl).val(1);
	
	if(t.hasClass('search-item')) {

		form.af.val('');
		form.af.filter(s.rl).val(1);
		
		google.maps.event.removeListener(m2Map.boundsChangedEvent);
		m2Map.boundsChangedEvent = null;
		google.maps.event.removeListener(m2Map.zoomChangedEvent);
		m2Map.zoomChangedEvent = null;
		
		google.maps.event.addListenerOnce(m2Map.map, 'bounds_changed', m2Map.setInitLocationProps);
		
		m2Map.removeAllDetail();
		
		if(t.data('type') == 'project_building') {
			form.projectInfoEl.html('');
			
			var id = t.data('id');
			
			$.get(loadProjectUrl, {id: id}, function(r) {
				form.projectInfoEl.html(r);
				toogleScroll();
			});
		} else {
			form.projectInfoEl.html('');
			toogleScroll();
		}
		
		m2Map.changeLocation(function(r){
			m2Map.removeAreas();
			
			m2Map.drawLocation(r);

			if(r.rl) {
				m2Map.drawList(r.rl);
			}
		});
	} else if(t.attr('id') == 'order_by') {
		
		form.af.filter(s.ra).val('');
		form.af.filter(s.raK).val('');
		form.af.filter(s.page).val('');
		
		m2Map.pushState();
		
		var rect = form.fields.filter(s.rect);

		if(m2Map.currentDrawState == 'city' || m2Map.currentDrawState == 'district' || m2Map.currentDrawState == 'ward' || m2Map.currentDrawState == 'project_building') {
			rect.prop('disabled', true);
			form.af.filter(s.rm).prop('disabled', true);
		} else {
			form.af.filter(s.rm).val(1);
		}
		
		form.af.filter(s.rl).val(1);
		
		m2Map.get(m2Map.drawDetailCallBack);

		rect.prop('disabled', false);
		form.af.filter(s.rm).prop('disabled', false);
	} else {
		form.af.filter(s.rl).val(1);
		form.af.filter(s.page).val('');
		
		var rect = form.fields.filter(s.rect);
		
		if(m2Map.currentDrawState == 'city' || m2Map.currentDrawState == 'district' || m2Map.currentDrawState == 'ward') {
			rect.prop('disabled', true);
			form.af.filter(s.rm).val('');
			
			var currentFocus = form.getFocusLocation();
			var currentRa = m2Map.getZoomAreaLevel(m2Map.map.getZoom(), form.af.filter(s.iz).val(), currentFocus.type);
			
			form.af.filter(s.ra).val(currentRa);
			
			var key = (currentFocus.type == currentRa) ? 'id' : currentFocus.type + '_id';
			form.af.filter(s.raK).val(key);
		} else {
			form.af.filter(s.rm).val(1);
			form.af.filter(s.ra).val('');
		}
		
		m2Map.get(function(r){
			m2Map.drawList(r.rl);
			
			if(r.rm) {
				m2Map.removeAllDetail();
				
				if(m2Map.currentDrawState == 'project_building') {
					m2Map.drawBuildingProject(r);
				} else {
					m2Map.drawDetail(r.rm);
				}
			}
			
			if(r.ra) {
				m2Map.drawAreas(r.ra, currentRa);
			}
		});
		
		rect.prop('disabled', false);
	}
	
	m2Map.pushState();
};

form.serialize = function() {
	return form.serialize_(form.fields);
};

form.serialize_ = function(fields) {
	return fields.filter(function () {
        return !!this.value;
    }).serialize();
};

form.toggleConditionFields = function() {
	if(desktop.isDesktop()) {
		form.af.prop('disabled', false);
	} else {
		form.af.prop('disabled', true);
	}
};

form.getFocusLocation = function() {
	var focusLocation = {};
	
	form.autoFill.each(function(){
		var self = $(this);
		var val = self.val();
		
		if(val) {
			focusLocation.id = val;
			focusLocation.type = self.attr('id').replace('_id', '');
		}
	});
	
	return focusLocation;
};

form.pagination = function(e) {
	e.preventDefault();
	
	form.af.filter(s.ra).val('');
	form.af.filter(s.raK).val('');
	
	var page = Number($(this).data('page')) + 1;
	var pageEl = form.af.filter(s.page);
	
	if(page == 1) {
		pageEl.val('');
	} else {
		pageEl.val(page);
	}
	
	m2Map.pushState();
	
	var rect = form.fields.filter(s.rect);
	
	if(m2Map.currentDrawState == 'city' || m2Map.currentDrawState == 'district' || m2Map.currentDrawState == 'ward') {
		rect.prop('disabled', true);
	}
	
	form.af.filter(s.rl).val(1);
	form.af.filter(s.rm).prop('disabled', true);
	
	m2Map.get(form.paginationCallback);

	form.af.filter(s.rl).val('');
	form.af.filter(s.rm).prop('disabled', false);
	rect.prop('disabled', false);
};

form.paginationCallback = function(r) {
	m2Map.drawList(r.rl);
};

form.itemClick = function(e) {
	e.preventDefault();
	
	var id = $(this).data('id');
	
	m2Map.showDetail(id);
};
form.itemMouseEnter = function(e) {
	var id = $(this).data('id');
	
	$.data(this, 'mouseenterTimer', setTimeout(function(){
		var marker = m2Map.getFocusMarker(id);

		if(marker) {
			m2Map.focusMarker(marker);
		}
	}, 300));
};
form.itemMouseLeave = function(e) {
	clearTimeout($.data(this, 'mouseenterTimer'));
	
	var marker = m2Map.getFocusMarker($(this).data('id'));
	
	if(marker) {
		m2Map.setIcon(marker, marker.get('products').length, 0);
	}
};

events.attachDesktopEvent(form.fields, 'change', form.formChange);
events.attachDesktopEvent(form.listSearchEl, 'click', 'a', form.formChange);
events.attachDesktopEvent(contentHolder, 'click', '.pagination a', form.pagination);
events.attachDesktopEvent(contentHolder, 'click', '.item a', form.itemClick);
events.attachDesktopEvent($('.close-slide-detail'), 'click', m2Map.closeDetail);
events.attachDesktopEvent(contentHolder, 'mouseenter', '.item a', form.itemMouseEnter);
events.attachDesktopEvent(contentHolder, 'mouseleave', '.item a', form.itemMouseLeave);
events.attachDesktopEvent($window, 'resize', form.toggleConditionFields);


/*
 * Infobox
 */
var ib;

function initInfoBox() {
	ib = function(opts) {
		google.maps.OverlayView.call(this);
		
		if(typeof opts === 'undefined') {
			opts = {};
		}
		
		this.opts = opts;
		
		if(!this.opts.position) {
			this.opts.position = 'top';
		}
		if(!this.opts.offsetLeft) {
			this.opts.offsetLeft = 0;
		}
		if(!this.opts.panPadding) {
			this.opts.panPadding = {top: 0, right: 0, bottom: 0, left: 0};
		}
		
		this.viewHolder = document.createElement("div");
		this.viewHolder.style.position = "absolute";
		this.viewHolder.className = this.opts.position;
		
		if(this.opts.content) {
			this.setContent(this.opts.content);
		}
	}

	ib.prototype = new google.maps.OverlayView();

	ib.prototype.setPosition = function(position) {
		this.opts.position = position;
		this.viewHolder.className = this.opts.position;
	};
	
	ib.prototype.draw = function() {
		var position = this.getProjection().fromLatLngToDivPixel(this.position);
		
		this.viewHolder.style.left = ((position.x - (this.viewHolder.offsetWidth / 2)) + this.opts.offsetLeft) + "px";
		
		var top = position.y - this.viewHolder.offsetHeight;
		
		if(this.opts.position == 'top') {
			if(this.anchor instanceof google.maps.Marker) {
				top = top - this.anchor.getShape().coords[3];
			}
		} else {
			top = top + this.viewHolder.offsetHeight;
		}
		
		this.viewHolder.style.top = top + "px";
		
		if(!this.opts.disableAutoPan) {
			if(this.boundsChangedListener) {
				this.panMap();
			}
		}
		
		if(this.opts.onDraw) {
			this.opts.onDraw.apply(this);
		}
	};

	ib.prototype.remove = function() {
		this.viewHolder.parentNode.removeChild(this.viewHolder);
	};

	ib.prototype.onAdd = function() {
		var panes = this.getPanes();
		
		panes.floatPane.appendChild(this.viewHolder);
		
		var me = this;
		
		if(!this.opts.disableAutoPan) {
			this.boundsChangedListener = google.maps.event.addListener(this.map, "bounds_changed", function() {
				return me.panMap.apply(me);
			});
		}
	};

	ib.prototype.open = function(mvcObject, latLng) {
		var position, map;
		
		if(mvcObject instanceof google.maps.Marker) {
			position = mvcObject.getPosition();
			map = mvcObject.getMap();
		} else if(mvcObject instanceof google.maps.Map) {
			position = latLng ? latLng : mvcObject.getCenter();
			map = mvcObject;
		}

		if(this.anchor !== mvcObject || (this.position.lat() != position.lat() && this.position.lng() != position.lng())) {
			this.anchor = mvcObject;
			this.position = position;
			this.setMap(map);
		}
	};

	ib.prototype.close = function() {
		this.anchor = null;
		this.position = null;
		this.setMap(null);
	}

	ib.prototype.setContent = function(content) {
		this.content = content;
		
		if(typeof content == 'string') {
			this.viewHolder.innerHTML = content;
		} else {
			while(this.viewHolder.firstChild) {
				this.viewHolder.removeChild(this.viewHolder.firstChild);
			}
			this.viewHolder.appendChild(content);
		}
	};

	ib.prototype.panMap = function() {
		 var map = this.getMap();
		 var bounds = map.getBounds();
		 if (!bounds) return;
		 
		 var mapWestLng = bounds.getSouthWest().lng();
		 var mapEastLng = bounds.getNorthEast().lng();
		 var mapNorthLat = bounds.getNorthEast().lat();
		 var mapSouthLat = bounds.getSouthWest().lat();
		 
		 var boundsInfoBox = this.getBounds();
		 
		 var iwWestLng = boundsInfoBox.getSouthWest().lng();
		 var iwEastLng = boundsInfoBox.getNorthEast().lng();
		 var iwNorthLat = boundsInfoBox.getNorthEast().lat();
		 var iwSouthLat = boundsInfoBox.getSouthWest().lat();
		 
		 var shiftLng = (iwWestLng < mapWestLng ? mapWestLng - iwWestLng : 0) + (iwEastLng > mapEastLng ? mapEastLng - iwEastLng : 0);
		 var shiftLat = (iwNorthLat > mapNorthLat ? mapNorthLat - iwNorthLat : 0) + (iwSouthLat < mapSouthLat ? mapSouthLat - iwSouthLat : 0);
		 
		 var center = map.getCenter();
		 
		 var centerX = center.lng() - shiftLng;
		 var centerY = center.lat() - shiftLat;
		 
		 map.panTo(new google.maps.LatLng(centerY, centerX));
		 
		 google.maps.event.removeListener(this.boundsChangedListener);
		 this.boundsChangedListener = null;
	}

	ib.prototype.getBounds = function() {
		var map = this.getMap();
		if (!map) return;
		
		var mapBounds = map.getBounds();
		if (!mapBounds) return;
		
		var mapDiv = map.getDiv();
		var mapWidth = mapDiv.offsetWidth;
		var mapHeight = mapDiv.offsetHeight;
		var boundsSpan = mapBounds.toSpan();
		var longSpan = boundsSpan.lng();
		var latSpan = boundsSpan.lat();
		var degPixelX = longSpan / mapWidth;
		var degPixelY = latSpan / mapHeight;
		
		if(this.opts.position == 'top') {
			var offsetTop = (this.anchor instanceof google.maps.Marker) ? this.anchor.getShape().coords[3] : 0;
		} else {
			var offsetTop = (this.anchor instanceof google.maps.Marker) ? - this.viewHolder.offsetHeight : 0;
		}
		
		var westLng = this.position.lng() + (-((this.viewHolder.offsetWidth/2) - this.opts.offsetLeft) - this.opts.panPadding.left) * degPixelX;
		var eastLng = this.position.lng() + (((this.viewHolder.offsetWidth/2) + this.opts.offsetLeft) + this.opts.panPadding.right) * degPixelX;
		var northLat = this.position.lat() - (-this.viewHolder.offsetHeight - offsetTop - this.opts.panPadding.top) * degPixelY;
		var southLat = this.position.lat() - (this.opts.panPadding.bottom - offsetTop) * degPixelY;
		
		return new google.maps.LatLngBounds(new google.maps.LatLng(southLat, westLng), new google.maps.LatLng(northLat, eastLng));
	}
}


/*
 * Other
 */
form.toggleConditionFields();

desktop.loadedResource();