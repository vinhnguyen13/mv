var districtsInFile = {};
var wardsInFile = {};
var wardsBelongDistrict = [];
var notEncodeDistricts = [];
var notEncodeWards = [];

$(document).ready(function(){
	$('#start').click(function(){
		var cityGeometry = JSON.parse(city['geometry']);

		if(typeof cityGeometry[0] !== 'string') {
			var lc = log("Encode Tỉnh/Thành phố...", 1);
			
			var encodedGeometry = encodeGeometryCity(cityGeometry);
			
			saveEncode({table: 'city', id: city['id'], geometry: encodedGeometry}, function(){
				log('OK', lc);
				startEncodeChilds();
			});
		} else {
			log("Tỉnh/Thành phố đã được encode trước đó rồi.", 1);
			startEncodeChilds();
		}
	});
});

function startEncodeChilds() {
	var lgetData = log("Lấy dữ liệu từ file...", 2);
	
	$.get('/store/data/geometry/' + $('#file').val(), function(r){
		log('OK', lgetData);
		log("-------------------------------------", 2);
		
		for(var i in r.geo.features) {
			var districtInFile = r.geo.features[i];
			districtsInFile[districtInFile.id] = districtInFile;
		}
		
		startParseDistrict(0);
	}, 'json');
}

function startParseDistrict(startOffset) {
	if(startOffset < districts.length) {
		wardsInFile = {};
		notEncodeWards = [];
		
		var district = districts[startOffset];
		var districtName = district.pre + " " + district.name;
		
		var findedHasResult = false;
		
		for(var i in districtsInFile) {
			var districtInFile = districtsInFile[i];
			
			var compareName = isNaN(district.name) ? district.name : districtName;
			
			if(districtInFile.properties.name == compareName) {
				log("Tìm thấy " + districtName + " trong file", 2);
				
				encodeDistrict(district, districtInFile, startOffset);
				
				findedHasResult = true;
				
				break;
			}
		}
		
		if(!findedHasResult) {
			log("Không tìm thấy " + districtName + " trong file", 2, 'red');
			
			showDistrictMapper(startOffset, district);
		}
	} else {
		var restD = [];
		
		for(var i in notEncodeDistricts) {
			restD.push(notEncodeDistricts[i].pre + " " + notEncodeDistricts[i].name);
		}
		
		var restDInFile = [];
		
		for(var i in districtsInFile) {
			restDInFile.push(districtsInFile[i].properties.name);
		}

		if(restD.length) {
			log("Các quận/huyện không được encode: " + restD.join(', '), 2, '#00f1ff');
		} else {
			log("Tất cả quận/huyện đã được encode", 2, '#00f1ff');
		}

		if(restDInFile.length) {
			log("Các quận/huyện còn dư trong file: " + restDInFile.join(', '), 2, '#00f1ff');
		} else {
			log("Không có quận huyện sót lại trong file", 2, '#00f1ff');
		}
	}
}

function encodeDistrict(district, districtInFile, startOffset) {
	delete districtsInFile[districtInFile.id];
	
	var districtName = district.pre + " " + district.name;
	
	for(var i in districtInFile.wards.geo.features) {
		var wardInFile = districtInFile.wards.geo.features[i];
		wardsInFile[wardInFile.id] = wardInFile;
	}
	wardsBelongDistrict = getWardsBelongDistrict(district);
	
	if(district.geometry == "0") {
		var ld = log("Bắt đầu encode " + districtName + "...", 2);
		
		var encodedGeometry = encodeGeometry(districtInFile.geometry.coordinates);
		
		saveEncode({table: 'district', id: district['id'], geometry: encodedGeometry}, function(){
			log("OK", ld, '#f5d427');
			startParseWards(0, function(){
				startParseDistrict(startOffset + 1);
			});
		});
	} else {
		log(districtName + " đã được encode trước đó", 2, '#e6ff27');

		startParseWards(0, function(){
			startParseDistrict(startOffset + 1);
		});
	}
}

function startParseWards(startOffset, fn) {
	if(startOffset < wardsBelongDistrict.length) {
		var ward = wardsBelongDistrict[startOffset];
		var wardName = ward.pre + " " + ward.name;
		
		var findedHasResult = false;
		
		for(var i in wardsInFile) {
			var wardInFile = wardsInFile[i];
			
			var compareName = isNaN(ward.name) ? ward.name : wardName;
			
			if(wardInFile.properties.name == compareName) {
				log("Tìm thấy " + wardName + " trong file", 3);
				
				encodeWard(ward, wardInFile, startOffset, fn);
				
				findedHasResult = true;
				
				break;
			}
		}
		
		if(!findedHasResult) {
			log("Không tìm thấy " + wardName + " trong file", 3, 'red');
			
			showWardMapper(startOffset, ward, fn);
		}
	} else {
		
		var restD = [];
		
		for(var i in notEncodeWards) {
			restD.push(notEncodeWards[i].pre + " " + notEncodeWards[i].name);
		}
		
		var restDInFile = [];
		
		for(var i in wardsInFile) {
			restDInFile.push(wardsInFile[i].properties.name);
		}

		if(restD.length) {
			log("Các phường/xã không được encode: " + restD.join(', '), 3, '#00f1ff');
		} else {
			log("Tất cả phường/xã đã được encode", 3, '#00f1ff');
		}

		if(restDInFile.length) {
			log("Các phường/xã còn dư trong file: " + restDInFile.join(', '), 3, '#00f1ff');
		} else {
			log("Không có phường/xã sót lại trong file", 3, '#00f1ff');
		}
		
		fn();
	}
}

