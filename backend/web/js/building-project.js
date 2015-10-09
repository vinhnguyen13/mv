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
			
			$('#progress-list').append('<div class="panel panel-default"> <div class="panel-body"> <i class="glyphicon glyphicon-remove"></i><div class="form-group"> <label class="control-label" for="buildingproject-bpvideo">Tháng / Năm</label> <div> <select class="form-control" name="BuildingProject[bpProgress][' + total + '][month]" style="width: auto; display: inline-block;"> <option value="1">1</option> <option value="2">2</option> <option value="3">3</option> <option value="4">4</option> <option value="5">5</option> <option value="6">6</option> <option value="7">7</option> <option value="8">8</option> <option value="9">9</option> <option value="10" selected="">10</option> <option value="11">11</option> <option value="12">12</option> </select> <select class="form-control" name="BuildingProject[bpProgress][' + total + '][year]" style="width: auto; display: inline-block;"> <option value="1998">1998</option> <option value="1999">1999</option> <option value="2000">2000</option> <option value="2001">2001</option> <option value="2002">2002</option> <option value="2003">2003</option> <option value="2004">2004</option> <option value="2005">2005</option> <option value="2006">2006</option> <option value="2007">2007</option> <option value="2008">2008</option> <option value="2009">2009</option> <option value="2010">2010</option> <option value="2011">2011</option> <option value="2012">2012</option> <option value="2013">2013</option> <option value="2014">2014</option> <option value="2015" selected="">2015</option> <option value="2016">2016</option> <option value="2017">2017</option> <option value="2018">2018</option> <option value="2019">2019</option> <option value="2020">2020</option> </select> </div> <div class="help-block"></div> </div> <div class="form-group"> <label class="control-label" for="buildingproject-bpvideo">Ảnh</label> <!-- The template to display files available for upload --> <script id="template-upload" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %} <li class="template-upload fade"> <span class="preview"></span> <p class="name">{%=file.name%}</p> <strong class="error text-danger"></strong> <p class="size">Processing...</p> <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div> </li> {% } %} </script><!-- The template to display files available for download --> <script id="template-download" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %} <li class="template-download fade"> <span class="preview"> {% if (file.thumbnailUrl) { %} <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a> {% } %} </span> <p class="name"> {% if (file.url) { %} <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?\'data-gallery\':\'\'%}>{%=file.name%}</a> {% } else { %} <span>{%=file.name%}</span> {% } %} </p> {% if (file.error) { %} <div><span class="label label-danger">Error</span> {%=file.error%}</div> {% } %} <span class="size">{%=o.formatFileSize(file.size)%}</span> {% if (file.deleteUrl) { %} <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields=\'{"withCredentials":true}\'{% } %}> <i class="glyphicon glyphicon-trash"></i> <span>Delete</span> </button> {% } else { %} <button class="btn btn-warning cancel"> <i class="glyphicon glyphicon-ban-circle"></i> <span>Cancel</span> </button> {% } %} </li> {% } %} </script> <!-- The file upload form used as target for the file upload widget --> <div id="w' + total + '-fileupload-fileupload" data-upload-template-id="template-upload" data-download-template-id="template-download"> <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload --> <div class="row fileupload-buttonbar"> <div class="col-lg-7"> <!-- The fileinput-button span is used to style the file input field as button --> <span class="btn btn-success fileinput-button"> <i class="glyphicon glyphicon-plus"></i> <span>Chọn ảnh...</span> <input type="hidden" name="BuildingProject[bpProgress][' + total + '][images]"><input type="file" id="w' + total + '-fileupload" name="upload" multiple=""> </span> <a class="btn btn-primary start"> <i class="glyphicon glyphicon-upload"></i> <span>Start upload</span> </a> <a class="btn btn-warning cancel"> <i class="glyphicon glyphicon-ban-circle"></i> <span>Cancel upload</span> </a> <a class="btn btn-danger delete"> <i class="glyphicon glyphicon-trash"></i> <span>Delete</span> </a> <input type="checkbox" class="toggle"> <!-- The global file processing state --> <span class="fileupload-process"></span> </div> <!-- The global progress state --> <div class="col-lg-5 fileupload-progress fade"> <!-- The global progress bar --> <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"> <div class="progress-bar progress-bar-success" style="width:0%;"></div> </div> <!-- The extended global progress state --> <div class="progress-extended">&nbsp;</div> </div> </div> <!-- The table listing the files available for upload/download --> <ul class="files"></ul> </div> <div class="help-block"></div> </div> </div> </div>');
		
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
			
			self.data('count', total + 1);
			
			floorList.append('<div class="panel panel-default"> <div class="panel-body"> <i class="glyphicon glyphicon-remove"></i> <div class="form-group"> <label class="control-label" for="buildingproject-bpvideo">Tầng</label> <input type="text" class="form-control" name="BuildingProject[' + name + '][floorPlan][' + total + '][title]" value=""> <div class="help-block"></div> </div> <div class="form-group"> <label class="control-label" for="buildingproject-bpvideo">Ảnh</label> <!-- The template to display files available for upload --> <script id="template-upload" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %} <li class="template-upload fade"> <span class="preview"></span> <p class="name">{%=file.name%}</p> <strong class="error text-danger"></strong> <p class="size">Processing...</p> <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div> </li> {% } %} </script><!-- The template to display files available for download --> <script id="template-download" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %} <li data-delete-later="{%=file.deleteLater%}" class="template-download fade"> <span class="preview"> {% if (file.thumbnailUrl) { %} <a target="_blank" href="{%=file.url%}" title="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a> {% } %} </span> <p class="name"> {% if (file.url) { %} <a target="_blank" href="{%=file.url%}" title="{%=file.name%}" {%=file.thumbnailUrl?\'data-gallery\':\'\'%}>{%=file.name%}</a> {% } else { %} <span>{%=file.name%}</span> {% } %} </p> {% if (file.error) { %} <div><span class="label label-danger">Error</span> {%=file.error%}</div> {% } %} <span class="size">{%=o.formatFileSize(file.size)%}</span> {% if (file.deleteUrl) { %} <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields=\'{"withCredentials":true}\'{% } %}> <i class="glyphicon glyphicon-trash"></i> <span>Delete</span> </button> {% } else { %} <button class="btn btn-warning cancel"> <i class="glyphicon glyphicon-ban-circle"></i> <span>Cancel</span> </button> {% } %} </li> {% } %} </script> <!-- The file upload form used as target for the file upload widget --> <div id="' + name + total + '-fileupload-fileupload" data-upload-template-id="template-upload" data-download-template-id="template-download"> <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload --> <div class="row fileupload-buttonbar"> <div class="col-lg-7"> <!-- The fileinput-button span is used to style the file input field as button --> <span class="btn btn-success fileinput-button"> <i class="glyphicon glyphicon-plus"></i> <span>Chọn ảnh...</span> <input type="hidden" name="BuildingProject[' + name + '][floorPlan][' + total + '][images]"><input type="file" id="' + name + total + '-fileupload" name="upload" multiple=""> </span> <a class="btn btn-primary start"> <i class="glyphicon glyphicon-upload"></i> <span>Start upload</span> </a> <a class="btn btn-warning cancel"> <i class="glyphicon glyphicon-ban-circle"></i> <span>Cancel upload</span> </a> <a class="btn btn-danger delete"> <i class="glyphicon glyphicon-trash"></i> <span>Delete</span> </a> <input type="checkbox" class="toggle"> <!-- The global file processing state --> <span class="fileupload-process"></span> </div> <!-- The global progress state --> <div class="col-lg-5 fileupload-progress fade"> <!-- The global progress bar --> <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"> <div class="progress-bar progress-bar-success" style="width:0%;"></div> </div> <!-- The extended global progress state --> <div class="progress-extended">&nbsp;</div> </div> </div> <!-- The table listing the files available for upload/download --> <ul class="files"></ul> </div> <div class="help-block"></div> </div> </div> </div>');
			
			var el = $('#' + name + total + '-fileupload-fileupload');
			customFileUpload.attachUploadWidget(el);
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
			current: '',
			rel: function() {
				return 'gal';
			},
			href: function() {
				return $(this).data('href')
			}
		});
		$('.video').colorbox({current: '', rel: 'video', iframe:true, innerWidth:640, innerHeight:390});
	}
};

