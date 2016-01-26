$(document).ready(function() {
	$("#menu-header").mmenu({
		navbar: {
			add: false
		}
	});

	$(document).on('click','.link-login', function() {$('a[href=#tab-signin]').trigger('click');});
	$(document).on('click','.link-regis', function() {$('a[href=#tab-signup]').trigger('click');});
});