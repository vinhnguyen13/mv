var isMobile;
var readyInitialMap = false;
var isInitMap = false;

doc.ready(function(){
	isMobile = ($('#mobile-menu-button').css('display') == 'none') ? false : true;
	
	home.init();
});

var home = {
	collapseButton: null,
	map: null,
	ratio: 1920/996,
	sections: null,
	sectionTop: [],
	offsetHeader: 109,
	offsetSwitchSection: 300,
	currentActiveSection: 0,
	arrowDown: null,
	paralaxNav: null,
	paralaxNavItem: null,
	init: function() {
		win.load(function(){
			$('#paralax-overlay').remove();
			$('#home-page').addClass('loaded');
			
			home.initEl();
			home.attachWindowEvent();
			
			home.attachHomeMenuClick();
			home.calSection();
			home.attachLancasterList();
			
			win.scrollTop(0);
			jQuery.scrollSpeed(100, 600);
			
			home.initSlides();
			home.pushSectionTop();
		});
	},
	initSlides: function() {
		$('.swiper-group').each(function(){
			home.initSlide($(this));
		});
		this.centerSlideMap();
	},
	initSlide: function(groupEl) {
		var swiper = groupEl.find('.swiper-container').swiper({
			loop: true,
			slidesPerView: 'auto',
			nextButton: groupEl.find('.swiper-button-next'),
			prevButton: groupEl.find('.swiper-button-prev'),
		});
		
		var swiperMapEl = groupEl.find('.swiper-map');
		var swiperMapSlides = swiperMapEl.find('.swiper-slide');
		var slideContents = groupEl.find('.slide-content');
		
		swiperMapSlides.eq(0).addClass('active');
		slideContents.eq(0).addClass('show-on-mobile');
		
		swiperMapSlides.hover(function() {
			var self = $(this);
			var index = swiperMapSlides.index(self);
			var parentPosition = swiperMapEl.position();
			var position = self.position();
			var slideContent = slideContents.eq(index);
			
			var bottom = swiper.height - parentPosition.top - 2;
			var left = parentPosition.left + position.left - (slideContent.outerWidth() / 2) + (self.outerWidth() / 2);
			
			if(left < 0) {
				left = 0;
			} else if(left + slideContent.outerWidth() > win.width()) {
				left = win.width() - slideContent.outerWidth();
			}
			
			slideContent.css({
				left: left,
				bottom: bottom
			});
			
			self.addClass('hover');
			slideContent.addClass('show');
		}, function() {
			var self = $(this);
			var index = swiperMapSlides.index(self);

			self.removeClass('hover');
			slideContents.eq(index).removeClass('show');
		});
		
		slideContents.hover(function(){
			var self = $(this);
			var index = slideContents.index(self);
			
			swiperMapSlides.eq(index).addClass('hover');
			$(this).addClass('show');
		}, function() {
			var self = $(this);
			var index = slideContents.index(self);

			swiperMapSlides.eq(index).removeClass('hover');
			$(this).removeClass('show');
		});
		
		var swiperMap = swiperMapEl.swiper({
			slidesPerView: 'auto',
			spaceBetween: 0,
	        freeMode: true,
	        onTap: function(sm, event) {
	        	swiper.slideTo(sm.clickedIndex + swiper.slides.length/3);
	        	slideContents.eq(sm.clickedIndex).addClass('active');
	        }
		});
		
		swiper.on('onSlideChangeStart', function(sw){
			var index = sw.activeIndex % (sw.slides.length / 3);
			
			swiperMap.slideTo(index);
			
			$(swiperMap.slides).removeClass('active');
			$(swiperMap.slides[index]).addClass('active');
			
			slideContents.removeClass('show-on-mobile');
			slideContents.eq(index).addClass('show-on-mobile');
			
			home.pushSectionTop();
		});
	},
	centerSlideMap: function() {
		$('.swiper-group .swiper-map').each(function(){
			var self = $(this);
			var space = win.width() - self.width();
			var left = (space > 0) ? space/2 : 0;
			
			if(isMobile) {
				self.css('margin-left', left + 'px');
			} else {
				self.css({
					left: left,
					marginLeft: 'initial'
				});
			}
		});
	},
	calSection: function() {
		var height = win.height() - ($('header').outerHeight() + $('#paralax-nav').outerHeight());
		
		this.sections.each(function(){
			var self = $(this);
			
			if(self.hasClass('section-swiper')) {
				if(win.width() / height < home.ratio) {
					self.find('.swiper-container').width('auto');
				} else {
					self.find('.swiper-container').width(height * home.ratio);
				}
			} else {
				self.css({
					minHeight: height
				});
			}
		});
	},
	initEl: function() {
		this.sections = $('#paralax-page').find('div.section');
		this.paralaxNav = $('#paralax-nav');
		this.paralaxNavItem = this.paralaxNav.find('.items > li');
		this.offsetHeader = $('header').outerHeight() + $('#paralax-nav').outerHeight();
		this.collapseButton = $('.lancaster-list').find('.title');
	},
	attachWindowEvent: function() {
		win.resize(function(){
			isMobile = ($('#mobile-menu-button').css('display') == 'none') ? false : true;
			
			home.pushSectionTop();
			home.calSection();
			home.centerSlideMap();
		}).scroll(function(){
			var scrollTop = win.scrollTop();
			for(i = 0; i < home.sectionTop.length; i++) {
				if(scrollTop >= home.sectionTop[i].start - home.offsetSwitchSection && scrollTop <= home.sectionTop[i].end - home.offsetSwitchSection) {
					if(home.currentActiveSection != i) {
						home.activeHomeItem(i);
					}
					
					break;
				}
			}
		}).on('mousewheel', function(){
			$('html, body').stop(true, false);
		});
	},
	attachHomeMenuClick: function() {
		this.paralaxNavItem.find('> a').click(function(e){
			e.preventDefault();
			
			var index = home.paralaxNavItem.index($(this).parent());
			var top = Math.ceil(home.sections.eq(index).position().top - home.offsetHeader);
			var duration = (Math.abs(index - home.currentActiveSection) - 1) * 200 + 800;
			
			var easing = 'easeOutCubic';
			if(isMobile) {
				easing = 'swing';
				duration = 300;
			}
			
			$('html, body').stop(true, false).animate({
				scrollTop: top
			}, duration, 'easeOutCubic');
		});
	},
	activeHomeItem: function(index) {
		this.paralaxNavItem.filter('.active').removeClass('active');
		this.paralaxNavItem.eq(index).addClass('active');
		home.currentActiveSection = index;
		
		this.slideNavItem(index);
	},
	slideNavItem: function(index) {
		if(isMobile) {
			var activeItem = this.paralaxNavItem.eq(index);
			var left = 0;
			
			this.paralaxNavItem.each(function(ind, el){
				if(ind == index) {
					return false;
				}
				left += $(el).width();
			});
			
			left =  left - (win.width() / 2) + (activeItem.width() / 2);
			
			$('#paralax-nav').stop(true, false).animate({
				scrollLeft: left
			});
		}
	},
	pushSectionTop: function() {
		home.sectionTop = [];
		this.sections.each(function(){
			var start = $(this).position().top;
			var end = start + $(this).outerHeight();
			
			home.sectionTop.push({
				start: start,
				end: end
			});
		});
	},
	attachLancasterList: function() {
		if(readyInitialMap == true && isInitMap == false) {
			home.initMap();
		} else {
			readyInitialMap = true;
		}
		
		var beforeActive = $();
		
		this.collapseButton.click(function(e) {
			e.preventDefault();
			
			var self = $(this);
			var content = self.next();
			
			if(content.height() == 0) {
				beforeActive.height(0);
				beforeActive = content;
				
				beforeActive.height(beforeActive.get(0).scrollHeight);
				
				home.pushSectionTop();
			}
			
			var position = {lat: Number(self.data('lat')), lng: Number(self.data('lng'))};
			
			if(!self.data('marker')) {
				home.addMarker(position, self.text());
				self.data('marker', true);
			}
			
			home.map.setCenter(position);
		});
		
		home.collapseButton.eq(0).trigger('click');
	},
	initMap: function() {
		this.map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: 10.753647, lng: 106.711543},
		    zoom: 16
		});
	},
	addMarker: function(position, text) {
		var marker = new google.maps.Marker({
			draggable: false,
		    animation: google.maps.Animation.DROP,
		    map: home.map,
		    position: position
		});
		
		var infowindow = new google.maps.InfoWindow({content: '<div style="font-size: 12px; font-weight: bold; color: #234959;">' + text + '</div>'});
		infowindow.open(home.map, marker);
	}
}

function initMap() {
	if(readyInitialMap == true && isInitMap == false) {
		home.initMap();
	} else {
		readyInitialMap = true;
	}
}