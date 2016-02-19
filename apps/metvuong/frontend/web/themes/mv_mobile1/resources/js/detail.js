$(document).ready(function () {
	var swiper = new Swiper('.swiper-container', {
		pagination: '.swiper-pagination',
		paginationClickable: true,
        spaceBetween: 30
    });

});
function loaded() {
	$(document).ready(function () {
		var mapEl = $('#map');
		var latLng = {lat: Number(mapEl.data('lat')), lng:  Number(mapEl.data('lng'))};
		var map = new google.maps.Map(mapEl.get(0), {
			center: latLng,
		    zoom: 16,
		    mapTypeControl: false,
		    zoomControl: false,
		    streetViewControl: false,
		    draggable: false
		});
		
		var marker = new google.maps.Marker({
		    position: latLng,
		    map: map
		});
	});
}