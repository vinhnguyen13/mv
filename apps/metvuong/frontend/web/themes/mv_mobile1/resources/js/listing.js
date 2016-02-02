$(document).ready(function () {
	$('.dropdown-select').dropdown();
	$('.num-phongngu, .num-phongtam').dropdown({
		txtAdd: true,
		styleShow: 0
	});
	$('.search-subpage').toggleShowMobi();
	$('.choice_price_dt').price_dt();
});