(function($){
    (function($) {
        "use strict";

		$(document).ready(function() {

			$('.toggle-btn').click(function(){
				$('.offcanvas').toggleClass('open');
			});

			$('.edvik_navbar_user a.nav-link.d-inline-block.popover-region-toggle.position-relative').click(function(){
				$('.offcanvas').toggleClass('open');
			});
		
			// Close the offcanvas menu when clicking on the overlay
			$('.offcanvas-overlay').click(function(){
				$('.offcanvas').removeClass('open');
			});
		
			// Close the offcanvas menu when clicking on a menu item (optional)
			$('.responsive-navbar .offcanvas-header .close-btn').click(function(){
				$('.offcanvas').removeClass('open');
			});

            var current_site_url = $(".navbar-area .navbar-brand").attr("href");

			// Support Moodle MultiLang
			var langValue = $("html").attr("lang");
			$('.multilang').each(function(){
				var currentLangValue = $(this).attr("lang");
				if(langValue != currentLangValue) {
					$(this).addClass('d-none');
				}
			});
		
			if (current_site_url != 'http://localhost:8888/moodle/edvik-4.5/') {
				// $('a').each(function () {
				// 	var url = $(this).attr("href");
				// 	if (url && url.includes("http://localhost:8888/moodle/edvik-4.5/")) {
				// 		url = url.replace("http://localhost:8888/moodle/edvik-4.5/", current_site_url);
				// 		$(this).attr('href', url);
				// 	}
				// });
				$('a').each(function () {
					var url = $(this).attr("href");

					// Check if the URL contains the old base and replace it
					if (url && url.includes("http://localhost:8888/moodle/edvik/")) {
						url = url.replace("http://localhost:8888/moodle/edvik/", "https://moodlepoc.beyondkey.co/");
						$(this).attr('href', url);
					}
				});
			
				$('img').each(function () {
					var url = $(this).attr("src");
					//if (url && url.includes("http://localhost:8888/moodle/edvedvik-4.5ik/")) {
						url = url.replace("http://localhost:8888/moodle/edvik/theme/edvik/", "https://moodlepoc.beyondkey.co/theme/edvik/");
						$(this).attr('src', url);
					//}
				});
			}

			// Sticky, Go To Top JS
			$(window).on('scroll', function() {
				// Go To Top JS
				var scrolled = $(window).scrollTop();
				if (scrolled > 300) $('#backtotop').addClass('active');
				if (scrolled < 300) $('#backtotop').removeClass('active');
			});

			// Click Event JS
			$('#backtotop').on('click', function() {
				$("html, body").animate({ scrollTop: "0" }, 50);
			});

			$('.category-menu > li').click(function(e) {
				$(this).find('ul').toggleClass('active');
			});
            
            $("body.role-standard:not(.path-contentbank):not(#page-contentbank) .bottom-region-main-box").each(function() {
                if (!$(this).find(".block").length && !$(this).find(".edvik-main").text().trim().length) {
                $(".bottom-region-main-box, .bottom-region-main-box #page-content").css({
                    'padding-top': '0',
                    'margin-top': '0',
                    'padding-bottom': '0px !important',
                });
                $(".edvik-main").remove();
                }
            });

            $(".dashbord_nav_list > a:first-child").prepend("<i class='bx bxs-dashboard' ></i>");
            $(".dashbord_nav_list > a:nth-child(2)").prepend("<i class='bx bx-user' ></i>");
            $(".dashbord_nav_list > a:nth-child(3)").prepend("<i class='bx bxs-graduation' ></i>");
            $(".dashbord_nav_list > a:nth-child(4)").prepend("<i class='bx bx-chat' ></i>");
            $(".dashbord_nav_list > a:nth-child(5)").prepend("<i class='bx bx-cog' ></i>");
            $(".dashbord_nav_list > a:nth-child(6)").prepend("<i class='bx bx-log-out' ></i>");
            $(".dashbord_nav_list > a:nth-child(7)").prepend("<i class='bx bx-user-plus' ></i>");
            $(".dashbord_nav_list > a:nth-child(8)").prepend("<i class='bx bx-log-out'></i>");
            $(".dashbord_nav_list > a").each(function() {
            $(this).removeClass("dropdown-item").wrap("<li></li>");
            });
            $(".dashbord_nav_list > li").wrapAll("<ul></ul>");

			$('#message-user-button .icon.iconsmall').replaceWith("<i class='bx bx-envelope'></i>");
			$('#toggle-contact-button .icon.iconsmall').replaceWith("<i class='bx bx-add-to-queue'></i>");

           // Popup Video JS
			$('.popup-youtube, .popup-vimeo, .popup-video').magnificPopup({
				disableOn: 300,
				type: 'iframe',
				mainClass: 'mfp-fade',
				removalDelay: 160,
				preloader: false,
				fixedContentPos: false,
			});
			
			$(".popover-region-notifications").click(function() {
				$(".popover-region-notifications").toggleClass('collapsed');
			});
        });

		// Hero Slider
		var swiper = new Swiper(".hero-course-slider", {
			speed: 1200,
			spaceBetween: 0,
			loop: false,
			effect: "fade",
			fadeEffect: { 
				crossFade: true
			},
			autoHeight: true,
			autoplay: {
				delay: 3500,
				disableOnInteraction: true
			},
			pagination: {
				clickable: true,
				el: ".hero-pagination",
			},
		});
		var swiper = new Swiper(".hero_course-slider", {
			speed: 1200,
			loop: false,
			autoHeight: true,
			navigation: {
				nextEl: ".hero-next",
				prevEl: ".hero-prev",
			},
			breakpoints: {
				0: {
					slidesPerView: 1,
					spaceBetween: 10
				},
				768: {
					slidesPerView: 2,
					spaceBetween: 20
				},
				992: {
					slidesPerView: 2,
					spaceBetween: 20
				},
				1200: {
					slidesPerView: 1.4,
					spaceBetween: 20
				},
				1400: {
					slidesPerView: 1.45,
					spaceBetween: 24
				},
				1600: {
					slidesPerView: 1.5,
					spaceBetween: 24
				},
				1900: {
					slidesPerView: 1.6,
					spaceBetween: 24
				}
			},
		});
	
		//Brand Slider
		var swiper = new Swiper(".brand-slider", {
			loop: false,
			speed: 15000,
			freemode: false,
			spaceBetween: 25,
			simulateTouch: false,
			autoplay: {
				delay: 1,
				disableOnInteraction: false
			},
			breakpoints:{
				0: {
					slidesPerView: 3
				},
				768: {
					slidesPerView: 3
				},
				992: {
					slidesPerView: 5
				},
				1200: {
					slidesPerView: 6
				}
			}
		});
	
		//Testimonial Slider
		var swiper = new Swiper(".testimonial-slider-one", {
			loop: false,
			speed: 1500,
			freemode: false,
			spaceBetween: 25,
			simulateTouch: false,
			autoplay: {
				delay: 5000,
				disableOnInteraction: false
			},
			breakpoints:{
				0: {
					slidesPerView: 1
				},
				768: {
					slidesPerView: 2
				},
				1600: {
					slidesPerView: 2,
					spaceBetween: 60
				}
			}
		});
		var swiper = new Swiper(".testimonial-slider-two", {
			speed: 1500,
			autoHeight: true,
			freemode: false,
			spaceBetween: 25,
			simulateTouch: false,
			centeredSlides: true,
			loop: true,
			autoplay: {
				delay: 3500,
				disableOnInteraction: false
			},
			navigation: {
				nextEl: ".testimonial-next",
				prevEl: ".testimonial-prev",
			},
			breakpoints:{
				0: {
					slidesPerView: 1
				},
				768: {
					slidesPerView: 2
				},
				1200: {
					slidesPerView: 3
				}
			}
		});
		var swiper = new Swiper(".testimonial-slider-three", {
			speed: 1500,
			autoHeight: true,
			spaceBetween: 25,
			pagination: {
				clickable: true,
				el: ".testimonial-navigation",
			},
			breakpoints:{
				0: {
					slidesPerView: 1
				},
				768: {
					slidesPerView: 2
				},
				1200: {
					slidesPerView: 3
				}
			}
		});
	
		//Event Slider
		var swiper = new Swiper(".event-slider", {
			speed: 1200,
			loop: false,
			autoHeight: true,
			navigation: {
				nextEl: ".event-next",
				prevEl: ".event-prev",
			},
			breakpoints: {
				0: {
					slidesPerView: 1,
					spaceBetween: 10
				},
				768: {
					slidesPerView: 2,
					spaceBetween: 20
				},
				992: {
					slidesPerView: 2,
					spaceBetween: 20
				},
				1200: {
					slidesPerView: 3,
					spaceBetween: 20
				},
				1600: {
					slidesPerView: 3,
					spaceBetween: 45
				}
			},
		});
	
		//Blog Slider
		var swiper = new Swiper(".blog-slider", {
			speed: 1200,
			spaceBetween: 24,
			loop: false,
			autoHeight: true,
			pagination: {
				clickable: true,
				el: ".blog-pagination",
			},
			breakpoints: {
				0: {
					slidesPerView: 1
				},
				768: {
					slidesPerView: 1
				},
				992: {
					slidesPerView: 1.6
				},
				1200: {
					slidesPerView: 2
				},
			},
		});
		var swiper = new Swiper(".blog-slider-two", {
			speed: 1200,
			spaceBetween: 24,
			loop: false,
			autoHeight: true,
			pagination: {
				clickable: true,
				el: ".blog-pagination",
			},
			breakpoints: {
				0: {
					slidesPerView: 1
				},
				768: {
					slidesPerView: 2
				},
				1200: {
					slidesPerView: 3
				},
			},
		});
	
		// Scrollcue
		scrollCue.init();
		
	})(window.jQuery);
}(jQuery));


