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
	
	$('.select2').select2({width: 'resolve'});
	
	$('.number-only').keydown(function(e){
		var allow = [46, 8, 9, 27, 13, 110, 116];
		
		if($(this).data('float') && $(this).val().indexOf(',') === -1 && $(this).val() !== '') {
			allow.push(188);
		}
		
        if ($.inArray(e.keyCode, allow) !== -1 ||
            (e.ctrlKey === true) ||
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
	});
	
	$('#adproduct-area').keyup(function(){
		$('#area-format').text(formatNumber($(this).val()));
	});
	
	$('#adproduct-price').keyup(function(){
		var val = $(this).val();
		var text = val.split( /(?=(?:\d{3})+(?:\.|$))/g ).join(".");
		
		if(val.length > 9) {
			text = (val / 1000000000) + '';
			text = formatNumber(text.replace('.', ',')) + ' tỷ';
		} else if(val.length > 6) {
			text = (val / 1000000) + '';
			text = text.replace('.', ',') + ' triệu';
		}
		
		$('#price-format').text(text);
	});
	
	function formatNumber(val) {
		val = val.split(',');
		var text = val[0];
		text = text.split( /(?=(?:\d{3})+(?:\.|$))/g ).join(".");
		
		if(val.length > 1) {
			text = text + ',' + val[1];
		}
		
		return text;
	}

	stepPost();
});

function validateStep1() {
	$return = true;
	
	if(!$('#adproduct-home_no').val()) {
		$return = false;
		$('#adproduct-home_no').parent().addClass('error-frm');
		$('#adproduct-home_no').next().show().html('Vui lòng nhập số nhà.');
	} else {
		$('#adproduct-home_no').parent().removeClass('error-frm');
		$('#adproduct-home_no').next().hide();
	}
	
	if(!$('#adproduct-street_id').val()) {
		$return = false;
		$('#adproduct-street_id').parent().addClass('error-frm');
		$('#adproduct-street_id').next().show().html('Vui lòng chọn tên Đường.');
	} else {
		$('#adproduct-street_id').parent().removeClass('error-frm');
		$('#adproduct-street_id').next().hide();
	}
	
	if(!$('#adproduct-ward_id').val()) {
		$return = false;
		$('#adproduct-ward_id').parent().addClass('error-frm');
		$('#adproduct-ward_id').next().show().html('Vui lòng chọn Phường/Xã.');
	} else {
		$('#adproduct-ward_id').parent().removeClass('error-frm');
		$('#adproduct-ward_id').next().hide();
	}
	
	if(!$('#adproduct-area').val()) {
		$return = false;
		$('#adproduct-area').parent().addClass('error-frm');
		$('#adproduct-area').next().show().html('Vui lòng nhập diện tích.');
	} else {
		$('#adproduct-area').parent().removeClass('error-frm');
		$('#adproduct-area').next().hide();
	}
	
	if(!$('#adproduct-price').prop('disabled') && $('#adproduct-price').val() == '') {
		$return = false;
		$('#adproduct-price').parent().addClass('error-frm');
		$('#adproduct-price').next().show().html('Vui lòng nhập giá.');
	} else {
		$('#adproduct-price').parent().removeClass('error-frm');
		$('#adproduct-price').next().hide();
	}
	
	return $return;
}

function validateStep2() {
	$return = true;
	
	if(!$('#adproduct-content').val()) {
		$return = false;
		$('#adproduct-content').parent().addClass('error-frm');
		$('#adproduct-content').next().show().html('Vui lòng nhập nội dung tin đăng.');
	} else {
		$('#adproduct-content').parent().removeClass('error-frm');
		$('#adproduct-content').next().hide();
	}
	
	if(!$('#adcontactinfo-mobile').val()) {
		$return = false;
		$('#adcontactinfo-mobile').parent().addClass('error-frm');
		$('#adcontactinfo-mobile').next().show().html('Vui lòng nhập số di động.');
	} else {
		$('#adcontactinfo-mobile').parent().removeClass('error-frm');
		$('#adcontactinfo-mobile').next().hide();
	}
	
	return $return;
}