var map = {
	
}

var customFileUpload = {
	fileuploadadd: function(event, data, ins) {
		var file = data.files[0];
		
		if((file.type == 'image/png' || file.type == 'image/jpeg')) {
			ins = $(ins).fileupload('option');

			if(ins.getNumberOfFiles() == ins.maxNumberOfFiles) {
				alert("Upload tối đa " + ins.maxNumberOfFiles + " ảnh cho trường này !");

				return false;
			}
		} else {
			return false;
		}
	},
	fileuploaddone: function(event, data, ins) {
		var input = $('#' + event.target.id.replace('-fileupload', '')).prev();
		
		var val = input.val();
		var filesName = val ? val.split(',') : [];
		
		filesName.push(data.result['files'][0].name);
		input.val(filesName.join(','));
	},
	fileuploaddestroy: function(event, data, ins) {
		if(data.context.data('delete-later')) {
			var name = data.context.find('.name').text().trim();
			
			if($('#delete-later').val()) {
				$('#delete-later').val($('#delete-later').val() + ',' + name);
			} else {
				$('#delete-later').val(name);
			}
		}
	},
	fileuploaddestroyed: function(event, data, ins) {
		var images = $(event.target).find('.files > li');
		var value = [];
		
		if(images.length > 0) {
			images.each(function(){
				value.push($(this).find('.name').text().trim());
			});
		}

		var input = $('#' + event.target.id.replace('-fileupload', '')).prev();
		input.val(value.join(','));
	},
	attachUploadWidget: function(el) {
		el.fileupload({"autoUpload":true,"uploadTemplateId":null,"downloadTemplateId":null,"previewCrop":true,"previewMinWidth":120,"previewMinHeight":120,"previewMaxWidth":120,"previewMaxHeight":120,"disableExifThumbnail":true,"formData":[],"url":"/express/upload/building-project-image"});
		el.on('fileuploadadd', function(e, data) {return customFileUpload.fileuploadadd(e, data, this);});
		el.on('fileuploaddone', function(e, data) {customFileUpload.fileuploaddone(e, data, this);});
		el.on('fileuploaddestroyed', function(e, data) {customFileUpload.fileuploaddestroyed(e, data, this);});
	}
};