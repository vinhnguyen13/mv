var objMap = [], 
	objItem = {
		id_area : '',
		coordinates : 0,
		//urlImage : '',
		nameImage : ''
	}, 
	arrCoordinates = [],
	countItemMap = 0,
	imgReGet;
$.fn.tabSwitch = function(tabContent) {
	var tabs = this;
	
	return this.each(function() {
		var self = $(this);
		
		self.click(function(e){
			e.preventDefault();

			if(!self.hasClass('active')) {
				var currentActive = tabs.filter('.active').removeClass('active');
				tabContent.eq(tabs.index(currentActive)).removeClass('active');
				
				self.addClass('active');
				tabContent.eq(tabs.index(self)).addClass('active');
			}
		});
    });
};

$.fn.groupDropdown = function(closeBefore) {
	var group = this;
	
	return this.each(function() {
		var self = $(this);
		
		self.click(function(e){
			e.preventDefault();

			var sub = $(this).parent().find('> ul');
			
			if(self.hasClass('active')) {
				self.removeClass('active');
				
				sub.height(0);
			} else {
				if(closeBefore) {
					var before = group.filter('.active').removeClass('active');
					before.parent().find('> ul').height(0);
				}
				
				self.addClass('active');
				
				sub.height(sub.get(0).scrollHeight);
			}
		});
    });
}

$.fn.checkbox = function() {
	this.each(function(){
		var self = $(this);
		
		if(self.prop('checked')) {
			var vCheck = $('<span class="vcheck checked"></span>');
		} else {
			var vCheck = $('<span class="vcheck"></span>');
		}
		
		self.after(vCheck);
		
		if(self.prop('disabled')) {
			vCheck.addClass('disabled');
		} else {
			self.change(function(){
				if(self.prop('checked')) {
					vCheck.addClass('checked');
				} else {
					vCheck.removeClass('checked');
				}
			})
			
			vCheck.parent().hover(function(){
				vCheck.addClass('hover');
			}, function(){
				vCheck.removeClass('hover');
			});
		}
	});
}

