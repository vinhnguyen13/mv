$(document).ready(function(){
	for(index in dataCities) {
		var city = dataCities[index];
		
		$('#city').append('<option value="' + index + '">' + city.name + '</option>');
	}
	
	$('#type').change(function(){
		var val = $(this).val();
		
		$('#category option').each(function(){
			var self = $(this);
			var type = self.data('type');
			
			if(type == val || type == 3) {
				self.show();
			} else {
				self.hide().prop('selected', false);
			}
		});
	});
	
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