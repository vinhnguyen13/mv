$(document).ready(function(){
	var avgSearchWrap = $('#avg-search-wrap');
	var avgSearch = $('#avg-search');
	var avgSearchPlaceholder = $('.avg-search-placeholder');
	var resultSearchWrap = $('#result-search-wrap');
	var resultSearch = resultSearchWrap.find('.result-search');
	var loading = $('#loading');
	var viewWrap = $('#view-wrap');
	var filterWrap = $('#filter-wrap');
	var type = $('#type');
	var inputWrap = $('#input-wrap');
	var hasWardWrap = $('#has-ward-wrap');
	var hasProjectWrap = $('#has-project-wrap');
	var tabsTitle = $('#tabs-title');
	var tabsContent = $('#tabs-content');
	var dateEl = $('.datepicker');
	
	$('.select-mask-show').click(function(){
		var selectMask = $(this).closest('.select-mask');
		var btn = selectMask.find('.btn');
		
		if(!selectMask.hasClass('.show-real')) {
			selectMask.addClass('show-real');
			var dclick, bclick;
			
			dclick = function(e){
				if($(e.target).closest('.select-mask').length == 0) {
					selectMask.removeClass('show-real');
					$(document).off('click', dclick);
					btn.off('click', bclick);
				}
			};
			
			bclick = function(){
				calculate();

				selectMask.removeClass('show-real');
				$(document).off('click', dclick);
				btn.off('click', bclick);
			};
			
			$(document).on('click', dclick);
			btn.on('click', bclick);
		}
	});
	
	$('.cb').click(function(){
		var ids = [];
		var texts = [];
		var parent = $(this).closest('.select-mask-real');
		
		parent.find('.cb').each(function(){
			var self = $(this);
			
			if(this.checked) {
				ids.push(self.val());
				texts.push(self.parent().text());
			}
		});
		
		parent.find('.real-value').val(ids.join(','));
		parent.prev().text(texts.join(', '));
	});
	
	dateEl.datepicker({
		dateFormat: "dd-mm-yy",
		constrainInput: true
	});
	
	avgSearch.keyup(function(e){
		if(e.keyCode == 38 || e.keyCode == 40) {
			var storeText = resultSearch.data('text');
			
			if(typeof storeText != 'string') {
				resultSearch.data('text', avgSearch.val());
			}
			
			var items = resultSearch.children();
			var direction = (e.keyCode == 40) ? 1 : -1;
			var currentActive = resultSearch.find('.active');
			var currentActiveIndex = items.index(currentActive);
			var nextIndex = currentActiveIndex + direction;
			var totalItems = items.length;
			
			if(nextIndex == totalItems) {
				nextIndex = -1;
			} else if(nextIndex == -2) {
				nextIndex = totalItems - 1;
			}
		
			if(nextIndex == -1) {
				if(typeof storeText == 'string') {
					avgSearch.val(storeText);
				}
			} else {
				var nextActiveItem = items.eq(nextIndex).addClass('active');
				
				avgSearch.val(nextActiveItem.text());
			}
			
			currentActive.removeClass('active');
			
			return;
		}
		
		resultSearch.data('text', false);
		
		$.data(this, 'ajax') && $.data(this, 'ajax').abort();
		
		$.data(this, 'ajax', $.get(avgSearch.data('url'), {v: avgSearch.val()}, function(r){
    		if(r.length > 0) {
    			showSearchList(r);
    		} else {
    			hideSearchList();
    		}
		}));
	}).keydown(function(e){
		if(e.keyCode === 13) {
			var children = resultSearch.children();

			if(children.length) {
				var active = resultSearch.find('.active');
				
				if(active.length) {
					active.eq(0).find('a').trigger('click');
				} else {
					children.eq(0).find('a').trigger('click');
				}
			}
		}
	}).focus();
	
	$('#export').click(function(){
		setCookie('avgComplete', '', 0)
		
		var el = $('<div style="top:0px; left:0px; position: fixed; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 99999; "><div style=" position: absolute; top: 50%; margin-top: -20px; font-size: 28px; color: #FFF; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.7); left: 50%; transform: translateX(-50%); "><span style="white-space: nowrap;" class="text">Đang tạo file Excel</span><img src="/admin/css/images/submit-loading.gif" style=" margin-left: 8px; width: 20px; "></div></div>');
		$('body').append(el);
		
		loadingFn(el, 0);
	});
	
	function loadingFn(el, start) {
		var text = el.find('.text');
		
		if(getCookie('avgComplete')) {
			el.remove();
			
			setCookie('avgComplete', '', 0)
		} else {
			setTimeout(function(){
				loadingFn(el, ++start);
			}, 100);
		}
	}
	
	$(document).on('click', '.hit', function(e){
		e.preventDefault();
		
		var self = $(this);
		var type = self.data('type');
		
		filterWrap.addClass('active');
		hideSearchList();
		avgSearch.val('').data({type: type, id: self.data('id')});
		
		avgSearchPlaceholder.find('.text').text(self.text());
		
		viewWrap.removeClass('hide');
		
		if(type == 'district') {
			hasWardWrap.show();
			hasProjectWrap.show();
		} else if(type == 'ward') {
			hasWardWrap.hide();
			hasProjectWrap.show();
		} else {
			hasWardWrap.hide();
			hasProjectWrap.hide();
		}
		
		calculate();
	}).on('mouseenter', '.hit', function(){
		var currentActive = resultSearch.find('.active');
		
		currentActive.removeClass('active');
		$(this).parent().addClass('active');
	}).on('mouseleave', '.hit', function(){
		$(this).parent().removeClass('active');
	});
	
	dateEl.on('change', function(){
		calculate();
	});
	
	filterWrap.find('select').change(function(){
		if(!viewWrap.hasClass('hide') && !$(this).hasClass('not-submit')) {
			calculate();
		}
	});
	filterWrap.find('.cb').change(function(){
		if(!viewWrap.hasClass('hide') && !$(this).hasClass('not-submit')) {
			calculate();
		}
	});
	
	avgSearchWrap.find('.close').click(function(e){
		e.preventDefault();
		
		filterWrap.removeClass('active');
		avgSearch.focus();

		viewWrap.addClass('hide').removeClass('loaded');
	});
	
	avgSearchPlaceholder.click(function(){
		filterWrap.removeClass('active');
		avgSearch.focus();

		viewWrap.addClass('hide').removeClass('loaded');
	});
	
	function addSheets(sheets) {
		for(var i in sheets) {
			if(sheets[i]['sheetName']) {
				addSheet(sheets[i]);
			}
		}
	}
	
	function addSheet(sheet) {
		var tabName = sheet.sheetName;
		var childs = sheet.data.childs;
		var parent = sheet.data.parent;
		
		var tabContainer = addTab(tabName);
		
		var table = $('<table class="avg-table"><tr class="area-title"><td></td></tr><tr class="data-point"><td>Data Point</td></tr><tr class="avg-price"><td>AVG Price</td></tr><tr class="avg-size"><td>AVG SQM</td></tr><tr class="avg-price-size"><td>AVG $/SQM</td></tr><tr class="avg-bed"><td>AVG Bed</td></tr><tr class="avg-bath"><td>AVG Bath</td></tr></table>');

		if(parent) {
			append(table, parent, true);
		}
		
		for(var i in childs) {
			if(childs[i]['name']) {
				append(table, childs[i]);
			}
		}
		
		tabContainer.append(table);
	}
	
	function append(table, data, isMain) {
		var c = isMain ? 'main' : '';
		
		var url = $('#view-listing').attr('href');
		
		if(data.type != avgSearch.data('type')) {
			if(data.type == 'ward') {
				url += '&ward_id=' + data.id + '&ward_name_filter=3' + '&ward_name_mask=' + encodeURIComponent(data.name + ', ' + avgSearchPlaceholder.text());
			} else if(data.type == 'project') {
				if(avgSearch.data('type') == 'district') {
					url += '&project_building_id=' + data.id + '&project_name_filter=3' + '&project_name_mask=' + encodeURIComponent(data.name + ', ' + avgSearchPlaceholder.text());
				} else {
					url += '&project_building_id=' + data.id + '&project_name_filter=3' + '&project_name_mask=' + encodeURIComponent(data.name);
				}
			}
		}
		
		table.find('.area-title').append('<td class="' + c + '"><a href="' + url + '" target="_blank">' + data.name + '</a></td>');
		table.find('.data-point').append('<td class="' + c + '">' + formatNumber(data.value['Data Point']) + '</td>');
		table.find('.avg-price').append($('<td class="has-iqr ' + c + '">' + formatNumber(data.value['AVG Price']) + '</td>').data(data.value['IQR Price']).data('type', 'price').data('url', url));
		table.find('.avg-size').append($('<td class="has-iqr ' + c + '">' + formatNumber(data.value['AVG SQM']) + '</td>').data(data.value['IQR Size']).data('type', 'area').data('url', url));
		table.find('.avg-price-size').append($('<td class="has-iqr ' + c + '">' + formatNumber(data.value['AVG $/SQM']) + '</td>').data(data.value['IQR Price/Size']));
		table.find('.avg-bed').append('<td class="' + c + '">' + percentCell(data.value['AVG Bed'], 'bed') + '</td>');
		table.find('.avg-bath').append('<td class="' + c + '">' + percentCell(data.value['AVG Bath'], 'bath') + '</td>');
	}
	
	var body = $('body');
	
	$(document).on('click', '.has-iqr', function(){
		body.find('> .iqr').remove();
		
		var self = $(this);
		var data = self.data();
		var popup = $('<div class="iqr"></div');
		
		var url = $('#view-listing').attr('href');
		
		popup.append('<div>Method: ' + data.method + '</div>');
		
		if(data.type) {
			var url = data.url;
			var unit = 1;
			
			if(data.type == 'price') {
				url += '&price_type=2';
				url += '&price_unit=' + type.val();
				
				unit = (type.val() == 1) ? 1000000000 : 1000000;
			} else {
				url += '&area_filter=3';
			}
			
			var max = (data.below - 1) / unit;
			var min = (data.above + 1) / unit;
			
			var equalMin = data.below / unit;
			var equalMax = data.above / unit;
			
			if(data.type == 'price') {
				
				var unitt = (type.val() == 1) ? 'tỷ' : 'triệu';
				var maxMask = '< ' + equalMin + unitt;
				var minMask = '> ' + equalMax + unitt;
			} else {
				
				var unitt = '';
				var maxMask = max;
				var minMask = min;
			}
			
			var urlBelow = url + '&' + data.type + '_max=' + max + '&' + data.type + '_mask=' + maxMask;
			var urlAbove = url + '&' + data.type + '_min=' + min + '&' + data.type + '_mask=' + minMask;
			var urlRetention = url + '&' + data.type + '_min=' + equalMin + '&' + data.type + '_max=' + equalMax + '&' + data.type + '_mask=' + equalMin+unitt + ' - ' + equalMax+unitt;

			popup.append('<div>Lower limit: ' + formatNumber(data.below) + ' - <a target="_blank" href="' + urlBelow + '">View Outliers Below</a></div>');
			popup.append('<div>Upper limit: ' + formatNumber(data.above) + ' - <a target="_blank" href="' + urlAbove + '">View Outliers Above</a></div>');
			popup.append('<div><a target="_blank" href="' + urlRetention + '">View Retention Listing</a></div>');
		} else {
			popup.append('<div>Upper limit: ' + formatNumber(data.above) + '</div>');
			popup.append('<div>Lower limit: ' + formatNumber(data.below) + '</div>');
		}
		
		body.append(popup);
		
		var left = self.position().left;
		var w = $(window).width();
		
		if(left + popup.outerWidth() > w) {
			left = w - popup.outerWidth();
		}
		
		popup.css({
			left: left + 'px',
			top: (self.position().top - popup.outerHeight()) + 'px'
		});
		
		$(document).on('click', function(e){
			var t = $(e.target);
			
			if(t.closest('.iqr').length == 0) {
				popup.remove();
				$(document).off('click', arguments.callee);
			}
		});
	});
	
	function percentCell(counts, label) {
		var html = '<div>';
		
		for(var i in counts) {
			slabel = counts[i][0] > 1 ? label + 's' : label;
			
			html += '<div>' + counts[i][0] + ' ' + slabel + ': ' + formatNumber(counts[i][1]) + '%</div>';
		}
		
		html += '</div>';
		
		return html;
	}
	
	var firstTab = true;
	
	function addTab(tabName) {
		var tabTitle = $('<a href="#" class="tab-title">' + tabName + '</a>');
		var tabContent = $('<div class="tab-content"></div>');
		
		if(firstTab) {
			firstTab = false;
			tabTitle.addClass('active');
			tabContent.addClass('active');
		}
		
		tabsTitle.append(tabTitle);
		tabsContent.append(tabContent);
		
		tabTitle.click(function(e){
			e.preventDefault();
			
			tabsTitle.find('> .active').removeClass('active');
			tabsContent.find('> .active').removeClass('active');
			tabTitle.addClass('active');
			tabContent.addClass('active');
		});
		
		return tabContent;
	}
	
	function clearTabs() {
		tabsTitle.html('');
		tabsContent.html('');
		
		firstTab = true;
	}
	
	function calculate() {
		clearTabs();
		
		viewWrap.removeClass('loaded');

		var hasWard = hasWardWrap.find('input').is(':checked') ? 1 : 0;
		var hasProject = hasProjectWrap.find('input').is(':checked') ? 1 : 0;
		
		var params = {
			category_id: $('#category_id').val(),
			round: $('#round').val(),
			hasProject: hasProject,
			hasWard: hasWard,
			type: avgSearch.data('type'),
			id: avgSearch.data('id'),
			t: type.val(),
			location: avgSearchPlaceholder.find('.text').text(),
			date_from: $('#date-from').val(),
			date_to: $('#date-to').val()
		};
		
		$.get('calculate', params, function(r){
			viewWrap.addClass('loaded');
			
			$('#view-listing').attr('href', r.url);
			$('#export').attr('href', r.exportUrl);
			
			addSheets(r.sheets);
		});
	}
	
	function showSearchList(r) {
		$(document).on('click', _hideSearchList);
		
		var html = '';
		
		for(var i in r) {
			html += '<li><a href="#" class="hit" data-type="' + r[i].type + '" data-id="' + r[i].id + '">' + r[i].full_name + '</a></li>';
		}
		
		resultSearch.html(html);
		resultSearchWrap.removeClass('hide');
	}
	
	function _hideSearchList(e) {
		if(!$(e.target).closest('#avg-search-wrap').length) {
			hideSearchList();
		}
	}
	
	function hideSearchList() {
		$(document).off('click', _hideSearchList);
		
		resultSearchWrap.addClass('hide');
		resultSearch.html('');
	}
});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
	
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

function formatNumber(num, round) {
	var parseNum = parseFloat(num);
	
	if(isNaN(num) || isNaN(parseNum)) {
		return null;
	}
	
	if(typeof round !== 'undefined') {
		parseNum = parseFloat(parseNum.toFixed(round));
	}
	
	var parts = parseNum.toString().split(".");
	
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    
    return parts.join(".");
}