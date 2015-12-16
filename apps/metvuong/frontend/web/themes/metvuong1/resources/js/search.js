;(function($){

	var demo = 
	[
		{
			"idItem": 1,
			"nextStep": "quan-huyen",
			"text": "Hồ Chí Minh",
			"dataItem": "tinh-thanh"
		},
		{
			"idItem": 1,
			"nextStep": "loai-bds",
			"text": "Bình Chánh",
			"dataItem": "quan-huyen"
		},
		{
			"idItem": 1,
			"nextStep": "loai-duan",
			"text": "Chung cu",
			"dataItem": "loai-bds"
		},
		{
			"idItem": 2,
			"nextStep": "",
			"text": "RICHSTAR",
			"dataItem": "chung-cu"
		}
	];

	var plugin = {};

	var defaults = {
		input: $('#searchInput'),
		objSave: [],
		wrapSuggest: $('.type-search ul'),
		loading: $('<div class="loading_search"><span></span></div>'),
		ajaxLoad: false,
		searchWrap: $('.search-wrap'),
		currentStep: '',
		current: '',
		nextStep: '',
		timeoutClose: '',
		inputPlaceholder: '',
		arrStep: [],
		arrStepRender: {},
		flagEdit: false,
		itemEdit: '',
		tabActive: '',
		countSuggest: 0
	};

	$.fn.MVS = function (options) {

		if ( this.length == 0 ) 
			return this;

		var mv = {};

		var tabs = this;

		plugin.tabs = this;

		//get start
		var init = function (tabs) {
			mv.settings = $.extend({}, defaults, options);
			showBoxSearch();
		};

		//render step first and set step
		var renderStep = function (tabs) {
			
			//load tab active and run step
			tabs.each(function (i) {
				var _this = $(this);
				if ( _this.hasClass('active') ) {
					mv.settings.tabActive = _this.data('tab');
					render(_this);
					return;
				}
			});

			tabs.on('click', function (e) {
				e.preventDefault();
				tabs.removeClass('active');
				$(this).addClass('active');

				mv.settings.tabActive = $(this).data('tab');
				
				mv.settings.wrapSuggest.html('');
				inputResize();

				mv.settings.currentStep = '';
				mv.settings.nextStep = '';
				mv.settings.arrStepRender = {};
				mv.settings.objSave = [];
				$('.valInputHidden').val('');
				
				render($(this));
			});

			function render (item) {
				mv.settings.arrStep = item.data('step');
				
				step(mv.settings.arrStep);
			}

			$(document).on('click', '.wrap-step li a', changeStep);

		};

		function stepLoad (itemRender) {
			switch( itemRender ) {
				case 'tinh-thanh':
					$('#'+itemRender).find('ul').html('');
					for ( var j in dataCities ) {
						var item = $('<li data-id="'+j+'"><a href="#" data-next-step="'+mv.settings.nextStep+'" data-item="tinh-thanh" data-slug-name='+ChangeToSlug(dataCities[j].name)+'>'+dataCities[j].name+'</a></li>');
						$('#'+itemRender).find('ul').append(item);
					}
					break;
				case 'loai-bds':
					$('#'+itemRender).find('ul').html('');
					for ( var j in dataCategories ) {
						var item = $('<li data-id="'+j+'"><a href="#" data-item="loai-bds" data-slug-name='+ChangeToSlug(dataCategories[j].name)+'>'+dataCategories[j].name+'</a></li>');
						$('#'+itemRender).find('ul').append(item);
					}
					break;
				case 'news':
					$('#'+itemRender).find('ul').html('');
					for ( var j in news ) {
						var item = $('<li data-id="'+j+'"><a href="#" data-item="news" data-slug-name='+ChangeToSlug(news[j].title)+'>'+news[j].title+'</a></li>');
						$('#'+itemRender).find('ul').append(item);
					}
					break;
				case 'loai-tin-tuc':
					$('#loai-tin-tuc').find('ul').html('');
					for ( var j in newsCatalogs ) {
						var item = $('<li data-id="'+j+'"><a href="#" data-item="loai-tin-tuc" data-slug-name='+ChangeToSlug(newsCatalogs[j].title)+'>'+newsCatalogs[j].title+'</a></li>');
						$('#loai-tin-tuc').find('ul').append(item);
					}
					break;
				default: 
					
			}
		}

		//chuyen doi giua cac step voi nhau
		var changeStep = function (e) {
			e.preventDefault();
			var _this = $(this),
				idItem = _this.parent().data('id'),
				nextStepData = _this.data('nextStep');

			if ( nextStepData != undefined && nextStepData.split('|').length > 1 ) {
				mv.settings.arrStepRender.root = _this.data('slugName');
				mv.settings.arrStepRender.step = nextStepData.split('|');
				mv.settings.currentStep = mv.settings.arrStepRender.step[0];
			}else {
				mv.settings.currentStep = nextStepData;
			}
			
			next();

			renderSuggest(_this);

			if ( nextStepData == undefined || nextStepData == '' ) {
				if ( mv.settings.tabActive == 'tin-tuc' ) {
					$(document).trigger( 'real-estate/news', [{data: '1'}, 'something'] );
				}else if ( mv.settings.tabActive == 'ban-thue' ) {
					$(document).trigger( 'real-estate/post', [{data: '1'}, 'something'] );
				}else if ( mv.settings.tabActive == 'mua-thue' ) {
					l(1);
				}
				
			}

			//render khi chon tinh thanh
			if ( mv.settings.currentStep === 'quan-huyen' ) {
				for ( var i in dataCities ) {
					if ( i == idItem ) {
						$('#quan-huyen').find('ul').html('');
						for ( var j in dataCities[i].districts ) {
							var item = $('<li data-id="'+j+'"><a  data-item="quan-huyen" data-next-step="'+mv.settings.nextStep+'" href="#" data-slug-name='+ChangeToSlug(dataCities[i].districts[j].name)+'>'+dataCities[i].districts[j].name+'</a></li>');
							$('#quan-huyen').find('ul').append(item);
						}
						break;
					}
				}
			}

			//render du-an theo quan-huyen
			if ( mv.settings.currentStep === 'loai-duan' ) {
				var idTinhThanh = mv.settings.wrapSuggest.find('li[data-item="tinh-thanh"]').data('id'),
					idQuanHuyen = mv.settings.wrapSuggest.find('li[data-item="quan-huyen"]').data('id'),
					idLoaiBDS = mv.settings.wrapSuggest.find('li[data-item="loai-bds"]').data('id');

				mv.settings.nextStep = '';

				for ( var i in dataCities ) {
					if ( i == idTinhThanh ) {
						for ( var j in dataCities[i].districts ) {
							if ( j == idQuanHuyen ) {
								$('#loai-duan').find('ul').html('');
								if ( dataCities[i].districts[j].projects == '' ) {
									$('#loai-duan').find('ul').append('<li class="notfound">Không có dự án nào !!!</li>');
									break;
								}
								for ( var k in dataCities[i].districts[j].projects ) {
									var item = $('<li data-id="'+k+'"><a  data-item="chung-cu" data-next-step="'+mv.settings.nextStep+'" href="#" data-slug-name='+ChangeToSlug(dataCities[i].districts[j].projects[k].name)+'>'+dataCities[i].districts[j].projects[k].name+'</a></li>');
									$('#loai-duan').find('ul').append(item);
								}
							}
						}
					}
				}
			}
			
			open();
			
		}

		var next = function () {
			var arrTemp = [];
			if ( typeof mv.settings.arrStep === 'object' ) {
				mv.settings.arrStep = JSON.stringify(mv.settings.arrStep);
				arrTemp.push(mv.settings.arrStep);
			}else {
				arrTemp = mv.settings.arrStep.split('|');
			}
			for ( var i = 0; i < arrTemp.length; i++ ) {
				if ( jsonCheck(arrTemp[i]) ) { // is object
					for ( var j in obj = JSON.parse(arrTemp[i]) ) {

						for ( var k in obj[j] ) {
							if ( k === mv.settings.arrStepRender.root ) {
								var arr = obj[j][k].template.split('|');
								for ( var t = 0; t < arr.length; t++ ) {
									if ( arr[t] == mv.settings.currentStep ) {
										mv.settings.nextStep = arr[t+1];
										break;
									}
								}
								break;
							}
						}
						if ( j == mv.settings.currentStep ) {
							$('#'+mv.settings.currentStep).find('li a').each(function () {
								var _this = $(this),
									slugName = _this.data('slugName'), tempStep;
								
								for ( var k in obj[j] ) {
									if ( slugName === k ) {
										_this.attr('data-next-step', obj[j][k].template);
										break;
									}else {
										_this.attr('data-next-step', obj[j][k].template);
									}
								}
								
							});
							
							break;
						}
					}
				}else { // is string
					
					if ( arrTemp[i] == mv.settings.currentStep && mv.settings.arrStepRender.root == undefined ) {
						if ( jsonCheck(arrTemp[i+1]) ) { // next is object
							for ( var j in obj = JSON.parse(arrTemp[i+1]) ) {
								mv.settings.nextStep = j;
								break;
							}
						}else { // next is string
							mv.settings.nextStep = arrTemp[i+1];
						}
					}else if ( mv.settings.arrStepRender.root != undefined ) {
						for ( var j = 0; j < mv.settings.arrStepRender.step.length; j++ ) {
							if ( mv.settings.arrStepRender.step[j] == mv.settings.currentStep ) {
								mv.settings.nextStep = mv.settings.arrStepRender.step[j+1];
							}
						}
					}

				}
			}
		};

		var step = function (str) {
			var arrTemp = [];
			if ( typeof str === 'object' ) {
				str = JSON.stringify(str);
				arrTemp.push(str);
			}else {
				arrTemp = str.split('|');
			}

			for ( var i = 0; i < arrTemp.length; i++ ) {
				if ( jsonCheck(arrTemp[i]) ) { // is object
					var jsonObj = JSON.parse(arrTemp[i]);
					
					for ( var j in jsonObj ) {
						if ( i == 0 && mv.settings.currentStep == '' ) {
							mv.settings.currentStep = j;
							getPlaceHolder(j);
						}
						stepLoad(j);
						for ( var k in jsonObj[j]) {
							var arrTemplate = jsonObj[j][k].template.split('|');
							if ( arrTemplate.length > 1 ) {
								for ( var t = 0; t < arrTemplate.length; t++ ) {
									mv.settings.nextStep = arrTemplate[t+1];
									stepLoad(arrTemplate[t]);
								}
							}else {
								stepLoad(jsonObj[j][k].template);
							}

							if ( i == 0 && mv.settings.currentStep != '' ) {
								$('#'+mv.settings.currentStep).find('li a').each(function () {
									var _this = $(this),
										slugName = _this.data('slugName'), tempStep;
									if ( slugName === k ) {
										tempStep = jsonObj[j][k].template;
										/*if ( arrTemplate.length > 1 ) {
											tempStep = arrTemplate[0];
										}*/
										_this.attr('data-next-step', tempStep);
									}
								});
							}
						}
					}
				}else { // is string
					if ( i == 0 && mv.settings.currentStep == '' ) {
						mv.settings.currentStep = arrTemp[i];
						getPlaceHolder(arrTemp[i]);
						if ( jsonCheck(arrTemp[i+1]) ) {
							for ( var j in jsonCheck(arrTemp[i+1]) ) {
								mv.settings.nextStep = j;
								break;	
							}
						}else {
							mv.settings.nextStep = arrTemp[i+1];
						}
					}
					stepLoad(arrTemp[i]);
				}
			}
		}

		//open box list
		var open = function (ajaxFlag) {
			
			mv.settings.searchWrap.addClass('hidden-effect');
			mv.settings.searchWrap.removeClass('active');

			getPlaceHolder(mv.settings.currentStep);

			$('#'+mv.settings.currentStep).find('h3').html(mv.settings.inputPlaceholder);
			
			$('#'+mv.settings.currentStep).removeClass('hidden-effect');
			
			setTimeout(function() {
                $('#'+mv.settings.currentStep).addClass('active');
            }, 30);

		};

		//close box list
		var close = function () {
			$('.btn-close-search').on('click', function (e) {
				e.preventDefault();
				$(this).closest('.search-wrap').removeClass('active');
				mv.settings.searchWrap.removeClass('active');

	            mv.settings.timeoutClose = setTimeout(function() {
	                mv.settings.searchWrap.addClass('hidden-effect');
	            }, 300);

	            return false;
			});
			$(document).on('click', clickOutsideevent);
		};

		//event click input type=text search
		var clickInput = function () {
			mv.settings.input.on('click', function(e) {
				e.preventDefault();
				
				open();
			});
		};

		//get step when redirect
		var getSuggest = function () {
			mv.settings.objSave = [];
			mv.settings.wrapSuggest.find('li').each(function () {
				var _this = $(this),
					idEl = _this.data('id'),
					nextStepBK = _this.data('nextStep'),
					txt = _this.find('span').text(),
					dataItem = _this.data('item'),
					objItem = {};

				objItem.idItem = idEl;
				objItem.nextStep = nextStepBK;
				objItem.text = txt;
				objItem.dataItem = dataItem;

				mv.settings.objSave.push(objItem);
			});

			var jsonStringify = mv.settings.objSave;
			$('#valTinhThanh').val('');
			$('#valQuanHuyen').val('');
			$('#valLoaiBDS').val('');
			$('#valTinTuc').val('');
			$('#valLoaiTinTuc').val('');
			$('#valDuAn').val('');
			for ( var i = 0; i < jsonStringify.length; i++ ) {
				if ( jsonStringify[i].dataItem == 'tinh-thanh' ) {
					$('#valTinhThanh').val(jsonStringify[i].idItem);
				}else if ( jsonStringify[i].dataItem == 'quan-huyen' ) {
					$('#valQuanHuyen').val(jsonStringify[i].idItem);
				}else if ( jsonStringify[i].dataItem == 'loai-bds' ) {
					$('#valLoaiBDS').val(jsonStringify[i].idItem);
				}else if ( jsonStringify[i].dataItem == 'news' ) {
					$('#valTinTuc').val(jsonStringify[i].idItem);
				}else if ( jsonStringify[i].dataItem == 'loai-tin-tuc' ) {
					$('#valLoaiTinTuc').val(jsonStringify[i].idItem);
				}else if ( jsonStringify[i].dataItem == 'chung-cu' ) {
					$('#valDuAn').val(jsonStringify[i].idItem);
				}
			}

			$('#valSearch').val(JSON.stringify(mv.settings.objSave, null, 2));
			$('#valTabActive').val($('.search-select.active').data('active'));

		};

		var renderSuggest = function (el) {
			var item = $('<li><i>x</i><span></span></li>'),
				idEl = el.parent().data('id'),
				nextStepBK = el.data('nextStep'),
				txt = el.text(),
				dataItem = el.data('item'),
				objItem = {};

			//edit get item
			if ( mv.settings.itemEdit.length > 0 ) {
				mv.settings.itemEdit.attr('data-id',idEl);
				mv.settings.itemEdit.attr('data-next-step',nextStepBK);
				mv.settings.itemEdit.find('span').html(txt);
				mv.settings.itemEdit.attr('data-item',dataItem);

				mv.settings.itemEdit.data('id', idEl);
				mv.settings.itemEdit.data('nextStep', nextStepBK);
				mv.settings.itemEdit.find('span').html(txt);
				mv.settings.itemEdit.data('item', dataItem);
				mv.settings.itemEdit = '';
			}else {
				item.attr('data-id',idEl);
				item.attr('data-next-step',nextStepBK);
				item.find('span').html(txt);
				item.attr('data-item',dataItem);
				mv.settings.wrapSuggest.append(item).show();
			}

			getSuggest();
			
			inputResize();

			//DELETE item suggest
			item.find('i').on('click', function (e) {
				e.preventDefault();
				getItemEvent($(this).parent(), true);
			});

			//UPDATE item suggest
			mv.settings.wrapSuggest.find('li').removeClass('active');
			item.on('click', function (e) {
				if ( !$(e.target).is('i') ) {// DETECT btn close
					mv.settings.wrapSuggest.find('li').removeClass('active');
					$(this).addClass('active');

					getItemEvent($(this), false);

					mv.settings.itemEdit = $(this);
				}
			});

			function getItemEvent(item, eventFlag) {//event: true -> delete, event: false ->update
				var index = item.index(),
					attrId = item.data('id'),
					attrNextStep = item.data('nextStep'),
					attrItem = item.data('item'),
					lengItem = mv.settings.wrapSuggest.find('li').length;

				mv.settings.currentStep = attrItem;
				mv.settings.nextStep = attrNextStep;

				//timeout for click outsite hiden document click
				setTimeout(function () {
					for ( var i = (lengItem-1); i >= index; i-- ) {
						if ( eventFlag ) { //delete
							mv.settings.wrapSuggest.find('li').eq(i).remove();
						}else { //update
							var temp = i;
							if ( i == index ) {
								break;
							}
							mv.settings.wrapSuggest.find('li').eq(temp).remove();
						}
					}
					getSuggest();
					inputResize();
				},35);
				
				getPlaceHolder(mv.settings.currentStep);

				open();

			}
		};

		var inputResize = function() {
			mv.settings.input.hide();
			var wWrapSuggest = mv.settings.wrapSuggest.outerWidth(),
				wWrap = $('.type-search').outerWidth();
			mv.settings.input.css('width', wWrap - wWrapSuggest).show();
		};

		//position box search
		var showBoxSearch = function () {
			setTimeout(function() {
				boxCenter();
			},150);
			
			clickInput();
			close();
			renderStep(tabs);
			loadCost.init();
		};

		var getPlaceHolder = function (item) {
			mv.settings.inputPlaceholder = $('#'+item).data('stepTitle');
			mv.settings.input.attr('placeholder',mv.settings.inputPlaceholder);
		};

		var boxCenter = function () {
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
		};

		var ChangeToSlug = function (text) {
			var slug;
		    //Đổi chữ hoa thành chữ thường
		    slug = text.toLowerCase();
		    //Đổi ký tự có dấu thành không dấu
		    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
		    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
		    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
		    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
		    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
		    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
		    slug = slug.replace(/đ/gi, 'd');
		    //Xóa các ký tự đặt biệt
		    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
		    //Đổi khoảng trắng thành ký tự gạch ngang
		    slug = slug.replace(/ /gi, "-");
		    //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
		    //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
		    slug = slug.replace(/\-\-\-\-\-/gi, '-');
		    slug = slug.replace(/\-\-\-\-/gi, '-');
		    slug = slug.replace(/\-\-\-/gi, '-');
		    slug = slug.replace(/\-\-/gi, '-');
		    //Xóa các ký tự gạch ngang ở đầu và cuối
		    slug = '@' + slug + '@';
		    slug = slug.replace(/\@\-|\-\@|\@/gi, '');
		    //In slug ra textbox có id “slug”
		    return slug;
		};

		var jsonCheck = function (str) {
		    try {
		        JSON.parse(str);
		    } catch (e) {
		        return false;
		    }
		    return true;
		};

		var clickOutsideevent = function (e) {
			var container = $(".outsideevent");
	        if ( !container.is(e.target) && container.has(e.target).length === 0 ){
	        	if( $(".wrap-cost-bds").hasClass('active') ) {
	        		loadCost.hide();
	        	}else {
	        		$('.btn-close-search').trigger('click');
	        	}
	        }
		};

		var loading = function () {
			if ( mv.settings.ajaxLoad ) {
                $('#'+mv.settings.currentStep).find('.search-item').append(mv.settings.loading);
			}else {
				$('.loading_search').fadeOut('fast', function() {
					$(this).remove();
				});
			}
		};

		$(window).resize(function () {
			boxCenter();
			inputResize();
		});

		var loadCost = {
			inputSubmitVal: $('.val-cost'),
			wrapList: $('.wrap-cost-bds'),
			choiceEvent: $('.wrap-cost-bds li span'),
			inputEvent: $('.cost-value'),
			flag: '',
			init: function () {
				loadCost.show();
				loadCost.clickChoice();
				loadCost.btnChoice();
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
			},
			btnChoice: function () {
				$('.btn-cost button').on('click',function () {
					var cosMin = $('#minCost'),
						cosMax = $('#maxCost');

					if ( $('.type-search ul .cost-suggest').length > 0 ) {
						if ( cosMin.val() != '' && cosMax.val() != '' ) {
							$('.type-search ul .cost-suggest').find('span').html(cosMin.val()+' - '+cosMax.val());
						}else if ( cosMin.val() != '' && cosMax.val() == '' ) {
							$('.type-search ul .cost-suggest').find('span').html(cosMin.val());
						}else if ( cosMin.val() == '' && cosMax.val() != '' ) {
							$('.type-search ul .cost-suggest').find('span').html(cosMax.val());
						}
						
					}else {
						if ( cosMin.val() != '' && cosMax.val() != '' ) {
							$('.type-search ul').append('<li class="cost-suggest"><i>x</i><span>'+cosMin.val()+' - '+cosMax.val()+'</span></li>');
						}else if ( cosMin.val() != '' && cosMax.val() == '' ) {
							$('.type-search ul').append('<li><i>x</i><span>'+cosMin.val()+'</span></li>');
						}else if ( cosMin.val() == '' && cosMax.val() != '' ) {
							$('.type-search ul').append('<li><i>x</i><span>'+cosMax.val()+'</span></li>');
						}
					}

					
				});
			}
		};

		init(tabs);

		return this;

	}


})(jQuery);
$(document).ready(function () {
	$('.search-select').MVS();
});


function animateSearch() {
    var $boxSearch = $('.box-search-header'),
        wBox = $boxSearch.outerWidth(),
        wWin = $('.wrap-search-home').outerWidth(),
        cBox = Math.floor(wWin/2 - wBox/2);
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
            l(1);
        },500);
    },500);
}