$(document).ready(function(){
	var streets = dataCities[$('#adproduct-city_id').val()]['districts'][$('#adproduct-district_id').val()]['streets'];
	var wards = dataCities[$('#adproduct-city_id').val()]['districts'][$('#adproduct-district_id').val()]['wards'];
	var projects = dataCities[$('#adproduct-city_id').val()]['districts'][$('#adproduct-district_id').val()]['projects'];

	for(index in streets) {
		var street = streets[index];
		$('#adproduct-street_id').append('<option value="' + index + '">' + street.pre + ' ' + street.name + '</option>');
	}
	
	for(index in wards) {
		var ward = wards[index];
		$('#adproduct-ward_id').append('<option value="' + index + '">' + ward.pre + ' ' + ward.name + '</option>');
	}
	
	for(index in projects) {
		var project = projects[index];
		$('#adproduct-project_building_id').append('<option value="' + index + '">' + project.name + '</option>');
	}
	
	function dropDownListPriceType(id) {
		$('#adproduct-price_type option:gt(0)').remove();
		$('#adproduct-price_type').append($('#price-type-' + id).html());
	}
	

	dropDownListPriceType($('#adproduct-type').val());
	$('#adproduct-type').change(function(){
		dropDownListPriceType($(this).val());
		$('#adproduct-price').prop('disabled', false);
	});
	
	$('#adproduct-area, #adproduct-price').keydown(function(e){
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.ctrlKey === true) ||
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
	}).keyup(function(){
		var val = $(this).val().replace(/\./g, '').split( /(?=(?:...)*$)/ ).join('.');
		$(this).val(val);
	});
	
	$('#adproduct-price_type').change(function(){
		if($(this).val() == '1') {
			$('#adproduct-price').prop('disabled', true).val('');
		} else {
			$('#adproduct-price').prop('disabled', false);
		}
	});

	stepPost();
});

function validateStep1() {
	$return = true;
	
	if(!$('#adproduct-home_no').val()) {
		$return = false;
		$('#adproduct-home_no').next().show().html('Vui lòng nhập số nhà.');
	} else {
		$('#adproduct-home_no').next().hide();
	}
	
	if(!$('#adproduct-street_id').val()) {
		$return = false;
		$('#adproduct-street_id').next().show().html('Vui lòng chọn tên Đường.');
	} else {
		$('#adproduct-street_id').next().hide();
	}
	
	if(!$('#adproduct-ward_id').val()) {
		$return = false;
		$('#adproduct-ward_id').next().show().html('Vui lòng chọn Phường/Xã.');
	} else {
		$('#adproduct-ward_id').next().hide();
	}
	
	if(!$('#adproduct-area').val()) {
		$return = false;
		$('#adproduct-area').next().show().html('Vui lòng nhập diện tích.');
	} else {
		$('#adproduct-area').next().hide();
	}
	
	if(!$('#adproduct-price_type').val()) {
		$return = false;
		$('#adproduct-price_type').next().show().html('Vui lòng chọn đơn vị cho giá.');
	} else {
		$('#adproduct-price_type').next().hide();
		
		if(!$('#adproduct-price').prop('disabled') && $('#adproduct-price').val() == '') {
			$return = false;
			$('#adproduct-price').next().show().html('Vui lòng nhập giá.');
		} else {
			$('#adproduct-price').next().hide();
		}
	}
	
	return $return;
}

function stepPost () {
    //jQuery time
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches

    $(".next").click(function(){
//    	if(!validateStep1()) {
//        	return;
//        }
    	
    	
        if(animating) return false;
        animating = true;
        
        current_fs = $(this).parent();
        next_fs = $(this).parent().next();
        
        //activate next step on progressbar using the index of next_fs
        $("#progressbar li").eq($(".fieldset").index(next_fs)).addClass("active");
        
        //show the next fieldset
        next_fs.show(); 
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50)+"%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'transform': 'scale('+scale+')'});
                next_fs.css({'left': left, 'opacity': opacity});
                $('body').addClass('overHidden');
            }, 
            duration: 800, 
            complete: function(){
                current_fs.hide();
                animating = false;
                $('body').removeClass('overHidden');
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    $(".previous").click(function(){
        if(animating) return false;
        animating = true;
        
        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();
        
        //de-activate current step on progressbar
        $("#progressbar li").eq($(".fieldset").index(current_fs)).removeClass("active");
        
        //show the previous fieldset
        previous_fs.show(); 
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1-now) * 50)+"%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
                $('body').addClass('overHidden');
            }, 
            duration: 800, 
            complete: function(){
                current_fs.hide();
                animating = false;
                $('body').removeClass('overHidden');
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    $(".submit").click(function(){
        return false;
    });
}