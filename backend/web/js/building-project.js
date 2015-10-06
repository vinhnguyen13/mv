$(document).ready(function(){
	customChecbox($('.custom-checkbox'));

	var showContent = $('.show-content');
	showContent.click(function(e){
		e.preventDefault();
		
		var self = $(this);
		
		if(!self.hasClass('active')) {
			var currentActive = $('.show-content.active').removeClass('active');
			$('.bp-fields > li').eq(showContent.index(currentActive)).hide();
			
			self.addClass('active');
			currentActive = self;
			
			$('.bp-fields > li').eq(showContent.index(self)).show();
		}
	});
	$('.show-content').eq(0).trigger('click');
	
	$('.bp-subcontents > a').click(function(e){
		e.preventDefault();
		
		var sub = $(this).parent().find('> ul');
		
		if(sub.height() == 0) {
			sub.height(sub.get(0).scrollHeight);
		} else {
			sub.height(0);
		}
	});
	
	$('#bp-save-button').click(function(e){
		e.preventDefault();
		
		var form = $('#bp-form');
		var url = form.attr('action');
		var data = form.serialize();
		
		$.post(url, data, function(response){
			
		});
	});
});

function customChecbox(els) {
	els.each(function(){
		var self = $(this);
		
		if(self.prop('checked')) {
			var vCheck = $('<span class="vcheck checked"></span>');
		} else {
			var vCheck = $('<span class="vcheck"></span>');
		}
		
		self.after(vCheck);
		
		if(self.prop('disabled')) {
			vCheck.addClass('disabled');
		} else {
			self.change(function(){
				if(self.prop('checked')) {
					vCheck.addClass('checked');
				} else {
					vCheck.removeClass('checked');
				}
			})
			
			vCheck.parent().hover(function(){
				vCheck.addClass('hover');
			}, function(){
				vCheck.removeClass('hover');
			});
		}
	});
}

var customFileUpload = {
	filebatchselected: function(event, files) {
		$(event.target).fileinput('upload');
	},
	filebatchuploadsuccess: function(event, data, previewId, index) {
		console.log(event);
		console.log(data);
		console.log(previewId);
		console.log(index);
	}
};