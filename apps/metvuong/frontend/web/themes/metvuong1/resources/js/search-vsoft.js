;(function($){
	var stepCurrent = null;
	var stepConfig = null;
	var tabActive = null;
	
	/**---ACTION---**/
	$(document).on('click', '.wrapSuggestList .close', function () {
		$(this).trigger('real-estate/tabActive');			
		$('.wrapSuggestList').hide();
		if(steps[stepConfig][stepCurrent] && steps[stepConfig][stepCurrent]['prev']){
			tabActive.attr('data-step-current', steps[stepConfig][stepCurrent]['prev']);
			tabActive.trigger('real-estate/displayList', [{step: steps[stepConfig][stepCurrent]['prev'], status: 1}, '']);
		}
	});

	$(document).on('click', '.tab', function () {
		$('.tab').parent().removeClass('active');
		$(this).parent().addClass('active');
		$('.wrapSuggestList').hide();
	});

	$(document).on('click', '.wrapClickSearch .searchInput', function () {
		$(this).trigger('real-estate/tabActive');
		tabActive.trigger('real-estate/actionDisplayList', [{'this': tabActive}, '']);

	});

	$(document).on('click', '.wrapClickSearch .textSelected .remove', function () {
		var element = $(this).parent();
		$(this).trigger('real-estate/tagRemove', [{element: element}]);
		
	});

	$(document).on('click', '.wrapClickSearch .textSelected .label-info', function () {
		$(this).trigger('real-estate/tabActive');
		var tmpStep = $(this).attr('data-step-selected');				
		if(steps[stepConfig][tmpStep] && steps[stepConfig][tmpStep]['id']){
			var stepDisplay = steps[stepConfig][tmpStep]['id'];
			tabActive.trigger('real-estate/actionDisplayList', [{'this': tabActive, 'stepDisplay': stepDisplay}, '']);
			$(this).trigger('real-estate/tagRemove', [{element: $(this).next()}]);
		}
	});

	$(document).on('click', '.wrapSuggestList .suggestListData a', function () {
		$(this).trigger('real-estate/tabActive');
		var forceStep = $(this).attr('data-force-step');		
		if(steps[stepConfig][stepCurrent] && steps[stepConfig][stepCurrent][forceStep]){
			var stepDisplay = steps[stepConfig][stepCurrent][forceStep];
		}else{
			var stepDisplay = steps[stepConfig][stepCurrent]['next'];
		}
		tabActive.trigger('real-estate/actionDisplayList', [{'this': tabActive, 'stepDisplay': stepDisplay}, '']);
		tabActive.trigger('real-estate/tagSelected', [{step: stepCurrent, text: $(this).html()}]);
	});
	/**---END ACTION---**/


	/**---REGISTER EVENT---**/
	$(document).bind('real-estate/actionDisplayList', function (event, json, string) {
		var _this = json.this;
		var stepDisplay = _this.attr('data-step-current');				
		if(json.stepDisplay){
			stepDisplay = json.stepDisplay;
		}
		_this.trigger('real-estate/displayList', [{step: stepDisplay, status: 1}, '']);
		_this.attr('data-step-current', stepDisplay);
	});

	$(document).bind('real-estate/displayList', function (event, json, string) {				
		if(json.status == 1){
			if($('.' + json.step)){
				$('.wrapSuggestList .suggestListData').html($('.' + json.step)[0].outerHTML);
				$('.wrapSuggestList').show();
			}
		}else{
			$('.wrapSuggestList').hide();
		}

	});

	$(document).bind('real-estate/tabActive', function (event, json, string) {
		tabActive = $('.tab').closest('li.active');
		stepCurrent = tabActive.attr('data-step-current');
		stepConfig = tabActive.attr('data-step-config');
	});

	$(document).bind('real-estate/tagSelected', function (event, json, string) {		
		if(json.step){			
			var txt = '<span class="tag label label-info" data-value="1" data-step-selected="'+json.step+'">'+json.text+' <span class="remove">REMOVE</span></span>';
			$('.textSelected').append(txt);			
		}
	});

	$(document).bind('real-estate/tagRemove', function (event, json, string) {
		if(json.element) {
			var _this = json.element;
			var next = _this.next();			
			_this.remove();
			if(next){					
				next.trigger('real-estate/tagRemove', [{element: next}]);
			}			
		}
	});
	/**---END REGISTER EVENT---**/

})(jQuery);