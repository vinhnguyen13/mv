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
		var input = $('#' + event.target.id.replace(/\-fileupload$/, '')).prev();
		
		var val = input.val();
		var filesName = val ? val.split(',') : [];
		
		filesName.push(data.result['files'][0].name);
		input.val(filesName.join(','));
		
		if($(ins).data('callback')) {
			eval($(ins).data('callback') + '("done", event, data, ins)');
		}
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
		
		if($(ins).data('callback')) {
			eval($(ins).data('callback') + '("destroyed", event, data, ins)');
		}
	},
	attachUploadWidget: function(el) {
		el.fileupload({"autoUpload":true,"uploadTemplateId":null,"downloadTemplateId":null,"previewCrop":true,"previewMinWidth":120,"previewMinHeight":120,"previewMaxWidth":120,"previewMaxHeight":120,"disableExifThumbnail":true,"formData":[],"url":"/express/upload/image"});
		el.on('fileuploadadd', function(e, data) {return customFileUpload.fileuploadadd(e, data, this);});
		el.on('fileuploaddone', function(e, data) {customFileUpload.fileuploaddone(e, data, this);});
		el.on('fileuploaddestroy', function(e, data) {customFileUpload.fileuploaddestroy(e, data, this);});
		el.on('fileuploaddestroyed', function(e, data) {customFileUpload.fileuploaddestroyed(e, data, this);});
	}
};