function encodeWard(ward, wardInFile, startOffset, fn) {
	delete wardsInFile[wardInFile.id];
	
	var wardName = ward.pre + " " + ward.name;
	
	if(ward.geometry == "0") {
		var ld = log("Bắt đầu encode " + wardName + "...", 3);
		
		var encodedGeometry = encodeGeometry(wardInFile.geometry.coordinates);
		
		saveEncode({table: 'ward', id: ward['id'], geometry: encodedGeometry}, function(){
			log("OK", ld, '#f5d427');
			startParseWards(startOffset + 1, fn);
		});
	} else {
		log(wardName + " đã được encode trước đó", 3, '#e6ff27');

		startParseWards(startOffset + 1, fn);
	}
}

function showWardMapper(startOffset, ward, fn) {
	$('#ward-mapper').show();
	$('#ward-mapper-label').text(ward.pre + " " + ward.name);
	
	$('#ward-mapper-select').html('');
	
	for(var i in wardsInFile) {
		var wardInFile = wardsInFile[i];
		$('#ward-mapper-select').append('<option value="' + wardInFile.id + '">' + wardInFile.properties.name + '</option>');
	}
	
	$('#ward-mapper-cancel').off('click').click(function(){
		$('#ward-mapper').hide();
		notEncodeWards.push(ward);
		startParseWards(startOffset + 1, fn)
	});
	
	$('#ward-mapper-ok').off('click').click(function(){
		$('#ward-mapper').hide();
		var wardInFile = wardsInFile[$('#ward-mapper-select').val()];
		encodeWard(ward, wardInFile, startOffset, fn);
	});
}

function getWardsBelongDistrict(district) {
	var wardsBelongDistrict = [];
	
	for(var i in wards) {
		if(wards[i].district_id == district.id) {
			wardsBelongDistrict.push(wards[i]);
		}
	}
	
	return wardsBelongDistrict;
}

function showDistrictMapper(startOffset, district) {
	$('#district-mapper').show();
	$('#district-mapper-label').text(district.pre + " " + district.name);
	
	$('#district-mapper-select').html('');
	
	for(var i in districtsInFile) {
		var districtInFile = districtsInFile[i];
		$('#district-mapper-select').append('<option value="' + districtInFile.id + '">' + districtInFile.properties.name + '</option>');
	}
	
	$('#district-mapper-cancel').off('click').click(function(){
		$('#district-mapper').hide();
		notEncodeDistricts.push(district);
		startParseDistrict(startOffset + 1);
	});
	
	$('#district-mapper-ok').off('click').click(function(){
		$('#district-mapper').hide();
		var districtInFile = districtsInFile[$('#district-mapper-select').val()];
		encodeDistrict(district, districtInFile, startOffset);
	});
}

function saveEncode(params, fn) {
	$.post(encodeUrl, params, fn);
}

function encodeGeometryCity(geo) {
	var paths = [];
	
	for(var i = 0; i < geo.length; i++) {
		var path = [];

		for(var j = 0; j < geo[i].length; j++) {
			path[j] = new google.maps.LatLng(geo[i][j][0], geo[i][j][1]);
		}

		paths[i] = google.maps.geometry.encoding.encodePath(path);
	}
	return JSON.stringify(paths);
}

function encodeGeometry(geo) {
	var paths = [];
	
	for(var i = 0; i < geo.length; i++) {
		var path = [];
		
		for(var j = 0; j < geo[i][0].length; j++) {
			path[j] = new google.maps.LatLng(geo[i][0][j][1], geo[i][0][j][0]);
		}

		paths[i] = google.maps.geometry.encoding.encodePath(path);
	}
	
	return JSON.stringify(paths);
}

function log(text, logOrLevel, color) {
	
	if(typeof logOrLevel == 'number') {
		var log = $('<div class="level-' + logOrLevel + '"><span>' + text + '</span></div>');
		$('#logs').append(log);
	} else {
		var log = logOrLevel;
		
		log.find('span').append(text);
	}
	
	if(color) {
		log.find('span').css('color', color);
	}
	
	return log;
}