// Offcanvas Responsive Menu
const list = document.querySelectorAll('.responsive-menu-list');
function accordion(e) {
	e.stopPropagation(); 
	if(this.classList.contains('active')){
		this.classList.remove('active');
	}
	else if(this.parentElement.parentElement.classList.contains('active')){
		this.classList.add('active');
	}
	else {
		for(i=0; i < list.length; i++){
			list[i].classList.remove('active');
		}
		this.classList.add('active');
	}
}
for(i = 0; i < list.length; i++ ){
	list[i].addEventListener('click', accordion);
}

// Quantity Counter
var resultEl = document.querySelector(".resultSet"),
plusMinusWidgets = document.querySelectorAll(".v-counter");
for (var i = 0; i < plusMinusWidgets.length; i++) {
	plusMinusWidgets[i].querySelector(".minusBtn").addEventListener("click", clickHandler);
	plusMinusWidgets[i].querySelector(".plusBtn").addEventListener("click", clickHandler);
}
function clickHandler(event) {
	// reference to the count input field
	var countEl = event.target.parentNode.querySelector(".count");
	if (event.target.className.match(/\bminusBtn\b/)) {
		 countEl.value = Number(countEl.value) - 1;
	} else if (event.target.className.match(/\bplusBtn\b/)) {
		 countEl.value = Number(countEl.value) + 1;
	}
	triggerEvent(countEl, "change");
};
function triggerEvent(el, type){
	if ('createEvent' in document) {
		// modern browsers, IE9+
		var e = document.createEvent('HTMLEvents');
		e.initEvent(type, false, true);
		el.dispatchEvent(e);
	} else {
		// IE 8
		var e = document.createEventObject();
		e.eventType = type;
		el.fireEvent('on'+e.eventType, e);
	}
}
function triggerEvent(el, type){
	if('createEvent' in document) {
		// modern browsers, IE9+
		var e = document.createEvent('HTMLEvents');
		e.initEvent(type, false, true);
		el.dispatchEvent(e);
	} else {
		// IE 8
		var e = document.createEventObject();
		e.eventType = type;
		el.fireEvent('on'+e.eventType, e);
	}
}


try {

// function to set a given theme/color-scheme
function setTheme(themeName) {
	localStorage.setItem('edvik_theme', themeName);
	document.documentElement.className = themeName;
}
// function to toggle between light and dark theme
function toggleTheme() {
	if (localStorage.getItem('edvik_theme') === 'theme-dark') {
		setTheme('theme-light');
	} else {
		setTheme('theme-dark');
	}
}
// Immediately invoked function to set the theme on initial load
(function () {
	if (localStorage.getItem('edvik_theme') === 'theme-dark') {
		setTheme('theme-dark');
		document.querySelector('.slider-btn').checked = false;
	} else {
		setTheme('theme-light');
	document.querySelector('.slider-btn').checked = true;
	}
})();

} catch (err) {}