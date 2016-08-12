$(document).ready(function(){
	var compare = {
		setNumItem: $('.tool-compare button'),
		numGet: $('.tool-compare .num-show'),
		saveGetItem: $('.getCompare'),
		compareItemCookie: 'compareItems',
		listingCompare: $('.listing-compare').find('li'),
		init: function () {
			$(document).on('click', '.flag-compare-set', this.add);
			$(document).on('click', '.flag-compare-remove', this.remove);
			$(document).on('click', '.remove-compare', function(e){
				e.preventDefault();
				
				var self = $(this).closest('li');
				var id = self.data('id');
				
				compare.removeCookie(id);
				
				if(self.find('.checkbox-ui').hasClass('active')) {
					compare.updateCompareView();
				}
				
				self.remove()
			});
			$(document).on('change', '.active-compare', compare.checkCompare);
			$(document).on('change', '.custom-compare', compare.updateCustomCompare);
		},
		updateCustomCompare: function() {
			var cb = $('#compare-box');
			
			$('.custom-compare').each(function(){
				var row = cb.find('.compare-' + $(this).data('row'));
				
				if(this.checked) {
					row.show();
				} else {
					row.hide();
				}
			});
		},
		checkCompare: function() {
			var self = $(this).closest('li');
			var id = self.data('id');
			
			if(this.checked) {
				var compareItems = compare.getCookieCompares();
				
				var currentActived = [];
				
				for(var i in compareItems) {
					var item = compareItems[i].split(':');
					var isActive = item[1];
					
					if(isActive == '1') {
						currentActived.push(i);
					}
				}
				
				if(currentActived.length > 2) {
					for(var i = 0; i < currentActived.length - 2; i++) {
						var index = currentActived[i];
						var item = compareItems[index].split(':');
						
						compare.updateCheckbox(item[0], {unchecked: true, trigger: false});
						//compare.updateCheckbox(item[0], false);
						
						compare.updateCookieId(item[0], '0');
					}
				}
				
				compare.updateCookieId(id, '1');
			} else {
				compare.updateCookieId(id, '0');
			}
			
			compare.updateCompareView();
		},
		updateCheckbox: function(id, status) {
			compare.listingCompare.each(function(){
				var self = $(this);
				
				if(self.data('id') == id) {
					//self.find('input').prop('checked', status);
					self.find('.checkbox-ui').checkbox_ui(status);

					return false;
				}
			});
		},
		updateCompareView: function() {
			var ids = [];
			
			compare.listingCompare.each(function(){
				var self = $(this);
				
				if(self.find('input').get(0).checked) {
					ids.push(self.data('id'));
				}
			});
			
			$('body').loading();
			
			$.get(url, {ids: ids}, function(r) {
				$('body').loading({done: true});
				
				$('.compare-block').html(r);
				
				compare.updateCustomCompare();
			});
		},
		add: function () {
			var item = $(this);
			var id = item.data('value');
			
			compare.sync(item, 1);
			
			if(!compare.isAdded(id)) {
				compare.pushCookie(id);
			}
		},
		remove: function () {
			var item = $(this);
			
			compare.sync(item, 0);
			
			compare.removeCookie(item.data('value'));
		},
		sync: function(item, status) {
			var id = item.data('value');

			if(item.is('a')) {
				var listItems = $('#listing-list').find('.compare-button');
				
				if(listItems.length) {
					listItems.each(function() {
						var self = $(this);
						
						if(self.data('value') == id) {
							item = item.add(self);
							
							return false;
						}
					});
				}				
			} else {
				var compareButton = $('.detail-listing-dt').find('.compare-button');
				
				if(compareButton.length && compareButton.data('value') == id) {
					item = item.add(compareButton);
				}
			}
			
			if(status) {
				item.removeClass('flag-compare-set').addClass('flag-compare-remove');
				item.find('.txt-change').text(lajax.t('Đã thêm so sánh'));
				item.find('.icon-balance-scale').attr('class','icon-close-icon');
			} else {
				item.removeClass('flag-compare-remove').addClass('flag-compare-set');
				item.find('.txt-change').text(lajax.t('So Sánh'));
				item.find('.icon-close-icon').attr('class','icon-balance-scale');
			}
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
				for(var i = 0; i < currentActived.length - 2; i++) {
					var index = currentActived[i];
					compareItems[index] = compareItems[index].replace(':1', ':0');
				}
			}
			
			compareItems.push(id + ':' + '1');
			
			this.updateCookie(compareItems);
		},
		updateCookieId: function(id, status) {
			var compareItems = compare.getCookieCompares();
			
			for(var i in compareItems) {
				var item = compareItems[i].split(':');
				var _id = item[0];
				
				if(_id == id) {
					compareItems[i] = _id + ':' + status;
					break;
				}
			}
			
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
			var compareItems = getServerCookie(this.compareItemCookie);
			compareItems = compareItems ? compareItems.split(',') : [];
			
			return compareItems;
		},
		updateCookie: function(compareItems) {
			setServerCookie(this.compareItemCookie, compareItems.join(','), 30);
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