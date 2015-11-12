$(document).ready(function(){
	toggleLanguageFields($('#language-switch').val());
	
	$('#language-switch').change(function(){
		var languageCode = $(this).val();
		toggleLanguageFields(languageCode);
		
		for(index in CKEDITOR.instances) {
			CKEDITOR.instances[index].updateElement();
		}
	});
	
	$('#language-switch-view').change(function(){
		location.href = $(this).val();
	});
	
	$('.nav-tabs a').on('shown.bs.tab', function (e) {
		$($(e.target).attr('href')).find('.ckeditor-textarea').each(function(){
			CKEDITOR.instances[$(this).attr('id')].updateElement();
		});
	});
});

function toggleLanguageFields($lang) {
	$('.language-fields').each(function(){
		var self = $(this);
		if(self.hasClass($lang)) {
			self.show();
		} else {
			self.hide();
		}
	});
}