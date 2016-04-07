$(document).ready(function(){

	$('.frm-radio').radio({
		done: function (item) {
			form.filterCategories();

			if(form.getSelectedCat().hasClass('hide')) {
				form.catEl.val('');
			}
		}
	});
	$('.wrap-frm-listing').slideSection({
		active: 0,
		validateFrm: function (step) {
			var s = 'step' + step;
			
			if(form.validate[s]) {
				form.validate[s]();
			}

			form.fixHeight(step);
			
			if($('#' + s).find('.has-error').length) {
				return false;
			} else {
				if(form.success['step' + step]) {
					form.success['step' + step]();
				}
				
				return true;
			}
		},
		funCallBack: function (current) {
			if ( current > 0 ) {
				$('.step-link').show();
			}else {
				$('.step-link').hide();
			}
		}
	});

	form.init();
});

var form = {
	CHCK: '6',
	DNDU: '10',
	init: function() {
		form.initJavasciptCall();
		form.attachEvent();
		
		if($('#is-update').val() == '1') {
			setTimeout(function(){
				$('.preview').trigger('click');
			}, 1);
		}
	},
	initJavasciptCall: function() {
		form.ownerHostEl = $('#owner-host');
		form.catEl = $('#adproduct-category_id');
		form.projectWrapEl = $('#project-info-wrap');
		form.projectEl = $('#adproduct-project_building_id');
		form.projectInfoEl = $('#project-info');
		form.projectDetailEl = $('#project-info-detail');
		form.homeWrapEl = $('#home-wrap');
		form.homeEl = $('#adproduct-home_no');
		form.roomEl = $('#adproductadditioninfo-room_no');
		form.toiletEl = $('#adproductadditioninfo-toilet_no');
		form.showHomeEl = $('#adproduct-show_home_no');
		form.cityEl = $('#adproduct-city_id');
		form.districtEl = $('#adproduct-district_id');
		form.wardEl = $('#adproduct-ward_id');
		form.streetEl = $('#adproduct-street_id');
		form.areaEl = $('#adproduct-area');
		form.priceEl = $('#adproduct-price');
		form.priceFormatEl = $('#price-format');
		form.contentEl = $('#adproduct-content');
		form.facadeWiEl = $('#adproductadditioninfo-facade_width');
		form.landWiEl = $('#adproductadditioninfo-land_width');
		form.homeDiEl = $('#adproductadditioninfo-home_direction');
		form.facadeDiEl = $('#adproductadditioninfo-facade_direction');
		form.floorEl = $('#adproductadditioninfo-floor_no');
		form.interiorEl = $('#adproductadditioninfo-interior');
		form.nameEl = $('#adcontactinfo-name');
		form.mobileEl = $('#adcontactinfo-mobile');
		form.emailEl = $('#adcontactinfo-email');
		form.addressEl = $('#adcontactinfo-address');
		form.latEl = $('#adproduct-lat');
		form.lngEl = $('#adproduct-lng');
		
		form.filterCategories();
	},
	attachEvent: function() {
		$('select').change(function(){
			form.hideError($(this));
		});
		
		$('input').keyup(function(){
			form.hideError($(this));
		});
		
		form.catEl.change(function(){
			if($(this).val() != '') {
				$('#next-screen').trigger('click');
			}
		});
		
		form.showHomeEl.change(function(){
			$(this).data('set', '1');
		});
		
		form.cityEl.change(function(){
			$.get('/ad/list-district', {cityId: form.cityEl.val()}, function(districts){
				form.appendDropdown(form.districtEl, districts);
				
				form.appendDropdown(form.wardEl, []);
				form.appendDropdown(form.streetEl, []);
				form.appendProjectDropdown([]);
			});
		});
		
		form.districtEl.change(function(){
			$.get('/ad/list-swp', {districtId: form.districtEl.val()}, function(response){
				form.appendDropdown(form.wardEl, response.wards);
				form.appendDropdown(form.streetEl, response.streets);
				form.appendProjectDropdown(response.projects);
			});
		});
		
		form.projectEl.change(function(){
			var val = $(this).val();
			
			if(val) {
				form.projectInfoEl.addClass('loading').show();
				
				$.get(form.projectInfoEl.data('url'), {id: val}, function(r){
					form.projectInfoEl.removeClass('loading');
					
					$('#project-info-location').text(r.location);
					form.projectDetailEl.attr('href', r.url);

					form.latEl.val(r.lat);
					form.lngEl.val(r.lng);
					
					form.fixHeight(2);
				});
			} else {
				form.projectInfoEl.hide();
			}
		});
		
		form.priceEl.keyup(function(){
			var val = form.priceEl.val();
			
			if(/^\d+$/.test(val)) {
				val = parseInt(val) + '';
				form.priceFormatEl.show().text(formatPrice(val) + ' ' + lajax.t('VND'));
			} else {
				form.priceFormatEl.hide();
			}
		}).trigger('keyup');
		
		var detailListing = $('.detail-listing');
		
		$('.btn-post').click(function(){
			form.require(form.nameEl, 'Nhập họ tên');
			
			if(form.require(form.mobileEl, 'Nhập số di động')) {
				if(!form.isDigit(form.mobileEl.val())) {
					form.showError(form.mobileEl, 'Số điện thoại không hợp lệ');
				} else if(form.mobileEl.val().length < 7 || form.mobileEl.val().length > 11) {
					form.showError(form.mobileEl, 'Số điện thoại không được ít hơn 7 hoặc nhiều hơn 11 số.');
				} else {
					form.hideError(form.mobileEl);
				}
			}
			
			if(!form.validateEmail(form.emailEl.val())) {
				form.showError(form.emailEl, 'Địa chỉ email không hợp lệ');
			} else {
				form.hideError(form.emailEl);
			}

			if(!$('#step5').find('.has-error').length) {
				var listingForm = $('#listing-form');
				
				$('body').loading();
				
				$.post(listingForm.attr('action'), listingForm.serialize(), function(r){
					if(r.success) {
						listingForm.hide();
						detailListing.hide();
						$('#popup-share-social').removeClass('hide-popup');
						$('body').loading({done: true});
					} else {
						alert('có lỗi xảy ra !');
					}
				});
			}
		});
		
		var formView = $('.post-listing, .fixed-prev-next');
		var swiperContainer = detailListing.find('.swiper-container');
		var swiperWrap = swiperContainer.find('.swiper-wrapper');
		var swiper;
		
		$('.preview').click(function(){
			detailListing.show();
			//formView.hide();
			
			swiperWrap.html('');
			var imgs = $('.files').children();

			if(imgs.length) {
				swiperContainer.show();
				imgs.each(function(){
					swiperWrap.append('<div class="swiper-slide"> <div class="img-show"> <div> <img src="' + $(this).find('a').attr('href') + '"> </div> </div> </div>');
				});
				swiper = new Swiper('.swiper-container', {
					pagination: '.swiper-pagination',
					paginationClickable: true,
			        spaceBetween: 0,
			        nextButton: '.swiper-button-next',
        			prevButton: '.swiper-button-prev'
			    });
			} else {
				swiperContainer.hide();
			}
			
			var type = $("input[name='AdProduct[type]']:checked").parent().next().text();
			var owner = $("input[name='AdProduct[owner]']:checked").parent().next().text();
			
			$('.infor-by-up').html(capitalizeFirstLetter(form.getSelectedCat().text()) + ' ' + type + ' bởi <a href="#">' + owner + '</a>');
			
			$('.address-listing p').text(form.buildAddress());
			
			if(form.catEl.val() == form.DNDU) {
				$('.icon-bed').hide();
				$('.icon-pt').hide();
			} else if(form.catEl.val() == form.CHCK) {
				$('#floor-no-text').text('Floor plan');
			} else {
				$('#floor-no-text').text('Number of storeys');
				$('.icon-bed').show();
				$('.icon-pt').show();
			}
			
			$('.area-show').text(form.areaEl.val());
			$('.bed-show').text(form.roomEl.val());
			$('.toilet-show').text(form.toiletEl.val());
			
			$('.price-show').text(form.priceFormatEl.text());
			$('.content-show').html(form.contentEl.val().replace(/\n/g, "<br />"));
			
			if(form.projectEl.val()) {
				$('.project-item').show();
				$('.project-show').html('<a href="' + form.projectDetailEl.attr('href') + '">' + form.projectEl.find('option').filter(':selected').text() + '</a>');
			} else {
				$('.project-item').hide();
			}
			
			if(form.facadeWiEl.val()) {
				$('.facade-width-item').show();
				$('.facade-width-show').text(form.facadeWiEl.val());
			} else {
				$('.facade-width-item').hide();
			}
			
			if(form.landWiEl.val()) {
				$('.land-width-item').show();
				$('.land-width-show').text(form.landWiEl.val());
			} else {
				$('.land-width-item').hide();
			}
			
			if(form.floorEl.val()) {
				$('.floor-no-item').show().find('strong').text(form.floorEl.attr('placeholder') + ':');
				$('.floor-no-show').text(form.floorEl.val());
			} else {
				$('.floor-no-item').hide();
			}
			
			if(form.homeDiEl.val()) {
				$('.home-di-item').show();
				$('.home-di-show').text(form.homeDiEl.find('option').filter(':selected').text());
			} else {
				$('.home-di-item').hide();
			}
			
			if(form.facadeDiEl.val()) {
				$('.facade-di-item').show();
				$('.facade-di-show').text(form.facadeDiEl.find('option').filter(':selected').text());
			} else {
				$('.facade-di-item').hide();
			}
			
			if(form.interiorEl.val()) {
				$('.interior-item').show();
				$('.interior-show').text(form.interiorEl.val());
			} else {
				$('.interior-item').hide();
			}
			
			$('.name-agent').text(form.nameEl.val());
			
			if(form.mobileEl.val()) {
				$('.icon-phone-item').show();
				$('.phone-show').text(form.mobileEl.val());
			} else {
				$('.icon-phone-item').hide();
			}
			
			if(form.emailEl.val()) {
				$('.icon-email-item').show();
				$('.email-show').text(form.emailEl.val());
			} else {
				$('.icon-email-item').hide();
			}
			
			if(form.addressEl.val()) {
				$('.address-icon-item').show();
				$('.address-show').text(form.addressEl.val());
			} else {
				$('.address-icon-item').hide();
			}
		});
		
		$('.back-form').click(function(){
			detailListing.hide();
			formView.show();
			if(swiperWrap.html()) {
				swiper.destroy(false, true);
			}
			$('#review-listing-post').modal('hide');
		});
		
		$('#add-field').click(function(){
			var additionFields = $('#addition-fields');
			var flag = true;
			
			additionFields.find('.form-control').each(function(){
				var self = $(this);
				
				if(!self.val()) {
					self.focus();
					flag = false;
					
					return false;
				}
			});
			
			if(flag) {
				var template = 	$('#addition-field-template').children().clone();
				var formControl = template.find('.form-control');
				
				formControl.attr('name', formControl.attr('name') + '[]');
				template.find('.field-remove').click(function(){
					template.remove();
				});
				
				additionFields.append(template);
				
				template.find('.field-title').focus();
			}
		});
	},
	appendProjectDropdown: function(projects) {
		form.projectInfoEl.hide();
		form.appendDropdown(form.projectEl, projects);
	},
	filterCategories: function() {
		var type = $("input[name='AdProduct[type]']:checked").val();
		var options = form.catEl.find("option:not(:first-child)");
		
		options.each(function() {
			var self = $(this);
			var oType = self.data('type');
			
			(oType == type || oType == APPLY_TO_TYPE_BOTH) ? self.removeClass('hide') : self.addClass('hide');
		});
	},
	showError: function(el, m) {
		var container = el.closest('.form-group');
		container.addClass('has-error');
		container.find('.help-block').text(m);
	},
	hideError: function(el) {
		var container = el.closest('.form-group');
		container.removeClass('has-error');
		container.find('.help-block').text('');
	},
	hideEl: function(el) {
		el.closest('.form-group').hide();
	},
	showEl: function(el) {
		el.closest('.form-group').show();
	},
	appendDropdown: function(el, items) {
		el.find("option:not(:first-child)").remove();
		for(var i in items) {
			el.append('<option value="' + items[i]['id'] + '">' + items[i]['name'] + '</option>');
		}
	},
	getSelectedCat: function() {
		return form.catEl.find("option:not(:first-child)").filter(':selected');
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
	},
	fixHeight: function(step) {
		//$('.post-listing').height($('#step' + step).outerHeight() + 120);
	},
	require: function(el, m) {
		if(!el.val()) {
			form.showError(el, m);
			return false;
		} else {
			form.hideError(el);
			return true;
		}
	},
	validateEmail: function (email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	},
	buildAddress: function() {
		var address = '';
		
		if(form.showHomeEl.prop('checked') && form.catEl.val() != form.DNDU && form.homeEl.val()) {
			address += form.homeEl.val() + ', ';
		}
		
		address = address + ' ' + form.streetEl.find('option').filter(':selected').text() + ', '
				+ form.wardEl.find('option').filter(':selected').text() + ', '
				+ form.districtEl.find('option').filter(':selected').text() + ', '
				+ form.cityEl.find('option').filter(':selected').text();
		
		return address;
	},
	setLatLng: function(results) {
		var r = results[0];
		form.latEl.val(r.geometry.location.lat());
		form.lngEl.val(r.geometry.location.lng());
	}
};