var buildingProject = {
	initForm: function() {
		this.formSubmit();
		
		$('.show-content').tabSwitch($('.bp-fields > li'));
		$('.bp-subcontents > a').groupDropdown(true);
		
		$('#btn-add-progress').click(function(){
			var progressList = $('#progress-list');
			var total = Number($(this).data('count'));
			
			$(this).data('count', total + 1);
			
			$('#progress-list').append('<div class="panel panel-default"> <div class="panel-body"> <i class="glyphicon glyphicon-remove"></i><div class="form-group"> <label class="control-label" for="buildingproject-bpvideo">Tháng / Năm</label> <div> <select class="form-control" name="BuildingProject[progress][' + total + '][month]" style="width: auto; display: inline-block;"> <option value="1">1</option> <option value="2">2</option> <option value="3">3</option> <option value="4">4</option> <option value="5">5</option> <option value="6">6</option> <option value="7">7</option> <option value="8">8</option> <option value="9">9</option> <option value="10" selected="">10</option> <option value="11">11</option> <option value="12">12</option> </select> <select class="form-control" name="BuildingProject[progress][' + total + '][year]" style="width: auto; display: inline-block;"> <option value="1998">1998</option> <option value="1999">1999</option> <option value="2000">2000</option> <option value="2001">2001</option> <option value="2002">2002</option> <option value="2003">2003</option> <option value="2004">2004</option> <option value="2005">2005</option> <option value="2006">2006</option> <option value="2007">2007</option> <option value="2008">2008</option> <option value="2009">2009</option> <option value="2010">2010</option> <option value="2011">2011</option> <option value="2012">2012</option> <option value="2013">2013</option> <option value="2014">2014</option> <option value="2015" selected="">2015</option> <option value="2016">2016</option> <option value="2017">2017</option> <option value="2018">2018</option> <option value="2019">2019</option> <option value="2020">2020</option> </select> </div> <div class="help-block"></div> </div> <div class="form-group"> <label class="control-label" for="buildingproject-bpvideo">Ảnh</label> <!-- The template to display files available for upload --> <script id="template-upload" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %} <li class="template-upload fade"> <span class="preview"></span> <p class="name">{%=file.name%}</p> <strong class="error text-danger"></strong> <p class="size">Processing...</p> <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div> </li> {% } %} </script><!-- The template to display files available for download --> <script id="template-download" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %} <li class="template-download fade"> <span class="preview"> {% if (file.thumbnailUrl) { %} <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a> {% } %} </span> <p class="name"> {% if (file.url) { %} <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?\'data-gallery\':\'\'%}>{%=file.name%}</a> {% } else { %} <span>{%=file.name%}</span> {% } %} </p> {% if (file.error) { %} <div><span class="label label-danger">Error</span> {%=file.error%}</div> {% } %} <span class="size">{%=o.formatFileSize(file.size)%}</span> {% if (file.deleteUrl) { %} <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields=\'{"withCredentials":true}\'{% } %}> <i class="glyphicon glyphicon-trash"></i> <span>Delete</span> </button> {% } else { %} <button class="btn btn-warning cancel"> <i class="glyphicon glyphicon-ban-circle"></i> <span>Cancel</span> </button> {% } %} </li> {% } %} </script> <!-- The file upload form used as target for the file upload widget --> <div id="w' + total + '-fileupload-fileupload" data-upload-template-id="template-upload" data-download-template-id="template-download"> <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload --> <div class="row fileupload-buttonbar"> <div class="col-lg-7"> <!-- The fileinput-button span is used to style the file input field as button --> <span class="btn btn-success fileinput-button"> <i class="glyphicon glyphicon-plus"></i> <span>Chọn ảnh...</span> <input type="hidden" name="BuildingProject[progress][' + total + '][images]"><input type="file" id="w' + total + '-fileupload" name="upload" multiple=""> </span> <a class="btn btn-primary start"> <i class="glyphicon glyphicon-upload"></i> <span>Start upload</span> </a> <a class="btn btn-warning cancel"> <i class="glyphicon glyphicon-ban-circle"></i> <span>Cancel upload</span> </a> <a class="btn btn-danger delete"> <i class="glyphicon glyphicon-trash"></i> <span>Delete</span> </a> <input type="checkbox" class="toggle"> <!-- The global file processing state --> <span class="fileupload-process"></span> </div> <!-- The global progress state --> <div class="col-lg-5 fileupload-progress fade"> <!-- The global progress bar --> <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"> <div class="progress-bar progress-bar-success" style="width:0%;"></div> </div> <!-- The extended global progress state --> <div class="progress-extended">&nbsp;</div> </div> </div> <!-- The table listing the files available for upload/download --> <ul class="files"></ul> </div> <div class="help-block"></div> </div> </div> </div>');
		
			var el = $('#w' + total + '-fileupload-fileupload');
			
			customFileUpload.attachUploadWidget(el);
				
		});
		
		$('.dynamic-list').on('click', '.glyphicon-remove', function(){
			$(this).closest('.panel').remove();
		});
		
		$('.btn-clone').click(function(){
			var self = $(this);
			
			var floorList = self.prev();
			var total = Number(self.data('count'));
			var name = self.data('name');
			var id = name.replace('[', '-').replace(']', '-');
			
			self.data('count', total + 1);
			
			floorList.append('<div class="panel panel-default"> <div class="panel-body"> <i class="glyphicon glyphicon-remove"></i> <div class="form-group"> <label class="control-label" for="buildingproject-bpvideo">Tầng</label> <input type="text" class="form-control" name="' + name + '[floor_plan][' + total + '][title]" value=""> <div class="help-block"></div> </div> <div class="form-group"> <label class="control-label" for="buildingproject-bpvideo">Ảnh</label> <!-- The template to display files available for upload --> <script id="template-upload" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %} <li class="template-upload fade"> <span class="preview"></span> <p class="name">{%=file.name%}</p> <strong class="error text-danger"></strong> <p class="size">Processing...</p> <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div> </li> {% } %} </script><!-- The template to display files available for download --> <script id="template-download" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %} <li data-delete-later="{%=file.deleteLater%}" class="template-download fade"> <span class="preview"> {% if (file.thumbnailUrl) { %} <a target="_blank" href="{%=file.url%}" title="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a> {% } %} </span> <p class="name"> {% if (file.url) { %} <a target="_blank" href="{%=file.url%}" title="{%=file.name%}" {%=file.thumbnailUrl?\'data-gallery\':\'\'%}>{%=file.name%}</a> {% } else { %} <span>{%=file.name%}</span> {% } %} </p> {% if (file.error) { %} <div><span class="label label-danger">Error</span> {%=file.error%}</div> {% } %} <span class="size">{%=o.formatFileSize(file.size)%}</span> {% if (file.deleteUrl) { %} <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields=\'{"withCredentials":true}\'{% } %}> <i class="glyphicon glyphicon-trash"></i> <span>Delete</span> </button> {% } else { %} <button class="btn btn-warning cancel"> <i class="glyphicon glyphicon-ban-circle"></i> <span>Cancel</span> </button> {% } %} </li> {% } %} </script> <!-- The file upload form used as target for the file upload widget --> <div id="' + id + total + '-fileupload-fileupload" class="map-area" data-callback="buildingProject.makeMapArea" data-upload-template-id="template-upload" data-download-template-id="template-download"> <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload --> <div class="row fileupload-buttonbar"> <div class="col-lg-7"> <!-- The fileinput-button span is used to style the file input field as button --> <span class="btn btn-success fileinput-button"> <i class="glyphicon glyphicon-plus"></i> <span>Chọn ảnh...</span> <input type="hidden" name="' + name + '[floor_plan][' + total + '][images]"><input type="file" id="' + id + total + '-fileupload" name="upload"> </span> <a class="btn btn-primary start"> <i class="glyphicon glyphicon-upload"></i> <span>Start upload</span> </a> <a class="btn btn-warning cancel"> <i class="glyphicon glyphicon-ban-circle"></i> <span>Cancel upload</span> </a> <a class="btn btn-danger delete"> <i class="glyphicon glyphicon-trash"></i> <span>Delete</span> </a> <input type="checkbox" class="toggle"> <!-- The global file processing state --> <span class="fileupload-process"></span> </div> <!-- The global progress state --> <div class="col-lg-5 fileupload-progress fade"> <!-- The global progress bar --> <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"> <div class="progress-bar progress-bar-success" style="width:0%;"></div> </div> <!-- The extended global progress state --> <div class="progress-extended">&nbsp;</div> </div> </div> <!-- The table listing the files available for upload/download --> <ul class="files"></ul> </div> <div class="help-block"></div> </div> </div> </div>');
			
			var el = $('#' + id + total + '-fileupload-fileupload');
			customFileUpload.attachUploadWidget(el, true);
		});
	},
	formSubmit: function() {
		$('#project-building-form .btn-block').click(function(e){
			e.preventDefault();
			
			var loading = $('<div class="loading"></div>');
			$('body').append(loading);
			for (instance in CKEDITOR.instances) {
				CKEDITOR.instances[instance].updateElement();
			}
			
			var form = $('#bp-form');
			var url = form.attr('action');
			var data = form.serialize();
			
			$.post(url, data, function(response){
				loading.remove();
				
				if(response.success) {
					location.href = response.redirect;
				} else {
					for(error in response.errors) {
						var parent = $('#buildingproject-' + error.toLowerCase()).closest('.form-group');
						parent.addClass('has-error').find('.help-block').text(response.errors[error]);
					}
				}
			});
		});
	},
	initView: function() {
		$('.gal').colorbox({
			current: function() {
				return $(this).data('current');
			},
			rel: function() {
				return 'gal';
			},
			href: function() {
				return $(this).data('href')
			}
		});
		$('.video').colorbox({current: '', rel: 'video', iframe:true, innerWidth:640, innerHeight:390});
		$('#map-view').colorbox({
			inline: true,
			href: $('#map'),
			width: '920px',
			height: '490px'
		});
		$('.content-popup').colorbox({
			inline: true,
			href: function() {
				var content = $(this).data('content');
				return $('<div style="max-width: 800px;">' + content + '</div>');
			}
		});
	},
	makeMapArea: function(action, event, data, ins) {
		if(action == "done") {
			setTimeout(function(){
				buildingProject.customColorbox($(event.target).find('>.files >li >.preview >a:not(.cboxElement)'), true);
			}, 210);
		} else {
			$(ins).data('images-detail', '').removeAttr('images-coordinate');
		}
	},
	customColorbox: function(els, open) {
		els.each(function(){
			countItemMap += 1;
			arrCoordinates = [];
			$('body').append('<input id="coordsText-'+countItemMap+'" class="effect" name="" type="hidden" value="">');
			var el = $(this);
			var superParent = el.closest('.map-area');

			var img = $('<img src="' + el.attr('href') + '" class="main-map" id="imgmapMainImage-'+countItemMap+'" alt="" usemap="#map-'+countItemMap+'" />');
			var wrapImgCanvas = $('<div data-map-id="'+countItemMap+'" id="mapContainer-'+countItemMap+'" class="effect mapContainer"><h3></h3><map name="map-'+countItemMap+'" class="map" id="map-'+countItemMap+'"></map></div>');
			wrapImgCanvas.append(img);
			var popup = $('<div></div>').append(wrapImgCanvas);
			var popupHide = $('<div class="popup-hide"></div>').append(popup);

			el.after(popupHide);
			
			var name = superParent.find('.fileinput-button').find('input[type="hidden"]').attr('name').replace('[images]', '[imagesDetail][]');
			var nameId = superParent.attr('id').replace('-fileupload', '-');
			var total = (superParent.data('count')) ? Number(superParent.data('count')) : 0;
			superParent.data('count', total + 1);
			
			var htmlButtons = $('<div class="clearStyleButtons" id="clearStyleButtons-'+countItemMap+'"><p>Nhấn vào ảnh để thiết lập giá trị tọa độ.</p><div class="effect clearCurrentButton"><i class="icon icon-clear"></i> Clear Last</div><div class="effect clearAllButton"><i class="icon icon-clear"></i> Clear All</div></div>');
			popup.append(htmlButtons);

			var imageUploaded = (typeof superParent.data('images-detail') !== 'undefined') ? superParent.data('images-detail') : '';
			
			popup.append('<div class="form-group"><label class="control-label" for="buildingproject-bpvideo">Ảnh chi tiết căn hộ</label> <!-- The template to display files available for upload --> <script id="template-upload" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %} <li class="template-upload fade"> <span class="preview"></span> <p class="name">{%=file.name%}</p> <strong class="error text-danger"></strong> <p class="size">Processing...</p> <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div> </li> {% } %} </script> <!-- The template to display files available for download --> <script id="template-download" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %} <li data-delete-later="{%=file.deleteLater%}" class="template-download fade"> <span class="preview"> {% if (file.thumbnailUrl) { %} <a target="_blank" href="{%=file.url%}" title="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a> {% } %} </span> <p class="name"> {% if (file.url) { %} <a target="_blank" href="{%=file.url%}" title="{%=file.name%}" {%=file.thumbnailUrl?\'data-gallery\':\'\'%}>{%=file.name%}</a> {% } else { %} <span>{%=file.name%}</span> {% } %} </p> {% if (file.error) { %} <div><span class="label label-danger">Error</span> {%=file.error%}</div> {% } %} <span class="size">{%=o.formatFileSize(file.size)%}</span> {% if (file.deleteUrl) { %} <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields=\'{"withCredentials":true}\'{% } %}> <i class="glyphicon glyphicon-trash"></i> <span>Delete</span> </button> {% } else { %} <button class="btn btn-warning cancel"> <i class="glyphicon glyphicon-ban-circle"></i> <span>Cancel</span> </button> {% } %} </li> {% } %} </script> <!-- The file upload form used as target for the file upload widget --> <div id="' + nameId + total + '-fileupload-fileupload" data-upload-template-id="template-upload" data-download-template-id="template-download" data-callback="buildingProject.floorDetail"> <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload --> <div class="row fileupload-buttonbar"> <div class="col-lg-7"> <!-- The fileinput-button span is used to style the file input field as button --> <span class="btn btn-success fileinput-button"> <i class="glyphicon glyphicon-plus"></i> <span>Chọn ảnh tương ứng...</span> <input type="hidden" name="' + name + '" value="' + imageUploaded + '"><input type="file" id="' + nameId + total + '-fileupload" name="upload"> </span> <a class="btn btn-primary start"> <i class="glyphicon glyphicon-upload"></i> <span>Start upload</span> </a> <a class="btn btn-warning cancel"> <i class="glyphicon glyphicon-ban-circle"></i> <span>Cancel upload</span> </a> <a class="btn btn-danger delete"> <i class="glyphicon glyphicon-trash"></i> <span>Delete</span> </a> <input type="checkbox" class="toggle"> <!-- The global file processing state --> <span class="fileupload-process"></span> </div> <!-- The global progress state --> <div class="col-lg-5 fileupload-progress fade"> <!-- The global progress bar --> <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"> <div class="progress-bar progress-bar-success" style="width: 0%;"></div> </div> <!-- The extended global progress state --> <div class="progress-extended">&nbsp;</div> </div> </div> <!-- The table listing the files available for upload/download --> <ul class="files"></ul> </div> <div class="help-block"></div> </div>');
			
			var elu = $('#' + nameId + total + '-fileupload-fileupload');
			customFileUpload.attachUploadWidget(elu);
			
			if(imageUploaded) {
				var files = [];
				var imageUploaded = imageUploaded.split(',');
				for(i = 0; i < imageUploaded.length; i++) {
					var thumbnail = imageUploaded[i].replace('.jpg', '.thumb.jpg');
					files.push({
						"url": "\/store\/building-project-images\/" + imageUploaded[i],
						"thumbnailUrl": "\/store\/building-project-images\/" + thumbnail,
						"name": imageUploaded[i],
						"type": "image\/jpeg",
						"size": "1",
						"deleteUrl": "\/admin\/express\/upload\/delete-image?orginal=" + imageUploaded[i] + "&thumbnail=" + thumbnail + "&deleteLater=1&folder=building-project-images",
						"deleteType": "DELETE",
						"deleteLater": 1
					});
				}
				elu.fileupload('option', 'done').call(elu, $.Event('done'), {result: {files: files}});
			}
			
			var coordinateInputValue = (typeof superParent.attr('images-coordinate') !== 'undefined') ? superParent.attr('images-coordinate') : '';
			var coordinateInput = $('<input value="' + coordinateInputValue + '" id="valCoordinate-'+countItemMap+'" type="hidden" name="' + name.replace('[imagesDetail][]', '[imagesCoordinate][]') + '" />');
			popup.append(coordinateInput);
			
			//coordinateInput
			//img
			
			img.load(function(){
				el.colorbox({
					open: open,
					inline: true,
					href: popup,
					title: false,
					onOpen: function() {
						imgReGet = popup.find('.fileupload-buttonbar input[type=hidden]').val().split(',');
						arrCoordinates = [];
						var idMap = popup.find('.mapContainer').data('mapId');
						countItemMap = idMap;
						var strArray = popup.find('#valCoordinate-'+idMap).val();
						
						if( strArray != '' ) {
							var arrInput = JSON.parse(strArray),
								typeArea = '';
							popup.find('.map').html('');
							for( var i = 0; i < arrInput.length; i++ ) {
								if( arrInput[i].length <= 4 ) {
									typeArea = 'rect';
								}else {
									typeArea = 'poly';
								}
								var areaItem = $('<area shape="'+typeArea+'" id="area'+idMap+''+(i+1)+'" class="area" coords="'+arrInput[i].toString()+'" href="#" alt="" title="">')
								popup.find('.map').append(areaItem);
								objItem = {
									coordinates: arrInput[i].toString(),
									id_area: 'area'+idMap+''+(i+1)+'',
									nameImage: imgReGet[i]
								};
								objMap.push(objItem);
							}
							hightlight(idMap);
							counter = arrInput.length;
						}
					},
					onComplete: function() {
						var idMap = popup.find('.mapContainer').data('mapId');
						var wImgCanvas = $('#imgmapMainImage-'+idMap).outerWidth(),
							hImgCanvas = $('#imgmapMainImage-'+idMap).outerHeight();
						popup.find('.main-map canvas').css({
							width: wImgCanvas+'px',
							height: hImgCanvas+'px'
						});

					},
					onClosed: function() {
						objItem = {};
						objMap = [];
						counter = 0;
						//countItemMap = $('.mapContainer').length;
					}
				});
			});

			$('#mapContainer-'+countItemMap).click(function(e) {
				setCoordinates(e, 1, countItemMap);
				e.preventDefault();
			});

			popup.find('.fileinput-button').click(function(){
				$('#coordsText-'+countItemMap).val('');
				var lenArea = $('#mapContainer-'+countItemMap).find('#map-'+countItemMap+' .area').length,
					lenImageUpload = $('#' + nameId + total + '-fileupload-fileupload').find('.files li').length;
				if( lenArea <= 0 || lenImageUpload === lenArea ) {
					alert('Bạn chưa chọn vùng Area trên hình ảnh !!!');
					return false;
				}
			});

			$('#clearStyleButtons-'+countItemMap+' .clearCurrentButton').click(function() {
				$('#coordsText-'+countItemMap).val('');
				counter = $('#mapContainer-'+countItemMap).find('map area').length - 1;
				imgReGet = popup.find('.fileupload-buttonbar input[type=hidden]').val().split(',');

				var idArea = $('#mapContainer-'+countItemMap).find('area:last').attr('id');
				for( var i = 0; i < objMap.length; i++ ) {
					if( idArea === objMap[i].id_area ) {
						var nameCurrent = objMap[i].nameImage;

						for( var j = 0; j < imgReGet.length; j++ ) {
							if( imgReGet[j] === objMap[i].nameImage ) {
								imgReGet.splice(j,1);
								popup.find('.fileupload-buttonbar input[type=hidden]').val(imgReGet.toString());
							}
						}

						removeArrCoordinates(i);

						$('#cboxContent button[data-type=DELETE]').each(function() {
							var _this = $(this),
								getUrl = _this.data('url');
							if( getUrl.search(nameCurrent) >= 0 ) {
								_this.trigger('click');
							}
						});
						hightlight(countItemMap);
						return;
					}
				}
				if( objItem.coordinates != 0 || objMap.length <= 0 ) {
					$('#mapContainer-'+countItemMap).find('area:last').remove();
				}
				hightlight(countItemMap);
			});

			$('#clearStyleButtons-'+countItemMap+' .clearAllButton').click(function() {
				$('#coordsText-'+countItemMap).val('');
				$('#mapContainer-'+countItemMap).find('map').html('');
				//$('#dots').html('');
				var lengthArea = objMap.length;
				$('#cboxContent button[data-type=DELETE]').each(function() {
					var _this = $(this);
					_this.trigger('click');
				});
				$('#mapContainer-'+countItemMap).find('map area').each(function(i) {
					var _this = $(this),
						idArea = _this.attr('id');
					for( var j = 0; j < objMap.length; j++ ) {
						if( objMap[j].id_area == idArea ) {
							removeArrCoordinates(j);
						}
					}	
				});
				arrCoordinates = [];
				$('#valCoordinate-'+countItemMap).val(arrayAreaChange(arrCoordinates));
				hightlight(countItemMap);
				popup.find('.fileupload-buttonbar input[type=hidden]').val('');
				l('insert #valCoordinate-'+ countItemMap +' '+ $('#valCoordinate-'+countItemMap).val() +'');
			});
		});
	},
	floorDetail: function(action, event, data, ins) {
		$.colorbox.resize();
		if(action == 'done') {
			//console.log('upload');
			var flag = true;
			for( var i = 0; i < objMap.length; i++ ) {
				if( objMap[i].id_area === 'area'+countItemMap+''+counter ) {
					flag = false;
				}
			}
			if( flag && objItem.coordinates != 0 ) {
				var fileResponse = data.getFilesFromResponse(data)[0];
				objItem.id_area = 'area'+countItemMap+''+counter;
				//objItem.urlImage = fileResponse.url;
				objItem.nameImage = fileResponse.name;
				objMap.push(objItem);
				var arrCoordinatesItem = objItem.coordinates.split(',');

				getArrValueCoor(countItemMap);

				arrCoordinates.push(arrCoordinatesItem);
				$('#valCoordinate-'+countItemMap).val(arrayAreaChange(arrCoordinates));
				objItem = {};
				l('insert #valCoordinate-'+ countItemMap +' '+ $('#valCoordinate-'+countItemMap).val() +'');
			}
		} else {
			//console.log('xóa');
			var urlDelete = data.url;
			imgReGet = $(ins).find('.fileupload-buttonbar input[type=hidden]').val().split(',');
			for( var i = 0; i < objMap.length; i++ ) {
				if( urlDelete.search(objMap[i].nameImage) >= 0 ) {
					
					for( var j = 0; j < imgReGet.length; j++ ) {
						if( imgReGet[j] === objMap[i].nameImage ) {
							imgReGet.splice(j,1);
							$(ins).find('.fileupload-buttonbar input[type=hidden]').val(imgReGet.toString());
						}
					}

					removeArrCoordinates(i);

					hightlight(countItemMap);
					
				}else {

				}
			}
		}
	}
};

