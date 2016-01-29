$(document).ready(function () {

	var step = {
		section: {
            "select-type": {
                "next": "sub-step",
                "prev": ""
            },
            "sub-step": {
            	"next": {
            		"tt-chung": {
		                "next": "tt-chitiet",
		                "prev": ""
		            },
		            "tt-chitiet": {
		                "next": "hinh-anh",
		                "prev": "tt-chung"
		            },
		            "hinh-anh": {
		                "next": "tien-ich",
		                "prev": "tt-chung"
		            },
		            "tien-ich": {
		                "next": "tt-lienhe",
		                "prev": "hinh-anh"
		            },
		            "tt-lienhe": {
		                "next": "",
		                "prev": "tien-ich"
		            }
            	},
            	"prev": "select-type"
            }
            
        },
		wrap: $('.section'),
		wrapNavi: $('.fixed-prev-next'),
		btnNext: $('#next-screen'),
		btnBack: $('#back-screen'),
		next: '',
		prev: '',
		current: '',
		objSub: {},
		init: function () {
			step.wrap.each(function () {
				var _this = $(this);

				if ( _this.hasClass('active') ) {
					step.current = _this.data('section');
					for ( var i in step.section ) {
						if ( i == step.current ) {
							step.next = step.section[i].next;
							step.prev = step.section[i].prev;
						}
					}
					return false;
				}
			});
			step.btnNext.on('click', step.nextSection);
			step.btnBack.on('click', step.prevSection);
		},
		show: function () {
			step.getSection();
			$('.'+step.next).addClass('in active');
			$('.'+step.prev).addClass('out');
		},
		getSection: function () {
			if ( typeof step.section[step.next] ) {
				step.objSub = step.section[step.next];
				
			}
			for ( var i in step.section ) {
				
			}
		},
		selectItemDone: function (item) {
			if ( item.data('flag') ) { // chon xong chủ nhà hoặc môi giới
				step.wrapNavi.show();
				step.show(step.next, step.current);
			}
		},
		nextSection: function (e) {
			e.preventDefault();
			
		},
		prevSection: function (e) {
			e.preventDefault();
			
		}
	};

	$('.frm-radio').radio({
		done: step.selectItemDone
	});

	step.init();
});