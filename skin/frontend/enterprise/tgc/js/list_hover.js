(function ($) {
	$.fn.link_hover = function() {
		if (typeof($(this)) === 'undefined') {
			return;
		} else {
			$(this).each(function() {
				$(this).css('cursor', 'pointer');			
				$(this).click(function(){
					var link = $(this).find('.list_data_title a');
					var href = link.attr('href');
					if (typeof(href) !== 'undefined') {
						window.location = href;
					}
				});
			})
		}
	};
}(jQuery));

jQuery(document).ready(function(){
	jQuery(".list_events_content li").link_hover();
});