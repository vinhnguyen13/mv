$(document).ready(function () {
	var listingForm = $('#listing-form');
	
	var catId = 'adproduct-category_id';
	var catEl = $('#' + catId);
	var defaultCat = Number(catEl.data('default'));

	for(var i in dataCategories) {
		var item = $('<option value="' + i + '">' + dataCategories[i].name + '</option>');
		
		if(i == defaultCat) {
			item.prop('selected', true);
		}
		
		catEl.append(item);
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
			listingForm.yiiActiveForm('validateAttribute', catId);
			
			console.log(catEl.closest('.form-group').attr('class'));
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