function setCookie(cname, cvalue, exdays) {if(typeof exdays === 'undefined') {document.cookie = cname + "=" + cvalue + "; path=/";} else {var d = new Date();d.setTime(d.getTime() + (exdays*24*60*60*1000));var expires = "expires="+d.toGMTString();document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";}}
function getCookie(cname) { var name = cname + "="; var ca = document.cookie.split(';');for(var i=0; i<ca.length; i++) { var c = ca[i]; while (c.charAt(0)==' ') c = c.substring(1);if (c.indexOf(name) != -1) return c.substring(name.length,c.length); } return "";}

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