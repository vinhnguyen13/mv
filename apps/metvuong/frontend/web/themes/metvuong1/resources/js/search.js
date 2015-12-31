(function($){
    $.fn.MVS = function (options) {
        
        var prices = {"thue": {"0" : "0","-1": "Giá bất kỳ","2000000" : "2 triệu","4000000" : "4 triệu","6000000" : "6 triệu","8000000" : "8 triệu","10000000": "10 triệu","12000000": "12 triệu","14000000": "14 triệu","16000000": "16 triệu","18000000": "18 triệu","20000000": "20 triệu",},"muaban": {"0" : "0","-1": "Giá bất kỳ","2000000000" : "2 tỷ","4000000000" : "4 tỷ","6000000000" : "6 tỷ","8000000000" : "8 tỷ","10000000000": "10 tỷ","12000000000": "12 tỷ","14000000000": "14 tỷ","16000000000": "16 tỷ","18000000000": "18 tỷ","20000000000": "20 tỷ"}};
        
        var getLang = getValCookie('language') == undefined ? 'vi-VN' : getValCookie('language');

        if ( getLang.search('vi-VN') >= 0 ) {
            var steps = {
                "step1": {
                    "mua-thue": {
                        "next": "tinh-thanh"
                    },
                    "tinh-thanh": {
                        "next": "quan-huyen"
                    },
                    "quan-huyen": {
                        "next": "loai-bds",
                        "prev": "tinh-thanh"
                    },
                    "loai-bds": {
                        "next": {
                            "chung-cu": {
                                "next": "loai-duan",
                                "prev": "loai-bds"
                            },
                            "common": {
                                "next": "min-max",
                                "prev": "loai-bds"
                            }
                        },
                        "prev": "quan-huyen"
                    }
                },
                "step2": {
                    "ban-thue": {
                        "next": "tinh-thanh"
                    },
                    "tinh-thanh": {
                        "next": "quan-huyen"
                    },
                    "quan-huyen": {
                        "next": "loai-bds",
                        "prev": "tinh-thanh"
                    },
                    "loai-bds": {
                        "prev": "quan-huyen"
                    }
                },
                "step3": {
                    "news": {
                        "next": {
                            "tin-tuc": {
                                "next": "loai-tin-tuc",
                                "prev": "news"
                            },
                            "du-an": {
                                "next": "tinh-thanh",
                                "prev": "news"
                            }
                        }
                    },
                    "tinh-thanh": {
                        "next": "quan-huyen"
                    },
                    "quan-huyen": {
                        "next": "loai-duan",
                        "prev": "tinh-thanh"
                    }
                }
            };
        }else if ( getLang.search('en-US') >= 0 ) {
            var steps = {
                "step1": {
                    "mua-thue": {
                        "next": "tinh-thanh"
                    },
                    "tinh-thanh": {
                        "next": "quan-huyen"
                    },
                    "quan-huyen": {
                        "next": "loai-bds",
                        "prev": "tinh-thanh"
                    },
                    "loai-bds": {
                        "next": {
                            "apartment": {
                                "next": "loai-duan",
                                "prev": "loai-bds"
                            },
                            "common": {
                                "next": "min-max",
                                "prev": "loai-bds"
                            }
                        },
                        "prev": "quan-huyen"
                    }
                },
                "step2": {
                    "ban-thue": {
                        "next": "tinh-thanh"
                    },
                    "tinh-thanh": {
                        "next": "quan-huyen"
                    },
                    "quan-huyen": {
                        "next": "loai-bds",
                        "prev": "tinh-thanh"
                    },
                    "loai-bds": {
                        "prev": "quan-huyen"
                    }
                },
                "step3": {
                    "news": {
                        "next": {
                            "news": {
                                "next": "loai-tin-tuc",
                                "prev": "news"
                            },
                            "projects": {
                                "next": "tinh-thanh",
                                "prev": "news"
                            }
                        }
                    },
                    "tinh-thanh": {
                        "next": "quan-huyen"
                    },
                    "quan-huyen": {
                        "next": "loai-duan",
                        "prev": "tinh-thanh"
                    }
                }
            };
        }

        var defaults = {
            input: $('#searchInput'),
            wrapSuggest: $('.type-search ul'),
            searchWrap: $('.search-wrap'),
            btnSubmit: $('#btn-search'),
            submitCallBack: function() {}
        };

        if ( this.length == 0 ) return this;

        var mv = {},
            tabs = this,
            itemEdit = '',
            flagEdit = false,
            next = '',
            prev = '',
            current = '',
            inputPlaceholder = '',
            stepData = {},
            tabActiveGet = '',
            objSave = [],
            flagCookie = false,
            lengReloadSuggest = 0,
            idTinhThanhReload,
            idHinhThuc = '';

        //get start
        function init () {
            mv.settings = $.extend({}, defaults, options);
            showBoxSearch();
            if ( $('header.wrap-page-home').length > 0 ) {
                scrollFixed();
            }
        };

        //event click input type=text search
        function clickInput () {
            mv.settings.input.on('click', function(e) {
                e.preventDefault();
                if ( !reopen() ) {
                    open();
                    close();
                }
            });
        };

        function submitSearchVal() {
            var tabActive = $('.search-select.active').data('active');
            
            $('#valTabActive').val(tabActive);

            objSave = [];

            objSave.push(tabActive);
            
            mv.settings.wrapSuggest.find('li').each(function () {
                var _this = $(this),
                    idItem = _this.data('id'),
                    nextData = _this.data('next'),
                    prevData = _this.data('prev'),
                    itemData = _this.data('item'),
                    txt = _this.find('span').html(),
                    objItem = {};

                objItem.idItem = idItem;
                objItem.nextData = nextData;
                objItem.prevData = prevData;
                objItem.itemData = itemData;
                objItem.txt = txt;

                if ( itemData == 'min-max' ) {
                    var cloneItem = _this.clone();
                    cloneItem.find('i').remove();
                    cloneItem.find('span').remove();
                    objItem.icon = cloneItem.html();
                }

                objSave.push(objItem);

                if ( itemData == 'tinh-thanh' ) {
                    $('#valTinhThanh').val(idItem);
                }else if ( itemData == 'quan-huyen' ) {
                    $('#valQuanHuyen').val(idItem);
                }else if ( itemData == 'loai-bds' ) {
                    $('#valLoaiBDS').val(idItem);
                }else if ( itemData == 'loai-duan' ) {
                    $('#valDuAn').val(idItem);
                }else if ( itemData == 'news' ) {
                    $('#valTinTuc').val(idItem);
                }else if ( itemData == 'loai-tin-tuc' ) {
                    $('#valLoaiTinTuc').val(idItem);
                }else if ( itemData == 'mua-thue' || itemData == 'ban-thue' ) {
                    $('#valType').val(idItem);
                }else {
                    l("no data submit");
                }
            });
            
            setValCookie(JSON.stringify(objSave));

        };

        function setValCookie (val) {
            document.cookie = "valSearch="+val+"; path=/";
        };

        function getValCookie (name) {
            var value = "; " + document.cookie,
                parts = value.split("; "+name+"="),
                valCookie;

            if (parts.length == 2) 
                valCookie = parts.pop().split(";").shift();

            return valCookie;
        };

        function cookieSearch() {
            var valCookie = getValCookie('valSearch'),
                objItemGet;

            if ( location.pathname == '/' ) {
                delete_cookie("valSearch");
                return;
            }

            try {
                objItemGet = JSON.parse(valCookie);
            }catch (e) {
                objItemGet = [];
            }

            if ( objItemGet.length > 0 ) {
                flagCookie = true;
            }

            for ( var i = 0; i < objItemGet.length; i++ ) {
                if ( i === 0 ) { // tab active
                    $('.search-select').removeClass('active');
                    $('.search-select[data-active="'+objItemGet[i]+'"]').addClass('active');
                    var stepOld = $('.search-select[data-active="'+objItemGet[i]+'"]').data('step');
                    getDataStep(stepOld);
                }else {
                    renderSuggest(objItemGet[i], i, objItemGet.length);
                }
            }
        };

        function delete_cookie(name) {
            document.cookie = name + '=;';
        };

        //open box list
        function open () {
            mv.settings.searchWrap.addClass('hidden-effect');
            mv.settings.searchWrap.removeClass('active');

            getPlaceHolder(current);

            $('#'+current).find('h3').html(inputPlaceholder);
            
            $('#'+current).removeClass('hidden-effect');
            
            setTimeout(function() {
                $('#'+current).addClass('active');
            }, 30);
        };

        //close box list
        function close () {
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

        //render step first and set step
        function renderStep () {
            //load tab active and run step
            tabs.each(function () {
                var _this = $(this),
                    step = _this.data('step'),
                    stepFirstShow = '';
                
                if ( _this.hasClass('active') ) {
                    menuScroll (_this);
                    tabActiveGet = _this.data('active');

                    iconChange (tabActiveGet);
                    
                    if ( !flagCookie || current != '' ) {
                        stepFirst(step);
                    }
                    return;
                }
            });

            tabs.on('click', function (e) {
                e.preventDefault();
                var _this = $(this),
                    step = _this.data('step');
                menuScroll (_this);
                tabActiveGet = _this.data('active');

                iconChange (tabActiveGet);

                tabs.removeClass('active');
                $(this).addClass('active');
                mv.settings.wrapSuggest.html('');
                inputResize();

                stepFirst(step);

                //show and hide menu khi scroll
                if ( _this.closest('.options-search').hasClass('search-dropdown') ) {
                    _this.closest('.options-search').removeClass('search-dropdown');
                }
            });

            function stepFirst(step) {
                for ( var i in steps ) {
                    if ( i == step ) {
                        stepData = steps[i];
                        for ( var j in stepData ) {
                            stepLoad(j);
                            stepGet(j);
                            if ( mv.settings.wrapSuggest.find('li').length > 0 && flagCookie ) { // first reload suggest
                                getPlaceHolder(current);
                                flagCookie = false;
                            }else {
                                getPlaceHolder(j);    
                                current = j;
                            }
                            break;
                        }
                    }
                }
            };
        };

        function getDataStep(step) {
            for ( var i in steps ) {
                if ( i == step ) {
                    stepData = steps[i];
                }
            }
        };

        function stepLoad (itemRender, idLoadBy) {
            switch( itemRender ) {
                case 'tinh-thanh':
                    $('#'+itemRender).find('ul').html('');
                    for ( var j in dataCities ) {
                        var item = $('<li data-id="'+j+'"><a href="#" data-item="tinh-thanh" data-slug-name="'+ChangeToSlug(dataCities[j].name)+'" data-next="'+next+'" data-prev="'+prev+'">'+dataCities[j].name+'</a></li>');
                        if ( item.find('a').data('slugName') != 'ho-chi-minh' ) {
                            item.find('a').addClass('disable');
                        }
                        $('#'+itemRender).find('ul').append(item);
                    }
                    break;
                case 'quan-huyen':
                    for ( var i in dataCities ) {
                        if ( i == idLoadBy ) {
                            $('#'+itemRender).find('ul').html('');
                            for ( var j in dataCities[i].districts ) {
                                var item = $('<li data-id="'+j+'"><a  data-item="quan-huyen" href="#" data-slug-name="'+ChangeToSlug(dataCities[i].districts[j].name)+'">'+dataCities[i].districts[j].name+'</a></li>');
                                $('#'+itemRender).find('ul').append(item);
                            }
                            break;
                        }
                    }
                    break;
                case 'loai-duan':
                    var idTinhThanh = mv.settings.wrapSuggest.find('li[data-item="tinh-thanh"]').data('id'),
                        idQuanHuyen = mv.settings.wrapSuggest.find('li[data-item="quan-huyen"]').data('id'),
                        idLoaiBDS = mv.settings.wrapSuggest.find('li[data-item="loai-bds"]').data('id');

                    for ( var i in dataCities ) {
                        if ( i == idTinhThanh ) {
                            for ( var j in dataCities[i].districts ) {
                                if ( j == idQuanHuyen ) {
                                    $('#'+itemRender).find('ul').html('');
                                    if ( dataCities[i].districts[j].projects == '' ) {
                                        $('#'+itemRender).find('ul').append('<li class="notfound">Không có dự án nào !!!</li>');
                                        break;
                                    }
                                    for ( var k in dataCities[i].districts[j].projects ) {
                                        var item = $('<li data-id="'+k+'"><a  data-item="loai-duan" href="#" data-slug-name='+ChangeToSlug(dataCities[i].districts[j].projects[k].name)+'>'+dataCities[i].districts[j].projects[k].name+'</a></li>');
                                        $('#'+itemRender).find('ul').append(item);
                                    }
                                }
                            }
                        }
                    }
                    break;
                case 'loai-bds':
                    $('#'+itemRender).find('ul').html('');
                    for ( var j in dataCategories ) {
                        if ( dataCategories[j].apply_to_type == idLoadBy || dataCategories[j].apply_to_type == 3 ) {
                            var item = $('<li data-id="'+j+'"><a href="#" data-item="loai-bds" data-slug-name="'+ChangeToSlug(dataCategories[j].name)+'" data-template="'+dataCategories[j].template+'">'+dataCategories[j].name+'</a></li>');
                            $('#'+itemRender).find('ul').append(item);
                        }
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
                    //l("Not Data");

            }
        };

        //chuyen doi giua cac step voi nhau
        function changeStep (e) {
            e.preventDefault();
            var _this = $(this),
                idItem = _this.parent().data('id');
            
            next = _this.data('next');
            prev = _this.data('prev');

            renderSuggest(_this);

            if ( next == undefined || next == '' ) {
                getPlaceHolder(null, true);
                if ( tabActiveGet != 1 ) 
                    submitSearchVal();
                if ( tabActiveGet == '2' ) {
                    $(document).trigger('real-estate/post', [{data: '1'}, 'something', inputResize]);
                }else if ( tabActiveGet == '3' ) {
                    $(document).trigger('real-estate/news', [{data: '1'}, 'something', inputResize]);
                }
            }

            current = next;

            stepLoad(current);

            //render quan-huyen khi chon city
            if ( current == 'quan-huyen' ) {
                stepLoad(current, idItem);
            }

            //render du-an theo quan-huyen
            if ( current === 'loai-duan' ) {
                stepLoad('loai-duan');
            }

            //render loai-bds hinh thuc mua-ban-thue
            if ( current === 'loai-bds' ) {
                mv.settings.wrapSuggest.find('li').each(function () {
                    if ( $(this).data('item') == 'mua-thue' ) {
                        idHinhThuc = $(this).data('id');
                    }else if ( $(this).data('item') == 'ban-thue' ) {
                        idHinhThuc = $(this).data('id');
                    }
                });
                
                stepLoad('loai-bds', idHinhThuc);

                loadCost.init(idHinhThuc);
            }            

            stepGet(next);

            open();
        };

        function stepGet (stepCurrent) {
            for ( var i in stepData ) {
                if ( i == stepCurrent ) {
                    next = stepData[i].next;
                    prev = stepData[i].prev;

                    if ( typeof next == 'object' ) {
                        $('#'+stepCurrent).find('ul li a').each(function () {
                            var _this = $(this),
                                slug = _this.data('slugName'),
                                template = _this.data('template');

                            if ( template != undefined ) {
                                _this.attr('data-next', template);
                            }else {
                                for ( var j in next ) {
                                    if ( slug == j ) {
                                        _this.attr('data-next', next[j].next);
                                        _this.attr('data-prev', next[j].prev);
                                        _this.addClass('flag');
                                        break;
                                    }
                                    if ( !_this.hasClass('flag') ) {
                                        _this.attr('data-next', next[j].next);
                                        _this.attr('data-prev', next[j].prev);
                                    }
                                }
                            }
                        });
                        
                    }else {
                        $('#'+stepCurrent).find('ul li a').attr('data-next', next);
                        $('#'+stepCurrent).find('ul li a').attr('data-prev', prev);
                    }

                    break;
                }else {

                }
            }
        };

        // render suggest khi chon va reload suggest khi search
        function renderSuggest (el, count, lenSuggest) {
            var flag = el.idItem >= 0 ? true : false,
                item = $('<li><i>x</i><span></span></li>'),
                idEl = flag ? el.idItem : el.parent().data('id'),
                nextAttr = flag ? el.nextData : el.data('next'),
                prevAttr = flag ? el.prevData : el.data('prev'),
                txt = flag ? el.txt : el.text(),
                dataItem = flag ? el.itemData : el.data('item');

            if ( current == 'min-max' ) {
                current = '';
            }

            if ( flag ) {
                //add icon price
                if ( dataItem == 'min-max' ) {
                    item.append(el.icon);
                }
            }

            var valMin = $('.box-cost[data-tab="min"] .cost-value').val(),
                valMax = $('.box-cost[data-tab="max"] .cost-value').val(),
                txtMerge = '';

            if ( valMin != '' ) {
                item.append('<em class="fa fa-long-arrow-up"></em>');
            }else if ( valMax != '' ) {
                item.append('<em class="fa fa-long-arrow-down"></em>');
            }

            // update text suggest min-max
            if ( mv.settings.wrapSuggest.find('li[data-item=min-max]').length > 0 ) {
                itemEdit = mv.settings.wrapSuggest.find('li[data-item=min-max]');
                itemEdit.find('em').remove();
                if ( valMax != '' && !el.parent().hasClass('anyVal') && valMin != '' ) {
                    txtMerge = valMin+' - '+valMax;
                }else if ( valMin != '' ) { // chi gia tri Min
                    txtMerge = valMin;
                    itemEdit.append('<em class="fa fa-long-arrow-up"></em>');
                }else if ( valMax != '' ) { // chi gia tri Max
                    txtMerge = valMax;
                    itemEdit.append('<em class="fa fa-long-arrow-down"></em>');
                }
                
                txt = txtMerge;
                getPlaceHolder(null, true);
            }

            //edit get item
            if ( itemEdit.length > 0 ) {
                itemEdit.attr('data-id',idEl);
                itemEdit.attr('data-next',nextAttr);
                itemEdit.attr('data-prev',prevAttr);
                itemEdit.find('span').html(txt);
                itemEdit.attr('data-item',dataItem);

                itemEdit.data('id', idEl);
                itemEdit.data('next', nextAttr);
                itemEdit.data('prev', prevAttr);
                itemEdit.find('span').html(txt);
                itemEdit.data('item', dataItem);
                itemEdit = '';
            }else {
                item.attr('data-id',idEl);
                item.attr('data-next',nextAttr);
                item.attr('data-prev',prevAttr);
                item.find('span').html(txt);
                item.attr('data-item',dataItem);
                mv.settings.wrapSuggest.append(item).show();
            }

            if ( flag ) {
                if ( dataItem == 'tinh-thanh' ) {
                    idTinhThanhReload = idEl;
                }

                // reload price
                if ( dataItem == 'mua-thue' ) {
                    idHinhThuc = idEl;
                }else if (dataItem == 'ban-thue') {
                    idHinhThuc = idEl;
                }
                loadCost.init(idHinhThuc);

                stepLoad(dataItem, idTinhThanhReload);

                $('#'+dataItem).find('ul li a').attr('data-next', nextAttr);
                $('#'+dataItem).find('ul li a').attr('data-prev', prevAttr);

                if ( dataItem == 'loai-bds' ) {
                    stepGet(dataItem);
                }
                
                if ( (nextAttr != undefined || nextAttr != '') && count == (lenSuggest-1) ) {
                    current = nextAttr;
                    stepLoad(nextAttr, idTinhThanhReload);
                    stepGet(current);
                    close();
                }
            }
            
            inputResize();

            //delete item suggest
            item.find('i').on('click', function (e) {
                e.preventDefault();
                itemEdit = '';
                getItemEvent($(this).parent(), true);
            });

            //update item suggest
            mv.settings.wrapSuggest.find('li').removeClass('active');
            item.on('click', function (e) {
                if ( !$(e.target).is('i') ) {// DETECT btn delete
                    mv.settings.wrapSuggest.find('li').removeClass('active');
                    $(this).addClass('active');

                    getItemEvent($(this), false);

                    itemEdit = $(this);
                }
            });

            function getItemEvent(item, eventFlag) {//event: true -> delete, event: false ->update
                var index = item.index(),
                    attrId = item.data('id'),
                    attrNextStep = item.data('next'),
                    attrItem = item.data('item'),
                    lengItem = mv.settings.wrapSuggest.find('li').length;

                current = attrItem;
                next = attrNextStep;

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
                    inputResize();
                },35);
                
                getPlaceHolder(current);

                // reset value cost
                $('.cost-value').val('');

                open();

            }
        };

        // reopen khi edit và click outsite và click input
        function reopen () {

            if ( itemEdit.length > 0 ) {
                mv.settings.wrapSuggest.find('li').removeClass('active');
                //stepGet(current);
                current = itemEdit.data('next');
                if ( current == '' ) {
                    getPlaceHolder(null, true);
                    return false;
                }
                itemEdit = '';
                setTimeout(function () {open();},310);

                return true;
            }

            return false;
        }

        // show box search khi load page
        function showBoxSearch () {
            cookieSearch();
            
            setTimeout(function() {
                boxCenter();
            },150);

            renderStep();

            clickInput();
            
            $(document).on('click', '.wrap-step li a:not(.disable)', changeStep);

            $(document).on('click', '#btn-search, .btn-cost button', function (e) {
                e.preventDefault();
                $('.btn-close-search').trigger('click');
                $(document).trigger("submit_search", [{data: '1'}, 'something', inputResize]);

                submitSearchVal();
            });
        };

        // can chinh box search theo ty le man hinh + effect
        function boxCenter () {
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
                'transform' : 'translateX('+leftCenterLogo+'px) translateY('+topCenterLogo+'px)',
                '-moz-transform' : 'translateX('+leftCenterLogo+'px) translateY('+topCenterLogo+'px)',
                '-webkit-transform' : 'translateX('+leftCenterLogo+'px) translateY('+topCenterLogo+'px)',
                '-ms-transform' : 'translateX('+leftCenterLogo+'px) translateY('+topCenterLogo+'px)'

            });
            searchBox.css({
                'transform' : 'translateX('+leftCenterSearch+'px) translateY('+topCenterSearch+'px)',
                '-webkit-transform' : 'translateX('+leftCenterSearch+'px) translateY('+topCenterSearch+'px)',
                '-moz-transform' : 'translateX('+leftCenterSearch+'px) translateY('+topCenterSearch+'px)',
                '-ms-transform' : 'translateX('+leftCenterSearch+'px) translateY('+topCenterSearch+'px)'
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

        $(window).resize(function () {
            boxCenter();
            inputResize();
        });

        // price min-max khi chon search theo gia
        var loadCost = {
            inputSubmitVal: $('.val-cost'),
            wrapList: $('.wrap-cost-bds'),
            choiceEvent: $('.wrap-cost-bds li span'),
            inputEvent: $('.cost-value'),
            flag: '',
            numPriceShow: 11,
            priceAdd: '',
            txtAnyPrice: '',
            init: function (idHinhThuc) {
                loadCost.show(idHinhThuc);
            },
            show: function (idHinhThuc) {
                loadCost.inputEvent.unbind('click');
                loadCost.inputEvent.on('click', function (e) {
                    e.preventDefault();
                    var _this = $(this),
                        tabShow = _this.closest('.box-cost').data('tab');

                    loadCost.flag = loadCost.toggleCost(tabShow);
                    
                    if ( tabShow == 'min' ) {
                        $('.box-cost[data-tab="min"] ul').html('');
                    }

                    if ( tabShow == 'max' && $('.box-cost[data-tab="min"] .cost-value').val() != '' ) {
                        var valGetMin = $('.box-cost[data-tab="min"] .valPrice').val();
                        loadCost.priceMaxRender(parseInt(valGetMin));
                    }else {
                        loadCost.renderPrice(idHinhThuc, tabShow);
                    }

                    loadCost.hide();

                    loadCost.open(tabShow);
                });
            },
            renderPrice: function (idHinhThuc, tabShow) {
                $('.box-cost[data-tab="max"] ul').html('');
                var arrType = {};
                for ( var i in prices ) { // prices theo hinh thuc mua-ban-thue
                    if ( idHinhThuc == 1 && i == 'muaban' ) { // hinh thuc mua-ban
                        arrType = prices[i];
                    }else if ( idHinhThuc == 2 && i == 'thue' ) { // hinh thuc cho thue - muon thue
                        arrType = prices[i];
                    }
                }

                for ( var j in arrType ) {
                    var item;
                    if ( parseInt(j) < 0 ) {
                        txtAnyPrice = arrType[j];
                        continue;
                    }
                    item = $('<li data-id="'+idHinhThuc+'" data-cost="'+j+'"><span data-item="min-max" data-next="" data-prev="">'+arrType[j]+'</span></li>');
                    $('.box-cost[data-tab='+tabShow+'] ul').append(item);
                    loadCost.clickChoice(item.find('span'));
                }
                //render element batky price max
                if ( tabShow == 'max' ) {
                    var item = $('<li data-id="'+idHinhThuc+'" class="anyVal"><span data-item="min-max" data-next="" data-prev="">'+txtAnyPrice+'</span></li>');
                    $('.box-cost[data-tab='+tabShow+'] ul').append(item);
                    loadCost.clickChoice(item.find('span'));    
                }
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
            clickChoice: function(itemVal) {
                itemVal.on('click', function() {
                    var _this = $(this),
                        textChoice = _this.html(),
                        costValueChoice = _this.parent().data('cost'),
                        tabIndex = _this.closest('.box-cost').data('tab');

                    //value input show number and text vd: 1 ty
                    $('.box-cost[data-tab='+tabIndex+'] .cost-value').val(textChoice);

                    //value input get number price vd: 1000000000
                    $('.box-cost[data-tab='+tabIndex+'] .valPrice').val(costValueChoice);

                    loadCost.hide();

                    if( loadCost.flag == 'max') {
                        $('.box-cost[data-tab="max"] ul').html('');
                        $('.box-cost[data-tab='+loadCost.flag+'] .cost-value').val('');

                        if ( parseInt($('.box-cost[data-tab="min"] .cost-value').val()) != 0 ) {
                            loadCost.priceMaxRender(costValueChoice);
                        }else {
                            loadCost.renderPrice(idHinhThuc, loadCost.flag);
                        }

                        loadCost.open(loadCost.flag);
                        
                        loadCost.flag = 'min';
                    }

                    renderSuggest(_this);
                });
            },
            priceUnit: function (num) {
                isNegative = false
                if (num < 0) {
                    isNegative = true
                }
                num = Math.abs(num);
                if (num >= 1000000000) {
                    formattedNumber = (num / 1000000000).toFixed(0).replace(/\.0$/, '') + ' tỷ';
                    loadCost.priceAdd = 2000000000;
                } else if (num >= 1000000) {
                    formattedNumber =  (num / 1000000).toFixed(0).replace(/\.0$/, '') + ' triệu';
                    loadCost.priceAdd = 2000000;
                } else  if (num >= 1000) {
                    formattedNumber =  (num / 1000).toFixed(0).replace(/\.0$/, '') + ' nghìn';
                    loadCost.priceAdd = 2000;
                } else {
                    formattedNumber = num;
                    loadCost.priceAdd = 2;
                }   
                if(isNegative) { formattedNumber = '-' + formattedNumber }
                return formattedNumber;
            },
            priceMaxRender: function (costValueChoice) {
                //render price max
                var priceFirst = parseInt(costValueChoice);
                for ( var i = 0; i < loadCost.numPriceShow; i++ ) {
                    var costStr = loadCost.priceUnit(costValueChoice);
                    if ( costValueChoice != priceFirst ) {
                        var item = $('<li data-id="'+idHinhThuc+'" data-cost="'+costValueChoice+'"><span data-item="min-max" data-next="" data-prev="">'+costStr+'</span></li>');
                        $('.box-cost[data-tab=max] ul').append(item);
                        loadCost.clickChoice(item.find('span'));
                    }
                    costValueChoice += loadCost.priceAdd;
                }
                //render element batky
                var item = $('<li data-id="'+idHinhThuc+'" class="anyVal"><span data-item="min-max" data-next="" data-prev="">'+txtAnyPrice+'</span></li>');
                $('.box-cost[data-tab=max] ul').append(item);
                loadCost.clickChoice(item.find('span'));
            }
        };

        // click outsite hide element
        function clickOutsideevent (e) {
            var container = $(".outsideevent");
            
            if( $(".wrap-cost-bds").hasClass('active') ) {
                loadCost.hide();
            }

            if ( !container.is(e.target) && container.has(e.target).length === 0 ){
                if ( mv.settings.searchWrap.is(':visible') ) {
                    $('.btn-close-search').trigger('click');
                    reopen();    
                }

                if ( $('.options-search').hasClass('search-dropdown') ) {
                    $('.options-search').removeClass('search-dropdown');
                }
            }
        };

        // get placeholder tung step
        function getPlaceHolder (item, nullVal) {
            if ( nullVal ) {
                mv.settings.input.attr('placeholder','');
            }else {
                inputPlaceholder = $('#'+item).data('stepTitle');
                mv.settings.input.attr('placeholder',inputPlaceholder);    
            }
        };

        // resize input khi insert suggest
        function inputResize () {
            mv.settings.input.hide();
            var wWrapSuggest = mv.settings.wrapSuggest.outerWidth(),
                wWrap = $('.type-search').outerWidth();
            mv.settings.input.css('width', wWrap - wWrapSuggest).show();
        };

        // chuyen doi string toi slug key -> xac dinh step dac biet
        function ChangeToSlug (text) {
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

        //change icon search
        function iconChange (tabActive) {
            if ( tabActive == 1 ) {
                mv.settings.btnSubmit.find('em').attr('class','fa fa-search');
            }else if ( tabActive == 2 ) {
                mv.settings.btnSubmit.find('em').attr('class','fa fa-pencil-square-o');
            }else if ( tabActive == 3 ) {
                mv.settings.btnSubmit.find('em').attr('class','fa fa-file-text');
            }
        };

        function scrollFixed () {
            var $header = $('.cd-secondary-nav'),
                $container = $('.o-wrapper'),
                hHeader = $header.outerHeight(),
                valShow = 0,
                flagShow = false;

            $(window).on('scroll', function(){
                valShow = $(window).scrollTop();

                if( valShow >= hHeader ) {
                    if( flagShow ) {
                        return;
                    }
                    $header.hide();
                    $container.addClass('pdTContainer');
                    $header.addClass('is-fixed animate-children');
                    $('.options-search').addClass('outsideevent');

                    setTimeout(function() {
                        $header.show();
                        setTimeout(function () {
                            $header.addClass('show-fixed');
                            inputResize();
                        },50);
                    }, 150);

                    $('.icon-selected a').on('click', function() {
                        var _this = $(this),
                            $rootParent = _this.closest('.options-search');
                        $rootParent.addClass('search-dropdown');

                        return false;
                    });

                    flagShow = true;
                }else {
                    $('.options-search').removeClass('outsideevent');
                    $('.options-search').removeClass('search-dropdown');
                    $header.removeClass('is-fixed animate-children show-fixed');
                    flagShow = false;
                    $container.removeClass('pdTContainer');
                }

            });
        };

        function menuScroll (item) {
            $('.icon-selected span').html(item.find('span').html());
        };

        init();
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
        },500);
    },500);
}