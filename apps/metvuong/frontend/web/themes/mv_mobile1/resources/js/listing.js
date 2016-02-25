$(document).ready(function(){
	var searchForm = $('#search-form');
	var page = 1;
	var listingListId = '#listing-list';
	var itemLoadingId = '#item-loading';
	
	if(!$(itemLoadingId).hasClass('hide')) {
		$(window).on('scroll', next);
	}
	
	function next() {
		var self = $(this);
		
		if(self.scrollTop() >= (document.body.scrollHeight - 260 - self.height())) {
			$(window).off('scroll', next);
			
			var inputPage = $('<input type="hidden" name="page" value="' + (++page) + '" />');
			searchForm.append(inputPage);
			
			$.get(searchForm.attr('action'), searchForm.serialize(), function(r){
				r = $(r);
				
				inputPage.remove();
				
				var items = r.find('.item-listing');
				$(listingListId).append(items);
				
				var countTo = $('#count-to');
				countTo.text(Number(countTo.text()) + items.length);
				
				if(r.find(itemLoadingId).hasClass('hide')) {
					$(itemLoadingId).addClass('hide');
				} else {
					$(window).on('scroll', next);
				}
				$('.wrap-listing img').lazyload({
					threshold : 200
				});
			});
		}
	}
	
	$('.dropdown-select').dropdown({
		hiddenFillValue: '#sort',
		ajaxSubmit: function () {
			$('button.btn-submit').data('flag',false);
			searchForm.submit();
		}
	});
	
	searchForm.submit(function(e){
		e.preventDefault();

		var btnSubmit = $(this).find('button.btn-submit');

		if ( btnSubmit.data('flag') ) {
			btnSubmit.data('flag', false);
			return;
		}else {
			btnSubmit.data('flag', true);
		}

		$(window).off('scroll', next);
		
		$(itemLoadingId).removeClass('hide');
		
		var id = '#content-holder';
		var contentHolder = $(id).html('');
		
		page = 1;
		
		$.get(searchForm.attr('action'), searchForm.serialize(), function(r){
			r = $(r);
			
			contentHolder.html(r.find(id).html());
			
			if(r.find(itemLoadingId).hasClass('hide')) {
				$(itemLoadingId).addClass('hide');
			} else {
				$(window).on('scroll', next);
			}
			$('.wrap-listing img').lazyload({
				threshold : 200
			});
		});
	});
});