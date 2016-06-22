/*!
 * author: Heminei
 * site: https://github.com/heminei/jquery-hemi-intro
 * email: heminei@heminei.com
 * v1.2
 */
(function ($) {
	var pluginName = "hemiIntro";

	$[pluginName] = function (userOptions) {
		var plugin = this;

		var defaultOptions = {
			debug: false,
			steps: [],
			startFromStep: 0,
            backdrop: {
                element: $("<div>"),
                class: "hemi-intro-backdrop"
            },
            popover: {
                template: '<div class="popover hemi-intro-popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
            },
            buttons: {
                holder: {
                    element: $("<div>"),
                    class: "hemi-intro-buttons-holder"
                },
                next: {
                    element: $("<a href='javascript:void(0)' class='btn-tour btn'>Next</a>"),
                    class: "btn btn-primary"
                },
                noThanks: {
                	element: $("<a href='javascript:void(0)' class='btn-tour btn mgL-10'>No Thanks</a>"),
                    class: "btn btn-primary"
                },
                finish: {
                    element: $("<a href='javascript:void(0)' class='btn-tour btn'>Finish</a>"),
                    class: "btn btn-primary"
                }
            },
            welcomeDialog: {
                show: false,
                selector: "#myModal"
            },
            scroll: {
                anmationSpeed: 500
            },
            currentStep: {
                selectedClass: "hemi-intro-selected"
            },
			init: function (plugin) {

			},
			onLoad: function (plugin) {

			},
			onStart: function (plugin) {

			},
			onBeforeChangeStep: function (plugin, step) {

			},
			onAfterChangeStep: function (plugin, step) {

			},
			onShowModalDialog: function (plugin, modal) {

			},
			onHideModalDialog: function (plugin, modal) {

			},
			onComplete: function (plugin) {

			}
		};

		plugin.options = $.extend(true, defaultOptions, userOptions);
		plugin.options.init(plugin); //CALLBACK

		var currentIndex = plugin.options.startFromStep;
		var currentElement = null;
		var currentStep = null;

		plugin.backdrop = plugin.options.backdrop.element.clone().addClass(plugin.options.backdrop.class);

		plugin.options.onLoad(plugin); //CALLBACK

		plugin.start = function () {
			
			if ( parseInt($('#checkUserFirst').val()) ) return;

			plugin.options.onStart(plugin); //CALLBACK

			if (plugin.options.welcomeDialog.show) {
				var modal = $(plugin.options.welcomeDialog.selector);
				if (modal.length > 0) {
					modal.on('show.bs.modal', function (e) {
						plugin.options.onShowModalDialog(plugin, modal); //CALLBACK
					});
					modal.on('hidden.bs.modal', function (e) {
						plugin.options.onHideModalDialog(plugin, modal); //CALLBACK
						plugin.backdrop.appendTo("body");
						goToStep(currentIndex);
					});
					modal.modal("show");
				} else {
					debugLog(pluginName + ":", "Modal '" + plugin.options.welcomeDialog.selector + "' not found");
					plugin.backdrop.appendTo("body");
					goToStep(currentIndex);
				}
			} else {
				//plugin.backdrop.appendTo("body");
				goToStep(currentIndex);
				if ( plugin.options.steps[currentIndex].selector != '.dt-header' ) {
					$('.dt-header').append('<div class="bg-over"></div>');
				}
			}
		};

		plugin.next = function () {
			if (plugin.options.steps[currentIndex + 1]) {
				goToStep(currentIndex + 1);
			} else {
				plugin.finish();
			}
		};

		plugin.prev = function () {
			if (currentIndex - 1 < 0) {
				goToStep(currentIndex);
			} else {
				goToStep(currentIndex - 1);
			}
		};

		plugin.finish = function () {
			onFinish();
		};

		plugin.goToStep = function (index) {
			goToStep(index);
		};

		plugin.getCurrentStep = function () {
			return currentStep;
		};

		var goToStep = function (index) {
			if (plugin.options.steps[index]) {
				var step = plugin.options.steps[index];

				if ($(step.selector).length > 0) {
					removeCurrentStep();

					currentElement = $(step.selector);
					currentIndex = index;
					currentStep = step;

					if ( step.selector == "#map-wrap" ) {
						currentElement.parent().css({
							'z-index': 11
						});
					}

					plugin.backdrop.insertAfter(currentElement);

					plugin.options.onBeforeChangeStep(plugin, step); //CALLBACK

					currentElement.addClass(plugin.options.currentStep.selectedClass);

					scrollToElement(function () {
						var template = $(plugin.options.popover.template);
						var uniq = "id" + Math.random().toString(30).slice(2);
						var buttonsHolder = plugin.options.buttons.holder.element.clone().addClass(plugin.options.buttons.holder.class);
						var button;
						if (plugin.options.steps[index + 1]) {
							button = plugin.options.buttons.next.element.clone();
							button.addClass(plugin.options.buttons.next.class).addClass(uniq);
							buttonsHolder.append(button);
						} else {
							button = plugin.options.buttons.finish.element.clone();
							button.addClass(plugin.options.buttons.finish.class).addClass(uniq);
							buttonsHolder.append(button);
						}

						button = plugin.options.buttons.noThanks.element.clone();
						button.addClass(plugin.options.buttons.noThanks.class).addClass(uniq+' no-thanks');
						buttonsHolder.append(button);

						var content = $("<div>").append(step.content);

						if (step.showButtons !== false) {
							content.append(buttonsHolder.get(0).outerHTML);
						}

						currentElement.popover({
							content: content.get(0).outerHTML,
							html: true,
							trigger: 'manual',
							placement: step.placement,
							template: template.get(0).outerHTML
						}).popover('show');

						currentElement.on('shown.bs.popover', function () {
							plugin.options.onAfterChangeStep(plugin, step); //CALLBACK

							$("." + uniq).on("click", function () {
								if ( plugin.options.steps[index].selector == "#map-wrap" ) {
									$(plugin.options.steps[index].selector).parent().css({
										'z-index': 0
									});
								}else if ( plugin.options.steps[index].selector == ".dt-header" ) {
									$(plugin.options.steps[index].selector).append('<div class="bg-over"></div>');
								}

								if ( $(this).hasClass('no-thanks') ) {
									plugin.finish();
									$(".dt-header").find('.bg-over').remove();
									return;
								}

								if (plugin.options.steps[index + 1]) {
									plugin.next();
								} else {
									plugin.finish();
									$(".dt-header").find('.bg-over').remove();
								}
							});
						});
					});
				} else {
					debugLog(pluginName + ":", "Step element not found: ", step);
				}
			} else {
				debugLog(pluginName + ":", "Step not found");
			}
		};
		var removeCurrentStep = function () {
			if (currentElement !== null) {
				currentElement.removeClass(plugin.options.currentStep.selectedClass);
				currentElement.popover('destroy');
			}
		};
		var onFinish = function () {
			removeCurrentStep();
			plugin.backdrop.remove();
			plugin.options.onComplete(plugin); //CALLBACK
		};
		var scrollToElement = function (callback) {
			if (typeof callback != "function") {
				callback = $.noop();
			}
			if (currentStep.scrollToElement !== false) {
				var offsetTop = 20;
				if (currentStep.offsetTop) {
					offsetTop = currentStep.offsetTop;
				}
				var called = false;
				$('html, body').animate({
					scrollTop: currentElement.offset().top - offsetTop - 70
				}, plugin.options.scroll.anmationSpeed, function () {
					if (called === false) {
						callback();
						called = true;
					}
				});
			} else {
				callback();
			}
		};
		var debugLog = function () {
			if (plugin.options.debug) {
				console.log.apply(console, arguments);
			}
		};

		return plugin;
	};
})(jQuery);

