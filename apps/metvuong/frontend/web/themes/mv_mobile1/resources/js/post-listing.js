$(document).ready(function () {
	var listingForm = $('#listing-form');
	var buildingEl = $('#adproduct-project_building_id');
	var buildingElContainer = buildingEl.closest('.form-group');
	var projectInfoEl = $('#project-info');
	var cityEl = $('#adproduct-city_id');
	var districtEl = $('#adproduct-district_id');
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
		
		if(catId != '') {
			if(catId == '6') {
				buildingElContainer.removeClass('hide');
			} else {
				$('#next-screen').trigger('click');
			}

			catElContainer.removeClass('has-error');
			catElContainer.find('.help-block').text('');
		}
		
		fixHeightStepOne();
	});
	
	buildingEl.change(function(){
		var val = buildingEl.val();

		cityDropdown.show();
		districtDropdown.show();
		
		if(val != '') {
			projectInfoEl.removeClass('hide').addClass('loading');
			fixHeightStepOne();
			
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
				
				fixHeightStepOne();
			});
		} else {
			projectInfoEl.addClass('hide');
			fixHeightStepOne();
		}
	});
	
	function fixHeightStepOne() {
		$('.post-listing').height($('#step-1').outerHeight());
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
			return validate['step' + step]();
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
				success.step1();
				return true;
			}
		},
		step2: function() {
			
		},
		step3: function() {
			
		},
		step4: function() {
			
		}
	};
	
	var success = {
		step1: function() {
			
		},
		step2: function() {
			
		},
		step3: function() {
			
		},
		step4: function() {
			
		}
	};
});