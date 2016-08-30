if (!Date.now) {
    Date.now = function() { return new Date().getTime(); }
}

var setServerCookie = function(cname, cvalue, exdays) {if(typeof exdays === 'undefined') {document.cookie = cname + "=" + cvalue + "; path=/";} else {var d = new Date();d.setTime(d.getTime() + (exdays*24*60*60*1000));var expires = "expires="+d.toGMTString();document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";}};
var getServerCookie = function(cname) { var name = cname + "="; var ca = document.cookie.split(';');for(var i=0; i<ca.length; i++) { var c = ca[i]; while (c.charAt(0)==' ') c = c.substring(1);if (c.indexOf(name) != -1) return c.substring(name.length,c.length); } return "";};

if (typeof(Storage) !== "undefined") {
	function setCookie(cname, cvalue, exdays) {
		var expired = exdays ? Date.now() + (exdays * 86400000) : '';
		
		localStorage.setItem(cname, expired + " " + cvalue);
	}
	
	function getCookie(cname) {
		var item = localStorage.getItem(cname);

		if(item) {
			var index = item.indexOf(" ");
			var expired = item.substring(0, index);
			
			item = item.substring(index + 1, item.length);
			
			if(expired && expired < Date.now()) {
				item = null;
			}
		}
		
		return item;
	}
} else {
	window.setCookie = setServerCookie;
	window.getCookie = getServerCookie;
}

function move(array, old_index, new_index) {
    if (new_index >= array.length) {
        var k = new_index - array.length;
        while ((k--) + 1) {
            array.push(undefined);
        }
    }
    array.splice(new_index, 0, array.splice(old_index, 1)[0]);
    
    return array;
};

function formatPrice(num, round, roundBelowMillion) {
	var parseNum = parseFloat(num);
	
	if(isNaN(num) || isNaN(parseNum)) {
		return null;
	}
	
	round = (typeof round !== 'undefined') ? round : 2;
	
	if(parseNum < 1000000) {
		roundBelowMillion = (typeof roundBelowMillion !== 'undefined') ? roundBelowMillion :0;
		
		return formatNumber(parseNum, roundBelowMillion);
	}
	
	var f = parseFloat((parseNum / 1000000).toFixed(round));
	var unit = lajax.t('million');
	
	if(f >= 1000) {
		f = (f / 1000).toFixed(round);
		unit = lajax.t('billion');
	}
	
	return formatNumber(f) + ' ' + unit;
}

function formatNumber(num, round) {
	var parseNum = parseFloat(num);
	
	if(isNaN(num) || isNaN(parseNum)) {
		return null;
	}
	
	if(typeof round !== 'undefined') {
		parseNum = parseFloat(parseNum.toFixed(round));
	}
	
	var parts = parseNum.toString().split(".");
	
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    
    return parts.join(".");
}

var balance = {
	selector: '.num-gold .notifi',
	get: function() {
		return Number($(this.selector).text());
	},
	update: function(key) {
		$(this.selector).text(key);
	},
	increase: function(key) {
		var newKey = this.get() + key;
		
		this.update(newKey);
	},
	decrease: function(key) {
		var newKey = this.get() - key;
		
		this.update(newKey);
	}
};