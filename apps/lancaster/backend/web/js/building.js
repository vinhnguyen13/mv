$(document).ready(function(){
	$('#language-switch').change(function(){
		var languageCode = $(this).val();
		
		$('.language-fields').each(function(){
			var self = $(this);
			if(self.hasClass(languageCode)) {
				self.show();
			} else {
				self.hide();
			}
		});
	});
	
	$('#language-switch-view').change(function(){
		location.href = $(this).val();
	});
});