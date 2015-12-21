$(document).ready(function(){
	for(index in dataCities) {
		var city = dataCities[index];
		
		$('#city').append('<option value="' + index + '">' + city.name + '</option>');
	}
	
	$('#city').change(function(){
		var city = dataCities[$(this).val()];
		var districtEl = $('#district');
		
		districtEl.find('> option:not(:first)').remove();
		districtEl.select2('val', '');
		
		for(index in city['districts']) {
			var district = city['districts'][index];
			
			districtEl.append('<option value="' + index + '">' + district.pre + ' ' + district.name + '</option>');
		}
	});
	
	$('.select2').select2({width: 'resolve'});
});