form.validate = {
	step1: function() {
		form.require(form.catEl, lajax.t('Choose property types'));
	},
	step2: function() {
		form.require(form.cityEl, lajax.t('Choose city'));
		form.require(form.districtEl, lajax.t('Choose district'));
		form.require(form.wardEl, lajax.t('Choose ward'));
		form.require(form.streetEl, lajax.t('Choose street'));
		
		if(form.require(form.areaEl, lajax.t('Enter home size'))) {
			var selectedCat = form.getSelectedCat();
			var limit = Number(selectedCat.data('limit'));

			if(!form.isNumber(form.areaEl.val()) || form.areaEl.val() < 0) {
				form.showError(form.areaEl, lajax.t('Home size is invalid'));
			} else if(limit < (formatArea = form.formatNumber(form.areaEl.val()))) {
				form.showError(form.areaEl, lajax.t('Home size must be not greater %s').replace('%s', limit));
			} else {
				form.areaEl.val(parseFloat(formatArea).toString().replace('.', ','));
				form.hideError(form.areaEl);
			}
		}
		
		if(form.require(form.priceEl, lajax.t('Enter price'))) {
			if(!form.isDigit(form.priceEl.val())) {
				form.showError(form.priceEl, lajax.t('Price is invalid'));
			} else {
				form.priceEl.val(parseInt(form.priceEl.val()));
				form.hideError(form.priceEl);
			}
		}
		
		if(form.catEl.val() == form.DNDU) {
			form.hideError(form.roomEl);
			form.hideError(form.toiletEl);
		} else {
			form.require(form.roomEl, lajax.t('Enter no of bedrooms'));
			form.require(form.toiletEl, lajax.t('Enter no of bathrooms'));
		}
	},
	step3: function() {
		form.require(form.contentEl, lajax.t('Enter content'));
		
		if(form.facadeWiEl.val() && !form.isDigit(form.facadeWiEl.val())) {
			form.showError(form.facadeWiEl, lajax.t('Facade is invalid'));
		} else {
			form.hideError(form.facadeWiEl);
		}
		
		if(form.landWiEl.val() && !form.isDigit(form.landWiEl.val())) {
			form.showError(form.landWiEl, lajax.t('Entry width is invalid'));
		} else {
			form.hideError(form.landWiEl);
		}
		
		if(form.catEl.val() == form.DNDU) {
			form.hideError(form.floorEl);
		} else {
			if(form.floorEl.val() && !form.isDigit(form.floorEl.val())) {
				form.showError(form.floorEl, lajax.t('%s is invalid').replace('%s', form.floorEl.attr('placeholder')));
			} else {
				form.hideError(form.floorEl);
			}
		}
	}
};

