//m2Map.initial(function(gmap) {
//	var options = {center: {lat: 10.783068, lng: 106.704825}, zoom: 15};
//	var gmap = new google.maps.Map(document.getElementById('map'), options);
//});
getListingLocation(function(result){
	console.log(result);
});

function getListingLocation(callback) {
	$.get(searchForm.attr('action'), searchForm.serialize(), function(r){
		callback(r);
	});
}