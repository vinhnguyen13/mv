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