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
			
			form.fields.cityId.on('change', function(e, eventData){
				if(eventData) {
					form.cityChange(function(r){
						form.select(form.fields.districtId, eventData.district_id, eventData);
					});
				} else {
					form.cityChange();
				}
			});
			
			form.fields.districtId.on('change', function(e, eventData){
				if(eventData) {
					form.districtChange(function(r){
						form.select(form.fields.wardId, eventData.ward_id);
						form.select(form.fields.streetId, eventData.street_id);
						form.fields.homeNo.val(eventData.home_no);
					});
				} else {
					form.districtChange();
				}
			});
			
			form.fields.categoryId.on('change', function(){
				if($(this).val() == CHCK) {
					form.getWrap(form.fields.projectBuildingId).fadeIn();
				} else {
					form.getWrap(form.fields.projectBuildingId).fadeOut();
					form.removeProject();
				}
			});
			
			var ss = $('#search-list');
			
			form.fields.projectMask.on('keyup', function(){
				var self = $(this);
				var val = self.val().trim();
				
		    	if(val.length > 1) {
		    		if(self.data('v') != val) {
		    			self.data('v', val);
		            	
		    			if($.data(this, 'ajax')) {
		    				$.data(this, 'ajax').abort();
		    			}
		    			
		    			$.data(this, 'ajax', $.get('/ad/list-project', {v: val}, function(response){
		            		if(response.length) {
		            			showSearchProject();
		            			
		            			var list = ss.find('ul').html('');
		            			
		            			for(var i in response) {
		            				list.append('<li data-id="' + response[i].id + '">' + response[i].full_name + '</li>');
		                      	}
		            		} else {
		            			ss.addClass('hide');
		            		}
		            	}));
		            }
		    	} else {
		    		form.fields.projectMask.data('v', '');
		    		ss.addClass('hide');
		    	}
			}).on('blur', function(){
				form.fields.projectMask.data('v', '').val('');
			});
			
			function showSearchProject(items) {
				ss.removeClass('hide');
				$(document).on('click', hideSearchProject);
			}
			
			function hideSearchProject(e) {
				if($(e.target).attr('id') != 'projectMask') {
		    		ss.addClass('hide');
					$(document).off('click', hideSearchProject);
				}
			}
			
			var projectValue = $('#project-value');
			var projectWrap = $('.project-wrap');
			
			ss.on('click', 'li', function(){
				var self = $(this);
				var val = self.data('id');
				
				form.fields.projectBuildingId.val(self.data('id'));
				projectWrap.addClass('has-project');
				projectValue.find('.name').text(self.text());
				
				$.get('/building-project/detail', {id: val}, function(r){
					projectValue.attr('href', r.url);
					form.select(form.fields.cityId, r.city_id, r);
				});
			});
			
			projectValue.find('.icon-mv').on('click', function(e){
				projectWrap.removeClass('has-project');
				form.fields.projectBuildingId.val('');
				
				e.preventDefault();
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
		removeProject: function() {
			console.log('remove Project');
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
			
			if(form.fields.districtId.val()) {
				$.get('/ad/list-sw', {districtId: form.fields.districtId.val()}, function(response){
					form.appendDropdown(form.fields.wardId, response.wards);
					form.appendDropdown(form.fields.streetId, response.streets);

					if(fn) {
						fn(response);
					}
				});
			}
		},
		select: function(el, val, params) {
			el.val(val).trigger('change', params);
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