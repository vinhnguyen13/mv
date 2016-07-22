$(document).ready(function(){
	$('.wrap-list-duan').on('click', '.btn-active', function(){
		$('.btn-active-listing').data('id', $(this).data('product'));
	});
	$('.btn-active-listing').click(function(e){
		e.preventDefault();
		
		var currentKey = Number($('.current-key').text());
		var charge = Number($('.charge-post').text());
		
		if(currentKey < charge) {
			$('#update-status').modal('toggle');
			$('#charge').modal('toggle');
		} else {
			var self = $(this);
			var id = self.data('id');
			
			$('body').loading();
			
			$.get(self.attr('href'), {id: id}, function(r){
				$('body').loading({done: true});
				
				if(r.success) {
					$('#p-' + id).replaceWith(r.template);
					
					$('.current-key').text(currentKey - charge);
				}

				$('#notify-text').text(r.message);
				$('#update-status').modal('toggle');
				$('#notify').modal('toggle');
			});
		}
	});
});