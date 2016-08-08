$(document).ready(function(){
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
	
	$('#toggle').click(function(e){
		e.preventDefault();
		
		$('#filter_columns').toggleClass('show');
	});
	
	$(document).click(function(e){
		var target = $(e.target);
		
		if(target.closest('#columns-wrap').length == 0 && target.attr('id') != 'toggle') {
			$('#filter_columns').removeClass('show');
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
			$.get('/admin/craw/manager/search', {type: type, value: self.val()}, function(r){
				var parent = self.closest('.mask-wrap');
				
				if(r) {
					parent.addClass('show-search');
					
					var ul = '';
					
					for(var i in r) {
						ul += '<li><a href="#" class="hit" data-id="' + i + '">' + r[i] + '</a></li>';
					}
					
					parent.find('.result-search').html(ul);
				} else {
					parent.removeClass('show-search');
				}
			});
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