var allow = [46, 8, 9, 27, 13, 110, 116];

$(document).ready(function(){
	var form = {
		el: $('#w0'),
		fields: {},
		init: function() {
			form.el.find('select, input').each(function(){
				var self = $(this);
				
				if(self.attr('id')) {
					form.fields[camel(self.attr('id'))] = self;
				}
			});
			
			form.attachEvents();
		},
		attachEvents: function() {
			form.fields.type.on('change', form.filterCategories);
			
			form.fields.cityId.on('change', function(){
				form.cityChange();
			});
			
			form.fields.districtId.on('change', function(){
				form.districtChange();
			});
			
			form.fields.categoryId.on('change', function(){
				
			});
			
			
			$('.radio-ui').radio({
				done: function (item) {
					if(item.attr('name') == 'price-unit') {
						var unit = Number(item.val());
						var mask = Number(form.fields.priceMask.val().replace(',', '.'));

						calPrice(unit, mask);
					}
				}
			});
			
			form.fields.priceMask.on('keyup', function(e){
				var self = $(this);
				
				var unit = Number(form.el.find('input[name=price-unit]').filter(':checked').val());
				var mask = Number(self.val().replace(',', '.'));
				
				calPrice(unit, mask);
			});
			
			function calPrice(unit, mask) {
				var price = mask * unit;
				var priceFormat = formatNumber(Math.round(price) + '');
				var priceShow = $('#price-show');
				
				if(priceFormat) {
					priceShow.text(priceFormat).parent().show();
				} else {
					priceShow.parent().hide();
				}
			}
		},
		filterCategories: function() {
			var type = form.fields.type.val();
			var selectedValue = form.getSelectedCatEl() ? form.getSelectedCatEl().attr('value') : '';
			var flag = true;
			
			form.fields.categoryId.find("option:not(:first-child)").remove();
			for(var i = 0; i < categories.length; i++) {
				if(categories[i]['apply_to_type'] == type || categories[i]['apply_to_type'] == APPLY_TO_TYPE_BOTH) {
					var option = $('<option value="' + categories[i]['id'] + '" data-limit="' + categories[i]['limit_area'] + '">' + categories[i]['name'] + '</option>');
					
					if(selectedValue == categories[i]['id']) {
						option.prop('selected', true);
						flag = false;
					}

					form.fields.categoryId.append(option);
				}
			}
			
			if(flag && selectedValue != '') {
				form.fields.categoryId.select2("val");
			}
		},
		getSelectedCatEl: function() {
			var el = null;

			form.fields.categoryId.find("option:not(:first-child)").each(function(){
				var self = $(this);
				
				if(self.attr('value') == form.fields.categoryId.val()) {
					el = self;
					
					return false;
				}
			});
			return el;
		},
		getWrap: function(el) {
			return el.closest('.form-group');
		},
		cityChange: function(fn) {
			$.get('/ad/list-district', {cityId: form.fields.cityId.val()}, function(districts){
				form.appendDropdown(form.fields.districtId, districts);
				
				if(fn) {
					fn(districts);
				}
			});
		},
		districtChange: function(fn) {
			form.appendDropdown(form.fields.wardId, []);
			form.appendDropdown(form.fields.streetId, []);
			
			$.get('/ad/list-sw', {districtId: form.fields.districtId.val()}, function(response){
				form.appendDropdown(form.fields.wardId, response.wards);
				form.appendDropdown(form.fields.streetId, response.streets);
			});
		},
		select: function(el, val) {
			el.val(val).trigger('change');
		},
		appendDropdown: function(el, items) {
			el.find("option:not(:first-child)").remove();
			for(var i in items) {
				el.append('<option value="' + items[i]['id'] + '">' + items[i]['name'] + '</option>');
			}
			
			el.select2('val');
		}
	};
	
	form.init();
	
	form.el.find('select').each(function(){
		var self = $(this);
		var options = {
			width: '100%',
			placeholder: '...',
			language: {
				'noResults': function() {
					return self.data('no-results');
				}
			}
		};
		
		if(!self.hasClass('search')) {
			options.minimumResultsForSearch = Infinity;
		}
		
		self.select2(options);
	});
	
	form.el.find('.number-only').on('keydown', numnberOnly);
});

function camel(str) {
	return str.replace(/adproduct-|adproductadditioninfo-|adcontactinfo-/, '').replace(/_([a-z])/g, function (g) { return g[1].toUpperCase(); });
}

function numnberOnly(e) {
	if($(this).hasClass('number-float')) {
		if($(this).val().indexOf(',') === -1 && $(this).val() !== '') {
			allow.push(188);
		}
	} else {
		if(e.keyCode == 48 && $(this).val() == '' && !$(this).data('zero-first')) {
			e.preventDefault();
		}
	}
	
    if ($.inArray(e.keyCode, allow) !== -1 ||
        (e.ctrlKey === true) ||
        (e.keyCode >= 35 && e.keyCode <= 39)) {
             return;
    }
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
}