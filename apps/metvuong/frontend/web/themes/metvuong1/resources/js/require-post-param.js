$(document).ready(function(){
	for(index in dataCities) {
		var city = dataCities[index];
		
		$('#city').append('<option value="' + index + '">' + city.name + '</option>');
	}
	
	filterCat($('#type').val());
	$('#type').change(function(){
		filterCat($(this).val());
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

function filterCat(applyType) {
	var category = $('#category');
	var options = category.find('option');
	var values = [];
	
	options.each(function(){
		var option = $(this);
		var type = option.data('type');
		
		if(type == applyType || type == TYPE_BOTH) {
			option.show();
			values.push(option.attr('value'));
		} else {
			option.hide();
		}
	});
	
	if(values.indexOf(category.val()) == -1) {
		category.val(values[0]);
	}
}