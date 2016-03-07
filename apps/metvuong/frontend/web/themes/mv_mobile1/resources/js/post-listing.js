$(document).ready(function(){

	$('.frm-radio').radio({
		done: function (item) {
			form.filterCategories();

			if(form.getSelectedCat().hasClass('hide')) {
				form.catEl.val('');
			}
		}
	});
	$('.post-listing').slideSection({
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
	},
	initJavasciptCall: function() {
		form.ownerHostEl = $('#owner-host');
		form.catEl = $('#adproduct-category_id');
		form.projectWrapEl = $('#project-info-wrap');
		form.projectEl = $('#adproduct-project_building_id');
		form.projectInfoEl = $('#project-info');
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
					$('#project-info-detail').attr('href', r.url);

					form.fixHeight(2);
				});
			} else {
				form.projectInfoEl.hide();
			}
		});
		
		form.priceEl.keyup(function(){
			var priceFormat = $('#price-format');
			var val = form.priceEl.val();
			
			if(/^\d+$/.test(val)) {
				val = parseInt(val) + '';
				priceFormat.show().text(formatPrice(val) + ' VNĐ');
			} else {
				priceFormat.hide();
			}
		}).trigger('keyup');
		
		$('.btn-post').click(function(){
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
						$('#popup-share-social').removeClass('hide-popup');
						$('body').loading({done: true});
					} else {
						alert('có lỗi xảy ra !');
					}
				});
			}
		});
		
		$('.preview').click(function(){
			
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
			
			(self.data('type') == type || oType == 3) ? self.removeClass('hide') : self.addClass('hide');
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
			el.append('<option value="' + i + '">' + items[i] + '</option>');
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
		$('.post-listing').height($('#step' + step).outerHeight() + 120);
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
	}
};

form.validate = {
	step1: function() {
		form.require(form.catEl, 'Chọn loại BĐS');
	},
	step2: function() {
		form.require(form.cityEl, 'Chọn Tỉnh/Thành Phố');
		form.require(form.districtEl, 'Chọn Quận/Huyện');
		form.require(form.wardEl, 'Chọn Phường/Xã');
		form.require(form.streetEl, 'Chọn Đường');
		
		if(form.require(form.areaEl, 'Nhập diện tích')) {
			var selectedCat = form.getSelectedCat();
			var limit = Number(selectedCat.data('limit'));

			if(!form.isNumber(form.areaEl.val()) || form.areaEl.val() < 0) {
				form.showError(form.areaEl, 'Diện tích không hợp lệ');
			} else if(limit < (formatArea = form.formatNumber(form.areaEl.val()))) {
				form.showError(form.areaEl, 'Diện tích không được lớn hơn ' + limit);
			} else {
				form.areaEl.val(parseFloat(formatArea).toString().replace('.', ','));
				form.hideError(form.areaEl);
			}
		}
		
		if(form.require(form.priceEl, 'Nhập giá')) {
			if(!form.isDigit(form.priceEl.val())) {
				form.showError(form.priceEl, 'Giá không hợp lệ');
			} else {
				form.priceEl.val(parseInt(form.priceEl.val()));
				form.hideError(form.priceEl);
			}
		}
		
		if(form.catEl.val() == form.DNDU) {
			form.hideError(form.roomEl);
			form.hideError(form.toiletEl);
		} else {
			form.require(form.roomEl, 'Chọn số phòng ngủ');
			form.require(form.toiletEl, 'Chọn số phòng tắm');
		}
	},
	step3: function() {
		form.require(form.contentEl, 'Nhập nội dung');
		
		if(form.facadeWiEl.val() && !form.isDigit(form.facadeWiEl.val())) {
			form.showError(form.facadeWiEl, 'Mặt tiền nhập không hợp lệ');
		} else {
			form.hideError(form.facadeWiEl);
		}
		
		if(form.landWiEl.val() && !form.isDigit(form.landWiEl.val())) {
			form.showError(form.landWiEl, 'Đường vào nhập không hợp lệ');
		} else {
			form.hideError(form.landWiEl);
		}
		
		if(form.catEl.val() == form.DNDU) {
			form.hideError(form.floorEl);
		} else {
			if(form.floorEl.val() && !form.isDigit(form.floorEl.val())) {
				form.showError(form.floorEl, form.floorEl.attr('placeholder') + ' nhập không hợp lệ');
			} else {
				form.hideError(form.floorEl);
			}
		}
	}
};

form.success = {
	step1: function() {
		form.projectWrapEl.hide();
    	form.floorEl.attr('placeholder', 'Số tầng');
		
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
	        	form.floorEl.attr('placeholder', 'Tầng cao');
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