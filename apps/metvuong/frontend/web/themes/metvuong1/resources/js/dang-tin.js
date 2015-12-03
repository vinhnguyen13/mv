$(document).ready(function(){
	var streets = dataCities[$('#adproduct-city_id').val()]['districts'][$('#adproduct-district_id').val()]['streets'];
	var wards = dataCities[$('#adproduct-city_id').val()]['districts'][$('#adproduct-district_id').val()]['wards'];
	
	for(index in streets) {
		var street = streets[index];
		$('#adproduct-street_id').append('<option value="' + index + '">' + street.pre + ' ' + street.name + '</option>');
	}
	
	for(index in wards) {
		var ward = wards[index];
		$('#adproduct-ward_id').append('<option value="' + index + '">' + ward.pre + ' ' + ward.name + '</option>');
	}
	
	function dropDownListPriceType(id) {
		$('#adproduct-price_type option:gt(0)').remove();
		$('#adproduct-price_type').append($('#price-type-' + id).html());
	}
	

	dropDownListPriceType($('#adproduct-type').val());
	$('#adproduct-type').change(function(){
		dropDownListPriceType($(this).val());
	});
});