function initMap() {
	var city = dataCities[$('#adproduct-city_id').val()];
	var district = city['districts'][$('#adproduct-district_id').val()];
	var districtName = district['pre'] + ' ' + district['name'];
	var cityName = city['name'];
	var address = $('#adproduct-home_no').val() + ' ' + $( "#adproduct-street_id option:selected" ).text()
					+ ' ' + $( "#adproduct-ward_id option:selected" ).text() + ' ' + districtName + ' ' + cityName;

	var mapOptions = {
		center: {lat: 10.803164, lng: 106.631439},
	    zoom: 16
	};
	asynInitial(document.getElementById('map'), mapOptions, function(gmap){
		gmap.geocode(address, function(latLng) {
			if(latLng) {
				setCenter(latLng);
			} else {
				gmap.geocode(districtName + ' ' + cityName, function(latLng) {
					if(latLng) {
						setCenter(latLng);
					}
				});
			}
		});
		
		function setCenter(latLng) {
			setLngLatField(latLng);
			
			var marker = new Marker({
				draggable: true,
			    animation: google.maps.Animation.DROP,
			    position: latLng
			});
			
			marker.dragend(function(latLng){
				setLngLatField(latLng);
			});
			
			gmap.addMarker(marker, true);
		}
	});
}

function setLngLatField(latLng) {
	$('#adproduct-lng').val(latLng.lng);
	$('#adproduct-lat').val(latLng.lat);
}

function stepPost () {

	var objSP = {
		current_fs: 0,
		next_fs: 0,
		previous_fs: 0,
		left: 0,
		opacity: 0,
		scale: 0,
		animating: false,
		lengStep: $(".fieldset").length,
		flagAjax: false,
		valiStepFrm: false,
		init: function () {
			objSP.eventButton();
		},
		eventButton: function () {
			$('.action-button').on('click', function () {
				var _this = $(this);

				objSP.flagAjax = _this.data('ajaxPost') ? true : false;

				if ( _this.hasClass('next') ) {
					objSP.nextStep(_this);
				}else if( _this.hasClass('previous') ) {
					objSP.previouStep(_this);
				}

				if ( objSP.flagAjax ) {
					var form = $('#frm-post-tin');
	        	
		        	$.post(form.attr('action'), $('#frm-post-tin').serialize(), function(response){
		        		if(response.success) {
		        			l('success!!!');
		        			objSP.nextStep(_this);
		        		} else {
		        			for(index in response.errors) {
		        				var errors = response.errors[index];
		        				for(j in errors) {
		        					var error = errors[j];
		        					$('#' + index + '-' + j).next().show().html(error);
		        				}
		        			}
		        		}
		        	});
					return;
				}
				
				
			});
		},
		nextStep: function (btnStep) {
			if(objSP.animating) 
	        	return false;

	        objSP.current_fs = btnStep.parent();
	        objSP.next_fs = btnStep.parent().next();
	        
	        var index = $(".fieldset").index(objSP.next_fs);

	        if ( index == 1 && !validateStep1() ) {
	        	objSP.flagAjax = false;
	        	return;
	        }
	        if ( index == 2 && !validateStep2() ) {
	        	objSP.flagAjax = false;
	        	return;
	        }

	        objSP.animating = true;

	        $("#progressbar li").eq(index).addClass("active");

	        objSP.next_fs.show(); 
		        
	        objSP.current_fs.animate({opacity: 0}, {
	            step: function(now, mx) {
	                objSP.scale = 1 - (1 - now) * 0.2;
	                objSP.left = (now * 50)+"%";
	                objSP.opacity = 1 - now;
	                objSP.current_fs.css({'transform': 'scale('+objSP.scale+')'});
	                objSP.next_fs.css({'left': objSP.left, 'opacity': objSP.opacity});
	                $('body').addClass('overHidden');
	            }, 
	            duration: 800, 
	            complete: function(){
	                objSP.current_fs.hide();
	                objSP.animating = false;
	                $('body').removeClass('overHidden');
	            	
	            	initMap();
	            }, 
	            easing: 'easeInOutBack'
	        });
		},
		previouStep: function (btnStep) {
			if(objSP.animating) 
				return false;
	        objSP.animating = true;
	        
	        objSP.current_fs = btnStep.parent();
	        objSP.previous_fs = btnStep.parent().prev();
	        
	        $("#progressbar li").eq($(".fieldset").index(objSP.current_fs)).removeClass("active");
	        
	        objSP.previous_fs.show(); 
	        objSP.current_fs.animate({opacity: 0}, {
	            step: function(now, mx) {
	                objSP.scale = 0.8 + (1 - now) * 0.2;
	                objSP.left = ((1-now) * 50)+"%";
	                objSP.opacity = 1 - now;
	                objSP.current_fs.css({'left': objSP.left});
	                objSP.previous_fs.css({'transform': 'scale('+objSP.scale+')', 'opacity': objSP.opacity});
	                $('body').addClass('overHidden');
	            }, 
	            duration: 800, 
	            complete: function(){
	                objSP.current_fs.hide();
	                objSP.animating = false;
	                $('body').removeClass('overHidden');
	            }, 
	            easing: 'easeInOutBack'
	        });
		}
	};

	objSP.init();
}