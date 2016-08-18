$(document).ready(function(){
	var avgSearchWrap = $('#avg-search-wrap');
	var avgSearch = $('#avg-search');
	var avgSearchPlaceholder = $('.avg-search-placeholder');
	var resultSearchWrap = $('#result-search-wrap');
	var resultSearch = resultSearchWrap.find('.result-search');
	var loading = $('#loading');
	var viewWrap = $('#view-wrap');
	var filterWrap = $('#filter-wrap');
	var type = $('#type');
	var inputWrap = $('#input-wrap');
	
	avgSearch.keyup(function(e){
		$.data(this, 'ajax') && $.data(this, 'ajax').abort();
		
		$.data(this, 'ajax', $.get(avgSearch.data('url'), {v: avgSearch.val()}, function(r){
    		if(r.length > 0) {
    			showSearchList(r);
    		} else {
    			hideSearchList();
    		}
		}));
	});
	
	$(document).on('click', '.hit', function(e){
		e.preventDefault();
		
		var self = $(this);
		
		filterWrap.addClass('active');
		hideSearchList();
		avgSearch.val('').data({type: self.data('type'), id: self.data('id')});
		
		avgSearchPlaceholder.find('.text').text(self.text());
		
		viewWrap.removeClass('hide');
		
		calculate();
	});
	
	filterWrap.find('select').change(function(){
		if(!viewWrap.hasClass('hide')) {
			calculate();
		}
	});
	
	avgSearchWrap.find('.close').click(function(e){
		e.preventDefault();
		
		filterWrap.removeClass('active');
		avgSearch.focus();

		viewWrap.addClass('hide').removeClass('loaded');
	});
	
	function calculate() {
		viewWrap.removeClass('loaded');
		
		$.get('calculate', {type: avgSearch.data('type'), id: avgSearch.data('id'), t: type.val(), location: avgSearchPlaceholder.find('.text').text()}, function(r){
			viewWrap.addClass('loaded');
			
			inputWrap.find('a').attr('href', r.url);
		});
	}
	
	function showSearchList(r) {
		$(document).on('click', _hideSearchList);
		
		var html = '';
		
		for(var i in r) {
			html += '<li><a href="#" class="hit" data-type="' + r[i].type + '" data-id="' + r[i].id + '">' + r[i].full_name + '</a></li>';
		}
		
		resultSearch.html(html);
		resultSearchWrap.removeClass('hide');
	}
	
	function _hideSearchList(e) {
		if(!$(e.target).closest('#avg-search-wrap').length) {
			hideSearchList();
		}
	}
	
	function hideSearchList() {
		$(document).off('click', _hideSearchList);
		
		resultSearchWrap.addClass('hide');
		resultSearch.html('');
	}
});