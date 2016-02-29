$(document).ready(function () {
	
	var catEl = $('#adproduct-category_id');
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
			//return false => fill khong thoa yeu cau => khong next
			//return true => fill thoa yeu cau => next screen
			l(step);
			return true;
		}
	});

});