$(document).ready(function () {
	var steps = '';
	if ( $(".search-wrap-home").length ) {
		steps = [
	                {
	                    selector: ".dt-header",
	                    placement: "bottom",
	                    content: "At the top you will find the HomeBar, this bar will always be visible to you, and will let you quickly navigate to all of Metvuong's key Features.",
	                },{
	                    selector: ".search-wrap-home",
	                    placement: "bottom",
	                    content: "<p class='mgB-10'>The main feature of the Metvuong homepage is our Search Bar. It will let you quickly select how you want to search for your property, whether it be through it's location (city, district, ward and street) or by which Development it belongs to.</p><p>If you know the MVID of your listing, you can also use this as a shortcut to take you to the listing that you want.</p>"
	                }
	            ]
	}else if ( $(".statis section").length ) {
		steps = [
	                {
	                    selector: ".statis section",
	                    placement: "left",
	                    content: "<p class='mgB-5'>Here you can clearly track the popularity of your listing, based on metrics such as the amount of views and favorites, as well as see this as a function of time.</p><p>You can also reach out to customers who may have searched for or favorited this listing, simply click on their account to send them a message</p>",
	                }
	            ]
	}else if ( $(".menuUser ul").length ) {
		steps = [
	                {
	                    selector: ".menuUser ul",
	                    placement: "right",
	                    content: "<p class='mgB-5'>The Dashboard is where you will find many of Metvuong's more advanced Features</p><p class='mgB-5'>Customizing your personal information</p><p class='mgB-5'>Update yourself on your listings status</p><p class='mgB-5'>Buy more Keys</p><p>Look at your notifications and messages.</p>"
	                },
	                {
	                    selector: "#list-all",
	                    placement: "left",
	                    content: "<p class='mgB-5'>Here is where you can post your listings.</p><p class='mgB-5'>At Metvuong.com we encourage detailed and accurate information, listings that fulfill this requirement will have a higher MV Score, which means that it is more likely to show up on a customers search.</p><p>Any information found to be false in the listing will lead to penalties on the listings score.</p>",
	                }
	            ]
	}else if ( $("#map-wrap").length ) {
		steps = [
	                {
	                    selector: ".toggle-search",
	                    placement: "bottom",
	                    content: "Welcome to the Metvuong Buy Page, this is functionally the same as the Metvuong Rent Page, here up top you can alter your search parameters, and the results will change dynamically.",
	                },{
	                	selector: "#map-wrap",
	                	placement: "right",
	                	content: "The map on the bottom let's you see where your potential listings lie, you can click on them to bring them up for closer inspection."
	                },{
	                	selector: ".wrap-listing",
	                	placement: "left",
	                	content: "The list on the right are the listings that are the closest to your search parameters, categorized by our MV algorithim to ensure the quality as well as relevancy to your listing."
	                }
	            ]
	}else if ( $(".type-payment").length ) {
		steps = [
	                {
	                    selector: ".type-payment",
	                    placement: "left",
	                    content: "Metvuong.com caters to a variety of payment system to maximize your convenience, simply select the amount of keys you want to buy, and your method of payment. THe more you buy, the cheaper it is.",
	                }
	            ]
	}

	var intro = $.hemiIntro({
        debug: false,
        steps: steps
    });

    intro.start();

});