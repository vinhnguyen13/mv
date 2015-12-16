;(function($){
	var stepCurrent = null;
	var stepConfig = null;
	var tabActive = null;
	

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


	$(document).on('click', '.wrapSuggestList .suggestListData a', function () {
		$(this).trigger('real-estate/tabActive');
		var forceStep = $(this).attr('data-force-step');
		var stepDisplay = null;
		if(steps[stepConfig][stepCurrent] && steps[stepConfig][stepCurrent][forceStep]){
			stepDisplay = steps[stepConfig][stepCurrent][forceStep];
		}else{
			stepDisplay = steps[stepConfig][stepCurrent]['next'];
		}

		tabActive.trigger('real-estate/actionDisplayList', [{'this': tabActive, 'stepDisplay': stepDisplay}, '']);
	});

	$(document).bind('real-estate/actionDisplayList', function (event, json, string) {
		var _this = json.this;
		var stepCurrent = _this.attr('data-step-current');
		var step = _this.attr('data-step');
		var stepDisplay = stepCurrent;
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
		stepConfig = tabActive.attr('data-step');
	});
})(jQuery);