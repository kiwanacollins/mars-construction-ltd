(function ($) {
	"use strict";
	var body = $("body");
	body.attr("data-sidebar-style", "full");
	if (!body.attr("data-layout")) {
		body.attr("data-layout", "vertical");
	}
})(jQuery);
