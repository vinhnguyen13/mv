var objEvent = {
			itemInput: $('#searchInput'),
			tabsSearch: $('.search-select a'),
	        flagEnd : false,
	        flagSetTemplate: [],
	        countStep : 1,
	        currentStep : 0,
	        wrapListSuggest: $('.type-search ul'),
	        wrapStep: $('.search-wrap'),
	        lenghtSuggest : 0,
	        flagOpenSugget : false,
	        flagTrigger : '',
	        current: 0,
	        next: '',
	        timeoutClose: '',
	        editItem: '',
	        icon: $('#search-kind button span'),
	        iconScrollSearch : $('.icon-selected'),
	        objSave: [],

	        init: function() {
	        	objEvent.searchEvent();
	        	objEvent.tabSearchEvent();
	        	objEvent.selectItem();
	        	objEvent.btnclose();
	        	objEvent.reOpenBySuggest();
	        	objEvent.removeSuggest();
	        	loadCost.init();
	        	objEvent.centerBox();
	        	objEvent.renderSuggest();
	        },
	        getDataTinhThanhQuanHuyen: function () {
	        	$('.list-tinh-thanh').html('');
	        	$('.list-loai-bds').html('');
	        	for( var i in dataCities ) {
			        var $itemTinhThanh = $('<li data-id-tt='+i+'><a title="'+dataCities[i].name+'" href="#valTT">'+dataCities[i].name+'</a></li>');
			        $('.list-tinh-thanh').append($itemTinhThanh);
			    }

			    for( var i in dataCategories ) {
			        var $item = $('<li data-id-loai='+i+'><a title="'+dataCategories[i].name+'" data-template-show="'+dataCategories[i].template+'" href="#valLoai">'+dataCategories[i].name+'</a></li>');
			        $('.list-loai-bds').append($item);   
			    }
	        },
	        searchEvent: function() {
	        	objEvent.tabsSearch.on('click', function(e) {
	        		e.preventDefault();
	        		var _this = $(this),
			            txtPlaceholder = _this.data('placeholder'),
			            itemSearch = _this.attr('rel');

			        objEvent.reset();

			        itemTabs(itemSearch);
			        
			        objEvent.tabsSearch.parent().removeClass('active');
			        objEvent.itemInput.attr('placeholder', txtPlaceholder);
			    	_this.parent().addClass('active');

			    	if( _this.closest('.search-dropdown').length > 0 ) {
	                    _this.closest('.search-dropdown').removeClass('search-dropdown');
	                }

	                $('#valActive').val(_this.data('active'));
			    	
			        return false;
	        	});

	        	objEvent.tabsSearch.each(function() {
	        		var txtPlaceholder = $(this).data('placeholder');
	        		if( $(this).parent().hasClass('active') ) {
	        			objEvent.itemInput.attr('placeholder', txtPlaceholder);	
	        			objEvent.flagTrigger = $(this).attr('rel');
	        			itemTabs(objEvent.flagTrigger);
	        		}
	        	});

				function itemTabs(itemTab) {
			        switch(itemTab) {
			            case '#dd-search':
			            	objEvent.getDataTinhThanhQuanHuyen();
			                objEvent.flagTrigger = '#dd-search';
			                objEvent.icon.html('<em class="fa fa-search"></em>');
			                objEvent.iconScrollSearch.find('span').html('<em class="fa fa-home"></em><em class="fa fa-search"></em>');
			                break;
			            case '#dd-dky':
			            	//_this.trigger( 'real-estate/post', [{data: '1'}, 'something'] );
			            	objEvent.getDataTinhThanhQuanHuyen();
			            	$('.list-loai-bds li a').attr('data-end-submit',true);
			                objEvent.icon.html('<em class="fa fa-pencil-square-o"></em>');
			                objEvent.iconScrollSearch.find('span').html('<em class="fa fa-home"></em><em class="fa fa-pencil-square-o"></em>');
			                objEvent.flagTrigger = '#dd-dky';
			                break;
			            case '#dd-news':
			                //_this.trigger( 'real-estate/news', [{data: '1'}, 'something'] );
			                $('.list-loai-tt').html('');
			                $('.list-tintuc-suggest').html('');
			                $.getJSON(url_ttuc, function(result){
						        arrJSONReturn.objTTuc = result;
						        for( var i = 0; i < result.length; i++ ) {
						            var $item = $('<li data-id-ttuc='+result[i].id+'><a data-template-show="'+result[i].template+'" href="#valTTuc">'+result[i].ten_tt+'</a></li>');
						            $('.list-loai-tt').append($item);   
						        }
						    });
			                for( var i in newsCatalogs ) {
						        var $item = $('<li data-id-loaittuc="'+i+'"><a title="'+newsCatalogs[i].title+'" href="#">'+newsCatalogs[i].title+'</a></li>');
						        $('.list-tintuc-suggest').append($item);   
						    }
			                $('.list-duan-news li a').attr('data-end-submit',true);
						    $('.list-tintuc-suggest li a').attr('data-end-submit',true);

			                objEvent.icon.html('<em class="fa fa-file-text"></em>');
			                objEvent.iconScrollSearch.find('span').html('<em class="fa fa-home"></em><em class="fa fa-file-text"></em>');
			                objEvent.flagTrigger = '#dd-news';
			                objEvent.countStep = "news";
			                break;
			        }
			    }
	        },
	        tabSearchEvent: function() {
	        	objEvent.itemInput.on('click', function (e) {
	        		e.preventDefault();
	        		var _this = $(this);

	        		objEvent.editItem = '';

	        		if ( objEvent.next != '' ) {
	        			objEvent.countStep = objEvent.next;
	        			objEvent.next = '';
	        		}

	        		if( !objEvent.flagEnd )
			        	objEvent.open(objEvent.countStep);
				});
	        },
	        open: function(countStep) {
	        	objEvent.countStep = countStep;
	            objEvent.wrapStep.addClass('hidden-effect');

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

	            objEvent.editItem = '';
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
	                    $itemSuggest = $('<li data-step="'+objEvent.countStep+'"><i>x</i><span></span></li>'),
	                    itemId = '',
	                    nameAttr = '',
	                    relAttr = '',
	                    templateSuggest = _this.data('templateShow');

	                // render quận/huyện theo tỉnh/thành phố
	                if( _this.parent().data('idTt') != undefined ) {
	                    //$('#valTT').val(_this.parent().data('idTt'));
	                    itemId = _this.parent().data('idTt');
	                    nameAttr = 'data-id-tt';
	                    relAttr = 'id-tt';

	                    for( var i in dataCities ) {
	                        if( i == _this.parent().data('idTt') ) {
	                            $('.list-quan-huyen').html('');
	                            for( var j in dataCities[i].districts ) {
	                                var $item = $('<li data-id-qh='+j+'><a title="'+dataCities[i].districts[j].name+'" href="#valQh">'+dataCities[i].districts[j].name+'</a></li>');
	                                
	                                $('.list-quan-huyen').append($item);
	                            }
	                        }
	                    }

	                }
	                //end

	                // lấy value id khi chọn dropdown
	                if( _this.parent().data('idQh') != undefined ) {
	                    //$('#valQh').val(_this.parent().data('idQh'));
	                    itemId = _this.parent().data('idQh');
	                    nameAttr = 'data-id-qh';
	                    relAttr = 'id-qh';
	                }else if( _this.parent().data('idLoai') != undefined ) {
	                    //$('#valLoaibds').val(_this.parent().data('idLoai'));
	                    itemId = _this.parent().data('idLoai');
	                    nameAttr = 'data-id-loai';
	                    relAttr = 'id-loai';
	                }else if( _this.parent().data('idDuans') != undefined ) {
	                    //$('#valDuaan').val(_this.parent().data('idDuans'));
	                    itemId = _this.parent().data('idDuans');
	                    nameAttr = 'data-id-duans';
	                    relAttr = 'id-duans';
	                }else if( _this.parent().data('idTtuc') != undefined ) {
	                    //$('#valTTuc').val(_this.parent().data('idTtuc'));
	                    itemId = _this.parent().data('idTtuc');
	                    nameAttr = 'data-id-ttuc';
	                    relAttr = 'id-ttuc';
	                }else if( _this.parent().data('idDuannews') != undefined ) {
	                    //$('#valDuaannews').val(_this.parent().data('idDuannews'));
	                    itemId = _this.parent().data('idDuannews');
	                    nameAttr = 'data-id-duannews';
	                    relAttr = 'id-duannews';
	                }else if( _this.parent().data('idLoaittuc') != undefined ) {
	                    //$('#valLoaiTTuc').val(_this.parent().data('idLoaittuc'));
	                    itemId = _this.parent().data('idLoaittuc');
	                    nameAttr = 'data-id-loaittuc';
	                    relAttr = 'id-loaittuc';
	                }
	                //end

					//render dự án theo Loại Bất Động Sản
					var idTThanh =  objEvent.wrapListSuggest.find('li[data-id-tt]').data('idTt'),
                    	idQh = objEvent.wrapListSuggest.find('li[data-id-qh]').data('idQh'),
                    	idLoaiBDS = _this.parent().data('idLoai');

	                if( idTThanh != '' && idQh != '' && idLoaiBDS != '' && _this.parent().data('idLoai') != undefined ) {
	                    $('.list-duan-suggest').html('');
	                    for( var i in dataCities[idTThanh].districts[idQh].projects ) {
	                        var $item = $('<li data-id-duans='+i+'><a title="'+dataCities[idTThanh].districts[idQh].projects[i].name+'" href="#">'+dataCities[idTThanh].districts[idQh].projects[i].name+'</a></li>');
                            $('.list-duan-suggest').append($item);
	                    }
	                }
	                //end

	                if( templateSuggest !== undefined && templateSuggest != '' ) {
                    	objEvent.flagSetTemplate = templateSuggest.replace(/ /g,'').split(',');
                    	objEvent.countStep = objEvent.flagSetTemplate[0];
                    	$itemSuggest.attr('data-next',objEvent.countStep);
                		objEvent.flagSetTemplate.splice(0,1);
                		$(".search-wrap[data-template="+objEvent.countStep+"] li a").attr('data-template-show',objEvent.flagSetTemplate);
                    }

                    if( !objEvent.editItem ) { // insert item mới
	                    
	                    $itemSuggest.find('span').text(txt);
	                    $itemSuggest.find('span').attr('title',txt);
	                    $itemSuggest.attr(nameAttr,itemId);
	                    $itemSuggest.attr('rel',relAttr);
	                    objEvent.wrapListSuggest.append($itemSuggest);

	                    objEvent.checkCounter();
	                    
	                    var txtStep;
	                    txtStep = isNaN(objEvent.countStep) ? $(".search-wrap[data-template="+objEvent.countStep+"]").data('txtStep') : $('#step-'+objEvent.countStep).data('txtStep');

	                    objEvent.changeTextHolder(txtStep);

	                }else {// edit item đã được chọn
	                    objEvent.wrapListSuggest.find('li[data-step='+objEvent.editItem+'] span').text(txt);
	                    objEvent.wrapListSuggest.find('li[data-step='+objEvent.editItem+']').attr('data-next', objEvent.countStep);
	                    objEvent.wrapListSuggest.find('li[data-step='+objEvent.editItem+']').attr(nameAttr,itemId);
	                    $itemSuggest.attr('rel',relAttr);
	                    objEvent.editItem = '';
	                }

	                objEvent.close();
	                
	                objEvent.wrapListSuggest.show();

	                objEvent.resizeWidthInput();

	                if( objEvent.flagTrigger == '#dd-dky' && _this.data('endSubmit') && $('#valLoaibds').val() == '' ) {
	                	objEvent.updateSuggert();
                        _this.trigger( 'real-estate/post', [{data: '1'}, 'something'] );
                        objEvent.flagEnd = true;
                    }

                    if( objEvent.flagTrigger == '#dd-news' && _this.data('endSubmit') && $('#valLoaiTTuc').val() == '' && $('#valDuaannews').val() == '' ) {
                    	objEvent.updateSuggert();
                        _this.trigger( 'real-estate/news', [{data: '1'}, 'something'] );
                    }

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

	                for( var i = objEvent.wrapListSuggest.find('li').length; i > getStep ; i-- ) {
	                	var idInputHiddenRemove = objEvent.wrapListSuggest.find('li').eq(i-1).attr('rel');
						$('.getValSuggest[rel='+idInputHiddenRemove+']').val('');

	                	objEvent.wrapListSuggest.find('li').eq(i-1).remove();
					}
	                
	                objEvent.resizeWidthInput();
	                
	                objEvent.next = objEvent.countStep = $parentList.data('step');

	                objEvent.flagEnd = false;

	                if( !isNaN(objEvent.countStep) ) {
	                	objEvent.changeTextHolder($('#step-'+objEvent.countStep).data('txtStep'));
	                }else {
	                	objEvent.changeTextHolder($('.search-wrap[data-template='+objEvent.countStep+']').data('txtStep'));
	                }
	                
				});
	        },
	        changeTextHolder: function(txtStep) {
	        	objEvent.itemInput.attr('placeholder', txtStep);
	        },
	        resizeWidthInput: function() {
	        	objEvent.itemInput.hide();
	            setTimeout(function () {
	            	var wInput = $('.type-search').width() - objEvent.wrapListSuggest.outerWidth();
	            	objEvent.itemInput.css('width',wInput+'px').show();
	            },250);
	        },
	        reOpenBySuggest: function() {
	            $(document).on('click', '.type-search li span',function(e) {
	                e.preventDefault();
	                
	                var _this = $(this),
	                    boxId = _this.parent().data('step'),
	                    nextId = _this.parent().data('next');

	                objEvent.editItem = boxId;

	                objEvent.next = nextId;

	                $('.type-search li').removeClass('active');
	                _this.parent().addClass('active');
	                
	                var index = $('.type-search li[data-step='+boxId+']').index();
                	for ( var i = index; i <= objEvent.lenghtSuggest; i++ ) {
                		var itemIndex = index + 1,
                			idInputHiddenRemove = $('.type-search li').eq(itemIndex).attr('rel');

                		$('.getValSuggest[rel='+idInputHiddenRemove+']').val('');

                		$('.type-search li').eq(itemIndex).remove();
                	}
	                objEvent.resizeWidthInput();

	                objEvent.open(boxId);
	                setTimeout(function() {
	                	objEvent.flagEnd = false;
	                	objEvent.checkCounter();
	                },35);
	            });
	        },
	        checkCounter: function() {
	        	//l('countStep: '+objEvent.countStep);
	            objEvent.lenghtSuggest = objEvent.wrapListSuggest.find('li').length;
	            if( !isNaN(objEvent.countStep) ) { // is a number
	            	objEvent.countStep += 1;
	            }else { // is a string
	            	return;
	            }
	        },
	        reset: function() {
	            objEvent.countStep = 1;
	            objEvent.next = '';
	            objEvent.lenghtSuggest = 0;
	            objEvent.wrapListSuggest.hide().find('li').remove();
	            objEvent.flagEnd = false;
	            objEvent.wrapStep.find('li a').removeAttr('data-template-show');
	            objEvent.resizeWidthInput();
	        },
	        updateSuggert: function() {
	            var arr = [],
	            	strArrFirst = "[",
					strArrLast = "]";

				objEvent.wrapListSuggest.find('li').each(function (i) {
					var _this = $(this),
						dataStep = _this.data('step'),
						rel = _this.attr('rel'),
						dataId = _this.data(rel),
						dataNext = _this.data('next');

					if ( rel == 'id-tt' ) {
						$('#valTT').val(dataId);
						$('#stepTT').val(dataStep);
						$('#valNextTT').val(dataNext);
					}else if ( rel == 'id-qh' ) {
						$('#valQh').val(dataId);
						$('#stepQh').val(dataStep);
						$('#valNextQh').val(dataNext);
					}else if ( rel == 'id-loai' ) {
						$('#valLoaibds').val(dataId);
						$('#stepLbds').val(dataStep);
						$('#valNextLbds').val(dataNext);
					}else if ( rel == 'id-ttuc' ) {
						$('#valTTuc').val(dataId);
						$('#stepTtuc').val(dataStep);
						$('#valNextTtuc').val(dataNext);
					}else if ( rel == 'id-loaittuc' ) {
						$('#valLoaiTTuc').val(dataId);
						$('#stepLoaittuc').val(dataStep);
						$('#valNextLoaittuc').val(dataNext);
					}else if (rel == 'id-duans' ) {
						$('#valDuaan').val(dataId);
						$('#stepDuan').val(dataStep);
						$('#valNextDuan').val(dataNext);
					}else if ( rel == 'id-duannews' ) {
						$('#valDuaannews').val(dataId);
						$('#stepDuannews').val(dataStep);
						$('#valNextDuannews').val(dataNext);
					}else {
						l('Not Value');
					}
				});

				$('#arrVal').val(strArrFirst);
	        },
	        centerBox: function() {
	        	var logo = $('div.wrap-page-home .logo-home'),
	        		searchBox = $('div.wrap-page-home .box-search-header'),
	        		wLogo = logo.outerWidth(),
	        		hLogo = logo.outerHeight(),
	        		wSearchBox = searchBox.outerWidth(),
	        		hSearchBox = searchBox.outerHeight(),
	        		wWin = $('div.wrap-page-home .wrap-search-home').outerWidth(),
	        		hWin = $(window).outerHeight(),
	        		topCenterLogo = Math.floor(hWin/2 - hLogo/2 - hLogo - 30),
	        		leftCenterLogo = Math.floor(wWin/2 - wLogo/2),
	        		topCenterSearch = Math.floor(hWin/2 - hSearchBox/2) + 30,
	        		leftCenterSearch = Math.floor(wWin/2 - wSearchBox/2);

	        	logo.css({
	        		'transform' : 'translate3d( '+leftCenterLogo+'px, '+topCenterLogo+'px, 0)',
	        		'-moz-transform' : 'translate3d( '+leftCenterLogo+'px, '+topCenterLogo+'px, 0)',
	        		'-webkit-transform' : 'translate3d( '+leftCenterLogo+'px, '+topCenterLogo+'px, 0)',
	        		'-ms-transform' : 'translate3d( '+leftCenterLogo+'px, '+topCenterLogo+'px, 0)'

	        	});
	        	searchBox.css({
	        		'transform' : 'translate3d( '+leftCenterSearch+'px, '+topCenterSearch+'px, 0)',
	        		'-webkit-transform' : 'translate3d( '+leftCenterSearch+'px, '+topCenterSearch+'px, 0)',
	        		'-moz-transform' : 'translate3d( '+leftCenterSearch+'px, '+topCenterSearch+'px, 0)',
	        		'-ms-transform' : 'translate3d( '+leftCenterSearch+'px, '+topCenterSearch+'px, 0)'
	        	});

	        	setTimeout(function() {
	        		logo.show().css({
	        			visibility: 'visible'
	        		});
	        		logo.addClass('aniTopDown');
	        		searchBox.show().css({
	        			visibility: 'visible'
	        		});
	        		searchBox.addClass('aniTopDown');
	        	},100);
			},
			renderSuggest: function () {
				objEvent.getDataTinhThanhQuanHuyen();
				
				if ( $('#valActive').val() != '' ) {
					$('.search-select a[data-active='+$('#valActive').val()+']').trigger('click');
					setTimeout(function() {
						if ( $('#valTTuc').val() != '' ) {
							$('.list-loai-tt li[data-id-ttuc="'+$('#valTTuc').val()+'"] a').trigger('click');
						}
						if ( $('#valLoaiTTuc').val() != '' ) {
							$('.list-tintuc-suggest li[data-id-loaittuc="'+$('#valLoaiTTuc').val()+'"] a').trigger('click');
						}
						if ( $('#valTT').val() != '' ) {
							$('.list-tinh-thanh li[data-id-tt='+$('#valTT').val()+'] a').trigger('click');
						}
						if ( $('#valQh').val() != '' ) {
							$('.list-quan-huyen li[data-id-qh='+$('#valQh').val()+'] a').trigger('click');
						}
						if ( $('#valLoaibds').val() != '' ) {
							$('.list-loai-bds li[data-id-loai='+$('#valLoaibds').val()+'] a').trigger('click');
						}
						if ( $('#valDuaannews').val() != '' ) {
							$('.list-duan-news li[data-id-duannews='+$('#valDuaannews').val()+'] a').trigger('click');
						}
						if ( $('#valDuaan').val() != '' ) {
							$('.list-duan-suggest li[data-id-duans="'+$('#valDuaan').val()+'"] a').trigger('click');
						}
					},1000);
				}
			}
	    };

	var loadCost = {
			inputSubmitVal: $('.val-cost'),
			wrapList: $('.wrap-cost-bds'),
			choiceEvent: $('.wrap-cost-bds li span'),
			inputEvent: $('.cost-value'),
			flag: '',
			init: function () {
				loadCost.show();
				loadCost.clickChoice();
			},
			show: function () {
				loadCost.inputEvent.on('click', function (e) {
					e.preventDefault();
					var _this = $(this),
						tabShow = _this.closest('.box-cost').data('tab');

					loadCost.flag = loadCost.toggleCost(tabShow);
					loadCost.hide();
					loadCost.open(tabShow);
				});
			},
			open: function (item) {
				loadCost.wrapList.addClass('hidden-cost');
				$('.box-cost[data-tab='+item+'] .wrap-cost-bds').removeClass('hidden-cost');
				setTimeout(function() {
	                $('.box-cost[data-tab='+item+'] .wrap-cost-bds').addClass('active');
	            }, 30);
			},
			hide: function () {
				loadCost.wrapList.removeClass('active');
				loadCost.wrapList.addClass('hidden-cost');
			},
			toggleCost: function (item) {
				var itemGet = item == 'min' ? 'max' : 'min';
				return itemGet;
			},
			clickChoice: function() {
				loadCost.choiceEvent.on('click', function() {
					var _this = $(this),
						textChoice = _this.html(),
						costValueChoice = _this.parent().data('cost'),
						costRate = _this.parent().data('unit'),
						tabIndex = _this.closest('.box-cost').data('tab');

					
					$('.box-cost[data-tab='+tabIndex+'] .cost-value').val(textChoice);
					$('.val-cost[data-val-cost='+tabIndex+']').val(costValueChoice);

					loadCost.hide();

					if( loadCost.flag == 'max' ) {
						$('.box-cost[data-tab='+loadCost.flag+'] .cost-value').val('');
						$('.box-cost[data-tab='+loadCost.flag+'] ul li').each(function(i) {
							var _this = $(this),
								valCostList = _this.data('cost');

							if( costValueChoice > valCostList ) {
								_this.hide();
							}else {
								_this.show();
							}
						});

						loadCost.open(loadCost.flag);
						
						loadCost.flag = 'min';
					}
				});
			}
		};

    //start click outside
    $(document).on('click', function(e) {
        var container = $(".outsideevent");
        if (!container.is(e.target) && container.has(e.target).length === 0){
        	if( $(".wrap-cost-bds").hasClass('active') ) {
        		loadCost.hide();
        	}else {
        		$('.btn-close-search').trigger('click');
        	}
        }
    });
    //end click outside

    $(document).ready(function() {
    	objEvent.init();
    });

    $(window).resize(function() {
    	objEvent.centerBox();
    	objEvent.resizeWidthInput();
    });


