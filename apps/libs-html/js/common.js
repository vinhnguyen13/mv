$(document).ready(function () {
	//Start Popup style 2
	$('.login-modal-footer a').on('click', function() {
		var _this = $(this),
			idTabPopup = _this.attr('class');
		$('#'+idTabPopup+'a').tab('show');
		return false;
	});
	//End Popup style 2
});