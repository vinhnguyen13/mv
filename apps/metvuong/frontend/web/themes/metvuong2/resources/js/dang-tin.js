$(document).ready(function(){
	var city = dataCities[$('#adproduct-city_id').val()];
	var district = city['districts'][$('#adproduct-district_id').val()];
	var streets = district['streets'];
	var wards = district['wards'];
	var projects = district['projects'];
	var sliderInstance;
	var files = $('.files');
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
	
	$('#adproduct-type').change(function(){
		
		var text = $('#price-format').text();
		
		if(text) {
			if($(this).val() == 1) {
				$('#price-format').text(text.replace('/tháng', ''));
			} else {
				text = text.replace('/tháng', '');
				$('#price-format').text(text + '/tháng');
			}
		}
	});
	
	$('.ad-select').change(function(){
		if($(this).hasClass('select2')) {
			var el = $('#s2id_' + $(this).attr('id'));
			if(el.hasClass('tooltipstered')) {
				$('#s2id_' + $(this).attr('id')).tooltipster('hide');
			}
		} else {
			if($(this).hasClass('tooltipstered')) {
				$(this).tooltipster('hide');
			}
		}
	});
	$('.ad-input').keyup(function(){
		if($(this).val() != '' && $(this).hasClass('tooltipstered')) {
			$(this).tooltipster('hide');
		}
	});
	
	$('.select2').each(function(){
		var self = $(this);
		self.select2({width: 'resolve'});
	});
	
	$('#adproduct-home_no').keydown(function(e) {
		var allow = [46, 8, 9, 27, 13, 110, 116];
		
        if ($.inArray(e.keyCode, allow) !== -1 ||
            (e.ctrlKey === true) ||
            (e.keyCode >= 35 && e.keyCode <= 39)) {
        	
        	if(this.selectionStart == 0) {
        		return;
        	}
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        	if(this.selectionStart == 0)
        		e.preventDefault();
        }
	});
	
	$('.number-only').keydown(function(e){
		var allow = [46, 8, 9, 27, 13, 110, 116];
		
		if($(this).data('float')) {
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
	});
	
	$('#adproduct-area').keyup(function(){
		$('#area-format').text(formatNumber($(this).val()));
	});
	
	$('#adproduct-price').keyup(function(){
		var val = $(this).val();
		var text = '';
		
		if(/^0*$/.test(val)) {
			$('.price-format').text('');
		} else {
			if(val) {
				text = val.split( /(?=(?:\d{3})+(?:\.|$))/g ).join(".");
				
				if(val.length > 9) {
					text = (val / 1000000000) + '';
					text = formatNumber(text.replace('.', ',')) + ' tỷ';
				} else if(val.length > 6) {
					text = (val / 1000000) + '';
					text = text.replace('.', ',') + ' triệu';
				}
				
				if($('#adproduct-type').val() == 2) {
					text += '/tháng';
				}
			}
			
			$('.price-format').text(text);
		}
	});

	lightbox.option({
        'resizeDuration': 300,
        'fadeDuration': 400
    });
	
	var slider = $('.bxslider');
	$('#preview').click(function(e){
		if(validateStep2()) {
			$('.editable').each(function(){
				refText($(this));
				// $(this).append('<em class="icon-pencil"></em>');
			});
			
			$('#ref-adproduct-district_id').text(district['pre'] + ' ' + district['name']);
			$('#ref-adproduct-city_id').text(city['name']);
			
			var images = [];
			files.find('img').each(function(){
				var self = $(this);
				
				images.push({
					src: self.attr('src'),
					id: self.parent().attr('title')
				});
			});
			
			slider.empty();
			
			if(images.length > 0) {
				slider.removeClass('no-image')
				
				var firstGroup = images.splice(0, 5);
				var firstGroupHtml = '';
				
				for(i = 0; i < firstGroup.length; i++) {
					var img = firstGroup[i];
					firstGroupHtml += '<li data-id="' + img.id + '" class="item"> <div class="bgcover" style="background-image:url(' + img.src.replace('thumb', 'medium') + ');"></div> <a data-lightbox="detail-post" class="group mask" href="' + img.src.replace('thumb', 'large') + '"><em class="fa fa-search"></em><img src="' + img.src.replace('thumb', 'medium') + '" alt="" style="display:none;"></a> </li>';
				}
				
				slider.append('<div class="wrap-img-detail first-slide"><ul class="clearfix">' + firstGroupHtml + '</ul></div>');
				
				imagesGroup = images.chunk_inefficient(8);
				
				for(i = 0; i < imagesGroup.length; i++) {
					var images = imagesGroup[i];
					var append = '<div class="wrap-img-detail"><ul class="clearfix">';
					for(j = 0; j < images.length; j++) {
						var img = images[j];
						append += '<li data-id="' + img.id + '" class="item"> <div class="bgcover" style="background-image:url(' + img.src.replace('thumb', 'medium') + ');"></div> <a data-lightbox="detail-post" class="group mask" href="' + img.src.replace('thumb', 'large') + '"><em class="fa fa-search"></em><img src="' + img.src.replace('thumb', 'medium') + '" alt="" style="display:none;"></a> </li>';
					}
					append += '</ul></div>';
					slider.append(append);
				}
				
				if(sliderInstance) {
					slider.find('.gallery-detail').imagesLoaded().done(function(){
						setTimeout(function() {
							sliderInstance.reloadSlider();
				 		}, 300);
					});
				} else {
					slider.find('.gallery-detail').imagesLoaded().done( function( instance ) {
				 		setTimeout(function() {
				 			sliderInstance = slider.bxSlider({
				 				infiniteLoop: false,
				 				moveSlides: 1,
				                startSlide: 0,
				                slideWidth: 800,
				                startSlide: 0,
				                onSliderLoad: function() {
				                    this.infiniteLoop = false;
				                    this.hideControlOnEnd = true;
				                    scrollFixed();
				                }
				            });
				 		}, 300);
				 	});
				}
				
				slider.find('ul').each(function(){
					var options = {};
					var self = $(this);
					if(self.parent().hasClass('first-slide')) {
						options = {
							sort: function(event, ui) {
								if(ui.helper.index() == 0) {
									if(ui.placeholder.index() == 1) {
										ui.placeholder.width(398).height(298);
									} else {
										ui.placeholder.width(198).height(148);
										ui.helper.removeClass('item');
										$(event.target).find('.item').eq(0).width(398).height(298);
									}
								}
							},
							update: function( event, ui ) {
								ui.item.addClass('item');
								$(event.target).find('li').removeAttr('style');
								resortMapper();
							}
						};
					} else {
						options.update = function() {
							resortMapper();
						};
					}
					
					self.sortable(options);
				});
			} else {
				slider.addClass('no-image').text('Không có hình ảnh đính kèm');
			}
		} else {
			e.stopPropagation();
		}
	});
	
	function resortMapper() {
		var li = files.find('> li');
		var obj = li.eq(0);
		slider.find('li').each(function() {
			var id = $(this).data('id');
			
			files.find('> li').each(function(){
				var self = $(this);
				
				if($(this).find('.preview a').attr('title') == id) {
					self.insertAfter(obj);
					obj = self;
					return false;
				}
			});
		});
		resort();
	}
	
	function refText(self) {
		var ref = $('#' + self.attr('id').replace('ref-', ''));
		var text = '';
		
		if(ref.is('span')) {
			text = ref.text();
		} else {
			text = ref.val();
		}
		
//		if(self.hasClass('not-required')) {
//			
//			if(text == '') {
//				self.closest('.not-required-wrap').hide();
//			} else {
//				self.closest('.not-required-wrap').show();
//			}
//		}
		
		if(ref.is('select')) {
			if(ref.val() != '') {
				text = ref.find('option:selected').text();
			}
		}
		
		self.text(text);
	}
	
	$('.editable').click(function(e){
		var self = $(this);
		
		if($(this).hasClass('tooltipstered')) {
			$(this).tooltipster('hide');
		}
		
		if(!self.hasClass('editing')) {
			self.addClass('editing');
			
			var ref = $('#' + self.attr('id').replace('ref-', ''));
			
			if(ref.hasClass('select2')) {
				var clone = ref.clone().attr('id', 'clone' + ref.attr('id'));
			} else {
				if(ref.data('ref')) {
					ref = $('#'+ref.data('ref'));
				}
				var clone = ref.clone(true).attr('id', 'clone' + ref.attr('id'));
			}
			
			
			
			self.css({
				'width': ref.width(),
				'display': 'inline-block'
			});
			
			self.text('').append(clone);
			
			if(ref.attr('id') == 'adproduct-price') {
				self.append($('#price-format').clone().removeAttr('id'));
			}
			
			if(clone.is('select')) {
				clone.val(ref.val());
			}
			
			if(clone.hasClass('select2')) {
				clone.show();
				clone.select2({width: 'resolve'});
			} else {
				SetCaretAtEnd(clone.get(0));
			}
			
			clone.change(function(){
				ref.val(clone.val());
				refText(self);
				
				if(ref.hasClass('select2')) {
					ref.trigger('change');
				}
				
				clone.remove();
				self.removeAttr('style').removeClass('editing');
			});
			
			$(document).on('click', function(event){
				if(self.has(event.target).length == 0 && event.target != self.get(0)) {
					clone.trigger('change');
					$(document).off(event);
				}
			});
		}
	});
	
	function formatNumber(val) {
		if(/^0*$/.test(val)) {
			return '';
		}
		
		val = val.split(',');
		var text = val[0];
		text = text.split( /(?=(?:\d{3})+(?:\.|$))/g ).join(".");
		
		if(val.length > 1) {
			text = text + ',' + val[1];
		}
		
		return text;
	}
	
	$('#detail-listing').on('hide.bs.modal', function (e) {
		$(this).find('.tooltipstered').each(function(){
			$(this).tooltipster('hide');
		});
	});

	stepPost();
	
	files.sortable({
		update: function() {
			resort();
		}
	});
	
	function resort() {
		var values = [];
		files.find('> li').each(function(){
			values.push($(this).find('.preview a').attr('title'));
		});
		$('#images').prev().val(values.join(','));
	}
});

Object.defineProperty(Array.prototype, 'chunk_inefficient', {
    value: function(chunkSize) {
        var array=this;
        return [].concat.apply([],
            array.map(function(elem,i) {
                return i%chunkSize ? [] : [array.slice(i,i+chunkSize)];
            })
        );
    }
});

function SetCaretAtEnd(elem) {
    var elemLen = elem.value.length;
    // For IE Only
    if (document.selection) {
        // Set focus
        elem.focus();
        // Use IE Ranges
        var oSel = document.selection.createRange();
        // Reset position to 0 & then set at end
        oSel.moveStart('character', -elemLen);
        oSel.moveStart('character', elemLen);
        oSel.moveEnd('character', 0);
        oSel.select();
    }
    else if (elem.selectionStart || elem.selectionStart == '0') {
        // Firefox/Chrome
        elem.selectionStart = elemLen;
        elem.selectionEnd = elemLen;
        elem.focus();
    } // if
}

function showMessage(el, m, pu) {
	el.parent().addClass('error-frm');
	if(!el.hasClass('tooltipstered')) {
		var options = {content: m, animation: 'grow', position: 'right', trigger: 'custom', contentAsHTML: true, delay: 0};
		
		if(el.data('position')) {
			options.position = el.data('position');
		}
		
		if(pu) {
			options.positionTracker = true;
			options.theme = 'tooltipster-default model-tooltipster-default';
		}
		
		el.tooltipster(options);
	} else {
		el.tooltipster('content', m);
	}
	el.tooltipster('show');
}

function validateStep1() {
	$return = true;
	
	if(!$('#adproduct-home_no').val()) {
		$return = false;
		showMessage($('#adproduct-home_no'), 'Vui lòng nhập số nhà.');
	} else {
		$('#adproduct-home_no').parent().removeClass('error-frm');
	}
	
	if(!$('#adproduct-street_id').val()) {
		$return = false;
		showMessage($('#s2id_adproduct-street_id'), 'Vui lòng chọn tên Đường.');
	} else {
		$('#adproduct-street_id').parent().removeClass('error-frm');
	}
	
	if(!$('#adproduct-ward_id').val()) {
		$return = false;
		showMessage($('#s2id_adproduct-ward_id'), 'Vui lòng chọn Phường/Xã.');
	} else {
		$('#adproduct-ward_id').parent().removeClass('error-frm');
	}
	
	if(!$('#adproduct-area').val()) {
		$return = false;
		showMessage($('#adproduct-area'), 'Vui lòng nhập diện tích.');
	} else if(/^0*$/.test($('#adproduct-area').val())) {
		$return = false;
		showMessage($('#adproduct-area'), 'Diện tích nhập không hợp lệ');
	} else if($('#adproduct-area').data('limit') && $('#adproduct-area').val() > $('#adproduct-area').data('limit')) {
		$return = false;
		showMessage($('#adproduct-area'), 'Diện tích không được lớn hơn ' + $('#adproduct-area').data('limit') + 'm<sup style="font-size: .7em">2</sup>');
	} else {
		$('#adproduct-area').parent().removeClass('error-frm');
	}
	
	if(!$('#adproduct-price').prop('disabled') && $('#adproduct-price').val() == '') {
		$return = false;
		showMessage($('#adproduct-price'), 'Vui lòng nhập giá.');
	} else if(/^0*$/.test($('#adproduct-price').val())) {
		$return = false;
		showMessage($('#adproduct-price'), 'Giá nhập không hợp lệ');
	} else {
		$('#adproduct-price').parent().removeClass('error-frm');
	}
	
	return $return;
}

function validateStep2() {
	$return = true;
	
	if(!$('#adproduct-content').val()) {
		$return = false;
		showMessage($('#adproduct-content'), 'Vui lòng nhập nội dung tin đăng.');
	} else {
		$('#adproduct-content').parent().removeClass('error-frm');
	}
	
	if(!$('#adcontactinfo-mobile').val()) {
		$return = false;
		showMessage($('#adcontactinfo-mobile'), 'Vui lòng nhập số di động.');
	} else if($('#adcontactinfo-mobile').val().length < 7 || $('#adcontactinfo-mobile').val().length > 11) {
		$return = false;
		showMessage($('#adcontactinfo-mobile'), 'Số di động không được ít hơn 7 hoặc nhiều hơn 11 số.');
	} else {
		$('#adcontactinfo-mobile').parent().removeClass('error-frm');
	}
	
	if($('#adcontactinfo-email').val() != '' && !validateEmail($('#adcontactinfo-email').val())) {
		$return = false;
		showMessage($('#adcontactinfo-email'), 'Địa chỉ email không hợp lệ');
	} else {
		$('#adcontactinfo-email').parent().removeClass('error-frm');
	}
	
	if($('#adcontactinfo-phone').val() != '' && ($('#adcontactinfo-phone').val().length < 7 || $('#adcontactinfo-phone').val().length > 11)) {
		$return = false;
		showMessage($('#adcontactinfo-phone'), 'Số điện thoại không được ít hơn 7 hoặc nhiều hơn 11 số.');
	} else {
		$('#adcontactinfo-phone').parent().removeClass('error-frm');
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

function validateEmail(email) { 
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
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
					$('#frm-post-tin').find('.tooltipstered').each(function(){
						$(this).tooltipster('hide');
					});
					
					objSP.previouStep(_this);
				}

				if ( objSP.flagAjax ) {
					var form = $('#frm-post-tin');
	        	
		        	$.post(form.attr('action'), $('#frm-post-tin').serialize(), function(response){
		        		if(response.success) {
		        			$('#detail-listing').modal('hide');
		        			setTimeout(function() {
		        				_this = $('#preview').addClass('next');
		        				objSP.nextStep(_this);
		        			},300);
		        		} else {
		        			for(index in response.errors) {
		        				var errors = response.errors[index];
		        				for(j in errors) {
		        					var error = errors[j];
		        					var el = $('#' + index + '-' + j);
		        					
		        					if(el.data('ref-back')) {
		        						ref = $('#ref-' + el.data('ref-back'));
		        					} else {
		        						ref = $('#ref-' + index + '-' + j);
		        					}
		        					
		        					showMessage(ref, error[0], true);
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