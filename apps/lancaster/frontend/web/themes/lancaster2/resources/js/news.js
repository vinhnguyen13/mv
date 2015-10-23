WebFont.load({
	google: {
		families: ['Roboto:400,700', 'Noticia Text:400italic,400,700,700italic']
	},
	active: function() {
		news.init();
	}
});

var news = {
	items: $(),
	init: function() {
		
	}
};