form.success = {
	step1: function() {
		form.projectWrapEl.hide();
    	form.floorEl.attr('placeholder', lajax.t('Number of storeys'));
		
		form.homeWrapEl.show();
    	form.showEl(form.roomEl);
    	form.showEl(form.toiletEl);
    	form.showEl(form.homeDiEl);
    	form.showEl(form.facadeDiEl);
    	form.showEl(form.floorEl);
    	form.showEl(form.interiorEl);
    	
		switch(form.catEl.val()) {
	    	case form.CHCK:
	    		form.projectWrapEl.show();
	        	form.floorEl.attr('placeholder', lajax.t('Floor plan'));
	    		break;
	    	case form.DNDU:
	        	form.homeWrapEl.hide();
	        	form.hideEl(form.roomEl);
	        	form.hideEl(form.toiletEl);
	        	form.hideEl(form.homeDiEl);
	        	form.hideEl(form.facadeDiEl);
	        	form.hideEl(form.floorEl);
	        	form.hideEl(form.interiorEl);
	        	
	        	break;
		}
		
		if(form.showHomeEl.data('set') == '0') {
			form.showHomeEl.prop('checked', form.ownerHostEl.prop('checked'));
		}
	},
	step2: function() {
		if(!form.projectEl.val()) {
			var geoCode = new google.maps.Geocoder;
			geoCode.geocode({address: form.buildAddress()}, function(results) {
				form.setLatLng(results);
			});
		}
	}
};

form.upload = {
	fileuploadcompleted: function(e, data, el) {
		if($('#step4').hasClass('in')) {
			form.fixHeight(4);
		}
	},
	fileuploadadded: function(e, data, el) {
		if($('#step4').hasClass('in')) {
			form.fixHeight(4);
		}
	}
};

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}