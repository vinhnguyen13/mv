var upload = {};

$(document).ready(function () {
	var listingForm = $('#listing-form');
	var buildingEl = $('#adproduct-project_building_id');
	var buildingElContainer = buildingEl.closest('.form-group');
	var projectInfoEl = $('#project-info');
	var cityEl = $('#adproduct-city_id');
	var districtEl = $('#adproduct-district_id');
	var wardEl = $('#adproduct-ward_id');
	var streetEl = $('#adproduct-street_id');
	var roomEl = $('#adproductadditioninfo-room_no');
	var toiletEl = $('#adproductadditioninfo-toilet_no');
	var facadeWidthEl = $('#adproductadditioninfo-facade_width');
	var landWidthEl = $('#adproductadditioninfo-land_width');
	var floorNoEl = $('#adproductadditioninfo-floor_no');
	var cityDropdown = $('#city');
	var districtDropdown = $('#district');
	
	var catEl = $('#adproduct-category_id');
	var catElContainer = catEl.closest('.form-group');
	var defaultCat = Number(catEl.data('default'));

	for(var i in dataCities) {
		cityDropdown.append('<option value="' + i +'">' + dataCities[i].name + '</option>');
	}
	
	for(var i in dataCategories) {
		var item = $('<option value="' + i + '">' + dataCategories[i].name + '</option>');
		
		if(i == defaultCat) {
			item.prop('selected', true);
		}
		
		catEl.append(item);
	}
	
	
	if(catEl.val() != '6') {
		buildingElContainer.addClass('hide');
	}
	
	catEl.change(function(){
		var catId = catEl.val();
		buildingElContainer.addClass('hide');
		buildingEl.val('');
		projectInfoEl.addClass('hide')
		
		cityEl.val('');
		districtEl.val('');
		cityDropdown.show();
		districtDropdown.show();
		
		roomEl.closest('.form-group').show();
		toiletEl.closest('.form-group').show();
		
		if(catId != '') {
			if(catId == '6') {
				buildingElContainer.removeClass('hide');
			} else {
				if(catId == '10') {
					roomEl.closest('.form-group').hide();
					toiletEl.closest('.form-group').hide();
				}
				
				$('#next-screen').trigger('click');
			}

			catElContainer.removeClass('has-error');
			catElContainer.find('.help-block').text('');
		}
		
		fixHeightStep();
	});
	
	buildingEl.change(function(){
		var val = buildingEl.val();

		cityDropdown.show();
		districtDropdown.show();
		
		if(val != '') {
			projectInfoEl.removeClass('hide').addClass('loading');
			fixHeightStep();
			
			cityDropdown.hide();
			districtDropdown.hide();
			cityEl.val('');
			districtEl.val('');
			
			$.get(projectInfoEl.data('url'), {id: val}, function(r){
				projectInfoEl.removeClass('loading');
				$('#project-info-location').text(r.location);
				$('#project-info-detail').attr('href', r.url);
				
				cityEl.val(r.city_id);
				districtEl.val(r.district_id);
				
				filterWard(r.city_id, r.district_id);
				filterStreet(r.city_id, r.district_id)
				
				fixHeightStep();
			});
		} else {
			projectInfoEl.addClass('hide');
			fixHeightStep();
		}
	});
	
	cityDropdown.change(function(){
		var cityId = $(this).val();
		cityEl.val(cityId);
		filterDistrict(Number(cityId));
	});
	
	districtDropdown.change(function(){
		var districtId = $(this).val();
		districtEl.val(districtId);
		filterWard(cityEl.val(), districtId);
		filterStreet(cityEl.val(), districtId);
	});
	
	$('select').change(function(){
		hideError($(this));
	});
	
	$('input, textarea').keyup(function(){
		hideError($(this));
	});
	
	function fixHeightStep(step) {
		if(!step) {
			step = 1;
		}
		$('.post-listing').height($('#step-' + step).outerHeight());
	}
	
	function filterDistrict(cityId) {
		districtEl.val('');
		wardEl.html('').append('<option value="">Phường/Xã</option>');
		streetEl.html('').append('<option value="">Đường</option>');
		
		var districts = dataCities[cityId]['districts'];
		districtDropdown.html('').append('<option value="">Quận/Huyện</option>');
		for(did in districts) {
			districtDropdown.append('<option value="' + did + '">' + districts[did].pre + ' ' + districts[did].name + '</option>');
		}
	}
	
	function filterWard(cityId, districtId) {
		var wards = dataCities[cityId]['districts'][districtId]['wards'];
		wardEl.html('').append('<option value="">Phường/Xã</option>');
		for(wid in wards) {
			wardEl.append('<option value="' + wid + '">' + wards[wid].pre + ' ' + wards[wid].name + '</option>');
		}
	}
	
	function filterStreet(cityId, districtId) {
		var streets = dataCities[cityId]['districts'][districtId]['streets'];
		streetEl.html('').append('<option value="">Đường</option>');
		for(sid in streets) {
			streetEl.append('<option value="' + sid + '">' + streets[sid].pre + ' ' + streets[sid].name + '</option>');
		}
	}
	
	/*$('.dropdown-select').dropdown({
		hiddenFillValue: '#sort'
	});*/

	$('.frm-radio').radio({
		done: function (item) {
			/*if ( item.attr('name') == 'agent' ) {
				setTimeout(function() {
					$('#next-screen').trigger('click');
				},250);
			}*/
		}
	});

	$('.post-listing').slideSection({
		active: 0,
		validateFrm: function (step) {
			if(validate['step' + step]()) {
				success['step' + step]();
				return true;
			} else {
				return false;
			}
		}
	});

	var validate = {
		step1: function() {
			var catHint = catElContainer.find('.help-block');
			
			if(catEl.val() == '') {
				catElContainer.addClass('has-error');
				catHint.text('Vui lòng chọn loại BĐS');
				return false;
			} else {
				catElContainer.removeClass('has-error');
				catHint.text('');
				return true;
			}
		},
		step2: function() {
			var isValid = true;
			
			if(!cityEl.val()) {
				showError(cityDropdown, 'Vui lòng chọn Tỉnh/Thành Phố');
				isValid = false;
			} else {
				hideError(cityDropdown);
			}
			
			if(!districtEl.val()) {
				showError(districtDropdown, 'Vui lòng chọn Quận/Huyện');
				isValid = false;
			} else {
				hideError(districtDropdown);
			}
			
			if(!wardEl.val()) {
				showError(wardEl, 'Vui lòng chọn Phường/Xã');
				isValid = false;
			} else {
				hideError(wardEl);
			}

			if(!streetEl.val()) {
				showError(streetEl, 'Vui lòng chọn Đường');
				isValid = false;
			} else {
				hideError(streetEl);
			}
			
			var areaEl = $('#adproduct-area');
			var areaVal = areaEl.val();
			var priceEl = $('#adproduct-price');
			var priceVal = priceEl.val();
			var roomEl = $('#adproductadditioninfo-room_no');
			var roomVal = roomEl.val();
			var toiletEl = $('#adproductadditioninfo-toilet_no');
			var toiletVal = toiletEl.val();
			
			if(areaVal) {
				var format = areaVal.replace('.', 'A').replace(',', '.');
				if(isNaN(format) || format < 0) {
					showError(areaEl, 'Diện tích nhập không hợp lệ');
					isValid = false;
				} else if(catEl.val() != '10' && format > 200) {
					showError(areaEl, 'Diện tích không được lớn hơn 200');
					isValid = false;
				} else {
					areaEl.val(parseFloat(format).toString().replace('.', ','));
					hideError(areaEl);
				}
			} else {
				showError(areaEl, 'Vui lòng nhập diện tích');
				isValid = false;
			}
			
			if(priceVal) {
				if(/^\d+$/.test(priceVal)) {
					priceEl.val(parseInt(priceVal, 10));
					hideError(priceEl);
				} else {
					showError(priceEl, 'Giá nhập không hợp lệ');
				}
			} else {
				showError(priceEl, 'Vui lòng nhập giá');
				isValid = false;
			}
			
			if(catEl.val() != '10') {
				if(roomVal) {
					if(/^\d+$/.test(roomVal)) {
						roomEl.val(parseInt(roomVal, 10));
						hideError(roomEl);
					} else {
						showError(roomEl, 'Số phòng ngủ nhập không hợp lệ');
					}
				} else {
					showError(roomEl, 'Vui lòng nhập số phòng ngủ');
					isValid = false;
				}
				
				if(toiletVal) {
					if(/^\d+$/.test(toiletVal)) {
						toiletEl.val(parseInt(toiletVal, 10));
						hideError(toiletEl);
					} else {
						showError(toiletEl, 'Số toilet nhập không hợp lệ');
					}
				} else {
					showError(toiletEl, 'Vui lòng nhập số toilet');
					isValid = false;
				}
			}
			
			fixHeightStep(2);
			
			return isValid;
		},
		step3: function() {
			var isValid = true;
			var contentEl = $('#adproduct-content');
			
			if(contentEl.val()) {
				hideError(contentEl);
			} else {
				showError(contentEl, 'Vui lòng nhập số nội dung tin đăng');
				isValid = false;
			}
			
			var floorNoEl = $('#adproductadditioninfo-floor_no');
			var floorNoVal = floorNoEl.val();
			
			if(floorNoVal && !/^\d+$/.test(floorNoVal)) {
				showError(floorNoEl, 'Số tầng nhập không hợp lệ');
				isValid = false;
			} else {
				hideError(floorNoEl);
			}
			
			if(catEl.val() != '6') {
				var facadeWidthVal = facadeWidthEl.val();
				var landWidthVal = landWidthEl.val();
				
				if(facadeWidthVal && !/^\d+$/.test(facadeWidthVal)) {
					showError(facadeWidthEl, 'Mặt tiền phải là số');
					isValid = false;
				} else {
					hideError(facadeWidthEl);
				}
				
				if(landWidthVal && !/^\d+$/.test(landWidthVal)) {
					showError(landWidthEl, 'Đường vào phải là số');
					isValid = false;
				} else {
					hideError(landWidthEl);
				}
			}
			
			return isValid;
		},
		step4: function() {
			
		}
	};
	
	var success = {
		step1: function() {
			
		},
		step2: function() {
			if(catEl.val() == '6') {
				facadeWidthEl.closest('.form-group').hide();
				landWidthEl.closest('.form-group').hide();
				floorNoEl.attr('placeholder', 'Tầng cao');
			} else {
				facadeWidthEl.closest('.form-group').show();
				landWidthEl.closest('.form-group').show();
				floorNoEl.attr('placeholder', 'Số tầng');
			}
		},
		step3: function() {
			
		},
		step4: function() {
			
		}
	};
	
	upload.fileuploadcompleted = function(e, data, el) {
		fixHeightStep(4);
	};
	upload.fileuploadadded = function(e, data, el) {
		fixHeightStep(4);
	};
	
	function showError(el, error) {
		var container = el.closest('.form-group');
		container.addClass('has-error');
		container.find('.help-block').text(error);
	}
	
	function hideError(el) {
		var container = el.closest('.form-group');
		container.removeClass('has-error');
		container.find('.help-block').text('');
	}
});