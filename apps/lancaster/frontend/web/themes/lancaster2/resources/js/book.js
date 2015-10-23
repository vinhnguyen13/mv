doc.ready(function(){
	book.init();
});

var book = {
	init: function(){
		this.attachDatePicker();
		this.attachCustomSelect();
	},
	attachDatePicker: function(){
		$('.date-picker').each(function(){
			var self = $(this);
			var icon = $('<i class="icon-calendar"></i>');
			
			icon.click(function(){
				self.focus();
			});

			self.wrap('<div class="date-picker-wrap"></div>').after(icon).datepicker();
		});
	},
	attachCustomSelect: function() {
		$('.custom-select').select2({
			minimumResultsForSearch: -1
		});
	}
}