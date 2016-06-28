$(document).ready(function(){
	$('.wrap-list-duan').on('click', '.btn-active', function(){
		$('.btn-active-listing').data('id', $(this).data('product'));
	});
	$('.btn-active-listing').click(function(e){
		e.preventDefault();
		
		var currentKey = Number($('.current-key').eq(0).text());
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
					
					var key = currentKey - charge;
					
					$('.current-key').text(key);
					balance.update(key);
				}

				$('#notify-text').text(r.message);
				$('#update-status').modal('toggle');
				$('#notify').modal('toggle');
			});
		}
	});
	
	$('.wrap-list-duan').on('click', '.btn-expired', function(){
		$('.btn-update-expired').data('id', $(this).data('product'));
	});
	$('.btn-update-expired').click(function(e){
		e.preventDefault();
		
		var currentKey = Number($('.current-key').eq(0).text());
		var charge = Number($('.charge-expired').text());
		
		if(currentKey < charge) {
			$('#update-expired').modal('toggle');
			$('#charge').modal('toggle');
		} else {
			var self = $(this);
			var id = self.data('id');
			
			$('body').loading();
			
			$.get(self.attr('href'), {id: id}, function(r){
				$('body').loading({done: true});
				
				if(r.success) {
					$('#p-' + id).replaceWith(r.template);
					
					var key = currentKey - charge;
					
					$('.current-key').text(key);
					balance.update(key);
				}

				$('#notify-text').text(r.message);
				$('#update-expired').modal('toggle');
				$('#notify').modal('toggle');
			});
		}
	});
	
	$('.wrap-list-duan').on('click', '.btn-boost', function(){
		$('.btn-boost-listing').data('id', $(this).data('product'));
	});
	$('.btn-boost-listing').click(function(e){
		e.preventDefault();
		
		var currentKey = Number($('.current-key').eq(0).text());
		var closest = $('.days-up').find('input:checked').closest('.radio-ui');
		var day = Number(closest.find('.day').text());
		var key = Number(closest.find('.key').text());
		
		if(currentKey < key) {
			$('#update-boost').modal('toggle');
			$('#charge').modal('toggle');
		} else {
			var self = $(this);
			var id = self.data('id');
			
			$('body').loading();
			
			$.get(self.attr('href'), {day: day, id: id}, function(r){
				$('body').loading({done: true});
				
				if(r.success) {
					$('#p-' + id).replaceWith(r.template);
					
					var ckey = currentKey - key;
					
					$('.current-key').text(ckey);
					balance.update(ckey);
				}

				$('#notify-text').text(r.message);
				$('#update-boost').modal('toggle');
				$('#notify').modal('toggle');
			});
		}
	});
});