function removeArrCoordinates(index) {
	$('.area#'+objMap[index].id_area).remove();
	getArrValueCoor(countItemMap);
	for( var j = 0; j < arrCoordinates.length; j++ ) {
		var str = arrCoordinates[j].toString();
		if( str === objMap[index].coordinates ) {
			arrCoordinates.splice(j,1);
			$('#valCoordinate-'+countItemMap).val(arrayAreaChange(arrCoordinates));
			l('delete #valCoordinate-'+ countItemMap +' '+ $('#valCoordinate-'+countItemMap).val() +'');
		}
	}
	objMap.splice(index,1);
}

function getArrValueCoor(countItemMap) {
	if( $('#valCoordinate-'+countItemMap).val() != '' ) {
		return arrCoordinates = JSON.parse($('#valCoordinate-'+countItemMap).val());
	}
}

function hightlight(countItemMap) {
	$('#imgmapMainImage-'+countItemMap).maphilight({
		strokeColor: 'f00b00',
		alwaysOn: true,
		fillColor: '71f000',
		fillOpacity: 0.2,
		shadow: true,
		shadowColor: '000000',
		shadowRadius: 5,
		shadowOpacity: 0.6,
		shadowPosition: 'outside'
	});
}

var counter = 0;
var coordsLength = 0;
var textarea = '';
var getValueCoord = 0;
function setCoordinates(e, status, countItemMap) {
	var x = e.pageX;
	var y = e.pageY;

	//$('#dots').append('<img class="dot" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAMAAAADCAYAAABWKLW/AAAABGdBTUEAALGPC/xhBQAAABh0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjM2qefiJQAAACFJREFUGFdj9J/6KomBgUEYiN8yADmlQPwfRIM4SVCBJAAiRREoec4ImAAAAABJRU5ErkJggg==" style="left: '+ (x-1) +'px; top: '+ (y-1) +'px;" />');

	var offset = $('#mapContainer-'+countItemMap+' img').offset();
	x -= parseInt(offset.left);
	y -= parseInt(offset.top);
	if(x < 0) { x = 0; }
	if(y < 0) { y = 0; }
	
	var value = $('#coordsText-'+countItemMap).val();
	
	if( value == '' ) {
		value = x+','+y;
		coordsLength = value.length;
		counter++;
	} else {
		value = value+','+x+','+y;
		coordsLength = value.length;
	}
	if( status )
		$('#coordsText-'+countItemMap).val(value);
	
	if( $('#area'+countItemMap+''+counter).length != 0 ) {
		$('.area#area'+countItemMap+''+counter).remove();
	}
	var countKomma = value.split(',').length;
	var shape = (countKomma <= 4) ? 'rect' : 'poly';
	if(countKomma >= 4) {
		var html = '<area shape="'+shape+'" id="area'+countItemMap+''+counter+'" class="area" coords="'+value+'" href="#" alt="" title="">';
		$('#map-'+countItemMap).append(html);
	}
	
	$('#mapContainer-'+countItemMap).append($('#imgmapMainImage-'+countItemMap));
	$('#mapContainer-'+countItemMap).children('div').remove();
	$('#imgmapMainImage-'+countItemMap).removeClass('maphilighted');
	
	hightlight(countItemMap);
	objItem.coordinates = value;
}

function arrayAreaChange(arrCoordinates) {
	var strArrFirst = '[',
		strArrLast = ']';
	for( var i = 0; i < arrCoordinates.length; i++ ) {
		var strTemp = '['+arrCoordinates[i].toString()+']';
		if( arrCoordinates.length === 1 || (i+1) === arrCoordinates.length) {
			strArrFirst += strTemp;
		}else {
			strArrFirst += strTemp+',';
		}
	}
	strArrFirst += strArrLast;

	return strArrFirst;
}

function l(x){console.log(x);}