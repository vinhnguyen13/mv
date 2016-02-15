$(document).ready(function(){
	var searchForm = $('#search-form');
	var listingList = $('#listing-list');
	var page = 1;
	
	$(window).scroll(function() {
		var self = $(this);
		if(self.scrollTop() >= (document.body.scrollHeight - 260 - self.height())) {
			if(!self.data('loading')) {
				self.data('loading', true);

				var inputPage = $('<input type="hidden" name="page" value="' + (++page) + '" />');
				searchForm.append(inputPage);
				
				$.get(searchForm.attr('action'), searchForm.serialize(), function(r){
					var items = $(r).find('.item-listing');
					inputPage.remove();
					
					if(items.length > 0) {
						listingList.append(items);
						self.data('loading', false);
					} else {
						$('#item-loading').hide();
					}
				});
			}
    	}
	});

	$('.dropdown-select').dropdown({
		hiddenFillValue: '#sort',
		ajaxSubmit: function () {
			searchForm.submit();
		}
	});

});