$(document).ready(function(){
	var searchForm = $('#search-form');
	var listingList = $('#listing-list');
	var page = $('#page');
	
	$(window).scroll(function() {
		var self = $(this);
		if(self.scrollTop() >= (document.body.scrollHeight - 240 - self.height())) {
			if(!self.data('loading')) {
				self.data('loading', true);
				$.get(searchForm.attr('action'), searchForm.serialize(), function(r){
					var items = $(r).find('.item-listing');
					
					if(items.length > 0) {
						listingList.append(items);
						self.data('loading', false);
						page.val(Number(page.val()) + 1);
					} else {
						$('#item-loading').hide();
					}
				});
			}
    	}
	});
});