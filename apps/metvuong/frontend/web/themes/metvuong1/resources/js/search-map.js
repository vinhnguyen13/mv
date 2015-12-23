var gmap = null, response = null, listResult, infoWindow;
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
	
	$('#submit-filter').click(function(){
		search(function(r){
			response = r;
			loadListing();
		});
	});
});

function search(callback) {
	var searchForm = $('#map-search-form');
	
	listResult.empty();
	$('#listing-loading').show();
	$('.pagination').remove();
	$('#no-result').hide();
	
	$.get(searchForm.attr('action'), searchForm.serialize(), function(r) {
		$('#listing-loading').hide();
		if(callback) {
			callback(r);
		}
	});
}

function start() {
	if(response && gmap) {
		gmap.click(function(){
			$('#detail-wrap').css({
				left: '0px'
			});
		});
		
		infoWindow = new InfoWindow();
		
		$('#detail-wrap').on('click', '.close', function(){
			$('#detail-wrap').css({
				left: '0px'
			});
		});
		
		var hoverTimeout;
		listResult.on('mouseenter', '> li', function() {
			var self = $(this);
			
			hoverTimeout = setTimeout(function(){
				if(self.hasClass('onmap'))
					return;
				var marker = gmap.getMarker(self.data('id'));

				if(marker) {
					marker.setIcon('/images/marker-hover.png');
					marker.setZIndex(google.maps.Marker.MAX_ZINDEX++);
					if(!gmap.getBounds().contains(marker.getPosition())) {
						gmap.setCenter(marker.getPosition());
					}
				}
			}, 200);
		}).on('mouseleave', '> li', function() {
			clearTimeout(hoverTimeout);
			
			var self = $(this);
			if(self.hasClass('onmap'))
				return;
			var marker = gmap.getMarker(self.data('id'));
			marker.setIcon('/images/marker.png');
		});
		
		listResult.on('click', '> li', function(){
			var self = $(this);
			
			$('#map-loading').height($('.cd-main-content').height()).show();
			
			$.get('/ad/detail', {id: $(this).data('detail'), isCraw: $(this).data('is-craw')}, function(response){
				$('#map-loading').hide();
				var width = $('.wrap-map-result').width();
				width = (width > 820) ? 820 : width;
				
				$('#detail-wrap').css({
					width: width,
					left: '-' + width + 'px',
					height: $('.result-items').height()
				});
				$('#detail-listing').html($(response).html());
				

				if(!self.data('clone')) {
					var marker = gmap.getMarker(self.data('id'));
					if(marker) {
						var position = marker.getPosition();
						gmap.setCenter({lat: position.lat(), lng: position.lng()});
					}
				}
				
				$('.gallery-detail').imagesLoaded()
				 	.always( function( instance ) {
						if($('#detail-listing .bxslider').find('.wrap-img-detail').length > 0) {
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
					 		},500);
						}
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
		
		$('#moi-nhat').on('click', '.pagination a', function(e){
			e.preventDefault();
			
			var self = $(this);
			
			if($(this).parent().hasClass('active')) {
				return false;
			}
			
			gmap.removeAllMarker();
			
			$.get(self.attr('href'), {}, function(r){
				response = r;
				listResult.empty();
				loadListing();
			});
		});
		
		loadListing();
	}
}

function loadListing() {
	var list = '';
	var count = 0;
	
	if(response.productResponse.length > 0) {
		for(index in response.productResponse) {
			var product = response.productResponse[index];
			list += makeMarker(product);
			count++;
		}
		
		$('#count-listing').text(count);
		listResult.append(list);
		
		$('.pagination').remove();
		$('#moi-nhat').append(response.pages);
	} else {
		$('#no-result').show();
	}
}

function makeMarker(product) {
	var city = dataCities[product.city_id];
	var district = city['districts'][product.district_id];
	var address = '';
	
	if(product.home_no) {
		address += product.home_no + ', ';
	}
	
	if(product.street_id) {
		var street = district['streets'][product.street_id];
		address = address + street.pre + ' ' + street.name + ', ';
	}
	
	if(product.ward_id) {
		var ward = district['wards'][product.ward_id];
		address = address + ward.pre + ' ' + ward.name + ', ';
	}
	
	if(address == '') {
		address = district.pre + ' ' + district.name + ', ' + city.name;
	} else {
		address = address + ', ' + district.pre + ' ' + district.name + ', ' + city.name;
	}

	var marker = new Marker({
		draggable: false,
	    position: {lat: Number(product.lat), lng: Number(product.lng)},
	    icon: '/images/marker.png',
	    zIndex: google.maps.Marker.MAX_ZINDEX++
	});
	
	marker.click(function(latLng){
		var id = marker.getId();
		var listEl = $('#moi-nhat').clone(true).removeAttr('id');
		listEl.find('.pagination').remove();
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
	
	var toiletNo = '';
	if(product['toilet_no']) {
		toiletNo = '• ' + product['toilet_no'] + ' Phòng tắm ';
	}
	
	var roomNo = '';
	if(product['room_no']) {
		roomNo = '• ' + product['room_no'] + ' phòng ngủ ';
	}
	
	var floorNo = '';
	if(product['floor_no']) {
		floorNo = '• ' + product['floor_no'] + ' tầng ';
	}
	
	
	
	var li = '<li data-is-craw="' + product.is_craw +'" data-detail="' + product.id +'" data-id="' + markerId + '">' +
                '<div class="bgcover wrap-img pull-left" style="background-image:url('+product.image_url+')"><a href="#" class=""></a></div>' +
                '<div class="infor-result">' +
                    '<p class="item-title">' + address + '</p>' +
                    '<p class="type-result"><em class="fa fa-circle for-rent"></em>' + category + ' ' + type + '</p>' +
                    '<p class="rice-result">' + price + '</p>' +
                    '<p class="beds-baths-sqft">' + product['area'] + 'm<sup>2</sup> ' + floorNo + roomNo + toiletNo + '</p>' +
                    '<p class="date-post-rent">' + product.previous_time + '</p>' +
                '</div>' +
            '</li>';
	return li;
}