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
			
			form.validate[s]();

			form.fixHeight(step);
			
			if($('#' + s).find('.has-error').length) {
				return false;
			} else {
				form.success['step' + step]();
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
		form.homeEl = $('#adproduct-home_no');
		form.roomEl = $('#adproductadditioninfo-room_no');
		form.toiletEl = $('#adproductadditioninfo-toilet_no');
		form.showHomeEl = $('#adproduct-show_home_no');
		form.cityEl = $('#adproduct-city_id');
		form.districtEl = $('#adproduct-district_id');
		form.wardEl = $('#adproduct-ward_id');
		form.streetEl = $('#adproduct-street_id');
		form.areaEl = $('#adproduct-area');
		
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
		$('.post-listing').height($('#step' + step).outerHeight());
	}
}; 

form.validate = {
	step1: function() {
		if(!form.catEl.val()) {
			form.showError(form.catEl, 'Chọn loại BĐS');
		} else {
			form.hideError(form.catEl);
		}
	},
	step2: function() {
		if(!form.cityEl.val()) {
			form.showError(form.cityEl, 'Chọn Tỉnh/Thành Phố');
		} else {
			form.hideError(form.cityEl);
		}
		
		if(!form.districtEl.val()) {
			form.showError(form.districtEl, 'Chọn Quận/Huyện');
		} else {
			form.hideError(form.districtEl);
		}
		
		if(!form.wardEl.val()) {
			form.showError(form.wardEl, 'Chọn Phường/Xã');
		} else {
			form.hideError(form.wardEl);
		}
		
		if(!form.streetEl.val()) {
			form.showError(form.streetEl, 'Chọn Đường');
		} else {
			form.hideError(form.streetEl);
		}
		
		var selectedCat = form.getSelectedCat();
		var limit = Number(selectedCat.data('limit'));

		if(!form.areaEl.val()) {
			form.showError(form.areaEl, 'Nhập diện tích');
		} else if(!form.isNumber(form.areaEl.val()) || form.areaEl.val() < 0) {
			form.showError(form.areaEl, 'Diện tích không hợp lệ');
		} else if(limit < (formatArea = form.formatNumber(form.areaEl.val()))) {
			form.showError(form.areaEl, 'Diện tích không được lớn hơn ' + limit);
		} else {
			form.areaEl.val(parseFloat(formatArea).toString().replace('.', ','));
			form.hideError(form.areaEl);
		}
		
		if(form.catEl.val() == form.DNDU) {
			form.hideError(form.roomEl);
			form.hideError(form.toiletEl);
		} else {
			if(!form.roomEl.val()) {
				form.showError(form.roomEl, 'Chọn số phòng ngủ');
			} else {
				form.hideError(form.roomEl);
			}
			
			if(!form.toiletEl.val()) {
				form.showError(form.toiletEl, 'Chọn số phòng tắm');
			} else {
				form.hideError(form.toiletEl);
			}
		}
	}
};

