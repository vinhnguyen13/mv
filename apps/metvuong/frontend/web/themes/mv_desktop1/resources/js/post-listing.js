var events = { mobileEvents: [], desktopEvents: [], attachMobileEvent: function() { events._attachEvent.apply(events.mobileEvents, arguments); }, attachDesktopEvent: function() { events._attachEvent.apply(events.desktopEvents, arguments); }, _attachEvent: function() { events.switchEvent('on', arguments); this.push(arguments); }, detachMobileEvents: function() { events._detachEvents(events.mobileEvents); events._attachEvents(events.desktopEvents); }, detachDesktopEvents: function() { events._detachEvents(events.desktopEvents); events._attachEvents(events.mobileEvents); }, _detachEvents: function(eventsCollection) { for(var i in eventsCollection) { events.switchEvent('off', eventsCollection[i]); } }, _attachEvents: function(eventsCollection) { for(var i in eventsCollection) { events.switchEvent('on', eventsCollection[i]); } }, switchEvent: function(status, args) { var argsArray = []; for(var i in args) { argsArray.push(args[i]); } var el = argsArray.splice(0, 1); el = el[0]; el[status].apply(el, argsArray); } };
var desktop = { checkToEnable: function() { if(desktop.isDesktop() && !desktop.isEnabled) { desktop.enable(); } else if(!desktop.isDesktop() && desktop.isEnabled) { desktop.disable(); } }, isDesktop: function() { return $('.m-header').css('display') == 'none'; }, enable: function() { desktop.isEnabled = true; events.detachMobileEvents(); if(!desktop.isLoadedResources) { desktop.isLoadedResources = true; desktop.loadResources(); } }, disable: function() { desktop.isEnabled = false; events.detachDesktopEvents(); } };
var $window = $(window);
var form;

