(function() {
	"use strict";

	$.getJSON(url_tt, function(result){
        arrJSONReturn.objTT = result;
        for( var i = 0; i < result.length; i++ ) {
            var $itemTinhThanh = $('<li data-id-tt='+result[i].id+' data-active='+result[i].status_active+'><a href="#valTT">'+result[i].tinh_thanh+'</a></li>');
            $('.list-tinh-thanh').append($itemTinhThanh);
        }
    });

    $.getJSON(url_loaibds, function(result){
        arrJSONReturn.objLoai = result;
        for( var i = 0; i < result.length; i++ ) {
            var $item = $('<li data-id-loai='+result[i].id+' data-active='+result[i].status_active+'><a data-template-show="'+result[i].template+'" href="#valLoai">'+result[i].ten_loai+'</a></li>');
            $('.list-loai-bds').append($item);   
        }
    });

    $.getJSON(url_ttuc, function(result){
        arrJSONReturn.objTTuc = result;
        for( var i = 0; i < result.length; i++ ) {
            var $item = $('<li data-id-ttuc='+result[i].id+' data-active='+result[i].status_active+'><a href="#valTTuc">'+result[i].ten_tt+'</a></li>');
            $('.list-loai-tt').append($item);   
        }
    });

	var objEvent = {
			itemInput: $('#searchInput'),
			tabsSearch: $('.search-select a'),
	        flagEnd : false,
	        countStep : 1,
	        countCurrent : 0,
	        wrapListSuggest: $('.type-search ul'),
	        wrapStep: $('.search-wrap'),
	        lenghtSuggest : 0,
	        flagOpenSugget : false,
	        flagTrigger : '',
	        template: {},
	        timeoutClose: '',
	        editItem: '',
	        init: function() {
	        	objEvent.searchEvent();
	        	objEvent.tabSearchEvent();
	        	objEvent.selectItem();
	        	objEvent.btnclose();
	        	objEvent.reOpenBySuggest();
	        },
	        searchEvent: function() {
	        	objEvent.tabsSearch.each(function() {
	        		var txtPlaceholder = $(this).data('placeholder');
	        		if( $(this).parent().hasClass('active') ) {
	        			objEvent.itemInput.attr('placeholder', txtPlaceholder);	
	        			objEvent.flagTrigger = $(this).attr('rel');
	        		}
	        	});
	        	objEvent.tabsSearch.on('click', function(e) {
	        		e.preventDefault();
	        		var _this = $(this),
			            txtPlaceholder = _this.data('placeholder'),
			            itemSearch = _this.attr('rel');

			        switch(itemSearch) {
			            case '#dd-search':
			                //something
			                objEvent.flagTrigger = '#dd-search';
			                break;
			            case '#dd-dky':
			                //_this.trigger( 'real-estate/post', [{data: '1'}, 'something'] );
			                objEvent.flagTrigger = '#dd-dky';
			                break;
			            case '#dd-news':
			                //something
			                //_this.trigger( 'real-estate/news', [{data: '1'}, 'something'] );
			                objEvent.flagTrigger = '#dd-news';
			                break;
			        }

			        objEvent.reset();
			        
			        objEvent.tabsSearch.parent().removeClass('active');
			        objEvent.itemInput.attr('placeholder', txtPlaceholder);
			    	_this.parent().addClass('active');
			    	
			        return false;
	        	});
	        },
	        tabSearchEvent: function() {
	        	objEvent.itemInput.on('click', function (e) {
	        		e.preventDefault();
	        		var _this = $(this);

			        if( !objEvent.flagEnd )
			        	objEvent.open(objEvent.countStep);
				});
	        },
	        open: function(countStep) {//1. edit, 0. open normal
	        	objEvent.countStep = countStep;
	            objEvent.wrapStep.addClass('hidden-effect');

	            //flagOpen ? $('#step-'+objEvent.countStep).addClass('edit-suggest') : $('#step-'+objEvent.countStep).removeClass('edit-suggest');

	            isNaN(objEvent.countStep) ? $(".search-wrap[data-template="+objEvent.countStep+"]").removeClass('hidden-effect') : $('#step-'+objEvent.countStep).removeClass('hidden-effect');

	            setTimeout(function() {
	                isNaN(objEvent.countStep) ? $(".search-wrap[data-template="+objEvent.countStep+"]").addClass('active') : $('#step-'+objEvent.countStep).addClass('active');
	            	isNaN(objEvent.countStep) ? $(".search-wrap[data-template="+objEvent.countStep+"]").find('input').eq(0).focus() : '';
	            }, 30);

	            clearTimeout(objEvent.timeoutClose);
	        },
	        close: function() {
	            $('.search-wrap').removeClass('edit-suggest');

	            $('.search-wrap').removeClass('active');

	            objEvent.timeoutClose = setTimeout(function() {
	                $('.search-wrap').addClass('hidden-effect');
	            }, 300);

	            $('.type-search li').removeClass('active');

	            if( objEvent.countStep < objEvent.wrapStep.lenghtStep ) {
	                objEvent.flagOpenSugget = false;
	            }
	        },
	        btnclose: function() {
	            $('.btn-close-search').on('click',function(e) {
	                e.preventDefault();

	                $('.type-search li').removeClass('active');

	                $(this).closest('.search-wrap').removeClass('active');
	                objEvent.close();

	                return false;
	            });
	        },
	        selectItem: function() {
	        	$(document).on('click', '.search-item > ul a', function(e) {
	                e.preventDefault();
	                var _this = $(this),
	                    txt = _this.text(),
	                    $itemSuggest = $('<li data-step="'+objEvent.countStep+'" data-step-show='+_this.attr('href')+'><i>x</i><span></span></li>'),
	                    itemId = '',
	                    templateSuggest = _this.data('templateShow');

	                if( _this.parent().data('idTt') != undefined ) {
	                    $('#valTT').val(_this.parent().data('idTt'));
	                    itemId = _this.parent().data('idTt');
	                    for( var i = 0; i < arrJSONReturn.objTT.length; i++ ) {
	                        if( arrJSONReturn.objTT[i].id == _this.parent().data('idTt') ) {
	                            $('.list-quan-huyen').html('');
	                            for( var j = 0; j < arrJSONReturn.objTT[i].quan_huyen.length; j++ ) {
	                                var $item = $('<li data-id-qh='+arrJSONReturn.objTT[i].quan_huyen[j].id+'><a href="#valQh">'+arrJSONReturn.objTT[i].quan_huyen[j].ten_quan_huyen+'</a></li>');
	                                $('.list-quan-huyen').append($item);
	                            }
	                        }
	                    }
	                }

	                if( _this.parent().data('idQh') != undefined ) {
	                    $('#valQh').val(_this.parent().data('idQh'));
	                    itemId = _this.parent().data('idQh');
	                }else if( _this.parent().data('idLoai') != undefined ) {
	                    $('#valLoai').val(_this.parent().data('idLoai'));
	                    itemId = _this.parent().data('idLoai');
	                }else if( _this.parent().data('idTtuc') != undefined ) {
	                    $('#valTTuc').val(_this.parent().data('idTtuc'));
	                    itemId = _this.parent().data('idTtuc');
	                }

	                $itemSuggest.attr('data-item-id',itemId);

	                if( !objEvent.editItem ) {
	                    
	                    $itemSuggest.find('span').text(txt);
	                    
	                    objEvent.wrapListSuggest.append($itemSuggest);

	                    if( objEvent.flagTrigger == '#dd-dky' && objEvent.countStep === 3 ) {
	                        _this.trigger( 'real-estate/post', [{data: '1'}, 'something'] );
	                    }

	                    setTimeout(function() {
	                        if( objEvent.flagTrigger == '#dd-news' ) {
	                            _this.trigger( 'real-estate/news', [{data: '1'}, 'something'] );
	                        }
	                    },200);
	                    
	                    if( templateSuggest !== undefined ) {
	                		objEvent.countStep = templateSuggest;
	                	}

	                    objEvent.checkCounter();

	                    var txtStep;
	                    txtStep = isNaN(objEvent.countStep) ? $(".search-wrap[data-template="+objEvent.countStep+"]").data('txtStep') : $('#step-'+objEvent.countStep).data('txtStep');

	                    objEvent.itemInput.attr('placeholder', txtStep);

	                }else {
	                    objEvent.wrapListSuggest.find('li[data-step='+objEvent.editItem+'] span').text(txt);
	                    if( !isNaN(objEvent.editItem) ) {
	                    	//objEvent.countStep = objEvent.editItem + 1;
	                    	objEvent.flagEnd = false;
	                    }else {

	                    }
	                    objEvent.editItem = '';
	                }
	                
	                objEvent.close();
	                
	                objEvent.wrapListSuggest.show();

	                objEvent.resizeWidthInput();

	                if( _this.closest('.search-wrap').data('end') ) {
	                	objEvent.flagEnd = true;
	                }

	                if( !objEvent.flagEnd ) {
	                	objEvent.open(objEvent.countStep);
	                }

	                return false;
	            });
	        },
	        removeSuggest: function() {
	            $(document).on('click', '.type-search li i',function(e) {
	                e.preventDefault();
	                var _this = $(this),
	                    $parentList = _this.parent(),
	                    getStep = $parentList.index(),
	                    count = 0;

	                for( var i = objEvent.lenghtSuggest-1; i >= getStep ; i-- ) {
	                    $($('.type-search li').eq(i).data('stepShow')).val('');
	                    $('.type-search li').eq(i).remove();
	                }

	                objEvent.close();
	                objEvent.countStep = objEvent.countStep === 5 ? 5 : getStep;
	                objEvent.resizeWidthInput();
	                objEvent.checkCounter();
	                $textSearch.val('');
	                if( !objEvent.flagOpenSugget ) {
	                    var txtStep = $('#step-'+objEvent.countStep).data('txtStep');
	                    $('.type-search input').attr('placeholder', txtStep);
	                }
	                if( objEvent.flagEnd && objEvent.countStep == 5 ) objEvent.flagEnd = false;
	            });
	            
	        },
	        resizeWidthInput: function() {
	        	objEvent.itemInput.hide();
	            var wInput = $('.type-search').width() - objEvent.wrapListSuggest.outerWidth();
	            objEvent.itemInput.css('width',wInput+'px').show();
	        },
	        reOpenBySuggest: function() {
	            $(document).on('click', '.type-search li span',function(e) {
	                e.preventDefault();
	                
	                var _this = $(this),
	                    boxId = _this.parent().data('step');

	                objEvent.editItem = boxId;

	                $('.type-search li').removeClass('active');
	                _this.parent().addClass('active');
	                
	                for( var i = objEvent.lenghtSuggest; i >= 0 ; i-- ) {
	                    if( i > boxId ) {
	                        $('.type-search li').eq(i-1).remove();
	                    }
	                }

	                objEvent.resizeWidthInput();

	                objEvent.open(boxId);
	                objEvent.countStep = boxId;
	                objEvent.checkCounter();
	            });
	        },
	        checkCounter: function() {
	            objEvent.lenghtSuggest = objEvent.wrapListSuggest.find('li').length;
	            if( objEvent.wrapStep.length === objEvent.lenghtSuggest ) {
	            	objEvent.flagEnd = true;
	            }else if( objEvent.countStep < objEvent.wrapStep.length ){
	                objEvent.countStep += 1;
	            }
	        },
	        reset: function() {
	            objEvent.countStep = 1;
	            objEvent.lenghtSuggest = 0;
	            objEvent.wrapListSuggest.hide().find('li').remove();
	            objEvent.itemInput.removeAttr('style');
	            objEvent.flagEnd = false;
	            $('.getValSuggest').val('');
	        },
	        updateSuggert: function(countStep, lenghtSuggest) {
	            objEvent.countStep = countStep;
	            objEvent.lenghtSuggest = lenghtSuggest;
	        }
	    };

    //start click outside
    $(document).on('click', function(e) {
        var container = $(".outsideevent");
        if (!container.is(e.target) && container.has(e.target).length === 0){
            $('.btn-close-search').trigger('click');
        }
    });
    //end click outside

    objEvent.init();


})(jQuery);