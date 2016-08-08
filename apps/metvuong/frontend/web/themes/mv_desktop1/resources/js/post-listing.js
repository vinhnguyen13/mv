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
	var previewButton = $('#preview');
	
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
		
		self.waitingUpload = 0;
		
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
		
		self.fileuploadadd = function(e, data, instance) {
			self.waitingUpload++;
			
			previewButton.addClass('disabled');
		};
		self.fileuploadalways = function(e, data, instance) {
			self.waitingUpload--;
			
			if(self.waitingUpload == 0) {
				previewButton.removeClass('disabled');
			}
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
			
			if(self.fields.homeNo.val().length > 32) {
				self.showError(self.fields.homeNo, lajax.t('Số nhà không được nhiều hơn 32 ký tự'));
			} else if(self.fields.homeNo.val().length > 0) {
				var match = self.fields.homeNo.val().match(/[A-Za-z\u00AA\u00B5\u00BA\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u02C1\u02C6-\u02D1\u02E0-\u02E4\u02EC\u02EE\u0370-\u0374\u0376\u0377\u037A-\u037D\u037F\u0386\u0388-\u038A\u038C\u038E-\u03A1\u03A3-\u03F5\u03F7-\u0481\u048A-\u052F\u0531-\u0556\u0559\u0561-\u0587\u05D0-\u05EA\u05F0-\u05F2\u0620-\u064A\u066E\u066F\u0671-\u06D3\u06D5\u06E5\u06E6\u06EE\u06EF\u06FA-\u06FC\u06FF\u0710\u0712-\u072F\u074D-\u07A5\u07B1\u07CA-\u07EA\u07F4\u07F5\u07FA\u0800-\u0815\u081A\u0824\u0828\u0840-\u0858\u08A0-\u08B4\u0904-\u0939\u093D\u0950\u0958-\u0961\u0971-\u0980\u0985-\u098C\u098F\u0990\u0993-\u09A8\u09AA-\u09B0\u09B2\u09B6-\u09B9\u09BD\u09CE\u09DC\u09DD\u09DF-\u09E1\u09F0\u09F1\u0A05-\u0A0A\u0A0F\u0A10\u0A13-\u0A28\u0A2A-\u0A30\u0A32\u0A33\u0A35\u0A36\u0A38\u0A39\u0A59-\u0A5C\u0A5E\u0A72-\u0A74\u0A85-\u0A8D\u0A8F-\u0A91\u0A93-\u0AA8\u0AAA-\u0AB0\u0AB2\u0AB3\u0AB5-\u0AB9\u0ABD\u0AD0\u0AE0\u0AE1\u0AF9\u0B05-\u0B0C\u0B0F\u0B10\u0B13-\u0B28\u0B2A-\u0B30\u0B32\u0B33\u0B35-\u0B39\u0B3D\u0B5C\u0B5D\u0B5F-\u0B61\u0B71\u0B83\u0B85-\u0B8A\u0B8E-\u0B90\u0B92-\u0B95\u0B99\u0B9A\u0B9C\u0B9E\u0B9F\u0BA3\u0BA4\u0BA8-\u0BAA\u0BAE-\u0BB9\u0BD0\u0C05-\u0C0C\u0C0E-\u0C10\u0C12-\u0C28\u0C2A-\u0C39\u0C3D\u0C58-\u0C5A\u0C60\u0C61\u0C85-\u0C8C\u0C8E-\u0C90\u0C92-\u0CA8\u0CAA-\u0CB3\u0CB5-\u0CB9\u0CBD\u0CDE\u0CE0\u0CE1\u0CF1\u0CF2\u0D05-\u0D0C\u0D0E-\u0D10\u0D12-\u0D3A\u0D3D\u0D4E\u0D5F-\u0D61\u0D7A-\u0D7F\u0D85-\u0D96\u0D9A-\u0DB1\u0DB3-\u0DBB\u0DBD\u0DC0-\u0DC6\u0E01-\u0E30\u0E32\u0E33\u0E40-\u0E46\u0E81\u0E82\u0E84\u0E87\u0E88\u0E8A\u0E8D\u0E94-\u0E97\u0E99-\u0E9F\u0EA1-\u0EA3\u0EA5\u0EA7\u0EAA\u0EAB\u0EAD-\u0EB0\u0EB2\u0EB3\u0EBD\u0EC0-\u0EC4\u0EC6\u0EDC-\u0EDF\u0F00\u0F40-\u0F47\u0F49-\u0F6C\u0F88-\u0F8C\u1000-\u102A\u103F\u1050-\u1055\u105A-\u105D\u1061\u1065\u1066\u106E-\u1070\u1075-\u1081\u108E\u10A0-\u10C5\u10C7\u10CD\u10D0-\u10FA\u10FC-\u1248\u124A-\u124D\u1250-\u1256\u1258\u125A-\u125D\u1260-\u1288\u128A-\u128D\u1290-\u12B0\u12B2-\u12B5\u12B8-\u12BE\u12C0\u12C2-\u12C5\u12C8-\u12D6\u12D8-\u1310\u1312-\u1315\u1318-\u135A\u1380-\u138F\u13A0-\u13F5\u13F8-\u13FD\u1401-\u166C\u166F-\u167F\u1681-\u169A\u16A0-\u16EA\u16F1-\u16F8\u1700-\u170C\u170E-\u1711\u1720-\u1731\u1740-\u1751\u1760-\u176C\u176E-\u1770\u1780-\u17B3\u17D7\u17DC\u1820-\u1877\u1880-\u18A8\u18AA\u18B0-\u18F5\u1900-\u191E\u1950-\u196D\u1970-\u1974\u1980-\u19AB\u19B0-\u19C9\u1A00-\u1A16\u1A20-\u1A54\u1AA7\u1B05-\u1B33\u1B45-\u1B4B\u1B83-\u1BA0\u1BAE\u1BAF\u1BBA-\u1BE5\u1C00-\u1C23\u1C4D-\u1C4F\u1C5A-\u1C7D\u1CE9-\u1CEC\u1CEE-\u1CF1\u1CF5\u1CF6\u1D00-\u1DBF\u1E00-\u1F15\u1F18-\u1F1D\u1F20-\u1F45\u1F48-\u1F4D\u1F50-\u1F57\u1F59\u1F5B\u1F5D\u1F5F-\u1F7D\u1F80-\u1FB4\u1FB6-\u1FBC\u1FBE\u1FC2-\u1FC4\u1FC6-\u1FCC\u1FD0-\u1FD3\u1FD6-\u1FDB\u1FE0-\u1FEC\u1FF2-\u1FF4\u1FF6-\u1FFC\u2071\u207F\u2090-\u209C\u2102\u2107\u210A-\u2113\u2115\u2119-\u211D\u2124\u2126\u2128\u212A-\u212D\u212F-\u2139\u213C-\u213F\u2145-\u2149\u214E\u2183\u2184\u2C00-\u2C2E\u2C30-\u2C5E\u2C60-\u2CE4\u2CEB-\u2CEE\u2CF2\u2CF3\u2D00-\u2D25\u2D27\u2D2D\u2D30-\u2D67\u2D6F\u2D80-\u2D96\u2DA0-\u2DA6\u2DA8-\u2DAE\u2DB0-\u2DB6\u2DB8-\u2DBE\u2DC0-\u2DC6\u2DC8-\u2DCE\u2DD0-\u2DD6\u2DD8-\u2DDE\u2E2F\u3005\u3006\u3031-\u3035\u303B\u303C\u3041-\u3096\u309D-\u309F\u30A1-\u30FA\u30FC-\u30FF\u3105-\u312D\u3131-\u318E\u31A0-\u31BA\u31F0-\u31FF\u3400-\u4DB5\u4E00-\u9FD5\uA000-\uA48C\uA4D0-\uA4FD\uA500-\uA60C\uA610-\uA61F\uA62A\uA62B\uA640-\uA66E\uA67F-\uA69D\uA6A0-\uA6E5\uA717-\uA71F\uA722-\uA788\uA78B-\uA7AD\uA7B0-\uA7B7\uA7F7-\uA801\uA803-\uA805\uA807-\uA80A\uA80C-\uA822\uA840-\uA873\uA882-\uA8B3\uA8F2-\uA8F7\uA8FB\uA8FD\uA90A-\uA925\uA930-\uA946\uA960-\uA97C\uA984-\uA9B2\uA9CF\uA9E0-\uA9E4\uA9E6-\uA9EF\uA9FA-\uA9FE\uAA00-\uAA28\uAA40-\uAA42\uAA44-\uAA4B\uAA60-\uAA76\uAA7A\uAA7E-\uAAAF\uAAB1\uAAB5\uAAB6\uAAB9-\uAABD\uAAC0\uAAC2\uAADB-\uAADD\uAAE0-\uAAEA\uAAF2-\uAAF4\uAB01-\uAB06\uAB09-\uAB0E\uAB11-\uAB16\uAB20-\uAB26\uAB28-\uAB2E\uAB30-\uAB5A\uAB5C-\uAB65\uAB70-\uABE2\uAC00-\uD7A3\uD7B0-\uD7C6\uD7CB-\uD7FB\uF900-\uFA6D\uFA70-\uFAD9\uFB00-\uFB06\uFB13-\uFB17\uFB1D\uFB1F-\uFB28\uFB2A-\uFB36\uFB38-\uFB3C\uFB3E\uFB40\uFB41\uFB43\uFB44\uFB46-\uFBB1\uFBD3-\uFD3D\uFD50-\uFD8F\uFD92-\uFDC7\uFDF0-\uFDFB\uFE70-\uFE74\uFE76-\uFEFC\uFF21-\uFF3A\uFF41-\uFF5A\uFF66-\uFFBE\uFFC2-\uFFC7\uFFCA-\uFFCF\uFFD2-\uFFD7\uFFDA-\uFFDC]/g);
				if(!/[0-9]/.test(self.fields.homeNo.val()) || (match && match.length > 6)) {
					self.showError(self.fields.homeNo, lajax.t('Số nhà không hợp lệ.'));
				}
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
					if($(this).find('input').length) {
						previewEls.swiperWrapper.append('<div class="swiper-slide"> <div class="img-show"> <div> <img src="' + $(this).find('a').attr('href') + '"> </div> </div> </div>');
					}
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
	
	$('#notify').on('click', '.boost', function(e){
		e.preventDefault();
		
		$('body').loading();
		
		$.get($(this).attr('href'), function(r){
			
			$('body').loading({done: true});
			
			if(r.success) {
				balance.update(r.amount);
			} else {
				$('#notify-boost-charge').show();
			}
			
			$('.notify-post').hide();
			$('.notify-boost').show();
			
			$('#notify-boost-message').text(r.message);
			$('#notify-boost-key').find('.key').text(r.amount);
		});
	});
	
	previewButton.click(function(){
		if(previewButton.hasClass('disabled')) {
			$('.wait-upload-hint').show();
		} else {
			$('.wait-upload-hint').hide();
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
				$('#notify').removeClass('hide').html(r.template);
				
				balance.update(r.amount);
				
				window.onbeforeunload = function() {};
				window.onunload = function() {};
			} else {
				for(var fn in r.errors) {
					var f = r.errors[fn]
					
					for(var er in f) {
						alert(f[er]);
					}
				}
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
	
	window.onunload = function() {
		if(isNewRecord && form.data != form.el.serialize()) {
			var disabled = form.el.find(':input:disabled').prop('disabled', false);
			
			$.ajax({
				url: form.el.data('auto-save'),
				data: form.el.serialize(),
				type: 'POST',
				async: false
			});
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
		}).on('keydown', function(e){
			var self = $(this);
			
			if(e.keyCode === 190) {
				self.val(self.val() + ',');
			}
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