$(document).ready(function(){
	var CountUp=function(c,g,l,n,h,d){for(var p=0,k=["webkit","moz","ms","o"],f=0;f<k.length&&!window.requestAnimationFrame;++f)window.requestAnimationFrame=window[k[f]+"RequestAnimationFrame"],window.cancelAnimationFrame=window[k[f]+"CancelAnimationFrame"]||window[k[f]+"CancelRequestAnimationFrame"];window.requestAnimationFrame||(window.requestAnimationFrame=function(a,q){var e=(new Date).getTime(),c=Math.max(0,16-(e-p)),d=window.setTimeout(function(){a(e+c)},c);p=e+c;return d});window.cancelAnimationFrame|| (window.cancelAnimationFrame=function(a){clearTimeout(a)});var a=this;a.options={useEasing:!0,useGrouping:!0,separator:"",decimal:".",easingFn:null,formattingFn:null};for(var m in d)d.hasOwnProperty(m)&&(a.options[m]=d[m]);""===a.options.separator&&(a.options.useGrouping=!1);a.options.prefix||(a.options.prefix="");a.options.suffix||(a.options.suffix="");a.d="string"===typeof c?document.getElementById(c):c;a.startVal=Number(g);a.endVal=Number(l);a.countDown=a.startVal>a.endVal;a.frameVal=a.startVal; a.decimals=Math.max(0,n||0);a.dec=Math.pow(10,a.decimals);a.duration=1E3*Number(h)||2E3;a.firedCallback=!1;a.formatNumber=function(b){b=b.toFixed(a.decimals);var c,e;c=(b+"").split(".");b=c[0];c=1<c.length?a.options.decimal+c[1]:"";e=/(\d+)(\d{3})/;if(a.options.useGrouping)for(;e.test(b);)b=b.replace(e,"$1"+a.options.separator+"$2");return a.options.prefix+b+c+a.options.suffix};a.easeOutExpo=function(a,c,e,d){return e*(-Math.pow(2,-10*a/d)+1)*1024/1023+c};a.easingFn=a.options.easingFn?a.options.easingFn: a.easeOutExpo;a.formattingFn=a.options.formattingFn?a.options.formattingFn:a.formatNumber;a.version=function(){return"1.7.1"};a.printValue=function(b){b=a.formattingFn(b);"INPUT"===a.d.tagName?this.d.value=b:"text"===a.d.tagName||"tspan"===a.d.tagName?this.d.textContent=b:this.d.innerHTML=b};a.count=function(b){a.startTime||(a.startTime=b);a.timestamp=b;b-=a.startTime;a.remaining=a.duration-b;a.frameVal=a.options.useEasing?a.countDown?a.startVal-a.easingFn(b,0,a.startVal-a.endVal,a.duration):a.easingFn(b, a.startVal,a.endVal-a.startVal,a.duration):a.countDown?a.startVal-b/a.duration*(a.startVal-a.endVal):a.startVal+b/a.duration*(a.endVal-a.startVal);a.frameVal=a.countDown?a.frameVal<a.endVal?a.endVal:a.frameVal:a.frameVal>a.endVal?a.endVal:a.frameVal;a.frameVal=Math.round(a.frameVal*a.dec)/a.dec;a.printValue(a.frameVal);b<a.duration&&(a.rAF=requestAnimationFrame(a.count),a.firedCallback||a.endVal!=a.frameVal||(a.callback&&a.callback(),a.firedCallback=!0))};a.start=function(b){a.callback=b;a.rAF=requestAnimationFrame(a.count); return!1};a.pauseResume=function(){a.paused?(a.paused=!1,delete a.startTime,a.duration=a.remaining,a.startVal=a.frameVal,requestAnimationFrame(a.count)):(a.paused=!0,cancelAnimationFrame(a.rAF))};a.reset=function(){a.paused=!1;delete a.startTime;a.startVal=g;cancelAnimationFrame(a.rAF);a.printValue(a.startVal)};a.update=function(b){cancelAnimationFrame(a.rAF);a.paused=!1;delete a.startTime;a.startVal=a.frameVal;a.endVal=Number(b);a.countDown=a.startVal>a.endVal;a.rAF=requestAnimationFrame(a.count)}; a.printValue(a.startVal)};(function(c){c.fn.countup=function(g,l){this.each(function(n,h){var d=Number(c(h).text());(new CountUp(h,d,g)).start(l)});return this}})(jQuery);

	var adw = $('#address-detail-wrap');
	var projectMask = $('#projectMask');
	var searchList = $('#search-list');
	var projectWrap = $('#project-wrap');
	var priceShow = $('#price-show');
	var priceMask = $('#priceMask');
	var errorHint = $('.error-hint');
	
	$('.alert-success .icon-close-icon').on('click', hideHint);
	
	var point = {
		fields: {},
		pel: $('#score-percent'),
		score: $('#score'),
		init: function() {
			$('.point-select').find('li').each(function(){
				var self = $(this);
				var id = self.data('id');
				
				point.fields[id] = self;
				point.fields[id + 'Icon'] = self.find('.icon-checked');
			});
		},
		update: function(n) {
			point.score.countup(n);
			point.pel.css('width', n + '%');
		},
		checkPoint: function(field, p) {
			var icon = point.fields[field + 'Icon'];
			var count = point.fields[field].find('.count');
			var parent = count.parent();
			
			if(p > 0) {
				icon.addClass('selected-point');
				
				parent.fadeIn(50, function(){
					count.countup(p);
				});
			} else {
				icon.removeClass('selected-point');
				
				count.countup(p, function(){
					parent.fadeOut();
				});
			}
		},
		calc: function() {
			var p = 0;

			var pType = 0;

			pType += form.fields.type.val() ? form.getPoint(form.fields.type) : 0;
			pType += form.fields.categoryId.val() ? form.getPoint(form.fields.categoryId) : 0;
			pType += form.fields.projectBuildingId.val() ? form.getPoint(form.fields.projectBuildingId) : 0;
			
			point.checkPoint('type', pType);
			p += pType;
			
			
			var addressPoint = 0;
			
			addressPoint += form.fields.cityId.val() ? form.getPoint(form.fields.cityId) : 0;
			addressPoint += form.fields.districtId.val() ? form.getPoint(form.fields.districtId) : 0;
			addressPoint += form.fields.wardId.val() ? form.getPoint(form.fields.wardId) : 0;
			addressPoint += form.fields.streetId.val() ? form.getPoint(form.fields.streetId) : 0;
			addressPoint += form.fields.homeNo.val() ? form.getPoint(form.fields.homeNo) : 0;
			
			point.checkPoint('address', addressPoint);
			p += addressPoint;
			
			
			var infoP = 0;
			
			infoP += form.fields.area.val() ? form.getPoint(form.fields.area) : 0;
			infoP += priceMask.val() ? form.getPoint(priceMask) : 0;
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
			
			point.checkPoint('info', infoP);
			p += infoP;
			
			var additionP = 0;
			
			additionP += (form.fields.floorNo.val()) ? form.getPoint(form.fields.floorNo) : 0;
			additionP += (form.fields.facadeWidth.val()) ? form.getPoint(form.fields.facadeWidth) : 0;
			additionP += (form.fields.landWidth.val()) ? form.getPoint(form.fields.landWidth) : 0;
			additionP += (form.fields.homeDirection.val()) ? form.getPoint(form.fields.homeDirection) : 0;
			additionP += (form.fields.facadeDirection.val()) ? form.getPoint(form.fields.facadeDirection) : 0;
			
			var facilityP = $('.tienich-frm input').filter(':checked').length;
			
			additionP += (facilityP > 5) ? 5 : facilityP;
			
			point.checkPoint('additionInfo', additionP);
			p += additionP;
			
			var totalImage = form.files.children().length;
			var imageP = 0;
			
			if(totalImage > 2) {
				imageP += 10;
			} else if(totalImage > 0) {
				imageP += 5;
			}
			
			point.checkPoint('photo', imageP);
			p += imageP;
			
			var contactP = 5;
			
			contactP += (form.fields.name.val()) ? form.getPoint(form.fields.name) : 0;
			contactP += (form.fields.email.val()) ? form.getPoint(form.fields.email) : 0;
			contactP += (form.fields.mobile.val()) ? form.getPoint(form.fields.mobile) : 0;

			point.checkPoint('contact', contactP);
			p += contactP;
			
			point.update(p);
		}
	};
	
	form = new function() {
		var self = this;

		self.el = $('#w0');
		self.input = self.el.find('select, input, textarea');
		self.files = $(".files" );
		self.fields = mapFieldsName(self.input);
		
		select2(self.el.find('select'));
		
		self.filterCategories = function() {
			var type = form.fields.type.val();
			var currentCat = self.fields.categoryId.val();
			var options = [];
			
			for(var i = 0; i < categories.length; i++) {
				if(categories[i]['apply_to_type'] == type || categories[i]['apply_to_type'] == APPLY_TO_TYPE_BOTH) {
					options.push({
						id: categories[i]['id'],
						name: categories[i]['name'],
						data: {
							limit: categories[i]['limit_area']
						}
					});
				}
			}

			select2Change(self.fields.categoryId, options);
			select2Select(self.fields.categoryId, currentCat, !hasValue(self.fields.categoryId, currentCat));
		};
		
		self.catChange = function() {
			var categoryId = Number(self.fields.categoryId.val());
			
			if(landType.indexOf(categoryId) === -1) {
				for(var i in landTypeApplyFields) {
					show(self.fields[camel(landTypeApplyFields[i])]);
				}
			} else {
				for(var i in landTypeApplyFields) {
					hide(self.fields[camel(landTypeApplyFields[i])], true);
				}
			}
			
			if(categoryId == CHCK) {
				parent(self.fields.categoryId).find('.point').text(2);
				show(form.fields.projectBuildingId);
			} else {
				parent(self.fields.categoryId).find('.point').text(5);
				hide(form.fields.projectBuildingId);
				
				removeProject();
			}
		};
		
		self.cityChange = function() {
			var cityId = self.fields.cityId.val();

			select2Change(self.fields.districtId, []);
			select2Select(self.fields.districtId, '', true);
			
			if(cityId) {
				$.get('/listing/list-district', {cityId: self.fields.cityId.val()}, function(districts){
					select2Change(self.fields.districtId, districts);
				});
				show(self.fields.districtId);
			} else {
				hide(self.fields.districtId, true, true);
			}
		};
		
		self.districtChange = function() {
			select2Change(self.fields.wardId, []);
			select2Change(self.fields.streetId, []);
			
			var districtId = self.fields.districtId.val();
			
			if(districtId) {
				$.get('/listing/list-sw', {districtId: districtId}, function(response){
					select2Change(self.fields.wardId, response.wards);
					select2Change(self.fields.streetId, response.streets);
				});
				adw.show();
			} else {
				adw.hide();
			}
		};
		
		self.fileuploadcompleted = function() {
			if(form.files.find('.template-download').length > 1) {
				$('#upload-hint').fadeIn();
			}
			
			self.fileuploadcommon();
		};
		
		self.fileuploaddestroyed = function() {
			if(form.files.find('.template-download').length < 2) {
				$('#upload-hint').fadeOut();
			}
			
			form.fileuploadcommon();
		};

		self.fileuploadcommon = function() {
			form.files.sortable('refreshPositions');
			
			point.calc();
		};

		self.require = function(el, m) {
			if(el.val()) {
				self.hideError(el);
			} else {
				self.showError(el, m);
			}
		};
		
		self.showError = function(el, m) {
			parent(el).addClass('has-error').find('.help-block').text(m);
		};
		
		self.hideError = function(el) {
			parent(el).removeClass('has-error').find('.help-block').text('');
		};

		self._hideError = function() {
			self.hideError($(this));
			
			self.buildErrorHint();
			
			point.calc();
		};
		
		self.buildErrorHint = function() {
			var errorFields = self.getFormErrors();
			
			if(errorFields.length) {
				$('#error-fields').text(errorFields.join(', ') + '.');
			} else {
				errorHint.hide();
			}
		};
		
		self.hasError = function(el) {
			return parent(el).hasClass('has-error');
		};
		
		self.isHidden = function(el) {
			return (parent(el).css('display') == 'none');
		}
		
		self.getSelectedCatEl = function() {
			return self.fields.categoryId.find("option:selected");
		};
		
		self.isNumber = function(number) {
			var format = form.formatNumber(number);

			return !isNaN(format);
		};

		self.formatNumber = function(number) {
			return number.replace('.', 'A').replace(',', '.');
		};
		
		self.isDigit = function(digit) {
			return /^\d+$/.test(digit);
		};
		
		self.validateEmail = function (email) { 
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		};
		
		self.getFormErrors = function() {
			var errorFields = [];
			
			form.el.find('.has-error').each(function(){
				errorFields.push($(this).find('.label-attr').text());
			});
			
			return errorFields;
		};
		
		self.validate = function() {
			self.require(self.fields.categoryId, lajax.t('Choose property types'));
			self.require(self.fields.cityId, lajax.t('Choose city'));
			
			if(!self.isHidden(self.fields.districtId)) {
				self.require(self.fields.districtId, lajax.t('Choose district'));
			}
			
			if(adw.css('display') != 'none') {
				self.require(self.fields.wardId, lajax.t('Choose ward'));
				self.require(self.fields.streetId, lajax.t('Choose street'));
			}
			
			self.require(self.fields.area, lajax.t('Enter home size'));
			self.require(self.fields.price, lajax.t('Enter price'));
			self.require(self.fields.content, lajax.t('Enter content'));
			self.require(self.fields.mobile, lajax.t('Enter mobile number'));
			
			if(!self.hasError(self.fields.area)) {
				var val = self.fields.area.val();
				
				if(!self.isNumber(val) || val < 0) {
					self.showError(self.fields.area, lajax.t('Home size is invalid'));
				} else {
					if(self.getSelectedCatEl()) {
						var limit = self.getSelectedCatEl().data('limit');
						
						if(limit && Number(limit) < Number(val)) {
							self.showError(self.fields.area, lajax.t('Home size must be not greater %s').replace('%s', limit));
						}
					}
				}
			}
			
			if(!self.hasError(self.fields.price)) {
				if(!self.isNumber(self.fields.price.val())) {
					self.showError(self.fields.price, lajax.t('Price is invalid'));
				}
			}
			
			if(!self.hasError(self.fields.mobile)) {
				var val = self.fields.mobile.val();
				
				if(!self.isDigit(val)) {
					self.showError(self.fields.mobile, lajax.t('Mobile number is invalid'));
				} else if(val.length < 7 || val.length > 11) {
					self.showError(self.fields.mobile, lajax.t('Số di động phải từ 7 đến 11 số.'));
				}
			}
			
			if(self.hasError(self.fields.mobile)) {
				self.fields.mobile.prop('disabled', false);
			}
			
			if(self.fields.email.val() && !self.validateEmail(self.fields.email.val())) {
				self.showError(self.fields.email, lajax.t('Địa chỉ email không hợp lệ'));
			} else {
				self.hideError(self.fields.email);
			}

			if(self.hasError(self.fields.email)) {
				self.fields.email.prop('disabled', false);
			}
			
			if(self.el.find('.has-error').length > 0) {
				return false;
			}
			
			return true;
		};
		
		self.getPreviewEls = function() {
			return {
				galleryDetail: $('.gallery-detail'),
				noGallery: $('.no-gallery'),
				swiperWrapper: $('.swiper-wrapper')
			};
		};
		
		self.updateRef = function(container) {
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
		};
		
		self.setLatLng = function(lat, lng) {
			self.fields.lat.val(lat);
			self.fields.lng.val(lng);
		};

		self.geoLocation = function(address) {
			if(address) {
				var geoCode = new google.maps.Geocoder;
				geoCode.geocode({address: address}, function(results) {
					if(results.length) {
						self.setLatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
					} else {
						self.geoLocation(self.splitAddress(address));
					}
				});
			}
		};
		
		self.splitAddress = function(address) {
			address = address.split(', ');
			address.shift();
			return address.join(', ');
		};
		
		self.buildAddress = function() {
			var address = [];
			
			if(self.fields.homeNo.val() && self.fields.showHomeNo.prop('checked')) {
				address.push(self.fields.homeNo.val());
			}

			
			var street = self.fields.streetId.find('option').filter(':selected');
			address.push(street.data('pre') + ' ' + street.text());
			
			var ward = self.fields.wardId.find('option').filter(':selected');
			address.push(ward.data('pre') + ' ' + ward.text());
			
			address.push(self.fields.districtId.find('option').filter(':selected').text());
			address.push(self.fields.cityId.find('option').filter(':selected').text());
			
			return address.join(', ');
		};
		
		self.hidePreview = function() {
			$('html, body').removeClass('previewing');
			
			if(self.swiper) {
				self.swiper.destroy(false, true);
				self.swiper = null;
			}
		};
		
		self.showPreview = function() {
			$('html, body').addClass('previewing');
			
			var previewEls = self.getPreviewEls();
			
			previewEls.swiperWrapper.html('');
			
			var galleries = self.files.find('.template-download');
			
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
			
			if(previewEls.swiperWrapper.html() != '') {
				self.swiper = new Swiper('.swiper-container', {
			        nextButton: '.swiper-button-next',
	    			prevButton: '.swiper-button-prev'
			    });
			}
			
			self.updateRef($('#review-listing'));
			
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
			
			$('.address-show').text(self.buildAddress());
		};
		
		self.getPoint = function(el) {
			return Number(parent(el).find('.point').text());
		};
		
		self.files.sortable({cancel: '.template-upload'});
		self.fields.type.on('change', self.filterCategories);
		self.fields.categoryId.on('change', self.catChange);
		self.fields.cityId.on('change', self.cityChange);
		self.fields.districtId.on('change', self.districtChange);
		self.el.find('.number-only').on('keydown', numnberOnly);
		
		self.input.on('change', self._hideError);
		self.input.on('keyup', self._hideError);
	};
	
	$('#preview').click(function(){
		if(form.validate()) {
			errorHint.hide();
			
			if(projectMask.data('lat') && projectMask.data('lng')) {
				form.setLatLng(projectMask.data('lat'), projectMask.data('lng'));
			} else {
				form.geoLocation(form.buildAddress());
			}
			
			form.showPreview();
		} else {
			errorHint.show();
			form.buildErrorHint();
		}
	});
	
	$('#review-listing').click(function(e){
		if($(e.target).attr('id') == 'review-listing') {
			form.hidePreview();
		}
	});
	
	$('#back').click(function(){
		form.hidePreview();
	});
	
	$('#post').click(function(){
		var disabled = form.el.find(':input:disabled').prop('disabled', false);
		
		$('body').loading();
		
		$.post(form.el.attr('action'), form.el.serialize(), function(r){
			$('body').loading({done: true});
			
			if(r.success) {
				$('.wrap-frm-listing').hide();
				$('#success-notify').removeClass('hide').html(r.template);
				
				window.onbeforeunload = function() {};
			} else {
				
			}
		});
		
		disabled.prop('disabled', true);
	});
	
	point.init();
	setTimeout(function(){point.calc();}, 500);
	attachWindowScrollEvent();
	attachProjectSuggest();
	attachRadioUi();
	attachPriceFormat();
	
	if(isNewRecord) {
		form.data = form.el.serialize();
	} else {
		if(totalImage) {
			function serialize(e, data) {
				form.data = form.el.serialize();
				jQuery('#w1-fileupload').off('fileuploadcompleted', serialize);
			}
			
			jQuery('#w1-fileupload').on('fileuploadcompleted', serialize);
		} else {
			form.data = form.el.serialize();
		}
	}
	
	window.onbeforeunload = function() {
		if(form.data != form.el.serialize()) {
			return lajax.t("Are you sure you want to navigate away from this page and discard all changes ?");
		}
	};

	function attachPriceFormat() {
		if(!isNewRecord) {
			var priceUnitMil = $('#price-unit-mil');
			var priceUnitBil = $('#price-unit-bil');
			var maskValue = Number(form.fields.price.val()) / Number(priceUnitMil.val());
			
			if(maskValue > 999) {
				priceMask.val((Number(form.fields.price.val()) / Number(priceUnitBil.val()) + '').replace('.', ','));
				priceUnitBil.parent().trigger('click');
			} else {
				priceMask.val(maskValue);
				priceUnitMil.parent().trigger('click');
			}
		}
		
		priceMask.on('keyup', function(e){
			calPrice();
		});
	}

	function attachRadioUi() {
		$('.radio-ui').radio({
			done: function (item) {
				if(item.attr('name') == 'price-unit') {
					calPrice();
				}
			}
		});
	}

	function calPrice() {
		var unit = Number(form.el.find('input[name=price-unit]').filter(':checked').val());
		var price = Number(priceMask.val().replace(',', '.'));
		
		if(price) {
			price = Math.round(price * unit);
			
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

	function removeProject() {
		form.fields.cityId.prop("disabled", false);
		form.fields.districtId.prop("disabled", false);
		form.fields.wardId.prop("disabled", false);
		form.fields.streetId.prop("disabled", false);
		form.fields.homeNo.prop("disabled", false);
		
		projectMask.val('').prop('readonly', false).focus();
		projectWrap.removeClass('show-project');
		form.fields.projectBuildingId.val('');
		
		projectMask.data('url', null);
		projectMask.data('lat', null);
		projectMask.data('lng', null);
		
		point.calc();
	}

	function attachProjectSuggest() {
		
		projectMask.on('keyup', function(){
			var val = $(this).val().trim();

			if($.data(this, 'ajax')) {
				$.data(this, 'ajax').abort();
			}
			
			if(val.length) {
				$.data(this, 'ajax', $.get('/map/search-project', {v: val}, function(response){
	        		if(response.length) {
	        			searchList.addClass('has-result');
	        			
	        			var list = searchList.find('ul').html('');
	        			
	        			for(var i in response) {
	        				list.append('<li class="search-item" data-id="' + response[i].id + '">' + response[i].full_name + '</li>');
	                  	}
	        		} else {
	        			searchList.removeClass('has-result');
	        		}
	        	}));
	    	} else {
	    		searchList.removeClass('has-result');
	    	}
		}).on('focus', function(){
			if(!projectMask.prop('readonly')) {
				showSearchList();
			}
		}).on('click', function(){
			if(projectMask.prop('readonly')) {
				window.open(projectMask.data('url'), '_blank');
			}
		});
		
		projectWrap.on('click', '.icon-close-icon', function(){
			removeProject();
		});
		
		searchList.on('click', 'li', function(){
			hideSearchList_();
			
			var self = $(this);
			var id = self.data('id');
			
			form.fields.projectBuildingId.val(id);
			
			projectMask.val(self.text()).prop('readonly', true);
			projectWrap.addClass('show-project');
			
			searchList.find('ul').html('');
			searchList.removeClass('has-result');
			
			$.get('/building-project/detail', {id: id}, function(r){
				projectMask.data('url', r.url);
				projectMask.data('lat', r.lat);
				projectMask.data('lng', r.lng);
				
				form.fields.cityId.prop("disabled", true);
				select2Select(form.fields.cityId, r.city_id);
				form.hideError(form.fields.cityId);

				form.fields.districtId.prop("disabled", true);
				show(form.fields.districtId);
				select2Change(form.fields.districtId, r.districts);
				select2Select(form.fields.districtId, r.district_id);
				form.hideError(form.fields.districtId);

				adw.show();

				select2Change(form.fields.wardId, r.wards);
				if(r.ward_id) {
					form.fields.wardId.prop("disabled", true);
					select2Select(form.fields.wardId, r.ward_id);
					form.hideError(form.fields.wardId);
				} else {
					form.fields.wardId.prop("disabled", false);
					select2Select(form.fields.wardId, '');
				}
				
				select2Change(form.fields.streetId, r.streets);
				if(r.street_id) {
					form.fields.streetId.prop("disabled", true);
					select2Select(form.fields.streetId, r.street_id);
					form.hideError(form.fields.streetId);
				} else {
					form.fields.streetId.prop("disabled", false);
					select2Select(form.fields.streetId, '');
				}
				
				if(r.home_no) {
					form.fields.homeNo.prop("disabled", true);
					form.fields.homeNo.val(r.home_no);
				} else {
					form.fields.homeNo.prop("disabled", false);
					form.fields.homeNo.val('');
				}

				form.buildErrorHint();
				
				point.calc();
			});
		});
		
		function showSearchList() {
			if(searchList.hasClass('hide')) {
				searchList.removeClass('hide');
				$(document).on('click', hideSearchList);
			}
		}
		
		function hideSearchList(e) {
			var target = $(e.target);
			
			if(target.hasClass('search-item') || target.closest('#project-wrap').length == 0) {
				hideSearchList_();
			}
		}
		
		function hideSearchList_() {
			searchList.addClass('hide');
			projectMask.val('');
			$(document).off('click', hideSearchList);
		}
	}

	function hide(el, reset, trigger) {
		parent(el).hide();

		if(reset) {
			var tagName = el.get(0).tagName;
			
			if(tagName == 'INPUT') {
				el.val('');
				
				if(trigger) {
					el.trigger('change');
				}
			} else if(tagName == 'SELECT') {
				select2Select(el, '', trigger);
			}
		}
	}

	function show(el) {
		parent(el).show();
	}

	function parent(el) {
		return el.closest('.form-group');
	}

	function select2(select) {
		select.each(function(){
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
	}

	function select2Change(el, options) {
		var firstOption = el.find('option:first-child').clone();
		
		el.html('');
		
		if(firstOption.attr('value') == '') {
			el.append(firstOption);
		}
		
		for(var i in options) {
			var option = options[i];
			
			var optionEl = $('<option value="' + option.id + '">' + option.name + '</option>');
			
			if(option.data) {
				optionEl.data(option.data);
			}
			
			if(option.pre) {
				optionEl.data('pre', option.pre);
			}
			
			el.append(optionEl);
		}
		
		var firstEl = el.find('option:first-child');
		
		if(firstEl.length) {
			el.val(firstEl.val()).trigger('change.select2');
		} else {
			el.val(null).trigger('change.select2');
		}
	}

	function select2Select(el, val, trigger) {
		if(!hasValue(el, val)) {
			var firstEl = el.find('option:first-child');
			val = firstEl.length ? firstEl.val() : null;
		}
		
		if(trigger) {
			el.val(val).change();
		} else {
			el.val(val).trigger('change.select2');
		}
	}

	function hasValue(select, val) {
		var exists = false;
		select.find('option').each(function(){
		    if(this.value == val) {
		        exists = true;
		        return false;
		    }
		});
		return exists;
	}

	function mapFieldsName(fields) {
		var _fields = {};
		var patt = /\[(\w*)\]/i;
		
		fields.each(function(){
			var self = $(this);
			var name = self.attr('name');
			
			if(name) {
				var pattMatch = name.match(patt);

				if(pattMatch) {
					var attribute = pattMatch[1];
					
					if(_fields[camel(attribute)]) {
						if(_fields[camel(attribute)].attr('type') == 'hidden') {
							_fields[camel(attribute)] = self;
						} else {
							_fields[camel(attribute)] = _fields[camel(attribute)].add(self);
						}
					} else {
						_fields[camel(attribute)] = self;
					}
				}
			}
		});
		
		return _fields;
	}

	function hideHint(e) {
		e.preventDefault();
		
		$(this).fadeOut().closest('.alert-success').slideUp();
		
		setServerCookie('cpl', 1);
	}

	function attachWindowScrollEvent() {
		var header = $('.wrap-header');
		var cl = $('.checkpoint-listing');
		var wrapFormListing = $('.wrap-frm-listing');
		
		var offtop, offleft, width, setTop;

		checkToAttach();
		$window.on('resize', checkToAttach);
		
		function checkToAttach() {
			cl.removeAttr('style');
			cl.get(0).offsetLeft;
			
			setTop = header.outerHeight() + 12;
			offtop = wrapFormListing.offset().top + parseInt(wrapFormListing.css('padding-top')) - setTop;
			offleft = cl.offset().left;
			width = cl.outerWidth();
			
			if(Number(cl.css('margin-top').replace('px', '')) == 0) {
				$window.on('scroll', wScroll);
				wScroll();
			} else {
				$window.off('scroll', wScroll);
			}
		}
		
		function wScroll() {
			var st = $window.scrollTop();
			if(st >= offtop) {
				cl.css({
					position: 'fixed',
					top: setTop + 'px',
					left: offleft + 'px',
					width: width + 'px'
				});
			} else {
				cl.removeAttr('style');
			}
		}
	}
});

var allow = [46, 8, 9, 27, 13, 110, 116];

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

function camel(str) {
	return str.replace(/_([a-z])|-([a-z])/g, function (g) { return g[1].toUpperCase(); });
}