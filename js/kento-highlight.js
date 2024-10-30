jQuery(document).ready(function($){
	'use strict';
	$('ul li.tabs').click(function(event){
		jQuery('.active').removeClass('active');
		jQuery(this).addClass('active');
	});

	var divs = jQuery("#tab1, #tab2, #tab3");
		// Show chosen div, and hide all others
		jQuery("li.tabs a").click(function () {
		jQuery(divs).hide();
		jQuery("#" + jQuery(this).attr("class")).slideDown(500);
	});
});