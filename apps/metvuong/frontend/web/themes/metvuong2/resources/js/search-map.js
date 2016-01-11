var gmap = null, response = null, listResult, infoWindow, savedTab = false, closeInfowindow, dragging = false, infoGroupWindow;
var page = 1;
var limit = 20;
var ZOOM_CHANGE_LIMIT = 13;

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
	
	$('.tab-content').scroll(function() {
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
		
		var bermudaTriangles = [];
		gmap.zoomChanged(function(){
			if(gmap.getOriginal().getZoom() <= ZOOM_CHANGE_LIMIT) {
				if(gmap.status == 1) {
					gmap.hideAllMarker();

					if($.isEmptyObject(gmap.getGroupMakers())) {
						var markers = gmap.getMarkers();
						for(index in markers) {
							var marker = markers[index];
							var district = districts[marker.data.districtId];
							district.counter = Number(district.counter) + Number(marker.counter);
						}

						for(index in districts) {
							var district = districts[index];
							if(district.counter > 0) {
								var lngLat = JSON.parse(district.geometry);
								
								if(lngLat) {
									var triangleCoords = [];
									var bounds = new google.maps.LatLngBounds();
									for(index in lngLat) {
										var ll = lngLat[index].split(',');
										var a = new google.maps.LatLng(Number(ll[0]), Number(ll[1]));
										triangleCoords.push(a);
										bounds.extend(a);
									}
									var bermudaTriangle = new google.maps.Polygon({
									    paths: triangleCoords,
									    strokeColor: '#FF0000',
									    strokeOpacity: 0.5,
									    strokeWeight: 2,
									    fillColor: '#FF0000',
									    fillOpacity: 0.2
									  });
									bermudaTriangle.setMap(gmap.getOriginal());
									bermudaTriangles.push(bermudaTriangle);
									
									var firstLatLng = new google.maps.LatLng(bounds.getCenter().lat(), bounds.getCenter().lng());
									var option = {
										draggable: false,
									    position: firstLatLng,
									    icon: '/images/marker.png'
									};
									if(district.counter > 1) {
										option.icon = '/site/map-image?s=0&t=' + district.counter;
									}
									var markerGroup = new Marker(option);
									gmap.addMarkerGroup(markerGroup);
									addEvent(markerGroup, district);
								}
							}
						}
					} else {
						var markers = gmap.getGroupMakers();
						for(index in markers) {
							var marker = markers[index];
							marker.setMap(gmap);
						}
						
						for(index in bermudaTriangles) {
							bermudaTriangles[index].setMap(gmap.getOriginal());
						}
					}
				}
			} else {
				if(gmap.status == 0) {
					gmap.showAllMarker();
					
					for(index in bermudaTriangles) {
						bermudaTriangles[index].setMap(null);
					}
				}
			}
		});

		infoWindow = new InfoWindow({disableAutoPan: true});
		infoGroupWindow = new InfoWindow({disableAutoPan: true});
		
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
		
		function addEvent(marker, district) {
			marker.mouseover(function(latLng) {
				var el = $('<div style="font-weight: bolder; padding-left: 8px;">' + district.name + '</div>');
				infoWindow.setContent(el.get(0));
				infoWindow.open(marker);
				
				var gmStyle = el.closest('.gm-style-iw');
				gmStyle.css({
					overflow: 'visible'
				});
				el.parent().css({
				    marginLeft: '-5px',
			    	marginTop: '-8px',
			    	marginBottom: '-10px',
			        marginRight: '-20px',
			        overflow: 'visible'
				});
				gmStyle.next().hide();
			});
			marker.mouseout(function(latLng) {
				infoWindow.close();
			});
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

        // share-item click event
        $(document).on('click', '.share-item-1', function() {
            var _this = $(this);
            $('#share_form_1 input[type=text]').val(null);
            $('#share_form_1 textarea').val(null);
            var _address = $('#share_form_1 ._address');
            var _detailUrl = $('#share_form_1 ._detailUrl');
            var _image = $('#share_form_1 ._image');
            if(_address != null && _detailUrl != null){
                _address.val(_this.attr("data-address"));
                _detailUrl.val(_this.attr("data-url"));
                _image.val(_this.attr("data-img"));
            }
        });

        $(document).on('click', '#share_form_1 button.send_mail', function(){
            var recipient_email = $('#share_form_1 .recipient_email').val();
            var your_email = $('#share_form_1 .your_email').val();
            if(recipient_email != null && your_email != null) {
                $('#box-share-1').modal('hide');
                clearTimeout(timer);
                timer = setTimeout(function () {
                    $.ajax({
                        type: "post",
                        dataType: 'json',
                        url: $('#share_form_1').attr('action'),
                        data: $('#share_form_1').serializeArray(),
                        success: function (data) {
                            if(data.status == 200){

                            }
                            else {
                                var strMessage = '';
                                $.each(data.parameters, function(idx, val){
                                    var element = 'share_form_1_'+idx;
                                    strMessage += "\n" + val;
                                });
                                alert(strMessage+"\nTry again");
                            }
                        },
                        error: function () {
                            var strMessage = '';
                            $.each(data.parameters, function(idx, val){
                                var element = 'change-pass-form-'+idx;
                                strMessage += "\n" + val;
                            });
                            alert(strMessage);
                        }
                    });
                }, 1000);
            }
            return false;
        });

        // share product link to facebook
        $(document).on('click', '#share_form_1 a.fb-icon', function() {
            var detailUrl = $('#share_form_1 ._detailUrl').val();
            if(detailUrl == null || detailUrl == '' )
                detailUrl = $('#share_form_1 ._domain').val();

            FB.ui({
                method: 'share',
                href: detailUrl
            }, function(response){});
        });

        $(document).on('click', '#share_form_1 a.twe-icon', function() {
            location.href='skype:nhuttranm?chat';
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

					//$(document).trigger('gallery-load', [{data: '1'}, 'something']);

					$('.gallery-detail').imagesLoaded()
				 	.always( function( instance ) {
				 		
				 		$('#map-loading').hide();
						if($('#detail-listing .bxslider').find('.wrap-img-detail').length > 0) {
							
							$('#detail-listing .bxslider').bxSlider({
								moveSlides: 1,
				                startSlide: 0,
				                startSlide: 0,
				                onSliderLoad: function() {
				                    this.infiniteLoop = false;
				                    this.hideControlOnEnd = true;
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
		
		marker.mouseout(function(latLng){
			if(dragging)
				return;
			
			closeInfowindow = setTimeout(function(){
				infoWindow.close();
			}, 400);
		});
		
		marker.mousedown(function(){
			infoWindow.close();
		});
		
		gmap.addMarker(marker);
		
		marker.data.districtId = product.district_id;
	}
	
	var type = (product.type == 1) ? 'BÁN' : 'CHO THUÊ';
	var category = categories[product.category_id]['name'].toUpperCase();
	var price = (product.type == 1) ? product.price : product.price + '/tháng';
	
	var toiletNo = '';
	if(product['toilet_no'] && product['toilet_no'] != 0) {
		toiletNo = '• ' + product['toilet_no'] + ' Phòng tắm ';
	}
	
	var roomNo = '';
	if(product['room_no'] && product['room_no'] != 0) {
		roomNo = '• ' + product['room_no'] + ' phòng ngủ ';
	}
	
	var floorNo = '';
	if(product['floor_no'] && product['floor_no'] != 0) {
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
	
	listEl.hover(function(){
		clearTimeout(closeInfowindow);
	}, function(){
		closeInfowindow = setTimeout(function(){
			infoWindow.close();
		}, 400);
	});
	
	listEl.find('.' + id).addClass('onmap').show();
	listEl.find('li').not(listEl.find('.' + id)).remove();

	listEl.width(168);
	listEl.find('.list-results').width(168 + 51);
	
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
}