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
			form.fields.type.on('change', function(){
				form.filterCategories();
			});
			form.fields.categoryId.on('change', function(){
				
			});
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
		cityChange: function() {
			
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
});

function camel(str) {
	return str.replace(/adproduct-|adproductadditioninfo-|adcontactinfo-/, '').replace(/_([a-z])/g, function (g) { return g[1].toUpperCase(); });
}