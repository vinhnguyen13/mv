(function(){
	$(document).on('click','.title-chat', function () {
		if ( $(this).hasClass('active') ) {
			$(this).parent().css('height','auto');
			$(this).removeClass('active');
		}else {
			$(this).parent().css('height','34px');
			$(this).addClass('active');
		}
		
	});
})();