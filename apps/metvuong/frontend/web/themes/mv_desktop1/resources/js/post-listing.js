function setCookie(cname, cvalue, exdays) {if(typeof exdays === 'undefined') {document.cookie = cname + "=" + cvalue + "; path=/";} else {var d = new Date();d.setTime(d.getTime() + (exdays*24*60*60*1000));var expires = "expires="+d.toGMTString();document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";}}
function getCookie(cname) { var name = cname + "="; var ca = document.cookie.split(';');for(var i=0; i<ca.length; i++) { var c = ca[i]; while (c.charAt(0)==' ') c = c.substring(1);if (c.indexOf(name) != -1) return c.substring(name.length,c.length); } return "";}

var allow = [46, 8, 9, 27, 13, 110, 116];
var form;

$(document).ready(function(){
	var CountUp=function(a,t,n,e,i,r){for(var o=0,s=["webkit","moz","ms","o"],m=0;m<s.length&&!window.requestAnimationFrame;++m)window.requestAnimationFrame=window[s[m]+"RequestAnimationFrame"],window.cancelAnimationFrame=window[s[m]+"CancelAnimationFrame"]||window[s[m]+"CancelRequestAnimationFrame"];window.requestAnimationFrame||(window.requestAnimationFrame=function(a,t){var n=(new Date).getTime(),e=Math.max(0,16-(n-o)),i=window.setTimeout(function(){a(n+e)},e);return o=n+e,i}),window.cancelAnimationFrame||(window.cancelAnimationFrame=function(a){clearTimeout(a)});var u=this;u.options={useEasing:!0,useGrouping:!0,separator:",",decimal:".",easingFn:null,formattingFn:null};for(var l in r)r.hasOwnProperty(l)&&(u.options[l]=r[l]);""===u.options.separator&&(u.options.useGrouping=!1),u.options.prefix||(u.options.prefix=""),u.options.suffix||(u.options.suffix=""),u.d="string"==typeof a?document.getElementById(a):a,u.startVal=Number(t),u.endVal=Number(n),u.countDown=u.startVal>u.endVal,u.frameVal=u.startVal,u.decimals=Math.max(0,e||0),u.dec=Math.pow(10,u.decimals),u.duration=1e3*Number(i)||2e3,u.formatNumber=function(a){a=a.toFixed(u.decimals),a+="";var t,n,e,i;if(t=a.split("."),n=t[0],e=t.length>1?u.options.decimal+t[1]:"",i=/(\d+)(\d{3})/,u.options.useGrouping)for(;i.test(n);)n=n.replace(i,"$1"+u.options.separator+"$2");return u.options.prefix+n+e+u.options.suffix},u.easeOutExpo=function(a,t,n,e){return n*(-Math.pow(2,-10*a/e)+1)*1024/1023+t},u.easingFn=u.options.easingFn?u.options.easingFn:u.easeOutExpo,u.formattingFn=u.options.formattingFn?u.options.formattingFn:u.formatNumber,u.version=function(){return"1.7.1"},u.printValue=function(a){var t=u.formattingFn(a);"INPUT"===u.d.tagName?this.d.value=t:"text"===u.d.tagName||"tspan"===u.d.tagName?this.d.textContent=t:this.d.innerHTML=t},u.count=function(a){u.startTime||(u.startTime=a),u.timestamp=a;var t=a-u.startTime;u.remaining=u.duration-t,u.options.useEasing?u.countDown?u.frameVal=u.startVal-u.easingFn(t,0,u.startVal-u.endVal,u.duration):u.frameVal=u.easingFn(t,u.startVal,u.endVal-u.startVal,u.duration):u.countDown?u.frameVal=u.startVal-(u.startVal-u.endVal)*(t/u.duration):u.frameVal=u.startVal+(u.endVal-u.startVal)*(t/u.duration),u.countDown?u.frameVal=u.frameVal<u.endVal?u.endVal:u.frameVal:u.frameVal=u.frameVal>u.endVal?u.endVal:u.frameVal,u.frameVal=Math.round(u.frameVal*u.dec)/u.dec,u.printValue(u.frameVal),t<u.duration?u.rAF=requestAnimationFrame(u.count):u.callback&&u.callback()},u.start=function(a){return u.callback=a,u.rAF=requestAnimationFrame(u.count),!1},u.pauseResume=function(){u.paused?(u.paused=!1,delete u.startTime,u.duration=u.remaining,u.startVal=u.frameVal,requestAnimationFrame(u.count)):(u.paused=!0,cancelAnimationFrame(u.rAF))},u.reset=function(){u.paused=!1,delete u.startTime,u.startVal=t,cancelAnimationFrame(u.rAF),u.printValue(u.startVal)},u.update=function(a){cancelAnimationFrame(u.rAF),u.paused=!1,delete u.startTime,u.startVal=u.frameVal,u.endVal=Number(a),u.countDown=u.startVal>u.endVal,u.rAF=requestAnimationFrame(u.count)},u.printValue(u.startVal)};

	var excludeFields = {
		'10': ['homeNo', 'roomNo', 'toiletNo', 'floorNo', 'homeDirection', 'facadeDirection'],
		'11': ['homeNo', 'roomNo', 'toiletNo', 'floorNo', 'homeDirection', 'facadeDirection']
	};
	
	var point = {
		fields: {},
		counterFields: {},
		pel: $('#score-percent'),
		init: function() {
			$('.point-select').find('li').each(function(){
				var self = $(this);
				var id = self.data('id');
				
				point.fields[id] = self;
				point.fields[id + 'Icon'] = self.find('.icon-checked');
				
				point.counterFields[id] = new CountUp(self.find('.count').get(0), 0, 0, 0, 1, {useEasing : true});
			});
			
			point.counter = new CountUp("score", 0, 0, 0, 1, {useEasing : true});
		},
		update: function(n) {
			point.counter.update(n);
			point.pel.css('width', n + '%');
		},
		current: function() {
			return point.counter.endVal;
		},
		increase: function(n) {
			point.update(point.current() + n);
		},
		decrease: function(n) {
			point.update(point.current() - n);
		},
		checkPoint: function(field, p) {
			var ppp = $(point.counterFields[field].d).parent();
			clearTimeout(ppp.data('timeout'));
			ppp.stop(true, true).show();
			
			point.fields[field + 'Icon'].addClass('selected-point');
			point.counterFields[field].update(p);
			return p;
		},
		uncheckPoint: function(field) {
			point.fields[field + 'Icon'].removeClass('selected-point');
			point.counterFields[field].update(0);
			
			var pp = $(point.counterFields[field].d);
			var ppp = pp.parent();
			
			clearTimeout(ppp.data('timeout'));
			ppp.data('timeout', setTimeout(function(){
				ppp.fadeOut();
			}, 500));
		},
		calc: function() {
			var p = 0;

			var pType = 0;

			pType += form.fields.type.val() ? form.getPoint(form.fields.type) : 0;
			pType += form.fields.categoryId.val() ? form.getPoint(form.fields.categoryId) : 0;
			pType += form.fields.projectBuildingId.val() ? form.getPoint(form.fields.projectBuildingId) : 0;

			if(pType) {
				p += point.checkPoint('type', pType);
			} else {
				point.uncheckPoint('type');
			}
			
			var addressPoint = 0;
			
			addressPoint += form.fields.cityId.val() ? form.getPoint(form.fields.cityId) : 0;
			addressPoint += form.fields.districtId.val() ? form.getPoint(form.fields.districtId) : 0;
			addressPoint += form.fields.wardId.val() ? form.getPoint(form.fields.wardId) : 0;
			addressPoint += form.fields.streetId.val() ? form.getPoint(form.fields.streetId) : 0;
			addressPoint += form.fields.homeNo.val() ? form.getPoint(form.fields.homeNo) : 0;
			
			if(addressPoint) {
				p += point.checkPoint('address', addressPoint);
			} else {
				point.uncheckPoint('address');
			}
			
			
			var infoP = 0;
			
			infoP += form.fields.area.val() ? form.getPoint(form.fields.area) : 0;
			infoP += form.fields.priceMask.val() ? form.getPoint(form.fields.priceMask) : 0;
			infoP += form.fields.roomNo.val() ? form.getPoint(form.fields.roomNo) : 0;
			infoP += form.fields.toiletNo.val() ? form.getPoint(form.fields.toiletNo) : 0;
			
			var words = form.fields.content.val().trim().split(/\s+/);
			
			if(words.length >= 30) {
				infoP += 15;
			} else if(words.length >= 20) {
				infoP += 10;
			} else if(words.length >= 10) {
				infoP += 5;
			}
			
			if(infoP) {
				p += point.checkPoint('info', infoP);
			} else {
				point.uncheckPoint('info');
			}
			
			var additionP = 0;
			
			additionP += (form.fields.floorNo.val()) ? form.getPoint(form.fields.floorNo) : 0;
			additionP += (form.fields.facadeWidth.val()) ? form.getPoint(form.fields.facadeWidth) : 0;
			additionP += (form.fields.landWidth.val()) ? form.getPoint(form.fields.landWidth) : 0;
			additionP += (form.fields.homeDirection.val()) ? form.getPoint(form.fields.homeDirection) : 0;
			additionP += (form.fields.facadeDirection.val()) ? form.getPoint(form.fields.facadeDirection) : 0;
			
			var facilityP = $('.tienich-frm input').filter(':checked').length;
			
			additionP += (facilityP > 5) ? 5 : facilityP;
			
			if(additionP) {
				p += point.checkPoint('additionInfo', additionP);
			} else {
				point.uncheckPoint('additionInfo');
			}
			
			var totalImage = form.files.children().length;
			var imageP = 0;
			
			if(totalImage > 2) {
				imageP += 10;
			} else if(totalImage > 0) {
				imageP += 5;
			}
			
			if(imageP > 0) {
				p += point.checkPoint('photo', imageP);
			} else {
				point.uncheckPoint('photo');
			}
			
			var contactP = 5;
			
			contactP += (form.fields.name.val()) ? form.getPoint(form.fields.name) : 0;
			contactP += (form.fields.email.val()) ? form.getPoint(form.fields.email) : 0;
			contactP += (form.fields.mobile.val()) ? form.getPoint(form.fields.mobile) : 0;
			
			p += point.checkPoint('contact', contactP);
				
			point.update(p);
		}
	};
	
	form = {
		el: $('#w0'),
		fields: {},
		init: function() {
			form.el.find('select, input, textarea').each(function(){
				var self = $(this);
				
				if(self.attr('id')) {
					form.fields[camel(self.attr('id'))] = self;
				}
			});
			

			form.fields.categoryId.on('change', function(){
				var self = $(this);
				var val = self.val();
				
				if(val == CHCK) {
					form.getWrap(self).find('.point').text(2);
					form.getWrap(form.fields.projectBuildingId).find('.point').text(3);
				} else {
					form.getWrap(self).find('.point').text(5);
					form.getWrap(form.fields.projectBuildingId).find('.point').text(0);
				}
				
				if(excludeFields[val]) {
					for(var i in excludeFields[val]) {
						form.fields[excludeFields[val][i]].val('');
					}
				}
			});
			
			for(var i in form.fields) {
				form.fields[i].on('change', function(){
					form.hideError($(this));
					point.calc();
				});
				form.fields[i].on('keyup', function(){
					point.calc();
				});
			}
			
			$('.tienich-frm input').change(function(){
				point.calc();
			});
			
			form.files = $(".files" );
			form.projectWrap = $('.project-wrap');
			form.attachEvents();
			
			form.oldCat = form.fields.categoryId.val();
		},
		resetLatLng: function(e) {
			if(!form.projectWrap.hasClass('has-project')) {
				form.fields.lat.val('');
				form.fields.lng.val('');
			}
		},
		attachEvents: function() {
			form.files.sortable({cancel: '.template-upload'});
			form.files.disableSelection();
			
			form.fields.streetId.on('change', form.resetLatLng);
			form.fields.homeNo.on('change', form.resetLatLng);
			form.fields.wardId.on('change', form.resetLatLng);
			
			form.fields.type.on('change', form.filterCategories);
			
			form.fields.cityId.on('change', function(e, eventData){
				if(eventData) {
					form.cityChange(function(r){
						form.select(form.fields.districtId, eventData.district_id, eventData);
					});
				} else {
					form.cityChange();
				}
			});
			
			form.fields.districtId.on('change', function(e, eventData){
				if(eventData) {
					form.districtChange(function(r){
						if(eventData.ward_id) {
							form.select(form.fields.wardId, eventData.ward_id);
							form.fields.wardId.prop('disabled', true);
						}
						
						if(eventData.street_id) {
							form.select(form.fields.streetId, eventData.street_id);
							form.fields.streetId.prop('disabled', true);
						}
						
						if(eventData.home_no) {
							form.fields.homeNo.val(eventData.home_no).prop('disabled', true);
						}
					});
				} else {
					form.districtChange();
				}
			});
			
			form.fields.categoryId.on('change', function(){
				var val = $(this).val();
				
				if(val == CHCK) {
					form.getWrap(form.fields.projectBuildingId).fadeIn();
				} else {
					form.getWrap(form.fields.projectBuildingId).fadeOut(function(){
						form.removeProject();
					});
				}
				
				if(typeof excludeFields[form.oldCat] !== 'undefined') {
					for(var i = 0; i < excludeFields[form.oldCat].length; i++) {
						form.getWrap(form.fields[excludeFields[form.oldCat][i]]).fadeIn();
					}
				}
				
				if(typeof excludeFields[val] !== 'undefined') {
					for(var i = 0; i < excludeFields[val].length; i++) {
						form.getWrap(form.fields[excludeFields[val][i]]).fadeOut().val('');
					}
				}

				form.oldCat = val;
			});
			
			var ss = $('#search-list');
			
			form.fields.projectMask.on('keyup', function(){
				var self = $(this);
				var val = self.val().trim();
				
		    	if(val.length > 1) {
		    		if(self.data('v') != val) {
		    			self.data('v', val);
		            	
		    			if($.data(this, 'ajax')) {
		    				$.data(this, 'ajax').abort();
		    			}
		    			
		    			$.data(this, 'ajax', $.get('/listing/list-project', {v: val}, function(response){
		            		if(response.length) {
		            			showSearchProject();
		            			
		            			var list = ss.find('ul').html('');
		            			
		            			for(var i in response) {
		            				list.append('<li data-id="' + response[i].id + '">' + response[i].full_name + '</li>');
		                      	}
		            		} else {
		            			ss.addClass('hide');
		            		}
		            	}));
		            }
		    	} else {
		    		form.fields.projectMask.data('v', '');
		    		ss.addClass('hide');
		    	}
			}).on('blur', function(){
				form.fields.projectMask.data('v', '').val('');
			});
			
			function showSearchProject(items) {
				ss.removeClass('hide');
				$(document).on('click', hideSearchProject);
			}
			
			function hideSearchProject(e) {
				if($(e.target).attr('id') != 'projectMask') {
		    		ss.addClass('hide');
					$(document).off('click', hideSearchProject);
				}
			}
			
			var projectValue = $('#project-value');
			
			ss.on('click', 'li', function(){
				var self = $(this);
				var val = self.data('id');
				
				form.fields.projectBuildingId.val(self.data('id'));
				form.projectWrap.addClass('has-project');
				projectValue.find('.name').text(self.text());
				
				$.get('/building-project/detail', {id: val}, function(r){
					projectValue.attr('href', r.url);
					form.select(form.fields.cityId, r.city_id, r);
					form.fields.cityId.prop("disabled", true);
					form.fields.districtId.prop("disabled", true);
					
					form.fields.lat.val(r.lat);
					form.fields.lng.val(r.lng);
					
					if(r.facilities) {
						var fa = r.facilities.split(',');

						$('.tienich-frm input').each(function(){
							var self = $(this);
							if($.inArray(self.val(), fa) != -1) {
								self.parent().addClass('active');
							}
						});
					}
				});
			});
			
			projectValue.find('.icon-mv').on('click', function(e){
				form.removeProject();
				
				e.preventDefault();
			});
			
			
			$('.radio-ui').radio({
				done: function (item) {
					if(item.attr('name') == 'price-unit') {
						var unit = Number(item.val());
						var mask = Number(form.fields.priceMask.val().replace(',', '.'));

						calPrice(unit, mask);
					}
				}
			});
			
			form.fields.priceMask.on('keyup', function(e){
				var self = $(this);
				
				var unit = Number(form.el.find('input[name=price-unit]').filter(':checked').val());
				var mask = self.val() ? Number(self.val().replace(',', '.')) : '';
				
				calPrice(unit, mask);
			});
			
			function calPrice(unit, mask) {
				var priceShow = $('#price-show');
				
				if(mask) {
					var price = Math.round(mask * unit);
					
					form.fields.price.val(price);
					
					var priceFormat = formatNumber(price + '');
					
					
					if(priceFormat) {
						priceShow.text(priceFormat).parent().show();
					} else {
						priceShow.parent().hide();
					}
				} else {
					form.fields.price.val('');
					priceShow.parent().hide();
				}
			}
			
			$('#preview').click(function(){
				if(form.validate()) {
					$('.error-hint').fadeOut();
					
					if(!form.fields.lat.val()) {
						form.geoLocation(form.buildAddress());
					}
					
					form.showPreview();
				} else {
					$('.error-hint').fadeIn();
				}
			});
			
			$('#review-listing').click(function(e){
				if($(e.target).attr('id') == 'review-listing') {
					form.hidePreview();
				}
			});
			
			$('#post').click(function(){
				var disabled = form.el.find(':input:disabled').prop('disabled', false);
				
				$('body').loading();
				
				$.post(form.el.attr('action'), form.el.serialize(), function(r){
					$('body').loading({done: true});
					
					if(r.success) {
						$('.wrap-frm-listing').hide();
						$('#success-notify').removeClass('hide').html(r.template);
						
						// $('#to-detail').attr('href', r.url);
						
						window.onbeforeunload = function() {};
					} else {
						
					}
				});
				
				disabled.prop('disabled', true);
			});
		},
		showPreview: function() {
			$('html, body').addClass('previewing');
			
			var previewEls = form.getPreviewEls();
			
			previewEls.swiperWrapper.html('');
			
			if(tempSlide) {
				previewEls.galleryDetail.show();
				previewEls.noGallery.hide();
				
				for(var i in tempSlide) {
					previewEls.swiperWrapper.append('<div class="swiper-slide"> <div class="img-show"> <div> <img src="' + tempSlide[i] + '"> </div> </div> </div>');
				}
				
				tempSlide = false;
			} else {
				var galleries = form.files.find('.template-download');
				
				if(galleries.length > 0) {
					previewEls.galleryDetail.show();
					previewEls.noGallery.hide();
					
					galleries.each(function(){
						previewEls.swiperWrapper.append('<div class="swiper-slide"> <div class="img-show"> <div> <img src="' + $(this).find('a').attr('href') + '"> </div> </div> </div>');
					});
				} else {
					previewEls.galleryDetail.hide();
					previewEls.noGallery.show();
				}
			}
			
			if(previewEls.swiperWrapper.html() != '') {
				form.swiper = new Swiper('.swiper-container', {
			        nextButton: '.swiper-button-next',
	    			prevButton: '.swiper-button-prev'
			    });
			}
			
			form.updateRef($('#review-listing'));
			
			$('.list-attr-td, .list-tienich-detail').find('.ref').each(function(){
				var self = $(this);
				var li = self.closest('li');
				
				if(self.text()) {
					li.show();
				} else {
					li.hide();
				}
			});
			
			$('.item-agent .ref').each(function(){
				var self = $(this);
				var item = self.closest('.item-agent');
				
				if(self.text()) {
					item.show();
				} else {
					item.hide();
				}
			});
			
			$('.address-show').text(form.buildAddress());
		},
		updateRef: function(container) {
			container.find('.ref').each(function(){
				var self = $(this);
				var refEl = $(self.data('ref'));
				var val;
				
				if(refEl.is('select')) {
					var selected = refEl.children().filter(':selected');
					
					if(selected.attr('value')) {
						val = refEl.children().filter(':selected').text();
					}
				} else if(refEl.is(':radio')) {
					var checkedEl = refEl.filter(':checked');
					val = checkedEl.closest('.radio-ui').text();
				} else {
					val = refEl.val();
				}
				
				if(self.data('format')) {
					val = window[self.data('format')](val);
				}
				
				if(self.data('html')) {
					self.html(val);
				} else {
					self.text(val);
				}
			});
		},
		hidePreview: function() {
			$('html, body').removeClass('previewing');
			
			if(form.swiper) {
				form.swiper.destroy(false, true);
				form.swiper = null;
			}
		},
		getPreviewEls: function() {
			return {
				galleryDetail: $('.gallery-detail'),
				noGallery: $('.no-gallery'),
				swiperWrapper: $('.swiper-wrapper')
			};
		},
		geoLocation: function(address) {
			if(address) {
				var geoCode = new google.maps.Geocoder;
				geoCode.geocode({address: address}, function(results) {
					if(results.length) {
						form.fields.lat.val(results[0].geometry.location.lat());
						form.fields.lng.val(results[0].geometry.location.lng());
					} else {
						form.geoLocation(form.splitAddress(address));
					}
				});
			}
		},
		splitAddress: function(address) {
			address = address.split(', ');
			address.shift();
			return address.join(', ');
		},
		buildAddress: function() {
			var address = [];
			
			if(form.fields.homeNo.val() && form.fields.showHomeNo.prop('checked')) {
				address.push(form.fields.homeNo.val());
			}

			
			var street = form.fields.streetId.find('option').filter(':selected');
			address.push(street.data('pre') + ' ' + street.text());
			
			var ward = form.fields.wardId.find('option').filter(':selected');
			address.push(ward.data('pre') + ' ' + ward.text());
			
			address.push(form.fields.districtId.find('option').filter(':selected').text());
			address.push(form.fields.cityId.find('option').filter(':selected').text());
			
			return address.join(', ');
		},
		removeProject: function() {

			form.projectWrap.removeClass('has-project');
			form.fields.projectBuildingId.val('');
			
			form.fields.cityId.prop("disabled", false);
			form.fields.districtId.prop("disabled", false);
			form.fields.wardId.prop("disabled", false);
			form.fields.streetId.prop("disabled", false);
			form.fields.homeNo.prop("disabled", false);
			
			point.calc();
		},
		filterCategories: function() {
			var type = form.fields.type.val();
			var selectedValue = form.getSelectedCatEl() ? form.getSelectedCatEl().attr('value') : '';
			var flag = true;
			
			form.fields.categoryId.find("option:not(:first-child)").remove();
			for(var i = 0; i < categories.length; i++) {
				if(categories[i]['apply_to_type'] == type || categories[i]['apply_to_type'] == APPLY_TO_TYPE_BOTH) {
					var option = $('<option value="' + categories[i]['id'] + '" data-limit="' + categories[i]['limit_area'] + '">' + categories[i]['name'] + '</option>');
					
					if(selectedValue == categories[i]['id']) {
						option.prop('selected', true);
						flag = false;
					}

					form.fields.categoryId.append(option);
				}
			}
			
			if(flag && selectedValue != '') {
				form.fields.categoryId.select2("val");
			}
		},
		getSelectedCatEl: function() {
			var el = null;

			form.fields.categoryId.find("option:not(:first-child)").each(function(){
				var self = $(this);
				
				if(self.attr('value') == form.fields.categoryId.val()) {
					el = self;
					
					return false;
				}
			});
			return el;
		},
		getWrap: function(el) {
			return el.closest('.form-group');
		},
		cityChange: function(fn) {
			if(form.fields.cityId.val()) {
				form.getWrap(form.fields.districtId).fadeIn();
			} else {
				form.getWrap(form.fields.districtId).fadeOut();
			}
			
			$.get('/listing/list-district', {cityId: form.fields.cityId.val()}, function(districts){
				form.appendDropdown(form.fields.districtId, districts);

				if(fn) {
					fn(districts);
				}
			});
		},
		districtChange: function(fn) {
			form.appendDropdown(form.fields.wardId, []);
			form.appendDropdown(form.fields.streetId, []);
			
			if(form.fields.districtId.val()) {
				form.getWrap(form.fields.wardId).fadeIn();
				form.getWrap(form.fields.streetId).fadeIn();
				
				if(typeof excludeFields[form.fields.categoryId.val()] === 'undefined') {
					form.getWrap(form.fields.homeNo).fadeIn();
				}
			} else {
				form.getWrap(form.fields.wardId).fadeOut();
				form.getWrap(form.fields.streetId).fadeOut();
				form.getWrap(form.fields.homeNo).fadeOut();
			}
			
			
			
			if(form.fields.districtId.val()) {
				$.get('/listing/list-sw', {districtId: form.fields.districtId.val()}, function(response){
					form.appendDropdown(form.fields.wardId, response.wards);
					form.appendDropdown(form.fields.streetId, response.streets);

					if(fn) {
						fn(response);
					}
				});
			}
		},
		select: function(el, val, params) {
			el.val(val).trigger('change', params);
		},
		appendDropdown: function(el, items) {
			el.find("option:not(:first-child)").remove();
			for(var i in items) {
				if(items[i]['pre']) {
					el.append('<option data-pre="' + items[i]['pre'] + '" value="' + items[i]['id'] + '">' + items[i]['name'] + '</option>');
				} else {
					el.append('<option value="' + items[i]['id'] + '">' + items[i]['name'] + '</option>');
				}
			}
			
			el.select2('val');
		},
		fileuploadcompleted: function(e, d, t) {
			if(form.files.find('.template-download').length > 1) {
				$('#upload-hint').fadeIn();
			}
			
			form.fileuploadcommon();
		},
		fileuploaddestroyed: function(e, d, t) {
			if(form.files.find('.template-download').length < 2) {
				$('#upload-hint').fadeOut();
			}
			
			form.fileuploadcommon();
		},
		fileuploadcommon: function() {
			form.files.sortable('refreshPositions');
			point.calc();
		},
		validate: function() {
			form.require(form.fields.categoryId, lajax.t('Choose property types'));
			form.require(form.fields.cityId, lajax.t('Choose city'));
			
			if(!form.isHidden(form.fields.districtId)) {
				form.require(form.fields.districtId, lajax.t('Choose district'));
			}
			
			if(!form.isHidden(form.fields.wardId)) {
				form.require(form.fields.wardId, lajax.t('Choose ward'));
			}
			
			if(!form.isHidden(form.fields.streetId)) {
				form.require(form.fields.streetId, lajax.t('Choose street'));
			}
			
			form.require(form.fields.area, lajax.t('Enter home size'));
			form.require(form.fields.price, lajax.t('Enter price'));
			form.require(form.fields.content, lajax.t('Enter content'));
			form.require(form.fields.mobile, lajax.t('Enter mobile number'));
			
			if(!form.hasError(form.fields.area)) {
				var val = form.fields.area.val();
				
				if(!form.isNumber(val) || val < 0) {
					form.showError(form.fields.area, lajax.t('Home size is invalid'));
				} else {
					if(form.getSelectedCatEl()) {
						var limit = form.getSelectedCatEl().data('limit');
						
						if(limit && Number(limit) < Number(val)) {
							form.showError(form.fields.area, lajax.t('Home size must be not greater %s').replace('%s', limit));
						}
					}
				}
			}
			
			if(!form.hasError(form.fields.price)) {
				if(!form.isNumber(form.fields.price.val())) {
					form.showError(form.fields.price, lajax.t('Price is invalid'));
				}
			}
			
			if(!form.hasError(form.fields.mobile)) {
				var val = form.fields.mobile.val();
				
				if(!form.isDigit(val)) {
					form.showError(form.fields.mobile, lajax.t('Mobile number is invalid'));
				} else if(val.length < 7 || val.length > 11) {
					form.showError(form.fields.mobile, lajax.t('Số di động phải từ 7 đến 11 số.'));
				}
			}
			
			if(form.fields.email.val() && !form.validateEmail(form.fields.email.val())) {
				form.showError(form.fields.email, lajax.t('Địa chỉ email không hợp lệ'));
			} else {
				form.hideError(form.fields.email);
			}
			
			if(form.el.find('.has-error').length > 0) {
				return false;
			}
			
			return true;
		},
		require: function(el, m) {
			if(el.val()) {
				form.hideError(el);
			} else {
				form.showError(el, m);
			}
		},
		showError: function(el, m) {
			form.getWrap(el).addClass('has-error').find('.help-block').text(m);
		},
		hideError: function(el) {
			form.getWrap(el).removeClass('has-error').find('.help-block').text('');
		},
		hasError: function(el) {
			return form.getWrap(el).hasClass('has-error');
		},
		isHidden: function(el) {
			return (form.getWrap(el).css('display') == 'none');
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
		validateEmail: function (email) { 
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		},
		getPoint: function(el) {
			return Number(form.getWrap(el).find('.point').text());
		}
	};
	
	point.init();
	form.init();
	
	setTimeout(function(){
		point.calc();
	}, 1000);
	
	form.el.find('select').each(function(){
		var self = $(this);
		var options = {
			width: '100%',
			placeholder: '...',
			language: {
				'noResults': function() {
					return self.data('no-results');
				}
			}
		};
		
		if(!self.hasClass('search')) {
			options.minimumResultsForSearch = Infinity;
		}
		
		self.select2(options);
	});
	
	form.el.find('.number-only').on('keydown', numnberOnly);
	
	var w = $(window);
	var cl = $('.checkpoint-listing');
	var offtop = $('.wrap-frm-listing').offset().top - ($('header').outerHeight()) + parseInt($('.wrap-frm-listing').css('padding-top')) - 12;

	if(Number(cl.css('margin-top').replace('px', '')) == 0) {
		w.on('scroll', wScroll).trigger('scroll');
	}
	
	function wScroll() {
		var st = w.scrollTop();
		if(st >= offtop) {
			cl.css('top', st - offtop);
		} else {
			cl.css('top', 0);
		}
	}
	
	w.on('resize', function(){
		offtop = $('.wrap-frm-listing').offset().top - ($('header').outerHeight()) + parseInt($('.wrap-frm-listing').css('padding-top')) - 12;
		
		if(Number(cl.css('margin-top').replace('px', '')) == 0) {
			w.on('scroll', wScroll);
		} else {
			cl.removeAttr('style');
			w.off('scroll', wScroll);
		}
	});
	
	if(isNewRecord) {
		form.data = form.el.serialize();
	} else {
		function serialize(e, data) {
			form.data = form.el.serialize();
			jQuery('#w1-fileupload').off('fileuploadcompleted', serialize);
		}
		
		jQuery('#w1-fileupload').on('fileuploadcompleted', serialize);
		
		var maskValue = Number(form.fields.price.val()) / Number(form.fields.priceUnitMil.val());
		
		if(maskValue > 999) {
			form.fields.priceMask.val((Number(form.fields.price.val()) / Number(form.fields.priceUnitBil.val()) + '').replace('.', ','));
			form.fields.priceUnitBil.parent().trigger('click');
		} else {
			form.fields.priceMask.val(maskValue);
			form.fields.priceUnitMil.parent().trigger('click');
		}
		
		// form.showPreview();
	}
	
	$('#back').click(function(){
		form.hidePreview();
	});
	
	window.onbeforeunload = function() {
		if(form.data != form.el.serialize()) {
			return lajax.t("Are you sure you want to navigate away from this page and discard all changes ?");
		}
	};
	
	$('.icon-close-icon').click(function(e){
		e.preventDefault();
		
		$(this).fadeOut().closest('.alert-success').slideUp();
		
		setCookie('cpl', '1');
	});
});

function camel(str) {
	return str.replace(/adproduct-|adproductadditioninfo-|adcontactinfo-/, '').replace(/_([a-z])|-([a-z])/g, function (g) { return g[1].toUpperCase(); });
}

function numnberOnly(e) {
	if($(this).hasClass('number-float')) {
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
}

function formatContent(content) {
	return content.replace(/\n/g, "<br />");
}