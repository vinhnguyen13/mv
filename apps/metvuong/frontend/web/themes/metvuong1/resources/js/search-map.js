var gmap = null, response = null, listResult, infoWindow;
var zIndex = 100;
$(document).ready(function(){
	listResult = $('.list-results');
	var mapOptions = {
		center: {lat: 10.803164, lng: 106.631439},
	    zoom: 14
	};
	asynInitial(document.getElementById('map'), mapOptions, function(gmapInstance){
		gmap = gmapInstance;
		start();
	});
	
	search(function(r){
		response = r;
		start();
	});
});

function search(callback) {
	var searchForm = $('#map-search-form');
	
	$.get(searchForm.attr('action'), searchForm.serialize(), function(r) {
		if(callback) {
			callback(r);
		}
	});
}

function start() {
	if(response && gmap) {
		infoWindow = new InfoWindow();
		
		$('#detail-wrap').on('click', '.close', function(){
			$('#detail-wrap').css({
				left: '0px'
			});
		});
		
		listResult.on('mouseenter', '> li', function() {
			var self = $(this);
			if(self.hasClass('onmap'))
				return;
			var marker = gmap.getMarker(self.data('id'));
			marker.setIcon('/images/marker-hover.png');
			marker.setZIndex(zIndex++);
			if(!gmap.getBounds().contains(marker.getPosition())) {
				gmap.setCenter(marker.getPosition());
			}
		}).on('mouseleave', '> li', function() {
			var self = $(this);
			if(self.hasClass('onmap'))
				return;
			var marker = gmap.getMarker(self.data('id'));
			marker.setIcon('/images/marker.png');
		});
		
		listResult.on('click', '> li', function(){
			var self = $(this);
			
			$('#map-loading').height($('.cd-main-content').height()).show();
			
			$.get('/ads/detail', {id: $(this).data('detail')}, function(response){

				var width = $('.wrap-map-result').width();
				width = (width > 820) ? 820 : width;
				
				$('#detail-wrap').css({
					width: width,
					left: '-' + width + 'px',
					height: $('.result-items').height()
				});
				$('#detail-listing').html($(response).html());
				$('.gallery-detail').imagesLoaded()
				 	.always( function( instance ) {
					    // all loaded (broken + success)
				 		console.log('all images loaded');
				 	})
				 	.done( function( instance ) {
						$('#map-loading').hide();
						
				 		setTimeout(function() {
				 			$('#detail-listing .bxslider').bxSlider({
				                moveSlides: 1,
				                startSlide: 0,
				                minSlides: 1,
				                maxSlides: 2,
				                slideWidth: 444,
				                startSlide: 0,
				                onSliderLoad: function() {
				                    this.infiniteLoop = false;
				                    this.hideControlOnEnd = true;
				                    scrollFixed();
				                }
				            });
							lightbox.option({
					            'resizeDuration': 300,
					            'fadeDuration': 400
					        });
							
							if(!self.data('clone')) {
								var marker = gmap.getMarker(self.data('id'));
								var position = marker.getPosition();
								gmap.setCenter({lat: position.lat(), lng: position.lng()});
							}
				 		},500);
					    
				 	})
				 	.fail( function() {
				 		console.log('all images loaded, at least one is broken');
				 	})
				 	.progress( function( instance, image ) {
				 		var result = image.isLoaded ? 'loaded' : 'broken';
				 		console.log( 'image is ' + result + ' for ' + image.img.src );
				 	});
				
			});
		});
		
		$('#order-by-tab a').click(function(){
			gmap.removeAllMarker();
			$('#order-by').val($(this).data('order'));
			search(function(r){
				response = r;
				loadListing();
			});
		});
		
		loadListing();
	}
}

function loadListing() {
	var list = '';
	var count = 0;
	for(index in response) {
		var product = response[index];
		list += makeMarker(product);
		count++;
	}
	
	$('#count-listing').text(count);
	listResult.empty().append(list);
}

function makeMarker(product) {
	var city = dataCities[product.city_id];
	var district = city['districts'][product.district_id];
	var ward = district['wards'][product.ward_id];
	var street = district['streets'][product.street_id];
	var address = product.home_no + ' ' + street.pre + ' ' + street.name + ', ' + ward.pre + ' ' + ward.name + ', ' + district.pre + ' ' + district.name + ', ' + city.name;

	var marker = new Marker({
		draggable: false,
	    position: {lat: Number(product.lat), lng: Number(product.lng)},
	    icon: '/images/marker.png',
	});
	
	marker.click(function(latLng){
		var id = marker.getId();
		var listEl = $('#moi-nhat').clone(true).removeAttr('id');
		var list = listEl.find('li');
		
		list.each(function(){
			if(id == $(this).data('id')) {
				$(this).data('clone', true);
				list.not($(this)).remove();
				$(this).addClass('onmap');
				infoWindow.setContent(listEl.get(0));
				return false;
			}
		});
		
		infoWindow.open(marker);
	});
	
	var markerId = gmap.addMarker(marker, true);
	var type = (product.type == 1) ? 'BÁN' : 'CHO THUÊ';
	var category = categories[product.category_id]['name'].toUpperCase();
	var price = (product.type == 1) ? product.price : product.price + '/tháng';
	
	var roomNo = '';
	if(product['adProductAdditionInfo']['room_no']) {
		roomNo = '• ' + product['adProductAdditionInfo']['room_no'] + ' phòng ngủ ';
	}
	
	var floorNo = '';
	if(product['adProductAdditionInfo']['floor_no']) {
		floorNo = '• ' + product['adProductAdditionInfo']['floor_no'] + ' tầng ';
	}
	
	var li = '<li data-detail="' + product.id +'" data-id="' + markerId + '">' +
                '<div class="bgcover wrap-img pull-left" style="background-image:url('+product.image_url+')"><a href="#" class=""></a></div>' +
                '<div class="infor-result">' +
                    '<p class="item-title">' + address + '</p>' +
                    '<p class="type-result"><em class="fa fa-circle for-rent"></em>' + category + ' ' + type + '</p>' +
                    '<p class="rice-result">' + price + '</p>' +
                    '<p class="beds-baths-sqft">' + product['area'] + 'm<sup>2</sup> ' + floorNo + roomNo + '</p>' +
                    '<p class="date-post-rent">' + product.previous_time + '</p>' +
                '</div>' +
            '</li>';
	return li;
}