form.success = {
	step1: function() {
		form.projectWrapEl.hide();
	
		form.showEl(form.homeEl);
    	form.showEl(form.roomEl);
    	form.showEl(form.toiletEl);
    	
		switch(form.catEl.val()) {
	    	case form.CHCK:
	    		form.projectWrapEl.show();
	    		break;
	    	case form.DNDU:
	        	form.hideEl(form.homeEl);
	        	form.hideEl(form.roomEl);
	        	form.hideEl(form.toiletEl);
	        	break;
		}
		
		if(form.showHomeEl.data('set') == '0') {
			form.showHomeEl.prop('checked', form.ownerHostEl.prop('checked'));
		}
	},
	step2: function() {
		
	}
};
//var upload = {};
//
//$(document).ready(function () {
//	var listingForm = $('#listing-form');
//	var buildingEl = $('#adproduct-project_building_id');
//	var buildingElContainer = buildingEl.closest('.form-group');
//	var projectInfoEl = $('#project-info');
//	var cityEl = $();
//	var districtEl = $();
//	var wardEl = $();
//	var streetEl = $();
//	var roomEl = $('#adproductadditioninfo-room_no');
//	var toiletEl = $('#adproductadditioninfo-toilet_no');
//	var facadeWidthEl = $('#adproductadditioninfo-facade_width');
//	var landWidthEl = $('#adproductadditioninfo-land_width');
//	var floorNoEl = $('#adproductadditioninfo-floor_no');
//	var cityDropdown = $('#city');
//	var districtDropdown = $('#district');
//	
//	var catEl = $('#adproduct-category_id');
//	var catElContainer = catEl.closest('.form-group');
//	var defaultCat = Number(catEl.data('default'));
//	
//	var priceEl = $('#adproduct-price');
//	
////	priceEl.after(priceFormat);
//	priceEl.keyup(function(){
//		var priceFormat = $('#price-format');
//		
//		if(/^\d+$/.test(priceEl.val())) {
//			priceFormat.show().text(formatPrice(priceEl.val()) + ' VNĐ');
//		} else {
//			priceFormat.hide();
//		}
//	}).trigger('keyup');
//	
//	$('.btn-post').click(function(){
//		var isValid = true;
//		var mobileEl = $('#adcontactinfo-mobile');
//		var mobileVal = mobileEl.val();
//		var emailEl = $('#adcontactinfo-email');
//		var emailVal = emailEl.val();
//		
//		if(mobileVal) {
//			if(!/^\d+$/.test(mobileVal)) {
//				showError(mobileEl, 'Số điện thoại không hợp lệ');
//				isValid = false;
//			} else if(mobileVal.length < 7 || mobileVal.length > 11) {
//				showError(mobileEl, 'Số di động không được ít hơn 7 hoặc nhiều hơn 11 số.');
//				isValid = false;
//			} else {
//				hideError(mobileEl);
//			}
//		} else {
//			showError(mobileEl, 'Vui lòng nhập SĐT');
//			isValid = false;
//		}
//		
//		if(emailVal && !validateEmail(emailVal)) {
//			showError(emailEl, 'Địa chỉ email không hợp lệ');
//			isValid = false;
//		} else {
//			hideError(emailEl);
//		}
//		
//		$('body').loading();
//		
//		if(isValid) {
//			$.post(listingForm.attr('action'), listingForm.serialize(), function(r){
//				if(r.success) {
//					listingForm.hide();
//					$('#popup-share-social').removeClass('hide-popup');
//					$('body').loading({done: true});
//				} else {
//					alert('có lỗi xảy ra !');
//				}
//			});
//		}
//	});
//	
//	function validateEmail(email) { 
//		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
//		return re.test(email);
//	}
//
////	for(var i in dataCities) {
////		cityDropdown.append('<option value="' + i +'">' + dataCities[i].name + '</option>');
////	}
//	
//	for(var i in dataCategories) {
//		var item = $('<option value="' + i + '">' + dataCategories[i].name + '</option>');
//		
//		if(i == defaultCat) {
//			item.prop('selected', true);
//		}
//		
//		catEl.append(item);
//	}
//	
//	
//	if(catEl.val() != '6') {
//		buildingElContainer.addClass('hide');
//	}
//	
//	catEl.change(function(){
//		var catId = catEl.val();
//		buildingElContainer.addClass('hide');
//		buildingEl.val('');
//		projectInfoEl.addClass('hide')
//		
//		cityEl.val('');
//		districtEl.val('');
//		cityDropdown.show();
//		districtDropdown.show();
//		
//		roomEl.closest('.form-group').show();
//		toiletEl.closest('.form-group').show();
//		
//		if(catId != '') {
//			if(catId == '6') {
//				buildingElContainer.removeClass('hide');
//			} else {
//				if(catId == '10') {
//					roomEl.closest('.form-group').hide();
//					toiletEl.closest('.form-group').hide();
//				}
//				
//				$('#next-screen').trigger('click');
//			}
//
//			catElContainer.removeClass('has-error');
//			catElContainer.find('.help-block').text('');
//		}
//		
//		fixHeightStep();
//	});
//	
//	buildingEl.change(function(){
//		var val = buildingEl.val();
//
//		cityDropdown.show();
//		districtDropdown.show();
//		
//		if(val != '') {
//			projectInfoEl.removeClass('hide').addClass('loading');
//			fixHeightStep();
//			
//			cityDropdown.hide();
//			districtDropdown.hide();
//			cityEl.val('');
//			districtEl.val('');
//			
//			$.get(projectInfoEl.data('url'), {id: val}, function(r){
//				projectInfoEl.removeClass('loading');
//				$('#project-info-location').text(r.location);
//				$('#project-info-detail').attr('href', r.url);
//				
//				cityEl.val(r.city_id);
//				districtEl.val(r.district_id);
//				
//				filterWard(r.city_id, r.district_id);
//				filterStreet(r.city_id, r.district_id)
//				
//				fixHeightStep();
//			});
//		} else {
//			projectInfoEl.addClass('hide');
//			fixHeightStep();
//		}
//	});
//	
//	cityDropdown.change(function(){
//		var cityId = $(this).val();
//		cityEl.val(cityId);
//		filterDistrict(Number(cityId));
//	});
//	
//	districtDropdown.change(function(){
//		var districtId = $(this).val();
//		districtEl.val(districtId);
//		filterWard(cityEl.val(), districtId);
//		filterStreet(cityEl.val(), districtId);
//	});
//	
//	$('select').change(function(){
//		hideError($(this));
//	});
//	
//	$('input, textarea').keyup(function(){
//		hideError($(this));
//	});
//	
//	function fixHeightStep(step) {
//		if(!step) {
//			step = 1;
//		}
//		$('.post-listing').height($('#step-' + step).outerHeight());
//	}
//	
//	function filterDistrict(cityId) {
//		districtEl.val('');
//		wardEl.html('').append('<option value="">Phường/Xã</option>');
//		streetEl.html('').append('<option value="">Đường</option>');
//		
//		var districts = dataCities[cityId]['districts'];
//		districtDropdown.html('').append('<option value="">Quận/Huyện</option>');
//		for(did in districts) {
//			districtDropdown.append('<option value="' + did + '">' + districts[did].pre + ' ' + districts[did].name + '</option>');
//		}
//	}
//	
//	function filterWard(cityId, districtId) {
//		var wards = dataCities[cityId]['districts'][districtId]['wards'];
//		wardEl.html('').append('<option value="">Phường/Xã</option>');
//		for(wid in wards) {
//			wardEl.append('<option value="' + wid + '">' + wards[wid].pre + ' ' + wards[wid].name + '</option>');
//		}
//	}
//	
//	function filterStreet(cityId, districtId) {
//		var streets = dataCities[cityId]['districts'][districtId]['streets'];
//		streetEl.html('').append('<option value="">Đường</option>');
//		for(sid in streets) {
//			streetEl.append('<option value="' + sid + '">' + streets[sid].pre + ' ' + streets[sid].name + '</option>');
//		}
//	}
//	
//	/*$('.dropdown-select').dropdown({
//		hiddenFillValue: '#sort'
//	});*/
//

