$(document).ready(function(){
	var compare = {
		setNumItem: $('.tool-compare button'),
		numGet: $('.tool-compare .num-show'),
		saveGetItem: $('.getCompare'),
		compareItemCookie: 'compareItems',
		init: function () {
			$(document).on('click', '.flag-compare-set', this.add);
			$(document).on('click', '.flag-compare-remove', this.remove);
			
			this.setCounter(this.getCookieCompares());
		},
		add: function () {
			var item = $(this);
			var id = item.data('value');
			
			item.removeClass('flag-compare-set').addClass('flag-compare-remove');
			item.find('.txt-change').text(lajax.t('Đã thêm so sánh'));
			item.find('.icon-balance-scale').attr('class','icon-close-icon');
			
			if(!compare.isAdded(id)) {
				compare.pushCookie(id);
			}
		},
		remove: function () {
			var item = $(this);
			
			item.removeClass('flag-compare-remove').addClass('flag-compare-set');
			item.find('.txt-change').text(lajax.t('So Sánh'));
			item.find('.icon-close-icon').attr('class','icon-balance-scale');
			
			compare.removeCookie(item.data('value'));
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
			
			this.updateCookie(compareItems);
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
			
			this.updateCookie(compareItems);
		},
		getCookieCompares: function() {
			var compareItems = getCookie(this.compareItemCookie);
			compareItems = compareItems ? compareItems.split(',') : [];
			
			return compareItems;
		},
		updateCookie: function(compareItems) {
			setCookie(this.compareItemCookie, compareItems.join(','));
			this.setCounter(compareItems);
		},
		setCounter: function(compareItems) {
			if(compareItems.length == 0) {
				compare.numGet.text('');
			} else {
				compare.numGet.text('('+compareItems.length+')');	
			}
		},
		isAdded: function(id) {
			var compareItems = this.getCookieCompares();
			
			for(var i in compareItems) {
				var item = compareItems[i].split(':');
				var _id = item[0];
				
				if(_id == id) {
					return true;
				}
			}
			
			return false;
		}
	};

	compare.init();
});