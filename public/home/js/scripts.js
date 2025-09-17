// all functions ------------------
function initOutdoor() {
    "use strict";
	$(".loader").fadeOut(300, function() {
		$("#main").animate({
			opacity: "1"
		}, 900);
	});
    initgalheight();
	// css ------------------
	$(".hero-title , .team-social , .srtp ul , .slide-title , .scroll-page-nav , .count-folio").addClass("cdc");
    function a() {
        $(" .fullheight-carousel .item").css({
            height: $(".fullheight-carousel").outerHeight(true)
        });
        $(".hero-slider .item").css({
            height: $(".hero-slider").outerHeight(true)
        });
        $(".slideshow-item .item").css({
            height: $(".slideshow-item ").outerHeight(true)
        });
        $("#content-sidebar").css({
            top: $("header").outerHeight(true)
        });
        $(" #portfolio_horizontal_container .portfolio_item img , .port-desc-holder").css({
            height: $(".p_horizontal_wrap").outerHeight(true)  - 120 + "px" 
        });
        $(".mm").css({
            "padding-top": $("header").outerHeight(true)
        });
    }
    a();
    $(window).on("resize", function() {
        a();
    });
    $(".show-hidden-info").on("click", function() {
        $(this).toggleClass("vhi");
        $(this).closest(".resume-box").find(".hidden-info").slideToggle(500);
    });
    function d() {
        var a = document.querySelectorAll(".intense");
        Intense(a);
    }
    d();
	//swiper  ------------------
    var f = new Swiper("#horizontal-slider", {
        speed: 1900,
        loop: true,
        grabCursor: true,
         
             navigation: {
                nextEl: '.hor a.arrow-right',
                prevEl: '.hor a.arrow-left',
            },
            pagination: {
                el: '.pagination',
                clickable: true,
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false
            },
    });
	// popups  ------------------
        $(".image-popup").lightGallery({
            selector: "this",
            cssEasing: "cubic-bezier(0.25, 0, 0.25, 1)",
            download: false,
            counter: false
        });
        $(".popup-gallery").lightGallery({
            selector: "a",
            cssEasing: "cubic-bezier(0.25, 0, 0.25, 1)",
            download: false,
            loop: true,
            counter: false
        });	
	
    $(".hide-column").bind("click", function() {
        $(".not-vis-column").animate({
            right: "-100%"
        }, 500);
    });
    $(".show-info").bind("click", function() {
        $(".not-vis-column").animate({
            right: "0"
        }, 500);
    });
	// owl carousel  ------------------
    var b = $(".full-width");
    b.owlCarousel({
        navigation: false,
        slideSpeed: 500,
        singleItem: true,
        pagination: true
    });
    $(".fullwidth-slider-holder a.next-slide").on("click", function() {
        $(this).closest(".fullwidth-slider-holder").find(b).trigger("owl.next");
    });
    $(".fullwidth-slider-holder  a.prev-slide").on("click", function() {
        $(this).closest(".fullwidth-slider-holder").find(b).trigger("owl.prev");
    });
    var heroslides = $(".hero-slider");
    var synksldes = $(".hero-slider.synkslider");
    heroslides.each(function(index) {
        var auttime = eval($(this).data("attime"));
        var rtlt = eval($(this).data("rtlt"));
        $(this).owlCarousel({
            items: 1,
            loop: true,
            margin: 0,
            autoplay: true,
            autoplayTimeout: auttime,
            autoplayHoverPause: false,
            autoplaySpeed: 1600,
            rtl: rtlt
        });
    });
    synksldes.on("change.owl.carousel", function(a) {
        synkslider2.trigger("to.owl.carousel", [ a.item.index, 10, true ]);
    });
    var auttime2 = $(".hero-text").data("attime");
    var synkslider2 = $(".hero-text");
    synkslider2.owlCarousel({
        loop: true,
        margin: 0,
        nav: false,
        items: 1,
        dots: false,
        animateOut: "fadeOut",
        startPosition: 1,
        autoHeight: true,
        autoplay: true,
        autoplayTimeout: auttime2,
        autoplayHoverPause: false,
        autoplaySpeed: 1600
    });
    var customSlider = $(".custom-slider");
    customSlider.owlCarousel({
        loop: true,
        margin: 0,
        nav: false,
        items: 1
    });
    $(".custom-slider-holder a.next-slide").on("click", function() {
        $(this).closest(".custom-slider-holder").find(customSlider).trigger("next.owl.carousel");
    });
    $(".custom-slider-holder a.prev-slide").on("click", function() {
        $(this).closest(".custom-slider-holder").find(customSlider).trigger("prev.owl.carousel");
    });
    var slsl = $(".slideshow-item");
    slsl.owlCarousel({
        loop: true,
        margin: 0,
        nav: false,
        items: 1,
		animateOut: 'fadeOut',
    	animateIn: 'fadeIn',
        autoplay: true,
        autoplayTimeout: 4e3,
        autoplayHoverPause: false,
        autoplaySpeed: 3600
    });
    var testiSlider = $(".testimonials-slider");
    testiSlider.owlCarousel({
        loop: true,
        margin: 0,
        nav: false,
        items: 1,
        dots: true
    });
    $(".testimonials-slider-holder a.next-slide").on("click", function() {
        $(this).closest(".testimonials-slider-holder").find(testiSlider).trigger("next.owl.carousel");
    });
    $(".testimonials-slider-holder a.prev-slide").on("click", function() {
        $(this).closest(".testimonials-slider-holder").find(testiSlider).trigger("prev.owl.carousel");
    });
    $(".servicses-holder li").hover(function() {
        var a = $(this).data("bgscr");
        $(".bg-ser").css("background-image", "url(" + a + ")");
    });
    $(".scroll-page-nav  ul").singlePageNav({
        filter: ":not(.external)",
        updateHash: false,
        offset: 70,
        threshold: 120,
        speed: 1200,
        currentClass: "act-link"
    });
	// isotope  ------------------
    function n() {
        if ($(".gallery-items").length) {
            var a = $(".gallery-items").isotope({
                singleMode: true,
                columnWidth: ".grid-sizer, .grid-sizer-second, .grid-sizer-three",
                itemSelector: ".gallery-item, .gallery-item-second, .gallery-item-three",
                transformsEnabled: true,
                transitionDuration: "700ms"
            });
            a.imagesLoaded(function() {
                a.isotope("layout");
            });
            $(".gallery-filters").on("click", "a.gallery-filter", function(b) {
                b.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.gallery-items').offset().top - 90
                }, 600);
                var c = $(this).attr("data-filter");
                setTimeout(function () {
                    a.isotope({
                        filter: c
                    });
                }, 700);				
                $(".gallery-filters a.gallery-filter").removeClass("gallery-filter-active");
                $(this).addClass("gallery-filter-active");
                return false;
            });
            a.isotope("on", "layoutComplete", function(a, b) {
                var c = a.length;
                $(".num-album").html(c);
            });
        }
        var b = {
            touchbehavior: true,
            cursoropacitymax: 1,
            cursorborderradius: "0",
            background: "#eee",
            cursorwidth: "10px",
            cursorborder: "0px",
            cursorcolor: "#292929",
            autohidemode: false,
            bouncescroll: false,
            scrollspeed: 120,
            mousescrollstep: 90,
            grabcursorenabled: true,
            horizrailenabled: true,
            preservenativescrolling: true,
            cursordragontouch: true,
            railpadding: {
                top: 0,
                right: 0,
                left: 0,
                bottom: 0
            }
        };
        $(".p_horizontal_wrap").niceScroll(b);
        var c = $("#portfolio_horizontal_container");
        c.imagesLoaded(function(a, d, e) {
            var f = {
                itemSelector: ".portfolio_item",
                layoutMode: "packery",
                packery: {
                    isHorizontal: true,
                    gutter: 0
                },
                resizable: true,
                transformsEnabled: true,
                transitionDuration: "700ms"
            };
            var g = {
                itemSelector: ".portfolio_item",
                layoutMode: "packery",
                packery: {
                    isHorizontal: false,
                    gutter: 0
                },
                resizable: true,
                transformsEnabled: true,
                transitionDuration: "700ms"
            };
            if ($(window).width() < 768) {
                c.isotope(g);
                c.isotope("layout");
                if ($(".p_horizontal_wrap").getNiceScroll()) $(".p_horizontal_wrap").getNiceScroll().remove();
            } else {
                c.isotope(f);
                c.isotope("layout");
                $(".p_horizontal_wrap").niceScroll(b);
            }
            $(".gallery-filters").on("click", "a", function(a) {
                a.preventDefault();
                var b = $(this).attr("data-filter");
				 $('.p_horizontal_wrap').animate({scrollLeft: 2}, 500);
				 setTimeout(function () {
					c.isotope({
						filter: b
					});
				}, 900);
                $(".gallery-filters a").removeClass("gallery-filter_active");
                $(this).addClass("gallery-filter_active");
            });
            c.isotope("on", "layoutComplete", function(a, b) {
                var c = a.length;
                $(".num-album").html(c);
            });
        });
    }
    var j = $(".portfolio_item , .gallery-item").length;
    $(".all-album , .num-album").html(j);
    n();
    $(".portfolio_item a").on("click", function() {
        var a = $(this).attr("href");
        window.location.href = a;
        return false;
    });
    $(".filter-button").on("click", function() {
        $(".hid-filter").fadeToggle(500);
        $(".filter-button i").toggleClass("roticon");
    });
	//  contact form  ------------------
    $("#contactform").submit(function() {
        var a = $(this).attr("action");
        $("#message").slideUp(750, function() {
            $("#message").hide();
            $("#submit").attr("disabled", "disabled");
            $.post(a, {
                name: $("#name").val(),
                email: $("#email").val(),
                comments: $("#comments").val()
            }, function(a) {
                document.getElementById("message").innerHTML = a;
                $("#message").slideDown("slow");
                $("#submit").removeAttr("disabled");
                if (null != a.match("success")) $("#contactform").slideDown("slow");
            });
        });
        return false;
    });
    $("#contactform input, #contactform textarea").keyup(function() {
        $("#message").slideUp(1500);
    });
	//  other functions   ------------------
    function showHidDes() {
        $(".show-hid-content").removeClass("ishid");
        $(".hidden-column").animate({
            left: "90px",
            opacity: 1
        }, 500);
        $(".anim-holder").animate({
            left: "450px"
        }, 500);
    }
    function hideHidDes() {
        $(".show-hid-content").addClass("ishid");
        $(".hidden-column").animate({
            left: "-450px",
            opacity: 0
        }, 500);
        $(".anim-holder").animate({
            left: "0"
        }, 500);
    }
    $(".show-hid-content").on("click", function() {
        if ($(this).hasClass("ishid")) showHidDes(); else hideHidDes();
    });
    $(window).on("scroll", function() {
        if ($(this).scrollTop() > 300) $(".to-top").addClass("vistotop"); else $(".to-top").removeClass("vistotop");
    });
    $(".to-top").on("click", function() {
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
    });
    $(".custom-scroll-link").on("click", function() {
        var a = 70;
        if (location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") || location.hostname == this.hostname) {
            var b = $(this.hash);
            b = b.length ? b : $("[name=" + this.hash.slice(1) + "]");
            if (b.length) {
                $("html,body").animate({
                    scrollTop: b.offset().top - a
                }, {
                    queue: false,
                    duration: 1200,
                    easing: "easeInOutExpo"
                });
                return false;
            }
        }
    });
    $(".fix-box").scrollToFixed({
        marginTop: 90,
        minWidth: 1036
    });
    var gR = $(".gallery_horizontal"), w = $(window);
    function initGalleryhorizontal() {
        var a = $(window).height(), c = $("header").outerHeight(), d = $("footer").outerHeight(), e = $("#gallery_horizontal");
        e.find("img").css("height", a - c - d);
        if (gR.find(".owl-stage-outer").length) {
            gR.trigger("destroy.owl.carousel");
            gR.find(".horizontal_item").unwrap();
        }
        if (w.width() > 1036) gR.owlCarousel({
            autoWidth: true,
            margin: 4,
            items: 3,
            smartSpeed: 1300,
            loop: true,
            nav: false,
            dots: false,
            onInitialized: function() {
                gR.find(".owl-stage").css({
                    height: a - c - d,
                    overflow: "hidden"
                });
            }
        });
    }
    if (gR.length) {
        initGalleryhorizontal();
        w.on("resize.destroyhorizontal", function() {
            setTimeout(initGalleryhorizontal, 150);
        });
    }
	if (navigator.appVersion.indexOf("Win")!=-1) {	
		var timestamp_mousewheel = 0;  
		gR.on("mousewheel", ".owl-stage", function(a) {
			var d = new Date();
			if((d.getTime() - timestamp_mousewheel) > 1000){  
				timestamp_mousewheel = d.getTime();
			if (a.deltaY < 0) gR.trigger("next.owl"); else gR.trigger("prev.owl");
				a.preventDefault();
			}
		});
	}	
    $(".resize-carousel-holder a.next-slide").on("click", function() {
        $(this).closest(".resize-carousel-holder").find(gR).trigger("next.owl.carousel");
    });
    $(".resize-carousel-holder a.prev-slide").on("click", function() {
        $(this).closest(".resize-carousel-holder").find(gR).trigger("prev.owl.carousel");
    });
	// team  ------------------	
    $(".team-box").hover(function() {
        $(this).find("ul.team-social").fadeIn();
        $(this).find(".team-social a").each(function(a) {
            var b = $(this);
            setTimeout(function() {
                b.animate({
                    opacity: 1,
                    top: "0"
                }, 400);
            }, 150 * a);
        });
    }, function() {
        $(this).find(".team-social a").each(function(a) {
            var b = $(this);
            setTimeout(function() {
                b.animate({
                    opacity: 0,
                    top: "50px"
                }, 400);
            }, 150 * a);
        });
        setTimeout(function() {
            $(this).find("ul.team-social").fadeOut();
        }, 150);
    });
	// counter  ------------------	
    var $i = 1;
    $(document.body).on("appear", ".stats", function(a) {
        if (1 === $i) stats(2600);
        $i++;
    });
    function number(a, b, c, d) {
        if (d) {
            var e = 0;
            var f = parseInt(d / a);
            var g = setInterval(function() {
                if (e - 1 < a) c.html(e); else {
                    c.html(b);
                    clearInterval(g);
                }
                e++;
            }, f);
        } else c.html(b);
    }
    function stats(a) {
        $(".stats .num").each(function() {
            var b = $(this);
            var c = b.attr("data-num");
            var d = b.attr("data-content");
            number(c, d, b, a);
        });
    }
    $(".animaper").appear();
	// video  ------------------	
     //   Video------------------	
    if ($(".video-holder-wrap").length > 0) {
        function videoint() {
           var w = $(".background-vimeo").data("vim"),
                bvc = $(".background-vimeo"),
                bvmc = $(".media-container"),
                bvfc = $(".background-vimeo iframe "),
                vch = $(".video-container");
            bvc.append('<iframe src="//player.vimeo.com/video/' + w + '?background=1"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen ></iframe>');
            $(".video-holder").height(bvmc.height());
            if ($(window).width() > 1024) {
                if ($(".video-holder").length > 0)
                    if (bvmc.height() / 9 * 16 > bvmc.width()) {
                        bvfc.height(bvmc.height()).width(bvmc.height() / 9 * 16);
                        bvfc.css({
                            "margin-left": -1 * $("iframe").width() / 2 + "px",
                            top: "-75px",
                            "margin-top": "0px"
                        });
                    } else {
                        bvfc.width($(window).width()).height($(window).width() / 16 * 9);
                        bvfc.css({
                            "margin-left": -1 * $("iframe").width() / 2 + "px",
                            "margin-top": -1 * $("iframe").height() / 2 + "px",
                            top: "50%"
                        });
                    }
            } else if ($(window).width() < 760) {
                $(".video-holder").height(bvmc.height());
                bvfc.height(bvmc.height());
            } else {
                $(".video-holder").height(bvmc.height());
                bvfc.height(bvmc.height());
            }
            vch.css("width", $(window).width() + "px");
            vch.css("height", Number(720 / 1280 * $(window).width()) + "px");
            if (vch.height() < $(window).height()) {
                vch.css("height", $(window).height() + "px");
                vch.css("width", Number(1280 / 720 * $(window).height()) + "px");
            }
        }
        videoint();
    }
	//  Share  ------------------	
	$(".share-container").share({
		networks: ['facebook', 'pinterest', 'twitter', 'linkedin']
	});
    function hideShare() {
        $(".show-share").addClass("isShare");
        $(".share-container a").each(function(a) {
            var b = $(this);
            setTimeout(function() {
                b.animate({
                    opacity: 0
                }, 500);
            }, 120 * a);
        });
        setTimeout(function() {
            $(".share-container ").removeClass("visshare");
        }, 400);
    }
    function showShare() {
        $(".show-share").removeClass("isShare");
        $(".share-container ").addClass("visshare");
        setTimeout(function() {
            $(".share-container a").each(function(a) {
                var b = $(this);
                setTimeout(function() {
                    b.animate({
                        opacity: 1
                    }, 500);
                }, 120 * a);
            });
        }, 400);
    }
    $(".show-share").on("click", function(a) {
        a.preventDefault();
        if ($(".show-share").hasClass("isShare")) showShare(); else hideShare();
    });
	//  menu    ------------------
	$(".nav-holder").addClass("main-menu");
     $(".nav-button-holder").on("click", function() {
         $(".main-menu").toggleClass("vismobmenu");
     });
     function mobMenuInit() {
         var ww = $(window).width();
         if (ww < 1036) {
             $(".menusb").remove();
             $(".main-menu").removeClass("nav-holder");
             $(".main-menu nav").clone().addClass("menusb").appendTo(".main-menu");
             $(".menusb").menu();
         } else {
             $(".menusb").remove();
             $(".main-menu").addClass("nav-holder");
         }
     }
     mobMenuInit();
     //   css ------------------
     $(window).on("resize", function() {
         mobMenuInit();
     });
     $("#menu").menu();
 }
