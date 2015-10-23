var doc = $(document);
var win = $(window);

doc.ready(function(){
	main.attachBranchToggle();
	main.menuNavClick();
});

var main = {
	attachBranchToggle: function() {
		
		var timeout;
		
		$('#logo, #branch-wrap').hover(function(){
			clearTimeout(timeout);
			
			$('#branch-wrap').addClass('show');
		}, function(){
			timeout = setTimeout(function(){
				$('#branch-wrap').removeClass('show');
			}, 300);
		});
		
		$('#logo .arrow').click(function(e){
			e.preventDefault();
			e.stopPropagation();
			
			$('#branch-wrap').toggleClass('show-force').removeClass('show');
			$('#mobile-menu').removeClass('show');
		});
	},
	menuNavClick: function() {
		$('#menu-nav').click(function(e){
			e.preventDefault();
			
			var wWidth = win.width();
			
			var self = $(this);
			var nav = $('header .nav');
			
			var left = self.position().left - (nav.outerWidth() / 2) + (self.width() / 2);
			
			nav.css('left', left).toggleClass('show');
		});
		$('#mobile-menu-button').click(function(e){
			e.preventDefault();
			$('#mobile-menu').toggleClass('show');
			$('#branch-wrap').removeClass('show-force');
		});
	}
}