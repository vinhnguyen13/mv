$(document).ready(function(){
	$('#export-link').click(function(){
		setCookie('downloadComplete', '', 0)
		
		var el = $('<div style="top:0px; left:0px; position: fixed; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 99999; "><div style=" position: absolute; top: 50%; margin-top: -20px; font-size: 28px; color: #FFF; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.7); left: 50%; transform: translateX(-50%); "><span style="white-space: nowrap;" class="text">Đang tạo file Excel</span><img src="/admin/css/images/submit-loading.gif" style=" margin-left: 8px; width: 20px; "></div></div>');
		$('body').append(el);
		
		loading(el, 0);
	});
	
	$('#content-setting-wrap').find('input').change(function(){
		setCookie('export-setting', $(this).val());
	});
	
	$('.range-date').datepicker({
		dateFormat: "dd-mm-yy",
		constrainInput: true
	});
	
	function loading(el, start) {
		var text = el.find('.text');
		
		if(getCookie('downloadComplete')) {
			text.fadeOut(function(){
				el.find('img').remove();
				text.text('ĐÃ TẠO XONG FILE EXCEL').fadeIn(function(){
					setTimeout(function(){
						el.remove();
					}, 1000);
				});
			});
			
			setCookie('downloadComplete', '', 0)
		} else {
			if(start == 80) {
				text.fadeOut(function(){
					text.text('Vui lòng chờ thêm chút nửa').fadeIn();
				});
			} else if(start == 30) {
				text.fadeOut(function(){
					text.text('Build tổng cộng ' + $('.summary').find('b').eq(1).text() + ' record').fadeIn();
				});
			} else if(start == 140) {
				text.fadeOut(function(){
					text.text('Càng nhiều record thì build càng lâu').fadeIn();
				});
			} else if(start == 240) {
				text.fadeOut(function(){
					text.text('Vẫn đang tiếp tục tạo file...').fadeIn();
				});
			} else if(start == 440) {
				text.fadeOut(function(){
					text.text('Nhiều record quá ! chờ, đừng đóng cửa sổ').fadeIn();
				});
				
				start = -80;
			}
			
			setTimeout(function(){
				loading(el, ++start);
			}, 100);
		}
	}
	
	$('.toggle-column').change(function(){
		var exclude = getCookie('columns');
		
		exclude = exclude ? exclude.split(',') : [];
		
		var val = $(this).val();
		
		if(this.checked) {
			var index = exclude.indexOf(val);
			exclude.splice(index, 1);
	    } else {
	    	exclude.push(val);
	    }
		
		setCookie('columns', exclude.join(','));
	});
	
	$('#content-setting').click(function(e){
		e.preventDefault();
		
		$('#content-setting-wrap').toggleClass('show');
	});
	
	$('#toggle').click(function(e){
		e.preventDefault();
		
		$('#filter_columns').toggleClass('show');
	});
	
	$(document).click(function(e){
		var target = $(e.target);

		if(target.closest('#columns-wrap').length == 0 && target.attr('id') != 'toggle') {
			$('#filter_columns').removeClass('show');
		}
		
		if(target.closest('#content-setting-wrap').length == 0 && target.attr('id') != 'content-setting') {
			$('#content-setting-wrap').removeClass('show');
		}
	});
	
	$('.filter-type-3').change(function(e){
		var self = $(this);
		var val = self.val();
		
		if(val == 3) {
			var next = self.hide().next();
			
			next.show();
			next.find('input').focus();
			
			e.stopPropagation();
		}
	});
	$('.filter-type-2').change(function(e){
		var self = $(this);
		var val = self.val();
		
		var max = self.data('max') ? self.data('max') : 3;
		
		if(val == max) {
			var next = self.hide().next();
			
			next.show();
			next.find('.search-range').click();
			
			e.stopPropagation();
		}
	});
	
	var currentFocus = $();
	
	$('.search').keyup(function(){
		var self = $(this);
		var type = self.data('type');
		
		if(type) {
			$.data(this, 'ajax') && $.data(this, 'ajax').abort();
			
			$.data(this, 'ajax', $.get('/api/v1/craw-search/get-m', {type: type, value: self.val()}, function(r){
				var parent = self.closest('.mask-wrap');
				
				if(r.length) {
					parent.addClass('show-search');
					
					var ul = '';
					
					for(var i in r) {
						ul += '<li><a href="#" class="hit" data-id="' + r[i].id + '">' + r[i].full_name + '</a></li>';
					}
					
					parent.find('.result-search').html(ul);
				} else {
					parent.removeClass('show-search');
				}
			}));
		}
	}).change(function(e){
		e.stopPropagation();
	}).focus(function(){
		var self = $(this);
		
		self.data('backup', self.val());
		self.val('');
		
		currentFocus = self;
	});
	
	$('.mask-wrap').on('click', '.hit', function(e){
		e.preventDefault();
		
		var self = $(this);
		var parent = self.closest('.mask-wrap');
		
		parent.next().val(self.data('id'));
		parent.find('.search').val(self.html()).trigger('change.yiiGridView');
	});
	
	$(document).click(function(e){
		if($(e.target).closest('.mask-wrap').length == 0) {
			currentFocus.val(currentFocus.data('backup'));
			currentFocus.closest('.mask-wrap').removeClass('show-search');
			
			currentFocus = $();
		}
	});
	
	$('.range-wrap .mask-close').on('click', function(){
		var self = $(this);
		var parent = self.closest('.mask-wrap');
		parent.find('.range-value').val('');
		parent.find('.search-range').val('');
	});
	
	$('.simple-close').on('click', function(){
		var self = $(this);
		var parent = self.closest('.mask-wrap');
		parent.find('.search').val('');
		self.hide();
	});
	
	$('.mask-close').on('click', function(){
		var self = $(this);
		var parent = self.closest('.mask-wrap');
		parent.prev().val('');
		parent.next().val('');
		parent.find('.search').trigger('change.yiiGridView');
	});
	
	$('.search-range').click(function(){
		var self = $(this);
		var parent = self.closest('.mask-wrap').addClass('show-range');
	});
	
	$('.range-value').change(function(e){
		e.stopPropagation();
	});
	
	$('.range-value').keyup(function(){
		var parent = $(this).closest('.range');
		
		parent.prev().prev().val(parent.find('.range-min').val() + ' - ' + parent.find('.range-max').val());
	});
	
	$('.range-date').change(function(){
		$('#created_mask').val($('.range-date.range-min').val() + ' -> ' + $('.range-date.range-max').val());
	});

	$('.price-wrap .range-value').keyup(function(){
		var parent = $(this).closest('.range');
		var t = (($('#price_unit').val() == '1') ? 'tỷ' : 'tr');
		
		parent.prev().prev().val((parent.find('.range-min').val() ? parent.find('.range-min').val() + t : '') + ' - ' + (parent.find('.range-max').val() ? parent.find('.range-max').val() + t : ''));
	});
	
	$('#price_unit').change(function(){
		var parent = $(this).closest('.range');
		var t = (($('#price_unit').val() == '1') ? 'tỷ' : 'tr');
		
		parent.prev().prev().val((parent.find('.range-min').val() ? parent.find('.range-min').val() + t : '') + ' - ' + (parent.find('.range-max').val() ? parent.find('.range-max').val() + t : ''));
	});
	
	$('.submit-range').click(function(){
		$(this).closest('.mask-wrap').find('.search').trigger('change.yiiGridView');
	});
});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
	
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}