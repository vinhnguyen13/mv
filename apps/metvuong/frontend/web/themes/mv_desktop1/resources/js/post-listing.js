var allow = [46, 8, 9, 27, 13, 110, 116];
var form;

$(document).ready(function(){
	var excludeFields = {
		'10': ['homeNo', 'roomNo', 'toiletNo', 'floorNo', 'homeDirection', 'facadeDirection', 'interior'],
		'11': ['homeNo', 'roomNo', 'toiletNo', 'floorNo', 'homeDirection', 'facadeDirection', 'interior']
	};
	
	var point = {};
	
	$('.point-select').find('.icon-checked').each(function(){
		var self = $(this);
		point[self.closest('li').data('id')] = self;
	});
	
	form = {
		el: $('#w0'),
		fields: {},
		init: function() {
			form.el.find('select, input, textarea').each(function(){
				var self = $(this);
				
				if(self.attr('id')) {
					form.fields[camel(self.attr('id'))] = self;
				}
			});
			
			for(var i in form.fields) {
				form.fields[i].on('change', function(){
					form.hideError($(this));
				});
			}
			
			form.files = $(".files" );
			form.projectWrap = $('.project-wrap');
			form.attachEvents();
			
			form.oldCat = form.fields.categoryId.val();
		},
		resetLatLng: function(e) {
			if(!form.projectWrap.hasClass('has-project')) {
				form.fields.lat.val('');
				form.fields.lng.val('');
			}
		},
		attachEvents: function() {
			form.files.sortable({cancel: '.template-upload'});
			form.files.disableSelection();
			
			form.fields.streetId.on('change', form.resetLatLng);
			form.fields.homeNo.on('change', form.resetLatLng);
			form.fields.wardId.on('change', form.resetLatLng);
			
			form.fields.homeNo.on('change', function(){
				if(form.fields.homeNo.val()) {
					point.address.addClass('selected-point');
				} else {
					point.address.removeClass('selected-point');
				}
			});
			
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
						if(eventData.ward_id) {
							form.select(form.fields.wardId, eventData.ward_id);
							form.fields.wardId.prop('disabled', true);
						}
						
						if(eventData.street_id) {
							form.select(form.fields.streetId, eventData.street_id);
							form.fields.streetId.prop('disabled', true);
						}
						
						if(eventData.home_no) {
							form.fields.homeNo.val(eventData.home_no).prop('disabled', true);
						}
					});
				} else {
					form.districtChange();
				}
			});
			
			form.fields.categoryId.on('change', function(){
				var val = $(this).val();
				
				if(val == CHCK) {
					form.getWrap(form.fields.projectBuildingId).fadeIn();
				} else {
					form.getWrap(form.fields.projectBuildingId).fadeOut(function(){
						form.removeProject();
					});
				}
				
				if(typeof excludeFields[form.oldCat] !== 'undefined') {
					for(var i = 0; i < excludeFields[form.oldCat].length; i++) {
						form.getWrap(form.fields[excludeFields[form.oldCat][i]]).fadeIn();
					}
				}
				
				if(typeof excludeFields[val] !== 'undefined') {
					for(var i = 0; i < excludeFields[val].length; i++) {
						form.getWrap(form.fields[excludeFields[val][i]]).fadeOut().val('');
					}
				}

				form.oldCat = val;
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
			
			ss.on('click', 'li', function(){
				var self = $(this);
				var val = self.data('id');
				
				form.fields.projectBuildingId.val(self.data('id'));
				form.projectWrap.addClass('has-project');
				projectValue.find('.name').text(self.text());
				
				$.get('/building-project/detail', {id: val}, function(r){
					projectValue.attr('href', r.url);
					form.select(form.fields.cityId, r.city_id, r);
					form.fields.cityId.prop("disabled", true);
					form.fields.districtId.prop("disabled", true);
					
					form.fields.lat.val(r.lat);
					form.fields.lng.val(r.lng);
					
					if(r.facilities) {
						var fa = r.facilities.split(',');

						$('.tienich-frm input').each(function(){
							var self = $(this);
							if($.inArray(self.val(), fa) != -1) {
								self.parent().addClass('active');
							}
						});
					}
				});
			});
			
			projectValue.find('.icon-mv').on('click', function(e){
				form.removeProject();
				
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
				var mask = self.val() ? Number(self.val().replace(',', '.')) : '';
				
				calPrice(unit, mask);
			});
			
			function calPrice(unit, mask) {
				var priceShow = $('#price-show');
				
				if(mask) {
					var price = Math.round(mask * unit);
					
					form.fields.price.val(price);
					
					var priceFormat = formatNumber(price + '');
					
					
					if(priceFormat) {
						priceShow.text(priceFormat).parent().show();
					} else {
						priceShow.parent().hide();
					}
				} else {
					form.fields.price.val('');
					priceShow.parent().hide();
				}
			}
			
			$('#preview').click(function(){
				if(form.validate()) {
					$('.error-hint').fadeOut();
					
					if(!form.fields.lat.val()) {
						form.geoLocation(form.buildAddress());
					}
					
					form.showPreview();
				} else {
					$('.error-hint').fadeIn();
				}
			});
			
			$('#review-listing').click(function(e){
				if($(e.target).attr('id') == 'review-listing') {
					form.hidePreview();
				}
			});
			
			$('#post').click(function(){
				var disabled = form.el.find(':input:disabled').prop('disabled', false);
				
				$('body').loading();
				
				$.post(form.el.attr('action'), form.el.serialize(), function(r){
					$('body').loading({done: true});
					
					if(r.success) {
						$('.wrap-frm-listing').hide();
						$('#success-notify').removeClass('hide');
						
						$('#to-detail').attr('href', r.url);
					} else {
						
					}
				});
				
				disabled.prop('disabled', true);
			});
		},
		showPreview: function() {
			$('html, body').addClass('previewing');
			
			var previewEls = form.getPreviewEls();
			
			previewEls.swiperWrapper.html('');
			
			if(tempSlide) {
				previewEls.galleryDetail.show();
				previewEls.noGallery.hide();
				
				for(var i in tempSlide) {
					previewEls.swiperWrapper.append('<div class="swiper-slide"> <div class="img-show"> <div> <img src="' + tempSlide[i] + '"> </div> </div> </div>');
				}
				
				tempSlide = false;
			} else {
				var galleries = form.files.find('.template-download');
				
				if(galleries.length > 0) {
					previewEls.galleryDetail.show();
					previewEls.noGallery.hide();
					
					galleries.each(function(){
						previewEls.swiperWrapper.append('<div class="swiper-slide"> <div class="img-show"> <div> <img src="' + $(this).find('a').attr('href') + '"> </div> </div> </div>');
					});
				} else {
					previewEls.galleryDetail.hide();
					previewEls.noGallery.show();
				}
			}
			
			if(previewEls.swiperWrapper.html() != '') {
				form.swiper = new Swiper('.swiper-container', {
			        nextButton: '.swiper-button-next',
	    			prevButton: '.swiper-button-prev'
			    });
			}
			
			form.updateRef($('#review-listing'));
			
			$('.address-show').text(form.buildAddress());
		},
		updateRef: function(container) {
			container.find('.ref').each(function(){
				var self = $(this);
				var refEl = $(self.data('ref'));
				var val;
				
				if(refEl.is('select')) {
					val = refEl.children().filter(':selected').text();
				} else if(refEl.is(':radio')) {
					var checkedEl = refEl.filter(':checked');
					val = checkedEl.closest('.radio-ui').text();
				} else {
					val = refEl.val();
				}
				
				if(self.data('format')) {
					val = window[self.data('format')](val);
				}
				
				if(self.data('html')) {
					self.html(val);
				} else {
					self.text(val);
				}
			});
		},
		hidePreview: function() {
			$('html, body').removeClass('previewing');
			
			if(form.swiper) {
				form.swiper.destroy(false, true);
				form.swiper = null;
			}
		},
		getPreviewEls: function() {
			return {
				galleryDetail: $('.gallery-detail'),
				noGallery: $('.no-gallery'),
				swiperWrapper: $('.swiper-wrapper')
			};
		},
		geoLocation: function(address) {
			if(address) {
				var geoCode = new google.maps.Geocoder;
				geoCode.geocode({address: address}, function(results) {
					if(results.length) {
						form.fields.lat.val(results[0].geometry.location.lat());
						form.fields.lng.val(results[0].geometry.location.lng());
					} else {
						form.geoLocation(form.splitAddress(address));
					}
				});
			}
		},
		splitAddress: function(address) {
			address = address.split(', ');
			address.shift();
			return address.join(', ');
		},
		buildAddress: function() {
			var address = [];
			
			if(form.fields.homeNo.val() && form.fields.showHomeNo.prop('checked')) {
				address.push(form.fields.homeNo.val());
			}

			
			var street = form.fields.streetId.find('option').filter(':selected');
			address.push(street.data('pre') + ' ' + street.text());
			
			var ward = form.fields.wardId.find('option').filter(':selected');
			address.push(ward.data('pre') + ' ' + ward.text());
			
			address.push(form.fields.districtId.find('option').filter(':selected').text());
			address.push(form.fields.cityId.find('option').filter(':selected').text());
			
			return address.join(', ');
		},
		removeProject: function() {

			form.projectWrap.removeClass('has-project');
			form.fields.projectBuildingId.val('');
			
			form.fields.cityId.prop("disabled", false);
			form.fields.districtId.prop("disabled", false);
			form.fields.wardId.prop("disabled", false);
			form.fields.streetId.prop("disabled", false);
			form.fields.homeNo.prop("disabled", false);
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
			if(form.fields.cityId.val()) {
				form.getWrap(form.fields.districtId).fadeIn();
			} else {
				form.getWrap(form.fields.districtId).fadeOut();
			}
			
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
				form.getWrap(form.fields.wardId).fadeIn();
				form.getWrap(form.fields.streetId).fadeIn();
				form.getWrap(form.fields.homeNo).fadeIn();
			} else {
				form.getWrap(form.fields.wardId).fadeOut();
				form.getWrap(form.fields.streetId).fadeOut();
				form.getWrap(form.fields.homeNo).fadeOut();
			}
			
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
				if(items[i]['pre']) {
					el.append('<option data-pre="' + items[i]['pre'] + '" value="' + items[i]['id'] + '">' + items[i]['name'] + '</option>');
				} else {
					el.append('<option value="' + items[i]['id'] + '">' + items[i]['name'] + '</option>');
				}
			}
			
			el.select2('val');
		},
		fileuploadcompleted: function(e, d, t) {
			form.files.sortable('refreshPositions');
			
			if(form.files.find('.template-download').length > 1) {
				$('#upload-hint').fadeIn();
			}
		},
		fileuploaddestroyed: function(e, d, t) {
			form.files.sortable('refreshPositions');
			
			if(form.files.find('.template-download').length < 2) {
				$('#upload-hint').fadeOut();
			}
		},
		validate: function() {
			form.require(form.fields.categoryId, lajax.t('Choose property types'));
			form.require(form.fields.cityId, lajax.t('Choose city'));
			
			if(!form.isHidden(form.fields.districtId)) {
				form.require(form.fields.districtId, lajax.t('Choose district'));
			}
			
			if(!form.isHidden(form.fields.wardId)) {
				form.require(form.fields.wardId, lajax.t('Choose ward'));
			}
			
			if(!form.isHidden(form.fields.streetId)) {
				form.require(form.fields.streetId, lajax.t('Choose street'));
			}
			
			form.require(form.fields.area, lajax.t('Enter home size'));
			form.require(form.fields.price, lajax.t('Enter price'));
			form.require(form.fields.content, lajax.t('Enter content'));
			form.require(form.fields.mobile, lajax.t('Enter mobile number'));
			
			if(!form.hasError(form.fields.area)) {
				var val = form.fields.area.val();
				
				if(!form.isNumber(val) || val < 0) {
					form.showError(form.fields.area, lajax.t('Home size is invalid'));
				} else {
					if(form.getSelectedCatEl()) {
						var limit = form.getSelectedCatEl().data('limit');
						
						if(limit && Number(limit) < Number(val)) {
							form.showError(form.fields.area, lajax.t('Home size must be not greater %s').replace('%s', limit));
						}
					}
				}
			}
			
			if(!form.hasError(form.fields.price)) {
				if(!form.isNumber(form.fields.price.val())) {
					form.showError(form.fields.price, lajax.t('Price is invalid'));
				}
			}
			
			if(!form.hasError(form.fields.mobile)) {
				var val = form.fields.mobile.val();
				
				if(!form.isDigit(val)) {
					form.showError(form.fields.mobile, lajax.t('Mobile number is invalid'));
				} else if(val.length < 7 || val.length > 11) {
					form.showError(form.fields.mobile, lajax.t('Số di động phải từ 7 đến 11 số.'));
				}
			}
			
			if(form.el.find('.has-error').length > 0) {
				return false;
			}
			
			return true;
		},
		require: function(el, m) {
			if(el.val()) {
				form.hideError(el);
			} else {
				form.showError(el, m);
			}
		},
		showError: function(el, m) {
			form.getWrap(el).addClass('has-error').find('.help-block').text(m);
		},
		hideError: function(el) {
			form.getWrap(el).removeClass('has-error').find('.help-block').text('');
		},
		hasError: function(el) {
			return form.getWrap(el).hasClass('has-error');
		},
		isHidden: function(el) {
			return (form.getWrap(el).css('display') == 'none');
		},
		isNumber: function(number) {
			var format = form.formatNumber(number);

			return !isNaN(format);
		},
		isDigit: function(digit) {
			return /^\d+$/.test(digit);
		},
		formatNumber: function(number) {
			return number.replace('.', 'A').replace(',', '.');
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
	
	var w = $(window);
	var cl = $('.checkpoint-listing');
	var offtop = form.el.offset().top - ($('header').outerHeight() + 12);

	if(Number(cl.css('margin-top').replace('px', '')) == 0) {
		w.on('scroll', wScroll).trigger('scroll');
	}
	
	function wScroll() {
		var st = w.scrollTop();
		if(st >= offtop) {
			cl.css('top', st - offtop);
		} else {
			cl.css('top', 0);
		}
	}
	
	w.on('resize', function(){
		offtop = form.el.offset().top - ($('header').outerHeight() + 12);
		
		if(Number(cl.css('margin-top').replace('px', '')) == 0) {
			w.on('scroll', wScroll);
		} else {
			cl.removeAttr('style');
			w.off('scroll', wScroll);
		}
	});
	
	if(!isNewRecord) {
		
		var maskValue = Number(form.fields.price.val()) / Number(form.fields.priceUnitMil.val());
		
		if(maskValue > 999) {
			form.fields.priceMask.val((Number(form.fields.price.val()) / Number(form.fields.priceUnitBil.val()) + '').replace('.', ','));
			form.fields.priceUnitBil.parent().trigger('click');
		} else {
			form.fields.priceMask.val(maskValue);
			form.fields.priceUnitMil.parent().trigger('click');
		}
		
		form.showPreview();
	}
	
	$('#back').click(function(){
		form.hidePreview();
	});
});

function camel(str) {
	return str.replace(/adproduct-|adproductadditioninfo-|adcontactinfo-/, '').replace(/_([a-z])|-([a-z])/g, function (g) { return g[1].toUpperCase(); });
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

function formatContent(content) {
	return content.replace(/\n/g, "<br />");
}