//

//
//	var validate = {
//		step1: function() {
//			var catHint = catElContainer.find('.help-block');
//			
//			if(catEl.val() == '') {
//				catElContainer.addClass('has-error');
//				catHint.text('Vui lòng chọn loại BĐS');
//				return false;
//			} else {
//				catElContainer.removeClass('has-error');
//				catHint.text('');
//				return true;
//			}
//		},
//		step2: function() {
////			var isValid = true;
////			
////			if(!cityEl.val()) {
////				showError(cityDropdown, 'Vui lòng chọn Tỉnh/Thành Phố');
////				isValid = false;
////			} else {
////				hideError(cityDropdown);
////			}
////			
////			if(!districtEl.val()) {
////				showError(districtDropdown, 'Vui lòng chọn Quận/Huyện');
////				isValid = false;
////			} else {
////				hideError(districtDropdown);
////			}
////			
////			if(!wardEl.val()) {
////				showError(wardEl, 'Vui lòng chọn Phường/Xã');
////				isValid = false;
////			} else {
////				hideError(wardEl);
////			}
////
////			if(!streetEl.val()) {
////				showError(streetEl, 'Vui lòng chọn Đường');
////				isValid = false;
////			} else {
////				hideError(streetEl);
////			}
////			
////			var areaEl = $('#adproduct-area');
////			var areaVal = areaEl.val();
////			var priceEl = $('#adproduct-price');
////			var priceVal = priceEl.val();
////			var roomEl = $('#adproductadditioninfo-room_no');
////			var roomVal = roomEl.val();
////			var toiletEl = $('#adproductadditioninfo-toilet_no');
////			var toiletVal = toiletEl.val();
////			
////			if(areaVal) {
////				var format = areaVal.replace('.', 'A').replace(',', '.');
////				if(isNaN(format) || format < 0) {
////					showError(areaEl, 'Diện tích nhập không hợp lệ');
////					isValid = false;
////				} else if(catEl.val() != '10' && format > 200) {
////					showError(areaEl, 'Diện tích không được lớn hơn 200');
////					isValid = false;
////				} else {
////					areaEl.val(parseFloat(format).toString().replace('.', ','));
////					hideError(areaEl);
////				}
////			} else {
////				showError(areaEl, 'Vui lòng nhập diện tích');
////				isValid = false;
////			}
////			
////			if(priceVal) {
////				if(/^\d+$/.test(priceVal)) {
////					priceEl.val(parseInt(priceVal, 10));
////					hideError(priceEl);
////				} else {
////					showError(priceEl, 'Giá nhập không hợp lệ');
////				}
////			} else {
////				showError(priceEl, 'Vui lòng nhập giá');
////				isValid = false;
////			}
////			
////			if(catEl.val() != '10') {
////				if(roomVal) {
////					if(/^\d+$/.test(roomVal)) {
////						roomEl.val(parseInt(roomVal, 10));
////						hideError(roomEl);
////					} else {
////						showError(roomEl, 'Số phòng ngủ nhập không hợp lệ');
////					}
////				} else {
////					showError(roomEl, 'Vui lòng nhập số phòng ngủ');
////					isValid = false;
////				}
////				
////				if(toiletVal) {
////					if(/^\d+$/.test(toiletVal)) {
////						toiletEl.val(parseInt(toiletVal, 10));
////						hideError(toiletEl);
////					} else {
////						showError(toiletEl, 'Số toilet nhập không hợp lệ');
////					}
////				} else {
////					showError(toiletEl, 'Vui lòng nhập số toilet');
////					isValid = false;
////				}
////			}
////			
////			fixHeightStep(2);
//			
////			return isValid;
//			return true;
//		},
//		step3: function() {
//			var isValid = true;
//			var contentEl = $('#adproduct-content');
//			
//			if(contentEl.val()) {
//				hideError(contentEl);
//			} else {
//				showError(contentEl, 'Vui lòng nhập số nội dung tin đăng');
//				isValid = false;
//			}
//			
//			var floorNoEl = $('#adproductadditioninfo-floor_no');
//			var floorNoVal = floorNoEl.val();
//			
//			if(floorNoVal && !/^\d+$/.test(floorNoVal)) {
//				showError(floorNoEl, 'Số tầng nhập không hợp lệ');
//				isValid = false;
//			} else {
//				hideError(floorNoEl);
//			}
//			
//			if(catEl.val() != '6') {
//				var facadeWidthVal = facadeWidthEl.val();
//				var landWidthVal = landWidthEl.val();
//				
//				if(facadeWidthVal && !/^\d+$/.test(facadeWidthVal)) {
//					showError(facadeWidthEl, 'Mặt tiền phải là số');
//					isValid = false;
//				} else {
//					hideError(facadeWidthEl);
//				}
//				
//				if(landWidthVal && !/^\d+$/.test(landWidthVal)) {
//					showError(landWidthEl, 'Đường vào phải là số');
//					isValid = false;
//				} else {
//					hideError(landWidthEl);
//				}
//			}
//			
//			return isValid;
//		},
//		step4: function() {
//			return true;
//		}
//	};
//	
//	var success = {
//		step1: function() {
//			var homeNoEl = $('#adproduct-home_no');
//			
//			if($('#adproduct-project_building_id').val()) {
//				homeNoEl.closest('.form-group').hide();
//			} else {
//				homeNoEl.closest('.form-group').show();
//			}
//			
////			if($('#owner-host').prop('checked')) {
////				$('#adproduct-show_home_no').prop('checked', true);
////			} else {
////				$('#adproduct-show_home_no').prop('checked', false);
////			}
//		},
//		step2: function() {
//			if(catEl.val() == '6') {
//				facadeWidthEl.closest('.form-group').hide();
//				landWidthEl.closest('.form-group').hide();
//				floorNoEl.attr('placeholder', 'Tầng cao');
//			} else {
//				facadeWidthEl.closest('.form-group').show();
//				landWidthEl.closest('.form-group').show();
//				floorNoEl.attr('placeholder', 'Số tầng');
//			}
//		},
//		step3: function() {
//			
//		},
//		step4: function() {
//			if($('#owner-host').prop('checked')) {
//				
//			}
//		}
//	};
//	
//	upload.fileuploadcompleted = function(e, data, el) {
//		if($('#step-4').hasClass('in')) {
//			fixHeightStep(4);
//		}
//	};
//	upload.fileuploadadded = function(e, data, el) {
//		if($('#step-4').hasClass('in')) {
//			fixHeightStep(4);
//		}
//	};
//	
//	function showError(el, error) {
//		var container = el.closest('.form-group');
//		container.addClass('has-error');
//		container.find('.help-block').text(error);
//	}
//	
//	function hideError(el) {

//	}
//});