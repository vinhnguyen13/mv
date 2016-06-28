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

function formatPrice(price) {
	var priceFormated = price.split( /(?=(?:\d{3})+(?:\.|$))/g ).join(".");
	var priceNum = Number(price);
	
	if(price.length > 9) {
		priceFormated = (priceNum / 1000000000) + '';
		priceFormated = formatNumber(priceFormated.replace('.', ',')) + ' ' + lajax.t('billion');
	} else if(price.length > 6) {
		priceFormated = (priceNum / 1000000) + '';
		priceFormated = priceFormated.replace('.', ',') + ' ' + lajax.t('million');
	}
	
	return priceFormated;
}

function formatNumber(number) {
	if(/^0*$/.test(number)) {
		return '';
	}
	
	number = number.split(',');
	var numberFormated = number[0];
	numberFormated = numberFormated.split( /(?=(?:\d{3})+(?:\.|$))/g ).join(".");
	
	if(number.length > 1) {
		numberFormated = numberFormated + ',' + number[1];
	}
	
	return numberFormated;
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