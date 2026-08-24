(function ($) {
	"use strict";

	var $bar = $(".category-bar");
	if (!$bar.length) {
		return;
	}

	var originalOffsetTop = null;
	var barHeight = null;

	function captureOriginalPosition() {
		// Only measure while the bar is still in normal flow (not fixed)
		if (!$bar.hasClass("category-bar-fixed")) {
			originalOffsetTop = $bar.offset().top;
			barHeight = $bar.outerHeight();
		}
	}

	function updateStickyBar() {
		if (originalOffsetTop === null) {
			captureOriginalPosition();
		}

		var scrollPos = $(window).scrollTop();

		if (scrollPos >= originalOffsetTop) {
			if (!$bar.hasClass("category-bar-fixed")) {
				$bar.addClass("category-bar-fixed");
				$("body").css("padding-top", barHeight + "px");
			}
		} else {
			if ($bar.hasClass("category-bar-fixed")) {
				$bar.removeClass("category-bar-fixed");
				$("body").css("padding-top", "");
				captureOriginalPosition();
			}
		}
	}

	$(window).on("scroll resize", updateStickyBar);
	$(document).ready(function () {
		captureOriginalPosition();
		updateStickyBar();
	});
})(jQuery);
