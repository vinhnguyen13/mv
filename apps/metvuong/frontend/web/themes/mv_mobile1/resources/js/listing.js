$(document).ready(function(){
	var searchForm = $('#search-form');
	var page = 1;
	
	$(window).on('scroll', next);

	$('.dropdown-select').dropdown({
		hiddenFillValue: '#sort',
		ajaxSubmit: function () {
			console.log('sort');
			searchForm.submit();
		}
	});

	searchForm.submit(function(e){
		console.log('submit');
		e.preventDefault();
		
		searchForm.data('loading', false);
		$(window).off('scroll', next);
		
		var id = '#content-holder';
		var contentHolder = $(id).html('');
		
		$('#item-loading').show();
		
		$.get(searchForm.attr('action'), searchForm.serialize(), function(r){
			r = $(r);
			
			contentHolder.html(r.find(id));

			$(window).on('scroll', next);
			
			if(r.find('#item-loading').length == 0) {
				searchForm.data('loading', true);
				$('#item-loading').hide();
			}

			page = 1;
		});
	});
	
	function next() {
		var self = $(this);
		if((self.scrollTop() >= (document.body.scrollHeight - 260 - self.height())) && !searchForm.data('loading')) {
			console.log('scroll');
			searchForm.data('loading', true);

			var inputPage = $('<input type="hidden" name="page" value="' + (++page) + '" />');
			searchForm.append(inputPage);
			
			$.get(searchForm.attr('action'), searchForm.serialize(), function(r){
				r = $(r);

				inputPage.remove();
				
				var items = r.find('.item-listing');
				
				$('#listing-list').append(items);
				
				$('#count-to').text(Number($('#count-to').text()) + items.length);
				
				if(r.find('#item-loading').length > 0) {
					searchForm.data('loading', false);
				} else {
					$('#item-loading').hide();
				}
			});
    	}
	}
});