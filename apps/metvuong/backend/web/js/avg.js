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
	
	avgSearch.keyup(function(e){
		$.data(this, 'ajax') && $.data(this, 'ajax').abort();
		
		$.data(this, 'ajax', $.get(avgSearch.data('url'), {v: avgSearch.val()}, function(r){
    		if(r.length > 0) {
    			showSearchList(r);
    		} else {
    			hideSearchList();
    		}
		}));
	});
	
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
	});
	
	filterWrap.find('select').change(function(){
		if(!viewWrap.hasClass('hide')) {
			calculate();
		}
	});
	filterWrap.find('.cb').change(function(){
		if(!viewWrap.hasClass('hide')) {
			calculate();
		}
	});
	
	avgSearchWrap.find('.close').click(function(e){
		e.preventDefault();
		
		filterWrap.removeClass('active');
		avgSearch.focus();

		viewWrap.addClass('hide').removeClass('loaded');
	});
	
	function addSheets(sheets) {
		for(var i in sheets) {
			addSheet(sheets[i]);
		}
	}
	
	function addSheet(sheet) {
		var tabName = sheet.sheetName;
		var childs = sheet.data.childs;
		var parent = sheet.data.parent;
		
		var tabContainer = addTab(tabName);
		
		var table = $('<table class="avg-table"><tr class="area-title"><td></td></tr><tr class="data-point"><td>Data Point</td></tr><tr class="avg-price"><td>AVG Price</td></tr><tr class="avg-size"><td>AVG SQM</td></tr><tr class="avg-price-size"><td>AVG $/SQM</td></tr><tr class="avg-bed"><td>AVG Bed</td></tr><tr class="avg-bath"><td>AVG Bath</td></tr></table>');
		
		for(var i in childs) {
			append(table, childs[i]);
		}
		
		if(parent) {
			append(table, parent, true);
		}
		
		tabContainer.append(table);
	}
	
	function append(table, data, isMain) {
		var c = isMain ? 'main' : '';
		
		table.find('.area-title').append('<td class="' + c + '">' + data.name + '</td>');
		table.find('.data-point').append('<td class="' + c + '">' + data.value['Data Point'] + '</td>');
		table.find('.avg-price').append('<td class="' + c + '">' + data.value['AVG Price'] + '</td>');
		table.find('.avg-size').append('<td class="' + c + '">' + data.value['AVG SQM'] + '</td>');
		table.find('.avg-price-size').append('<td class="' + c + '">' + data.value['AVG $/SQM'] + '</td>');
		table.find('.avg-bed').append('<td class="' + c + '">' + data.value['AVG Bed'] + '</td>');
		table.find('.avg-bath').append('<td class="' + c + '">' + data.value['AVG Bath'] + '</td>');
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
		
		$.get('calculate', {hasProject: hasProject, hasWard: hasWard, type: avgSearch.data('type'), id: avgSearch.data('id'), t: type.val(), location: avgSearchPlaceholder.find('.text').text()}, function(r){
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