//  Parralax  ------------------
function initparallax() {
        var b = $(".content");
        b.find("[data-top-bottom]").length > 0 && b.waitForImages(function() {
            s = skrollr.init();
            s.destroy();
            skrollr.init({
                forceHeight: !1,
                easing: "outCubic",
                mobileCheck: function() {
                    return !1;
                }
            });
        });
}
    initparallax();
function initgalheight() {
    var a = $(window).height(), b = $("header").outerHeight(), c = $("footer").outerHeight(), d = $(".port-subtitle-holder").outerHeight(), e = $(".p_horizontal_wrap");
    e.css("height", a - b - c);
    $(" #portfolio_horizontal_container .portfolio_item img , .port-desc-holder").css({
        height: $(".p_horizontal_wrap").outerHeight(true) - d
    });
}
document.addEventListener('gesturestart', function (e) {
    e.preventDefault();
});  
//   Init all fucntions  ------------------
$(document).ready(function() {
    initOutdoor();
});

$(document).ready(function(){
  var Gid = localStorage.getItem("guest_id");
if (!Gid) {
    var timestamp = Date.now();
    localStorage.setItem("guest_id", timestamp);
    Gid = timestamp;
}

$(document).ready(function () {
    var guestId = Gid;

    $.ajax({
        url: "/guestId/" + guestId,
        type: "GET",
        dataType: "json",
        success: function (response) {
            // Handle success response here
        },
        error: function (xhr, status, error) {
            // Handle error here
        },
    });
});


});