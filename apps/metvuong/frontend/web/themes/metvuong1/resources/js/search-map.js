var gmap = null, response = null, listResult, infoWindow, savedTab = false, closeInfowindow, dragging = false;
var page = 1;
var limit = 20;

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
	
	$('#reset-filter').click(function(){
		search(function(r){
			response = r;
			loadListing();
		});
	});
	
	$('.wrap-col-fixed-result').scroll(function() {
		var self = $(this);
	    clearTimeout($.data(this, 'scrollTimer'));
	    $.data(this, 'scrollTimer', setTimeout(function() {
	    	if(self.scrollTop() >= (self.get(0).scrollHeight - 100 - self.outerHeight())) {
	    	       loadPage();
	    	}
	    }, 250));
	});
});

function search(callback) {
	page = 1;
	
	var searchForm = $('#map-search-form');
	
	listResult.empty();
	$('#listing-loading').show();
	$('.pagination').remove();
	$('#no-result').hide().text('Chưa có tòa nhà nào được đăng như tìm kiếm của bạn.');
	
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

			setTimeout(function(){
				$('#detail-listing').html('');
			}, 300);
		});
		
		gmap.dragstart(function(){
			dragging = true;
		});
		gmap.dragend(function(){
			dragging = false;
		});
		
		infoWindow = new InfoWindow({disableAutoPan: true});
		
		$('#detail-wrap').on('click', '.btn-close-detail', function(){
			$('#detail-wrap').css({
				left: '0px'
			});
			setTimeout(function(){
				$('#detail-listing').html('');
			}, 300);
		});

		var timer = 0;

		$('#moi-nhat').on('click', '.icon-hear', function(e){
			e.preventDefault();
			var self = $(this);
			
			addToFavorite(self);
			
			if($('#detail-listing').data('id') == self.data('id')) {
				$('#detail-listing').find('.icon-hear').attr('class', self.attr('class'));
			}
			
//			if(savedTab && !self.hasClass('active')) {
//				var li = self.closest('li');
//				gmap.removeMarker(gmap.getMarker(li.data('id')));
//				li.remove();
//			}
		});
		
		function addToFavorite(_this) {
			if(isGuest) {
				$('#frmLogin').modal('show');
			} else {
				var _id = _this.attr('data-id');
				var callUrl = _this.attr('data-url');
				_this.toggleClass('active');
				var _stt = (_this.hasClass('active')) ? 1 : 0;
				clearTimeout(timer);
				timer = setTimeout(function() {
					$.ajax({
						type: "post",
						dataType: 'json',
						url: callUrl,
						data: {id: _id, stt: _stt},
						success: function(data) {
							if(_stt) {
								saved.push(Number(_id));
							} else {
								var index = saved.indexOf(Number(_id));
								if (index > -1) {
									saved.splice(index, 1);
								}
							}
						}
					});
				}, 1000);
			}
		}

		$(document).on('click', '.rating .rate input', function(e){
			var _this = $(this);
			var _core = _this.val();
			var _url = _this.closest('.rating').attr('data-url');
			var _id = $('.detail-post').attr('data-id');
			console.log(_url);
			if(_core != 0 && _url){
				clearTimeout(timer);
				timer = setTimeout(function() {
					$.ajax({
						type: "post",
						dataType: 'json',
						url: _url,
						data: {id: _id, core: parseInt(_core)},
						success: function(data) {
							if(data.parameters.data){
								_this.closest('.rating').find(":radio[value="+data.parameters.data+"]").prop('checked',true);
								console.log(data.parameters.data);
							}
						}
					});
				}, 1000);
			}
		});


		$(document).on('click', '.report_modal', function(e){
			e.preventDefault();
			var _this = $(this);
			var _user_id = _this.attr('data-uid');
			if(_user_id == 0) {
				$('#frmLogin').modal('show');
				//$('#report-listing').modal('hide');
			} else {
				var _url = _this.attr('data-url');
				console.log(_url);
				clearTimeout(timer);
				timer = setTimeout(function () {
					$.ajax({
						type: "post",
						dataType: 'json',
						url: _url,
						data: {user_id: _user_id},
						success: function (data) {
							if(data.statusCode == 200){

							} else if(data.statusCode == 404){
								alert(data.parameters.msg);
							}
						}
					});
				}, 1000);
			}
		});

		$(document).on('click', '#frm-report .send_report', function(e){
			e.preventDefault();
			var _this = $(this);
			var _user_id = $('#frm-report #uid').val();
			if(_user_id != 0) {
				clearTimeout(timer);
				timer = setTimeout(function () {
					$.ajax({
						type: "post",
						dataType: 'json',
						url: $('#frm-report').attr('action'),
						data: $('#frm-report').serializeArray(),
						success: function (data) {
							$('#report-listing').modal('hide');
							if(data == 200){
								alert("Your message is sent.\nThank you.");
							}
							else if(data == 13){
								alert("You reported with us");
							}
							else {
								alert("Please, try again!");
							}
						},
						error: function () {
							$('#report-listing').modal('hide');
						}
					});
				}, 500);
			}
			return false;
		});

		
		var hoverTimeout;
		listResult.on('mouseenter', '> li', function() {
			var self = $(this);
			
			hoverTimeout = setTimeout(function(){
				if(self.hasClass('onmap'))
					return;
				var marker = gmap.getMarker(self.attr('class'));

				if(marker) {
					if(marker.counter == 1) {
						marker.setIcon('/images/marker-hover.png');
					} else {
						marker.setIcon('/site/map-image?s=1&t=' + marker.counter);
					}
					
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
			var marker = gmap.getMarker(self.attr('class'));
			if(marker.counter == 1) {
				marker.setIcon('/images/marker.png');
			} else {
				marker.setIcon('/site/map-image?s=0&t=' + marker.counter);
			}
		});
		
		listResult.on('click', '> li', function(e){
			if(!$(e.target).hasClass('icon-hear') && $(e.target).closest('.icon-hear').length == 0) {
				var self = $(this);
				
				// infoWindow.close();
				
				$('#map-loading').show();

				var width = $('.wrap-map-result').width();
				width = (width > 820) ? 820 : width;
				
				$('#detail-wrap').css({
					width: width,
					left: '-' + width + 'px',
					height: $('.result-items').height()
				});
				
				var id = $(this).data('detail');
				
				$.get('/ad/detail', {id: id}, function(response){
					
					
					var res = $(response);
					
					var index = saved.indexOf(Number(id));
					if (index > -1) {
						res.find('.icon-hear').addClass('active');
					}
					
					$('#detail-listing').data('id', id).html(res.html());
					
					$('#detail-listing').find('.icon-hear').click(function(e){
						e.preventDefault();
						addToFavorite($(this));
						
						self.find('.icon-hear').attr('class', $(this).attr('class'));
					});

//					if(!self.data('clone')) {
//						var marker = gmap.getMarker(self.data('id'));
//						if(marker) {
//							var position = marker.getPosition();
//							gmap.setCenter({lat: position.lat(), lng: position.lng()});
//						}
//					}
					
					$('.gallery-detail').imagesLoaded()
					 	.always( function( instance ) {
					 		
					 		$('#map-loading').hide();
							if($('#detail-listing .bxslider').find('.wrap-img-detail').length > 0) {
								
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
								$('.gallery-detail').css('visibility', 'visible');
							}
					 	});

					$('.tabs-detail-item li .sub-more').dropdown();
				});
			}
		});
		
		$('#order-by-tab a').click(function(e){
			
			$('.btn-close-detail').trigger('click');
			
			if($(this).data('href')) {
				savedTab = true;
				listResult.empty();
				$('#listing-loading').show();
				$('.pagination').remove();
				$('#no-result').hide().text('Chưa có tòa nhà nào được lưu.');
				$('#map-search-form').css('visibility', 'hidden');
				$.get($(this).data('href'), {}, function(r) {
					$('#listing-loading').hide();
					page = 1;
					response = r;
					loadListing();
				});
			} else {
				savedTab = false;
				$('#order-by').val($(this).data('order'));
				$('#map-search-form').css('visibility', 'visible');
				search(function(r){
					response = r;
					loadListing();
				});
			}
		});
		
		loadListing();
	}
}

function loadListing() {
	var list = '';
	gmap.removeAllMarker();
	if(response.productResponse.length > 0) {
		for(index in response.productResponse) {
			var product = response.productResponse[index];
			list += makeMarker(product);
		}
		
		$('#count-listing').text(response.total);
		listResult.append(list);
		
		var marker = gmap.getMarker(listResult.find('> li:last-child').attr('class'));
		gmap.setCenter(marker.getPosition());
		
		loadPage();
		
		$('.pagination').remove();
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
		address = address + ' ' + district.pre + ' ' + district.name + ', ' + city.name;
	}
	
	var markerId = latLngToClass(product.lat, product.lng);
	var marker;
	
	if(marker = gmap.getMarker(markerId)) {
		marker.setIcon('/site/map-image?s=0&t=' + ++marker.counter);
	} else {
		marker = new Marker({
			draggable: false,
		    position: {lat: Number(product.lat), lng: Number(product.lng)},
		    icon: '/images/marker.png'
		}, latLngToClass(product.lat, product.lng));
		
		marker.mouseover(function(latLng) {
			showinfo(latLng, marker);
		});
		
		marker.mouseup(function(latLng) {
			showinfo(latLng, marker);
		});
		
//		marker.mouseout(function(latLng){
//			if(dragging)
//				return;
//			
//			closeInfowindow = setTimeout(function(){
//				infoWindow.close();
//			}, 400);
//		});
		
		marker.mousedown(function(){
			infoWindow.close();
		});
		
		gmap.addMarker(marker);
	}
	
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

	var className = 'icon-hear';
	
	for(i = 0; i < saved.length; i++) {
		if(product.id == saved[i]) {
			className += ' active';
			break;
		}
	}
	
	var li = '<li style="display: none;" data-detail="' + product.id +'" class="' + markerId + '">' +
                '<div class="bgcover wrap-img pull-left" style="background-image:url('+product.image_url+')"><a href="#" class=""></a></div>' +
                '<div class="infor-result">' +
                    '<p class="item-title">' + address + '</p>' +
                    '<p class="type-result">' + category + ' ' + type + '</p>' +
                    '<p class="rice-result">' + price + '</p>' +
                    '<p class="beds-baths-sqft">' + product['area'] + 'm<sup>2</sup> ' + floorNo + roomNo + toiletNo + '</p>' +
                    '<p class="date-post-rent">' + product.previous_time + '</p>' +
                    '<div class="icon-item-listing"><a title="Lưu" class="' + className + '" data-id="' + product.id + '" data-url="/ad/favorite"><em class="fa fa-heart-o"></em></a></div>' +
                '</div>' +
            '</li>';
	
	return li;
}

function loadPage() {
	var offset = (page - 1) * limit;
	
	$('.list-results').children().slice(offset, offset+limit).show();
	
	page++;
}

function latLngToClass(lat, lng) {
	return 'p' + lat.replace('.', '_') + '-' + lng.replace('.', '_');
}

function showinfo(latLng, marker){
	if(dragging)
		return;
	clearTimeout(closeInfowindow);
	var id = marker.getId();
	var listEl = $('#moi-nhat').clone(true).attr('id', 'moi-nhat-onmap').addClass('moi-nhat-onmap');
	
//	listEl.hover(function(){
//		clearTimeout(closeInfowindow);
//	}, function(){
//		closeInfowindow = setTimeout(function(){
//			infoWindow.close();
//		}, 400);
//	});
	
	listEl.find('.' + id).addClass('onmap').show();
	listEl.find('li').not(listEl.find('.' + id)).remove();

	infoWindow.setContent(listEl.get(0));
	infoWindow.open(marker);
	
	marker.setZIndex(google.maps.Marker.MAX_ZINDEX++);
	
	var gmStyle = listEl.closest('.gm-style-iw');
	gmStyle.css({
		overflow: 'visible'
	});
	listEl.parent().css({
	    marginLeft: '-5px',
    	marginTop: '-8px',
    	marginBottom: '-10px',
        marginRight: '-20px',
        overflow: 'visible'
	});
	gmStyle.next().hide();
	gmStyle.parent().width(300);
}