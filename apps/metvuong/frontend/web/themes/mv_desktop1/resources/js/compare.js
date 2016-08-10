$(document).ready(function(){
	var compare = {
		countCompare: 0,
		numCheck: 3,
		setNumItem: $('.tool-compare button'),
		numGet: $('.tool-compare .num-show'),
		saveGetItem: $('.getCompare'),
		saveArr: [],
		compareItemCookie: 'compareItems',
		init: function () {
			$(document).on('click', '.flag-compare-set', function (e) {
				e.preventDefault();
				compare.add($(this));
			});
			$(document).on('click', '.flag-compare-remove', function (e) {
				e.preventDefault();
				compare.remove($(this));
			});
			
			var length = this.getCookieCompares().length;
			if (length == 0 ) {
				compare.numGet.text('');
			} else {
				compare.numGet.text('('+length+')');	
			}
		},
		add: function (item) {
			compare.countCompare += 1;
			item.removeClass('flag-compare-set').addClass('flag-compare-remove');
			item.find('.txt-change').text(lajax.t('Đã thêm so sánh'));
			item.find('.icon-balance-scale').attr('class','icon-close-icon');
			compare.numGet.text('('+compare.countCompare+')');
			compare.effectShow();
			compare.checkVal(item, 1);
			
			this.pushCookie(item.data('value'));
		},
		remove: function (item) {
			item.removeClass('flag-compare-remove').addClass('flag-compare-set');
			item.find('.txt-change').text(lajax.t('So Sánh'));
			item.find('.icon-close-icon').attr('class','icon-balance-scale');
			compare.countCompare -= 1;
			if ( compare.countCompare == 0 ) {
				compare.numGet.text('');
			}else {
				compare.numGet.text('('+compare.countCompare+')');	
			}
			
			compare.effectShow();
			compare.checkVal(item, 0);
			
			this.removeCookie(item.data('value'));
		},
		checkVal: function (item, flag) {
			var idItem = item.data('value');
			if ( flag ) {
				compare.saveArr.push(idItem);
			}else {
				for ( var i = 0; i < compare.saveArr.length; i++ ) {
					if ( idItem == compare.saveArr[i] ) {
						compare.saveArr.splice(i, 1);
					}
				}
			}
			
			var valSet = '['+compare.saveArr.toString()+']';
			compare.saveGetItem.val(valSet);
		},
		effectShow: function () {
			compare.setNumItem.addClass('get-show-num');
			setTimeout(function(){compare.setNumItem.removeClass('get-show-num')},300);
		},
		pushCookie: function(id) {
			var compareItems = this.getCookieCompares();
			
			var currentActived = [];
			
			for(var i in compareItems) {
				var item = compareItems[i].split(':');
				var isActive = item[1];
				
				if(isActive == '1') {
					currentActived.push(i);
				}
			}
			
			if(currentActived.length > 2) {
				for(var i = 2; i < currentActived.length; i++) {
					var index = currentActived[i];
					compareItems[index] = compareItems[index].replace(':1', ':0');
				}
			}
			
			compareItems.push(id + ':' + '1');
			
			setCookie(this.compareItemCookie, compareItems.join(','));
		},
		removeCookie: function(id) {
			var compareItems = this.getCookieCompares();
			
			for(var i in compareItems) {
				var item = compareItems[i].split(':');
				var _id = item[0];
				
				if(_id == id) {
					compareItems.splice(i, 1);
					break;
				}
			}
			
			setCookie(this.compareItemCookie, compareItems.join(','));
		},
		getCookieCompares: function() {
			var compareItems = getCookie(this.compareItemCookie);
			compareItems = compareItems ? compareItems.split(',') : [];
			
			return compareItems;
		}
	};

	compare.init();
});