function animateSearch() {
    var $boxSearch = $('.box-search-header'),
        wBox = $boxSearch.outerWidth(),
        wWin = $('.wrap-search-home').outerWidth(),
        cBox = 220;//Math.floor(wWin/2 - wBox/2);
    setTimeout(function() {
        $('.wrap-search-home .logo-home').addClass('ani-logo').css({
            'transform': 'translate3d( 0, 0, 0)',
            '-webkit-transform': 'translate3d( 0, 0, 0)',
            '-moz-transform': 'translate3d( 0, 0, 0)',
            '-ms-transform': 'translate3d( 0, 0, 0)'
        });

        $('.box-search-header').addClass('ani-search').css({
            'transform': 'translate3d('+cBox+'px, 10px, 0px)',
            '-webkit-transform': 'translate3d('+cBox+'px, 10px, 0px)',
            '-moz-transform': 'translate3d('+cBox+'px, 10px, 0px)',
            '-ms-transform': 'translate3d('+cBox+'px, 10px, 0px)'
        });
        $('.wrap-search-home .logo-home').addClass('ani-logo');
        $('.box-search-header').addClass('ani-search');
        setTimeout(function() {
            $('header').addClass('border-shadow');
        },500);
    },500);
}

function arrayAreaChange(arrCoordinates, flag) {
	var itemFirst = '{',
		itemLast = '},',
		itemLast_1 = '}';
	
	itemFirst += '"dataStep":"'+arrCoordinates[0]+'",';
	itemFirst += '"rel":"'+arrCoordinates[1].toString()+'",';
	itemFirst += '"dataId":"'+arrCoordinates[2]+'",';
	itemFirst += '"dataNext":"'+(arrCoordinates[3] == undefined ? "" : arrCoordinates[3])+'"';

	if( flag ) {
		itemFirst += itemLast_1;
	}else {
		itemFirst += itemLast;
	}
	
	
	return itemFirst;
}